<?php
class HomeController extends baseController
{
    private $moviesModel;
    private $genresModel;
    private $personModel;
    public function __construct()
    {
        $this->moviesModel = new Movies;
        $this->genresModel = new Genres;
        $this->personModel = new Person;
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
        $getLoveMovies = $this->moviesModel->getLoveMovies();
        $getHorrorMovies = $this->moviesModel->getHorrorMovies();

        $data = [
            'getMoviesHeroSection' => $getMoviesHeroSection,
            'getGenresGrid' => $getGenresGrid,
            'getMoviesKorean' => $getMoviesKorean,
            'getMoviesUSUK' => $getMoviesUSUK,
            'getMoviesChinese' => $getMoviesChinese,
            'getTopDailyByType1' => $getTopDailyByType1,
            'getTopDailyByType2' => $getTopDailyByType2,
            'getCinemaMovie' => $getCinemaMovie,
            'getAnimeMovies' => $getAnimeMovies,
            'getLoveMovies' => $getLoveMovies,
            'getHorrorMovies' => $getHorrorMovies
        ];
        $this->renderView('/layout-part/client/dashboard', $data);
    }

    public function search()
    {
        $filter = filterData();
        $chuoiWhereMovies = "";
        $chuoiWherePerson = "";
        $tu_khoa = '';


        if (isGet()) {
            if (isset($filter['tu_khoa'])) {
                $tu_khoa = $filter['tu_khoa'];
            }

            if (!empty($tu_khoa)) {
                if (strpos($chuoiWhereMovies, 'WHERE') == false) {
                    $chuoiWhereMovies .= ' WHERE ';
                } else {
                    $chuoiWhereMovies .= ' AND ';
                }
                $chuoiWhereMovies .= "tittle LIKE '%$tu_khoa%' OR original_tittle LIKE '%$tu_khoa%'";
            }

            if (!empty($tu_khoa)) {
                if (strpos($chuoiWherePerson, 'WHERE') == false) {
                    $chuoiWherePerson .= ' WHERE ';
                } else {
                    $chuoiWherePerson .= ' AND ';
                }
                $chuoiWherePerson .= "name LIKE '%$tu_khoa%'";
            }
        }

        $getAllPerson = $this->personModel->getAllPerson("SELECT * FROM persons $chuoiWherePerson");
        $getAllMovies = $this->moviesModel->getAllMovies("SELECT * FROM movies $chuoiWhereMovies");


        $data = [
            'getAllPerson' => $getAllPerson,
            'getAllMovies' => $getAllMovies,
            'tu_khoa' => $tu_khoa
        ];
        $this->renderView('/layout-part/client/search', $data);
    }

    public function filter()
    {
        $getAllMovies = $this->moviesModel->getAllMovies();
        $getAllGenres = $this->genresModel->getAllGenres();
        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllGenres' => $getAllGenres,
        ];
        $this->renderView('/layout-part/client/filter', $data);
    }

    public function phimLe()
    {

        $data = [];
        $this->renderView('/layout-part/client/phim-le', $data);
    }

    public function phimBo()
    {
        $this->renderView('/layout-part/client/phim-bo');
    }

    public function phimChieuRap()
    {
        $this->renderView('/layout-part/client/phim-chieu-rap');
    }

    public function theLoai()
    {
        $this->renderView('/layout-part/client/the-loai');
    }

    public function quocGia()
    {
        $this->renderView('/layout-part/client/quoc-gia');
    }

    public function dienVien()
    {
        $this->renderView('/layout-part/client/dien-vien');
    }
}
