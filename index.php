<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start(); // tranh loi tu cac ham header, cooke

// Load Composer libraries
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

foreach (glob(__DIR__ . '/configs/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/core/*php') as $filename) {
    require_once $filename;
}

require_once './core/mailer/Exception.php';
require_once './core/mailer/PHPMailer.php';
require_once './core/mailer/SMTP.php';

// Kiểm tra chế độ bảo trì (Maintenance Mode)
// [START] MAINTENANCE MODE CHECK
try {
    // 1. Kết nối Database
    $connect = Database::connectPDO();

    // 2. Lấy toàn bộ thông tin cần thiết (Trạng thái, Lời nhắn, Thời gian)
    // Sửa lại đúng tên cột: setting_key, setting_value
    $stmt = $connect->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('maintenance_mode', 'maintenance_message', 'maintenance_end')");
    $stmt->execute();

    // Fetch dạng Key-Value: ['maintenance_mode' => '1', 'maintenance_message' => '...']
    $settingsArr = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 3. Xác định trạng thái
    $isMaintenance = isset($settingsArr['maintenance_mode']) && $settingsArr['maintenance_mode'] == '1';

    if ($isMaintenance) {
        // 4. Kiểm tra URL chuẩn (Dùng REQUEST_URI giống Router phía dưới để chính xác nhất)
        $projectName = '/movie'; // Cần khớp với biến $projectName ở dưới
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

            // Load view
            require_once __DIR__ . '/app/Views/bao_tri.php';
            exit; // Dừng hệ thống ngay lập tức
        }
    }
} catch (Exception $e) {
    // Nếu lỗi DB, log lại và cho qua (để tránh web sập trắng trang chỉ vì lỗi check bảo trì)
    // error_log($e->getMessage());
}
// [END] MAINTENANCE MODE CHECK

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
