<?php
class WatchDetailController extends baseController
{
    private $moviesModel;
    private $genresModel;
    private $episodesModel;
    private $personModel;
    private $commentsModel;
    private $watchHistoryModel;
    public function __construct()
    {
        $this->moviesModel = new Movies();
        $this->genresModel = new Genres();
        $this->episodesModel = new Episode();
        $this->personModel = new Person();
        $this->commentsModel = new Comments();
        $this->watchHistoryModel = new WatchHistory();
    }

    // Hàm đệ quy để tạo cây thư mục comment
    private function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                // Gọi lại chính nó để tìm con của element này
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['replies'] = $children;
                } else {
                    $element['replies'] = [];
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function showWatch()
    {
        $filter = filterData();
        $idMovie = $filter['id'];
        $idEpisode = $filter['episode_id'];

        // Lấy thông tin phim chi tiết
        $condition = 'm.id=' . $idMovie;
        $movieDetail = $this->moviesModel->getMovieDetail($condition);

        // Lấy ID user đang đăng nhập
        if (isset($_SESSION['auth']['id'])) {
            $currentUserId = $_SESSION['auth']['id'];
        } else {
            $currentUserId = 0;
        }

        // Lấy thông tin tập phim
        $episodeDetail = [];
        $currentSeasonId = 0;

        // Lấy thông tin season
        $conditionSeason = 'movie_id=' . $idMovie;
        $seasonDetail = $this->moviesModel->getSeasonDetail($conditionSeason);

        if ($movieDetail['type_id'] == 2) {
            if (!empty($seasonDetail) && is_array($seasonDetail)) {

                $firstSeason = $seasonDetail[0];

                $currentSeasonId = $firstSeason['id'];

                $conditionEpisode = 'season_id=' . $currentSeasonId;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);
            } else {
                $conditionEpisode = 'movie_id=' . $idMovie;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);
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

        // =====================================================================
        // AUTO-REDIRECT: Nếu URL không có episode_id, redirect sang tập đầu tiên
        // =====================================================================
        if (empty($idEpisode) && !empty($episodeDetail)) {
            // Lấy ID tập đầu tiên
            $firstEpisodeId = $episodeDetail[0]['id'];

            // Tạo URL mới với episode_id
            $redirectUrl = _HOST_URL . '/watch?id=' . $idMovie . '&episode_id=' . $firstEpisodeId;

            // Redirect
            header("Location: $redirectUrl");
            exit;
        }
        //--------------------------------------------------------------------------------------
        // LAY LICH SU XEM PHIM
        //-------------------------------------------------------------------------------------
        $startTime = 0;
        //Chi lay lich su neu user da dang nhap va co ID tap phim
        if ($currentUserId > 0 && !empty($idEpisode)) {
            $startTime = $this->watchHistoryModel->getProgress($currentUserId, $idMovie, $idEpisode);
        }
        // Lấy danh sách bình luận
        $comments = $this->commentsModel->getCommentsByMovie($idMovie, $currentUserId, $idEpisode);
        // Xử lý Phân cấp Cha - Con (Tree Structure)
        $commentsTree = $this->buildTree($comments, 0);
        // ĐẾM SỐ LƯỢNG COMMENT CHA 
        $totalComments = count($commentsTree);

        // Lấy phim tương tự
        $similarMovies = $this->moviesModel->getSimilarMovies($idMovie, 12);

        // Lấy thông tin diễn viên
        $getCastByMovieId = $this->personModel->getCastByMovieId($idMovie);

        $data = [
            'idMovie' => $idMovie,
            'idEpisode' => $idEpisode,
            'movieDetail' => $movieDetail,
            'seasonDetail' => $seasonDetail,
            'episodeDetail' => $episodeDetail,
            'currentSeasonId' => $currentSeasonId,
            'getCastByMovieId' => $getCastByMovieId,
            'comments' => $comments,
            'listComments' => $commentsTree,
            'totalComments' => $totalComments,
            'similarMovies' => $similarMovies,
            'startTime' => $startTime

        ];
        $this->renderView('layout-part/client/watch', $data);
    }

    public function saveHistory()
    {
        //Cau hinh header tra ve JSON
        header('Content-Type: application/json');
        //Kiem tra dang nhap
        if (!isset($_SESSION['auth']['id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Unauthorized: Bạn chưa đăng nhập'
            ]);
            return;
        }

        //Nhap du lieu json tu js
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        //validate du lieu dau vao
        if (isset($input['movie_id'], $input['episode_id'], $input['current_time'])) {

            $userId = $_SESSION['auth']['id'];
            $movieId = (int)$input['movie_id'];
            $episodeId = (int)$input['episode_id'];
            $currentTime = (float)$input['current_time'];

            $result = $this->watchHistoryModel->saveProgress($userId, $movieId, $episodeId, $currentTime);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Thanh cong'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'That bai'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Du lieu khong hop le'
            ]);
        }
    }
}
