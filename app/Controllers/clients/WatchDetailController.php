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

    public function showWatch($slug = null)
    {
        $filter = filterData();

        $ssNumber = isset($filter['ss']) ? (int)$filter['ss'] : null;
        $epNumber = isset($filter['ep']) ? (int)$filter['ep'] : null;

        // Kiểm tra slug
        if (empty($slug)) {
            reload('/');
        }

        // Lấy thông tin phim theo slug
        $movieDetail = $this->moviesModel->findBySlug($slug);

        if (!$movieDetail) {
            reload('/');
        }

        $idMovie = $movieDetail['id'];
        $isSeries = ($movieDetail['type_id'] == 2);

        // Tang view phim
        $this->moviesModel->incrementMovieView($idMovie);

        // Lấy ID user đang đăng nhập
        $currentUserId = isset($_SESSION['auth']['id']) ? $_SESSION['auth']['id'] : 0;

        // Khởi tạo biến
        $episodeDetail = [];
        $seasonDetail = [];
        $currentSeasonId = 0;
        $currentEpisodeId = null;
        $currentSeasonNumber = 1;
        $currentEpisodeNumber = 1;

        // =====================================================================
        //  XỬ LÝ PHIM BỘ (type_id = 2)
        // =====================================================================
        if ($isSeries) {
            // Lấy danh sách season của phim
            $seasonDetail = $this->moviesModel->getSeasonsByMovieId($idMovie);

            if (!empty($seasonDetail)) {
                // Có season - xử lý theo ss và ep
                $currentSeasonNumber = $ssNumber ?: 1;

                // Lấy season theo số thứ tự
                $currentSeason = $this->moviesModel->getSeasonByNumber($idMovie, $currentSeasonNumber);

                if ($currentSeason) {
                    $currentSeasonId = $currentSeason['id'];

                    // Lấy danh sách tập của season
                    $episodeDetail = $this->moviesModel->getEpisodesBySeasonId($currentSeasonId);

                    // Xác định tập hiện tại
                    $currentEpisodeNumber = $epNumber ?: 1;
                    $currentEpisode = $this->moviesModel->getEpisodeBySeasonAndNumber($currentSeasonId, $currentEpisodeNumber);

                    if ($currentEpisode) {
                        $currentEpisodeId = $currentEpisode['id'];
                    } elseif (!empty($episodeDetail)) {
                        // Nếu không tìm thấy tập, lấy tập đầu tiên
                        $currentEpisodeId = $episodeDetail[0]['id'];
                        $currentEpisodeNumber = 1;
                    }
                } else {
                    // Season không tồn tại, redirect về ss=1
                    reload('/xem-phim/' . $slug . '?ss=1&ep=1');
                }
            } else {
                // Phim bộ nhưng không có season - lấy episodes trực tiếp
                $conditionEpisode = 'movie_id=' . $idMovie;
                $episodeDetail = $this->moviesModel->getEpisodeDetail($conditionEpisode);

                $currentEpisodeNumber = $epNumber ?: 1;
                if (!empty($episodeDetail)) {
                    $index = $currentEpisodeNumber - 1;
                    if (isset($episodeDetail[$index])) {
                        $currentEpisodeId = $episodeDetail[$index]['id'];
                    } else {
                        $currentEpisodeId = $episodeDetail[0]['id'];
                        $currentEpisodeNumber = 1;
                    }
                }
            }

            // Nếu URL không có ss/ep, redirect sang tập đầu tiên
            if ($ssNumber === null && $epNumber === null && !empty($episodeDetail)) {
                if (!empty($seasonDetail)) {
                    reload('/xem-phim/' . $slug . '?ss=1&ep=1');
                } else {
                    reload('/xem-phim/' . $slug . '?ep=1');
                }
            }
        }
        // =====================================================================
        //  XỬ LÝ PHIM LẺ (type_id != 2)
        // =====================================================================
        else {
            // Phim lẻ - lấy video source
            $sourceInfo = $this->moviesModel->getVideoSources($idMovie);

            if (!empty($sourceInfo)) {
                $episodeDetail[] = [
                    'id' => $sourceInfo['episode_id'],
                    'name' => $sourceInfo['voice_type'],
                    'link' => $sourceInfo['source_url'],
                ];
                $currentEpisodeId = $sourceInfo['episode_id'];
            }
        }

        //--------------------------------------------------------------------------------------
        // LẤY LỊCH SỬ XEM PHIM
        //-------------------------------------------------------------------------------------
        $startTime = 0;
        if ($currentUserId > 0 && !empty($currentEpisodeId)) {
            $startTime = $this->watchHistoryModel->getProgress($currentUserId, $idMovie, $currentEpisodeId, $currentSeasonId ?: null);
        }

        // Lấy danh sách bình luận
        $comments = $this->commentsModel->getCommentsByMovie($idMovie, $currentUserId, $currentEpisodeId);
        $commentsTree = $this->buildTree($comments, 0);
        $totalComments = count($commentsTree);

        // Lấy phim tương tự
        $similarMovies = $this->moviesModel->getSimilarMovies($idMovie, 12);

        // Lấy thông tin diễn viên
        $getCastByMovieId = $this->personModel->getCastByMovieId($idMovie);

        $countAllCommentsByMovie = $this->commentsModel->countCommentsByMovie($idMovie, $currentEpisodeId);

        $data = [
            'idMovie' => $idMovie,
            'idEpisode' => $currentEpisodeId,
            'movieDetail' => $movieDetail,
            'seasonDetail' => $seasonDetail,
            'episodeDetail' => $episodeDetail,
            'currentSeasonId' => $currentSeasonId,
            'currentSeasonNumber' => $currentSeasonNumber,
            'currentEpisodeNumber' => $currentEpisodeNumber,
            'getCastByMovieId' => $getCastByMovieId,
            'comments' => $comments,
            'listComments' => $commentsTree,
            'totalComments' => $totalComments,
            'countAllCommentsByMovie' => $countAllCommentsByMovie[0]['total'] ?? 0,
            'similarMovies' => $similarMovies,
            'startTime' => $startTime,
            'isSeries' => $isSeries
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
            $seasonId = isset($input['season_id']) ? (int)$input['season_id'] : null;
            $currentTime = (float)$input['current_time'];

            $result = $this->watchHistoryModel->saveProgress($userId, $movieId, $episodeId, $seasonId, $currentTime);

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
