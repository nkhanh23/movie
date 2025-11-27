<?php
class EpisodeController extends baseController
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
        $seasonId = '';
        $keyword = '';
        $chuoiWhere = '';
        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['filter-movie-id'])) {
                $movieId = $filter['filter-movie-id'];
            }
            if (isset($filter['season_id'])) {
                $seasonId = $filter['season_id'];
            }

            if (!empty($movieId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "e.movie_id = '$movieId'";
            }

            if (!empty($seasonId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "e.season_id = '$seasonId'";
            }

            //Sử dụng addslashes để tránh lỗi ký tự đặc biệt như dấu nháy đơn '
            $cleanKeyword = addslashes($keyword);
            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "e.name LIKE  '$cleanKeyword'";
            }
        }

        $sqlCount = "SELECT m.tittle as movie_name, e.name as episode_name, s.name as season_name
        FROM  episodes e
        LEFT JOIN movies m ON m.id = e.movie_id
        LEFT JOIN seasons s ON e.season_id = s.id
        $chuoiWhere
        ORDER BY m.created_at DESC,e.episode_number ASC";
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


        $getAllEpisode = $this->episodeModel->getAllEpisode("SELECT m.tittle as movie_name, e.name as episode_name, s.name as season_name
        FROM  episodes e
        LEFT JOIN movies m ON m.id = e.movie_id
        LEFT JOIN seasons s ON e.season_id = s.id
        $chuoiWhere
        ORDER BY m.created_at DESC,e.episode_number ASC
        LIMIT $offset , $perPage
        ");

        //Xử lý quẻy
        $queryString = $_SERVER['QUERY_STRING'];

        // 1. Xóa trường hợp "&page=..." (khi page nằm sau tham số khác)
        $queryString = str_replace('&page=' . $page, '', $queryString);

        // 2. Xóa trường hợp "page=..." (khi page nằm đầu tiên)
        $queryString = str_replace('page=' . $page, '', $queryString);

        // 3. Xóa dấu & thừa ở 2 đầu (nếu có) để link đẹp hơn
        $queryString = trim($queryString, '&');


        $getAllSeasons = $this->seasonsModel->getAllSeason();
        $getAllMovies = $this->moviesModel->getAllMovies();
        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeasons' => $getAllSeasons,
            'getAllEpisode' => $getAllEpisode,
            'oldData' => $filter,
            'maxPage' => $maxPage,
            'page' => $page,
            'countResult' => $countResult,
            'queryString' => $queryString
        ];
        $this->renderView('/layout-part/admin/episode/list', $data);
    }

    public function showAdd()
    {
        $getAllSeasons = $this->seasonsModel->getAllSeason();
        $getAllMovies = $this->moviesModel->getAllMovies();
        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeasons' => $getAllSeasons
        ];
        $this->renderView('/layout-part/admin/episode/add', $data);
    }

    public function add() {}
}