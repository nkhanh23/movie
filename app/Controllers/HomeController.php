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

    private function renderMoviesByType($typeId, $genresId = null, $countriesId = null, $viewPath)
    {
        $filter = filterData('get');
        $filterParams = [
            'genres'       => $filter['genres'] ?? $genresId,
            'countries'    => $filter['countries'] ?? $countriesId,
            'types'        => $typeId,
            'release_year' => $filter['release_year'] ?? null,
            'quality'      => $filter['quality'] ?? null,
            'age'          => $filter['age'] ?? null,
            'language'     => $filter['language'] ?? null,
        ];

        $whereData = $this->moviesModel->buildMovieWhereClause($filterParams);
        $page = $filter['page'] ?? 1;
        $sort = $filter['sort'] ?? 'newest';
        $result = $this->moviesModel->getMoviesByBuilder($whereData, $sort, $page);

        $data = [
            'movies'            => $result['data'],
            'pagination'        => $result['pagination'],
            'filters'           => $filterParams,
            'sort'              => $sort,
            'page'              => $page,
            'maxPage'           => $result['pagination']['maxPage'],
            'getAllGenres'      => $this->genresModel->getAllGenres(),
            'getAllCountries'   => $this->moviesModel->getAllCountries(),
            'getAllTypes'       => $this->moviesModel->getAllType(),
            'getAllVoiceType'   => $this->moviesModel->getVoiceType(),
            'getAllQuality'     => $this->moviesModel->getQuality(),
            'getAllAge'         => $this->moviesModel->getAge(),
            'getAllReleaseYear' => $this->moviesModel->getReleaseYear(),
        ];

        $this->renderView($viewPath, $data);
    }

    public function phimLe()
    {
        $this->renderMoviesByType(1, null, null, '/layout-part/client/phim-le');
    }

    public function phimBo()
    {
        $this->renderMoviesByType(2, null, null, '/layout-part/client/phim-bo');
    }

    public function phimChieuRap()
    {
        $this->renderMoviesByType(3, null, null, '/layout-part/client/phim-chieu-rap');
    }


    public function theLoai()
    {
        $filter = filterData();
        $genresId = $filter['id'];
        $this->renderMoviesByType(1, $genresId, null, '/layout-part/client/the_loai');
    }

    public function quocGia()
    {
        $filter = filterData();
        $countriesId = $filter['id'];
        $this->renderMoviesByType(1, null, $countriesId, '/layout-part/client/quoc_gia');
    }

    public function dienVien()
    {
        $filter = filterData();
        $currentTab = $filter['tab'] ?? 'actors';
        $roleId = ($currentTab === 'directors') ? 5 : 1;

        $maxData = $this->personModel->countPersonsByRole($roleId);
        $perPage = 12;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;
        if (isset($filter['page'])) {
            $page = $filter['page'];
        }
        if ($maxPage < 1) {
            $maxPage = 1;
        }
        if ($page < 1) {
            $page = 1;
        }
        if ($maxPage > 0 && $page > $maxPage) {
            $page = $maxPage;
        }
        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        // Lấy danh sách active
        $activeList = $this->personModel->getPersonsByRole($roleId, $offset, $perPage);

        $getAllActors = [];
        $getAllDirectors = [];
        if ($currentTab === 'directors') {
            $getAllDirectors = $activeList;
        } else {
            $getAllActors = $activeList;
        }

        //Xử lý query
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }

        $data = [
            'getAllActors'    => $getAllActors,
            'getAllDirectors' => $getAllDirectors,
            'page'            => $page,
            'maxPage'         => $maxPage,
            'currentTab'      => $currentTab,
            'queryString'     => $queryString,
        ];
        $this->renderView('/layout-part/client/dien_vien', $data);
    }
}
