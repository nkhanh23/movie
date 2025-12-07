<?php
class HomeController extends baseController
{
    private $moviesModel;
    private $genresModel;
    public function __construct()
    {
        $this->moviesModel = new Movies;
        $this->genresModel = new Genres;
    }

    public function adminDashboard()
    {
        $this->renderView('/layout-part/admin/dashboard');
    }

    public function index()
    {
        $getMoviesHeroSection = $this->moviesModel->getMoviesHeroSection();
        $getGenresGrid = $this->genresModel->getGenresGrid();
        $getMoviesKorean = $this->moviesModel->getMoviesKorean();
        $getMoviesUSUK = $this->moviesModel->getMoviesUSUK();
        $getMoviesChinese = $this->moviesModel->getMoviesChinese();
        $data = [
            'getMoviesHeroSection' => $getMoviesHeroSection,
            'getGenresGrid' => $getGenresGrid,
            'getMoviesKorean' => $getMoviesKorean,
            'getMoviesUSUK' => $getMoviesUSUK,
            'getMoviesChinese' => $getMoviesChinese
        ];
        $this->renderView('/layout-part/client/dashboard', $data);
    }
}
