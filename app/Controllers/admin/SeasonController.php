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
        $this->seasonsModel = new Season;
    }
    public function list()
    {

        $filter = filterData();
        $movieId = '';
        $keyword = '';
        $chuoiWhere = '';
        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['filter-movie-id'])) {
                $movieId = $filter['filter-movie-id'];
            }

            if (!empty($movieId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "s.movie_id = '$movieId'";
            }


            //Sử dụng addslashes để tránh lỗi ký tự đặc biệt như dấu nháy đơn '
            $cleanKeyword = addslashes($keyword);
            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "s.name LIKE  '$cleanKeyword'";
            }
        }
        $sqlCount = "SELECT s.*, m.tittle as movie_name 
        FROM seasons s
        LEFT JOIN movies m ON m.id = s.movie_id
        $chuoiWhere
        ORDER BY m.created_at DESC";
        $countResult = $this->episodeModel->countAllEpisode($sqlCount);
        $maxData = $countResult;
        $perPage = 5;
        $maxPage = ceil($maxData / $perPage);
        $page = 1;
        $offset = 0;
        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        if ($page < 1) {
            $page = 1;
        }
        // (Lưu ý: Nếu maxPage = 0 do không có dữ liệu thì page vẫn phải là 1 để tránh lỗi offset)
        if ($maxPage > 0 && $page > $maxPage) {
            $page = $maxPage;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        $getAllSeason = $this->seasonsModel->getAllSeason("SELECT s.*, m.tittle as movie_name 
        FROM seasons s
        LEFT JOIN movies m ON m.id = s.movie_id
        $chuoiWhere
        ORDER BY m.created_at DESC");

        //Xử lý quẻy
        $queryString = $_SERVER['QUERY_STRING'];

        // 1. Xóa trường hợp "&page=..." (khi page nằm sau tham số khác)
        $queryString = str_replace('&page=' . $page, '', $queryString);

        // 2. Xóa trường hợp "page=..." (khi page nằm đầu tiên)
        $queryString = str_replace('page=' . $page, '', $queryString);

        // 3. Xóa dấu & thừa ở 2 đầu (nếu có) để link đẹp hơn
        $queryString = trim($queryString, '&');

        $getAllMovies = $this->moviesModel->getAllMovies();

        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeason' => $getAllSeason,
            'oldData' => $filter,
            'maxPage' => $maxPage,
            'page' => $page,
            'countResult' => $countResult,
            'queryString' => $queryString
        ];
        $this->renderView('/layout-part/admin/season/list', $data);
    }
}
