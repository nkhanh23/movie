<?php
class Seasons extends CoreModel
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getAllSeasons()
    {
        return $this->getAll("SELECT * FROM seasons");
    }
}
