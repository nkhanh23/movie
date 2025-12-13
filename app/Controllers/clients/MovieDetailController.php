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

    public function showDetail()
    {
        $filter = filterData();
        $idMovie = $filter['id'];

        // Lấy ID user đang đăng nhập
        if (isset($_SESSION['auth']['id'])) {
            $currentUserId = $_SESSION['auth']['id'];
        } else {
            $currentUserId = 0;
        }

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
        $comments = $this->commentsModel->getCommentsByMovie($idMovie, $currentUserId);
        // Xử lý Phân cấp Cha - Con (Tree Structure)
        $commentsTree = $this->buildTree($comments, 0);
        // ĐẾM SỐ LƯỢNG COMMENT CHA 
        $totalComments = count($commentsTree);

        // Lấy phim tương tự
        $similarMovies = $this->moviesModel->getSimilarMovies($idMovie, 12);


        $data = [
            'idMovie' => $idMovie,
            'movieDetail' => $movieDetail,
            'seasonDetail' => $seasonDetail,
            'episodeDetail' => $episodeDetail,
            'currentSeasonId' => $currentSeasonId,
            'getCastByMovieId' => $getCastByMovieId,
            'comments' => $comments,
            'listComments' => $commentsTree,
            'totalComments' => $totalComments,
            'similarMovies' => $similarMovies
        ];

        $this->renderView('layout-part/client/detail', $data);
    }

    // API Lấy danh sách tập
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

    // API Thêm bình luận
    public function postCommentApi()
    {
        // Bắt đầu Output Buffering để hứng bất kỳ output rác nào (Warning/Error)
        ob_start();

        try {
            header('Content-Type: application/json');

            // Kiểm tra đăng nhập
            if (empty($_SESSION['auth'])) {
                ob_clean(); // Xóa buffer trước khi echo
                echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để bình luận.']);
                exit;
            }

            if (isPost()) {
                $filter = filterData();
                $movieId = $filter['movie_id'] ?? 0;
                $content = isset($filter['content']) ? trim($filter['content']) : '';

                // Kiểm tra key 'id' tồn tại
                if (!isset($_SESSION['auth']['id'])) {
                    throw new Exception('Không tìm thấy thông tin người dùng.');
                }
                $userId = $_SESSION['auth']['id'];

                if (empty($content)) {
                    ob_clean();
                    echo json_encode(['status' => 'error', 'message' => 'Nội dung không được để trống.']);
                    exit;
                }

                $data = [
                    'movie_id' => $movieId,
                    'user_id' => $userId,
                    'content' => $content,
                    'parent_id' => null,
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'), // Format chuẩn
                ];

                $isInserted = $this->commentsModel->addComment($data);

                if ($isInserted) {
                    // Lấy ID vừa insert
                    $insertId = $this->commentsModel->getLastID();

                    ob_clean(); // Xóa mọi output rác có thể đã xuất hiện trước đó
                    // Trả về dữ liệu để JS append vào giao diện ngay lập tức
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Đăng bình luận thành công!',
                        'data' => [
                            'id' => $insertId,
                            'fullname' => $_SESSION['auth']['fullname'] ?? 'Unknown',
                            'avatar' => $_SESSION['auth']['avatar'] ?? 'default-avatar.jpg',
                            'content' => htmlspecialchars($content),
                            'created_at' => 'Vừa xong'
                        ]
                    ]);
                    exit;
                } else {
                    throw new Exception('Lỗi hệ thống: Không thể lưu bình luận.');
                }
            }
        } catch (Throwable $e) {
            // Nếu có lỗi, xóa buffer và trả về JSON lỗi kèm message chi tiết để debug
            ob_clean();
            http_response_code(500); // Set headers error
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi server: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    // API Thích bình luận
    public function likeCommentApi()
    {
        error_reporting(0);
        ob_start();

        try {
            header('Content-Type: application/json');

            if (empty($_SESSION['auth'])) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thích bình luận.']);
                exit;
            }

            if (isPost()) {
                $commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
                $userId = $_SESSION['auth']['id'];

                if ($commentId <= 0) {
                    ob_clean();
                    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
                    exit;
                }

                // Gọi Model xử lý toggle
                $action = $this->commentsModel->toggleLike($userId, $commentId);
                // Lấy số like mới nhất
                $newCount = $this->commentsModel->countLikes($commentId);

                ob_clean();
                echo json_encode([
                    'status' => 'success',
                    'action' => $action, // 'liked' hoặc 'unliked'
                    'likes' => $newCount
                ]);
                exit;
            }
        } catch (Throwable $e) {
            ob_clean();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi server: ' . $e->getMessage()]);
            exit;
        }
    }

    // API Xóa bình luận
    public function deleteCommentApi()
    {
        // 1. Tắt báo lỗi PHP để tránh làm hỏng JSON (Hack fix cho localhost)
        error_reporting(0);
        // 2. Bắt đầu bộ đệm
        ob_start();

        try {
            header('Content-Type: application/json');

            // Kiểm tra đăng nhập
            if (empty($_SESSION['auth'])) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập.']);
                exit;
            }

            if (isPost()) {
                $commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;

                if ($commentId <= 0) {
                    ob_clean();
                    echo json_encode(['status' => 'error', 'message' => 'ID bình luận không hợp lệ.']);
                    exit;
                }

                // Kiểm tra Model đã load chưa
                if (!isset($this->commentsModel)) {
                    throw new Exception("Model Comments chưa được khởi tạo.");
                }

                // Lấy thông tin bình luận
                $comment = $this->commentsModel->getCommentById($commentId);

                if (empty($comment)) {
                    ob_clean();
                    echo json_encode(['status' => 'error', 'message' => 'Bình luận không tồn tại hoặc đã bị xóa.']);
                    exit;
                }

                $currentUserId = $_SESSION['auth']['id'];
                $currentUserGroup = $_SESSION['auth']['group_id'] ?? 0; // Fallback nếu không có group_id

                // XỬ LÝ LOGIC PHÂN QUYỀN

                // TRƯỜNG HỢP 1: ADMIN XÓA (group_id = 2) -> Cập nhật nội dung
                if ($currentUserGroup == 2) {
                    $dataUpdate = [
                        'content' => 'Bình luận này đã bị Admin xóa do vi phạm quy tắc cộng đồng.',
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $this->commentsModel->update('comments', $dataUpdate, "id = $commentId");

                    ob_clean();
                    echo json_encode([
                        'status' => 'success',
                        'action' => 'hide',
                        'message' => 'Đã ẩn bình luận vi phạm.',
                        'new_content' => $dataUpdate['content']
                    ]);
                    exit;
                }

                // TRƯỜNG HỢP 2: USER XÓA (Chính chủ)
                if ($comment['user_id'] == $currentUserId) {

                    // Xóa vĩnh viễn (bao gồm cả con cháu)
                    $this->commentsModel->deleteRecursive($commentId);

                    ob_clean();
                    echo json_encode([
                        'status' => 'success',
                        'action' => 'remove', // Frontend sẽ xóa div
                        'message' => 'Đã xóa bình luận thành công.'
                    ]);
                    exit;
                }

                // KHÔNG CÓ QUYỀN
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền xóa bình luận này.']);
                exit;
            }
        } catch (Throwable $e) {
            ob_clean();
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi Server: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    // API Trả lời bình luận
    public function replyCommentApi()
    {
        $filter = filterData();
        // 1. Tắt báo lỗi để tránh làm hỏng JSON
        error_reporting(0);
        ob_start();

        try {
            header('Content-Type: application/json');

            // Kiểm tra đăng nhập
            if (empty($_SESSION['auth'])) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để bình luận.']);
                exit;
            }

            // Lấy dữ liệu từ $_POST
            $userId   = $_SESSION['auth']['id'];
            $parentId = $filter['parent_id'] ?? 0;
            $movieId  = $filter['movie_id'] ?? 0;
            $content  = trim($filter['content'] ?? '');

            // Validate
            if (empty($content)) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Nội dung không được để trống.']);
                exit;
            }

            if ($parentId == 0 || $movieId == 0) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ (Thiếu ID).']);
                exit;
            }

            // Chuẩn bị dữ liệu insert
            $data = [
                'user_id'    => $userId,
                'movie_id'   => $movieId,
                'parent_id'  => $parentId,
                'content'    => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'status'     => 1
            ];

            // Gọi Model Insert
            $insertId = $this->commentsModel->addComment($data);
            $newId = $this->commentsModel->getLastID(); // Lấy ID thật

            if ($insertId) {
                // Trả về dữ liệu xây dựng từ Session giúp nhanh hơn query lại DB
                // Frontend đang mong đợi field 'avartar' (lưu ý chính tả)
                $responseComment = [
                    'id' => $newId,
                    'fullname' => $_SESSION['auth']['fullname'] ?? 'User',
                    'avartar' => $_SESSION['auth']['avatar'] ?? 'https://i.pravatar.cc/150?u=default',
                    'content' => htmlspecialchars($content),
                ];

                ob_clean();
                echo json_encode([
                    'status' => 'success',
                    'data'   => $responseComment
                ]);
            } else {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống, vui lòng thử lại.']);
            }
            exit;
        } catch (Throwable $e) {
            ob_clean();
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi Server: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}
