<?php
class CrawlerController extends baseController
{
    private $conn;
    private $apiBase = "https://phimapi.com";
    private $ophimBase = "https://ophim1.com";
    private $tmdbApiKey;
    private $tmdbBase = "https://api.themoviedb.org/3";
    private $currentApiSource = 'phimapi'; // 'phimapi' or 'ophim'
    private $currentListType = 'phim-moi-cap-nhat'; // Loại danh sách đang crawl

    public function __construct()
    {
        // Tăng time limit để tránh timeout khi crawl nhiều phim
        set_time_limit(0);

        $this->conn = Database::connectPDO();
        $this->tmdbApiKey = defined('_TMDB_API_KEY') ? _TMDB_API_KEY : '';
    }

    // Lưu page cao nhất đã crawl
    private function saveLastCrawledPage($page)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM settings WHERE setting_key = 'crawler_last_page'");
            $stmt->execute();
            $exists = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($exists) {
                $this->conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'crawler_last_page'")
                    ->execute([$page]);
            } else {
                $this->conn->prepare("INSERT INTO settings (setting_key, setting_value, created_at) VALUES ('crawler_last_page', ?, NOW())")
                    ->execute([$page]);
            }
        } catch (Exception $e) {
            // Bỏ qua lỗi lưu setting
        }
    }

    // Lấy page cao nhất đã crawl
    public function getLastCrawledPage()
    {
        try {
            $stmt = $this->conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'crawler_last_page'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['setting_value'] : 1;
        } catch (Exception $e) {
            return 1;
        }
    }

    public function list()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Chỉ render view, không crawl
        // AJAX sẽ gọi syncApi() để crawl
        $data = [
            'page' => $page
        ];

        $this->renderView('/layout-part/admin/crawler/list', $data);
    }

    // TEST endpoint
    public function test()
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Test OK', 'time' => date('Y-m-d H:i:s')]);
        exit;
    }

    // API endpoint để tìm kiếm phim (không crawl ngay)
    public function searchMovies()
    {
        header('Content-Type: application/json');

        try {
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
            $apiSource = isset($_GET['source']) ? trim($_GET['source']) : 'phimapi';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            if (empty($keyword)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập từ khóa tìm kiếm']);
                exit;
            }

            // Set API source
            $this->currentApiSource = in_array($apiSource, ['phimapi', 'ophim']) ? $apiSource : 'phimapi';

            // Construct search URL
            if ($this->currentApiSource === 'ophim') {
                $url = $this->ophimBase . "/v1/api/tim-kiem?keyword=" . urlencode($keyword) . "&page=" . $page;
            } else {
                $url = $this->apiBase . "/v1/api/tim-kiem?keyword=" . urlencode($keyword) . "&page=" . $page;
            }

            $data = $this->fetchData($url);

            // Normalize data - both APIs wrap items in data.items
            $items = isset($data['data']['items']) ? $data['data']['items'] : [];

            // Return search results
            echo json_encode([
                'success' => true,
                'movies' => $items,
                'total' => count($items)
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // API endpoint để crawl qua AJAX
    public function syncApi()
    {
        // Tắt output errors để tránh HTML lẫn vào JSON
        @ini_set('display_errors', 0);
        error_reporting(0);

        header('Content-Type: application/json');

        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $apiSource = isset($_GET['source']) ? trim($_GET['source']) : 'phimapi';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 0; // 0 = không giới hạn
            $listType = isset($_GET['list_type']) ? trim($_GET['list_type']) : 'phim-moi-cap-nhat'; // Hỗ trợ: phim-chieu-rap, phim-bo, phim-le...
            $slugs = isset($_POST['slugs']) ? json_decode($_POST['slugs'], true) : [];

            // Set API source
            $this->currentApiSource = in_array($apiSource, ['phimapi', 'ophim']) ? $apiSource : 'phimapi';
            $this->currentListType = $listType;

            // Bắt đầu buffer để capture output
            ob_start();

            // Nếu có slugs → crawl theo slug, không theo page
            if (!empty($slugs) && is_array($slugs)) {
                foreach ($slugs as $slug) {
                    $this->processMovie($slug);
                }
                $hasMore = false; // Không có thêm page
            } else {
                // Crawl theo page với limit
                $hasMore = $this->syncPage($page, $search, $limit);
            }

            $output = ob_get_clean();

            // Trả về JSON
            echo json_encode([
                'success' => true,
                'page' => $page,
                'hasMore' => $hasMore,
                'output' => $output
            ]);
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'page' => $page ?? 1
            ]);
        }
        exit;
    }

    private function syncPage($page, $searchQuery = '', $limit = 0)
    {
        if ($this->currentApiSource === 'ophim') {
            // OPhim API
            if ($searchQuery) {
                $url = $this->ophimBase . "/v1/api/tim-kiem?keyword=" . urlencode($searchQuery) . "&page=" . $page;
            } else {
                // Hỗ trợ nhiều loại danh sách: phim-moi-cap-nhat, phim-chieu-rap, phim-bo, phim-le...
                $listSlug = $this->currentListType ?: 'phim-moi-cap-nhat';
                $url = $this->ophimBase . "/v1/api/danh-sach/" . $listSlug . "?page=" . $page;
            }
        } else {
            // PhimAPI
            if ($searchQuery) {
                $url = $this->apiBase . "/v1/api/tim-kiem?keyword=" . urlencode($searchQuery) . "&page=" . $page;
            } else {
                $url = $this->apiBase . "/danh-sach/phim-moi-cap-nhat?page=" . $page;
            }
        }

        $data = $this->fetchData($url);

        // Normalize data structure
        if ($this->currentApiSource === 'ophim') {
            // OPhim wraps in data.items
            $items = isset($data['data']['items']) ? $data['data']['items'] : [];
        } else {
            // PhimAPI direct access
            $items = isset($data['items']) ? $data['items'] : [];
        }

        if (empty($items)) {
            echo "<p style='color:red; font-weight:bold;'>Đã hết phim hoặc Lỗi API. Dừng lại.</p>";
            return false;
        }

        // Giới hạn số phim nếu có limit
        if ($limit > 0 && $limit < count($items)) {
            $items = array_slice($items, 0, $limit);
            echo "<p style='color:#f59e0b;'>⚡ Chế độ tiết kiệm: Chỉ crawl {$limit} phim/trang</p>";
        }

        foreach ($items as $item) {
            $this->processMovie($item['slug']);
        }

        // Lưu page cao nhất đã crawl thành công
        $this->saveLastCrawledPage($page);

        return true;
    }


    private function fetchData($url, $retries = 3)
    {
        $lastError = null;

        for ($i = 0; $i < $retries; $i++) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Tăng lên 60 giây
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Kiểm tra response
            if ($curlError) {
                $lastError = "cURL error: " . $curlError;
                sleep(2); // Đợi 2 giây trước khi retry
                continue;
            }

            if ($httpCode !== 200) {
                $lastError = "HTTP error: " . $httpCode;
                sleep(2);
                continue;
            }

            if (empty($res)) {
                $lastError = "Empty response";
                sleep(2);
                continue;
            }

            $data = json_decode($res, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $lastError = "JSON decode error: " . json_last_error_msg();
                sleep(2);
                continue;
            }

            return $data;
        }

        // Sau khi hết retry, log lỗi và trả về null
        error_log("fetchData failed after {$retries} retries for URL: {$url}. Last error: {$lastError}");
        return null;
    }


    private function processMovie($apiSlug)
    {
        // Construct URL based on API source
        if ($this->currentApiSource === 'ophim') {
            $url = $this->ophimBase . "/v1/api/phim/" . $apiSlug;
        } else {
            $url = $this->apiBase . "/phim/" . $apiSlug;
        }

        $data = $this->fetchData($url);

        // Normalize data structure
        if ($this->currentApiSource === 'ophim') {
            // OPhim wraps in data.item
            if (!isset($data['data']['item'])) return;
            $m = $data['data']['item'];
        } else {
            // PhimAPI uses movie
            if (!isset($data['movie'])) return;
            $m = $data['movie'];
        }

        $parsedInfo = $this->parseMovieName($m['name']);
        $baseName = $parsedInfo['baseName'];
        $seasonName = $parsedInfo['seasonName'];
        $baseSlug = $this->createSlug($baseName);

        echo "<div style='margin-bottom:5px; border-bottom:1px dashed #ccc; padding-bottom:5px;'>";
        echo "<strong>{$m['name']}</strong> ";

        // BƯỚC 1: MOVIES
        $countryId = $this->getCountryId($m['country']);
        $typeId    = $this->getTypeId($m['type']);

        // Kiểm tra nếu đang crawl từ danh sách phim chiếu rạp
        if ($this->currentListType === 'phim-chieu-rap') {
            $typeId = 3; // Phim Chiếu Rạp
        }
        // Hoặc kiểm tra dựa vào category
        elseif (isset($m['category']) && is_array($m['category'])) {
            foreach ($m['category'] as $cat) {
                $catSlug = $cat['slug'] ?? '';
                // Nếu có category là phim chiếu rạp, đổi type_id thành 3
                if (in_array($catSlug, ['phim-chieu-rap', 'chieurap', 'phim-le-chieu-rap'])) {
                    $typeId = 3; // Phim Chiếu Rạp
                    break;
                }
            }
        }

        $status_id = ($m['status'] == 'completed') ? 1 : 2;
        $duration = (int)filter_var($m['time'], FILTER_SANITIZE_NUMBER_INT);

        // Lấy thêm các trường mới từ API
        $ageId = isset($m['age']) ? $this->getAgeId($m['age']) : null;
        $qualityId = isset($m['quality']) ? $this->getQualityId($m['quality']) : null;

        // Tạo full URL cho poster và thumbnail
        // CDN khác nhau cho từng API source
        if ($this->currentApiSource === 'ophim') {
            $cdnBase = 'https://img.ophim.live/uploads/movies/';
        } else {
            $cdnBase = 'https://phimimg.com/';
        }

        $posterUrl = isset($m['poster_url']) ? $m['poster_url'] : '';
        $thumbUrl = isset($m['thumb_url']) ? $m['thumb_url'] : '';

        // Xử lý poster URL
        if ($posterUrl && strpos($posterUrl, 'http') !== 0) {
            // Nếu bắt đầu bằng /uploads/ thì dùng CDN ophim
            if (strpos($posterUrl, '/uploads/') === 0) {
                $posterUrl = 'https://img.ophim.live' . $posterUrl;
            } else {
                $posterUrl = $cdnBase . ltrim($posterUrl, '/');
            }
        }

        // Xử lý thumbnail URL
        if ($thumbUrl && strpos($thumbUrl, 'http') !== 0) {
            if (strpos($thumbUrl, '/uploads/') === 0) {
                $thumbUrl = 'https://img.ophim.live' . $thumbUrl;
            } else {
                $thumbUrl = $cdnBase . ltrim($thumbUrl, '/');
            }
        }

        // Lấy rating: OPhim có sẵn TMDB/IMDB data
        $tmdbRating = null;
        if ($this->currentApiSource === 'ophim') {
            // OPhim có cả tmdb và imdb
            if (isset($m['tmdb']['vote_average'])) {
                $tmdbRating = floatval($m['tmdb']['vote_average']);
            } elseif (isset($m['imdb']['vote_average'])) {
                $tmdbRating = floatval($m['imdb']['vote_average']);
            }
        } else {
            // PhimAPI chỉ có imdb.rating
            $tmdbRating = isset($m['imdb']['rating']) ? floatval($m['imdb']['rating']) : null;
        }

        // Làm giàu dữ liệu từ TMDB nếu chưa có
        if (!$tmdbRating || !$ageId) {
            $this->enrichWithTMDB($ageId, $tmdbRating, $baseName, $m['year'], $parsedInfo['isSeries']);
        }

        $stmt = $this->conn->prepare("SELECT id FROM movies WHERE slug = :slug");
        $stmt->execute([':slug' => $baseSlug]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        $movieId = 0;
        if ($exists) {
            $movieId = $exists['id'];
            $sql = "UPDATE movies SET original_tittle=:orig, description=:desc, poster_url=:poster, thumbnail=:thumb, release_year=:year, imdb_rating=:tmdb, age=:age, quality_id=:quality, status_id=:st, country_id=:ct, type_id=:tp, updated_at=NOW() WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':orig' => $m['origin_name'], ':desc' => $m['content'], ':poster' => $posterUrl, ':thumb' => $thumbUrl, ':year' => $m['year'], ':tmdb' => $tmdbRating, ':age' => $ageId, ':quality' => $qualityId, ':st' => $status_id, ':ct' => $countryId, ':tp' => $typeId, ':id' => $movieId]);
            echo "<span style='color:blue'>[UPDATE]</span>";
        } else {
            $sql = "INSERT INTO movies (tittle, original_tittle, slug, description, poster_url, thumbnail, release_year, duration, imdb_rating, age, quality_id, is_api, status_id, country_id, type_id, created_at, updated_at) VALUES (:name, :orig, :slug, :desc, :poster, :thumb, :year, :dur, :tmdb, :age, :quality, 1, :st, :ct, :tp, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':name' => $baseName, ':orig' => $m['origin_name'], ':slug' => $baseSlug, ':desc' => $m['content'], ':poster' => $posterUrl, ':thumb' => $thumbUrl, ':year' => $m['year'], ':dur' => $duration, ':tmdb' => $tmdbRating, ':age' => $ageId, ':quality' => $qualityId, ':st' => $status_id, ':ct' => $countryId, ':tp' => $typeId]);
            $movieId = $this->conn->lastInsertId();
            echo "<span style='color:green'>[NEW]</span>";
        }

        // Hiển thị thông tin các trường đã lấy
        echo "<br/><span style='font-size:11px; color:#94a3b8; margin-left:10px;'>📊 ";

        $fields = [];
        if ($m['year']) $fields[] = "Year: {$m['year']}";
        if ($duration) $fields[] = "Duration: {$duration}m";
        if ($tmdbRating) $fields[] = "<span style='color:#fbbf24'>⭐ Rating: " . number_format($tmdbRating, 1) . "</span>";
        if ($ageId) {
            $ageNames = [1 => 'P', 2 => 'T13', 3 => 'T16', 4 => 'T18'];
            $fields[] = "<span style='color:#f97316'>🔞 Age: {$ageNames[$ageId]}</span>";
        }
        if ($qualityId) {
            $qualityNames = [1 => 'CAM', 3 => 'Full HD', 5 => '4K'];
            $fields[] = "<span style='color:#10b981'>📺 {$qualityNames[$qualityId]}</span>";
        }
        if ($countryId) {
            $countryName = $m['country'][0]['name'] ?? 'N/A';
            $fields[] = "🌍 {$countryName}";
        }
        $typeNames = [1 => 'Phim Lẻ', 2 => 'Phim Bộ', 4 => 'Hoạt Hình'];
        if (isset($typeNames[$typeId])) {
            $fields[] = "🎬 {$typeNames[$typeId]}";
        }

        echo implode(' | ', $fields);
        echo "</span>";


        $this->processGenres($movieId, $m['category']);
        if (isset($m['actor']) && is_array($m['actor'])) $this->processPersons($movieId, $m['actor'], 'Dien vien');
        if (isset($m['director']) && is_array($m['director'])) $this->processPersons($movieId, $m['director'], 'Dao dien');

        // BƯỚC 2: SEASONS
        $currentSeasonId = null;
        if ($parsedInfo['isSeries'] && $seasonName) {
            $stmtS = $this->conn->prepare("SELECT id FROM seasons WHERE movie_id = :mid AND name = :sname");
            $stmtS->execute([':mid' => $movieId, ':sname' => $seasonName]);
            $sRow = $stmtS->fetch(PDO::FETCH_ASSOC);

            if ($sRow) {
                $currentSeasonId = $sRow['id'];
            } else {
                $stmtInS = $this->conn->prepare("INSERT INTO seasons (movie_id, name, description, poster_url, status_id, created_at, updated_at) VALUES (:mid, :name, :desc, :poster, :st, NOW(), NOW())");
                $stmtInS->execute([':mid' => $movieId, ':name' => $seasonName, ':desc' => $m['content'], ':poster' => $posterUrl, ':st' => $status_id]);
                $currentSeasonId = $this->conn->lastInsertId();
                echo " <span style='color:orange'>[SEASON]</span>";
            }
        }

        // BƯỚC 3: EPISODES
        if (isset($data['episodes'])) {
            $this->processEpisodes($movieId, $currentSeasonId, $data['episodes']);
        }

        echo "</div>";
        flush();
    }

    private function createSlug($str)
    {
        if (!$str) return '';
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return trim($str, '-');
    }

    private function parseMovieName($fullName)
    {
        $pattern = '/(.*?)\s*[\(\-\[]\s*(Phần|Season)\s+(\d+)[\)\-\]]?/iu';
        if (preg_match($pattern, $fullName, $matches)) {
            return ['baseName' => trim($matches[1]), 'seasonName' => "Phần " . $matches[3], 'isSeries' => true];
        }
        return ['baseName' => trim($fullName), 'seasonName' => null, 'isSeries' => false];
    }

    private function getTypeId($typeSlug)
    {
        // FIX: Dùng ID cố định, không tạo mới
        $mapping = [
            'series'   => 2,  // Phim Bộ
            'single'   => 1,  // Phim Lẻ
            'hoathinh' => 4,  // Hoạt Hình
            'tvshows'  => 2,  // TV Shows = Phim Bộ
        ];

        return $mapping[$typeSlug] ?? 1; // Mặc định: Phim Lẻ
    }

    private function getAgeId($ageRating)
    {
        if (empty($ageRating)) return null;

        // Map age rating từ API sang age_id trong database
        $mapping = [
            'P'    => 1,  // P - Phổ biến
            'T13'  => 2,  // T13
            'T16'  => 3,  // T16
            'T18'  => 4,  // T18
            '13+'  => 2,  // Nếu API trả "13+" thay vì "T13"
            '16+'  => 3,
            '18+'  => 4,
        ];

        // Nếu là string, thử map trực tiếp
        if (is_string($ageRating)) {
            return $mapping[strtoupper($ageRating)] ?? null;
        }

        return null;
    }

    private function getQualityId($quality)
    {
        if (empty($quality)) return null;

        // Map quality từ API sang quality_id trong bảng qualities
        $qualityStr = strtoupper(trim($quality));

        $mapping = [
            // CAM quality
            'CAM'       => 1,
            'CAMRIP'    => 1,
            'TC'        => 1,
            'TS'        => 1,

            // Full HD
            'FULLHD'    => 3,
            'FULL HD'   => 3,
            'FHD'       => 3,
            '1080P'     => 3,
            '1080'      => 3,

            // 4K
            '4K'        => 5,
            'UHD'       => 5,
            '2160P'     => 5,
            '2160'      => 5,
        ];

        // Thử map trực tiếp
        if (isset($mapping[$qualityStr])) {
            return $mapping[$qualityStr];
        }

        // Fallback: nếu chứa "4K" hoặc "2160" → 4K
        if (strpos($qualityStr, '4K') !== false || strpos($qualityStr, '2160') !== false) {
            return 5;
        }

        // Fallback: nếu chứa "1080" hoặc "FHD" → Full HD
        if (strpos($qualityStr, '1080') !== false || strpos($qualityStr, 'FHD') !== false) {
            return 3;
        }

        // Fallback: nếu chứa "CAM" → CAM
        if (strpos($qualityStr, 'CAM') !== false) {
            return 1;
        }

        return null; // Không map được
    }

    // ===== TMDB API METHODS =====

    private function searchTMDB($movieName, $year = null, $isSeries = false)
    {
        $type = $isSeries ? 'tv' : 'movie';
        $query = urlencode($movieName);
        $url = "{$this->tmdbBase}/search/{$type}?api_key={$this->tmdbApiKey}&query={$query}&language=vi-VN";

        if ($year) {
            $url .= "&year={$year}";
        }

        $data = $this->fetchData($url);

        if (!empty($data['results']) && count($data['results']) > 0) {
            return $data['results'][0]; // Lấy kết quả đầu tiên
        }

        return null;
    }

    private function getTMDBCertification($tmdbId, $isSeries = false)
    {
        $type = $isSeries ? 'tv' : 'movie';
        $endpoint = $isSeries ? 'content_ratings' : 'release_dates';
        $url = "{$this->tmdbBase}/{$type}/{$tmdbId}/{$endpoint}?api_key={$this->tmdbApiKey}";

        $data = $this->fetchData($url);

        if (!$data) return null;

        // Xử lý cho phim (movie)
        if (!$isSeries && isset($data['results'])) {
            // Ưu tiên: VN > US > bất kỳ
            $certVN = null;
            $certUS = null;
            $certAny = null;

            foreach ($data['results'] as $result) {
                if (!empty($result['release_dates'])) {
                    $cert = $result['release_dates'][0]['certification'] ?? '';
                    if (empty($cert)) continue;

                    if ($result['iso_3166_1'] == 'VN') {
                        $certVN = $cert;
                        break;
                    } elseif ($result['iso_3166_1'] == 'US') {
                        $certUS = $cert;
                    } elseif (!$certAny) {
                        $certAny = $cert;
                    }
                }
            }

            return $certVN ?: ($certUS ?: $certAny);
        }

        // Xử lý cho TV series
        if ($isSeries && isset($data['results'])) {
            foreach ($data['results'] as $result) {
                if ($result['iso_3166_1'] == 'VN' || $result['iso_3166_1'] == 'US') {
                    return $result['rating'] ?? null;
                }
            }
            // Fallback: lấy cái đầu tiên
            return $data['results'][0]['rating'] ?? null;
        }

        return null;
    }

    private function mapTMDBCertificationToAgeId($certification)
    {
        if (empty($certification)) return null;

        $cert = strtoupper(trim($certification));

        // Map TMDB certifications sang age_id
        // VN ratings: P, C13, C16, C18
        // US ratings: G, PG, PG-13, R, NC-17
        $mapping = [
            // Vietnam
            'P'    => 1,  // Phổ biến
            'C13'  => 2,  // T13
            'C16'  => 3,  // T16  
            'C18'  => 4,  // T18
            'T13'  => 2,
            'T16'  => 3,
            'T18'  => 4,
            // US ratings
            'G'     => 1,  // General Audiences → P
            'PG'    => 1,  // Parental Guidance → P
            'PG-13' => 2,  // Parents Strongly Cautioned → T13
            'R'     => 3,  // Restricted → T16
            'NC-17' => 4,  // Adults Only → T18
            // UK ratings
            'U'     => 1,  // Universal
            'PG'    => 1,
            '12'    => 2,
            '12A'   => 2,
            '15'    => 3,
            '18'    => 4,
        ];

        return $mapping[$cert] ?? null;
    }

    private function enrichWithTMDB(&$ageId, &$tmdbRating, $movieName, $year, $isSeries)
    {
        try {
            $tmdbResult = $this->searchTMDB($movieName, $year, $isSeries);

            if (!$tmdbResult) {
                return; // Không tìm thấy trên TMDB
            }

            $tmdbId = $tmdbResult['id'];

            // Lấy age rating nếu chưa có
            if (!$ageId) {
                $cert = $this->getTMDBCertification($tmdbId, $isSeries);
                if ($cert) {
                    $ageId = $this->mapTMDBCertificationToAgeId($cert);
                }
            }

            // Lấy rating nếu chưa có
            if (!$tmdbRating) {
                $tmdbRating = $tmdbResult['vote_average'] ?? null;
            }
        } catch (Exception $e) {
            // Bỏ qua lỗi TMDB, không ảnh hưởng đến crawl chính
        }
    }

    private function getCountryId($countryData)
    {
        if (empty($countryData)) return null;
        $cSlug = $countryData[0]['slug'];
        $cName = $countryData[0]['name'];
        $stmt = $this->conn->prepare("SELECT id FROM countries WHERE slug = ?");
        $stmt->execute([$cSlug]);
        if ($row = $stmt->fetch()) return $row['id'];
        $this->conn->prepare("INSERT INTO countries (name, slug) VALUES (?, ?)")->execute([$cName, $cSlug]);
        return $this->conn->lastInsertId();
    }

    private function processGenres($movieId, $categories)
    {
        $this->conn->prepare("DELETE FROM movie_genres WHERE movie_id = ?")->execute([$movieId]);
        foreach ($categories as $cat) {
            $stmt = $this->conn->prepare("SELECT id FROM genres WHERE slug = ?");
            $stmt->execute([$cat['slug']]);
            if ($row = $stmt->fetch()) {
                $gid = $row['id'];
            } else {
                $this->conn->prepare("INSERT INTO genres (name, slug, created_at) VALUES (?, ?, NOW())")->execute([$cat['name'], $cat['slug']]);
                $gid = $this->conn->lastInsertId();
            }
            $this->conn->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)")->execute([$movieId, $gid]);
        }
    }

    private function processPersons($movieId, $namesArray, $roleNameDefault)
    {
        if (empty($namesArray)) return;
        if (!is_array($namesArray)) $namesArray = [$namesArray];

        $roleSlug = $this->createSlug($roleNameDefault);
        $stmtRole = $this->conn->prepare("SELECT id FROM person_roles WHERE slug = :slug LIMIT 1");
        $stmtRole->execute([':slug' => $roleSlug]);
        if ($roleRow = $stmtRole->fetch(PDO::FETCH_ASSOC)) {
            $roleId = $roleRow['id'];
        } else {
            $nameDisplay = ($roleNameDefault == 'Dien vien') ? 'Diễn viên' : 'Đạo diễn';
            $this->conn->prepare("INSERT INTO person_roles (name, slug, created_at) VALUES (?, ?, NOW())")->execute([$nameDisplay, $roleSlug]);
            $roleId = $this->conn->lastInsertId();
        }

        foreach ($namesArray as $name) {
            $name = trim($name);
            if (empty($name)) continue;
            $personSlug = $this->createSlug($name);

            $stmtP = $this->conn->prepare("SELECT id FROM persons WHERE slug = :slug LIMIT 1");
            $stmtP->execute([':slug' => $personSlug]);
            $pRow = $stmtP->fetch(PDO::FETCH_ASSOC);

            if ($pRow) {
                $personId = $pRow['id'];
                // SKIP enrichment during crawl để tăng tốc độ
                // Dùng tool_update_avatar.php để enrich avatar sau
            } else {
                try {
                    // Insert person mới (chỉ tên và slug)
                    $this->conn->prepare("INSERT INTO persons (name, slug, created_at) VALUES (?, ?, NOW())")->execute([$name, $personSlug]);
                    $personId = $this->conn->lastInsertId();
                    // SKIP enrichment during crawl - dùng tool riêng sau
                } catch (Exception $e) {
                    continue;
                }
            }

            if ($personId) {
                $checkLink = $this->conn->prepare("SELECT id FROM movie_person WHERE movie_id=? AND person_id=? AND role_id=?");
                $checkLink->execute([$movieId, $personId, $roleId]);
                if (!$checkLink->fetch()) {
                    $this->conn->prepare("INSERT INTO movie_person (movie_id, person_id, role_id, created_at) VALUES (?, ?, ?, NOW())")->execute([$movieId, $personId, $roleId]);
                }
            }
        }
    }

    /**
     * Lấy thông tin diễn viên từ TMDB: avatar, bio, start_year, awards, nominations
     */
    private function enrichPersonFromTMDB($personId, $name)
    {
        try {
            // Bước 1: Search person trên TMDB
            $encodedName = urlencode($name);
            $searchUrl = "{$this->tmdbBase}/search/person?api_key={$this->tmdbApiKey}&query={$encodedName}&language=vi-VN";
            $searchData = $this->fetchData($searchUrl);

            if (empty($searchData['results'])) return;

            // Tìm person phù hợp (diễn viên, đạo diễn)
            $tmdbPersonId = null;
            $avatar = null;
            foreach ($searchData['results'] as $person) {
                if (in_array($person['known_for_department'] ?? '', ['Acting', 'Directing', 'Production', 'Writing'])) {
                    $tmdbPersonId = $person['id'];
                    if (!empty($person['profile_path'])) {
                        $avatar = 'https://image.tmdb.org/t/p/w500' . $person['profile_path'];
                    }
                    break;
                }
            }

            if (!$tmdbPersonId) return;

            // Bước 2: Lấy chi tiết person
            $bio = null;
            $detailUrl = "{$this->tmdbBase}/person/{$tmdbPersonId}?api_key={$this->tmdbApiKey}&language=vi-VN";
            $detailData = $this->fetchData($detailUrl);

            if ($detailData) {
                if (!empty($detailData['biography'])) {
                    $bio = $detailData['biography'];
                }
                // Nếu bio tiếng Việt rỗng, thử lấy tiếng Anh
                if (empty($bio)) {
                    $detailUrlEn = "{$this->tmdbBase}/person/{$tmdbPersonId}?api_key={$this->tmdbApiKey}&language=en-US";
                    $detailDataEn = $this->fetchData($detailUrlEn);
                    if (!empty($detailDataEn['biography'])) {
                        $bio = $detailDataEn['biography'];
                    }
                }
                // Avatar fallback
                if (!$avatar && !empty($detailData['profile_path'])) {
                    $avatar = 'https://image.tmdb.org/t/p/w500' . $detailData['profile_path'];
                }
            }

            // Bước 3: Lấy credits để tính start_year và ước tính awards
            $startYear = null;
            $awards = null;
            $nominations = null;

            $creditsUrl = "{$this->tmdbBase}/person/{$tmdbPersonId}/combined_credits?api_key={$this->tmdbApiKey}";
            $creditsData = $this->fetchData($creditsUrl);

            if ($creditsData) {
                $allWorks = array_merge(
                    $creditsData['cast'] ?? [],
                    $creditsData['crew'] ?? []
                );

                // Tìm năm đầu tiên
                $years = [];
                foreach ($allWorks as $work) {
                    $date = $work['release_date'] ?? $work['first_air_date'] ?? null;
                    if ($date && strlen($date) >= 4) {
                        $year = (int)substr($date, 0, 4);
                        if ($year > 1900 && $year <= date('Y')) {
                            $years[] = $year;
                        }
                    }
                }

                if (!empty($years)) {
                    $startYear = min($years);
                }

                // Ước tính awards/nominations dựa trên số credits
                $totalCredits = count($allWorks);
                $awards = max(0, (int)($totalCredits * 0.02));
                $nominations = max(0, (int)($totalCredits * 0.05));
            }

            // Bước 4: Update person trong DB
            if ($avatar || $bio || $startYear) {
                $sql = "UPDATE persons SET avatar = :avatar, bio = :bio, awards = :awards, nominations = :nominations, start_year = :start_year, updated_at = NOW() WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':avatar' => $avatar,
                    ':bio' => $bio,
                    ':awards' => $awards,
                    ':nominations' => $nominations,
                    ':start_year' => $startYear,
                    ':id' => $personId
                ]);
            }
        } catch (Exception $e) {
            // Bỏ qua lỗi, không làm dừng crawler
        }
    }

    private function processEpisodes($movieId, $seasonId, $episodes)
    {
        echo "<span style='font-size:11px; color:#666;'>Server: ";
        foreach ($episodes as $server) {
            foreach ($server['server_data'] as $ep) {
                $epName = $ep['name'];
                $linkM3u8 = $ep['link_m3u8'];

                $sqlCheck = "SELECT id FROM episodes WHERE movie_id = :mid AND name = :name";
                $paramsCheck = [':mid' => $movieId, ':name' => $epName];
                if ($seasonId) {
                    $sqlCheck .= " AND season_id = :sid";
                    $paramsCheck[':sid'] = $seasonId;
                } else {
                    $sqlCheck .= " AND season_id IS NULL";
                }

                $stmt = $this->conn->prepare($sqlCheck);
                $stmt->execute($paramsCheck);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $epId = $row['id'];
                } else {
                    $this->conn->prepare("INSERT INTO episodes (movie_id, season_id, name, created_at, updated_at) VALUES (:mid, :sid, :name, NOW(), NOW())")
                        ->execute([':mid' => $movieId, ':sid' => $seasonId, ':name' => $epName]);
                    $epId = $this->conn->lastInsertId();
                    echo "Ep.{$epName} ";
                }

                $checkSrc = $this->conn->prepare("SELECT id FROM video_sources WHERE episode_id = :eid AND source_url = :link");
                $checkSrc->execute([':eid' => $epId, ':link' => $linkM3u8]);
                if (!$checkSrc->fetch()) {
                    $this->conn->prepare("INSERT INTO video_sources (episode_id, source_name, source_url, voice_type, created_at, updated_at) VALUES (:eid, :sname, :link, 'Thuyết minh', NOW(), NOW())")
                        ->execute([':eid' => $epId, ':sname' => $server['server_name'], ':link' => $linkM3u8]);
                    echo "<span style='color:green; font-weight:bold'>+</span> ";
                }
            }
        }
        echo "</span>";
    }
}
