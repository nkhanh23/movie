<?php
// --------------------------------------------------------------------------
// TOOL UPDATE NĂM V3: TÌM KIẾM NÂNG CAO & LÀM SẠCH TỪ KHÓA
// --------------------------------------------------------------------------

// 1. CẤU HÌNH
$host = 'localhost';
$dbname = 'movie';
$username = 'root';
$password = '';

// Tên bảng và cột chứa năm
$tableYear = 'release_year';
$yearColumnName = 'year'; // Sửa thành 'name' hoặc 'year' nếu cần

$tmdbApiKey = '0e3b943475e881fdc65dcdcbcc13cbaf';

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- HÀM HỖ TRỢ ---

function callApi($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MovieUpdaterTool/1.0 (admin@example.com)');
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Hàm làm sạch tên phim để tìm kiếm chính xác hơn
function cleanMovieTitle($title)
{
    // Loại bỏ nội dung trong ngoặc đơn. VD: "Doona! (2023)" -> "Doona!"
    $title = preg_replace('/\s*\(.*?\)\s*/', '', $title);
    // Loại bỏ các từ khóa mùa. VD: "Season 1", "Phần 2"
    $title = preg_replace('/(Season|Phần|Tập)\s*\d+/i', '', $title);
    return trim($title);
}

// 1. Tìm trên TMDB (Chính xác nhất)
function getYearFromTMDB($title, $apiKey)
{
    // Thử tìm tiếng Việt
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=" . urlencode($title) . "&language=vi-VN";
    $data = callApi($url);

    if (isset($data['results']) && count($data['results']) > 0) {
        $date = $data['results'][0]['release_date'];
        if (!empty($date)) return substr($date, 0, 4); // Lấy 4 ký tự đầu (Năm)
    }

    // Nếu không thấy, thử tìm tiếng Anh (cho phim Âu Mỹ)
    $urlEn = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=" . urlencode($title) . "&language=en-US";
    $dataEn = callApi($urlEn);
    if (isset($dataEn['results']) && count($dataEn['results']) > 0) {
        $date = $dataEn['results'][0]['release_date'];
        if (!empty($date)) return substr($date, 0, 4);
    }

    // Nếu vẫn không thấy, thử tìm TV Series (vì Doona! là phim bộ)
    $urlTv = "https://api.themoviedb.org/3/search/tv?api_key=$apiKey&query=" . urlencode($title) . "&language=vi-VN";
    $dataTv = callApi($urlTv);
    if (isset($dataTv['results']) && count($dataTv['results']) > 0) {
        $date = $dataTv['results'][0]['first_air_date']; // TV Series dùng first_air_date
        if (!empty($date)) return substr($date, 0, 4);
    }

    return null;
}

// 2. Tìm trên Wikipedia (Dự phòng)
function getYearFromWiki($title)
{
    $searchUrl = "https://vi.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($title) . "&format=json&srlimit=3";
    $data = callApi($searchUrl);

    if (isset($data['query']['search'])) {
        foreach ($data['query']['search'] as $item) {
            // Cách 1: Tìm trong tiêu đề bài viết: "Tên Phim (2023)"
            if (preg_match('/\(.*?(\d{4}).*?\)/', $item['title'], $matches)) {
                return $matches[1];
            }

            // Cách 2: Tìm trong đoạn trích dẫn (snippet): "là phim... năm 2023"
            $snippet = strip_tags($item['snippet']); // Xóa thẻ HTML
            if (preg_match('/(năm|dựng|chiếu|mắt)\s+(\d{4})/', $snippet, $matches)) {
                return $matches[2];
            }
        }
    }
    return null;
}

function getOrInsertYearId($pdo, $year, $tableName, $colName)
{
    // Validate năm hợp lệ (1900 - 2100)
    if (!is_numeric($year) || $year < 1800 || $year > 2100) return null;

    $sql = "SELECT id FROM $tableName WHERE `$colName` = :year LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':year' => $year]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['id'];
    } else {
        $sqlInsert = "INSERT INTO $tableName (`$colName`) VALUES (:year)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([':year' => $year]);
        return $pdo->lastInsertId();
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tool Update Years V3 (Smart)</title>
    <style>
        body {
            font-family: monospace;
            background: #1a1a1a;
            color: #ddd;
            padding: 20px;
            font-size: 13px;
            line-height: 1.5;
        }

        .log-item {
            border-bottom: 1px solid #333;
            padding: 5px 0;
        }

        .success {
            color: #4caf50;
            font-weight: bold;
        }

        .fail {
            color: #f44336;
        }

        .tmdb {
            color: #01b4e4;
            font-weight: bold;
        }

        .wiki {
            color: #d63384;
            font-weight: bold;
        }

        .new-id {
            color: #ff9800;
        }
    </style>
</head>

<body>
    <h3>--- CẬP NHẬT NĂM V3 (Ưu tiên TMDB + Làm sạch tên) ---</h3>
    <?php

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Lấy phim chưa có năm
        $sql = "SELECT id, tittle FROM movies WHERE release_year IS NULL OR release_year = '' OR release_year = 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<div style='color:#aaa; margin-bottom:15px'>Tìm thấy <strong>" . count($movies) . "</strong> phim cần xử lý.</div>";

        $updateStmt = $pdo->prepare("UPDATE movies SET release_year = :year_id WHERE id = :movie_id");

        foreach ($movies as $movie) {
            $movieId = $movie['id'];
            $originalTitle = trim($movie['tittle']);

            // BƯỚC QUAN TRỌNG: Làm sạch tên phim
            $cleanTitle = cleanMovieTitle($originalTitle);

            $foundYear = null;
            $source = "";

            // 1. Tìm TMDB (Movie & TV)
            $foundYear = getYearFromTMDB($cleanTitle, $tmdbApiKey);
            if ($foundYear) {
                $source = "<span class='tmdb'>TMDB</span>";
            } else {
                // 2. Nếu không thấy, Tìm Wikipedia
                $foundYear = getYearFromWiki($cleanTitle);
                if ($foundYear) {
                    $source = "<span class='wiki'>Wiki</span>";
                }
            }

            if ($foundYear) {
                $yearId = getOrInsertYearId($pdo, $foundYear, $tableYear, $yearColumnName);

                if ($yearId) {
                    $updateStmt->execute([':year_id' => $yearId, ':movie_id' => $movieId]);

                    echo "<div class='log-item'>
                        <span class='success'>[OK]</span> <strong>$originalTitle</strong> 
                        <span style='color:#777'>(Tìm: $cleanTitle)</span><br>
                        -> Năm: $foundYear ($source) -> ID: <span class='new-id'>$yearId</span>
                      </div>";
                } else {
                    echo "<div class='log-item fail'>[ERR] $originalTitle: Tìm thấy năm '$foundYear' nhưng không hợp lệ.</div>";
                }
            } else {
                echo "<div class='log-item fail'>[FAIL] <strong>$originalTitle</strong> <span style='color:#555'>(Tìm: $cleanTitle)</span>: Không tìm thấy năm.</div>";
            }

            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }
            usleep(250000); // 0.25s
        }

        echo "<br><div class='success' style='font-size:16px'>--- HOÀN TẤT ---</div>";
    } catch (Exception $e) {
        echo "<div class='fail'>Lỗi: " . $e->getMessage() . "</div>";
    }
    ?>
</body>

</html>