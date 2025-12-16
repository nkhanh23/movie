<?php

class CommentController extends baseController
{
    private $commentModel;
    private $movieModel;
    private $userModel;
    public function __construct()
    {
        $this->commentModel = new Comments;
        $this->movieModel = new Movies;
        $this->userModel = new User;
    }
    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $movie_id = '';
        $user_id = '';
        $status = '';
        $keyword = '';
        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['movie_id'])) {
                $movie_id = $filter['movie_id'];
            }
            if (isset($filter['user_id'])) {
                $user_id = $filter['user_id'];
            }
            if (isset($filter['status'])) {
                $status = $filter['status'];
            }

            if (!empty($movie_id)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "c.movie_id = $movie_id";
            }
            if (!empty($user_id)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "c.user_id = $user_id";
            }
            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "c.content LIKE '%$keyword%' OR u.fullname LIKE '%$keyword%' OR u.email LIKE '%$keyword%'";
            }
            if (isset($status) && $status !== '' && is_numeric($status)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                // Dùng alias c.status cho an toàn
                $chuoiWhere .= "c.status = $status";
            }
        }

        $maxData = $this->commentModel->countComment("SELECT
        c.*, m.tittle, u.fullname, u.email, s.name AS season_name, e.name AS episode_name   
        FROM comments c
        JOIN movies m ON c.movie_id = m.id
        JOIN users u ON c.user_id = u.id
        LEFT JOIN episodes e ON c.episode_id = e.id        
        LEFT JOIN seasons s ON c.season_id = s.id         
        $chuoiWhere");
        $perPage = 20;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (isset($filter['page'])) {
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

        $getAllComment = $this->commentModel->getAllComment("SELECT
        c.*, m.tittle, u.fullname, u.email, s.name AS season_name, e.name AS episode_name   
        FROM comments c
        JOIN movies m ON c.movie_id = m.id
        JOIN users u ON c.user_id = u.id
        LEFT JOIN episodes e ON c.episode_id = e.id        
        LEFT JOIN seasons s ON c.season_id = s.id         
        $chuoiWhere
        ORDER BY c.created_at ASC
        LIMIT $offset , $perPage");
        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }

        $getAllMovies = $this->movieModel->getAllMovies();
        $getAllUsers = $this->userModel->getAllUser();
        $data = [
            'getAllComment' => $getAllComment,
            'keyword'    => $keyword,
            'maxPage' => $maxPage,
            'maxData' => $maxData,
            'queryString' => $queryString,
            'page' => $page,
            'getAllMovies' => $getAllMovies,
            'getAllUsers' => $getAllUsers,
            'movie_id' => $movie_id,
            'user_id' => $user_id,
            'status' => $status,
        ];
        $this->renderView('/layout-part/admin/comments/list', $data);
    }

    public function delete() {}
}
