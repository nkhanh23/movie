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
        mt.name as type_name
        FROM movies m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_types mt ON m.id = mt.id
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
}
