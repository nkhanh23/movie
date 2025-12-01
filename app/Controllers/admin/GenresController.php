<?php
class GenresController extends baseController
{
    private $genresModel;
    public function __construct()
    {
        $this->genresModel = new Genres;
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
        $perPage = 3;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        if ($page < 1) {
            $page = 1;
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

            // Fix quan trọng: Xử lý cả 2 trường hợp (có & và không có &)
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
                $checkUpdate = $this->genresModel->updateGenres($data, $condition);
                if ($checkUpdate) {
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

    public function delete() {}
}
