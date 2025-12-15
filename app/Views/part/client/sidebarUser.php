<?php
// Lấy URL hiện tại để xác định trang active
$current_page = $_SERVER['REQUEST_URI'];
$current_path = parse_url($current_page, PHP_URL_PATH);

// Xác định trang nào đang active
$is_gioi_thieu = strpos($current_path, '/gioi_thieu') !== false;
$is_lien_he = strpos($current_path, '/lien_he') !== false;
$is_yeu_thich = strpos($current_path, '/yeu_thich') !== false;
$is_tai_khoan = strpos($current_path, '/tai_khoan') !== false;
$is_thong_bao = strpos($current_path, '/thong_bao') !== false;
?>
<aside id="userSidebar" class="w-full lg:w-72 shrink-0 transition-all duration-300 relative">
    <div id="sidebarContent" class="flex h-full min-h-[700px] flex-col justify-between sidebar-glassmorphic rounded-2xl p-6 relative">

        <div class="flex flex-col gap-8 relative z-10">
            <div class="flex items-center gap-4 pb-6 border-b border-white/5 sidebar-user-info relative">
                <div class="relative sidebar-avatar">
                    <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/avatar/default-avatar.jpg" alt="User Avatar" class="w-14 h-14 rounded-full border-2 border-primary/30 shadow-[0_0_15px_rgba(217,108,22,0.3)]">
                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 rounded-full border-2 border-slate-900"></div>
                </div>
                <div class="sidebar-user-text flex-1">
                    <h3 class="text-white font-semibold text-sm"><?= !empty($_SESSION['user_client']['fullname']) ? $_SESSION['user_client']['fullname'] : 'Người dùng' ?></h3>
                    <p class="text-slate-400 text-xs">Thành viên VIP</p>
                </div>
                <!-- Toggle Button -->
                <button id="sidebarToggle" class="w-8 h-8 rounded-full bg-primary/20 border-2 border-primary/40 flex items-center justify-center hover:bg-primary/30 hover:scale-110 transition-all duration-300 shadow-[0_0_15px_rgba(217,108,22,0.3)] flex-shrink-0 sidebar-toggle-btn">
                    <span class="material-symbols-outlined text-primary text-sm sidebar-toggle-icon">chevron_left</span>
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <!-- Giới thiệu -->
                <a href="<?= _HOST_URL ?>/gioi_thieu" class="sidebar-menu-item group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?php echo $is_gioi_thieu ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110 <?php echo !$is_gioi_thieu ? 'group-hover:text-primary' : ''; ?> sidebar-icon">info</span>
                    <p class="text-sm font-medium sidebar-text">Giới thiệu</p>
                </a>

                <!-- Liên hệ -->
                <a href="<?= _HOST_URL ?>/lien_he" class="sidebar-menu-item group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?php echo $is_lien_he ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110 <?php echo !$is_lien_he ? 'group-hover:text-primary' : ''; ?> sidebar-icon">contact_support</span>
                    <p class="text-sm font-medium sidebar-text">Liên hệ</p>
                </a>

                <!-- Yêu thích -->
                <a href="<?= _HOST_URL ?>/yeu_thich" class="sidebar-menu-item group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?php echo $is_yeu_thich ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110 <?php echo !$is_yeu_thich ? 'group-hover:text-primary' : ''; ?> sidebar-icon" <?php echo $is_yeu_thich ? 'style="font-variation-settings: \'FILL\' 1;"' : ''; ?>>favorite</span>
                    <p class="text-sm font-medium sidebar-text">Yêu thích</p>
                </a>

                <!-- Tài khoản -->
                <a href="<?= _HOST_URL ?>/tai_khoan" class="sidebar-menu-item group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?php echo $is_tai_khoan ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110 <?php echo !$is_tai_khoan ? 'group-hover:text-primary' : ''; ?> sidebar-icon">person</span>
                    <p class="text-sm font-medium sidebar-text">Tài khoản</p>
                </a>

                <!-- Thông báo -->
                <a href="<?= _HOST_URL ?>/thong_bao" class="sidebar-menu-item group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 <?php echo $is_thong_bao ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-white/5'; ?>">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110 <?php echo !$is_thong_bao ? 'group-hover:text-primary' : ''; ?> sidebar-icon">notifications</span>
                    <p class="text-sm font-medium sidebar-text">Thông báo</p>
                </a>
            </div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('userSidebar');
        const sidebarContent = document.getElementById('sidebarContent');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = toggleBtn.querySelector('.sidebar-toggle-icon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const userText = document.querySelector('.sidebar-user-text');
        const userInfo = document.querySelector('.sidebar-user-info');

        let isCollapsed = false;

        toggleBtn.addEventListener('click', function() {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                // Thu gọn sidebar
                sidebar.classList.remove('lg:w-72');
                sidebar.classList.add('lg:w-20');

                // Adjust padding for alignment
                sidebarContent.classList.remove('p-6');
                sidebarContent.classList.add('py-6', 'px-2');

                // Ẩn background
                sidebarContent.classList.remove('sidebar-glassmorphic');
                sidebarContent.classList.add('bg-transparent', 'border-0');

                // Ẩn text
                sidebarTexts.forEach(text => {
                    text.style.display = 'none';
                });

                if (userText) {
                    userText.style.display = 'none';
                }

                if (userInfo) {
                    userInfo.style.justifyContent = 'center';
                    userInfo.style.borderBottom = 'none';

                    // Fix layout alignment - Button to the right of avatar
                    toggleBtn.style.position = 'absolute';
                    toggleBtn.style.left = 'auto'; // Reset left
                    toggleBtn.style.right = '-35px';
                    toggleBtn.style.top = '50%';
                    toggleBtn.style.transform = 'translateY(calc(-50% - 10px))';
                    toggleBtn.style.zIndex = '20';
                }

                // Xoay icon
                toggleIcon.textContent = 'chevron_right';

                // Center icons - Set fixed size to match avatar
                const menuContainer = document.querySelector('.flex.flex-col.gap-8');
                if (menuContainer) {
                    menuContainer.style.gap = '0.5rem';
                }

                document.querySelectorAll('.sidebar-menu-item').forEach(item => {
                    item.style.justifyContent = 'center';
                    item.style.padding = '0';
                    item.style.width = '56px';
                    item.style.height = '56px';
                    item.style.margin = '0 auto';
                    item.style.display = 'flex';
                    item.style.alignItems = 'center';
                    item.style.borderRadius = '50%';
                });
            } else {
                // Mở rộng sidebar
                sidebar.classList.remove('lg:w-20');
                sidebar.classList.add('lg:w-72');

                // Reset padding
                sidebarContent.classList.remove('py-6', 'px-2');
                sidebarContent.classList.add('p-6');

                // Hiện background
                sidebarContent.classList.add('sidebar-glassmorphic');
                sidebarContent.classList.remove('bg-transparent', 'border-0');

                // Hiện text
                sidebarTexts.forEach(text => {
                    text.style.display = 'block';
                });

                if (userText) {
                    userText.style.display = 'block';
                }

                if (userInfo) {
                    userInfo.style.justifyContent = 'flex-start';
                    userInfo.style.borderBottom = '1px solid rgba(255, 255, 255, 0.05)';

                    // Reset layout
                    toggleBtn.style.position = 'static';
                    toggleBtn.style.left = 'auto';
                    toggleBtn.style.top = 'auto';
                    toggleBtn.style.transform = 'none';
                    toggleBtn.style.zIndex = 'auto';
                }

                // Xoay icon
                toggleIcon.textContent = 'chevron_left';

                // Reset menu items
                const menuContainer = document.querySelector('.flex.flex-col.gap-8');
                if (menuContainer) {
                    menuContainer.style.gap = '2rem';
                }

                document.querySelectorAll('.sidebar-menu-item').forEach(item => {
                    item.style.justifyContent = 'flex-start';
                    item.style.padding = '0.75rem 1rem';
                    item.style.width = 'auto';
                    item.style.height = 'auto';
                    item.style.margin = '0';
                    item.style.borderRadius = '0.75rem';
                });

                // Hiện toggle button và remove hover listeners
                toggleBtn.style.opacity = '1';
                toggleBtn.style.pointerEvents = 'auto';
                sidebar.onmouseenter = null;
                sidebar.onmouseleave = null;
            }
        });

        // Thêm logic ẩn/hiện toggle button khi collapsed
        const originalClickHandler = toggleBtn.onclick;
        toggleBtn.addEventListener('click', function() {
            setTimeout(() => {
                if (isCollapsed) {
                    // Ẩn toggle button
                    toggleBtn.style.opacity = '0';
                    toggleBtn.style.pointerEvents = 'none';
                    toggleBtn.style.transition = 'opacity 0.3s ease';

                    // Hiện khi hover
                    sidebar.onmouseenter = () => {
                        toggleBtn.style.opacity = '1';
                        toggleBtn.style.pointerEvents = 'auto';
                    };
                    sidebar.onmouseleave = () => {
                        toggleBtn.style.opacity = '0';
                        toggleBtn.style.pointerEvents = 'none';
                    };
                }
            }, 50);
        });
    });
</script>