<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
?>

<aside class="sidebar" id="sidebar">
    <nav class="sidebar-nav">
        <a href="#" class="nav-item active" data-target="dashboard">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Tổng quan</span>
        </a>

        <div class="nav-divider">Quản lý Nội dung</div>

        <a href="<?php echo _HOST_URL; ?>/admin/film/list" class="nav-item">
            <i class="fa-solid fa-film"></i>
            <span>Phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/episode" class="nav-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Tập Phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/season" class="nav-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Mùa phim</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-tags"></i>
            <span>Thể loại</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-comments"></i>
            <span>Bình luận</span>
        </a>

        <div class="nav-divider">Quản lý Tài khoản</div>

        <a href="#" class="nav-item">
            <i class="fa-solid fa-users"></i>
            <span>Người dùng</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-user-tie"></i>
            <span>Diễn viên/Đạo diễn</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-id-badge"></i>
            <span>Vai trò</span>
        </a>
    </nav>
</aside>