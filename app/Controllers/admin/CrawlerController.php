<?php
class CrawlerController extends baseController
{
    private $conn;
    private $apiBase = "https://phimapi.com";

    public function __construct()
    {
        $this->conn = Database::connectPDO();
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

    // API endpoint để crawl qua AJAX
    public function syncApi()
    {
        header('Content-Type: application/json');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Bắt đầu buffer để capture output
        ob_start();
        $hasMore = $this->syncPage($page);
        $output = ob_get_clean();

        // Trả về JSON
        echo json_encode([
            'success' => true,
            'page' => $page,
            'hasMore' => $hasMore,
            'output' => $output
        ]);
        exit;
    }

    private function syncPage($page)
    {
        $url = $this->apiBase . "/danh-sach/phim-moi-cap-nhat?page=" . $page;
        $data = $this->fetchData($url);

        if (!$data || empty($data['items'])) {
            echo "<p style='color:red; font-weight:bold;'>Đã hết phim hoặc Lỗi API. Dừng lại.</p>";
            return false; //Dừng
        }

        foreach ($data['items'] as $item) {
            $this->processMovie($item['slug']);
        }
        return true; // Tiếp tục
    }

    private function fetchData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    private function processMovie($apiSlug)
    {
        $url = $this->apiBase . "/phim/" . $apiSlug;
        $data = $this->fetchData($url);
        if (!isset($data['movie'])) return;
        $m = $data['movie'];

        $parsedInfo = $this->parseMovieName($m['name']);
        $baseName = $parsedInfo['baseName'];
        $seasonName = $parsedInfo['seasonName'];
        $baseSlug = $this->createSlug($baseName);

        echo "<div style='margin-bottom:5px; border-bottom:1px dashed #ccc; padding-bottom:5px;'>";
        echo "<strong>{$m['name']}</strong> ";

        // BƯỚC 1: MOVIES
        $countryId = $this->getCountryId($m['country']);
        $typeId    = $this->getTypeId($m['type']);
        $status_id = ($m['status'] == 'completed') ? 1 : 2;
        $duration = (int)filter_var($m['time'], FILTER_SANITIZE_NUMBER_INT);

        $stmt = $this->conn->prepare("SELECT id FROM movies WHERE slug = :slug");
        $stmt->execute([':slug' => $baseSlug]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        $movieId = 0;
        if ($exists) {
            $movieId = $exists['id'];
            $sql = "UPDATE movies SET original_tittle=:orig, description=:desc, poster_url=:poster, thumbnail=:thumb, release_year=:year, status_id=:st, country_id=:ct, type_id=:tp, updated_at=NOW() WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':orig' => $m['origin_name'], ':desc' => $m['content'], ':poster' => $m['poster_url'], ':thumb' => $m['thumb_url'], ':year' => $m['year'], ':st' => $status_id, ':ct' => $countryId, ':tp' => $typeId, ':id' => $movieId]);
            echo "<span style='color:blue'>[UPDATE]</span>";
        } else {
            $sql = "INSERT INTO movies (tittle, original_tittle, slug, description, poster_url, thumbnail, release_year, duration, is_api, status_id, country_id, type_id, created_at) VALUES (:name, :orig, :slug, :desc, :poster, :thumb, :year, :dur, 1, :st, :ct, :tp, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':name' => $baseName, ':orig' => $m['origin_name'], ':slug' => $baseSlug, ':desc' => $m['content'], ':poster' => $m['poster_url'], ':thumb' => $m['thumb_url'], ':year' => $m['year'], ':dur' => $duration, ':st' => $status_id, ':ct' => $countryId, ':tp' => $typeId]);
            $movieId = $this->conn->lastInsertId();
            echo "<span style='color:green'>[NEW]</span>";
        }

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
                $stmtInS = $this->conn->prepare("INSERT INTO seasons (movie_id, name, description, poster_url, status_id, created_at) VALUES (:mid, :name, :desc, :poster, :st, NOW())");
                $stmtInS->execute([':mid' => $movieId, ':name' => $seasonName, ':desc' => $m['content'], ':poster' => $m['poster_url'], ':st' => $status_id]);
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
            } else {
                try {
                    $this->conn->prepare("INSERT INTO persons (name, slug, created_at) VALUES (?, ?, NOW())")->execute([$name, $personSlug]);
                    $personId = $this->conn->lastInsertId();
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
