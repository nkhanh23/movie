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


        $getAllEpisode = $this->episodeModel->getAllEpisode("SELECT e.*, m.tittle as movie_name, e.name as episode_name, s.name as season_name
        FROM  episodes e
        LEFT JOIN movies m ON m.id = e.movie_id
        LEFT JOIN seasons s ON e.season_id = s.id
        $chuoiWhere
        ORDER BY m.created_at DESC
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
        $filterGet = filterData('get');
        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeasons' => $getAllSeasons,
            'getAllEpisode' => $getAllEpisode,
            'oldData' => $filter,
            'maxPage' => $maxPage,
            'page' => $page,
            'countResult' => $countResult,
            'queryString' => $queryString,
            'filterGet' => $filterGet
        ];
        $this->renderView('/layout-part/admin/episode/list', $data);
    }

    public function showAdd()
    {
        $getAllSeasons = $this->seasonsModel->getAllSeason();
        $getAllMovies = $this->moviesModel->getAllMovies();
        $getAllVideoSource = $this->episodeModel->getAllVideoSource();
        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeasons' => $getAllSeasons,
            'getAllVideoSource' => $getAllVideoSource

        ];
        $this->renderView('/layout-part/admin/episode/add', $data);
    }

    public function add()
    {
        $filterGet = filterData('get');
        $idMovie = $filterGet['id'];
        $idSeason = $filterGet['season_id'];

        if (isPost()) {
            $filter = filterData();
            $errors = [];

            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên tập bắt buộc phải nhập';
            }

            if (empty(trim($filter['server_name']))) {
                $errors['server_name']['required'] = ' Tên server bắt buộc phải nhập';
            }

            if (empty(trim($filter['duration']))) {
                $errors['duration']['required'] = ' Thời lượng bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'movie_id' => $idMovie,
                    'season_id' => $idSeason,
                    'name' => $filter['name'],
                    'video_source_id' => $filter['video_source_id'],
                    'duration' => $filter['duration'],
                    'server_name' => $filter['server_name'],
                    'created_at' => date('Y:m:d H:i:s'),
                ];
                $checkInsert = $this->episodeModel->insertEpisode($data);
                if ($checkInsert) {
                    setSessionFlash('msg', 'Thêm tập mới thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/episode?filter-movie-id=' . $idMovie . '&season_id=' . $idSeason);
                } else {
                    setSessionFlash('msg', 'Thêm tập mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/episode/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $idEpisode = $filter['id'];
        $conditionGetOneEpisode = 'id=' . $idEpisode;
        $getOneEpisode = $this->episodeModel->getOneEpisode($conditionGetOneEpisode);
        $getAllVideoSource = $this->episodeModel->getAllVideoSource();

        $data = [
            'oldData' => $getOneEpisode,
            'getAllVideoSource' => $getAllVideoSource,
            'idEpisode' => $idEpisode
        ];
        $this->renderView('/layout-part/admin/episode/edit', $data);
    }

    public function edit()
    {
        $filter = filterData();

        $errors = [];

        if (empty(trim($filter['name']))) {
            $errors['name']['required'] = ' Tên tập bắt buộc phải nhập';
        }

        if (empty(trim($filter['server_name']))) {
            $errors['server_name']['required'] = ' Tên server bắt buộc phải nhập';
        }

        if (empty(trim($filter['duration']))) {
            $errors['duration']['required'] = ' Thời lượng bắt buộc phải nhập';
        }

        if (empty($errors)) {
            $data = [
                'name' => $filter['name'],
                'server_name' => $filter['server_name'],
                'video_source_id' => $filter['video_source_id'],
                'duration' => $filter['duration'],
                'updated_at' => date('Y:m:d H:i:s'),
            ];
            $condition = 'id=' . $filter['idEpisode'];
            $checkUpdate = $this->episodeModel->updateEpisode($data, $condition);
            if ($checkUpdate) {
                setSessionFlash('msg', 'Cập nhật thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/episode');
            } else {
                setSessionFlash('msg', 'Cập nhật thất bại');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/episode/edit?id=' . $filter['idEpisode']);
            }
        } else {
            setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('errors', $errors);
            reload('/admin/episode/edit');
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter)) {
            $episode_id = $filter['id'];
            $condition = 'id=' . $episode_id;
            $checkDelete = $this->episodeModel->deleteEpisode($condition);
            if ($checkDelete) {
                setSessionFlash('msg', 'Xóa tập phim thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/episode');
            } else {
                setSessionFlash('msg', 'Xóa tập phim thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/episode');
            }
        } else {
            setSessionFlash('msg', 'Xoá mùa thất bại.');
            setSessionFlash('msg_type', 'danger');
        }
    }
}
