<?php

use GuzzleHttp\Promise\Is;

class AuthController extends baseController
{
    private $coreModel;
    private $client;
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
                            // User chỉ được đăng nhập 1 nơi
                            $user_id = $checkStatus['id'];
                            $checkAlready = $this->coreModel->getRows("SELECT * FROM token_login WHERE user_id = $user_id");
                            if ($checkAlready > 0) {
                                setSessionFlash('msg', 'Tài khoản đang được đăng nhập ở 1 nơi khác, vui lòng thử lại sau.');
                                setSessionFlash('msg_type', 'danger');
                                setSessionFlash('active_tab', 'login');
                            } else {
                                $tokenLogin = sha1(uniqid() . time());
                                $dataToken = [
                                    'user_id' => $user_id,
                                    'token' => $tokenLogin
                                ];
                                $checkInsert = $this->coreModel->insert('token_login', $dataToken);
                                if ($checkInsert) {
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
                $activeToken = sha1(uniqid() . time());
                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'active_token' => $activeToken,
                    'group_id' => 1,
                    'avartar' => _HOST_URL . '/public/img/avartar_default/9-anh-dai-dien-trang-inkythuatso-03-15-27-03.jpg',
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $insertStatus = $this->coreModel->insert('users', $data);
                if ($insertStatus) {
                    $emailTo = $filter['email'];
                    $subject = 'Kích hoạt tài khoản';
                    $content = '<div style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    
        <div style="background-color: #007bff; color: #ffffff; padding: 20px; text-align: center;">
        <h2 style="margin: 0;">Kích hoạt tài khoản của bạn</h2>
        </div>
    
        <div style="padding: 30px; color: #333333; line-height: 1.6;">
        <p>Xin chào <strong>' . htmlspecialchars($filter["fullname"]) . '</strong>,</p>
        <p>Chúc mừng bạn đã đăng ký tài khoản thành công trên hệ thống <strong>nkhanh</strong>!</p>
        <p>Để kích hoạt tài khoản, vui lòng nhấn vào nút bên dưới:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="' . _HOST_URL . '/active?token=' . $activeToken . '" 
            style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">
            Kích hoạt tài khoản
            </a>
        </div>

        <p>Nếu nút trên không hoạt động, bạn có thể truy cập đường link sau:</p>
        <p style="word-break: break-all; color: #007bff;">' . _HOST_URL . '/active?token=' . $activeToken . '</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 25px 0;">
        
        <p style="text-align: center; font-size: 14px; color: #888;">Cảm ơn bạn đã tin tưởng và ủng hộ <strong>nkhanh</strong> ❤️</p>
        </div>
  </div>
    </div>';
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
                $google_auth = new Google\Service\Oauth2($this->client);
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

                    // 1. Kiểm tra xem user này đã đăng nhập ở đâu chưa
                    $checkAlready = $this->coreModel->getRows("SELECT * FROM token_login WHERE user_id = $user_id");

                    if ($checkAlready > 0) {
                        // 2. NẾU ĐÃ ĐĂNG NHẬP: Báo lỗi và quay về trang login (giống hàm login)
                        setSessionFlash('msg', 'Tài khoản đang được đăng nhập ở 1 nơi khác, vui lòng thử lại sau.');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('active_tab', 'login');
                        reload('/login');
                        return; // Dừng code tại đây, không chạy xuống phần insert bên dưới
                    }

                    // 3. NẾU CHƯA ĐĂNG NHẬP: Tiến hành tạo token và đăng nhập
                    $tokenLogin = sha1(uniqid() . time());
                    $dataLogin = [
                        'user_id' => $user_id,
                        'token' => $tokenLogin
                    ];

                    $checkInsert = $this->coreModel->insert('token_login', $dataLogin);

                    if ($checkInsert) {
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
}
