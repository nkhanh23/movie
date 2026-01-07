<?php

/**
 * HƯỚNG DẪN SỬ DỤNG:
 * 1. Đổi tên file này thành 'configs.php'
 * 2. Điền thông tin database Localhost vào phần 'if ($is_local)'
 * 3. Cấu hình Environment Variables trên Vercel cho phần 'else'
 */

// 1. Tự động nhận diện môi trường
$is_local = ($_SERVER['SERVER_NAME'] === 'localhost');

// --- CẤU HÌNH DATABASE & API ---
if ($is_local) {
    // === MÔI TRƯỜNG LOCALHOST (XAMPP/LARAGON) ===
    // Điền thông tin máy cá nhân của bạn vào đây
    define('_HOST', 'localhost');
    define('_DB',   'movie');       // Tên database của bạn
    define('_USER', 'root');        // User mặc định
    define('_PASS', '');            // Pass mặc định thường để trống
    define('_PORT', '3306');        // Port mặc định

    // API Keys (Ở local có thể điền cứng để test cho nhanh)
    define('_GOOGLE_CLIENT_ID',     'your_local_google_id');
    define('_GOOGLE_CLIENT_SECRET', 'your_local_google_secret');
    define('_TMDB_API_KEY',         'your_local_tmdb_key');

    // Bật hiện lỗi khi code ở máy nhà
    define('_DEBUG', true);
} else {
    // === MÔI TRƯỜNG SERVER (VERCEL/PRODUCTION) ===
    // Không sửa ở đây - Hãy vào Vercel > Settings > Environment Variables để điền
    define('_HOST', getenv('DB_HOST'));
    define('_DB',   getenv('DB_NAME'));
    define('_USER', getenv('DB_USER'));
    define('_PASS', getenv('DB_PASS'));
    // Nếu quên set port thì mặc định lấy 4000 (cho TiDB) hoặc 3306
    define('_PORT', getenv('DB_PORT') ? getenv('DB_PORT') : '4000');

    // Lấy API Key từ biến môi trường để bảo mật
    define('_GOOGLE_CLIENT_ID',     getenv('GOOGLE_ID'));
    define('_GOOGLE_CLIENT_SECRET', getenv('GOOGLE_SECRET'));
    define('_TMDB_API_KEY',         getenv('TMDB_KEY'));

    // Tắt hiện lỗi khi chạy thật để bảo mật
    define('_DEBUG', false);
}

// --- CẤU HÌNH CỐ ĐỊNH (KHÔNG CẦN GIẤU) ---
define('_DRIVER', 'mysql');
define('_TMDB_IMAGE_BASE', 'https://image.tmdb.org/t/p/original');
define('_TMDB_AVATAR_THUMB', 'https://image.tmdb.org/t/p/w185');

// --- TỰ ĐỘNG XỬ LÝ URL ---
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$subfolder = ($scriptDir !== '/' && $scriptDir !== '\\') ? $scriptDir : '';

define('_HOST_URL', $protocol . $_SERVER['HTTP_HOST'] . $subfolder);
define('_HOST_URL_PUBLIC', _HOST_URL . '/public');
define('_DIR_ROOT', __DIR__);

// Google Redirect URL tự động theo tên miền
define('_GOOGLE_REDIRECT_URL', _HOST_URL . '/auth/google/callback');

// Security check
defined('_nkhanhh') or define('_nkhanhh', true);
