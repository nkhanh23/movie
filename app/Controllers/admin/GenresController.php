<?php
class GenresController extends baseController
{
    private $genresModel;
    private $activityModel;
    public function __construct()
    {
        $this->genresModel = new Genres;
        $this->activityModel = new Activity;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $keyword = '';
        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "g.name LIKE '%$keyword%'";
            }
        }
        //Xử lí phân trang
        $sql = "SELECT g.*, COUNT(mg.movie_id) as count_movies
        FROM genres g
        LEFT JOIN movie_genres mg ON g.id = mg.genre_id
        $chuoiWhere
        GROUP BY g.id";
        $countGenres = $this->genresModel->CountGenres($sql);
        $maxData = $this->genresModel->CountGenres($sql);
        $perPage = 50;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        if ($page < 1) {
            $page = 1;
        }

        // Nếu không có dữ liệu (maxPage = 0), gán mặc định là 1 để tránh lỗi chia/trừ số âm
        if ($maxPage < 1) {
            $maxPage = 1;
        }

        if ($page > $maxPage) {
            $page = $maxPage;
        }
        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        $getAllGenres = $this->genresModel->getAllGenresWithCount(" SELECT g.*, COUNT(mg.movie_id) as count_movies
        FROM genres g
        LEFT JOIN movie_genres mg ON g.id = mg.genre_id
        $chuoiWhere
        GROUP BY g.id
        ORDER BY created_at DESC
        LIMIT $offset , $perPage
        ");
        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'getAllGenres' => $getAllGenres,
            'maxPage' => $maxPage,
            'page' => $page,
            'queryString' => $queryString,
            'countGenres' => $countGenres
        ];
        $this->renderView('/layout-part/admin/genres/list', $data);
    }

    public function showAdd()
    {
        $data = [];

        $this->renderView('/layout-part/admin/genres/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            //validate name
            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên thể loại bắt buộc phải nhập';
            }

            //validate slug
            if (empty(trim($filter['slug']))) {
                $errors['slug']['required'] = ' Đường dẫn bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $filter['name'],
                    'slug' => $filter['slug'],
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $checkInsert = $this->genresModel->insertGenres($data);
                if ($checkInsert) {
                    $getLastGenreInsert = $this->genresModel->getLastInsertId();
                    // Ghi log
                    $logData = [
                        'name' => $data['name'],
                        'slug' => $data['slug']
                    ];
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'create',
                        'genres',
                        $getLastGenreInsert,
                        null,
                        $logData
                    );
                    setSessionFlash('msg', 'Thêm thể loại mới thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/genres');
                } else {
                    setSessionFlash('msg', 'Thêm thể loại mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                    reload('/admin/genres/add');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/genres/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData();
        $idGenres = $filter['id'];
        $condition = 'id=' . $idGenres;
        $listAllGenres = $this->genresModel->getOneGenres($condition);
        $data = [
            'oldData' => $listAllGenres,
            'idGenres' => $idGenres
        ];
        $this->renderView('/layout-part/admin/genres/edit', $data);
    }

    public function edit()
    {
        $filter = filterData();
        $idGenres = $filter['id'];

        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate name
            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên thể loại bắt buộc phải nhập';
            }

            //validate slug
            if (empty(trim($filter['slug']))) {
                $errors['slug']['required'] = ' Đường dẫn bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $filter['name'],
                    'slug' => $filter['slug'],
                    'updated_at' => date('Y:m:d H:i:m')
                ];
                $condition = 'id=' . $filter['id'];
                $oldData = $this->genresModel->getOneGenres($condition);
                $checkUpdate = $this->genresModel->updateGenres($data, $condition);
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
                            'genres',
                            $filter['id'],
                            $oldData,
                            $data
                        );
                    }
                    setSessionFlash('msg', 'Cập nhật thể loại thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/genres');
                } else {
                    setSessionFlash('msg', 'Cập nhật thể loại thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('errors', $errors);
                    reload('/admin/genres/edit?id=' . $idGenres);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                reload('/admin/genres/edit?id=' . $idGenres);
            }
        }
    }

    public function delete()
    {
        $filter = filterData();
        if (!empty($filter['id'])) {
            $idGenres = $filter['id'];
            $conditionGetOneGenres = 'id=' . $idGenres;
            $checkID = $this->genresModel->getOneGenres($conditionGetOneGenres);
            if (!empty($checkID)) {
                $conditionDeleteMovieGenres = 'genre_id=' . $idGenres;
                $deleteMovieGenres = $this->genresModel->deleteGenres('movie_genres', $conditionDeleteMovieGenres);
                if ($deleteMovieGenres) {
                    $conditionDeleteGenres = 'id=' . $idGenres;
                    $deleteGenres = $this->genresModel->deleteGenres('genres', $conditionDeleteGenres);
                    if ($deleteGenres) {
                        // GHI LOG
                        $this->activityModel->log(
                            $_SESSION['auth']['id'],
                            'delete',
                            'genres',
                            $idGenres,
                            $checkID, // Lưu data cũ để audit
                            null
                        );
                        setSessionFlash('msg', 'Xoá thể loại thành công.');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/genres');
                    } else {
                        setSessionFlash('msg', 'Xoá thể loại thất bại.');
                        setSessionFlash('msg_type', 'danger');
                        reload('/admin/genres');
                    }
                }
            } else {
                setSessionFlash('msg', 'Thể loại không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/genres');
            }
        } else {
            setSessionFlash('msg', 'Xoá thể loại thất bại.');
            setSessionFlash('msg_type', 'danger');
        }
    }
}
