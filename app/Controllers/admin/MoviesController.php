<?php
class MoviesController extends baseController
{
    private $moviesModel;
    private $genresModel;
    private $personModel;
    private $roleModel;
    public function __construct()
    {
        $this->moviesModel = new Movies;
        $this->genresModel = new Genres;
        $this->personModel = new Person;
        $this->roleModel = new Role;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $countries = '';
        $genres = '';
        $status = '';
        $types = '';
        $keyword = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['status'])) {
                $status = $filter['status'];
            }
            if (isset($filter['types'])) {
                $types = $filter['types'];
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

            if (!empty($types)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "mt.id = $types";
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
        LEFT JOIN movie_types mt ON mt.id = m.type_id
        $chuoiWhere";
        $countMovies = $this->moviesModel->getRowMovies($sqlCount);
        $countResult = $this->moviesModel->getAllMovies($sqlCount);
        $maxData = $countResult[0]['total'];
        $perPage = 20;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (!isset($filter['page'])) {
            $page = $filter['page'];
        }

        // Nếu không có dữ liệu (maxPage = 0), gán mặc định là 1 để tránh lỗi chia/trừ số âm
        if ($maxPage < 1) {
            $maxPage = 1;
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


        $getAllGenres = $this->genresModel->getAllGenres();
        $getCountries = $this->moviesModel->getAllCountries();
        $getStatus = $this->moviesModel->getAllMoviesStatus();
        $getAllMovieTypes = $this->moviesModel->getAllType();
        $getMovies = $this->moviesModel->getAllMovies("SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ',') as genres, ms.name as movie_status, c.name as country_name, mt.name as type_name
        FROM movies as m
        LEFT JOIN movie_genres mg ON m.id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.id
        LEFT JOIN movie_status ms ON ms.id = m.status_id 
        LEFT JOIN countries c ON c.id = m.country_id
        LEFT JOIN movie_types mt ON mt.id = m.type_id
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
            'getAllMovieTypes' => $getAllMovieTypes,
            'maxPage' => $maxPage,
            'page' => $page,
            'queryString' => $queryString,
            'countries' => $countries,
            'genres' => $genres,
            'status' => $status,
            'countMovies' => $countMovies
        ];
        $this->renderView('/layout-part/admin/movies/list', $data);
    }

    public function showAdd()
    {
        $getAllGenres = $this->genresModel->getAllGenres();
        $getAllStatus = $this->moviesModel->getAllStatus();
        $getAllCountries = $this->moviesModel->getAllCountries();
        $getAllType = $this->moviesModel->getAllType();
        $getAllPersons = $this->personModel->getAllPersonsSimple();
        $getAllRoles = $this->roleModel->getAllRole();

        $data = [
            'getAllGenres' => $getAllGenres,
            'getAllStatus' => $getAllStatus,
            'getAllCountries' => $getAllCountries,
            'getAllType' => $getAllType,
            'getAllPersons' => $getAllPersons,
            'getAllRoles' => $getAllRoles
        ];
        $this->renderView('/layout-part/admin/movies/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate tittle
            if (empty(trim($filter['tittle']))) {
                $errors['tittle']['required'] = ' Tên phim bắt buộc phải nhập';
            } else {
                $tittle = trim($filter['tittle']);
                $checkTittle = $this->moviesModel->getRowMovies("SELECT * FROM movies WHERE tittle = '$tittle'");
                if ($checkTittle >= 1) {
                    $errors['tittle']['check'] = ' Phim đã tồn tại ';
                }
            }

            //validate original_tittle
            if (empty(trim($filter['original_title']))) {
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

            if (empty($errors)) {
                $data = [
                    'tittle' => $filter['tittle'],
                    'original_tittle' => $filter['original_title'],
                    'slug' => $filter['slug'],
                    'release_year' => $filter['release_year'],
                    'duration' => $filter['duration'],
                    'country_id' => $filter['country_id'],
                    'type_id' => $filter['type_id'],
                    'imdb_rating' => $filter['imdb_rating'],
                    'status_id' => $filter['status_id'],
                    'poster_url' => $filter['poster_url'],
                    'thumbnail' => $filter['thumbnail'],
                    'img' => $filter['img'],
                    'trailer_url' => $filter['trailer_url'],
                    'description' => $filter['description'],
                    'total_views' => $filter['total_views'],
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $checkInsert = $this->moviesModel->insertMovies('movies', $data);
                if ($checkInsert) {
                    $movie_id = $this->moviesModel->getLastIdMovies();
                    $genre_id = $filter['genre_id'];
                    if (!empty($genre_id)) {
                        foreach ($genre_id as $item) {
                            $data = [
                                'movie_id' => $movie_id,
                                'genre_id' => $item
                            ];
                            $checkInsertMovie_Genres = $this->moviesModel->insertMoviesGenres($data);
                            if ($checkInsertMovie_Genres) {
                                $person_id = $filter['person_id'];
                                if (!empty($filter['cast_person']) && !empty($filter['cast_role'])) {
                                    $persons = $filter['cast_person']; // Mảng ID diễn viên
                                    $roles   = $filter['cast_role'];   // Mảng ID vai trò tương ứng

                                    for ($i = 0; $i < count($persons); $i++) {
                                        if (!empty($persons[$i]) && !empty($roles[$i])) {
                                            $dataCast = [
                                                'movie_id'  => $movie_id,
                                                'person_id' => $persons[$i],
                                                'role_id'   => $roles[$i]
                                            ];
                                            $checkInsertPerson = $this->personModel->insertMoviePerson($dataCast);
                                            if ($checkInsertPerson) {
                                                setSessionFlash('msg', 'Thêm phim mới thất bại');
                                                setSessionFlash('msg_type', 'danger');
                                            } else {
                                                setSessionFlash('msg', 'Thêm phim mới thất bại');
                                                setSessionFlash('msg_type', 'danger');
                                                setSessionFlash('oldData', $filter);
                                                setSessionFlash('errors', $errors);
                                            }
                                        }
                                    }
                                }
                            } else {
                                setSessionFlash('msg', 'Thêm phim mới thất bại');
                                setSessionFlash('msg_type', 'danger');
                                setSessionFlash('oldData', $filter);
                                setSessionFlash('errors', $errors);
                            }
                        }
                    }
                } else {
                    setSessionFlash('msg', 'Thêm phim mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                    reload('/admin/film/add');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/film/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $idMovie = $filter['id'];
        $condition = 'id=' . $idMovie;
        $result = $this->moviesModel->getOneMovie($condition);
        $listAllGenres = $this->genresModel->getAllGenres();
        $getAllCountries = $this->moviesModel->getAllCountries();
        $getAllStatus = $this->moviesModel->getAllStatus();
        $getAllType = $this->moviesModel->getAllType();
        $condition2 = 'movie_id=' . $idMovie;
        $movieGenresData = $this->moviesModel->getAllMoviesGenres("SELECT * FROM movie_genres WHERE $condition2");
        //Chuyen doi thanh mang 1 chieu chua cac id
        $selectedGenresId = [];

        // Kiểm tra biến $movieGenresData (dữ liệu từ DB) chứ không phải $selectedGenresId
        if (!empty($movieGenresData)) {
            $selectedGenresId = array_column($movieGenresData, 'genre_id');
        }

        $currentCast = $this->personModel->getCastByMovieId($idMovie);
        $getAllPersons = $this->personModel->getAllPersonsSimple();
        $getAllRoles   = $this->roleModel->getAllRole();
        $data = [
            'idMovie' => $idMovie,
            'oldData' => $result,
            'listAllGenres' => $listAllGenres,
            'selectedGenresId' => $selectedGenresId,
            'getAllCountries' => $getAllCountries,
            'getAllStatus' => $getAllStatus,
            'getAllType' => $getAllType,
            'currentCast'   => $currentCast,
            'getAllPersons' => $getAllPersons,
            'getAllRoles'   => $getAllRoles
        ];
        $this->renderView('/layout-part/admin/movies/edit', $data);
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
            //validate tittle
            if (empty(trim($filter['tittle']))) {
                $errors['tittle']['required'] = ' Tên phim bắt buộc phải nhập';
            }

            //validate original_tittle
            if (empty(trim($filter['original_title']))) {
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
                $dataUpdate = [
                    'tittle' => $filter['tittle'],
                    'original_tittle' => $filter['original_title'],
                    'slug' => $filter['slug'],
                    'release_year' => $filter['release_year'],
                    'duration' => $filter['duration'],
                    'country_id' => $filter['country_id'],
                    'type_id' => $filter['type_id'],
                    'status_id' => $filter['status_id'],
                    'poster_url' => $filter['poster_url'],
                    'thumbnail' => $filter['thumbnail'],
                    'img' => $filter['img'],
                    'trailer_url' => $filter['trailer_url'],
                    'description' => $filter['description'],
                    'total_views' => $filter['total_views'],
                    'updated_at' => date('Y:m:d H:i:s')
                ];

                // ID phim cần sửa
                $idMovie = $filter['idMovie'];
                $conditionUpdate = 'id=' . $idMovie;

                $checkUpdate = $this->moviesModel->updateMovies($dataUpdate, $conditionUpdate);

                if ($checkUpdate) {
                    //Xóa sạch liên kết cũ của phim này với các thể loại
                    $this->moviesModel->deleteMovieGenres("movie_id = $idMovie");
                    // Kiểm tra xem người dùng có tick chọn genre nào không
                    if (isset($filter['genre_id']) && !empty($filter['genre_id'])) {
                        foreach ($filter['genre_id'] as $genreId) {
                            $dataGenre = [
                                'movie_id' => $idMovie,
                                'genre_id' => $genreId
                            ];
                            $this->moviesModel->insertMoviesGenres($dataGenre);
                        }
                    }
                    $this->personModel->deleteMoviePerson("movie_id = $idMovie");

                    // 2. Thêm dữ liệu mới
                    if (!empty($filter['cast_person']) && !empty($filter['cast_role'])) {
                        $persons = $filter['cast_person'];
                        $roles   = $filter['cast_role'];

                        for ($i = 0; $i < count($persons); $i++) {
                            if (!empty($persons[$i]) && !empty($roles[$i])) {
                                $dataCast = [
                                    'movie_id'  => $idMovie,
                                    'person_id' => $persons[$i],
                                    'role_id'   => $roles[$i]
                                ];
                                $this->personModel->insertMoviePerson($dataCast);
                            }
                        }
                    }
                    setSessionFlash('msg', 'Cập nhật thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/film/list');
                } else {
                    setSessionFlash('msg', 'Cập nhật thất bại (Lỗi Database)');
                    setSessionFlash('msg_type', 'danger');
                    reload('/admin/film/edit?id=' . $idMovie);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                // Giữ lại ID trên URL để không bị lỗi trang trắng
                reload('/admin/film/edit?id=' . $filter['idMovie']);
            }
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter)) {
            $movie_id = $filter['id'];
            $condition = 'id=' . $movie_id;
            $checkID = $this->moviesModel->getOneMovie($condition);
            if (!empty($checkID)) {
                $conditionDeleteMovieGenres = 'movie_id=' . $movie_id;
                $deleteMovieGenres = $this->moviesModel->deleteMovieGenres($conditionDeleteMovieGenres);
                if ($deleteMovieGenres) {
                    $condionDeleteMovie = 'id=' . $movie_id;
                    $deleteMovie = $this->moviesModel->deleteMovie($condionDeleteMovie);
                    if ($deleteMovie) {
                        setSessionFlash('msg', 'Xoá bài viết thành công.');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/film/list');
                    }
                } else {
                    setSessionFlash('msg', 'Xoá bài viết thất bại.');
                    setSessionFlash('msg_type', 'danger');
                    reload('/admin/film/list');
                }
            } else {
                setSessionFlash('msg', 'Bài viết không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/film/list');
            }
        } else {
            setSessionFlash('msg', 'Xoá bài viết thất bại.');
            setSessionFlash('msg_type', 'danger');
        }
    }

    public function showView()
    {
        $data = [];
        $this->renderView('/layout-part/admin/movies/view', $data);
    }
}
