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

    public function getLastIdEpisode()
    {
        return $this->getLastID();
    }

    public function countAllEpisode($sql = '')
    {
        if (!empty($sql)) {
            return $this->getRows($sql);
        } else {
            return $this->getRows("SELECT * FROM episodes");
        }
    }

    public function getAllVideoSource()
    {
        return $this->getAll("SELECT * FROM video_sources");
    }

    public function insertEpisode($data)
    {
        return $this->insert('episodes', $data);
    }

    public function getOneEpisode($condition)
    {
        return $this->getOne("SELECT * FROM episodes WHERE $condition");
    }

    public function updateEpisode($data, $condition)
    {
        return $this->update("episodes", $data, $condition);
    }

    public function deleteEpisode($condition)
    {
        return $this->delete("episodes", $condition);
    }
}
