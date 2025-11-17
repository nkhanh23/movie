<?php
//Hàm gửi mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($emailTo, $subject, $content)
{

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function


    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'nkhanh2305@gmail.com';                     //SMTP username
        $mail->Password   = 'cbxv eatt imhj bsks';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('nkhanh2305@gmail.com', 'Nkhanh Course');
        $mail->addAddress($emailTo,);     //Add a recipient

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
                        $filterArray[$key] = filter_var(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filterArray[$key] = filter_var(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
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
                        $filterArray[$key] = filter_var(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filterArray[$key] = filter_var(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }
}

//Hàm validate email
function validateEmail($email)
{
    if (!empty($email)) {
        $checkEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}

//Hàm validate int
function validateInt($number)
{
    if (!empty($number)) {
        $checkInt = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    }
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
        $url = _HOST . $path;
        header("Location: $url");
        exit();
    }
}

//Hàm layout
function layout($viewName, $data = [])
{
    extract($data);
    if (file_exists('/app/Views/part' . $viewName . '.php')) {
        require_once '/app/Views/part' . $viewName . '.php';
    }
}
