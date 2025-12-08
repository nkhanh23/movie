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
}
