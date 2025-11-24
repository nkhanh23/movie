<?php
class Episode extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllEpisode($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        } else {
            return $this->getAll("SELECT * FROM episodes");
        }
    }

    public function countAllEpisode($sql = '')
    {
        if (!empty($sql)) {
            return $this->getRows($sql);
        } else {
            return $this->getRows("SELECT * FROM episodes");
        }
    }
}