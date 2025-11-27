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
            $this->getAll($sql);
        } else {
            $this->getAll("SELECT * FROM seasons");
        }
    }
}
