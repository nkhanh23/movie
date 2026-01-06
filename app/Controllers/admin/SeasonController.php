<?php
class SeasonController extends baseController
{
    private $episodeModel;
    private $moviesModel;
    private $seasonsModel;
    private $activityModel;
    public function __construct()
    {
        $this->episodeModel = new Episode;
        $this->moviesModel = new Movies;
        $this->seasonsModel = new Season;
        $this->activityModel = new Activity;
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
                $chuoiWhere .= "s.movie_id = '$movieId'";
            }

            if (!empty($seasonId)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "s.id = '$seasonId'";
            }

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
        ORDER BY m.created_at DESC
        LIMIT $offset, $perPage");

        //Xử lý quẻy
        $queryString = $_SERVER['QUERY_STRING'];

        // 1. Xóa trường hợp "&page=..." (khi page nằm sau tham số khác)
        $queryString = str_replace('&page=' . $page, '', $queryString);

        // 2. Xóa trường hợp "page=..." (khi page nằm đầu tiên)
        $queryString = str_replace('page=' . $page, '', $queryString);

        // 3. Xóa dấu & thừa ở 2 đầu (nếu có) để link đẹp hơn
        $queryString = trim($queryString, '&');

        $getAllMovies = $this->moviesModel->getAllMovies();

        $filterGet = filterData('get');

        $data = [
            'getAllMovies' => $getAllMovies,
            'getAllSeason' => $getAllSeason,
            'oldData' => $filter,
            'maxPage' => $maxPage,
            'page' => $page,
            'countResult' => $countResult,
            'queryString' => $queryString,
            'filterGet' => $filterGet
        ];
        $this->renderView('/layout-part/admin/season/list', $data);
    }

    public function showAdd()
    {

        $getAllStatus = $this->moviesModel->getAllStatus();
        $data = [
            'getAllStatus' => $getAllStatus
        ];
        $this->renderView('/layout-part/admin/season/add', $data);
    }

    public function add()
    {
        $idMovie = filterData('get');
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên mùa bắt buộc phải nhập';
            }

            if (empty(trim($filter['description']))) {
                $errors['description']['required'] = ' Chi tiết bắt buộc phải nhập';
            }

            if (empty(trim($filter['poster_url']))) {
                $errors['poster_url']['required'] = ' Poster URL bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'movie_id' => $idMovie['id'],
                    'name' => $filter['name'],
                    'description' => $filter['description'],
                    'poster_url' => $filter['poster_url'],
                    'trailer_url' => $filter['trailer_url'],
                    'status_id' => $filter['status_id'],
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $checkInsert = $this->seasonsModel->insertSeason($data);
                if ($checkInsert) {
                    $season_id = $this->seasonsModel->getLastInsertId();
                    // Ghi log
                    $logData = [
                        'tittle' => $data['name'],
                        'slug' => $data['slug']
                    ];
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'create',
                        'seasons',
                        $season_id,
                        null,
                        $logData
                    );
                    setSessionFlash('msg', 'Thêm mùa mới thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/season?filter-movie-id=' . $idMovie['id']);
                } else {
                    setSessionFlash('msg', 'Thêm mùa mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/season/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $idSeason = $filter['id'];
        $conditionGetOneSeason = 'id=' . $idSeason;
        $getOneSeason = $this->seasonsModel->getOneSeason($conditionGetOneSeason);
        $getAllStatus = $this->moviesModel->getAllStatus();

        $data = [
            'oldData' => $getOneSeason,
            'getAllStatus' => $getAllStatus,
            'idSeason' => $idSeason
        ];
        $this->renderView('/layout-part/admin/season/edit', $data);
    }

    public function edit()
    {
        if (isPost()) {
            $filter = filterData();
            // echo '<pre>';
            // print_r($filter);
            // echo '</pre>';
            // die();
            $errors = [];

            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên mùa bắt buộc phải nhập';
            }

            if (empty(trim($filter['description']))) {
                $errors['description']['required'] = ' Chi tiết bắt buộc phải nhập';
            }

            if (empty(trim($filter['poster_url']))) {
                $errors['poster_url']['required'] = ' Poster URL bắt buộc phải nhập';
            }
        }

        if (empty($errors)) {
            $data = [
                'name' => $filter['name'],
                'description' => $filter['description'],
                'poster_url' => $filter['poster_url'],
                'trailer_url' => $filter['trailer_url'],
                'status_id' => $filter['status_id'],
                'updated_at' => date('Y:m:d H:i:s')
            ];

            $condition = 'id=' . $filter['id'];
            $oldData = $this->seasonsModel->getOneSeason($condition);
            $checkUpdate = $this->seasonsModel->updateSeason($data, $condition);
            if ($checkUpdate) {
                // Lặp qua dataUpdate để xem trường nào thay đổi
                $changes = [];
                foreach ($data as $key => $value) {
                    if ($oldData[$key] != $value) {
                        $changes[$key] = [
                            'from' => $oldData[$key],
                            'to' => $value
                        ];
                    }
                }
                //ghi log
                if (!empty($changes)) {
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'update',
                        'seasons',
                        $filter['id'],
                        $oldData,
                        $data
                    );
                }
                setSessionFlash('msg', 'Cập nhật thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/season?filter-movie-id=' . $filter['movie_id']);
            } else {
                setSessionFlash('msg', 'Cập nhật thất bại');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/season?filter-movie-id=' . $filter['movie_id']);
            }
        } else {
            setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('errors', $errors);
            reload('/admin/season/edit?id=' . $filter['id']);
        }
    }
    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter)) {
            $season_id = $filter['id'];
            $condition = 'id=' . $season_id;
            $checkID = $this->seasonsModel->getOneSeason($condition);
            if (!empty($checkID)) {
                $conditionDeleteSeason = 'id=' . $season_id;
                $deleteSeason = $this->seasonsModel->deleteSeason($conditionDeleteSeason);
                if ($deleteSeason) {
                    // GHI LOG
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'delete',
                        'seasons',
                        $season_id,
                        $checkID, // Lưu data cũ để audit
                        null
                    );
                    setSessionFlash('msg', 'Xoá mùa thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/season');
                } else {
                    setSessionFlash('msg', 'Xoá mùa thất bại.');
                    setSessionFlash('msg_type', 'danger');
                    reload('/admin/season');
                }
            } else {
                setSessionFlash('msg', 'Mùa phim không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/season');
            }
        } else {
            setSessionFlash('msg', 'Xoá mùa thất bại.');
            setSessionFlash('msg_type', 'danger');
        }
    }
}
