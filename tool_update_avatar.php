<?php
// --------------------------------------------------------------------------
// SCRIPT CẬP NHẬT THÔNG TIN DIỄN VIÊN: AVATAR, BIO, AWARDS, START_YEAR
// --------------------------------------------------------------------------

// 1. CẤU HÌNH - Include config file của dự án
require_once __DIR__ . '/configs/configs.php';

$host = _HOST;
$dbname = _DB;
$username = _USER;
$password = _PASS;

$tmdbApiKey = '0e3b943475e881fdc65dcdcbcc13cbaf';
$tmdbImageBase = 'https://image.tmdb.org/t/p/w500';

set_time_limit(0);

// KẾT NỐI DB
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
    $pdo->query("UPDATE persons SET avatar = NULL, bio = NULL, awards = NULL, nominations = NULL, start_year = NULL");
    $msg = "Đã xóa toàn bộ thông tin để làm lại từ đầu.";
}

// --- HÀM HELPER ---
function fetchUrl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) movie-avatar-tool/1.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode == 200) ? $data : null;
}

/**
 * Tìm person trên TMDB và lấy đầy đủ thông tin
 */
function getPersonFromTmdb($name, $apiKey, $imageBase)
{
    $result = [
        'avatar' => null,
        'bio' => null,
        'awards' => null,
        'nominations' => null,
        'start_year' => null,
        'source' => null
    ];

    // Bước 1: Search person
    $encodedName = urlencode($name);
    $searchUrl = "https://api.themoviedb.org/3/search/person?api_key=$apiKey&query=$encodedName&language=vi-VN";
    $json = fetchUrl($searchUrl);

    if (!$json) return $result;

    $data = json_decode($json, true);
    if (empty($data['results'])) return $result;

    // Tìm person phù hợp
    $personId = null;
    foreach ($data['results'] as $person) {
        if (in_array($person['known_for_department'] ?? '', ['Acting', 'Directing', 'Production', 'Writing'])) {
            $personId = $person['id'];
            if (!empty($person['profile_path'])) {
                $result['avatar'] = $imageBase . $person['profile_path'];
            }
            break;
        }
    }

    if (!$personId) return $result;

    // Bước 2: Lấy chi tiết person
    $bio = null;
    $detailUrl = "https://api.themoviedb.org/3/person/$personId?api_key=$apiKey&language=vi-VN";
    $detailJson = fetchUrl($detailUrl);

    if ($detailJson) {
        $detail = json_decode($detailJson, true);

        if (!empty($detail['biography'])) {
            $bio = $detail['biography'];
        }
        // Nếu bio tiếng Việt rỗng, thử lấy tiếng Anh
        if (empty($bio)) {
            $detailUrlEn = "https://api.themoviedb.org/3/person/$personId?api_key=$apiKey&language=en-US";
            $detailDataEn = json_decode(fetchUrl($detailUrlEn), true);
            if (!empty($detailDataEn['biography'])) {
                $bio = $detailDataEn['biography'];
            }
        }
        $result['bio'] = $bio;

        // Avatar fallback
        if (!$result['avatar'] && !empty($detail['profile_path'])) {
            $result['avatar'] = $imageBase . $detail['profile_path'];
        }
    }

    // Bước 3: Lấy credits để tính start_year và ước tính awards
    $creditsUrl = "https://api.themoviedb.org/3/person/$personId/combined_credits?api_key=$apiKey";
    $creditsJson = fetchUrl($creditsUrl);

    if ($creditsJson) {
        $credits = json_decode($creditsJson, true);
        $allWorks = array_merge(
            $credits['cast'] ?? [],
            $credits['crew'] ?? []
        );

        // Tìm năm đầu tiên
        $years = [];
        foreach ($allWorks as $work) {
            $date = $work['release_date'] ?? $work['first_air_date'] ?? null;
            if ($date && strlen($date) >= 4) {
                $year = (int) substr($date, 0, 4);
                if ($year > 1900 && $year <= date('Y')) {
                    $years[] = $year;
                }
            }
        }

        if (!empty($years)) {
            $result['start_year'] = min($years);
        }

        // Ước tính awards/nominations dựa trên số credits
        $totalCredits = count($allWorks);
        $result['awards'] = max(0, (int)($totalCredits * 0.02));
        $result['nominations'] = max(0, (int)($totalCredits * 0.05));
    }

    $result['source'] = 'TMDB';
    return $result;
}

/**
 * Tìm avatar từ Wikipedia
 */
function getAvatarFromWiki($name, $lang = 'en')
{
    $endpoint = "https://$lang.wikipedia.org/w/api.php";
    if ($lang == 'vi') {
        $query = '"' . $name . '" (diễn viên OR đạo diễn OR phim OR nghệ sĩ)';
        $blackList = ['chính trị gia', 'vua', 'hoàng đế', 'tướng quân', 'cầu thủ'];
    } else {
        $query = '"' . $name . '" (actor OR director OR filmmaker OR actress)';
        $blackList = ['politician', 'king', 'emperor', 'general', 'footballer'];
    }

    $params = [
        'action' => 'query',
        'format' => 'json',
        'prop' => 'pageimages|pageterms|extracts',
        'piprop' => 'thumbnail',
        'pithumbsize' => 600,
        'generator' => 'search',
        'gsrsearch' => $query,
        'gsrlimit' => 3,
        'wbptterms' => 'description',
        'exintro' => true,
        'explaintext' => true,
        'exsentences' => 5
    ];
    $url = $endpoint . "?" . http_build_query($params);
    $json = fetchUrl($url);

    $result = ['avatar' => null, 'bio' => null];

    if ($json) {
        $data = json_decode($json, true);
        if (!empty($data['query']['pages'])) {
            foreach ($data['query']['pages'] as $page) {
                $desc = "";
                if (isset($page['terms']['description'])) {
                    $desc = mb_strtolower(implode(' ', $page['terms']['description']), 'UTF-8');
                }

                // Skip blacklisted
                foreach ($blackList as $badWord) {
                    if (strpos($desc, $badWord) !== false) continue 2;
                }

                if (isset($page['thumbnail']['source'])) {
                    $result['avatar'] = $page['thumbnail']['source'];
                }
                if (isset($page['extract'])) {
                    $result['bio'] = $page['extract'];
                }
                break;
            }
        }
    }
    return $result;
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
    <title>Tool Cập Nhật Thông Tin Diễn Viên</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
            padding-top: 200px;
        }

        .dashboard {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 180px;
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
            top: 140px;
            left: 50%;
            transform: translateX(-50%);
            background: #2196F3;
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 10000;
        }

        .log-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .item {
            display: flex;
            align-items: flex-start;
            border-bottom: 1px solid #333;
            padding: 12px 0;
            animation: fadeIn 0.3s;
            flex-wrap: wrap;
        }

        .item-main {
            display: flex;
            align-items: center;
            flex-grow: 1;
            width: 100%;
        }

        .item-details {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
            padding-left: 90px;
            width: 100%;
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
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-left: 15px;
            border: 2px solid #444;
        }

        .info-tag {
            display: inline-block;
            padding: 2px 6px;
            background: #333;
            border-radius: 3px;
            margin-right: 5px;
            font-size: 10px;
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
            <h3 style="margin: 0; color: #01d277;">🎭 PERSON INFO AUTO-UPDATER</h3>
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

        <div style="text-align:center; margin-top:10px; font-size:11px; color:#666;">
            📊 Lấy: Avatar, Bio, Start Year, Awards, Nominations từ TMDB + Wikipedia
        </div>
    </div>

    <?php if ($msg): ?>
        <div class="alert-box"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if ($isRunning): ?>
        <div class="log-container">

            <?php
            // LOGIC CHẠY TOOL
            $sql = "SELECT id, name FROM persons WHERE avatar IS NULL OR avatar = '' LIMIT 100";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($persons) == 0) {
                echo "<div style='text-align:center; padding:50px; color:#aaa;'>
                    <h4>✅ Đã hoàn tất!</h4>
                    <p>Không còn diễn viên nào cần xử lý.</p>
                   </div>";
            }

            $updateStmt = $pdo->prepare("UPDATE persons SET avatar = :avatar, bio = :bio, awards = :awards, nominations = :nominations, start_year = :start_year, updated_at = NOW() WHERE id = :id");

            foreach ($persons as $person) {
                $pId = $person['id'];
                $pName = trim($person['name']);

                if (empty($pName)) continue;

                $personData = [
                    'avatar' => null,
                    'bio' => null,
                    'awards' => null,
                    'nominations' => null,
                    'start_year' => null
                ];
                $sourceBadge = '';

                // 1. TMDB (ưu tiên - có nhiều thông tin nhất)
                $tmdbResult = getPersonFromTmdb($pName, $tmdbApiKey, $tmdbImageBase);
                if ($tmdbResult['avatar']) {
                    $personData = $tmdbResult;
                    $sourceBadge = '<span class="badge bg-tmdb">TMDB</span>';
                }

                // 2. Wiki EN fallback
                if (!$personData['avatar']) {
                    $wikiResult = getAvatarFromWiki($pName, 'en');
                    if ($wikiResult['avatar']) {
                        $personData['avatar'] = $wikiResult['avatar'];
                        if ($wikiResult['bio']) $personData['bio'] = $wikiResult['bio'];
                        $sourceBadge = '<span class="badge bg-en">Wiki EN</span>';
                    }
                }

                // 3. Wiki VI fallback
                if (!$personData['avatar']) {
                    $wikiResult = getAvatarFromWiki($pName, 'vi');
                    if ($wikiResult['avatar']) {
                        $personData['avatar'] = $wikiResult['avatar'];
                        if ($wikiResult['bio']) $personData['bio'] = $wikiResult['bio'];
                        $sourceBadge = '<span class="badge bg-vi">Wiki VI</span>';
                    }
                }

                echo "<div class='item'>";
                echo "<div class='item-main'>";
                echo "<span style='color:#666; width:50px;'>#$pId</span>";

                if ($personData['avatar']) {
                    $updateStmt->execute([
                        ':avatar' => $personData['avatar'],
                        ':bio' => $personData['bio'],
                        ':awards' => $personData['awards'],
                        ':nominations' => $personData['nominations'],
                        ':start_year' => $personData['start_year'],
                        ':id' => $pId
                    ]);
                    echo "<div style='color:#4caf50; width:40px;'>[OK]</div>";
                    echo "<div style='flex-grow:1; font-weight:500'>$pName $sourceBadge</div>";
                    echo "<a href='{$personData['avatar']}' target='_blank'><img src='{$personData['avatar']}' class='thumb'></a>";
                    echo "</div>";

                    // Chi tiết
                    echo "<div class='item-details'>";
                    if ($personData['start_year']) echo "<span class='info-tag'>📅 Bắt đầu: {$personData['start_year']}</span>";
                    if ($personData['awards']) echo "<span class='info-tag'>🏆 Awards: {$personData['awards']}</span>";
                    if ($personData['nominations']) echo "<span class='info-tag'>⭐ Nominations: {$personData['nominations']}</span>";
                    if ($personData['bio']) echo "<span class='info-tag'>📝 Bio: " . mb_substr($personData['bio'], 0, 80) . "...</span>";
                    echo "</div>";
                } else {
                    $updateStmt->execute([
                        ':avatar' => '0',
                        ':bio' => null,
                        ':awards' => null,
                        ':nominations' => null,
                        ':start_year' => null,
                        ':id' => $pId
                    ]);
                    echo "<div style='color:#666; width:40px;'>[0]</div>";
                    echo "<div style='flex-grow:1; color:#666'>$pName</div>";
                    echo "</div>";
                }
                echo "</div>";

                // Auto scroll
                echo "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

                if (ob_get_level() > 0) {
                    ob_flush();
                    flush();
                }
                usleep(300000); // 0.3s delay để tránh rate limit
            }

            // Tự động reload
            if (count($persons) > 0) {
                echo "<script>setTimeout(function(){ window.location.reload(); }, 1500);</script>";
            }
            ?>
        </div>
    <?php else: ?>
        <div style="text-align:center; padding-top: 50px; color: #666;">
            <p>Nhấn nút <strong>CHẠY NGAY</strong> ở trên để bắt đầu.</p>
            <p style="font-size:12px; margin-top:20px;">
                Tool sẽ lấy: <br>
                ✅ Avatar (ảnh đại diện)<br>
                ✅ Bio (tiểu sử)<br>
                ✅ Start Year (năm bắt đầu sự nghiệp)<br>
                ✅ Awards (số giải thưởng ước tính)<br>
                ✅ Nominations (số đề cử ước tính)
            </p>
        </div>
    <?php endif; ?>

</body>

</html>