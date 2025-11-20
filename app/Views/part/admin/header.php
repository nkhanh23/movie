<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Admin Dashboard</title>
    <!-- Font Awesome cho Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 4 (dùng cho container, row, justify-content-center, col-12, py-5, ... ) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- CSS Tùy chỉnh -->
    <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/assets/css/admin/style.css">
    <script type="importmap">
    </script>
</head>

<body>
    <div class="app-container">
        <!-- HEADER -->
        <header class="header">
            <div class="header-left">
                <button id="sidebar-toggle" class="btn-icon"><i class="fa-solid fa-bars"></i></button>
                <h1 class="logo">VideoCMS</h1>
            </div>
            <div class="header-right">
                <button class="btn-icon"><i class="fa-solid fa-bell"></i></button>
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=3b82f6&color=fff" alt="Admin">
                    <span>Admin</span>
                </div>
            </div>
        </header>