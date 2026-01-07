<?php
class Database
{
    private static $connect;
    public static function connectPDO()
    {
        $maxRetries = 3;
        $retryDelay = 500000; // 0.5 giây (microseconds)

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $OPTION = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 30,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::MYSQL_ATTR_SSL_CA => true
                );

                $port = defined('_PORT') ? _PORT : '3306';
                $dsn = _DRIVER . ':host=' . _HOST . ';port=' . $port . ';dbname=' . _DB;
                self::$connect = new PDO($dsn, _USER, _PASS, $OPTION);

                // Kết nối thành công
                return self::$connect;
            } catch (Exception $ex) {
                // Nếu là lần thử cuối cùng, báo lỗi
                if ($attempt === $maxRetries) {
                    echo 'Lỗi không kết nối được tới database sau ' . $maxRetries . ' lần thử. Vui lòng tải lại trang';
                    die();
                }

                // Chờ trước khi thử lại
                usleep($retryDelay);
            }
        }
    }
}
