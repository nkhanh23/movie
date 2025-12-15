<?php
class AccountController extends baseController
{
    private $moviesModel;

    public function __construct()
    {
        $this->moviesModel = new Movies();
    }

    public function showIntroduce()
    {
        $this->renderView('layout-part/client/user/gioi_thieu');
    }

    public function showContact()
    {
        $this->renderView('layout-part/client/user/lien_he');
    }

    public function showAccount()
    {
        $this->renderView('layout-part/client/user/profile');
    }

    public function account() {}

    public function showFavorite()
    {
        $userInfor = $_SESSION['auth'];
        $userID = $userInfor['id'];
        $favoriteMovies = $this->moviesModel->getFavoriteMovies($userID);
        $data = [
            'favoriteMovies' => $favoriteMovies
        ];
        $this->renderView('layout-part/client/user/yeu_thich', $data);
    }

    // Thêm/Xóa phim khỏi danh sách yêu thích

    public function toggleFavoriteApi()
    {
        error_reporting(0);
        ob_start();

        try {
            header('Content-Type: application/json');

            // Kiểm tra đăng nhập
            if (empty($_SESSION['auth'])) {
                ob_clean();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vui lòng đăng nhập để thực hiện chức năng này.',
                    'code' => 401
                ]);
                exit;
            }

            if (isPost()) {
                $filter = filterData();
                $movieId = isset($filter['movie_id']) ? (int)$filter['movie_id'] : 0;
                $userId = $_SESSION['auth']['id'];

                if ($movieId <= 0) {
                    ob_clean();
                    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu phim không hợp lệ.', 'code' => 400]);
                    exit;
                }

                // Gọi Model xử lý toggle
                $action = $this->moviesModel->toggleFavorite($userId, $movieId);

                ob_clean();
                echo json_encode([
                    'status' => 'success',
                    'action' => $action, // 'added' hoặc 'removed'
                    'message' => ($action === 'added') ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích'
                ]);
                exit;
            }
        } catch (Throwable $e) {
            ob_clean();
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi server: ' . $e->getMessage(),
                'code' => 500
            ]);
            exit;
        }
    }

    public function showNotice()
    {
        $this->renderView('layout-part/client/user/thong_bao');
    }
}
