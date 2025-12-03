<?php
class WatchDetailController extends baseController
{
    private $moviesModel;
    public function __construct()
    {
        $this->moviesModel = new Movies;
    }
}
