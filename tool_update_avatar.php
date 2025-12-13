<?php
// --------------------------------------------------------------------------
// SCRIPT CẬP NHẬT AVATAR TỰ ĐỘNG TỪ TMDB
// --------------------------------------------------------------------------

// 1. CẤU HÌNH KẾT NỐI (Lấy từ thông tin bạn cung cấp)
$host = 'localhost';
$dbname = 'movie';
$username = 'root';
$password = '';

// API Key bạn đã cung cấp
$tmdbApiKey = '0e3b943475e881fdc65dcdcbcc13cbaf';

// Cấu hình đường dẫn ảnh (Bạn có thể đổi thành w500 để nhẹ hơn nếu muốn)
$imageBaseUrl = 'https://image.tmdb.org/t/p/original';

// Tăng thời gian thực thi vì chạy loop API sẽ lâu
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tool Update Avatar</title>
    <style>
        body {
            font-family: monospace;
            background: #1a1a1a;
            color: #ccc;
            padding: 20px;
        }

        .log-item {
            margin-bottom: 5px;
            border-bottom: 1px solid #333;
            padding: 5px 0;
        }

        .success {
            color: #4caf50;
        }

        .fail {
            color: #f44336;
        }

        .skip {
            color: #ff9800;
        }

        .info {
            color: #2196f3;
            font-weight: bold;
        }

        img {
            vertical-align: middle;
            border-radius: 4px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <h3>--- BẮT ĐẦU QUÁ TRÌNH QUÉT VÀ CẬP NHẬT ---</h3>
    <?php

    try {
        // 2. KẾT NỐI DATABASE
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 3. LẤY DANH SÁCH DIỄN VIÊN THIẾU AVATAR
        // Kiểm tra avatar là NULL hoặc chuỗi rỗng
        $sql = "SELECT id, name FROM persons WHERE avatar IS NULL OR avatar = ''";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = count($persons);
        echo "<div class='log-item info'>Tìm thấy <strong>$count</strong> người chưa có avatar.</div>";

        if ($count > 0) {
            // Chuẩn bị câu lệnh Update để dùng lại nhiều lần
            $updateStmt = $pdo->prepare("UPDATE persons SET avatar = :avatar WHERE id = :id");

            foreach ($persons as $person) {
                $personId = $person['id'];
                $personName = trim($person['name']);

                // Bỏ qua nếu tên rỗng
                if (empty($personName)) continue;

                // 4. GỌI API TMDB
                // Sử dụng cURL để gọi API search person
                $encodedName = urlencode($personName);
                $apiUrl = "https://api.themoviedb.org/3/search/person?api_key=$tmdbApiKey&query=$encodedName&language=vi-VN";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200) {
                    $data = json_decode($response, true);

                    // Kiểm tra xem có kết quả nào không
                    if (isset($data['results']) && count($data['results']) > 0) {
                        // Lấy kết quả đầu tiên (độ chính xác cao nhất)
                        $result = $data['results'][0];

                        if (!empty($result['profile_path'])) {
                            // Tạo link ảnh đầy đủ
                            $fullAvatarUrl = $imageBaseUrl . $result['profile_path'];

                            // 5. UPDATE VÀO DATABASE
                            $updateStmt->execute([
                                ':avatar' => $fullAvatarUrl,
                                ':id' => $personId
                            ]);

                            echo "<div class='log-item success'>
                                [OK] ID: $personId - <strong>$personName</strong> 
                                <br> -> Đã cập nhật: <a href='$fullAvatarUrl' target='_blank'>Link ảnh</a>
                                <img src='$fullAvatarUrl' width='30' height='45'>
                              </div>";
                        } else {
                            echo "<div class='log-item skip'>[SKIP] ID: $personId - $personName: Tìm thấy trên TMDB nhưng không có ảnh profile_path.</div>";
                        }
                    } else {
                        echo "<div class='log-item fail'>[FAIL] ID: $personId - $personName: Không tìm thấy trên TMDB.</div>";
                    }
                } else {
                    echo "<div class='log-item fail'>[ERROR] Lỗi kết nối API cho $personName (HTTP $httpCode).</div>";
                }

                // Flush bộ đệm để in ra màn hình ngay lập tức, không đợi chạy xong hết mới hiện
                if (ob_get_level() > 0) {
                    ob_flush();
                    flush();
                }

                // --- QUAN TRỌNG: SLEEP ---
                // Ngủ 0.25 giây để tránh bị TMDB chặn do spam request (Rate Limit)
                usleep(250000);
            }
        } else {
            echo "<div class='log-item success'>Tuyệt vời! Tất cả diễn viên đã có avatar.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='log-item fail'>Lỗi Database: " . $e->getMessage() . "</div>";
    }
    ?>
    <div class='log-item info' style="margin-top:20px;">--- HOÀN TẤT ---</div>
</body>

</html>