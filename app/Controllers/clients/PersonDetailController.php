<?php
class PersonDetailController extends baseController
{
    private $personModel;
    private $movieModel;
    public function __construct()
    {
        $this->personModel = new Person();
        $this->movieModel = new Movies();
    }

    public function showPerson($slug = null)
    {
        // Nếu không có slug
        if (empty($slug)) {
            header("Location: " . _HOST_URL);
            exit;
        }

        // Tìm diễn viên theo slug
        $personDetail = $this->personModel->findBySlug($slug);

        if (!$personDetail) {
            header("Location: " . _HOST_URL);
            exit;
        }

        $idPerson = $personDetail['id'];
        $filter = filterData('get');

        // Check if current user has favorited this actor
        $personIsFavorited = false;
        if (!empty($_SESSION['auth']['id'])) {
            $checkFav = $this->personModel->checkIsFavorite($_SESSION['auth']['id'], $idPerson);
            $personIsFavorited = !empty($checkFav);
        }

        //Lấy danh sách phim của người
        $maxData = $this->personModel->countPersonMovies($idPerson);
        $perPage = 10;
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
        $getPersonMovies = $this->personModel->getPersonMovies($idPerson, $offset, $perPage);

        // Check favorite status cho TỪNG phim trong danh sách
        if (!empty($_SESSION['auth']) && !empty($getPersonMovies)) {
            $userId = $_SESSION['auth']['id'];
            foreach ($getPersonMovies as &$movie) {
                $checkFavorite = $this->movieModel->checkIsFavorite($userId, $movie['id']);
                $movie['is_favorited'] = !empty($checkFavorite);
            }
            unset($movie); // Xóa reference để tránh bug
        }

        //Xử lý query
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }

        // SEO Data
        $personName = $personDetail['name'];
        $personBio = !empty($personDetail['biography']) ? mb_substr(strip_tags($personDetail['biography']), 0, 150) . '...' : 'Thông tin diễn viên ' . $personName;

        $data = [
            'personDetail' => $personDetail,
            'getPersonMovies' => $getPersonMovies,
            'page' => $page,
            'maxPage' => $maxPage,
            'queryString' => $queryString,
            'countMovies' => $maxData,
            'personIsFavorited' => $personIsFavorited,
            // SEO
            'seoTitle' => $personName . ' - Tiểu Sử và Danh Sách Phim | Phê Phim',
            'seoDescription' => $personBio . ' Xem danh sách ' . $maxData . ' phim có ' . $personName . ' tham gia.',
            'seoKeywords' => $personName . ', diễn viên ' . $personName . ', phim ' . $personName . ', tiểu sử ' . $personName,
            'seoImage' => $personDetail['avatar'] ?? '',
            'seoCanonical' => _HOST_URL . '/dien-vien/' . $slug
        ];
        $this->renderView('layout-part/client/persons', $data);
    }
}
