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
                                <span class="material-symbols-outlined text-primary text-3xl lg:text-4xl animate-pulse" style="font-variation-settings: 'FILL' 1;">stars</span>
                                Diễn Viên Yêu Thích
                            </h2>
                            <p class="text-slate-400 text-sm">Bộ sưu tập những diễn viên bạn yêu thích nhất.</p>
                        </div>
                        <div class="hidden md:flex gap-2">
                            <div class="bg-white/5 border border-white/10 rounded-lg px-3 py-1 flex items-center gap-2 text-slate-400 text-sm cursor-pointer hover:bg-white/10 transition">
                                <span>Sắp xếp:</span>
                                <span class="text-white">Gần đây</span>
                                <span class="material-symbols-outlined text-sm">expand_more</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actors Grid -->
                    <?php if (empty($favoriteActors)) : ?>
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mb-6">
                                <span class="material-symbols-outlined text-5xl text-slate-600">person_off</span>
                            </div>
                            <h3 class="text-white text-xl font-semibold mb-2">Chưa có diễn viên yêu thích</h3>
                            <p class="text-slate-400 text-sm max-w-md mb-6">Bạn chưa thêm diễn viên nào vào danh sách yêu thích. Hãy khám phá và thêm các diễn viên bạn yêu thích nhé!</p>
                            <a href="<?= _HOST_URL ?>/dien_vien" class="flex items-center gap-2 px-6 py-3 rounded-full bg-primary hover:bg-secondary text-white text-sm font-medium transition-all shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined">explore</span>
                                Khám phá diễn viên
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6" id="favorite-actors-grid">
                            <?php foreach ($favoriteActors as $actor) : ?>
                                <div class="actor-card group relative overflow-hidden rounded-xl h-80 cursor-pointer transition-all duration-400 hover:-translate-y-2 hover:shadow-[0_0_30px_rgba(217,108,22,0.2)]"
                                    id="actor-card-<?php echo $actor['id']; ?>"
                                    onclick="window.location.href='<?= _HOST_URL ?>/dien_vien/chi_tiet?id=<?= $actor['id'] ?>'">

                                    <!-- Background Image -->
                                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
                                        style="background-image: url('<?= $actor['avatar'] ?: _HOST_URL_PUBLIC . '/img/default-avatar.png' ?>');"></div>

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/60 to-transparent z-10"></div>

                                    <!-- Glow Effect -->
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-primary/20 via-transparent to-transparent"></div>
                                    </div>

                                    <!-- Top Badge -->
                                    <div class="absolute top-3 left-3 z-20">
                                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-black/40 backdrop-blur-md border border-white/10">
                                            <div class="w-1.5 h-1.5 bg-primary rounded-full shadow-[0_0_8px_rgba(217,108,22,0.8)] animate-pulse"></div>
                                            <span class="text-xs text-white/80 font-medium">Diễn viên</span>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="absolute top-3 right-3 z-20">
                                        <button
                                            class="p-2 rounded-full bg-black/40 backdrop-blur-md border border-white/10 hover:bg-red-500/20 hover:border-red-500/50 transition-all duration-300 group/btn"
                                            onclick="event.stopPropagation(); removeFavoriteActor(<?php echo $actor['id']; ?>, this)"
                                            data-actor-id="<?php echo $actor['id']; ?>"
                                            title="Xóa khỏi danh sách">
                                            <span class="material-symbols-outlined text-red-500 text-lg group-hover/btn:scale-110 transition-transform" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                        </button>
                                    </div>

                                    <!-- Actor Info -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4 z-20">
                                        <h3 class="text-white font-bold text-lg truncate group-hover:text-primary transition-colors"><?= htmlspecialchars($actor['name']) ?></h3>
                                        <div class="flex items-center gap-2 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <span class="text-xs text-slate-400 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm text-primary">visibility</span>
                                                Xem chi tiết
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Decorative Lines -->
                                    <div class="absolute bottom-16 left-4 flex flex-col gap-1.5 opacity-0 group-hover:opacity-70 transition-opacity z-10">
                                        <div class="w-10 h-0.5 bg-primary/80 rounded-full shadow-[0_0_8px_rgba(217,108,22,0.8)]"></div>
                                        <div class="w-16 h-0.5 bg-white/10 rounded-full"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </main>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Hàm gọi API để xóa diễn viên khỏi danh sách yêu thích
     * @param {number} actorId - ID của diễn viên cần xóa
     * @param {HTMLElement} element - Nút bấm (dùng để thêm hiệu ứng loading)
     */
    function removeFavoriteActor(actorId, element) {
        // >> BƯỚC 1: HỎI XÁC NHẬN
        Swal.fire({
            title: 'Xác nhận',
            text: 'Bạn có chắc chắn muốn xóa diễn viên này khỏi danh sách yêu thích không?',
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
                performRemove(actorId, element);
            }
        });
    }

    function performRemove(actorId, element) {
        const hostUrl = '<?php echo _HOST_URL; ?>';

        // Chặn spam click và thiết lập loading
        if (element.classList.contains('is-processing')) return;
        element.classList.add('is-processing');

        // Lưu icon gốc để khôi phục nếu thất bại
        const originalIcon = element.innerHTML;
        element.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">sync</span>';

        const formData = new FormData();
        formData.append('actor_id', actorId);

        // >> BƯỚC 2: GỌI API XÓA
        fetch(`${hostUrl}/api/toggle-favorite-actor`, {
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
                    // >> BƯỚC 3: XÓA DIỄN VIÊN KHỎI GIAO DIỆN
                    const actorCard = document.getElementById(`actor-card-${actorId}`);
                    if (actorCard) {
                        actorCard.style.opacity = 0;
                        actorCard.style.transform = 'scale(0.8)';
                        actorCard.style.transition = 'all 0.3s ease-out';

                        setTimeout(() => {
                            actorCard.remove();

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

                            // Kiểm tra nếu không còn diễn viên nào
                            const grid = document.getElementById('favorite-actors-grid');
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
                console.error('Error removing favorite actor:', error);
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

<style>
    .actor-card {
        background: linear-gradient(135deg, rgba(18, 24, 33, 0.7) 0%, rgba(10, 14, 20, 0.8) 100%);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.7);
    }

    .actor-card:hover {
        border-color: rgba(217, 108, 22, 0.3);
    }
</style>

<!-- FOOTER -->
<?php layout('client/footer') ?>