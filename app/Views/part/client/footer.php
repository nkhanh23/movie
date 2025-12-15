<!-- Footer -->
<footer class="bg-[#050608] pt-16 pb-8 border-t border-[#1F2833]">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">

            <div class="col-span-1 md:col-span-1">
                <a href="#" class="flex items-center gap-2 mb-4">
                    <div
                        class="w-8 h-8 rounded-full bg-[#1F2833] border border-[#66FCF1] flex items-center justify-center">
                        <i data-lucide="play" class="w-5 h-5 text-[#66FCF1] fill-current ml-1"></i>
                    </div>
                    <span class="text-xl font-bold text-white">Ro<span class="text-[#66FCF1]">Phim</span></span>
                </a>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Trải nghiệm xem phim đỉnh cao với chất lượng hình ảnh sắc nét, âm thanh sống động và kho phim
                    khổng lồ được cập nhật liên tục.
                </p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-4">Danh Mục</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-[#66FCF1]">Phim Mới</a></li>
                    <li><a href="#" class="hover:text-[#66FCF1]">Phim Chiếu Rạp</a></li>
                    <li><a href="#" class="hover:text-[#66FCF1]">Phim Bộ</a></li>
                    <li><a href="#" class="hover:text-[#66FCF1]">TV Shows</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-4">Hỗ Trợ</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-[#66FCF1]">Hỏi đáp / FAQ</a></li>
                    <li><a href="#" class="hover:text-[#66FCF1]">Liên hệ quảng cáo</a></li>
                    <li><a href="#" class="hover:text-[#66FCF1]">Chính sách bảo mật</a></li>
                    <li><a href="#" class="hover:text-[#66FCF1]">Điều khoản sử dụng</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-4">Kết Nối</h4>
                <div class="flex gap-4">
                    <a href="#"
                        class="w-10 h-10 rounded bg-[#1F2833] flex items-center justify-center text-gray-400 hover:text-[#66FCF1] hover:border hover:border-[#66FCF1] transition-all">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded bg-[#1F2833] flex items-center justify-center text-gray-400 hover:text-[#66FCF1] hover:border hover:border-[#66FCF1] transition-all">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded bg-[#1F2833] flex items-center justify-center text-gray-400 hover:text-[#66FCF1] hover:border hover:border-[#66FCF1] transition-all">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center pt-8 border-t border-[#1F2833] text-gray-600 text-sm">
            <p>© 2024 RoPhim. All rights reserved. Design inspired by Cyberpunk.</p>
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
    });
</script>
</body>

</html>