<?php
// Tắt báo lỗi PHP để tránh rác vào file JSON
error_reporting(0);

// Ép kiểu dữ liệu trả về là JSON chuẩn cho PWA
header('Content-Type: application/manifest+json; charset=utf-8');

// Header chống cache để cập nhật icon nhanh hơn khi sửa
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
{
"name": "PHEPHIM",
"short_name": "PHEPHIM",
"start_url": "/",
"display": "standalone",
"background_color": "#1a1a1a",
"theme_color": "#FFD875",
"orientation": "portrait-primary",
"icons": [
{
"src": "/public/img/logo/android-chrome-192x192.png",
"type": "image/png",
"sizes": "192x192"
},
{
"src": "/public/img/logo/android-chrome-512x512.png",
"type": "image/png",
"sizes": "512x512"
}
]
}