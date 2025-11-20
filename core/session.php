<?php
//Hàm tạo session
function setSession($key, $value)
{
    if (!empty(session_id())) {
        $_SESSION[$key] = $value;
        return true;
    }
    return false;
}
//Hàm lấy dữ liệu từ session
function getSession($key = '')
{
    if (empty($key)) {
        //Nếu key rỗng trả về toàn bộ biến trong $session
        return $_SESSION;
    } else {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    return false;
}

//Hàm xóa session
function removeSession($key = '')
{
    if (empty($key)) {
        session_destroy();
        return true;
    } else {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
    }
    return false;
}

// Hàm flash session
function setSessionFlash($key, $value)
{
    $key = $key . 'flash';
    $result = setSession($key, $value);
    return $result;
}

//Hàm lấy flash session
function getSessionFlash($key)
{
    $key = $key . 'flash';
    $result = getSession($key);
    removeSession($key);
    return $result;
}
