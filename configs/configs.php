<?php
//Khai báo database
const _HOST = 'localhost';
const _DB = 'movie';
const _USER = 'root';
const _PASS = '';
const _DRIVER = 'mysql';

define('_nkhanhh', true);

//debug error
const _DEBUG = true;

//thiet lap duong dan host
define('_HOST_URL', 'http://' . ($_SERVER['HTTP_HOST']) . '/movie');
define('_HOST_URL_PUBLIC', _HOST_URL . '/public');

define('_DIR_ROOT', __DIR__);

// GOOGLE API CONFIG
const _GOOGLE_CLIENT_ID = '65158818594-a57gib4sgnorpjihh8g920ijh9i5hfrc.apps.googleusercontent.com';
const _GOOGLE_CLIENT_SECRET = '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';
const _GOOGLE_REDIRECT_URL = _HOST_URL . '/auth/google/callback';
