<?php
class AccountController extends baseController
{
    private $moviesModel;
    private $notificationModel;
    private $usersModel;

    public function __construct()
    {
        $this->moviesModel = new Movies();
        $this->notificationModel = new Notifications();
        $this->usersModel = new User();
    }

    public function showIntroduce()
    {
        $this->renderView('layout-part/client/user/gioi_thieu');
    }

    public function showContact()
    {
        $this->renderView('layout-part/client/user/lien_he');
    }



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
        $userId = $_SESSION['auth']['id'];
        $notices = $this->notificationModel->getLatest($userId, 20);
        $data = [
            'notices' => $notices
        ];
        $this->renderView('layout-part/client/user/thong_bao', $data);
    }

    public function showAccount()
    {

        $this->renderView('layout-part/client/user/profile');
    }

    public function showEdit()
    {
        $userInfor = $_SESSION['auth'];
        $data = [
            'userInfor' => $userInfor
        ];
        $this->renderView('layout-part/client/user/chinh_sua', $data);
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
            //validate name
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = ' Họ và tên bắt buộc phải nhập';
            }
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = ' Email bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'phone' => $filter['phone'],
                    'address' => $filter['address'],
                    'bio' => $filter['bio'],
                    'updated_at' => date('Y:m:d H:i:s')
                ];

                //Chỉ xử lý khi có file upload
                if (!empty($_FILES['avartar']['name'])) {
                    //Xu li avatar upload len
                    $uploadDir = 'public/img/avartar/';
                    //Kiem tra co chua neu chua co thi tao
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    //lay ten file 
                    $fileName = basename($_FILES['avartar']['name']);

                    //Tao duong dan dich
                    $targetFile = $uploadDir . time() . $fileName;

                    //Kiem tra co phai la file anh khong
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

                    if (in_array($imageFileType, $allowTypes)) {
                        if (move_uploaded_file($_FILES['avartar']['tmp_name'], $targetFile)) {
                            $data['avartar'] = _HOST_URL . '/' . $targetFile;
                        }
                    }
                }

                $condition = 'id=' . $_SESSION['auth']['id'];
                $checkUpdate = $this->usersModel->updateUser($data, $condition);
                if ($checkUpdate) {
                    $_SESSION['auth']['fullname'] = $data['fullname'];
                    $_SESSION['auth']['email']    = $data['email'];
                    $_SESSION['auth']['phone']    = $data['phone'];
                    $_SESSION['auth']['address']  = $data['address'];
                    $_SESSION['auth']['bio']      = $data['bio'];
                    if (isset($data['avartar'])) {
                        $_SESSION['auth']['avartar'] = $data['avartar'];
                    }
                    setSessionFlash('msg', 'Cập nhật thông tin thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/tai_khoan');
                } else {
                    setSessionFlash('msg', 'Cập nhật thông tin thất bại');
                    setSessionFlash('msg_type', 'danger');
                    reload('/tai_khoan/chinh_sua');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                reload('/tai_khoan/chinh_sua');
            }
        }
    }

    public function showSecurity()
    {
        $this->renderView('layout-part/client/user/bao_mat');
    }

    public function security()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            $condition = 'id=' . $_SESSION['auth']['id'];
            $userInfor = $this->usersModel->getOneUser($condition);

            // Validate current password
            if (empty(trim($filter['current_password']))) {
                $errors['current_password']['required'] = 'Mật khẩu hiện tại bắt buộc phải nhập';
            } else {
                // Verify current password matches database
                if (!password_verify($filter['current_password'], $userInfor['password'])) {
                    $errors['current_password']['incorrect'] = 'Mật khẩu hiện tại không đúng';
                }
            }

            // Validate new password
            if (empty(trim($filter['new_password']))) {
                $errors['new_password']['required'] = 'Mật khẩu mới bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['new_password'])) < 8) {
                    $errors['new_password']['length'] = 'Mật khẩu phải có ít nhất 8 ký tự';
                }
            }

            // Validate confirm password
            if (empty(trim($filter['confirm_password']))) {
                $errors['confirm_password']['required'] = 'Xác nhận mật khẩu bắt buộc phải nhập';
            } else {
                if (trim($filter['confirm_password']) !== trim($filter['new_password'])) {
                    $errors['confirm_password']['match'] = 'Mật khẩu xác nhận không khớp với mật khẩu mới';
                }
            }


            if (empty($errors)) {
                $data = [
                    'password' => password_hash($filter['new_password'], PASSWORD_DEFAULT),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $conditionUpdate = 'id=' . $_SESSION['auth']['id'];
                $checkUpdate = $this->usersModel->updateUser($data, $conditionUpdate);

                if ($checkUpdate) {
                    setSessionFlash('msg', 'Cập nhật mật khẩu thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/tai_khoan/bao_mat');
                } else {
                    setSessionFlash('msg', 'Cập nhật mật khẩu thất bại. Vui lòng thử lại');
                    setSessionFlash('msg_type', 'danger');
                    reload('/tai_khoan/bao_mat');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại thông tin nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                reload('/tai_khoan/bao_mat');
            }
        }
    }
}
