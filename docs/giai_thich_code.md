# GIẢI THÍCH CODE - DỰ ÁN QUẢN LÝ PHIM

---

## 1. QUẢN LÝ PHIM (MOVIES)

### 🔹 Thêm Phim (add)

**Bước 1: Validate dữ liệu**
```php
if (empty(trim($filter['tittle']))) {
    $errors['tittle']['required'] = 'Tên phim bắt buộc phải nhập';
}
```
**Giải thích:** Kiểm tra xem tên phim có được nhập không. Nếu rỗng → lưu lỗi vào mảng `$errors`.

---

**Bước 2: Kiểm tra trùng lặp**
```php
$checkTittle = $this->moviesModel->getRowMovies("SELECT * FROM movies WHERE tittle = '$tittle'");
if ($checkTittle >= 1) {
    $errors['tittle']['check'] = 'Phim đã tồn tại';
}
```
**Giải thích:** Query database để kiểm tra xem phim cùng tên đã tồn tại chưa. Nếu có → báo lỗi.

---

**Bước 3: Insert phim vào DB**
```php
$data = [
    'tittle' => $filter['tittle'],
    'slug' => $filter['slug'],
    'created_at' => date('Y:m:d H:i:s')
];
$checkInsert = $this->moviesModel->insertMovies('movies', $data);
$movie_id = $this->moviesModel->getLastIdMovies();
```
**Giải thích:** 
- Tạo mảng `$data` chứa thông tin phim
- Gọi model để insert vào bảng `movies`
- Lấy ID phim vừa thêm (dùng cho bước sau)

---

**Bước 4: Xử lý thể loại (Many-to-Many)**
```php
foreach ($genre_id as $item) {
    $dataGenre = [
        'movie_id' => $movie_id,
        'genre_id' => $item
    ];
    $this->moviesModel->insertMoviesGenres($dataGenre);
}
```
**Giải thích:** 
- Vòng lặp qua từng thể loại được chọn
- Insert vào bảng trung gian `movie_genres` để liên kết phim với thể loại
- Một phim có thể có nhiều thể loại → quan hệ Many-to-Many

---

**Bước 5: Xử lý diễn viên (Cast)**
```php
$persons = $filter['cast_person'];  // [1, 2, 3] - Mảng ID diễn viên
$roles   = $filter['cast_role'];    // [1, 2, 1] - Mảng ID vai trò

for ($i = 0; $i < count($persons); $i++) {
    $dataCast = [
        'movie_id'  => $movie_id,
        'person_id' => $persons[$i],
        'role_id'   => $roles[$i]
    ];
    $this->personModel->insertMoviePerson($dataCast);
}
```
**Giải thích:**
- Nhận 2 mảng song song: diễn viên và vai trò
- Lặp qua từng cặp → tạo record trong bảng `movie_cast`
- VD: Diễn viên ID=1 đóng vai trò ID=1 (diễn viên chính)

---

**Bước 6: Ghi log hoạt động**
```php
$this->activityModel->log(
    $_SESSION['auth']['id'],  // User thực hiện
    'create',                 // Hành động
    'movies',                 // Loại đối tượng
    $movie_id,                // ID đối tượng
    null,                     // Dữ liệu cũ (null vì tạo mới)
    $logData                  // Dữ liệu mới
);
```
**Giải thích:**
- Ghi lại hành động "tạo phim" vào bảng `activity_logs`
- Lưu thông tin: ai làm, làm gì, lúc nào, với đối tượng nào
- Dùng cho audit trail (kiểm tra sau này)

---

### 🔹 Sửa Phim (edit)

**Bước 1: Lấy dữ liệu cũ để so sánh**
```php
$oldData = $this->moviesModel->getOneMovie('id=' . $idMovie);
```
**Giải thích:** Lấy toàn bộ thông tin phim hiện tại từ DB → để so sánh với dữ liệu mới sau này.

---

**Bước 2: Update phim**
```php
$checkUpdate = $this->moviesModel->updateMovies($dataUpdate, 'id=' . $idMovie);
```
**Giải thích:** Gọi model để update record trong bảng `movies` theo điều kiện `id=$idMovie`.

---

**Bước 3: Cập nhật thể loại (Xóa hết → Thêm lại)**
```php
$this->moviesModel->deleteMovieGenres("movie_id = $idMovie");

foreach ($filter['genre_id'] as $genreId) {
    $dataGenre = ['movie_id' => $idMovie, 'genre_id' => $genreId];
    $this->moviesModel->insertMoviesGenres($dataGenre);
}
```
**Giải thích:**
- Xóa sạch tất cả thể loại cũ của phim
- Thêm lại thể loại mới theo form
- Cách này đơn giản hơn so với tìm diff (những thể loại bị xóa/thêm)

---

**Bước 4: So sánh thay đổi**
```php
$changes = [];
foreach ($dataUpdate as $key => $value) {
    if ($oldData[$key] != $value) {
        $changes[$key] = [
            'from' => $oldData[$key],
            'to' => $value
        ];
    }
}
```
**Giải thích:**
- Lặp qua từng trường dữ liệu mới
- So sánh với dữ liệu cũ
- Nếu khác → lưu vào mảng `$changes` (từ giá trị nào → thành giá trị nào)
- Để ghi log chi tiết những gì đã thay đổi

---

## 2. QUẢN LÝ EPISODE

### 🔹 Thêm Episode

**Lấy movie_id và season_id từ URL**
```php
$idMovie = $filterGet['id'];         // VD: id=123
$idSeason = $filterGet['season_id'];  // VD: season_id=5 (có thể null)
```
**Giải thích:** Khi admin vào trang "Thêm tập cho phim X, season Y" → lấy ID từ URL.

---

**Chế độ Bulk (thêm nhiều tập)**
```php
$isBulk = isset($filter['is_bulk']) && $filter['is_bulk'] == 'on';

if ($isBulk) {
    for ($i = $from; $i <= $to; $i++) {
        $dataBulk = [
            'movie_id'  => $idMovie,
            'season_id' => $idSeason,
            'name'      => 'Tập ' . $i,
        ];
        $this->episodeModel->insertEpisode($dataBulk);
    }
}
```
**Giải thích:**
- Kiểm tra checkbox "bulk mode" có được tick không
- Nếu có → lặp từ tập số `$from` đến `$to`
- Tự động tạo tập với tên "Tập 1", "Tập 2"...
- Tiết kiệm thời gian khi phải thêm 10-20 tập

---

**Chế độ Single (thêm 1 tập)**
```php
else {
    $dataInsert = [
        'movie_id'  => $idMovie,
        'season_id' => $idSeason,
        'name'      => $filter['name'],  // Nhập tay
    ];
    $this->episodeModel->insertEpisode($dataInsert);
}
```
**Giải thích:** Nếu không phải bulk → thêm 1 tập với tên do admin nhập.

---

### 🔹 Lấy Danh Sách Episode

**Xây dựng WHERE động**
```php
if (!empty($movieId)) {
    $chuoiWhere = !empty($chuoiWhere) ? $chuoiWhere . ' AND ' : ' WHERE ';
    $chuoiWhere .= "e.movie_id = '$movieId'";
}

if (!empty($seasonId)) {
    $chuoiWhere = !empty($chuoiWhere) ? $chuoiWhere . ' AND ' : ' WHERE ';
    $chuoiWhere .= "e.season_id = '$seasonId'";
}
```
**Giải thích:**
- Nếu có filter theo phim → thêm điều kiện `WHERE movie_id = ...`
- Nếu có filter theo season → thêm điều kiện `AND season_id = ...`
- Xử lý tự động chèn `WHERE` hoặc `AND` tùy vị trí

---

**Query JOIN**
```php
SELECT e.*, 
       m.tittle as movie_name, 
       s.name as season_name
FROM episodes e
LEFT JOIN movies m ON m.id = e.movie_id
LEFT JOIN seasons s ON e.season_id = s.id
WHERE e.movie_id = '123'
```
**Giải thích:**
- Lấy tất cả cột từ bảng `episodes` (e.*)
- JOIN với `movies` để lấy tên phim
- JOIN với `seasons` để lấy tên season
- 1 query lấy cả 3 bảng → hiệu quả hơn 3 queries riêng

---

## 3. QUẢN LÝ VIDEO SOURCE

### 🔹 Tự Động Tạo Video Source

```php
$idEpisode = $this->episodeModel->getLastIdEpisode();

$dataVideoSource = [
    'episode_id'   => $idEpisode,
    'source_url'   => '',    // Trống, admin sẽ điền sau
    'source_name'  => '',
    'voice_type'   => '',
    'created_at'   => date('Y:m:d H:i:s'),
];

$this->sourceModel->insertVideoSource($dataVideoSource);
```
**Giải thích:**
- Sau khi tạo episode → lấy ID episode vừa tạo
- Tự động tạo 1 record rỗng trong bảng `video_sources`
- Admin vào sau để cập nhật URL video, loại lồng tiếng...
- Đảm bảo mỗi episode có ít nhất 1 source

---

### 🔹 Cập Nhật Video Source

```php
$data = [
    'source_url'   => $filter['source_url'],    // https://example.com/video.m3u8
    'source_name'  => $filter['source_name'],   // "Server 1"
    'voice_type'   => $filter['voice_type'],    // "Vietsub"
    'subtitle_url' => $filter['subtitle_url'],  // Link file .vtt
];

$checkUpdate = $this->sourceModel->updateVideoSource($data, 'id=' . $filter['id']);
```
**Giải thích:**
- Admin điền thông tin nguồn video
- Update record trong `video_sources`
- `source_url`: Link video (HLS .m3u8 hoặc trực tiếp .mp4)
- `voice_type`: Vietsub, Thuyết minh, Lồng tiếng...

---

## 4. HỆ THỐNG ACTIVITY LOGS

### 🔹 Hàm Ghi Log

```php
public function log($userId, $action, $entityType, $entityId, $oldData, $newData)
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $data = [
        'user_id'     => $userId,
        'action'      => $action,        // create, update, delete
        'entity_type' => $entityType,    // movies, episodes...
        'entity_id'   => $entityId,
        'old_values'  => json_encode($oldData),  // Dữ liệu cũ (JSON)
        'new_values'  => json_encode($newData),  // Dữ liệu mới (JSON)
        'ip_address'  => $ip,
        'user_agent'  => $userAgent,
        'created_at'  => date('Y:m:d H:i:s')
    ];
    
    return $this->insert('activity_logs', $data);
}
```
**Giải thích:**
- **userId**: Ai thực hiện hành động
- **action**: Tạo/sửa/xóa
- **entityType**: Đối tượng gì (phim, tập phim, user...)
- **entityId**: ID cụ thể
- **old_values**: Dữ liệu cũ (trước khi sửa) → dạng JSON
- **new_values**: Dữ liệu mới (sau khi sửa) → dạng JSON
- **ip_address + user_agent**: Để biết từ đâu, thiết bị gì

**Mục đích:** Audit trail - Biết ai làm gì, khi nào, thay đổi cái gì

---

## 5. API ENDPOINTS

### 🔹 Session Check (Kiểm Tra Đăng Nhập)

```php
header('Content-Type: application/json');

if (!isset($_SESSION['auth']['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Bạn chưa đăng nhập',
        'code' => 401
    ]);
    exit;
}
```
**Giải thích:**
- Set header JSON để trình duyệt hiểu response là JSON
- Kiểm tra session có tồn tại không
- Nếu chưa login → trả về lỗi 401 và dừng luôn
- Bảo mật: Chỉ user đã login mới được gọi API

---

### 🔹 Response JSON Chuẩn

**Success:**
```php
echo json_encode([
    'status' => 'success',
    'message' => 'Thành công',
    'data' => ['id' => 123, 'name' => 'Example']
]);
```

**Error:**
```php
echo json_encode([
    'status' => 'error',
    'message' => 'Mô tả lỗi',
    'code' => 400
]);
```
**Giải thích:**
- Format thống nhất: `status` (success/error) + `message` + `data`/`code`
- JavaScript frontend dễ xử lý: `if (data.status === 'success') { ... }`

---

### 🔹 API Lưu Tiến Trình Xem Phim

**Backend:**
```php
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

$userId = $_SESSION['auth']['id'];
$movieId = (int)$input['movie_id'];
$currentTime = (float)$input['current_time'];  // Giây

$this->watchHistoryModel->saveProgress($userId, $movieId, $episodeId, $seasonId, $currentTime);
```
**Giải thích:**
- Nhận JSON từ JavaScript (không phải form POST thông thường)
- `file_get_contents('php://input')`: Đọc raw request body
- `json_decode()`: Chuyển JSON thành mảng PHP
- Lưu vào DB: user X đang xem phim Y tại giây thứ Z

---

**Frontend (JavaScript):**
```javascript
setInterval(() => {
    fetch('/api/save-history', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            movie_id: 123,
            episode_id: 5,
            current_time: videoPlayer.currentTime
        })
    })
    .then(response => response.json())
    .then(data => console.log('Saved:', data));
}, 5000);
```
**Giải thích:**
- Mỗi 5 giây gửi 1 request
- `JSON.stringify()`: Chuyển object JS thành JSON string
- `fetch()`: Gửi AJAX request (không reload page)
- Lưu tiến trình liên tục → user thoát ra vào lại vẫn xem tiếp được

---

### 🔹 API Toggle Favorite

```php
$action = $this->moviesModel->toggleFavorite($userId, $movieId);

echo json_encode([
    'status' => 'success',
    'action' => $action,  // 'added' hoặc 'removed'
    'message' => ($action === 'added') ? 'Đã thêm' : 'Đã xóa'
]);
```
**Giải thích:**
- `toggleFavorite()`: Kiểm tra phim đã yêu thích chưa
  - Nếu chưa → thêm vào bảng `favorites` → return 'added'
  - Nếu rồi → xóa khỏi bảng `favorites` → return 'removed'
- Trả về action để frontend biết cập nhật UI (đổi icon tim)

---

### 🔹 Thông Báo Realtime

**Tạo thông báo:**
```php
$this->notificationsModel->createNotification([
    'user_id' => $targetUserId,
    'message' => "<b>$senderName</b> đã thích bình luận của bạn.",
    'type' => 'like',
    'link' => '/xem-phim/ten-phim',
    'is_read' => 0,
    'created_at' => date('Y-m-d H:i:s')
]);
```
**Giải thích:**
- Khi user A like comment của user B
- Tạo notification cho user B
- `message`: Nội dung thông báo (HTML)
- `link`: Click vào sẽ đi đâu
- `is_read`: 0 = chưa đọc, 1 = đã đọc

---

**Lấy thông báo:**
```php
$notices = $this->notificationModel->getLatest($userId, 20);
```
**Giải thích:** Lấy 20 thông báo mới nhất của user hiện tại, sắp xếp theo thời gian.

---

## 📊 TÓM TẮT LUỒNG HOẠT ĐỘNG

### Thêm Phim:
```
Form submit → Validate → Insert movies → Lấy movie_id 
→ Insert movie_genres → Insert movie_cast → Ghi log → Reload
```

### Sửa Phim:
```
Form submit → Lấy oldData → Update movies 
→ Xóa genres cũ → Thêm genres mới 
→ Xóa cast cũ → Thêm cast mới 
→ So sánh thay đổi → Ghi log → Reload
```

### Thêm Episode:
```
Chọn phim + season → Single/Bulk mode 
→ Insert episodes → Tự động tạo video_sources → Reload
```

### API Workflow:
```
Frontend gửi fetch() → Backend check session 
→ Xử lý logic → Trả JSON → Frontend update UI
```

---

**Ngày tạo:** 01/01/2026  
**Phiên bản:** 1.0
