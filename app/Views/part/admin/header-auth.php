<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 4 (dùng cho container, row, justify-content-center, col-12, py-5, ... ) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Unicons (dùng cho các icon uil uil-at, uil-lock-alt, ...) -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC ?>/assets/css/login.css?ver=<?php rand(); ?>">
    <title><?php echo $data['tittle']; ?></title>
</head>

<body>