<?php
class EpisodeController extends baseController
{
    private $episodeModel;
    public function __construct()
    {
        $this->episodeModel = new Movies;
    }

    public function list()
    {
        $this->renderView('/layout-part/admin/episode/list');
    }
}
