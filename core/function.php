<?php
//Hàm gửi mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($emailTo, $subject, $content, $replyToEmail = null, $replyToName = null)
{
    $settingModel = new Setting();
    $settings = $settingModel->getAllSettings(); // Hàm lấy tất cả settings
    // Convert sang mảng key=>value cho dễ dùng
    $config = [];
    foreach ($settings as $item) {
        $config[$item['setting_key']] = $item['setting_value'];
    }

    // Kiểm tra xem đã cấu hình chưa
    if (empty($config['smtp_host']) || empty($config['smtp_username'])) {
        return false; // Chưa cấu hình thì không gửi
    }

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $config['smtp_host'];                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $config['smtp_username'];                     //SMTP username
        $mail->Password   = $config['smtp_password'];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $config['smtp_port'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($config['smtp_from_email'], $config['smtp_from_name']);
        $mail->addAddress($emailTo,);     // Gửi tới Admin
        //khi bấm Reply thì sẽ trả lời người dùng
        if ($replyToEmail) {
            // Nếu không có tên thì dùng chính email làm tên
            $mail->addReplyTo($replyToEmail, $replyToName ?? $replyToEmail);
        }
        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        //Custom connection options
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'  => true,
                'verify_depth' => 3,
                'allow_self_signed' => true,
            )
        );

        return $mail->send();
    } catch (Exception $e) {
        echo "Gửi thất bại. Mailer Error: {$mail->ErrorInfo}";
    }
}

//hàm kiểm tra phương thức get
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}

//hàm kiểm tra phương thức post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

//hàm lọc dữ liệu đầu vào
function filterData($method = '')
{
    $filterArray = [];
    if (empty($method)) {
        if (isGet()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);
                    //Kiểm tra người dùng nhập vào giá trị hay mảng
                    if (is_array($value)) {
                        $filterArray[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filterArray[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        if (isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);
                    //Kiểm tra người dùng nhập vào giá trị hay mảng
                    if (is_array($value)) {
                        $filterArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filterArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    } else {
        if ($method == 'get') {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);
                    //Kiểm tra người dùng nhập vào giá trị hay mảng
                    if (is_array($value)) {
                        $filterArray[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filterArray[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } else if ($method == 'post') {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);
                    //Kiểm tra người dùng nhập vào giá trị hay mảng
                    if (is_array($value)) {
                        $filterArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filterArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }
    return $filterArray;
}

//Hàm validate email
function validateEmail($email)
{
    if (!empty($email)) {
        $checkEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    }
    return $checkEmail;
}

//Hàm validate int
function validateInt($number)
{
    if (!empty($number)) {
        $checkInt = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    }
    return $checkInt;
}

//Hàm check phone
function isPhone($phone)
{
    if (!empty($phone)) {
        //Kiểm tra số đầu phải số 0 không
        $phoneFirst = false;
        if ($phone[0] == '0') {
            return $phoneFirst = true;
            $phone = substr($phone, 1);
        }
        //Kiểm tra 9 số còn lại có phải số nguyên không
        $phoneCheck = false;
        if (validateInt($phone)) {
            return $phoneCheck = true;
        }

        if ($phoneFirst && $phoneCheck) {
            return true;
        }
        return false;
    }
}

//Hàm chuyển hướng
function reload($path, $full = false)
{
    if ($full) {
        header("Location: $path");
        exit();
    } else {
        $url = _HOST_URL . $path;
        header("Location: $url");
        exit();
    }
}

//Hàm layout
function layout($viewName, $data = [])
{
    extract($data);
    if (file_exists('./app/Views/part/' . $viewName . '.php')) {
        require_once './app/Views/part/' . $viewName . '.php';
    }
}

//Hàm layout
function layoutPart($viewName, $data = [])
{
    extract($data);
    if (file_exists('./app/Views/layout-part/' . $viewName . '.php')) {
        require_once './app/Views/layout-part/' . $viewName . '.php';
    }
}

//hàm thông báo lỗi
// <div class="annouce-message alert alert-danger">Thong bao loi hoac thanh cong</div> 
function getMsg($msg, $msg_type)
{
    // echo ' <div class = "announce-message alert alert-' . $msg_type . '">';
    echo ' <div class = "announce-message alert alert-' . $msg_type . '" style="padding-left: 3rem;">';
    echo $msg;
    echo '</div>';
}

//hiển thị lỗi
function formError($errors, $fieldName)
{
    if (!empty($errors[$fieldName])) {
        echo '<div class="error">' . reset($errors[$fieldName]) . '</div>';
    }
}

//Hàm hiển thị lại giá trị cũ nếu lỡ bấm f5 hoặc nhập sai
function oldData($oldData, $fieldName)
{
    return (!empty($oldData[$fieldName])) ? $oldData[$fieldName] : NULL;
}

function renderMoviePlayer($url)
{
    $url = trim($url);

    // --- TRƯỜNG HỢP 0: URL RỖNG HOẶC NULL (Chưa có link) ---
    if (empty($url)) {
        return '
        <div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; background:#0f0f0f; border-radius:8px; border:1px solid #333;">
            <div style="position:absolute; top:0; left:0; width:100%; height:100%; display:flex; flex-direction:column; justify-content:center; align-items:center; color:#ccc; z-index:10;">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:15px; opacity:0.6;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                
                <h3 style="margin:0 0 5px 0; font-size:18px; font-weight:600; color:#fff;">Tập phim đang cập nhật</h3>
                <p style="margin:0; font-size:14px; opacity:0.7;">Vui lòng quay lại sau hoặc chọn server khác (nếu có).</p>
                
                <a href="javascript:void(0)" onclick="alert(\'Đã gửi báo cáo cho Admin!\')" style="margin-top:15px; padding:8px 20px; background:#e50914; color:#fff; text-decoration:none; border-radius:4px; font-size:13px; font-weight:bold;">
                    <i class="fa fa-flag"></i> Báo lỗi phim
                </a>
            </div>
        </div>';
    }

    // --- TRƯỜNG HỢP 1: Link HLS (.m3u8) từ API KKPhim ---
    if (strpos($url, '.m3u8') !== false) {
        return '
        <div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; background:#000;">
            <video id="hls-video" controls style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain;" playsinline>
                <source src="' . htmlspecialchars($url) . '" type="application/x-mpegURL">
                Trình duyệt không hỗ trợ phát video này.
            </video>
        </div>';
    }

    // --- TRƯỜNG HỢP 2: Link Embed Iframe (Dữ liệu cũ) ---
    // Fix link myvidplay sang d000d cho mượt (nếu cần)
    $url = str_replace('myvidplay.com', 'd000d.com', $url);

    return '<div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                <iframe src="' . htmlspecialchars($url) . '" 
                        style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; fullscreen"
                        webkitallowfullscreen="true" 
                        mozallowfullscreen="true">
                </iframe>
            </div>';
}

// Hàm đổi phút sang giờ
function convertMinutesToHours($minutes)
{
    $minutes = (int)$minutes;
    if ($minutes < 0) $minutes = 0; // tuỳ bạn có muốn chặn âm không

    $hours = intdiv($minutes, 60);
    $mins  = $minutes % 60;

    if ($hours === 0) {
        return $mins . 'm';
    }

    return $hours . 'h ' . $mins . 'm';
}


// Check login
function isLogin()
{
    // Lấy token từ Session
    $tokenLogin = getSession('tokenLogin');

    if (empty($tokenLogin)) {
        return false;
    }

    $model = new CoreModel();
    // Lấy thông tin User tương ứng với Token đó
    $sql = "SELECT u.* FROM token_login t 
            JOIN users u ON t.user_id = u.id 
            WHERE t.token = '$tokenLogin' AND u.status = 1";

    $user = $model->getOne($sql);

    if (!empty($user)) {
        // Lưu thông tin user vào Session Auth để dùng lại ở các trang khác
        $_SESSION['auth'] = [
            'id' => $user['id'],
            'fullname' => $user['fullname'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'address' => $user['address'],
            'bio' => $user['bio'],
            'group_id' => $user['group_id'],
            'avatar' => $user['avartar']
        ];
        return true;
    } else {
        // Token không hợp lệ hoặc user bị khóa -> Xóa session
        removeSession('tokenLogin');
        removeSession('auth');
        return false;
    }
}

// Hàm nối ? và tham số trên url
function getUrlParams($key, $value)
{
    // Lấy tất cả tham số trên url
    $params = $_GET;

    //Kiểm tra giá trị có rỗng hay null không
    if ($value === '' || $value === null) {
        // nếu rỗng hoặc null thì xóa tham số đó
        unset($params[$key]);
    } else {
        // nếu không rỗng hoặc null thì gán giá trị vào tham số
        $params[$key] = $value;
    }

    //Ví dụ đang ở trang 10 phim hành động mà giờ chọn lại phim tình cảm thì phải set về lại page = 1
    if ($key !== 'page') {
        unset($params['page']);
    }

    //Nối lại url
    return '?' . http_build_query($params);
}

//Hàm tính thời gian trước
function timeAgo($datetime)
{
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) return 'Vừa xong';
    if ($diff < 3600) return floor($diff / 60) . ' phút trước';
    if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
    if ($diff < 604800) return floor($diff / 86400) . ' ngày trước';

    return date('d/m/Y H:i', $time);
}

// Hàm lấy settings từ database (có cache)
function getSiteSettings()
{
    static $settings = null;

    if ($settings === null) {
        $settingModel = new Setting();
        $settingsArray = $settingModel->getAllSettings();

        // Convert array thành key-value
        $settings = [];
        foreach ($settingsArray as $item) {
            $settings[$item['setting_key']] = $item['setting_value'];
        }

        // Set default values nếu chưa có
        $defaults = [
            'site_name' => 'PhePhim',
            'site_description' => 'Trải nghiệm xem phim đỉnh cao',
            'site_email' => 'contact@phephim.com',
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($settings[$key]) || empty($settings[$key])) {
                $settings[$key] = $value;
            }
        }
    }

    return $settings;
}
