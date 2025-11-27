<?php
class SeasonController extends baseController
{
    private $episodeModel;
    private $moviesModel;
    private $seasonsModel;
    public function __construct()
    {
        $this->episodeModel = new Episode;
        $this->moviesModel = new Movies;
        $this->seasonsModel = new Seasons;
    }
    public function list()
    {
        $sql = "SELECT s.*, m.tittle as movie_name 
        FROM seasons s
        LEFT JOIN movies m ON m.id = s.movie_id
        ORDER BY m.created_at DESC";

        $getAllSeason = $this->seasonsModel->getAllSeasons($sql);
        $getAllMovies = $this->moviesModel->getAllMovies();

        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeason' => $getAllSeason
        ];
        $this->renderView('/layout-part/admin/season/list', $data);
    }
}
