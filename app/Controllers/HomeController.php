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
        $getTopDailyByType1 = $this->moviesModel->getTopDailyByType(1);
        $getTopDailyByType2 = $this->moviesModel->getTopDailyByType(2);
        $getCinemaMovie = $this->moviesModel->getCinemaMovie();
        $getAnimeMovies = $this->moviesModel->getAnimeMovies();

        $data = [
            'getMoviesHeroSection' => $getMoviesHeroSection,
            'getGenresGrid' => $getGenresGrid,
            'getMoviesKorean' => $getMoviesKorean,
            'getMoviesUSUK' => $getMoviesUSUK,
            'getMoviesChinese' => $getMoviesChinese,
            'getTopDailyByType1' => $getTopDailyByType1,
            'getTopDailyByType2' => $getTopDailyByType2,
            'getCinemaMovie' => $getCinemaMovie,
            'getAnimeMovies' => $getAnimeMovies
        ];
        $this->renderView('/layout-part/client/dashboard', $data);
    }
}
