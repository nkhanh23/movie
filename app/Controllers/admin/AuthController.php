<?php
class AuthController extends baseController
{
    private $coreModel;
    public function __construct()
    {
        $this->coreModel = new CoreModel;
    }
    public function showLogin()
    {
        $this->renderView('/layout-part/auth/login');
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
                echo '<pre>';
                print_r($checkStatus);
                echo '</pre>';
                if (!empty($checkStatus)) {
                    if (!empty($checkStatus['password'])) {
                        $checkPassword = password_verify($password, $checkStatus['password']);
                        if ($checkPassword) {
                            $user_id = $checkStatus['id'];
                            $tokenLogin = sha1(uniqid() . time());
                            $data = [
                                'user_id' => $user_id,
                                'token' => $tokenLogin
                            ];
                            $checkInsert = $this->coreModel->insert('token_login', $data);
                            if ($checkInsert) {
                                if ($checkStatus['group_id'] == 1) {
                                    setSession('tokenLogin', $tokenLogin);
                                    reload('/client/dashboard');
                                } elseif ($checkStatus['group_id'] == 2) {
                                    setSession('tokenLogin', $tokenLogin);
                                    reload('/admin/dashboard');
                                }
                            } else {
                                setSessionFlash('msg', 'Lỗi hệ thống. Đăng nhập thất bại');
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

    public function active()
    {

        $data = [
            'coreModel' => $this->coreModel,

        ];
        $this->renderView('layout-part/auth/active', $data);
    }
}
