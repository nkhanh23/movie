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
}
