<?php
class AuthController extends baseController
{
    private $coreModel;
    private $client;
    private $activityModel;
    public function __construct()
    {
        $this->coreModel = new CoreModel;
        // Cấu hình google client
        $this->client = new Google\Client();
        $this->client->setClientId(_GOOGLE_CLIENT_ID);
        $this->client->setClientSecret(_GOOGLE_CLIENT_SECRET);
        $this->client->setRedirectUri(_GOOGLE_REDIRECT_URL);
        $this->client->addScope("email");
        $this->client->addScope("profile");
        $this->activityModel = new Activity;
    }
    public function showLogin()
    {
        $data = [
            'google_login_url' => $this->client->createAuthUrl()
        ];
        $this->renderView('/layout-part/auth/login', $data);
    }

    public function login()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate email
            if (empty($filter['email'])) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                } else {
                    $email = trim($filter['email']);
                    $checkEmail = $this->coreModel->getRows("SELECT * FROM users WHERE email = '$email'");
                    if ($checkEmail < 1) {
                        $errors['email']['check'] = 'Email không tồn tại';
                    }
                }
            }

            //validate password
            if (empty($filter['password'])) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc nhập';
            } else {
                if (strlen(trim($filter['password'])) < 8) {
                    $errors['password']['length'] = ' Mật khẩu phải trên 8 kí tự';
                }
            }


            if (empty($errors)) {
                //Kiểm tra dữ liệu
                $email = $filter['email'];
                $password = $filter['password'];
                $checkStatus = $this->coreModel->getOne("SELECT id, password, group_id FROM users WHERE email = '$email' AND status = 1");
                if (!empty($checkStatus)) {
                    if (!empty($checkStatus['password'])) {
                        $checkPassword = password_verify($password, $checkStatus['password']);
                        if ($checkPassword) {
                            // User đăng nhập - kiểm tra và xóa token cũ nếu có
                            $user_id = $checkStatus['id'];
                            $checkAlready = $this->coreModel->getRows("SELECT * FROM token_login WHERE user_id = $user_id");
                            if ($checkAlready > 0) {
                                // Xóa token cũ trước khi tạo token mới
                                $this->coreModel->delete('token_login', "user_id = $user_id");
                            }

                            // Tạo token mới
                            $tokenLogin = sha1(uniqid() . time());
                            $dataToken = [
                                'user_id' => $user_id,
                                'token' => $tokenLogin
                            ];
                            $checkInsert = $this->coreModel->insert('token_login', $dataToken);
                            $getOne = $this->coreModel->getOne("SELECT * FROM users WHERE id = $user_id");
                            if ($checkInsert) {
                                // Ghi log
                                $logData = [
                                    'name' => $getOne['name'],
                                    'email' => $getOne['email']
                                ];
                                $this->activityModel->log(
                                    $user_id,
                                    'login',
                                    'users',
                                    $user_id,
                                    null,
                                    $logData
                                );
                                if ($checkStatus['group_id'] == 1) {
                                    setSession('tokenLogin', $tokenLogin);
                                    reload('/');
                                } elseif ($checkStatus['group_id'] == 2) {
                                    setSession('tokenLogin', $tokenLogin);
                                    reload('/admin/dashboard');
                                }
                            } else {
                                setSessionFlash('msg', 'Lỗi hệ thống. Đăng nhập thất bại');
                                setSessionFlash('msg_type', 'danger');
                                setSessionFlash('active_tab', 'login');
                            }
                        } else {
                            setSessionFlash('msg', 'Email hoặc mật khẩu không chính xác!');
                            setSessionFlash('msg_type', 'danger');
                            setSessionFlash('oldData', $filter);
                            setSessionFlash('errors', $errors);
                            setSessionFlash('active_tab', 'login');
                        }
                    } else {
                        setSessionFlash('msg', 'Email hoặc mật khẩu không chính xác!');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('oldData', $filter);
                        setSessionFlash('errors', $errors);
                        setSessionFlash('active_tab', 'login');
                    }
                } else {
                    setSessionFlash('msg', 'Email hoặc mật khẩu không chính xác. Hoặc tài khoản chưa kích hoạt');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                    setSessionFlash('active_tab', 'login');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                setSessionFlash('active_tab', 'login');
            }
            reload('/login');
        }
    }

    public function register()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate fullname
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = ' Họ tên bắt buộc phải nhập';
            } else {
                if (strlen(trim(($filter['fullname']))) < 5) {
                    $errors['fullname']['length'] = 'Họ tên phải lớn hơn 5 kí tự';
                }
            }

            //validate email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc nhập';
            } else {
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                } else {
                    $email = $filter['email'];
                    $checkEmail = $this->coreModel->getRows("SELECT * FROM users WHERE email = '$email'");
                    if ($checkEmail > 0) {
                        $errors['email']['check'] = 'Email đã tồn tại';
                    }
                }
            }
            //validate password
            if (empty(trim($filter['password']))) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc nhập';
            } else {
                if (strlen(trim(($filter['password']))) < 8) {
                    $errors['password']['length'] = 'Mật khẩu nhập phải trên 8 kí tự';
                }
            }

            //validate confirm password
            if (empty(trim($filter['confirm_pass']))) {
                $errors['confirm_pass']['required'] = 'Bắt buộc nhập lại mật khẩu';
            } else {
                if (trim($filter['confirm_pass']) !== trim($filter['password'])) {
                    $errors['confirm_pass']['same'] = 'Mật khẩu nhập lại không khớp';
                }
            }
            if (empty($errors)) {
                $folderPath = './public/img/avartar_default/';

                // Lấy danh sách tất cả file ảnh (jpg, png, jpeg) trong folder
                $files = glob($folderPath . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

                $avatarFinal = '';

                if ($files && count($files) > 0) {
                    // Nếu tìm thấy file, chọn ngẫu nhiên 1 key trong mảng
                    $randomKey = array_rand($files);

                    // Lấy tên file từ đường dẫn (ví dụ: 'image1.jpg')
                    $fileName = basename($files[$randomKey]);

                    $avatarFinal = _HOST_URL . '/public/img/avartar_default/' . $fileName;
                }
                $activeToken = sha1(uniqid() . time());
                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'active_token' => $activeToken,
                    'group_id' => 1,
                    'avartar' => $avatarFinal,
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $insertStatus = $this->coreModel->insert('users', $data);
                if ($insertStatus) {
                    // Prepare logo as base64 for email
                    $logoPath = './public/img/logo/PhePhim.png';
                    $logoData = '';
                    if (file_exists($logoPath)) {
                        $logoContent = file_get_contents($logoPath);
                        $logoBase64 = base64_encode($logoContent);
                        $logoData = 'data:image/png;base64,' . $logoBase64;
                    }

                    $emailTo = $filter['email'];
                    $subject = 'Kích hoạt tài khoản';
                    $content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);">
    <div style="max-width: 600px; margin: 40px auto; background: linear-gradient(135deg, rgba(18, 24, 33, 0.95) 0%, rgba(10, 14, 20, 0.98) 100%); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 1px rgba(255, 255, 255, 0.1) inset;">
        
        <!-- Header with Logo -->
        <div style="background: linear-gradient(135deg, #D96C16 0%, #F29F05 100%); padding: 40px 20px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);"></div>
            <img src="' . _HOST_URL_PUBLIC . '/img/logo/PhePhim.png" alt="Phê Phim" style="height: 60px; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));">
            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">Kích Hoạt Tài Khoản</h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px 30px; color: #e2e8f0; line-height: 1.8;">
            <p style="font-size: 16px; margin-bottom: 20px;">Xin chào <strong style="color: #F29F05;">' . htmlspecialchars($filter["fullname"]) . '</strong>,</p>
            
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 15px;">Chúc mừng bạn đã đăng ký tài khoản thành công tại <strong style="color: #D96C16;">Phê Phim</strong>!</p>
            
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 30px;">Để kích hoạt tài khoản và bắt đầu trải nghiệm, vui lòng nhấn vào nút bên dưới:</p>
            
            <!-- CTA Button -->
            <div style="text-align: center; margin: 35px 0;">
                <a href="' . _HOST_URL . '/active?token=' . $activeToken . '" style="display: inline-block; background: linear-gradient(90deg, #D96C16 0%, #F29F05 50%, #D96C16 100%); background-size: 200% auto; color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 20px rgba(217, 108, 22, 0.4), 0 0 1px rgba(255, 255, 255, 0.2) inset; transition: all 0.3s ease;">
                    ✨ Kích Hoạt Tài Khoản
                </a>
            </div>
            
            <!-- Fallback Link -->
            <div style="margin-top: 30px; padding: 20px; background: rgba(15, 23, 42, 0.5); border-left: 3px solid #D96C16; border-radius: 8px;">
                <p style="font-size: 13px; color: #94a3b8; margin: 0 0 10px 0;">Nếu nút trên không hoạt động, hãy sao chép và dán link sau vào trình duyệt:</p>
                <p style="font-size: 12px; color: #F29F05; word-break: break-all; margin: 0; font-family: monospace;">' . _HOST_URL . '/active?token=' . $activeToken . '</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: rgba(15, 23, 42, 0.7); padding: 25px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <p style="margin: 0; font-size: 13px; color: #64748b;">Cảm ơn bạn đã tin tưởng <strong style="color: #F29F05;">Phê Phim</strong> ❤️</p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #475569;">© 2024 Phê Phim. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
                    sendMail($emailTo, $subject, $content);
                    setSessionFlash('msg', 'Đăng kí thành công, vui lòng kích hoạt tài khoản');
                    setSessionFlash('msg_type', 'success');
                    setSessionFlash('active_tab', 'signup');
                    reload('/login');
                } else {
                    setSessionFlash('msg', 'Đăng kí không thành công');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('errors', $errors);
                    setSessionFlash('oldData', $filter);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('oldData', $filter);
                //Giữ lại tab đăng kí sau khi tải lại trang
                setSessionFlash('active_tab', 'signup');
                reload('/login');
            }
        }
    }

    public function googleCallback()
    {
        if (isset($_GET['code'])) {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (!isset($token['error'])) {
                $this->client->setAccessToken($token['access_token']);
                $google_auth = new \Google\Service\Oauth2($this->client);
                $google_account_info = $google_auth->userinfo->get();

                $email = $google_account_info->email;
                $name = $google_account_info->name;
                $google_id = $google_account_info->id;
                $avartar = $google_account_info->picture;

                $checkUser = $this->coreModel->getOne("SELECT * FROM users WHERE email = '$email'");

                if (!empty($checkUser)) {
                    // --- TRƯỜNG HỢP 1: TÀI KHOẢN ĐÃ TỒN TẠI ---

                    // Cập nhật Google ID nếu chưa có
                    if (empty($checkUser['google_id'])) {
                        $data = ['google_id' => $google_id];
                        $condition = 'id=' . $checkUser['id'];
                        $this->coreModel->update('users', $data, $condition);
                    }

                    $user_id = $checkUser['id'];

                    // Kiểm tra và xóa token cũ nếu có
                    $checkAlready = $this->coreModel->getRows("SELECT * FROM token_login WHERE user_id = $user_id");
                    if ($checkAlready > 0) {
                        // Xóa token cũ trước khi tạo token mới
                        $this->coreModel->delete('token_login', "user_id = $user_id");
                    }

                    // Tạo token mới
                    $tokenLogin = sha1(uniqid() . time());
                    $dataLogin = [
                        'user_id' => $user_id,
                        'token' => $tokenLogin
                    ];
                    $checkInsert = $this->coreModel->insert('token_login', $dataLogin);
                    if ($checkInsert) {
                        // Ghi log
                        $logData = [
                            'name' => $checkUser['name'],
                            'email' => $checkUser['email']
                        ];
                        $this->activityModel->log(
                            $user_id,
                            'login',
                            'users',
                            $user_id,
                            null,
                            $logData
                        );
                        if ($checkUser['group_id'] == 1) {
                            setSession('tokenLogin', $tokenLogin);
                            reload('/');
                        } elseif ($checkUser['group_id'] == 2) {
                            setSession('tokenLogin', $tokenLogin);
                            reload('/admin/dashboard');
                        }
                    } else {
                        setSessionFlash('msg', 'Lỗi hệ thống. Đăng nhập thất bại');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('active_tab', 'login');
                        reload('/login');
                    }
                } else {
                    // --- TRƯỜNG HỢP 2: ĐĂNG KÝ MỚI (User chưa tồn tại) ---
                    // Phần này giữ nguyên vì user mới chắc chắn chưa đăng nhập ở đâu
                    $dataRegister = [
                        'fullname' => $name,
                        'email' => $email,
                        'password' => null, // Hoặc password random
                        'status' => 1,
                        'group_id' => 1,
                        'avartar' => $avartar,
                        'created_at' => date('Y:m:d H:i:s'),
                        'google_id' => $google_id
                    ];

                    $insertStatus = $this->coreModel->insert('users', $dataRegister);

                    if ($insertStatus) {
                        // Lưu ý: Nên dùng cách query lại email như đã bàn trước đó để an toàn hơn getLastID()
                        $newUser = $this->coreModel->getOne("SELECT * FROM users WHERE email = '$email'");

                        if (!empty($newUser)) {
                            $user_id = $newUser['id'];
                            $tokenLogin = sha1(uniqid() . time());
                            $dataLogin = [
                                'user_id' => $user_id,
                                'token' => $tokenLogin
                            ];

                            $checkInsertRegister = $this->coreModel->insert('token_login', $dataLogin);
                            if ($checkInsertRegister) {
                                // Ghi log
                                $logData = [
                                    'name' => $checkUser['name'],
                                    'email' => $checkUser['email']
                                ];
                                $this->activityModel->log(
                                    $user_id,
                                    'login',
                                    'users',
                                    $user_id,
                                    null,
                                    $logData
                                );
                                setSession('tokenLogin', $tokenLogin);
                                reload('/');
                            } else {
                                setSessionFlash('msg', 'Lỗi hệ thống. Đăng nhập thất bại');
                                setSessionFlash('msg_type', 'danger');
                                setSessionFlash('active_tab', 'register');
                                reload('/login');
                            }
                        }
                    }
                }
            }
        }
    }

    public function active()
    {

        $data = [
            'coreModel' => $this->coreModel,

        ];
        $this->renderView('layout-part/auth/active', $data);
    }

    public function logout()
    {
        if (isLogin()) {
            $token = getSession('tokenLogin');
            $removeToken = $this->coreModel->delete('token_login', "token = '$token'");

            if ($removeToken) {
                // Bước 3: Hủy session hiện tại
                session_destroy();

                // Bước 5: Set flash message
                setSessionFlash('msg', 'Đăng xuất thành công');
                setSessionFlash('msg_type', 'success');
                reload('/');
            } else {
                setSessionFlash('msg', 'Lỗi hệ thống. Đăng xuất thất bại');
                setSessionFlash('msg_type', 'danger');
                reload('/');
            }
        } else {
            setSessionFlash('msg', 'Bạn chưa đăng nhập');
            setSessionFlash('msg_type', 'warning');
            reload('/');
        }
    }

    public function showForgot()
    {
        $userInfor = $_SESSION['auth']['email'] ?? '';
        $data = [
            'userInfor' => $userInfor,
            'pageTitle' => 'Khôi phục mật khẩu'
        ];
        $this->renderView('layout-part/auth/forgot', $data);
    }

    public function forgot()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // Validate email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                // Đúng định dạng email, email này đã tồn tại trong CSDL chưa
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                }
            }

            if (empty($errors)) {
                if (!empty($filter['email'])) {
                    $email = $filter['email'];
                    $checkEmail = $this->coreModel->getOne("SELECT * FROM users WHERE email = '$email'");
                    if (!empty($checkEmail)) {
                        $forgot_token = sha1(uniqid() . time());
                        $data = [
                            'forget_token' => $forgot_token
                        ];
                        $condition = "id=" . $checkEmail['id'];
                        $updateStatus = $this->coreModel->update('users', $data, $condition);
                        if ($updateStatus) {
                            $emailTo = $filter['email'];
                            $subject = 'Yêu cầu đặt lại mật khẩu';
                            $content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);">
    <div style="max-width: 600px; margin: 40px auto; background: linear-gradient(135deg, rgba(18, 24, 33, 0.95) 0%, rgba(10, 14, 20, 0.98) 100%); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 1px rgba(255, 255, 255, 0.1) inset;">
        
        <!-- Header with Logo -->
        <div style="background: linear-gradient(135deg, #D96C16 0%, #F29F05 100%); padding: 40px 20px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);"></div>
            <img src="' . _HOST_URL_PUBLIC . '/img/logo/PhePhim.png" alt="Phê Phim" style="height: 60px; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));">
            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">Đặt Lại Mật Khẩu</h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px 30px; color: #e2e8f0; line-height: 1.8;">
            <p style="font-size: 16px; margin-bottom: 20px;">Xin chào <strong style="color: #F29F05;">' . htmlspecialchars($checkEmail["fullname"]) . '</strong>,</p>
            
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 15px;">Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại <strong style="color: #D96C16;">Phê Phim</strong>.</p>
            
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 30px;">Để tiếp tục, vui lòng nhấn vào nút bên dưới:</p>
            
            <!-- CTA Button -->
            <div style="text-align: center; margin: 35px 0;">
                <a href="' . _HOST_URL . '/reset?token=' . $forgot_token . '" style="display: inline-block; background: linear-gradient(90deg, #D96C16 0%, #F29F05 50%, #D96C16 100%); background-size: 200% auto; color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 20px rgba(217, 108, 22, 0.4), 0 0 1px rgba(255, 255, 255, 0.2) inset; transition: all 0.3s ease;">
                    🔑 Đặt Lại Mật Khẩu
                </a>
            </div>
            
            <!-- Security Notice -->
            <div style="margin-top: 30px; padding: 15px; background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444; border-radius: 8px;">
                <p style="font-size: 13px; color: #fca5a5; margin: 0;">⚠️ <strong>Lưu ý:</strong> Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Link sẽ hết hạn sau 24 giờ.</p>
            </div>
            
            <!-- Fallback Link -->
            <div style="margin-top: 20px; padding: 20px; background: rgba(15, 23, 42, 0.5); border-left: 3px solid #D96C16; border-radius: 8px;">
                <p style="font-size: 13px; color: #94a3b8; margin: 0 0 10px 0;">Nếu nút trên không hoạt động, hãy sao chép và dán link sau vào trình duyệt:</p>
                <p style="font-size: 12px; color: #F29F05; word-break: break-all; margin: 0; font-family: monospace;">' . _HOST_URL . '/reset?token=' . $forgot_token . '</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: rgba(15, 23, 42, 0.7); padding: 25px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <p style="margin: 0; font-size: 13px; color: #64748b;">Cảm ơn bạn đã tin tưởng <strong style="color: #F29F05;">Phê Phim</strong> ❤️</p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #475569;">© 2024 Phê Phim. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
                            sendMail($emailTo, $subject, $content);
                            setSessionFlash('msg', 'Kiểm tra email của bạn.');
                            setSessionFlash('msg_type', 'success');
                            reload('/forgot');
                        }
                    }
                }
            }
        }
    }

    public function showReset()
    {
        $this->renderView('layout-part/auth/reset');
    }

    public function reset()
    {
        $filterGet = filterData('get');
        $tokenReset = '';

        if (!empty($filterGet['token'])) {
            $tokenReset = $filterGet['token'];
        }

        if (!empty($tokenReset)) {
            // Kiểm tra token có tồn tại trong database không
            $checkToken = $this->coreModel->getOne("SELECT * FROM users WHERE forget_token = '$tokenReset'");

            if (!empty($checkToken)) {
                // Nếu có yêu cầu gửi lên (Người dùng bấm nút Đổi mật khẩu)
                if (isPost()) {
                    $filter = filterData();
                    $errors = [];

                    // Validate Password MK > 6 ký tự
                    if (empty(trim($filter['password']))) {
                        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
                    } else {
                        if (strlen(trim($filter['password'])) < 6) {
                            $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 ký tự';
                        }
                    }

                    // Validate confirm password
                    // LƯU Ý: Ở view (HTML) input name phải là "confirm_password"
                    if (empty(trim($filter['confirm_password']))) {
                        $errors['confirm_password']['required'] = 'Vui lòng nhập lại mật khẩu';
                    } else {
                        if (trim($filter['password']) !== trim($filter['confirm_password'])) {
                            $errors['confirm_password']['like'] = 'Mật khẩu nhập lại không khớp';
                        }
                    }

                    // --- XỬ LÝ KẾT QUẢ VALIDATE ---
                    if (empty($errors)) {
                        // TRƯỜNG HỢP THÀNH CÔNG: Không có lỗi
                        $password = password_hash($filter['password'], PASSWORD_DEFAULT);
                        $data = [
                            'password' => $password,
                            'forget_token' => null, // Xóa token để không dùng lại được
                            'updated_at' => date('Y:m:d H:i:s')
                        ];

                        $condition = "id=" . $checkToken['id'];
                        $updateStatus = $this->coreModel->update('users', $data, $condition);

                        if ($updateStatus) {
                            // Chuẩn bị ảnh logo gửi mail
                            $logoPath = './public/img/logo/PhePhim.png';
                            // $logoData = ''; // Biến này chưa dùng trong HTML nhưng giữ lại nếu cần
                            // Code convert base64 (giữ nguyên logic của bạn)
                            if (file_exists($logoPath)) {
                                $logoContent = file_get_contents($logoPath);
                                $logoBase64 = base64_encode($logoContent);
                                $logoData = 'data:image/png;base64,' . $logoBase64;
                            }

                            $emailTo = $checkToken['email'];
                            $subject = 'Đổi mật khẩu thành công!!';
                            $content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);">
    <div style="max-width: 600px; margin: 40px auto; background: linear-gradient(135deg, rgba(18, 24, 33, 0.95) 0%, rgba(10, 14, 20, 0.98) 100%); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 1px rgba(255, 255, 255, 0.1) inset;">
        
        <div style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); padding: 40px 20px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);"></div>
            <img src="' . _HOST_URL_PUBLIC . '/img/logo/PhePhim.png" alt="Phê Phim" style="height: 60px; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));">
            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">✅ Đổi Mật Khẩu Thành Công</h1>
        </div>
        
        <div style="padding: 40px 30px; color: #e2e8f0; line-height: 1.8;">
            <p style="font-size: 16px; margin-bottom: 20px;">Chúc mừng <strong style="color: #F29F05;">' . htmlspecialchars($checkToken["fullname"]) . '</strong>!</p>
            
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 15px;">Mật khẩu của bạn đã được thay đổi thành công tại <strong style="color: #D96C16;">Phê Phim</strong>.</p>
            
            <p style="font-size: 15px; color: #cbd5e1; margin-bottom: 30px;">Bây giờ bạn có thể đăng nhập với mật khẩu mới:</p>
            
            <div style="text-align: center; margin: 35px 0;">
                <a href="' . _HOST_URL . '/login" style="display: inline-block; background: linear-gradient(90deg, #D96C16 0%, #F29F05 50%, #D96C16 100%); background-size: 200% auto; color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: 700; font-size: 16px; box-shadow: 0 8px 20px rgba(217, 108, 22, 0.4), 0 0 1px rgba(255, 255, 255, 0.2) inset; transition: all 0.3s ease;">
                    🎬 Đăng Nhập Ngay
                </a>
            </div>
            
            <div style="margin-top: 30px; padding: 15px; background: rgba(34, 197, 94, 0.1); border-left: 3px solid #22c55e; border-radius: 8px;">
                <p style="font-size: 13px; color: #86efac; margin: 0;">✨ <strong>Bảo mật:</strong> Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ với chúng tôi ngay lập tức.</p>
            </div>
        </div>
        
        <div style="background: rgba(15, 23, 42, 0.7); padding: 25px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <p style="margin: 0; font-size: 13px; color: #64748b;">Cảm ơn bạn đã tin tưởng <strong style="color: #F29F05;">Phê Phim</strong> ❤️</p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #475569;">© 2024 Phê Phim. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
                            sendMail($emailTo, $subject, $content);
                            setSessionFlash('msg', 'Đổi mật khẩu thành công!');
                            setSessionFlash('msg_type', 'success');
                            reload('/login');
                        } else {
                            setSessionFlash('msg', 'Lỗi hệ thống. Reset mật khẩu thất bại');
                            setSessionFlash('msg_type', 'danger');
                            reload('/reset?token=' . $tokenReset);
                        }
                    } else {
                        // TRƯỜNG HỢP CÓ LỖI: Trả về form và hiện lỗi
                        // Đây là phần bạn bị lỗi Undefined variable trước đó, giờ đã đặt đúng chỗ
                        setSessionFlash('msg', 'Vui lòng kiểm tra lại thông tin nhập vào');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('errors', $errors);
                        setSessionFlash('oldData', $filter);
                        reload('/reset?token=' . $tokenReset);
                    }
                }

                // Render view (Nếu không phải POST thì hiển thị form)
                $this->renderView('layout-part/auth/reset', [
                    'token' => $tokenReset
                ]);
            } else {
                setSessionFlash('msg', 'Link không hợp lệ hoặc đã hết hạn');
                setSessionFlash('msg_type', 'danger');
                reload('/forgot');
            }
        } else {
            setSessionFlash('msg', 'Link không hợp lệ');
            setSessionFlash('msg_type', 'danger');
            reload('/forgot');
        }
    }
}
