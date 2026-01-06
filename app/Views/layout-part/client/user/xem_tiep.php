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
                                Xem tiếp phim
                            </h2>
                            <p class="text-slate-400 text-sm">Xem tiếp phim của bạn</p>
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
                        <?php foreach ($getContinueWatching as $movie) : ?>
                            <div class="favorite-movie-card" id="movie-card-<?php echo $movie['id']; ?>">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-transparent z-10 opacity-80 pointer-events-none"></div>
                                <?php
                                // Tạo URL xem phim với đúng season và episode đang xem dở
                                $watchUrl = _HOST_URL . '/xem-phim/' . $movie['movie_slug'];
                                if (!empty($movie['season_number']) && !empty($movie['episode_number'])) {
                                    $watchUrl .= '?ss=' . $movie['season_number'] . '&ep=' . $movie['episode_number'];
                                } elseif (!empty($movie['episode_number'])) {
                                    $watchUrl .= '?ep=' . $movie['episode_number'];
                                }
                                ?>
                                <div onclick="window.location.href='<?php echo $watchUrl; ?>';" class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110 cursor-pointer z-5" style="background-image: url('<?= $movie['poster_url']; ?>');"></div>
                                <div class="absolute top-2 right-2 z-20">
                                    <button onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/delete-history-continue-page?id=<?php echo $movie['id']; ?>';"
                                        class="p-1.5 rounded-full bg-black/80 hover:bg-red-500 transition-all duration-300 shadow-md border border-white/30 hover:border-red-500"
                                        title="Xóa khỏi danh sách">
                                        <span class="material-symbols-outlined text-white text-[18px]">close</span>
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
<!-- FOOTER -->
<?php layout('client/footer') ?>