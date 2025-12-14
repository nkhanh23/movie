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
            return $this->getRows("SELECT * FROM movies");
        }
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
            $chuoiWhere .= "mg.genre_id = ?";
            $params[] = $filter['genres'];
        }

        // loc theo quoc gia
        if (!empty($filter['countries'])) {
            $chuoiWhere = $this->chuoiWhere($chuoiWhere);
            $chuoiWhere .= "m.country_id = ?";
            $params[] = $filter['countries'];
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
                LEFT JOIN video_sources vs ON m.video_source_id = vs.id
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
        LEFT JOIN video_sources vs ON m.video_source_id = vs.id
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

    public function getMoviesKorean()
    {
        return $this->getAll("SELECT * FROM movies WHERE country_id = 2 ORDER BY created_at DESC LIMIT 10");
    }

    public function getMoviesUSUK()
    {
        return $this->getAll("SELECT * FROM movies WHERE country_id = 7 ORDER BY created_at DESC LIMIT 10");
    }

    public function getMoviesChinese()
    {
        return $this->getAll("SELECT * FROM movies WHERE country_id = 4 ORDER BY created_at DESC LIMIT 10");
    }

    public function getTopDailyByType($typeId)
    {
        return $this->getAll("SELECT m.*
        FROM movies m
        JOIN movie_views_daily d ON m.id = d.movie_id
        WHERE m.type_id = $typeId
        AND d.view_date = CURDATE()
        ORDER BY d.views DESC
        LIMIT 10
        ");
    }

    public function getCinemaMovie()
    {
        return $this->getAll("SELECT * FROM movies WHERE type_id = 3 ORDER BY created_at DESC LIMIT 10");
    }

    public function getAnimeMovies()
    {
        return $this->getAll("SELECT m.*,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 56)
        GROUP BY m.id
        ORDER BY id 
        DESC LIMIT 12");
    }

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

    public function getHorrorMovies()
    {
        return $this->getAll("SELECT m.*,
        q.name as quality_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        WHERE m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = 1)
        GROUP BY m.id
        ORDER BY id 
        DESC LIMIT 12");
    }

    // Page Detail
    public function getMovieDetail($condition)
    {
        return $this->getOne("SELECT m.*,
        GROUP_CONCAT(g.name SEPARATOR ', ') as genre_name,
        q.name as quality_name,
        c.name as country_name,
        vs.source_url as source_url,
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
        LEFT JOIN qualities q ON m.quality_id = q.id
        LEFT JOIN countries c ON m.country_id = c.id
        LEFT JOIN video_sources vs ON m.video_source_id = vs.id
        WHERE m.$condition");
    }

    public function getSeasonDetail($condition)
    {
        return $this->getAll("SELECT s.*,
        m.tittle as movie_name
        FROM seasons s
        LEFT JOIN movies m ON s.movie_id = m.id
        WHERE s.$condition");
    }

    public function getEpisodeDetail($condition)
    {
        return $this->getAll("SELECT e.*,
        s.name as season_name
        FROM episodes e
        LEFT JOIN seasons s ON e.season_id = s.id
        WHERE e.$condition");
    }

    public function getVideoSources($id)
    {
        return $this->getOne("SELECT m.*, vs.*
        FROM movies m
        LEFT JOIN video_sources vs ON m.video_source_id = vs.id 
        WHERE m.id = '$id'");
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
}
