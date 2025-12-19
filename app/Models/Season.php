<?php
class Season extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllSeason($sql = '')
    {
        if (!empty($sql)) {
            return $this->getAll($sql);
        } else {
            return $this->getAll("SELECT * FROM seasons");
        }
    }

    public function getLastInsertId()
    {
        return $this->getLastInsertId();
    }

    public function insertSeason($data)
    {
        return $this->insert("seasons", $data);
    }

    public function getOneSeason($condition)
    {
        return $this->getOne("SELECT * FROM seasons WHERE $condition");
    }

    public function updateSeason($data, $condition)
    {
        return $this->update("seasons", $data, $condition);
    }

    public function deleteSeason($condition)
    {
        return $this->delete("seasons", $condition);
    }
}
