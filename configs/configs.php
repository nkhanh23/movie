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
