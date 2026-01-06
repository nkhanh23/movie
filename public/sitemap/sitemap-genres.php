<?php

/**
 * Genres Sitemap
 * Sinh động từ database
 */

require_once __DIR__ . '/../../configs/configs.php';
require_once __DIR__ . '/../../configs/database.php';
require_once __DIR__ . '/../../core/CoreModel.php';
require_once __DIR__ . '/../../app/Models/Genres.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = _HOST_URL;

// Cache - mỗi domain có cache riêng
$cacheKey = md5($baseUrl);
$cacheFile = __DIR__ . '/cache/sitemap-genres_' . $cacheKey . '.xml';
$cacheTime = 86400; // 24 hours

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

ob_start();

$genresModel = new Genres();
$genres = $genresModel->getAllGenres();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($genres as $genre): ?>
        <url>
            <loc><?php echo $baseUrl; ?>/the-loai/<?php echo htmlspecialchars($genre['slug']); ?></loc>
            <lastmod><?php echo date('Y-m-d'); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    <?php endforeach; ?>
</urlset>
<?php
$output = ob_get_contents();
ob_end_clean();

if (!is_dir(__DIR__ . '/cache')) {
    mkdir(__DIR__ . '/cache', 0755, true);
}
file_put_contents($cacheFile, $output);

echo $output;
?>