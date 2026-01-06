<?php

/**
 * Dynamic Sitemap Generator
 * Chuẩn SEO - Scalable - Tự động cập nhật từ database
 * 
 * Truy cập: /sitemap.xml hoặc /sitemap.php
 */

// Load configs
require_once __DIR__ . '/../../configs/configs.php';
require_once __DIR__ . '/../../configs/database.php';
require_once __DIR__ . '/../../core/CoreModel.php';
require_once __DIR__ . '/../../app/Models/Movies.php';
require_once __DIR__ . '/../../app/Models/Genres.php';
require_once __DIR__ . '/../../app/Models/Person.php';

// Set content type to XML
header('Content-Type: application/xml; charset=utf-8');

// Base URL - phải define trước khi tạo cache key
$baseUrl = _HOST_URL;

// Cache sitemap for 1 hour - mỗi domain có cache riêng
$cacheKey = md5($baseUrl);
$cacheFile = __DIR__ . '/cache/sitemap_index_' . $cacheKey . '.xml';
$cacheTime = 3600; // 1 hour

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

// Generate sitemap index
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Main pages sitemap -->
    <sitemap>
        <loc><?php echo $baseUrl; ?>/sitemap-main.xml</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    </sitemap>

    <!-- Movies sitemap (paginated) -->
    <?php
    $moviesModel = new Movies();
    $totalMovies = $moviesModel->getTotalMovies();
    $moviesPerSitemap = 1000;
    $totalMovieSitemaps = ceil($totalMovies / $moviesPerSitemap);

    for ($i = 1; $i <= $totalMovieSitemaps; $i++):
    ?>
        <sitemap>
            <loc><?php echo $baseUrl; ?>/sitemap-movies-<?php echo $i; ?>.xml</loc>
            <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        </sitemap>
    <?php endfor; ?>

    <!-- Genres sitemap -->
    <sitemap>
        <loc><?php echo $baseUrl; ?>/sitemap-genres.xml</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    </sitemap>

    <!-- Countries sitemap -->
    <sitemap>
        <loc><?php echo $baseUrl; ?>/sitemap-countries.xml</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    </sitemap>

    <!-- Persons (actors/directors) sitemap -->
    <sitemap>
        <loc><?php echo $baseUrl; ?>/sitemap-persons.xml</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    </sitemap>
</sitemapindex>
<?php
// Cache the output
$output = ob_get_contents();
if (!is_dir(__DIR__ . '/cache')) {
    mkdir(__DIR__ . '/cache', 0755, true);
}
file_put_contents($cacheFile, $output);
?>