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

    public function getAllGenres()
    {
        return $this->getAll("SELECT * FROM genres");
    }

    public function getAllCoutries()
    {
        return $this->getAll("SELECT * FROM countries");
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
}
