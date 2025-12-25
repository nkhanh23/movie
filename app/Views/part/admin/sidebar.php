<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}

// Lấy URL hiện tại để xác định tab active
$currentPath = $_SERVER['REQUEST_URI'];
$currentPath = parse_url($currentPath, PHP_URL_PATH);
// Loại bỏ project name (VD: /movie) để so sánh đúng
$projectName = '/movie';
$currentPath = str_replace($projectName, '', $currentPath);

// Hàm helper để check active
function isActive($path, $currentPath)
{
    // Exact match
    if ($currentPath === $path) {
        return 'active';
    }

    // Partial match for sub-pages (e.g., /admin/film/add should highlight /admin/film/list)
    if (strpos($currentPath, $path) === 0 && $path !== '/admin/dashboard') {
        return 'active';
    }

    return '';
}
?>

<aside class="sidebar">
    <div class="logo">
        <i class="fa-solid fa-film"></i> MovieAdmin
    </div>

    <div class="sidebar-nav">
        <div class="nav-divider">Tổng quan</div>
        <a href="<?php echo _HOST_URL; ?>/admin/dashboard" class="nav-item <?php echo isActive('/admin/dashboard', $currentPath); ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-divider">Quản lý Phim</div>
        <a href="<?php echo _HOST_URL; ?>/admin/film/list" class="nav-item <?php echo isActive('/admin/film', $currentPath); ?>">
            <i class="fa-solid fa-video"></i>
            <span>Danh sách Phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/season" class="nav-item <?php echo isActive('/admin/season', $currentPath); ?>">
            <i class="fa-solid fa-folder-tree"></i>
            <span>Mùa phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/episode" class="nav-item <?php echo isActive('/admin/episode', $currentPath); ?>">
            <i class="fa-solid fa-list-ol"></i>
            <span>Tập phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/source" class="nav-item <?php echo isActive('/admin/source', $currentPath); ?>">
            <i class="fa-solid fa-link"></i>
            <span>Nguồn phim</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/genres" class="nav-item <?php echo isActive('/admin/genres', $currentPath); ?>">
            <i class="fa-solid fa-tags"></i>
            <span>Thể loại</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/country" class="nav-item <?php echo isActive('/admin/country', $currentPath); ?>">
            <i class="fa-solid fa-earth-americas"></i>
            <span>Quốc gia</span>
        </a>

        <!-- NEW: SUPPORT SECTIONS -->
        <div class="nav-divider">Hỗ trợ & Phản hồi</div>
        <a href="<?php echo _HOST_URL; ?>/admin/support" class="nav-item <?php echo isActive('/admin/support', $currentPath); ?>">
            <i class="fa-solid fa-headset"></i>
            <span>Yêu cầu hỗ trợ</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/support_type" class="nav-item <?php echo isActive('/admin/support_type', $currentPath); ?>">
            <i class="fa-solid fa-folder-open"></i>
            <span>Loại hỗ trợ</span>
        </a>

        <div class="nav-divider">Nhân sự</div>
        <a href="<?php echo _HOST_URL; ?>/admin/person" class="nav-item <?php echo isActive('/admin/person', $currentPath); ?>">
            <i class="fa-solid fa-users"></i>
            <span>Diễn viên / Đạo diễn</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/role" class="nav-item <?php echo isActive('/admin/role', $currentPath); ?>">
            <i class="fa-solid fa-user-tag"></i>
            <span>Vai trò (Roles)</span>
        </a>

        <div class="nav-divider">Tương tác</div>
        <a href="<?php echo _HOST_URL; ?>/admin/comments" class="nav-item <?php echo isActive('/admin/comments', $currentPath); ?>">
            <i class="fa-solid fa-comments"></i>
            <span>Bình luận (Comments)</span>
        </a>

        <div class="nav-divider">Tool</div>
        <a href="<?php echo _HOST_URL; ?>/admin/crawler?page=1" class="nav-item <?php echo isActive('/admin/crawler', $currentPath); ?>">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Cập nhật phim mới</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/tool_update_avatar.php?page=1" class="nav-item <?php echo isActive('/tool_update_avatar.php', $currentPath); ?>">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Cập nhật avatar diễn viên</span>
        </a>

        <div class="nav-divider">Hệ thống</div>
        <a href="<?php echo _HOST_URL; ?>/admin/user" class="nav-item <?php echo isActive('/admin/user', $currentPath); ?>">
            <i class="fa-solid fa-users-gear"></i>
            <span>Người dùng</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/logs" class="nav-item <?php echo isActive('/admin/logs', $currentPath); ?>">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Nhật ký hoạt động</span>
        </a>
        <a href="<?php echo _HOST_URL; ?>/admin/settings?tab=general" class="nav-item <?php echo isActive('/admin/settings', $currentPath); ?>">
            <i class="fa-solid fa-gear"></i>
            <span>Cài đặt</span>
        </a>

        <a href="<?php echo _HOST_URL; ?>/admin/logout" class="nav-item">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Đăng xuất</span>
        </a>
    </div>
</aside>