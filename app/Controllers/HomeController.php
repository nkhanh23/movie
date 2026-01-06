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
            'getContinueWatching'  => $getContinueWatching,
            // SEO for Homepage
            'seoTitle'       => 'Phê Phim - Xem Phim Online Miễn Phí Chất Lượng Cao HD Vietsub',
            'seoDescription' => 'Phê Phim - Website xem phim online miễn phí, phim mới nhất 2024, phim bộ, phim lẻ, phim chiếu rạp vietsub thuyết minh chất lượng cao HD.',
            'seoKeywords'    => 'xem phim, phim online, phim mới, phim hay, phim vietsub, phim thuyết minh, phim hd, phê phim',
            'seoCanonical'   => _HOST_URL
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

        $getAllPerson = $this->personModel->getAllPerson("SELECT id, slug, name, avatar FROM persons $chuoiWherePerson");
        $getAllMovies = $this->moviesModel->getAllMovies("SELECT m.id, m.slug, m.tittle, m.poster_url, m.imdb_rating, m.duration, ry.year as release_year_name 
        FROM movies m
        LEFT JOIN release_year ry ON m.release_year = ry.id
        $chuoiWhereMovies");


        $data = [
            'getAllPerson' => $getAllPerson,
            'getAllMovies' => $getAllMovies,
            'tu_khoa' => $tu_khoa
        ];
        $this->renderView('/layout-part/client/search', $data);
    }

    private function renderMoviesByType($typeId, $viewPath, $genresId = null, $countriesId = null, $seoData = [])
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
            // SEO Data
            'seoTitle'          => $seoData['title'] ?? null,
            'seoDescription'    => $seoData['description'] ?? null,
            'seoKeywords'       => $seoData['keywords'] ?? null,
            'seoCanonical'      => $seoData['canonical'] ?? null,
        ];

        $this->renderView($viewPath, $data);
    }

    public function phimLe()
    {
        $seoData = [
            'title' => 'Phim Lẻ - Xem Phim Lẻ Mới Nhất Vietsub HD | Phê Phim',
            'description' => 'Xem phim lẻ mới nhất 2024, phim lẻ hay nhất, phim lẻ vietsub, thuyết minh chất lượng cao HD miễn phí tại Phê Phim.',
            'keywords' => 'phim lẻ, phim lẻ mới, phim lẻ hay, phim lẻ vietsub, phim lẻ hd, xem phim lẻ',
            'canonical' => _HOST_URL . '/phim-le'
        ];
        $this->renderMoviesByType(1, '/layout-part/client/phim-le', null, null, $seoData);
    }

    public function phimBo()
    {
        $seoData = [
            'title' => 'Phim Bộ - Xem Phim Bộ Mới Nhất Vietsub HD | Phê Phim',
            'description' => 'Xem phim bộ mới nhất 2024, phim bộ Hàn Quốc, Trung Quốc, Mỹ vietsub, thuyết minh chất lượng cao HD miễn phí.',
            'keywords' => 'phim bộ, phim bộ mới, phim bộ hay, phim bộ hàn quốc, phim bộ trung quốc, xem phim bộ',
            'canonical' => _HOST_URL . '/phim-bo'
        ];
        $this->renderMoviesByType(2, '/layout-part/client/phim-bo', null, null, $seoData);
    }

    public function phimChieuRap()
    {
        $seoData = [
            'title' => 'Phim Chiếu Rạp - Phim Rạp Mới Nhất 2024 | Phê Phim',
            'description' => 'Xem phim chiếu rạp mới nhất 2024, phim rạp hay, phim bom tấn Hollywood vietsub HD miễn phí tại Phê Phim.',
            'keywords' => 'phim chiếu rạp, phim rạp mới, phim bom tấn, phim hollywood, xem phim rạp',
            'canonical' => _HOST_URL . '/phim-chieu-rap'
        ];
        $this->renderMoviesByType(3, '/layout-part/client/phim-chieu-rap', null, null, $seoData);
    }


    public function theLoai($slug = null)
    {
        if (empty($slug)) {
            reload("/");
        }

        // Tìm thể loại theo slug
        $genre = $this->genresModel->getGenreBySlug($slug);

        if (!$genre) {
            reload("/");
        }

        $genresId = $genre['id'];
        $genreName = $genre['name'];

        $seoData = [
            'title' => 'Phim ' . $genreName . ' - Xem Phim ' . $genreName . ' Hay Nhất | Phê Phim',
            'description' => 'Xem phim ' . $genreName . ' mới nhất 2025, tuyển tập phim ' . $genreName . ' hay nhất, phim ' . $genreName . ' vietsub HD miễn phí tại Phê Phim.',
            'keywords' => 'phim ' . $genreName . ', phim ' . strtolower($genreName) . ' hay, xem phim ' . strtolower($genreName) . ', phim ' . strtolower($genreName) . ' vietsub',
            'canonical' => _HOST_URL . '/the-loai/' . $slug
        ];

        $this->renderMoviesByType(1, '/layout-part/client/the_loai', $genresId, null, $seoData);
    }

    public function quocGia($slug = null)
    {
        if (empty($slug)) {
            reload("/");
        }

        // Tìm quốc gia theo slug
        $country = $this->moviesModel->getCountryBySlug($slug);

        if (!$country) {
            reload("/");
        }

        $countriesId = $country['id'];
        $countryName = $country['name'];

        $seoData = [
            'title' => 'Phim ' . $countryName . ' - Xem Phim ' . $countryName . ' Hay Nhất | Phê Phim',
            'description' => 'Xem phim ' . $countryName . ' mới nhất 2025, tuyển tập phim ' . $countryName . ' hay nhất, phim ' . $countryName . ' vietsub HD miễn phí tại Phê Phim.',
            'keywords' => 'phim ' . $countryName . ', phim ' . strtolower($countryName) . ' hay, xem phim ' . strtolower($countryName),
            'canonical' => _HOST_URL . '/quoc-gia/' . $slug
        ];

        $this->renderMoviesByType(1, '/layout-part/client/quoc_gia', null, $countriesId, $seoData);
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
