<?php
// configs.example.php - File này up lên GitHub

// 1. Logic tự động nhận diện môi trường
$is_local = ($_SERVER['SERVER_NAME'] === 'localhost');

if ($is_local) {
    // --- MÔI TRƯỜNG LOCALHOST ---
    // (Phần này thực ra code sẽ chạy file configs.php thật của bạn, 
    // nhưng để example ở đây cho đủ cấu trúc)
    define('_HOST', 'localhost');
    define('_DB',   'movie');
    define('_USER', 'root');
    define('_PASS', '');
    define('_PORT', '3306');

    // API Key Local (Điền tạm để nhớ cấu trúc)
    define('_GOOGLE_CLIENT_ID',     'your_local_google_id');
    define('_GOOGLE_CLIENT_SECRET', 'your_local_google_secret');
    define('_TMDB_API_KEY',         'your_local_tmdb_key');

    define('_DEBUG', true);
} else {
    // --- MÔI TRƯỜNG VERCEL ---
    // 1. Lấy Database từ Biến môi trường
    define('_HOST', getenv('DB_HOST'));
    define('_DB',   getenv('DB_NAME'));
    define('_USER', getenv('DB_USER'));
    define('_PASS', getenv('DB_PASS'));
    define('_PORT', getenv('DB_PORT') ? getenv('DB_PORT') : '4000');

    // 2. Lấy API KEY từ Biến môi trường (Bạn vừa nhập ở Bước 1)
    define('_GOOGLE_CLIENT_ID',     getenv('GOOGLE_ID'));
    define('_GOOGLE_CLIENT_SECRET', getenv('GOOGLE_SECRET'));
    define('_TMDB_API_KEY',         getenv('TMDB_KEY'));

    define('_DEBUG', false);
}

// --- CẤU HÌNH CỐ ĐỊNH (KHÔNG CẦN GIẤU) ---
define('_DRIVER', 'mysql');
define('_TMDB_IMAGE_BASE', 'https://image.tmdb.org/t/p/original');
define('_TMDB_AVATAR_THUMB', 'https://image.tmdb.org/t/p/w185');

// --- LOGIC URL (GIỮ NGUYÊN) ---
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$subfolder = ($scriptDir !== '/' && $scriptDir !== '\\') ? $scriptDir : '';

define('_HOST_URL', $protocol . $_SERVER['HTTP_HOST'] . $subfolder);
define('_HOST_URL_PUBLIC', _HOST_URL . '/public');
define('_DIR_ROOT', __DIR__);

// Redirect URL sẽ tự động đổi theo tên miền (localhost hoặc vercel)
define('_GOOGLE_REDIRECT_URL', _HOST_URL . '/auth/google/callback');

// Security check
defined('_nkhanhh') or define('_nkhanhh', true);
