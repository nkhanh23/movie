<?php
class MoviesController extends baseController
{
    private $moviesModel;
    public function __construct()
    {
        $this->moviesModel = new Movies;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $countries = '';
        $genres = '';
        $status = '';
        $keyword = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['status'])) {
                $status = $filter['status'];
            }
            if (isset($filter['genres'])) {
                $genres = $filter['genres'];
            }
            if (isset($filter['countries'])) {
                $countries = $filter['countries'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "m.tittle LIKE '%$keyword%' OR m.original_tittle LIKE '%$keyword%'";
            }

            if (!empty($status)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "ms.id = $status";
            }

            if (!empty($genres)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "g.id = $genres";
            }

            if (!empty($countries)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "c.id = $countries";
            }
        }

        // Xử lí phân trang
        //Tạo câu lệnh SQL để đếm số lượng kết quả tìm được
        $sqlCount = "SELECT count(DISTINCT m.id) as total
        FROM movies as m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_status ms ON ms.id = m.status_id 
        LEFT JOIN countries c ON c.id = m.country_id
        $chuoiWhere";
        $countResult = $this->moviesModel->getAllMovies($sqlCount);
        $maxData = $countResult[0]['total'];
        $perPage = 3;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        if ($page > $maxPage || $page < 1) {
            $page = 1;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }


        $getAllGenres = $this->moviesModel->getAllGenres();
        $getCountries = $this->moviesModel->getAllCoutries();
        $getStatus = $this->moviesModel->getAllMoviesStatus();
        $getMovies = $this->moviesModel->getAllMovies("SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ',') as genres, ms.name as movie_status, c.name as country_name
        FROM movies as m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_status ms ON ms.id = m.status_id 
        LEFT JOIN countries c ON c.id = m.country_id
        $chuoiWhere
        GROUP BY m.id
        ORDER BY m.created_at DESC
        LIMIT $offset , $perPage
        ");
        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'getStatus' => $getStatus,
            'getMovies' => $getMovies,
            'getCountries' => $getCountries,
            'getAllGenres' => $getAllGenres,
            'maxPage' => $maxPage,
            'page' => $page,
            'queryString' => $queryString,
            'countries' => $countries,
            'genres' => $genres,
            'status' => $status
        ];
        $this->renderView('/layout-part/admin/movies/list', $data);
    }

    public function showAdd()
    {
        $this->renderView('/layout-part/admin/movies/add');
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate tittle
            if (empty(trim($filter['tittle']))) {
                $errors['tittle']['required'] = ' Tên phim bắt buộc phải nhập';
            }

            //validate original_tittle
            if (empty(trim($filter['original_tittle']))) {
                $errors['original_tittle']['required'] = ' Tên gốc bắt buộc phải nhập';
            }

            //validate slug
            if (empty(trim($filter['slug']))) {
                $errors['slug']['required'] = ' Đường dẫn bắt buộc phải nhập';
            }

            //validate release_year
            if (empty(trim($filter['release_year']))) {
                $errors['release_year']['required'] = ' Năm phát hành bắt buộc phải nhập';
            }

            //validate duration
            if (empty(trim($filter['duration']))) {
                $errors['duration']['required'] = ' Thời lượng bắt buộc phải nhập';
            }

            if (empty($errors)) {
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/film/add');
            }
        }
    }
}
