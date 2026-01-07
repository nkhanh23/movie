<?php
// Update config Vercel fix error
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start(); // tranh loi tu cac ham header, cooke

ini_set('display_errors', 1); // BẬT TẠM ĐỂ DEBUG
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Load Composer libraries
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}


// foreach (glob(__DIR__ . '/configs/*.php') as $filename) {
//     if (strpos($filename, '.example.php') !== false) {
//         continue;
//     }
//     require_once $filename;
// }

// 1. Kiểm tra xem có file cấu hình gốc (configs.php) không?
$hasRealConfig = file_exists(__DIR__ . '/configs/configs.php');

foreach (glob(__DIR__ . '/configs/*.php') as $filename) {
    // Nếu gặp file example (configs.example.php)
    if (strpos($filename, '.example.php') !== false) {
        // Chỉ nạp file example NẾU KHÔNG CÓ file thật
        // (Tức là đang chạy trên Vercel)
        if (!$hasRealConfig) {
            require_once $filename;
        }
        continue; // Xử lý xong thì bỏ qua để không nạp trùng
    }

    // Các file config khác (nếu có) thì nạp bình thường
    require_once $filename;
}

foreach (glob(__DIR__ . '/core/*php') as $filename) {
    require_once $filename;
}

require_once './core/mailer/Exception.php';
require_once './core/mailer/PHPMailer.php';
require_once './core/mailer/SMTP.php';

// chế độ bảo trì (Maintenance Mode)

try {
    // 1. Kết nối Database
    $connect = Database::connectPDO();

    // 2. Lấy toàn bộ thông tin cần thiết (Trạng thái, Lời nhắn, Thời gian)
    $stmt = $connect->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('maintenance_mode', 'maintenance_message', 'maintenance_end')");
    $stmt->execute();

    // Fetch dạng Key-Value: ['maintenance_mode' => '1', 'maintenance_message' => '...']
    $settingsArr = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 3. Xác định trạng thái
    $isMaintenance = isset($settingsArr['maintenance_mode']) && $settingsArr['maintenance_mode'] == '1';

    if ($isMaintenance) {
        // 4. Kiểm tra URL chuẩn (Dùng REQUEST_URI giống Router phía dưới để chính xác nhất)
        $projectName = '/movie';
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Loại bỏ tên dự án nếu có để lấy đường dẫn thực tế
        $currentPath = str_replace($projectName, '', $requestUri);

        // Check xem có phải Admin hoặc trang Login không
        // strpos trả về 0 nghĩa là chuỗi bắt đầu bằng '/admin' hoặc '/auth'
        $isAdminRoute = (strpos($currentPath, '/admin') === 0) || (strpos($currentPath, '/auth/login') === 0);

        // 5. Nếu KHÔNG phải Admin thì chặn
        if (!$isAdminRoute) {
            // Gán biến để view sử dụng
            $maintenance_message = $settingsArr['maintenance_message'] ?? 'Hệ thống đang bảo trì.';
            $maintenance_end = $settingsArr['maintenance_end'] ?? '';

            // Xóa buffer để tránh in ra HTML thừa từ header/footer nếu có lỡ load
            if (ob_get_level()) ob_end_clean();


            require_once __DIR__ . '/app/Views/bao_tri.php';
            exit;
        }
    }
} catch (Exception $e) {
}


$router = new Router();

foreach (glob(__DIR__ . '/router/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Models/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/admin/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/clients/*php') as $filename) {
    require_once $filename;
}



$projectName = '/movie';
$method = $_SERVER['REQUEST_METHOD'];
$url = str_replace($projectName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$router->handlePath($method, $url);
