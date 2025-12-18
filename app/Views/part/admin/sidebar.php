<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
?>

<aside class="sidebar">
    <div class="logo">
        <i class="fa-solid fa-film"></i> MovieAdmin
    </div>

    <div class="sidebar-nav">
        <div class="nav-divider">Tổng quan</div>
        <a href="<?php echo _HOST_URL; ?>/admin/dashboard" class="nav-item active">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-divider">Quản lý Phim</div>
        <a href="<?php echo _HOST_URL; ?>/admin/film/list" class="nav-item">
            <i class="fa-solid fa-video"></i>
            <span>Danh sách Phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/season" class="nav-item">
            <i class="fa-solid fa-list-ol"></i>
            <span>Tập phim (Episodes)</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/episode" class="nav-item">
            <i class="fa-solid fa-list-ol"></i>
            <span>Tập phim (Episodes)</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/genres" class="nav-item">
            <i class="fa-solid fa-tags"></i>
            <span>Thể loại (Genres)</span>
        </a>

        <div class="nav-divider">Nhân sự</div>
        <a href="<?php echo _HOST_URL; ?>/admin/person" class="nav-item">
            <i class="fa-solid fa-users"></i>
            <span>Diễn viên / Đạo diễn</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/role" class="nav-item">
            <i class="fa-solid fa-user-tag"></i>
            <span>Vai trò (Roles)</span>
        </a>

        <div class="nav-divider">Tương tác</div>
        <a href="<?php echo _HOST_URL; ?>/admin/comments" class="nav-item">
            <i class="fa-solid fa-comments"></i>
            <span>Bình luận (Comments)</span>
        </a>

        <div class="nav-divider">Tool</div>
        <a href="<?php echo _HOST_URL; ?>/admin/crawler?page=1" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Cập nhật phim mới</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/tool_update_avatar.php?page=1" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Cập nhật avatar diễn viên</span>
        </a>

        <div class="nav-divider">Hệ thống</div>
        <a href="<?php echo _HOST_URL; ?>/admin/user" class="nav-item">
            <i class="fa-solid fa-users-gear"></i>
            <span>Người dùng</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/logs" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Nhật ký hoạt động</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/settings" class="nav-item">
            <i class="fa-solid fa-gear"></i>
            <span>Cài đặt</span>
        </a>

        <a href="<?php echo _HOST_URL; ?>/admin/logout" class="nav-item">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Đăng xuất</span>
        </a>
    </div>
</aside>