<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');

// echo '<pre>';
// print_r($getPersonMovies);
// echo '</pre>';
// die();
?>

<body class="font-display text-white overflow-x-hidden min-h-screen relative">
    <div class="layout-container flex flex-col min-h-screen pt-20 md:pt-32">
        <div class="flex-1 flex justify-center py-6 md:py-8 px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="flex flex-col max-w-[1920px] w-full gap-8">
                <!-- Actor Profile Hero -->
                <section class="glass-panel rounded-2xl p-6 md:p-8 relative overflow-hidden group">
                    <!-- Decorational BG glow -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/20 rounded-full blur-[80px]"></div>
                    <div class="flex flex-col md:flex-row gap-8 items-center md:items-start relative z-10">
                        <!-- Holographic Avatar Container -->
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-primary/30 rounded-full blur-xl animate-pulse"></div>
                            <div class="relative w-40 h-40 rounded-full p-[2px] bg-gradient-to-br from-primary/80 to-transparent">
                                <div class="w-full h-full rounded-full overflow-hidden border-4 border-black/50 bg-black/50">
                                    <div class="w-full h-full bg-cover bg-center" data-alt="Portrait of the actor looking stoic" style='background-image: url("<?php echo $personDetail['avatar']; ?>");'></div>
                                </div>
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-black/60 backdrop-blur-md border border-glass-border p-1.5 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-xl">verified</span>
                            </div>
                        </div>
                        <!-- Bio Content -->
                        <div class="flex-1 text-center md:text-left space-y-4">
                            <div>
                                <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white tracking-tight mb-2 drop-shadow-md"><?php echo $personDetail['name'] ?></h1>
                                <div class="flex flex-wrap justify-center md:justify-start gap-3 text-sm text-gray-400">
                                    <?php
                                    $roles = explode(',', $personDetail['role_name']);
                                    foreach ($roles as $role):
                                    ?>
                                        <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm"><?php echo $role ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <p class="text-gray-300 text-lg leading-relaxed max-w-2xl">
                                <?php echo $personDetail['bio'] ?>
                            </p>
                            <div class="flex flex-wrap gap-4 pt-2 justify-center md:justify-start">
                                <button class="js-favorite-btn flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-secondary text-white font-bold rounded-lg shadow-[0_0_15px_rgba(217,108,22,0.4)] hover:shadow-[0_0_25px_rgba(242,159,5,0.6)] transition-all duration-300 transform hover:-translate-y-0.5"
                                    data-person-id="<?php echo $personDetail['id']; ?>">
                                    <span class="material-symbols-outlined text-sm">favorite</span>
                                    Yêu thích
                                </button>
                                <button class="flex items-center gap-2 px-6 py-2.5 bg-transparent border border-white/20 hover:border-white/50 hover:bg-white/5 text-white font-bold rounded-lg transition-all duration-300">
                                    <span class="material-symbols-outlined text-sm">share</span>
                                    Share
                                </button>
                            </div>
                        </div>
                        <!-- Mini Stats Panel (Glass overlay) -->
                        <div class="hidden lg:flex flex-col gap-3 min-w-[200px] p-4 rounded-xl bg-black/20 border border-white/5 backdrop-blur-md">
                            <div class="flex justify-between items-center pb-2 border-b border-white/5">
                                <span class="text-xs text-gray-400 uppercase tracking-widest">Rank</span>
                                <span class="text-primary font-bold">#4 Global</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 uppercase tracking-widest">Followers</span>
                                <span class="text-white font-mono">12.5M</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 uppercase tracking-widest">Likes</span>
                                <span class="text-white font-mono">842K</span>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Stats Grid -->
                <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="glass-card p-5 rounded-xl flex flex-col items-center justify-center text-center group">
                        <span class="material-symbols-outlined text-primary/70 text-3xl mb-2 group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(217,108,22,0.8)] transition-all">movie</span>
                        <p class="text-3xl font-bold text-white"><?php echo $personDetail['count_movies'] ?></p>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Phim</p>
                    </div>
                    <div class="glass-card p-5 rounded-xl flex flex-col items-center justify-center text-center group">
                        <span class="material-symbols-outlined text-primary/70 text-3xl mb-2 group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(217,108,22,0.8)] transition-all">trophy</span>
                        <p class="text-3xl font-bold text-white"><?php echo $personDetail['awards'] ?></p>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Giải thưởng</p>
                    </div>
                    <div class="glass-card p-5 rounded-xl flex flex-col items-center justify-center text-center group">
                        <span class="material-symbols-outlined text-primary/70 text-3xl mb-2 group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(217,108,22,0.8)] transition-all">stars</span>
                        <p class="text-3xl font-bold text-white"><?php echo $personDetail['nominations'] ?></p>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Đề cử</p>
                    </div>
                    <div class="glass-card p-5 rounded-xl flex flex-col items-center justify-center text-center group">
                        <span class="material-symbols-outlined text-primary/70 text-3xl mb-2 group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(217,108,22,0.8)] transition-all">history</span>
                        <p class="text-3xl font-bold text-white"><?php echo $personDetail['start_year'] ?></p>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">Năm hoạt động</p>
                    </div>
                </section>
                <!-- Filmography Section -->
                <section class="flex flex-col gap-6">
                    <div class="flex items-center justify-between px-2">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-6 bg-primary shadow-[0_0_8px_#D96C16] rounded-full"></div>
                            <h2 class="text-2xl font-bold text-white tracking-wide">Danh sách phim</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3 md:gap-6">
                        <!-- Movie Card -->
                        <?php foreach ($getPersonMovies as $movie):
                            $favClass = (!empty($movie['is_favorited'])) ? 'is-favorited' : '';
                        ?>
                            <div class="glass-card rounded-xl p-3 flex flex-col gap-3 group cursor-pointer relative overflow-hidden">
                                <div onclick="window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';" class="relative w-full aspect-[2/3] rounded-lg overflow-hidden cursor-pointer">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-10 opacity-60"></div>
                                    <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" data-alt="<?php echo $movie['tittle']; ?>" style='background-image: url("<?php echo $movie['poster_url']; ?>");'></div>
                                    <div class="absolute top-2 right-2 z-20 bg-black/60 backdrop-blur-md px-2 py-0.5 rounded-md border border-white/10 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-yellow-400 text-[12px]">star</span>
                                        <span class="text-xs font-bold"><?php echo $movie['imdb_rating'] ?></span>
                                    </div>
                                </div>
                                <div class="px-1">
                                    <h3 onclick="window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';" class="text-white font-medium truncate group-hover:text-primary transition-colors cursor-pointer"><?php echo $movie['tittle'] ?></h3>
                                    <div class="flex items-center justify-between mt-1">
                                        <p onclick="window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';" class="text-gray-500 text-xs truncate flex-1 cursor-pointer"><?php echo $movie['original_tittle'] ?></p>
                                        <button
                                            class="js-favorite-btn ml-2 flex-shrink-0 p-2 rounded-full hover:bg-white/10 transition-all duration-300 <?= $favClass ?>"
                                            data-movie-id="<?php echo $movie['id']; ?>">
                                            <span class="material-symbols-outlined text-gray-400 hover:text-red-500 hover:scale-110 transition-all duration-300 text-2xl">favorite</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
        <!-- Pagination Simplified -->
        <div class="w-full flex justify-center mt-12 mb-10">
            <div class="flex items-center gap-2">
                <!-- Previous -->
                <?php if ($page > 1): ?>
                    <button onclick="window.location.href='dien_vien?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'"
                        class="group w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-primary hover:border-primary text-gray-400 hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary/30">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                <?php else: ?>
                    <button disabled class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-gray-700 cursor-not-allowed">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                <?php endif; ?>

                <!-- Numbers -->
                <div class="flex items-center gap-2 mx-2">
                    <?php
                    $start = $page - 1;
                    if ($start < 1) $start = 1;
                    $end = $page + 1;
                    if ($end > $maxPage) $end = $maxPage;

                    for ($i = $start; $i <= $end; $i++):
                        $isActive = ($page == $i);
                        $btnClass = $isActive
                            ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-110 font-bold border-primary'
                            : 'bg-transparent text-gray-400 hover:text-white hover:bg-white/5 border-transparent';
                    ?>
                        <button onclick="window.location.href='dien_vien?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                            class="w-10 h-10 flex items-center justify-center rounded-xl text-sm border transition-all duration-300 <?php echo $btnClass ?>">
                            <?php echo $i ?>
                        </button>
                    <?php endfor; ?>
                </div>

                <!-- Next -->
                <?php if ($page < $maxPage): ?>
                    <button onclick="window.location.href='dien_vien?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'"
                        class="group w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-primary hover:border-primary text-gray-400 hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary/30">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                <?php else: ?>
                    <button disabled class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-gray-700 cursor-not-allowed">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

<!-- FOOTER -->
<?php layout('client/footer') ?>

</html>