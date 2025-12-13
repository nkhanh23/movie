<?php
// --------------------------------------------------------------------------
// TOOL UPDATE ẢNH V7: QUÉT TOÀN BỘ (SCAN ALL)
// --------------------------------------------------------------------------

// 1. CẤU HÌNH
$host = 'localhost';
$dbname = 'movie';
$username = 'root';
$password = '';

$tmdbApiKey = '0e3b943475e881fdc65dcdcbcc13cbaf';
$tmdbImageBase = 'https://image.tmdb.org/t/p/original';

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

function callApi($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MovieUpdaterTool/1.0 (admin@example.com)');
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function getWikipediaPosterAdvanced($movieTitle)
{
    $searchUrl = "https://vi.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($movieTitle) . "&format=json&srlimit=1";
    $searchData = callApi($searchUrl);

    if (!isset($searchData['query']['search'][0]['title'])) {
        return null;
    }
    $wikiTitle = $searchData['query']['search'][0]['title'];

    // Cách 1: PageImages
    $quickUrl = "https://vi.wikipedia.org/w/api.php?action=query&titles=" . urlencode($wikiTitle) . "&prop=pageimages&format=json&pithumbsize=1000";
    $quickData = callApi($quickUrl);
    if (isset($quickData['query']['pages'])) {
        foreach ($quickData['query']['pages'] as $page) {
            if (isset($page['thumbnail']['source'])) {
                return $page['thumbnail']['source'];
            }
        }
    }

    // Cách 2: Deep Scan
    $imagesUrl = "https://vi.wikipedia.org/w/api.php?action=query&titles=" . urlencode($wikiTitle) . "&prop=images&format=json&imlimit=20";
    $imagesData = callApi($imagesUrl);
    $candidateImages = [];

    if (isset($imagesData['query']['pages'])) {
        foreach ($imagesData['query']['pages'] as $page) {
            if (isset($page['images']) && is_array($page['images'])) {
                foreach ($page['images'] as $img) {
                    $candidateImages[] = $img['title'];
                }
            }
        }
    }

    if (empty($candidateImages)) return null;

    $titlesParam = implode('|', array_map('urlencode', $candidateImages));
    $infoUrl = "https://vi.wikipedia.org/w/api.php?action=query&titles=$titlesParam&prop=imageinfo&iiprop=url|size|mime&format=json";
    $infoData = callApi($infoUrl);

    $bestImage = null;
    $maxScore = 0;

    if (isset($infoData['query']['pages'])) {
        foreach ($infoData['query']['pages'] as $page) {
            if (!isset($page['imageinfo'][0]['url'])) continue;
            $info = $page['imageinfo'][0];
            $url = $info['url'];
            $width = $info['width'];
            $height = $info['height'];
            $mime = $info['mime'];
            $title = $page['title'];

            if ($width < 150 || $height < 200) continue;
            if (strpos($mime, 'image/') === false) continue;
            if (strpos($title, '.svg') !== false) continue;

            $score = 0;
            if ($height > $width) $score += 50;
            if (stripos($title, 'poster') !== false) $score += 20;
            if (stripos($title, 'áp phích') !== false) $score += 20;
            if (stripos($title, 'bia') !== false) $score += 10;
            if ($width > 300) $score += 10;

            if ($score > $maxScore) {
                $maxScore = $score;
                $bestImage = $url;
            }
        }
    }
    return $bestImage;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tool V7 (Scan All)</title>
    <style>
        body {
            font-family: monospace;
            background: #111;
            color: #eee;
            padding: 20px;
            font-size: 13px;
            line-height: 1.5;
        }

        .log-item {
            border-bottom: 1px solid #222;
            padding: 8px 0;
            display: flex;
            align-items: flex-start;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 5px;
            color: #fff;
        }

        .tmdb {
            background-color: #01b4e4;
            color: #000;
        }

        .wiki {
            background-color: #d63384;
            color: #fff;
        }

        .success {
            color: #4caf50;
            font-weight: bold;
        }

        .full {
            color: #81d4fa;
            font-weight: bold;
        }

        /* Màu xanh dương nhạt cho Full */
        .fail {
            color: #f44336;
            font-weight: bold;
        }

        .gray {
            color: #666;
        }

        .thumb-preview {
            height: 50px;
            margin-left: 10px;
            border: 1px solid #444;
            border-radius: 3px;
        }

        a {
            color: #81d4fa;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h3>--- QUÉT TẤT CẢ PHIM (Scan All) ---</h3>
    <?php

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // [THAY ĐỔI]: Lấy TẤT CẢ phim, sắp xếp mới nhất lên đầu
        $sql = "SELECT id, tittle, release_year, poster_url, thumbnail, img FROM movies ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<div style='margin-bottom:20px; color:#aaa'>Tổng số phim cần quét: <strong>" . count($movies) . "</strong></div>";

        $updateStmt = $pdo->prepare("UPDATE movies SET poster_url = :poster, thumbnail = :thumb, img = :img WHERE id = :id");

        foreach ($movies as $movie) {
            $id = $movie['id'];
            $title = trim($movie['tittle']);
            $year = $movie['release_year'];

            $finalPoster = $movie['poster_url'];
            $finalThumb  = $movie['thumbnail'];
            $finalImg    = $movie['img'];

            // Kiểm tra nhanh: Nếu đã đủ 3 ảnh thì bỏ qua luôn (Tiết kiệm API)
            if (!empty($finalPoster) && !empty($finalThumb) && !empty($finalImg)) {
                echo "<div class='log-item'>
                    <div style='width: 60px;' class='full'>[FULL]</div>
                    <div style='flex:1' class='gray'>
                        $title ($year) - Đã đủ ảnh.
                    </div>
                  </div>";

                if (ob_get_level() > 0) {
                    ob_flush();
                    flush();
                }
                continue; // Chuyển sang phim tiếp theo
            }

            $updatesLog = [];

            // --- 1. WIKIPEDIA (CHẠY TRƯỚC) ---
            if (empty($finalPoster)) {
                $wikiImg = getWikipediaPosterAdvanced($title);
                if ($wikiImg) {
                    $finalPoster = $wikiImg;
                    $updatesLog[] = "<span class='badge wiki'>Poster</span>";
                    if (empty($finalThumb)) {
                        $finalThumb = $wikiImg;
                        $updatesLog[] = "<span class='badge wiki'>Thumb</span>";
                    }
                }
            }

            // --- 2. TMDB (CHẠY SAU - Lấp chỗ trống còn lại) ---
            $tmdbUrl = "https://api.themoviedb.org/3/search/movie?api_key=$tmdbApiKey&query=" . urlencode($title) . "&language=vi-VN&year=$year";
            $tmdbData = callApi($tmdbUrl);

            if (isset($tmdbData['results']) && count($tmdbData['results']) > 0) {
                $tmdbMovie = $tmdbData['results'][0];

                if (empty($finalPoster) && !empty($tmdbMovie['poster_path'])) {
                    $finalPoster = $tmdbImageBase . $tmdbMovie['poster_path'];
                    $updatesLog[] = "<span class='badge tmdb'>Poster</span>";
                }
                if (empty($finalImg) && !empty($tmdbMovie['backdrop_path'])) {
                    $finalImg = $tmdbImageBase . $tmdbMovie['backdrop_path'];
                    $updatesLog[] = "<span class='badge tmdb'>Backdrop</span>";
                }
                if (empty($finalThumb)) {
                    if (!empty($tmdbMovie['backdrop_path'])) {
                        $finalThumb = $tmdbImageBase . $tmdbMovie['backdrop_path'];
                        $updatesLog[] = "<span class='badge tmdb'>Thumb(Backdrop)</span>";
                    } elseif (!empty($tmdbMovie['poster_path'])) {
                        $finalThumb = $tmdbImageBase . $tmdbMovie['poster_path'];
                        $updatesLog[] = "<span class='badge tmdb'>Thumb(Poster)</span>";
                    }
                }
            }

            // --- 3. CẬP NHẬT DATABASE ---
            if (!empty($updatesLog)) {
                $updateStmt->execute([
                    ':poster' => $finalPoster,
                    ':thumb'  => $finalThumb,
                    ':img'    => $finalImg,
                    ':id'     => $id
                ]);

                $previewImg = $finalPoster ?: ($finalThumb ?: $finalImg);
                echo "<div class='log-item'>
                    <div style='width: 60px;' class='success'>[UPDATED]</div>
                    <div style='flex:1'>
                        <strong>$title</strong> <span class='gray'>($year)</span><br>
                        <small>Thêm mới: " . implode(' ', $updatesLog) . "</small>
                    </div>
                    " . ($previewImg ? "<a href='$previewImg' target='_blank'><img src='$previewImg' class='thumb-preview'></a>" : "") . "
                  </div>";
            } else {
                echo "<div class='log-item'>
                    <div style='width: 60px;' class='fail'>[SKIP]</div>
                    <div style='flex:1'>
                        <span class='gray'>$title ($year)</span> - <small class='gray'>Không tìm thấy thêm ảnh nào</small>
                    </div>
                  </div>";
            }

            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }
            usleep(200000);
        }

        echo "<br><div class='success' style='font-size:16px; margin-top:20px;'>--- HOÀN TẤT ---</div>";
    } catch (Exception $e) {
        echo "<div style='color:red'>Lỗi: " . $e->getMessage() . "</div>";
    }
    ?>
</body>

</html>