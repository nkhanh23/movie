<?php

/**
 * Configuration Example File
 * 
 * Copy file này thành configs.php và điền các giá trị thật của bạn.
 * KHÔNG BAO GIỜ commit file configs.php lên Git!
 */

// Database Configuration
const _HOST = 'localhost';
const _DB = 'your_database_name';
const _USER = 'your_username';
const _PASS = 'your_password';
const _DRIVER = 'mysql';

define('_nkhanhh', true);

// Debug mode (set to false in production)
const _DEBUG = true;

// Host URL Configuration
define('_HOST_URL', 'http://your-domain.com');
define('_HOST_URL_PUBLIC', _HOST_URL . '/public');

define('_DIR_ROOT', __DIR__);

// GOOGLE API CONFIG
// Get your credentials at: https://console.cloud.google.com/apis/credentials
const _GOOGLE_CLIENT_ID = 'your_google_client_id.apps.googleusercontent.com';
const _GOOGLE_CLIENT_SECRET = 'your_google_client_secret';
const _GOOGLE_REDIRECT_URL = _HOST_URL . '/auth/google/callback';
