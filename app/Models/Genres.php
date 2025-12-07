<?php
class Genres extends CoreModel
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllGenres($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        } else {
            return $this->getAll("SELECT * FROM genres");
        }
    }

    public function getGenresGrid()
    {

        return $this->getAll("SELECT * FROM genres LIMIT 7");
    }

    public function getAllGenresWithCount($sql)
    {
        return $this->countRows($sql);
    }

    public function CountGenres($sql)
    {
        return $this->getRows($sql);
    }

    public function insertGenres($data)
    {
        return $this->insert("genres", $data);
    }

    public function getOneGenres($condition)
    {
        return $this->getOne("SELECT * FROM genres WHERE $condition");
    }

    public function updateGenres($data, $condition)
    {
        return $this->update("genres", $data, $condition);
    }

    public function deleteGenres($table, $condition)
    {
        return $this->delete($table, $condition);
    }
}
