<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start(); // tranh loi tu cac ham header, cooke

// Load Composer libraries
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

foreach (glob(__DIR__ . '/configs/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/core/*php') as $filename) {
    require_once $filename;
}

require_once './core/mailer/Exception.php';
require_once './core/mailer/PHPMailer.php';
require_once './core/mailer/SMTP.php';

$router = new Router();

foreach (glob(__DIR__ . '/router/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Models/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/admin/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controllers/clients/*php') as $filename) {
    require_once $filename;
}

$projectName = '/movie';
$method = $_SERVER['REQUEST_METHOD'];
$url = str_replace($projectName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$router->handlePath($method, $url);
