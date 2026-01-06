# BÁO CÁO DỰ ÁN HỆ THỐNG QUẢN LÝ PHIM

---

## 📋 MỤC LỤC

1. [Quản lý Phim (Movies)](#1-quản-lý-phim-movies)
2. [Quản lý Episode](#2-quản-lý-episode)
3. [Quản lý Video Source](#3-quản-lý-video-source)
4. [Hệ thống Activity Logs](#4-hệ-thống-activity-logs)
5. [API Endpoints](#5-api-endpoints)

---

## 1. QUẢN LÝ PHIM (MOVIES)

### 1.1. THÊM PHIM MỚI (add)

#### A. Validate dữ liệu đầu vào

```php
public function add()
{
    if (isPost()) {
        $filter = filterData();
        $errors = [];
        
        // Validate tiêu đề
        if (empty(trim($filter['tittle']))) {
            $errors['tittle']['required'] = 'Tên phim bắt buộc phải nhập';
        } else {
            $tittle = trim($filter['tittle']);
            // Kiểm tra phim đã tồn tại
            $checkTittle = $this->moviesModel->getRowMovies("SELECT * FROM movies WHERE tittle = '$tittle'");
            if ($checkTittle >= 1) {
                $errors['tittle']['check'] = 'Phim đã tồn tại';
            }
        }
        
        // Validate các trường khác
        if (empty(trim($filter['original_title']))) {
            $errors['original_tittle']['required'] = 'Tên gốc bắt buộc phải nhập';
        }
        
        if (empty(trim($filter['slug']))) {
            $errors['slug']['required'] = 'Đường dẫn bắt buộc phải nhập';
        }
        
        if (empty(trim($filter['release_year']))) {
            $errors['release_year']['required'] = 'Năm phát hành bắt buộc phải nhập';
        }
```

#### B. Insert phim vào Database

```php
        if (empty($errors)) {
            $data = [
                'tittle' => $filter['tittle'],
                'original_tittle' => $filter['original_title'],
                'slug' => $filter['slug'],
                'release_year' => $filter['release_year'],
                'duration' => $filter['duration'],
                'country_id' => $filter['country_id'],
                'type_id' => $filter['type_id'],
                'imdb_rating' => $filter['imdb_rating'],
                'status_id' => $filter['status_id'],
                'poster_url' => $filter['poster_url'],
                'thumbnail' => $filter['thumbnail'],
                'img' => $filter['img'],
                'trailer_url' => $filter['trailer_url'],
                'description' => $filter['description'],
                'total_views' => $filter['total_views'],
                'created_at' => date('Y:m:d H:i:s')
            ];
            
            $checkInsert = $this->moviesModel->insertMovies('movies', $data);
            $movie_id = $this->moviesModel->getLastIdMovies();
```

#### C. Xử lý quan hệ Many-to-Many

```php
            // === XỬ LÝ THỂ LOẠI ===
            $genre_id = $filter['genre_id'];
            if (!empty($genre_id)) {
                foreach ($genre_id as $item) {
                    $dataGenre = [
                        'movie_id' => $movie_id,
                        'genre_id' => $item
                    ];
                    $this->moviesModel->insertMoviesGenres($dataGenre);
                }
            }
            
            // === XỬ LÝ DIỄN VIÊN (Cast) ===
            if (!empty($filter['cast_person']) && !empty($filter['cast_role'])) {
                $persons = $filter['cast_person'];
                $roles   = $filter['cast_role'];
                
                for ($i = 0; $i < count($persons); $i++) {
                    if (!empty($persons[$i]) && !empty($roles[$i])) {
                        $dataCast = [
                            'movie_id'  => $movie_id,
                            'person_id' => $persons[$i],
                            'role_id'   => $roles[$i]
                        ];
                        $this->personModel->insertMoviePerson($dataCast);
                    }
                }
            }
```

#### D. Ghi log hoạt động

```php
            // Ghi log
            $logData = [
                'tittle' => $data['tittle'],
                'slug' => $data['slug']
            ];
            
            $this->activityModel->log(
                $_SESSION['auth']['id'],
                'create',
                'movies',
                $movie_id,
                null,
                $logData
            );
            
            setSessionFlash('msg', 'Thêm phim mới thành công');
            setSessionFlash('msg_type', 'success');
            reload('/admin/film/list');
        }
    }
}
```

---

### 1.2. SỬA PHIM (edit)

#### A. Validate và chuẩn bị dữ liệu

```php
public function edit()
{
    if (isPost()) {
        $filter = filterData();
        $errors = [];
        
        // Validate (tương tự như add)
        if (empty(trim($filter['tittle']))) {
            $errors['tittle']['required'] = 'Tên phim bắt buộc phải nhập';
        }
        // ... các validation khác ...
        
        if (empty($errors)) {
            $dataUpdate = [
                'tittle' => $filter['tittle'],
                'original_tittle' => $filter['original_title'],
                'slug' => $filter['slug'],
                'release_year' => $filter['release_year'],
                'duration' => $filter['duration'],
                'poster_url' => $filter['poster_url'],
                'thumbnail' => $filter['thumbnail'],
                'img' => $filter['img'],
                'updated_at' => date('Y:m:d H:i:s')
            ];
```

#### B. Update và xử lý quan hệ

```php
            $idMovie = $filter['idMovie'];
            $conditionUpdate = 'id=' . $idMovie;
            
            // Lấy dữ liệu cũ để so sánh
            $oldData = $this->moviesModel->getOneMovie($conditionUpdate);
            
            // Update phim
            $checkUpdate = $this->moviesModel->updateMovies($dataUpdate, $conditionUpdate);
            
            if ($checkUpdate) {
                // Cập nhật thể loại: Xóa hết → Thêm lại
                $this->moviesModel->deleteMovieGenres("movie_id = $idMovie");
                
                if (isset($filter['genre_id']) && !empty($filter['genre_id'])) {
                    foreach ($filter['genre_id'] as $genreId) {
                        $dataGenre = [
                            'movie_id' => $idMovie,
                            'genre_id' => $genreId
                        ];
                        $this->moviesModel->insertMoviesGenres($dataGenre);
                    }
                }
                
                // Cập nhật diễn viên: Xóa hết → Thêm lại
                $this->personModel->deleteMoviePerson("movie_id = $idMovie");
                
                if (!empty($filter['cast_person']) && !empty($filter['cast_role'])) {
                    $persons = $filter['cast_person'];
                    $roles   = $filter['cast_role'];
                    
                    for ($i = 0; $i < count($persons); $i++) {
                        if (!empty($persons[$i]) && !empty($roles[$i])) {
                            $dataCast = [
                                'movie_id'  => $idMovie,
                                'person_id' => $persons[$i],
                                'role_id'   => $roles[$i]
                            ];
                            $this->personModel->insertMoviePerson($dataCast);
                        }
                    }
                }
```

#### C. So sánh thay đổi và ghi log

```php
                // So sánh thay đổi
                $changes = [];
                foreach ($dataUpdate as $key => $value) {
                    if ($oldData[$key] != $value) {
                        $changes[$key] = [
                            'from' => $oldData[$key],
                            'to' => $value
                        ];
                    }
                }
                
                // Ghi log nếu có thay đổi
                if (!empty($changes)) {
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'update',
                        'movies',
                        $idMovie,
                        $oldData,
                        $dataUpdate
                    );
                }
                
                setSessionFlash('msg', 'Cập nhật thành công');
                reload('/admin/film/list');
            }
        }
    }
}
```

---

## 2. QUẢN LÝ EPISODE

### 2.1. THÊM EPISODE (Gắn season_id và movie_id)

```php
public function add()
{
    // Lấy movie_id và season_id từ URL
    $filterGet = filterData('get');
    $idMovie = $filterGet['id'];
    $idSeason = (!empty($filterGet['season_id'])) ? $filterGet['season_id'] : null;

    if (isPost()) {
        $filter = filterData();
        
        // Kiểm tra chế độ: Single hoặc Bulk
        $isBulk = isset($filter['is_bulk']) && $filter['is_bulk'] == 'on';
        
        if ($isBulk) {
            // THÊM NHIỀU TẬP (Bulk Mode)
            $from = (int)$filter['episode_from'];
            $to = (int)$filter['episode_to'];
            
            for ($i = $from; $i <= $to; $i++) {
                $dataBulk = [
                    'movie_id'    => $idMovie,      // Gắn movie_id
                    'season_id'   => $idSeason,     // Gắn season_id
                    'name'        => 'Tập ' . $i,
                    'duration'    => $filter['duration'],
                    'server_name' => $filter['server_name'],
                    'created_at'  => date('Y:m:d H:i:s'),
                ];
                $this->episodeModel->insertEpisode($dataBulk);
            }
        } else {
            // THÊM 1 TẬP (Single Mode)
            $dataInsert = [
                'movie_id'    => $idMovie,
                'season_id'   => $idSeason,
                'name'        => $filter['name'],
                'duration'    => $filter['duration'],
                'server_name' => $filter['server_name'],
                'created_at'  => date('Y:m:d H:i:s'),
            ];
            $this->episodeModel->insertEpisode($dataInsert);
        }
        
        reload('/admin/episode?filter-movie-id=' . $idMovie);
    }
}
```

---

### 2.2. LẤY DANH SÁCH EPISODE (Filter theo season/movie)

```php
public function list()
{
    $filter = filterData();
    $movieId = '';
    $seasonId = '';
    $chuoiWhere = '';
    
    if (isGet()) {
        // Lấy filter từ URL
        if (isset($filter['filter-movie-id'])) {
            $movieId = $filter['filter-movie-id'];
        }
        if (isset($filter['season_id'])) {
            $seasonId = $filter['season_id'];
        }
        
        // Filter theo movie_id
        if (!empty($movieId)) {
            $chuoiWhere = !empty($chuoiWhere) ? $chuoiWhere . ' AND ' : ' WHERE ';
            $chuoiWhere .= "e.movie_id = '$movieId'";
        }
        
        // Filter theo season_id
        if (!empty($seasonId)) {
            $chuoiWhere = !empty($chuoiWhere) ? $chuoiWhere . ' AND ' : ' WHERE ';
            $chuoiWhere .= "e.season_id = '$seasonId'";
        }
    }
    
    // Query JOIN để lấy dữ liệu
    $getAllEpisode = $this->episodeModel->getAllEpisode("
        SELECT e.*, 
               m.tittle as movie_name, 
               e.name as episode_name, 
               s.name as season_name
        FROM episodes e
        LEFT JOIN movies m ON m.id = e.movie_id
        LEFT JOIN seasons s ON e.season_id = s.id
        $chuoiWhere
        ORDER BY m.created_at DESC
        LIMIT $offset, $perPage
    ");
    
    $data = [
        'getAllEpisode' => $getAllEpisode,
        'getAllMovies'  => $this->moviesModel->getAllMovies(),
        'getAllSeasons' => $this->seasonsModel->getAllSeason(),
    ];
    
    $this->renderView('/layout-part/admin/episode/list', $data);
}
```

---

## 3. QUẢN LÝ VIDEO SOURCE

### 3.1. THÊM VIDEO SOURCE (Tự động khi tạo Episode)

```php
// Trong EpisodeController::add()
$checkInsert = $this->episodeModel->insertEpisode($dataInsert);

if ($checkInsert) {
    // Lấy ID episode vừa tạo
    $idEpisode = $this->episodeModel->getLastIdEpisode();
    
    // Tự động tạo video source
    $dataVideoSource = [
        'episode_id'   => $idEpisode,
        'source_url'   => '',               // Trống, sẽ cập nhật sau
        'source_name'  => '',               // Tên server
        'voice_type'   => '',               // Lồng tiếng
        'subtitle_url' => '',               // Phụ đề
        'created_at'   => date('Y:m:d H:i:s'),
    ];
    
    $insertVideoSource = $this->sourceModel->insertVideoSource($dataVideoSource);
}
```

---

### 3.2. CẬP NHẬT VIDEO SOURCE

```php
// SourceController::edit()
public function edit()
{
    $filter = filterData();
    
    $data = [
        'source_url'   => $filter['source_url'],      // URL video (.m3u8, .mp4)
        'source_name'  => $filter['source_name'],     // Server 1, VIP...
        'voice_type'   => $filter['voice_type'],      // Vietsub, Thuyết minh
        'subtitle_url' => $filter['subtitle_url'],    // File phụ đề
        'updated_at'   => date('Y:m:d H:i:s'),
    ];
    
    $condition = 'id=' . $filter['id'];
    $oldData = $this->sourceModel->getOneSource($condition);
    
    $checkUpdate = $this->sourceModel->updateVideoSource($data, $condition);
    
    if ($checkUpdate) {
        // Ghi log thay đổi
        $this->activityModel->log(
            $_SESSION['auth']['id'],
            'update',
            'video_sources',
            $filter['id'],
            $oldData,
            $data
        );
        
        setSessionFlash('msg', 'Cập nhật nguồn video thành công');
        reload('/admin/source');
    }
}
```

---

## 4. HỆ THỐNG ACTIVITY LOGS

### 4.1. HÀM GHI LOG

```php
// Activity Model
public function log($userId, $action, $entityType, $entityId = null, $oldData = null, $newData = null)
{
    // Lấy thông tin IP và User Agent
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $data = [
        'user_id'     => $userId,
        'action'      => $action,           // create, update, delete, login
        'entity_type' => $entityType,       // movies, users, episodes...
        'entity_id'   => $entityId,
        'old_values'  => !empty($oldData) ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null,
        'new_values'  => !empty($newData) ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null,
        'ip_address'  => $ip,
        'user_agent'  => $userAgent,
        'created_at'  => date('Y:m:d H:i:s')
    ];
    
    return $this->insert('activity_logs', $data);
}
```

---

### 4.2. CÁCH SỬ DỤNG

#### Ghi log khi Thêm (Create)
```php
$this->activityModel->log(
    $_SESSION['auth']['id'],
    'create',
    'movies',
    $movie_id,
    null,
    $logData
);
```

#### Ghi log khi Cập nhật (Update)
```php
$this->activityModel->log(
    $_SESSION['auth']['id'],
    'update',
    'movies',
    $idMovie,
    $oldData,
    $dataUpdate
);
```

#### Ghi log khi Xóa (Delete)
```php
$this->activityModel->log(
    $_SESSION['auth']['id'],
    'delete',
    'movies',
    $id,
    $checkID,
    null
);
```

---

## 5. API ENDPOINTS

### 5.1. ROUTE API MAPPING (router/web.php)

```php
// Comment API
$router->post('/api/post-comment', 'CommentUserController@postCommentApi');
$router->post('/api/delete-comment', 'CommentUserController@deleteCommentApi');
$router->post('/api/reply-comment', 'CommentUserController@replyCommentApi');
$router->post('/api/like-comment', 'CommentUserController@likeCommentApi');

// Watch History API
$router->post('/api/save-history', 'WatchDetailController@saveHistory');

// Movie Detail API
$router->get('/api/get-episodes', 'MovieDetailController@getEpisodesApi');

// Favorite API
$router->post('/api/toggle-favorite', 'AccountController@toggleFavoriteApi');
$router->post('/api/toggle-favorite-actor', 'AccountController@toggleFavoriteActorApi');
```

---

### 5.2. SESSION CHECK MIDDLEWARE

```php
// Kiểm tra trong mỗi API endpoint
header('Content-Type: application/json');

if (!isset($_SESSION['auth']['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Bạn chưa đăng nhập',
        'code' => 401
    ]);
    exit;
}

$userId = $_SESSION['auth']['id'];
```

---

### 5.3. RESPONSE JSON CHUẨN

#### Success Response
```php
echo json_encode([
    'status' => 'success',
    'message' => 'Thành công',
    'data' => [
        'id' => 123,
        'name' => 'Example'
    ]
]);
```

#### Error Response
```php
echo json_encode([
    'status' => 'error',
    'message' => 'Mô tả lỗi',
    'code' => 400
]);
```

---

### 5.4. API LƯU TIẾN TRÌNH XEM PHIM

**Endpoint:** `POST /api/save-history`

```php
public function saveHistory()
{
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['auth']['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized: Bạn chưa đăng nhập'
        ]);
        return;
    }
    
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    
    if (isset($input['movie_id'], $input['episode_id'], $input['current_time'])) {
        $userId = $_SESSION['auth']['id'];
        $movieId = (int)$input['movie_id'];
        $episodeId = (int)$input['episode_id'];
        $seasonId = isset($input['season_id']) ? (int)$input['season_id'] : null;
        $currentTime = (float)$input['current_time'];
        
        $result = $this->watchHistoryModel->saveProgress(
            $userId, 
            $movieId, 
            $episodeId, 
            $seasonId, 
            $currentTime
        );
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Lưu tiến trình thành công'
            ]);
        }
    }
}
```

**Client-side JavaScript:**

```javascript
// Gửi request lưu progress mỗi 5 giây
setInterval(() => {
    fetch('/api/save-history', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            movie_id: movieId,
            episode_id: episodeId,
            season_id: seasonId,
            current_time: videoPlayer.currentTime
        })
    })
    .then(response => response.json())
    .then(data => console.log('Progress saved:', data));
}, 5000);
```

---

### 5.5. API TOGGLE FAVORITE

**Endpoint:** `POST /api/toggle-favorite`

```php
public function toggleFavoriteApi()
{
    header('Content-Type: application/json');
    
    if (empty($_SESSION['auth'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Vui lòng đăng nhập',
            'code' => 401
        ]);
        exit;
    }
    
    $filter = filterData();
    $movieId = (int)$filter['movie_id'];
    $userId = $_SESSION['auth']['id'];
    
    // Toggle favorite
    $action = $this->moviesModel->toggleFavorite($userId, $movieId);
    
    echo json_encode([
        'status' => 'success',
        'action' => $action,  // 'added' or 'removed'
        'message' => ($action === 'added') 
            ? 'Đã thêm vào yêu thích' 
            : 'Đã xóa khỏi yêu thích'
    ]);
}
```

---

### 5.6. THÔNG BÁO REALTIME

```php
// Lấy danh sách thông báo
public function showNotice()
{
    $userId = $_SESSION['auth']['id'];
    $notices = $this->notificationModel->getLatest($userId, 20);
    
    $data = ['notices' => $notices];
    $this->renderView('layout-part/client/user/thong_bao', $data);
}

// Tạo thông báo
$this->notificationsModel->createNotification([
    'user_id' => $targetUserId,
    'message' => "<b>$senderName</b> đã thích bình luận của bạn.",
    'type' => 'like',
    'link' => '/xem-phim/' . $movieSlug,
    'is_read' => 0,
    'created_at' => date('Y-m-d H:i:s')
]);
```

---

## 📊 CẤU TRÚC DATABASE

### Bảng `movies`
```sql
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tittle VARCHAR(255) NOT NULL,
    original_tittle VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    release_year INT,
    duration INT,
    country_id INT,
    type_id INT,
    status_id INT,
    poster_url VARCHAR(500),
    thumbnail VARCHAR(500),
    img VARCHAR(500),
    trailer_url VARCHAR(500),
    description TEXT,
    total_views INT DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME
);
```

### Bảng `episodes`
```sql
CREATE TABLE episodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT NOT NULL,
    season_id INT NULL,
    name VARCHAR(255) NOT NULL,
    duration INT,
    server_name VARCHAR(100),
    created_at DATETIME,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (season_id) REFERENCES seasons(id)
);
```

### Bảng `video_sources`
```sql
CREATE TABLE video_sources (
    id INT PRIMARY KEY AUTO_INCREMENT,
    episode_id INT NOT NULL,
    source_url VARCHAR(500),
    source_name VARCHAR(100),
    voice_type VARCHAR(50),
    subtitle_url VARCHAR(500),
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (episode_id) REFERENCES episodes(id)
);
```

### Bảng `activity_logs`
```sql
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## ✨ ĐẶC ĐIỂM KỸ THUẬT

### Quản lý Phim
✅ Validation đầy đủ  
✅ Xử lý quan hệ Many-to-Many (genres, cast)  
✅ Audit trail với Activity Logs  
✅ Upload và quản lý poster/thumbnail  

### Quản lý Episode
✅ Bulk add (thêm nhiều tập cùng lúc)  
✅ Linh hoạt với/không có season  
✅ Filter động theo movie/season  
✅ Tự động tạo video source  

### API System
✅ RESTful design  
✅ JSON response chuẩn  
✅ Session-based authentication  
✅ Error handling với try-catch  
✅ Real-time updates với AJAX  

### Security
✅ htmlspecialchars() để prevent XSS  
✅ Prepared statements (PDO)  
✅ Session validation  
✅ Role-based permissions  

---

**Ngày tạo:** 01/01/2026  
**Phiên bản:** 1.0  
**Tác giả:** Báo cáo dự án hệ thống quản lý phim
