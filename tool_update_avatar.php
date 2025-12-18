<?php
// --------------------------------------------------------------------------
// SCRIPT CẬP NHẬT AVATAR: AUTO SCROLL & STICKY DASHBOARD
// --------------------------------------------------------------------------

// 1. CẤU HÌNH
$host = 'localhost';
$dbname = 'movie';
$username = 'root';
$password = '';

$tmdbApiKey = '0e3b943475e881fdc65dcdcbcc13cbaf';
$tmdbImageBase = 'https://image.tmdb.org/t/p/w500';

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// KẾT NỐI DB SỚM
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi DB: " . $e->getMessage());
}

// --- XỬ LÝ NÚT BẤM ---
$msg = "";
if (isset($_POST['action']) && $_POST['action'] == 'reset_not_found') {
    $sqlReset = "UPDATE persons SET avatar = NULL WHERE avatar = '0'";
    $stmtReset = $pdo->prepare($sqlReset);
    $stmtReset->execute();
    $msg = "Đã reset " . $stmtReset->rowCount() . " mục '0' về NULL.";
}
if (isset($_POST['action']) && $_POST['action'] == 'reset_all') {
    $pdo->query("UPDATE persons SET avatar = NULL");
    $msg = "Đã xóa toàn bộ avatar để làm lại từ đầu.";
}

// --- HÀM HELPER ---
function fetchUrl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) movie-avatar-tool/1.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode == 200) ? $data : null;
}

function getAvatarFromTmdb($name, $apiKey, $imageBase)
{
    $encodedName = urlencode($name);
    $url = "https://api.themoviedb.org/3/search/person?api_key=$apiKey&query=$encodedName&language=vi-VN";
    $json = fetchUrl($url);
    if ($json) {
        $data = json_decode($json, true);
        if (isset($data['results']) && count($data['results']) > 0) {
            foreach ($data['results'] as $person) {
                if (in_array($person['known_for_department'], ['Acting', 'Directing', 'Production', 'Writing'])) {
                    if (!empty($person['profile_path'])) return $imageBase . $person['profile_path'];
                }
            }
        }
    }
    return null;
}

function getAvatarFromWiki($name, $lang = 'en')
{
    $endpoint = "https://$lang.wikipedia.org/w/api.php";
    if ($lang == 'vi') {
        $query = '"' . $name . '" (diễn viên OR đạo diễn OR phim OR nghệ sĩ OR minh tinh OR show)';
        $blackList = ['chính trị gia', 'vua', 'hoàng đế', 'tướng quân', 'cầu thủ', 'vận động viên', 'tội phạm'];
    } else {
        $query = '"' . $name . '" (actor OR director OR filmmaker OR drama OR movie OR actress)';
        $blackList = ['politician', 'king', 'emperor', 'general', 'footballer', 'athlete', 'criminal'];
    }

    $params = [
        'action' => 'query',
        'format' => 'json',
        'prop' => 'pageimages|pageterms',
        'piprop' => 'thumbnail',
        'pithumbsize' => 600,
        'generator' => 'search',
        'gsrsearch' => $query,
        'gsrlimit' => 3,
        'wbptterms' => 'description'
    ];
    $url = $endpoint . "?" . http_build_query($params);
    $json = fetchUrl($url);
    if ($json) {
        $data = json_decode($json, true);
        if (!empty($data['query']['pages'])) {
            foreach ($data['query']['pages'] as $page) {
                $desc = "";
                if (isset($page['terms']['description'])) $desc = mb_strtolower(implode(' ', $page['terms']['description']), 'UTF-8');
                foreach ($blackList as $badWord) {
                    if (strpos($desc, $badWord) !== false) continue 2;
                }
                if (isset($page['thumbnail']['source'])) return $page['thumbnail']['source'];
            }
        }
    }
    return null;
}

// LẤY THỐNG KÊ
$countNull = $pdo->query("SELECT COUNT(*) FROM persons WHERE avatar IS NULL OR avatar = ''")->fetchColumn();
$countMarked = $pdo->query("SELECT COUNT(*) FROM persons WHERE avatar = '0'")->fetchColumn();
$countDone = $pdo->query("SELECT COUNT(*) FROM persons WHERE avatar IS NOT NULL AND avatar != '' AND avatar != '0'")->fetchColumn();

$isRunning = isset($_GET['run']) && $_GET['run'] == 1;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tool Quản Lý Avatar</title>
    <style>
        /* CSS CHO PHẦN CỐ ĐỊNH */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
            padding-top: 170px;
            /* Đẩy nội dung xuống để tránh bị Dashboard che mất */
        }

        .dashboard {
            position: fixed;
            /* Ghim cứng lên đầu */
            top: 0;
            left: 0;
            width: 100%;
            height: 150px;
            background: #1e1e1e;
            border-bottom: 3px solid #01d277;
            z-index: 9999;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .dash-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .stat-box {
            text-align: center;
            padding: 0 30px;
            border-right: 1px solid #333;
        }

        .stat-box:last-child {
            border-right: none;
        }

        .stat-num {
            font-size: 28px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 14px;
            color: #888;
            text-transform: uppercase;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }

        .btn-start {
            background: #01d277;
            color: #000;
        }

        .btn-reset {
            background: #ff9800;
            color: #000;
        }

        .btn-danger {
            background: #f44336;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .alert-box {
            position: fixed;
            top: 110px;
            left: 50%;
            transform: translateX(-50%);
            background: #2196F3;
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 10000;
        }

        /* LOG LIST */
        .log-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #333;
            padding: 8px 0;
            font-size: 14px;
            animation: fadeIn 0.3s;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            margin-right: 8px;
            color: #fff;
            font-weight: bold;
        }

        .bg-tmdb {
            background: #01d277;
            color: #000;
        }

        .bg-en {
            background: #3f51b5;
        }

        .bg-vi {
            background: #d32f2f;
        }

        .thumb {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
            margin-left: 15px;
            border: 2px solid #444;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="dashboard">
        <div class="dash-row">
            <h3 style="margin: 0; color: #01d277;">🎥 AVATAR AUTO-UPDATER</h3>

            <div style="display:flex;">
                <?php if (!$isRunning): ?>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Reset lại các mục không tìm thấy?');">
                        <input type="hidden" name="action" value="reset_not_found">
                        <button class="btn btn-reset">↺ QUÉT LẠI MỤC '0'</button>
                    </form>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('CẢNH BÁO: Xóa HẾT làm lại từ đầu?');">
                        <input type="hidden" name="action" value="reset_all">
                        <button class="btn btn-danger">⚠ RESET ALL</button>
                    </form>
                    <a href="?run=1" class="btn btn-start">▶ CHẠY NGAY</a>
                <?php else: ?>
                    <a href="?" class="btn btn-danger">⏹ DỪNG LẠI</a>
                <?php endif; ?>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #333; width: 100%; margin: 15px 0;">

        <div class="dash-row" style="justify-content: center;">
            <div class="stat-box">
                <div class="stat-num" style="color:#4caf50"><?php echo number_format($countDone); ?></div>
                <div class="stat-label">Đã xong</div>
            </div>
            <div class="stat-box">
                <div class="stat-num" style="color:#f44336"><?php echo number_format($countNull); ?></div>
                <div class="stat-label">Chờ xử lý</div>
            </div>
            <div class="stat-box">
                <div class="stat-num" style="color:#ff9800"><?php echo number_format($countMarked); ?></div>
                <div class="stat-label">Không tìm thấy</div>
            </div>
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert-box"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if ($isRunning): ?>
        <div class="log-container">

            <?php
            // LOGIC CHẠY TOOL
            $sql = "SELECT id, name FROM persons WHERE avatar IS NULL OR avatar = '' LIMIT 500";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($persons) == 0) {
                echo "<div style='text-align:center; padding:50px; color:#aaa;'>
                    <h4>Đã hoàn tất đợt quét này!</h4>
                    <p>Đang kiểm tra lại...</p>
                   </div>";
            }

            $updateStmt = $pdo->prepare("UPDATE persons SET avatar = :avatar WHERE id = :id");

            foreach ($persons as $person) {
                $pId = $person['id'];
                $pName = trim($person['name']);

                if (empty($pName)) continue;

                $avatarUrl = null;
                $sourceBadge = '';

                // 1. TMDB
                $avatarUrl = getAvatarFromTmdb($pName, $tmdbApiKey, $tmdbImageBase);
                if ($avatarUrl) $sourceBadge = '<span class="badge bg-tmdb">TMDB</span>';

                // 2. Wiki EN
                if (!$avatarUrl) {
                    $avatarUrl = getAvatarFromWiki($pName, 'en');
                    if ($avatarUrl) $sourceBadge = '<span class="badge bg-en">EN</span>';
                }

                // 3. Wiki VI
                if (!$avatarUrl) {
                    $avatarUrl = getAvatarFromWiki($pName, 'vi');
                    if ($avatarUrl) $sourceBadge = '<span class="badge bg-vi">VI</span>';
                }

                echo "<div class='item'>";
                echo "<span style='color:#666; width:50px;'>#$pId</span>";

                if ($avatarUrl) {
                    $updateStmt->execute([':avatar' => $avatarUrl, ':id' => $pId]);
                    echo "<div style='color:#4caf50; width:40px;'>[OK]</div>";
                    echo "<div style='flex-grow:1; font-weight:500'>$pName $sourceBadge</div>";
                    echo "<a href='$avALTER TABLE movies ADD COLUMN temp_poster VARCHAR(500);

UPDATE movies
SET temp_poster = poster_url;

UPDATE movies
SET 
    poster_url = thumbnail,
    thumbnail  = temp_poster;

ALTER TABLE movies DROP COLUMN temp_poster;
atarUrl' target='_blank'><img src='$avatarUrl' class='thumb'></a>";
                } else {
                    $updateStmt->execute([':avatar' => '0', ':id' => $pId]);
                    echo "<div style='color:#666; width:40px;'>[0]</div>";
                    echo "<div style='flex-grow:1; color:#666'>$pName</div>";
                }
                echo "</div>";

                // --- JAVASCRIPT AUTO SCROLL ---
                // Dòng này giúp trang web tự động cuộn xuống dưới cùng
                echo "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

                if (ob_get_level() > 0) {
                    ob_flush();
                    flush();
                }
                usleep(150000);
            }

            // Tự động reload
            if (count($persons) > 0) {
                echo "<script>setTimeout(function(){ window.location.reload(); }, 1000);</script>";
            } else {
                echo "<script>alert('Đã xử lý sạch sẽ!'); window.location.href = window.location.pathname;</script>";
            }
            ?>
        </div>
    <?php else: ?>
        <div style="text-align:center; padding-top: 50px; color: #666;">
            <p>Nhấn nút <strong>CHẠY NGAY</strong> ở trên để bắt đầu.</p>
        </div>
    <?php endif; ?>

</body>

</html>