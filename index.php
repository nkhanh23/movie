<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
ob_start(); // tranh loi tu cac ham header, cooke

foreach (glob(__DIR__ . '/configs/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/core/*php') as $filename) {
    require_once $filename;
}

$router = new Router();

foreach (glob(__DIR__ . '/router/*php') as $filename) {
    require_once $filename;
}


foreach (glob(__DIR__ . '/app/Controller/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controller/admin/*php') as $filename) {
    require_once $filename;
}

foreach (glob(__DIR__ . '/app/Controller/clients/*php') as $filename) {
    require_once $filename;
}


$method = $_SERVER['REQUEST_METHOD'];
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->handlePath($method, $url);
