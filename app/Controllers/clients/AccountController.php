<?php
class AccountController extends baseController
{
    private $moviesModel;
    private $notificationModel;
    private $usersModel;
    private $supportModel;
    private $watchHistoryModel;

    public function __construct()
    {
        $this->moviesModel = new Movies();
        $this->notificationModel = new Notifications();
        $this->usersModel = new User();
        $this->supportModel = new Support();
        $this->watchHistoryModel = new WatchHistory();
    }

    public function showIntroduce()
    {
        $this->renderView('layout-part/client/user/gioi_thieu');
    }

    public function showContact()
    {
        $userInfor = $_SESSION['auth'];
        $getAllSupportType = $this->supportModel->getAllSupportType();
        $data = [
            'getAllSupportType' => $getAllSupportType,
            'userInfor' => $userInfor
        ];
        $this->renderView('layout-part/client/user/lien_he', $data);
    }

    public function contact()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            // echo '<pre>';
            // print_r($filter);
            // echo '</pre>';
            // die();
            //validate name
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = ' Họ và tên bắt buộc phải nhập';
            }
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = ' Email bắt buộc phải nhập';
            }
            if (empty(trim($filter['content']))) {
                $errors['content']['required'] = ' Nội dung tin nhắn bắt buộc phải nhập';
            }
            if (empty($errors)) {
                // Lấy tên loại hỗ trợ
                $supportTypeName = '';
                $allSupportTypes = $this->supportModel->getAllSupportType();
                foreach ($allSupportTypes as $type) {
                    if ($type['id'] == $filter['support_type']) {
                        $supportTypeName = $type['name'];
                        break;
                    }
                }

                $emailTo = 'nkhanh2305@gmail.com';
                $subject = '[Phê Phim] Yêu cầu hỗ trợ: ' . $supportTypeName;
                $content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);">
    <div style="max-width: 650px; margin: 40px auto; background: linear-gradient(135deg, rgba(18, 24, 33, 0.95) 0%, rgba(10, 14, 20, 0.98) 100%); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 1px rgba(255, 255, 255, 0.1) inset;">
        
        <!-- Header with Logo -->
        <div style="background: linear-gradient(135deg, #D96C16 0%, #F29F05 100%); padding: 40px 20px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);"></div>
            <img src="' . _HOST_URL_PUBLIC . '/img/logo/PhePhim.png" alt="Phê Phim" style="height: 60px; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));">
            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">🎧 Yêu Cầu Hỗ Trợ Mới</h1>
            <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px;">Từ khách hàng của Phê Phim</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 35px 30px; color: #e2e8f0; line-height: 1.8;">
            
            <!-- User Info Card -->
            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);"></div>
                    <h3 style="margin: 0; color: #10b981; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Thông tin người gửi</h3>
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px; width: 120px;">👤 Họ và tên:</td>
                        <td style="padding: 8px 0; color: #fff; font-size: 14px; font-weight: 600;">' . htmlspecialchars($filter['fullname']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">📧 Email:</td>
                        <td style="padding: 8px 0;">
                            <a href="mailto:' . htmlspecialchars($filter['email']) . '" style="color: #3b82f6; text-decoration: none; font-size: 14px;">' . htmlspecialchars($filter['email']) . '</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">📁 Loại hỗ trợ:</td>
                        <td style="padding: 8px 0;">
                            <span style="display: inline-block; background: rgba(217, 108, 22, 0.15); border: 1px solid rgba(217, 108, 22, 0.3); color: #F29F05; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">' . htmlspecialchars($supportTypeName) . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">🕐 Thời gian:</td>
                        <td style="padding: 8px 0; color: #cbd5e1; font-size: 13px;">' . date('d/m/Y H:i:s') . '</td>
                    </tr>
                </table>
            </div>

            <!-- Message Content -->
            <div style="background: rgba(59, 130, 246, 0.05); border-left: 4px solid #3b82f6; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <span style="font-size: 18px;">💬</span>
                    <h3 style="margin: 0; color: #93c5fd; font-size: 14px; font-weight: 600;">NỘI DUNG YÊU CẦU</h3>
                </div>
                <div style="background: rgba(15, 23, 42, 0.5); border-radius: 8px; padding: 15px; color: #e2e8f0; font-size: 14px; line-height: 1.7; white-space: pre-wrap;">' . htmlspecialchars($filter['content']) . '</div>
            </div>
            
            <!-- Quick Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . _HOST_URL . '/admin/support" style="display: inline-block; background: linear-gradient(135deg, #D96C16 0%, #F29F05 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 600; font-size: 14px; box-shadow: 0 4px 15px rgba(217, 108, 22, 0.3); transition: all 0.3s;">
                    🚀 Xem chi tiết trong Admin Panel
                </a>
            </div>
            
            <!-- Info Notice -->
            <div style="margin-top: 25px; padding: 15px; background: rgba(245, 158, 11, 0.1); border-left: 3px solid #f59e0b; border-radius: 8px;">
                <p style="font-size: 13px; color: #fbbf24; margin: 0;">
                    <strong>💡 Lưu ý:</strong> Vui lòng phản hồi yêu cầu này trong vòng 24 giờ để đảm bảo trải nghiệm tốt nhất cho khách hàng.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: rgba(15, 23, 42, 0.7); padding: 25px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <p style="margin: 0; font-size: 13px; color: #64748b;">Email tự động từ hệ thống <strong style="color: #F29F05;">Phê Phim</strong> ✨</p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #475569;">© 2024 Phê Phim. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
                sendMail($emailTo, $subject, $content);

                $data = [
                    'user_id' => $filter['user_id'],
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'content' => $filter['content'],
                    'support_type_id' => $filter['support_type'],
                    'support_status_id' => 1,
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $insertSupport = $this->supportModel->insertSupport($data);
                if ($insertSupport) {
                    setSessionFlash('msg', 'Gửi tin nhắn thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/lien_he');
                }
            }
        }
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


    public function showNextWatch()
    {
        // Lấy danh sách xem tiếp (nếu đã đăng nhập)
        $getContinueWatching = [];
        if (!empty($_SESSION['auth']['id'])) {
            $getContinueWatching = $this->watchHistoryModel->getContinueWatchingList($_SESSION['auth']['id'], 10);
        }
        $data = [
            'getContinueWatching' => $getContinueWatching
        ];
        $this->renderView('layout-part/client/user/xem_tiep', $data);
    }
    public function deleteHistoryDashboard()
    {
        $filter = filterData('get');
        if (!empty($filter['id'])) {
            $conditionDelete = 'id=' . $filter['id'];
            $checkDelete = $this->watchHistoryModel->deleteHistory($conditionDelete);
            reload('/');
        } else {
            reload('/');
        }
    }

    public function deleteHistoryContinuePage()
    {
        $filter = filterData('get');
        if (!empty($filter['id'])) {
            $conditionDelete = 'id=' . $filter['id'];
            $checkDelete = $this->watchHistoryModel->deleteHistory($conditionDelete);
            reload(_HOST_URL . '/xem_tiep');
        } else {
            reload(_HOST_URL . '/xem_tiep');
        }
    }
}
