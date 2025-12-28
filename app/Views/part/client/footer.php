<!-- Footer -->
<footer class="bg-background-dark pt-10 md:pt-16 pb-6 md:pb-8 border-t border-white/10">
    <div class="max-w-[1600px] mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mb-8 md:mb-12">

            <div class="col-span-2 md:col-span-1">
                <a href="<?php echo _HOST_URL; ?>" class="flex items-center gap-2 mb-3 md:mb-4">
                    <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo/PhePhim.png" alt="Phê Phim" class="h-10 md:h-12 w-auto">
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">
                    <?php $siteSettings = getSiteSettings();
                    echo htmlspecialchars($siteSettings['site_description']); ?>
                </p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">Danh Mục</h4>
                <ul class="space-y-1.5 md:space-y-2 text-xs md:text-sm text-gray-400">
                    <li><a href="<?php echo _HOST_URL; ?>" class="hover:text-primary transition-colors">Trang chủ</a></li>
                    <li><a href="<?php echo _HOST_URL; ?>/phim_le" class="hover:text-primary transition-colors">Phim lẻ</a></li>
                    <li><a href="<?php echo _HOST_URL; ?>/phim_bo" class="hover:text-primary transition-colors">Phim bộ</a></li>
                    <li><a href="<?php echo _HOST_URL; ?>/phim_chieu_rap" class="hover:text-primary transition-colors">Phim chiếu rạp</a></li>
                    <li><a href="<?php echo _HOST_URL; ?>/dien_vien" class="hover:text-primary transition-colors">Diễn viên</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">Hỗ Trợ</h4>
                <ul class="space-y-1.5 md:space-y-2 text-xs md:text-sm text-gray-400">
                    <li><a href="#" class="hover:text-primary transition-colors">Hỏi đáp / FAQ</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Liên hệ quảng cáo</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Chính sách bảo mật</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Điều khoản sử dụng</a></li>
                </ul>
            </div>

            <div class="col-span-2 md:col-span-1">
                <h4 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">Kết Nối</h4>
                <div class="flex gap-3 md:gap-4">
                    <a href="<?php echo $siteSettings['site_facebook']; ?>"
                        class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/20 hover:border-primary/50 transition-all duration-300">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="<?php echo $siteSettings['site_instagram']; ?>"
                        class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/20 hover:border-primary/50 transition-all duration-300">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="<?php echo $siteSettings['site_instagram']; ?>"
                        class="w-9 h-9 md:w-10 md:h-10 rounded-lg bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/20 hover:border-primary/50 transition-all duration-300">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center pt-6 md:pt-8 border-t border-white/10 text-gray-500 text-xs md:text-sm">
            <p>© <?php echo date('Y'); ?> <span class="text-primary font-semibold"><?php echo htmlspecialchars($siteSettings['site_name']); ?></span>. All rights reserved.</p>
        </div>
    </div>
</footer>
<!-- Initialise Lucide Icons -->
<script>
    lucide.createIcons();
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script type="module" src="<?php echo _HOST_URL_PUBLIC; ?>/assets/js/client/script.js?v=<?php echo time(); ?>"></script>

<!-- Hàm xử lý chung cho chức năng Yêu thích (Favorite) -->
<script>
    function toggleFavorite(movieId, element) {
        // Kiểm tra trạng thái đăng nhập
        const userId = '<?php echo $_SESSION['auth']['id'] ?? 0; ?>';
        if (userId == 0) {
            alert('Vui lòng đăng nhập để thực hiện chức năng này.');
            window.location.href = '<?php echo _HOST_URL; ?>/login';
            return;
        }

        // Chặn spam click và thay đổi trạng thái UI
        if (element.classList.contains('is-processing')) return;
        element.classList.add('is-processing');

        // Chuẩn bị dữ liệu
        const formData = new FormData();
        formData.append('movie_id', movieId);

        // Gửi AJAX request 
        fetch('<?php echo _HOST_URL; ?>/api/toggle-favorite', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Tìm icon bên trong button - hỗ trợ cả SVG và Material Icons
                    const svgIcon = element.querySelector('svg');
                    const materialIcon = element.querySelector('.material-symbols-outlined');

                    // Cập nhật trạng thái nút
                    if (data.action === 'added') {
                        element.classList.add('is-favorited');

                        // Cập nhật SVG fill và stroke trực tiếp
                        if (svgIcon) {
                            svgIcon.style.fill = '#ef4444';
                            svgIcon.style.color = '#ef4444';
                            svgIcon.style.stroke = '#ef4444';
                        }

                        // Cập nhật Material Icon
                        if (materialIcon) {
                            materialIcon.style.color = '#ef4444';
                            materialIcon.style.fontVariationSettings = '"FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24';
                        }

                        // Hiển thị toast thành công
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            background: 'rgba(26, 26, 26, 0.95)',
                            color: '#fff',
                            iconColor: '#ef4444',
                            customClass: {
                                popup: 'colored-toast'
                            }
                        });
                    } else if (data.action === 'removed') {
                        element.classList.remove('is-favorited');

                        // Reset SVG về màu mặc định
                        if (svgIcon) {
                            svgIcon.style.fill = '';
                            svgIcon.style.color = '';
                            svgIcon.style.stroke = '';
                        }

                        // Reset Material Icon về màu mặc định
                        if (materialIcon) {
                            materialIcon.style.color = '';
                            materialIcon.style.fontVariationSettings = '"FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24';
                        }

                        // Hiển thị toast xóa
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            background: 'rgba(26, 26, 26, 0.95)',
                            color: '#fff',
                            iconColor: '#6b7280',
                            customClass: {
                                popup: 'colored-toast'
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: 'rgba(26, 26, 26, 0.95)',
                        color: '#fff',
                        iconColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error toggling favorite:', error);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Có lỗi xảy ra khi kết nối. Vui lòng thử lại.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: 'rgba(26, 26, 26, 0.95)',
                    color: '#fff',
                    iconColor: '#ef4444'
                });
            })
            .finally(() => {
                element.classList.remove('is-processing');
            });
    }

    // Gán sự kiện cho các nút có class 'js-favorite-btn'
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-favorite-btn');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                const movieId = btn.getAttribute('data-movie-id');
                if (movieId) {
                    toggleFavorite(movieId, btn);
                }
            }
        });

        // Xử lý nút yêu thích diễn viên
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-favorite-actor-btn');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                const personId = btn.getAttribute('data-person-id');
                if (personId) {
                    toggleFavoriteActor(personId, btn);
                }
            }
        });
    });

    // Hàm xử lý toggle favorite cho diễn viên
    function toggleFavoriteActor(personId, element) {
        // Kiểm tra trạng thái đăng nhập
        const userId = '<?php echo $_SESSION['auth']['id'] ?? 0; ?>';
        if (userId == 0) {
            alert('Vui lòng đăng nhập để thực hiện chức năng này.');
            window.location.href = '<?php echo _HOST_URL; ?>/login';
            return;
        }

        // Chặn spam click
        if (element.classList.contains('is-processing')) return;
        element.classList.add('is-processing');

        // Chuẩn bị dữ liệu
        const formData = new FormData();
        formData.append('actor_id', personId);

        // Gửi AJAX request
        fetch('<?php echo _HOST_URL; ?>/api/toggle-favorite-actor', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const materialIcon = element.querySelector('.material-symbols-outlined');
                    const favText = element.querySelector('.fav-text');

                    if (data.action === 'added') {
                        element.classList.add('is-favorited');

                        if (materialIcon) {
                            materialIcon.style.color = '#ef4444';
                            materialIcon.style.fontVariationSettings = '"FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24';
                        }

                        if (favText) {
                            favText.textContent = 'Đã yêu thích';
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            background: 'rgba(26, 26, 26, 0.95)',
                            color: '#fff',
                            iconColor: '#ef4444',
                            customClass: {
                                popup: 'colored-toast'
                            }
                        });
                    } else if (data.action === 'removed') {
                        element.classList.remove('is-favorited');

                        if (materialIcon) {
                            materialIcon.style.color = '';
                            materialIcon.style.fontVariationSettings = '"FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24';
                        }

                        if (favText) {
                            favText.textContent = 'Yêu thích';
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            background: 'rgba(26, 26, 26, 0.95)',
                            color: '#fff',
                            iconColor: '#6b7280',
                            customClass: {
                                popup: 'colored-toast'
                            }
                        });
                    }
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: 'rgba(26, 26, 26, 0.95)',
                        color: '#fff',
                        iconColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error toggling favorite actor:', error);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Có lỗi xảy ra khi kết nối. Vui lòng thử lại.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: 'rgba(26, 26, 26, 0.95)',
                    color: '#fff',
                    iconColor: '#ef4444'
                });
            })
            .finally(() => {
                element.classList.remove('is-processing');
            });
    }
</script>
</body>

</html>