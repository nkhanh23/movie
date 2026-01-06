<?php

/**
 * Persons (Actors/Directors) Sitemap
 * Sinh động từ database
 */

require_once __DIR__ . '/../../configs/configs.php';
require_once __DIR__ . '/../../configs/database.php';
require_once __DIR__ . '/../../core/CoreModel.php';
require_once __DIR__ . '/../../app/Models/Person.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = _HOST_URL;

// Cache - mỗi domain có cache riêng
$cacheKey = md5($baseUrl);
$cacheFile = __DIR__ . '/cache/sitemap-persons_' . $cacheKey . '.xml';
$cacheTime = 86400; // 24 hours

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

ob_start();

$personModel = new Person();
// Get all persons with slug
$persons = $personModel->getAllPerson("SELECT id, slug, updated_at FROM persons WHERE slug IS NOT NULL AND slug != '' ORDER BY id DESC LIMIT 10000");

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($persons as $person):
        $lastmod = !empty($person['updated_at']) ? date('Y-m-d', strtotime($person['updated_at'])) : date('Y-m-d');
    ?>
        <url>
            <loc><?php echo $baseUrl; ?>/dien-vien/<?php echo htmlspecialchars($person['slug']); ?></loc>
            <lastmod><?php echo $lastmod; ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.5</priority>
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