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
            return $this->getAll("SELECT m.*, ry.year as release_year_name 
            FROM movies m
            LEFT JOIN release_year ry ON m.release_year = ry.id");
        }
    }

    public function getOneMovie($condition)
    {
        return $this->getOne("SELECT * 
        FROM movies 
        WHERE $condition");
    }

    public function getAllAge()
    {
        return $this->getAll("SELECT * FROM age");
    }

    public function getAllYears()
    {
        return $this->getAll("SELECT * FROM release_year");
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

    public function getCountryBySlug($slug)
    {
        return $this->getOne("SELECT * FROM countries WHERE slug = ?", [$slug]);
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

        $sql = "SELECT m.id, m.tittle,m.original_tittle, m.slug, m.poster_url, m.imdb_rating, m.duration,
                c.name as country_name, 
                ms.name as status_name,
                ry.year as release_year_name
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

        $sql = "SELECT m.id, m.tittle, m.slug, m.thumbnail, m.poster_url, m.original_tittle,
                       ry.year as release_year_name,
                       d.views as daily_views,
                       a.age as age_name
                FROM movies m
                JOIN movie_views_daily d ON m.id = d.movie_id
                LEFT JOIN age a ON m.age = a.id
                LEFT JOIN release_year ry ON m.release_year = ry.id
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

        // Dùng CURDATE() của MySQL để đảm bảo timezone nhất quán
        $sql = "INSERT INTO movie_views_daily (movie_id, view_date, views) 
            VALUES (:movie_id, CURDATE(), 1) 
            ON DUPLICATE KEY UPDATE views = views + 1";

        $this->execute($sql, [
            'movie_id' => $movieId
        ]);
        return true;
    }

    private function seedFakeViewsIfEmpty()
    {
        // Kiểm tra số lượng phim BỘ đã có views hôm nay
        $countType2Sql = "SELECT COUNT(DISTINCT d.movie_id) as cnt 
                          FROM movie_views_daily d
                          JOIN movies m ON d.movie_id = m.id
                          WHERE d.view_date = CURDATE() AND m.type_id = 2";
        $resultType2 = $this->getOne($countType2Sql);
        $countType2 = $resultType2['cnt'] ?? 0;

        // Kiểm tra số lượng phim LẺ đã có views hôm nay  
        $countType1Sql = "SELECT COUNT(DISTINCT d.movie_id) as cnt 
                          FROM movie_views_daily d
                          JOIN movies m ON d.movie_id = m.id
                          WHERE d.view_date = CURDATE() AND m.type_id = 1";
        $resultType1 = $this->getOne($countType1Sql);
        $countType1 = $resultType1['cnt'] ?? 0;

        $insertSql = "INSERT IGNORE INTO movie_views_daily (movie_id, view_date, views) VALUES (:movie_id, CURDATE(), :views)";

        // Seed thêm phim BỘ nếu chưa đủ 10
        if ($countType2 < 10) {
            $needMore = 10 - $countType2;
            $sqlMoviesType2 = "SELECT id FROM movies WHERE type_id = 2 
                               AND id NOT IN (SELECT movie_id FROM movie_views_daily WHERE view_date = CURDATE())
                               ORDER BY RAND() LIMIT " . $needMore;
            $moviesType2 = $this->getAll($sqlMoviesType2);
            foreach ($moviesType2 as $movie) {
                $this->execute($insertSql, ['movie_id' => $movie['id'], 'views' => rand(100, 5000)]);
            }
        }

        // Seed thêm phim LẺ nếu chưa đủ 10
        if ($countType1 < 10) {
            $needMore = 10 - $countType1;
            $sqlMoviesType1 = "SELECT id FROM movies WHERE type_id = 1 
                               AND id NOT IN (SELECT movie_id FROM movie_views_daily WHERE view_date = CURDATE())
                               ORDER BY RAND() LIMIT " . $needMore;
            $moviesType1 = $this->getAll($sqlMoviesType1);
            foreach ($moviesType1 as $movie) {
                $this->execute($insertSql, ['movie_id' => $movie['id'], 'views' => rand(100, 5000)]);
            }
        }
    }
    //--------------------------------------------------------------------------------------------------------------------------------------
    // CLIENT
    //--------------------------------------------------------------------------------------------------------------------------------------

    public function findBySlug($slug)
    {
        // Tìm phim theo slug
        $sql = "SELECT m.*,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        mt.name as type_name,
        q.name as quality_name,
        c.name as country_name,
        a.age as age_name,
        ry.year as release_year_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.type_id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        LEFT JOIN countries c ON m.country_id = c.id
        LEFT JOIN age a ON m.age = a.id
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE m.slug = ? AND m.status_id = 1 LIMIT 1";
        return $this->getOne($sql, [$slug]);
    }
    // Dashboard
    public function getMoviesHeroSection()
    {
        return $this->getAll("SELECT m.id, m.tittle, m.original_tittle,
        m.imdb_rating, m.slug, m.duration,m.thumbnail, m.poster_url, 
        ry.year as release_year_name, 
        a.age as age_name,
        m.description,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id 
        LEFT JOIN release_year ry ON m.release_year = ry.id
        LEFT JOIN age a ON m.age = a.id
        GROUP BY m.id
        ORDER BY m.id 
        DESC LIMIT 6");
    }

    // Lấy phim Hàn Quốc
    public function getMoviesKorean()
    {
        return $this->getAll("SELECT m.*, ry.year as release_year_name
        FROM movies m
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE country_id = 2 
        ORDER BY created_at DESC 
        LIMIT 10");
    }

    // Lấy phim Hoa Kỳ
    public function getMoviesUSUK()
    {
        return $this->getAll("SELECT m.id, m.tittle, m.original_tittle, m.slug, m.duration,  ry.year as release_year_name, m.poster_url 
        FROM movies m
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE country_id = 45 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim Trung Quốc
    public function getMoviesChinese()
    {
        return $this->getAll("SELECT m.id, m.tittle, m.original_tittle, m.slug, m.duration,  ry.year as release_year_name, m.poster_url
        FROM movies m
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE country_id = 4 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim chiếu rạp
    public function getCinemaMovie()
    {
        return $this->getAll("SELECT m.id, m.tittle, m.original_tittle, m.slug, m.duration,  ry.year as release_year_name, m.poster_url 
        FROM movies m
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE type_id = 3 ORDER BY created_at DESC LIMIT 10");
    }

    // Lấy phim anime
    public function getAnimeMovies()
    {
        return $this->getAll("SELECT m.id, m.tittle, m.original_tittle, m.slug, m.duration, m.poster_url, m.thumbnail as thumbnail, m.imdb_rating as imdb_rating, m.description,
        ry.year as release_year_name,
        a.age as age_name,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN release_year ry ON m.release_year = ry.id
        LEFT JOIN age a ON m.age = a.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 76)
        GROUP BY m.id
        ORDER BY m.id 
        DESC LIMIT 12");
    }

    // Lấy phim lãng mạn
    public function getLoveMovies()
    {
        return $this->getAll("SELECT m.id, m.tittle, m.original_tittle, m.slug, m.duration,  ry.year as release_year_name, m.poster_url,
        q.name as quality_name,
        ry.year as release_year_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 57)
        GROUP BY m.id
        ORDER BY m.id 
        DESC LIMIT 12");
    }

    // Lấy phim kinh dị
    public function getHorrorMovies()
    {
        return $this->getAll("SELECT m.id,m.tittle, m.original_tittle, m.slug, m.duration,  ry.year as release_year_name, m.poster_url,
        q.name as quality_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        LEFT JOIN release_year ry ON m.release_year = ry.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 62)
        GROUP BY m.id
        ORDER BY m.id 
        DESC LIMIT 12");
    }

    // Page Detail
    public function getMovieDetail($condition)
    {
        $sql = "SELECT m.*, 
                GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
                q.name as quality_name,
                c.name as country_name,
                a.age as age_name,
                ry.year as release_year_name,
                mt.name as type_name
                FROM movies m
                LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                LEFT JOIN movie_types mt ON m.type_id = mt.id
                LEFT JOIN qualities q ON m.quality_id = q.id
                LEFT JOIN countries c ON m.country_id = c.id
                LEFT JOIN age a ON m.age = a.id
                LEFT JOIN release_year ry ON m.release_year = ry.id
                WHERE $condition
                GROUP BY m.id";

        return $this->getOne($sql);
    }

    // Hàm lấy link từ bảng episodes và video_sources
    public function getSingleMovieSource($movieId)
    {
        $sql = "SELECT vs.id, vs.source_url, vs.source_name, vs.voice_type, e.id as episode_id
                FROM episodes e
                JOIN video_sources vs ON e.id = vs.episode_id
                WHERE e.movie_id = $movieId
                LIMIT 1"; // Phim lẻ chỉ cần lấy 1 link chính
        return $this->getOne($sql);
    }

    // Lấy thông tin season
    public function getSeasonDetail($condition)
    {
        return $this->getAll("SELECT s.*,
        m.tittle as movie_name
        FROM seasons s
        LEFT JOIN movies m ON s.movie_id = m.id
        WHERE s.$condition
        -- +0 de ep chuoi sang so
        ORDER BY (s.name + 0) ASC, s.name ASC");
    }

    // Lấy thông tin episode
    public function getEpisodeDetail($condition)
    {
        // GROUP BY e.id để mỗi tập chỉ hiện 1 lần (khi có nhiều video sources)
        return $this->getAll("SELECT e.*, vs.source_url, vs.voice_type, vs.source_name
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
                mt.name as type_name,
                ry.year as release_year_name
                FROM movies m
                JOIN movie_genres mg ON m.id = mg.movie_id
                LEFT JOIN genres g ON mg.genre_id = g.id
                LEFT JOIN movie_types mt ON m.id = mt.id
                LEFT JOIN release_year ry ON m.release_year = ry.id
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


    //Lấy tất cả season của một phim (theo movie_id)
    public function getSeasonsByMovieId($movieId)
    {
        // Sắp xếp theo số trong tên để lấy đúng thứ tự: Phần 1, Phần 2, ...
        // Dùng name+0 để MySQL trích xuất số từ chuỗi
        $sql = "SELECT * FROM seasons WHERE movie_id = ? ORDER BY (name + 0) ASC, name ASC";
        return $this->getAll($sql, [$movieId]);
    }

    //Lấy season theo số thứ tự (vị trí trong danh sách)
    public function getSeasonByNumber($movieId, $seasonNumber)
    {
        $seasons = $this->getSeasonsByMovieId($movieId);
        $index = $seasonNumber - 1; // Convert to 0-indexed
        return isset($seasons[$index]) ? $seasons[$index] : null;
    }

    //Lấy tất cả episodes của một season
    public function getEpisodesBySeasonId($seasonId)
    {
        $sql = "SELECT e.*, vs.source_url, vs.voice_type, vs.source_name
                FROM episodes e
                LEFT JOIN video_sources vs ON e.id = vs.episode_id
                WHERE e.season_id = ?
                GROUP BY e.id
                ORDER BY e.id ASC";
        return $this->getAll($sql, [$seasonId]);
    }

    //Lấy tất cả episodes của một phim (không có season)
    public function getEpisodesByMovieId($movieId)
    {
        $sql = "SELECT e.*, vs.source_url, vs.voice_type, vs.source_name
                FROM episodes e
                LEFT JOIN video_sources vs ON e.id = vs.episode_id
                WHERE e.movie_id = ? AND (e.season_id IS NULL OR e.season_id = 0)
                GROUP BY e.id
                ORDER BY e.id ASC";
        return $this->getAll($sql, [$movieId]);
    }

    //Lấy episode theo số thứ tự trong season
    public function getEpisodeBySeasonAndNumber($seasonId, $episodeNumber)
    {
        $episodes = $this->getEpisodesBySeasonId($seasonId);
        $index = $episodeNumber - 1; // Convert to 0-indexed
        return isset($episodes[$index]) ? $episodes[$index] : null;
    }

    //Lấy episode theo số thứ tự (phim không có season)
    public function getEpisodeByMovieAndNumber($movieId, $episodeNumber)
    {
        $episodes = $this->getEpisodesByMovieId($movieId);
        $index = $episodeNumber - 1; // Convert to 0-indexed
        return isset($episodes[$index]) ? $episodes[$index] : null;
    }

    //Lấy số thứ tự của season trong danh sách
    public function getSeasonNumber($movieId, $seasonId)
    {
        $seasons = $this->getSeasonsByMovieId($movieId);
        foreach ($seasons as $index => $season) {
            if ($season['id'] == $seasonId) {
                return $index + 1; // Convert to 1-indexed
            }
        }
        return 1;
    }

    //Lấy số thứ tự của episode trong season
    public function getEpisodeNumber($seasonId, $episodeId)
    {
        $episodes = $this->getEpisodesBySeasonId($seasonId);
        foreach ($episodes as $index => $episode) {
            if ($episode['id'] == $episodeId) {
                return $index + 1; // Convert to 1-indexed
            }
        }
        return 1;
    }

    //Lấy số thứ tự của episode trong phim (không có season)
    public function getEpisodeNumberByMovie($movieId, $episodeId)
    {
        $episodes = $this->getEpisodesByMovieId($movieId);
        foreach ($episodes as $index => $episode) {
            if ($episode['id'] == $episodeId) {
                return $index + 1; // Convert to 1-indexed
            }
        }
        return 1;
    }
}
