<?php
class Movies extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllMovies($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        } else {
            return $this->getAll("SELECT * FROM movies");
        }
    }

    public function getOneMovie($condition)
    {
        return $this->getOne("SELECT * FROM movies WHERE $condition");
    }

    public function getAllStatus()
    {
        return $this->getAll("SELECT * FROM movie_status");
    }

    public function getVoiceType()
    {
        return $this->getAll("SELECT * FROM video_sources");
    }

    public function getReleaseYear()
    {
        return $this->getAll("SELECT * FROM release_year");
    }

    public function getQuality()
    {
        return $this->getAll("SELECT * FROM qualities");
    }

    public function getAge()
    {
        return $this->getAll("SELECT * FROM age");
    }


    public function getAllMoviesGenres($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        } else {
            return $this->getAll("SELECT * FROM movie_genres");
        }
    }

    public function getAllCountries()
    {
        return $this->getAll("SELECT * FROM countries");
    }

    public function getAllType()
    {
        return $this->getAll("SELECT * FROM movie_types");
    }

    public function getAllMoviesStatus()
    {
        return $this->getAll("SELECT * FROM movie_status");
    }

    public function getRowMovies($sql = '')
    {
        if (!empty($sql)) {
            return $this->getRows($sql);
        } else {
            return $this->getRows("SELECT id FROM movies");
        }
    }

    // Lấy tổng số phim
    public function getTotalMovies()
    {
        return $this->getRows("SELECT id FROM movies");
    }

    public function getLastIdMovies()
    {
        return $this->getLastID();
    }

    public function insertMovies($table, $data)
    {
        return $this->insert($table, $data);
    }

    public function insertMoviesGenres($data)
    {
        return $this->insert('movie_genres', $data);
    }

    public function updateMovies($data, $condition)
    {
        return $this->update('movies', $data, $condition);
    }

    public function deleteMovieGenres($condition)
    {
        return $this->delete('movie_genres', $condition);
    }

    public function deleteMovie($condition)
    {
        return $this->delete('movies', $condition);
    }

    public function chuoiWhere($chuoiWhere)
    {
        if (strpos($chuoiWhere, 'WHERE') == false) {
            $chuoiWhere .= ' WHERE ';
        } else {
            $chuoiWhere .= ' AND ';
        }

        return $chuoiWhere;
    }

    // Ham tao chuoi where
    public function buildMovieWhereClause($filter)
    {
        $chuoiWhere = '';
        $params = [];

        // loc theo the loai phim
        if (!empty($filter['genres'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);

            // Kiểm tra nếu là mảng (multi-select) hoặc giá trị đơn
            if (is_array($filter['genres'])) {
                // Multi-select: sử dụng IN (?, ?, ?)
                $placeholders = implode(',', array_fill(0, count($filter['genres']), '?'));
                $chuoiWhere .= "mg.genre_id IN ($placeholders)";
                // Thêm từng giá trị vào params
                foreach ($filter['genres'] as $genreId) {
                    $params[] = $genreId;
                }
            } else {
                // Single value: sử dụng =
                $chuoiWhere .= "mg.genre_id = ?";
                $params[] = $filter['genres'];
            }
        }

        // loc theo quoc gia
        if (!empty($filter['countries'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);

            // Kiểm tra nếu là mảng (multi-select) hoặc giá trị đơn
            if (is_array($filter['countries'])) {
                // Multi-select: sử dụng IN (?, ?, ?)
                $placeholders = implode(',', array_fill(0, count($filter['countries']), '?'));
                $chuoiWhere .= "m.country_id IN ($placeholders)";
                // Thêm từng giá trị vào params
                foreach ($filter['countries'] as $countryId) {
                    $params[] = $countryId;
                }
            } else {
                // Single value: sử dụng =
                $chuoiWhere .= "m.country_id = ?";
                $params[] = $filter['countries'];
            }
        }

        // loc theo loai phim
        if (!empty($filter['types'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);
            $chuoiWhere .= "m.type_id = ?";
            $params[] = $filter['types'];
        }

        // loc theo phien ban ( kiem tra trong cot voice_type bang video_sources)
        if (!empty($filter['language'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);
            $chuoiWhere .= "vs.id = ?";
            $params[] = $filter['language'];
        }

        // loc theo nam ( kiem tra cot year trong bang release_year )
        if (!empty($filter['release_year'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);
            $chuoiWhere .= "ry.year = ?";
            $params[] = $filter['release_year'];
        }

        // loc theo chat luong (cot name bang quaility)
        if (!empty($filter['quality'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);
            $chuoiWhere .= "q.id = ?";
            $params[] = $filter['quality'];
        }

        // loc theo do tuoi ( cot name bang age )
        if (!empty($filter['age'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);
            $chuoiWhere .= "a.id = ?";
            $params[] = $filter['age'];
        }

        return [
            'sql' => $chuoiWhere,
            'params' => $params
        ];
    }

    // Ham lay phim dua tren chuoi Where
    public function getMoviesByBuilder($whereData, $sort = 'newest', $page = 1, $perPage = 20)
    {
        $chuoiWhere = $whereData['sql'];
        $params = $whereData['params']; // Mảng chứa giá trị để bind vào dấu ?

        $sql = "SELECT m.*, c.name as country_name, ms.name as status_name
                FROM movies m
                LEFT JOIN countries c ON m.country_id = c.id
                LEFT JOIN movie_status ms ON m.status_id = ms.id
                LEFT JOIN movie_types mt ON m.type_id = mt.id
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN release_year ry ON m.release_year = ry.id
                LEFT JOIN qualities q ON m.quality_id = q.id
                LEFT JOIN age a ON m.age = a.id
                $chuoiWhere
                GROUP BY m.id
                ";

        // Sap xep
        switch ($sort) {
            case "views":
                $sql .= " ORDER BY m.total_views DESC";
                break;
            case "rating":
                $sql .= " ORDER BY m.imdb_rating DESC";
                break;
            case "name_asc":
                $sql .= " ORDER BY m.tittle ASC";
                break;
            case "name_desc":
                $sql .= " ORDER BY m.tittle DESC";
                break;
            case "year_desc":
                $sql .= " ORDER BY ry.year DESC";
                break;
            case "year_asc":
                $sql .= " ORDER BY ry.year ASC";
                break;
            default:
                $sql .= " ORDER BY m.created_at DESC";
                break;
        }

        //phan trang
        $sqlCount = "SELECT COUNT(DISTINCT m.id) as total 
        FROM movies m
        LEFT JOIN countries c ON m.country_id = c.id
        LEFT JOIN movie_status ms ON m.status_id = ms.id
        LEFT JOIN movie_types mt ON m.type_id = mt.id
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN release_year ry ON m.release_year = ry.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        LEFT JOIN age a ON m.age = a.id
        $chuoiWhere";

        $maxData = $this->exactlyCount($sqlCount, $params);
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;

        // Nếu không có dữ liệu (maxPage = 0), gán mặc định là 1 để tránh lỗi chia/trừ số âm
        if ($maxPage < 1) {
            $maxPage = 1;
        }

        if ($page < 1) {
            $page = 1;
        }
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        // lay du lieu trang hien tai
        $sql .= " LIMIT $offset, $perPage";

        $getAllMovies = $this->getAll($sql, $params);

        return [
            'data' => $getAllMovies,
            'pagination' => [
                'total' => $maxData,
                'perPage' => $perPage,
                'currentPage' => $page,
                'maxPage' => $maxPage
            ]
        ];
    }

    public function getTopTrendingToday($typeId)
    {
        //Auto seeding: Nếu chưa có view hôm nay thì tạo giả
        $this->seedFakeViewsIfEmpty();

        $sql = "SELECT m.id, m.tittle, m.slug, m.thumbnail, m.poster_url, m.original_tittle,m.release_year,
                       d.views as daily_views
                FROM movies m
                JOIN movie_views_daily d ON m.id = d.movie_id
                WHERE d.view_date = CURDATE() 
                AND m.type_id = :type_id
                ORDER BY d.views DESC
                LIMIT 10";

        return $this->getAll($sql, [
            'type_id' => $typeId
        ]);
    }

    public function incrementMovieView($movieId)
    {
        // Kiểm tra movie có tồn tại không trước khi insert
        $checkSql = "SELECT id FROM movies WHERE id = :movie_id LIMIT 1";
        $movieExists = $this->getOne($checkSql, ['movie_id' => $movieId]);

        if (!$movieExists) {
            // Movie không tồn tại, bỏ qua không insert view
            return false;
        }

        $sql = "INSERT INTO movie_views_daily (movie_id, view_date, views) 
            VALUES (:movie_id, :view_date, 1) 
            ON DUPLICATE KEY UPDATE views = views + 1";

        $this->execute($sql, [
            'movie_id' => $movieId,
            'view_date' => date('Y-m-d')
        ]);
        return true;
    }

    private function seedFakeViewsIfEmpty()
    {
        $checkSql = "SELECT 1 FROM movie_views_daily WHERE view_date = CURDATE() LIMIT 1";
        $result = $this->getRows($checkSql);
        if ($result) {
            return;
        }
        //Nếu chưa có -> Tiến hành Fake
        $sqlMovies = "SELECT id FROM movies ORDER BY RAND() LIMIT 20";
        $movies = $this->getAll($sqlMovies);
        $countMovies = count($movies);
        if ($countMovies > 0) {
            $today = date('Y-m-d');
            $insertSql = "INSERT INTO movie_views_daily (movie_id, view_date, views) VALUES (:movie_id, :view_date, :views)";
            foreach ($movies as $movie) {
                $fakeViews = rand(100, 5000);
                $movieId = $movie['id'];
                $this->execute($insertSql, [
                    'movie_id' => $movieId,
                    'view_date' => $today,
                    'views' => $fakeViews
                ]);
            }
        }
    }
    // CLIENT

    // Dashboard
    public function getMoviesHeroSection()
    {
        return $this->getAll("SELECT m.*,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id 
        GROUP BY m.id
        ORDER BY id 
        DESC LIMIT 6");
    }

    // Lấy phim Hàn Quốc
    public function getMoviesKorean()
    {
        return $this->getAll("SELECT * FROM movies WHERE country_id = 2 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim Hoa Kỳ
    public function getMoviesUSUK()
    {
        return $this->getAll("SELECT * FROM movies WHERE country_id = 45 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim Trung Quốc
    public function getMoviesChinese()
    {
        return $this->getAll("SELECT * FROM movies WHERE country_id = 4 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim chiếu rạp
    public function getCinemaMovie()
    {
        return $this->getAll("SELECT * FROM movies WHERE type_id = 3 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim anime
    public function getAnimeMovies()
    {
        return $this->getAll("SELECT m.*,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 76)
        GROUP BY m.id
        ORDER BY id 
        DESC LIMIT 12");
    }

    // Lấy phim lãng mạn
    public function getLoveMovies()
    {
        return $this->getAll("SELECT m.*,
        q.name as quality_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 57)
        GROUP BY m.id
        ORDER BY id 
        DESC LIMIT 12");
    }

    // Lấy phim kinh dị
    public function getHorrorMovies()
    {
        return $this->getAll("SELECT m.*,
        q.name as quality_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 62)
        GROUP BY m.id
        ORDER BY id 
        DESC LIMIT 12");
    }

    // Page Detail
    public function getMovieDetail($condition)
    {
        $sql = "SELECT m.*, 
                GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
                q.name as quality_name,
                c.name as country_name,
                mt.name as type_name
                FROM movies m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                LEFT JOIN movie_types mt ON m.type_id = mt.id
                LEFT JOIN qualities q ON m.quality_id = q.id
                LEFT JOIN countries c ON m.country_id = c.id
                WHERE $condition
                GROUP BY m.id";

        return $this->getOne($sql);
    }

    // Hàm lấy link từ bảng episodes và video_sources
    public function getSingleMovieSource($movieId)
    {
        // Logic mới: Phim lẻ -> Tìm trong bảng episodes -> Tìm trong video_sources
        $sql = "SELECT vs.id, vs.source_url, vs.voice_type, e.id as episode_id
                FROM episodes e
                JOIN video_sources vs ON e.id = vs.episode_id
                WHERE e.movie_id = $movieId
                LIMIT 1"; // Phim lẻ chỉ cần lấy 1 link chính
        return $this->getOne($sql);
    }

    // Lấy thông tin season
    public function getSeasonDetail($condition)
    {
        // ORDER BY s.id ASC để đảm bảo Phần 1 luôn được lấy trước
        return $this->getAll("SELECT s.*,
        m.tittle as movie_name
        FROM seasons s
        LEFT JOIN movies m ON s.movie_id = m.id
        WHERE s.$condition
        ORDER BY s.id ASC");
    }

    // Lấy thông tin episode
    public function getEpisodeDetail($condition)
    {
        // GROUP BY e.id để mỗi tập chỉ hiện 1 lần (khi có nhiều video sources)
        return $this->getAll("SELECT e.*, vs.source_url, vs.voice_type
                FROM episodes e
                LEFT JOIN video_sources vs ON e.id = vs.episode_id
                WHERE e.$condition 
                GROUP BY e.id
                ORDER BY e.id ASC");
    }

    // Lấy video source
    public function getVideoSources($id)
    {
        return $this->getOne("SELECT vs.*, e.id as episode_id FROM episodes e
        JOIN video_sources vs ON e.id = vs.episode_id
        WHERE e.movie_id = '$id'
        LIMIT 1");
        // LIMIT 1: Vì phim lẻ thường chỉ cần lấy 1 link chính để phát
    }

    public function getSimilarMovies($currentMovieId, $limit = 6)
    {
        // 1. Lấy danh sách phim có chung genre_id với phim hiện tại
        // 2. Loại trừ phim hiện tại (m.id != $currentMovieId)
        // 3. GROUP BY để tránh trùng lặp phim
        // 4. Lấy kèm tên thể loại (GROUP_CONCAT) để hiển thị lên card

        $sql = "SELECT m.*, 
                GROUP_CONCAT(DISTINCT g.name SEPARATOR ', ') as genre_name,
                mt.name as type_name
                FROM movies m
                JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                LEFT JOIN movie_types mt ON m.id = mt.id
                WHERE m.id != $currentMovieId 
                AND m.id IN (
                    SELECT DISTINCT sub_mg.movie_id 
                    FROM movie_genres sub_mg 
                    WHERE sub_mg.genre_id IN (
                        SELECT current_mg.genre_id 
                        FROM movie_genres current_mg 
                        WHERE current_mg.movie_id = $currentMovieId
                    )
                )
                GROUP BY m.id
                ORDER BY m.created_at DESC
                LIMIT $limit";

        return $this->getAll($sql);
    }

    // Favorite
    public function getFavoriteMovies($userId)
    {
        return $this->getAll("SELECT m.*
        FROM favorites f
        LEFT JOIN movies m ON f.movie_id = m.id
        WHERE f.user_id = $userId");
    }

    // Kiểm tra phim đã được yêu thích chưa
    public function checkIsFavorite($userId, $movieId)
    {
        // Giả định bảng favorites có cột user_id và movie_id
        $sql = "SELECT id 
        FROM favorites 
        WHERE user_id = ? AND movie_id = ?";
        return $this->getOne($sql, [$userId, $movieId]);
    }

    public function toggleFavorite($userId, $movieId)
    {
        $checkFavorite = $this->checkIsFavorite($userId, $movieId);
        // Nếu đã yêu thích
        if (!empty($checkFavorite)) {
            $condition = 'id=' . $checkFavorite['id'];
            $this->delete('favorites', $condition);
            return 'removed';
        } else {
            // Nếu chưa yêu thích
            $data = [
                'user_id' => $userId,
                'movie_id' => $movieId,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->insert('favorites', $data);
            return 'added';
        }
    }

    //Lay id cua nguoi thich phim
    public function getFollowrs($movieId)
    {
        $sql = "SELECT user_id FROM favorites WHERE movie_id = :movie_id";
        return $this->getAll($sql, ['movie_id' => $movieId]);
    }
}
