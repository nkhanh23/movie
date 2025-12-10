<?php

class MovieDetailController extends baseController
{
    private $moviesModel;
    private $genresModel;
    private $episodesModel;
    private $personModel;
    private $commentsModel;
    public function __construct()
    {
        $this->moviesModel = new Movies();
        $this->genresModel = new Genres();
        $this->episodesModel = new Episode();
        $this->personModel = new Person();
        $this->commentsModel = new Comments();
    }

    public function showDetail()
    {
        $filter = filterData();
        $idMovie = $filter['id'];

        // Lấy thông tin phim
        $condition = 'id=' . $idMovie;
        $movieDetail = $this->moviesModel->getMovieDetail($condition);

        // Lấy thông tin season
        $conditionSeason = 'movie_id=' . $idMovie;
        $seasonDetail = $this->moviesModel->getSeasonDetail($conditionSeason);

        // Lấy thông tin tập phim
        $episodeDetail = [];
        $currentSeasonId = 0;

        if ($movieDetail['type_id'] == 2) {
            if (!empty($seasonDetail) && is_array($seasonDetail)) {

                $firstSeason = $seasonDetail[0];

                $currentSeasonId = $firstSeason['id'];

                $conditionEpisode = 'season_id=' . $currentSeasonId;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);
            } else {
                $episodeDetail = $this->moviesModel->getAll("SELECT * 
                FROM episodes 
                WHERE movie_id = $idMovie 
                ORDER BY id ASC");
            }
        } else {
            $sourceInfo = $this->moviesModel->getVideoSources($idMovie);

            if (!empty($sourceInfo)) {
                $episodeDetail[] = [
                    'id' => $sourceInfo['id'],
                    'name' => $sourceInfo['voice_type'],
                    'link' => $sourceInfo['source_url'],
                ];
            }
        }

        // Lấy thông tin diễn viên
        $getCastByMovieId = $this->personModel->getCastByMovieId($idMovie);

        // Lấy danh sách bình luận
        $comments = $this->commentsModel->getCommentsByMovie($idMovie);

        $data = [
            'movieDetail' => $movieDetail,
            'seasonDetail' => $seasonDetail,
            'episodeDetail' => $episodeDetail,
            'currentSeasonId' => $currentSeasonId,
            'getCastByMovieId' => $getCastByMovieId,
            'comments' => $comments,
        ];

        $this->renderView('layout-part/client/detail', $data);
    }

    public function getEpisodesApi()
    {
        $filter = filterData('get');
        $seasonId = $filter['season_id'];

        // Gọi Model lấy danh sách tập
        $condition = 'season_id=' . $seasonId;
        $episodes = $this->moviesModel->getEpisodeDetail($condition);

        // Trả về kết quả dạng JSON
        header('Content-Type: application/json');
        echo json_encode($episodes);
        exit; // Dừng chương trình để không load thêm layout
    }

    public function postCommentApi()
    {
        // Kiểm tra đăng nhập (Giả sử bạn lưu session user là 'auth' hoặc 'user')
        if (empty($_SESSION['auth'])) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để bình luận.']);
            return;
        }

        if (isPost()) {
            $movieId = $_POST['movie_id'] ?? 0;
            $content = trim($_POST['content'] ?? '');
            $userId = $_SESSION['auth']['id']; // Lấy ID user từ session

            if (empty($content)) {
                echo json_encode(['status' => 'error', 'message' => 'Nội dung không được để trống.']);
                return;
            }

            $data = [
                'movie_id' => $movieId,
                'user_id' => $userId,
                'content' => $content,
                'parent_id' => 0, // Mặc định là comment gốc
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $insertId = $this->commentsModel->addComment($data);

            if ($insertId) {
                // Trả về dữ liệu để JS append vào giao diện ngay lập tức
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đăng bình luận thành công!',
                    'data' => [
                        'fullname' => $_SESSION['auth']['fullname'],
                        'avatar' => $_SESSION['auth']['avatar'] ?? 'default-avatar.jpg', // Avatar mặc định nếu null
                        'content' => htmlspecialchars($content),
                        'created_at' => 'Vừa xong'
                    ]
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống, vui lòng thử lại.']);
            }
        }
    }
}