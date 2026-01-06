<?php

/**
 * Movies Sitemap (Paginated)
 * Sinh động từ database, hỗ trợ phân trang cho scalability
 * 
 * URL: /sitemap-movies-{page}.xml
 */

require_once __DIR__ . '/../../configs/configs.php';
require_once __DIR__ . '/../../configs/database.php';
require_once __DIR__ . '/../../core/CoreModel.php';
require_once __DIR__ . '/../../app/Models/Movies.php';

header('Content-Type: application/xml; charset=utf-8');

// Get page number from URL
$page = 1;
if (preg_match('/sitemap-movies-(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $page = (int) $matches[1];
}

$baseUrl = _HOST_URL;
$perPage = 1000; // Google recommends max 50,000 URLs per sitemap
$offset = ($page - 1) * $perPage;

// Cache - mỗi domain có cache riêng
$cacheKey = md5($baseUrl);
$cacheFile = __DIR__ . '/cache/sitemap-movies-' . $page . '_' . $cacheKey . '.xml';
$cacheTime = 3600; // 1 hour

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

ob_start();

$moviesModel = new Movies();

// Get movies with pagination
$movies = $moviesModel->getAll("
    SELECT m.slug, m.updated_at, m.created_at 
    FROM movies m 
    WHERE m.status_id = 1 
    ORDER BY m.updated_at DESC 
    LIMIT $perPage OFFSET $offset
");

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    <?php foreach ($movies as $movie):
        $lastmod = !empty($movie['updated_at']) ? date('Y-m-d', strtotime($movie['updated_at'])) : date('Y-m-d');
    ?>
        <url>
            <loc><?php echo $baseUrl; ?>/phim/<?php echo htmlspecialchars($movie['slug']); ?></loc>
            <lastmod><?php echo $lastmod; ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        <!-- Watch page -->
        <url>
            <loc><?php echo $baseUrl; ?>/xem-phim/<?php echo htmlspecialchars($movie['slug']); ?></loc>
            <lastmod><?php echo $lastmod; ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    <?php endforeach; ?>
</urlset>
<?php
$output = ob_get_contents();
ob_end_clean();

// Cache
if (!is_dir(__DIR__ . '/cache')) {
    mkdir(__DIR__ . '/cache', 0755, true);
}
file_put_contents($cacheFile, $output);

echo $output;
?>