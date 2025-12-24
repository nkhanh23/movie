<?php
$is_logged = isLogin();
if (!$is_logged) {
    reload('/login', true);
}

// Lấy thông tin user từ session
$userAvatar = !empty($_SESSION['auth']['avartar']) ? $_SESSION['auth']['avartar'] : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['auth']['fullname']) . '&background=3b82f6&color=fff';
$userName = $_SESSION['auth']['fullname'] ?? 'Admin';

// Lấy settings từ database
$siteSettings = getSiteSettings();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($siteSettings['site_name']); ?> - Admin</title>
    <!-- Font Awesome cho Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <?php
    $faviconPath = !empty($siteSettings['site_favicon']) ? _HOST_URL . '/' . $siteSettings['site_favicon'] : '';
    ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo htmlspecialchars($faviconPath); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo htmlspecialchars($faviconPath); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo htmlspecialchars($faviconPath); ?>">

    <!-- Bootstrap 4 (dùng cho container, row, justify-content-center, col-12, py-5, ... ) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- CSS Tùy chỉnh -->
    <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/admin/style.css">
    <!-- Thư viện HLS.js -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>

<body>
    <div class="app-container">
        <!-- HEADER -->
        <header class="header">
            <div class="header-left">
                <button id="sidebar-toggle" class="btn-icon"><i class="fa-solid fa-bars"></i></button>
                <h1 class="logo"><?php echo htmlspecialchars($siteSettings['site_name']); ?></h1>
            </div>
            <div class="header-right">
                <button class="btn-icon"><i class="fa-solid fa-bell"></i></button>
                <div class="user-profile-dropdown">
                    <div class="user-profile" id="user-profile-toggle">
                        <img src="<?php echo $userAvatar; ?>" alt="<?php echo htmlspecialchars($userName); ?>">
                        <span><?php echo htmlspecialchars($userName); ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="dropdown-menu-user" id="user-dropdown-menu">
                        <a href="<?php echo _HOST_URL; ?>/profile" class="dropdown-item">
                            <i class="fa-solid fa-user"></i> Thông tin cá nhân
                        </a>
                        <a href="<?php echo _HOST_URL; ?>/chinh_sua" class="dropdown-item">
                            <i class="fa-solid fa-gear"></i> Cài đặt
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo _HOST_URL; ?>/logout" class="dropdown-item logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </header>