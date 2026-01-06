<?php

/**
 * Main Pages Sitemap
 * Chứa các trang tĩnh chính của website
 */

require_once __DIR__ . '/../../configs/configs.php';

header('Content-Type: application/xml; charset=utf-8');

$baseUrl = _HOST_URL;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Trang chủ -->
    <url>
        <loc><?php echo $baseUrl; ?>/</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Phim lẻ -->
    <url>
        <loc><?php echo $baseUrl; ?>/phim-le</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Phim bộ -->
    <url>
        <loc><?php echo $baseUrl; ?>/phim-bo</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Phim chiếu rạp -->
    <url>
        <loc><?php echo $baseUrl; ?>/phim-chieu-rap</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Diễn viên -->
    <url>
        <loc><?php echo $baseUrl; ?>/dien-vien</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Tìm kiếm -->
    <url>
        <loc><?php echo $baseUrl; ?>/tim-kiem</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <!-- Giới thiệu -->
    <url>
        <loc><?php echo $baseUrl; ?>/gioi-thieu</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>

    <!-- Liên hệ -->
    <url>
        <loc><?php echo $baseUrl; ?>/lien-he</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
</urlset>