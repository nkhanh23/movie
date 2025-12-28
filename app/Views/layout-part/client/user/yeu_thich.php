<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
?>

<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-hidden" style="background-color: #050505;">
    <!-- Background Effects -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-primary/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[10%] right-[-5%] w-[600px] h-[600px] bg-secondary/10 rounded-full blur-[100px] animate-pulse delay-1000"></div>
        <div class="absolute top-[40%] left-[30%] w-[400px] h-[400px] bg-highlight/10 rounded-full blur-[80px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-150 contrast-150 mix-blend-overlay"></div>
    </div>

    <div class="layout-container relative z-10 flex h-full grow flex-col pt-20 lg:pt-24">
        <div class="flex flex-1 justify-center py-5 px-3 md:px-10 lg:px-40">
            <div class="flex w-full max-w-7xl flex-col gap-6 lg:flex-row lg:gap-8">
                <!-- SIDE BAR -->
                <?php layout('client/sidebarUser'); ?>
                <!-- END SIDE BAR -->

                <main class="flex-1 layout-content-container flex flex-col gap-6">
                    <!-- Page Header -->
                    <div class="px-2 py-2 flex items-end justify-between">
                        <div class="flex flex-col gap-2">
                            <h2 class="text-white text-2xl lg:text-3xl font-bold tracking-tight drop-shadow-lg flex items-center gap-2 lg:gap-3">
                                <span class="material-symbols-outlined text-primary text-3xl lg:text-4xl animate-pulse" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                Phim Yêu Thích
                            </h2>
                            <p class="text-slate-400 text-sm">Bộ sưu tập những bộ phim bạn yêu thích nhất.</p>
                        </div>
                        <div class="hidden md:flex gap-2">
                            <div class="bg-white/5 border border-white/10 rounded-lg px-3 py-1 flex items-center gap-2 text-slate-400 text-sm cursor-pointer hover:bg-white/10 transition">
                                <span>Sắp xếp:</span>
                                <span class="text-white">Gần đây</span>
                                <span class="material-symbols-outlined text-sm">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <!-- Movies Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6" id="favorite-movies-grid">
                        <?php foreach ($favoriteMovies as $movie) : ?>
                            <div class="favorite-movie-card" id="movie-card-<?php echo $movie['id']; ?>">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-transparent z-10 opacity-80"></div>
                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" style="background-image: url('<?= $movie['poster_url']; ?>');"></div>
                                <div class="absolute top-2 right-2 z-20">
                                    <button
                                        class="p-1.5 rounded-full bg-white/10 hover:bg-red-500/80 transition-all duration-300 shadow-md is-favorite-remove-btn"
                                        onclick="removeFavorite(<?php echo $movie['id']; ?>, this)"
                                        data-movie-id="<?php echo $movie['id']; ?>"
                                        title="Xóa khỏi danh sách">
                                        <span class="material-symbols-outlined text-red-500 text-[18px]" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Load More Button -->
                    <div class="flex justify-center mt-6">
                        <button class="flex items-center gap-2 px-6 py-3 rounded-full bg-white/5 border border-white/10 text-white text-sm font-medium hover:bg-white/10 hover:border-primary/30 transition-all shadow-lg hover:shadow-primary/10">
                            <span class="material-symbols-outlined">refresh</span>
                            Tải thêm
                        </button>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Hàm gọi API để xóa phim khỏi danh sách yêu thích
     * @param {number} movieId - ID của phim cần xóa
     * @param {HTMLElement} element - Nút bấm (dùng để thêm hiệu ứng loading)
     */
    function removeFavorite(movieId, element) {
        // >> BƯỚC 1: HỎI XÁC NHẬN với SweetAlert
        Swal.fire({
            title: 'Xác nhận',
            text: 'Bạn có chắc chắn muốn xóa phim này khỏi danh sách yêu thích không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D96C16',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            background: 'rgba(26, 26, 26, 0.95)',
            color: '#fff',
        }).then((result) => {
            if (result.isConfirmed) {
                performRemoveFavorite(movieId, element);
            }
        });
    }

    function performRemoveFavorite(movieId, element) {
        const hostUrl = '<?php echo _HOST_URL; ?>';

        // Chặn spam click và thiết lập loading
        if (element.classList.contains('is-processing')) return;
        element.classList.add('is-processing');

        // Lưu icon gốc để khôi phục nếu thất bại
        const originalIcon = element.innerHTML;
        element.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">sync</span>';

        const formData = new FormData();
        formData.append('movie_id', movieId);

        // >> BƯỚC 2: GỌI API XÓA
        fetch(`${hostUrl}/api/toggle-favorite`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.status === 401) {
                    throw new Error('Chưa đăng nhập');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' && data.action === 'removed') {
                    // >> BƯỚC 3: XÓA PHIM KHỎI GIAO DIỆN
                    const movieCard = document.getElementById(`movie-card-${movieId}`);
                    if (movieCard) {
                        movieCard.style.opacity = 0;
                        movieCard.style.transform = 'scale(0.8)';
                        movieCard.style.transition = 'all 0.3s ease-out';

                        setTimeout(() => {
                            movieCard.remove();

                            // Hiển thị thông báo toast
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
                            });

                            // Kiểm tra nếu không còn phim nào
                            const grid = document.getElementById('favorite-movies-grid');
                            if (grid && grid.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message || 'Xóa khỏi danh sách thất bại',
                        showConfirmButton: false,
                        timer: 3000,
                        background: 'rgba(26, 26, 26, 0.95)',
                        color: '#fff',
                    });
                    element.innerHTML = originalIcon;
                }
            })
            .catch(error => {
                console.error('Error removing favorite:', error);
                if (error.message === 'Chưa đăng nhập') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Phiên hết hạn',
                        text: 'Vui lòng đăng nhập lại.',
                        confirmButtonColor: '#D96C16',
                        background: 'rgba(26, 26, 26, 0.95)',
                        color: '#fff',
                    }).then(() => {
                        window.location.href = `${hostUrl}/login`;
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Lỗi kết nối server',
                        showConfirmButton: false,
                        timer: 3000,
                        background: 'rgba(26, 26, 26, 0.95)',
                        color: '#fff',
                    });
                }
                element.innerHTML = originalIcon;
            })
            .finally(() => {
                element.classList.remove('is-processing');
            });
    }
</script>
<!-- FOOTER -->
<?php layout('client/footer') ?>