<?php
class HomeController extends baseController
{
    private $moviesModel;
    private $genresModel;
    private $personModel;
    private $activityModel;
    private $supportModel;
    private $watchHistoryModel;
    private $userModel;
    private $commentModel;
    public function __construct()
    {
        $this->moviesModel = new Movies;
        $this->genresModel = new Genres;
        $this->personModel = new Person;
        $this->activityModel = new Activity;
        $this->supportModel = new Support;
        $this->watchHistoryModel = new WatchHistory;
        $this->userModel = new User;
        $this->commentModel = new Comments;
    }

    public function adminDashboard()
    {
        $getLatestMoviesLogs = $this->activityModel->getLatestMoviesLogs(5);
        $logs = $this->activityModel->getLatestLogs(5);

        // Lấy 5 support mới nhất
        $latestSupports = $this->supportModel->getAllSupport('', 5, 0);

        // Lấy thống kê tổng số
        $totalMovies = $this->moviesModel->getTotalMovies();
        $totalUsers = $this->userModel->getTotalUsers();
        $totalComments = $this->commentModel->getTotalComments();

        $data = [
            'logs' => $logs,
            'getLatestMoviesLogs' => $getLatestMoviesLogs,
            'latestSupports' => $latestSupports,
            'totalMovies' => $totalMovies,
            'totalUsers' => $totalUsers,
            'totalComments' => $totalComments
        ];
        $this->renderView('/layout-part/admin/dashboard', $data);
    }

    public function index()
    {
        // Sử dụng cached dashboard data thay vì 11 queries riêng lẻ
        $cachedData = getCachedDashboardData();

        // Lấy danh sách xem tiếp (nếu đã đăng nhập) - không cache vì theo user
        $getContinueWatching = [];
        if (!empty($_SESSION['auth']['id'])) {
            $getContinueWatching = $this->watchHistoryModel->getContinueWatchingList($_SESSION['auth']['id'], 10);
        }

        // Kiểm tra trạng thái favorite cho hero movie - không cache vì theo user
        $heroIsFavorited = false;
        if (!empty($_SESSION['auth']) && !empty($cachedData['getMoviesHeroSection'][0])) {
            $userId = $_SESSION['auth']['id'];
            $heroMovieId = $cachedData['getMoviesHeroSection'][0]['id'];
            $checkFavorite = $this->moviesModel->checkIsFavorite($userId, $heroMovieId);
            $heroIsFavorited = !empty($checkFavorite);
        }

        $data = [
            'getMoviesHeroSection' => $cachedData['getMoviesHeroSection'],
            'getGenresGrid'        => $cachedData['getGenresGrid'],
            'getMoviesKorean'      => $cachedData['getMoviesKorean'],
            'getMoviesUSUK'        => $cachedData['getMoviesUSUK'],
            'getMoviesChinese'     => $cachedData['getMoviesChinese'],
            'getTopDailyByType1'   => $cachedData['getTopDailyByType1'],
            'getTopDailyByType2'   => $cachedData['getTopDailyByType2'],
            'getCinemaMovie'       => $cachedData['getCinemaMovie'],
            'getAnimeMovies'       => $cachedData['getAnimeMovies'],
            'getLoveMovies'        => $cachedData['getLoveMovies'],
            'getHorrorMovies'      => $cachedData['getHorrorMovies'],
            'heroIsFavorited'      => $heroIsFavorited,
            'getContinueWatching'  => $getContinueWatching
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

    private function renderMoviesByType($typeId, $viewPath, $genresId = null, $countriesId = null)
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

        // Sử dụng cached filter data thay vì query riêng lẻ
        $cachedFilterData = getCachedFilterData();

        $data = [
            'movies'            => $result['data'],
            'pagination'        => $result['pagination'],
            'filters'           => $filterParams,
            'sort'              => $sort,
            'page'              => $page,
            'maxPage'           => $result['pagination']['maxPage'],
            'getAllGenres'      => $cachedFilterData['getAllGenres'],
            'getAllCountries'   => $cachedFilterData['getAllCountries'],
            'getAllTypes'       => $cachedFilterData['getAllTypes'],
            'getAllVoiceType'   => $cachedFilterData['getAllVoiceType'],
            'getAllQuality'     => $cachedFilterData['getAllQuality'],
            'getAllAge'         => $cachedFilterData['getAllAge'],
            'getAllReleaseYear' => $cachedFilterData['getAllReleaseYear'],
        ];

        $this->renderView($viewPath, $data);
    }

    public function phimLe()
    {
        $this->renderMoviesByType(1, '/layout-part/client/phim-le');
    }

    public function phimBo()
    {
        $this->renderMoviesByType(2, '/layout-part/client/phim-bo');
    }

    public function phimChieuRap()
    {
        $this->renderMoviesByType(3, '/layout-part/client/phim-chieu-rap');
    }


    public function theLoai()
    {
        $filter = filterData();
        $genresId = $filter['id'];
        $this->renderMoviesByType(1, '/layout-part/client/the_loai', $genresId);
    }

    public function quocGia()
    {
        $filter = filterData();
        $countriesId = $filter['id'];
        $this->renderMoviesByType(1, '/layout-part/client/quoc_gia', null, $countriesId);
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
