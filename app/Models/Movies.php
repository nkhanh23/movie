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

    public function getRowMovies()
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
}
