<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
?>

<!-- Ambient Background Lighting -->
<div class="ambient-glow w-[600px] h-[600px] bg-primary top-[-200px] left-[-100px]"></div>
<div class="ambient-glow w-[500px] h-[500px] bg-secondary bottom-[10%] right-[-100px]"></div>

<!-- Micro Particles -->
<div class="particle w-1 h-1 top-20 left-1/4"></div>
<div class="particle w-2 h-2 top-1/2 left-10 opacity-10"></div>
<div class="particle w-1 h-1 bottom-40 right-1/3"></div>
<div class="particle w-1.5 h-1.5 top-32 right-20"></div>

<main class="flex-1 w-full max-w-7xl mx-auto px-3 md:px-6 py-6 md:py-8 z-10 pt-24 md:pt-28">
    <!-- Search Section -->
    <form action="" method="GET">
        <div class="flex flex-col items-center mb-12 w-full">
            <div class="w-full max-w-2xl relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-primary/80">search</span>
                </div>
                <input name="tu_khoa" id="search-input" class="block w-full pl-12 pr-12 py-4 bg-black/40 border border-primary/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-primary search-neon backdrop-blur-md transition-all text-lg" placeholder="Tìm kiếm phim, diễn viên..." type="text" value="<?php echo isset($tu_khoa) ? htmlspecialchars($tu_khoa) : '' ?>" />
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="document.getElementById('search-input').value = ''; document.getElementById('search-input').focus();">
                    <span class="material-symbols-outlined text-gray-500 hover:text-white transition-colors">close</span>
                </div>
            </div>
        </div>
    </form>
    <!-- Actors Section -->
    <section class="mb-12">
        <div class="flex items-center justify-between mb-6 px-2">
            <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span class="w-1 h-6 bg-primary rounded-full"></span>
                Diễn viên
            </h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php if (!empty($getAllPerson) && is_array($getAllPerson)) : ?>

                <?php foreach ($getAllPerson as $person) : ?>
                    <div class="glass-panel p-4 rounded-xl flex flex-col items-center gap-3 hover:bg-white/5 transition-colors group cursor-pointer border-transparent hover:border-primary/30 actor-card">
                        <div class="w-20 h-20 rounded-full p-[2px] bg-gradient-to-br from-primary/50 to-transparent group-hover:from-primary group-hover:to-primary/50 transition-all shadow-[0_0_15px_rgba(217,108,22,0.15)] group-hover:shadow-[0_0_20px_rgba(217,108,22,0.4)]">
                            <img
                                alt="<?= htmlspecialchars($person['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full h-full rounded-full object-cover"
                                src="<?= htmlspecialchars($person['avatar'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                        </div>
                        <div class="text-center">
                            <h3 class="font-medium text-white text-sm">
                                <?= htmlspecialchars($person['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </h3>
                            <p class="text-xs text-gray-400">
                                <?= htmlspecialchars($person['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else : ?>
                <div class="col-span-full text-center text-gray-400 py-8">
                    Không thấy thông tin diễn viên
                </div>
            <?php endif; ?>
        </div>

    </section>
    <!-- Movies Section -->
    <section>
        <div class="flex items-center justify-between mb-6 px-2">
            <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <span class="w-1 h-6 bg-primary rounded-full"></span>
                Phim
            </h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
            <?php if (!empty($getAllMovies) && is_array($getAllMovies)) : ?>

                <?php foreach ($getAllMovies as $movie) : ?>
                    <div onclick="window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';" class="movie-card glass-panel rounded-xl overflow-hidden group cursor-pointer relative h-[420px]">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90 z-10"></div>

                        <img
                            alt="<?= $movie['tittle'] ?>"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            src="<?= $movie['poster_url'] ?>" />

                        <div class="absolute top-3 right-3 z-20">
                            <span class="bg-black/60 backdrop-blur-md text-primary px-2 py-1 rounded text-xs font-bold border border-primary/30">
                                <?= $movie['imdb_rating'] ?>
                            </span>
                        </div>

                        <div class="absolute inset-0 z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 play-btn scale-90 transition-transform">
                            <button class="w-14 h-14 rounded-full bg-primary/90 text-white flex items-center justify-center shadow-[0_0_20px_rgba(217,108,22,0.6)] hover:bg-primary transition-colors">
                                <span class="material-symbols-outlined !text-3xl ml-1">play_arrow</span>
                            </button>
                        </div>

                        <div class="absolute bottom-0 left-0 w-full p-5 z-20">
                            <h3 class="text-xl font-bold text-white mb-1 group-hover:text-primary transition-colors">
                                <?= $movie['tittle'] ?>
                            </h3>
                            <div class="flex items-center justify-between text-xs text-gray-300">
                                <div class="flex items-center gap-2">
                                    <span><?= $movie['release_year'] ?></span>
                                    <span class="w-1 h-1 bg-gray-500 rounded-full"></span>
                                    <span><?= isset($movie['duration']) ? convertMinutesToHours((int)$movie['duration']) : '' ?></span>
                                </div>
                                <span class="border border-white/20 px-1.5 py-0.5 rounded text-[10px]">4K</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else : ?>
                <div class="col-span-full text-center text-gray-400 py-10 glass-panel rounded-xl">
                    Không thấy thông tin phim
                </div>
            <?php endif; ?>
        </div>

    </section>
</main>

<!-- Footer -->
<?php layout('client/footer'); ?>