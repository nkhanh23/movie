<?php

/**
 * Dynamic robots.txt
 * Tự động dùng domain từ _HOST_URL
 */

require_once __DIR__ . '/configs/configs.php';

header('Content-Type: text/plain; charset=utf-8');
?>
# Robots.txt for Movie Website
# Auto-generated with dynamic domain

User-agent: *
Allow: /

# Disallow admin and private areas
Disallow: /admin/
Disallow: /api/
Disallow: /tai-khoan/
Disallow: /yeu-thich/
Disallow: /xem-tiep/
Disallow: /thong-bao/
Disallow: /login
Disallow: /register
Disallow: /logout

# Disallow cache and system folders
Disallow: /public/cache/
Disallow: /public/sitemap/
Disallow: /core/
Disallow: /app/
Disallow: /configs/

# Sitemap location (dynamic)
Sitemap: <?php echo _HOST_URL; ?>/sitemap.xml