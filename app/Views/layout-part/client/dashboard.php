<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// Sử dụng biến từ controller để xác định trạng thái favorite
$favClass = $heroIsFavorited ? 'is-favorited' : '';
// echo '<pre>';
// print_r($getContinueWatching);
// echo '</pre>';
// die();
?>

<div class="min-h-screen selection:bg-secondary/30 selection:text-white relative">
    <!-- Ambient Header Gradient -->
    <div class="fixed top-0 left-0 w-full h-[300px] bg-gradient-to-b from-primary/10 via-secondary/5 to-transparent pointer-events-none z-0"></div>

    <!-- Hero Section -->
    <div class="relative h-[60vh] sm:h-[70vh] md:h-[95vh] w-full flex items-center overflow-hidden group transition-all duration-500">
        <?php
        $heroFirst = !empty($getMoviesHeroSection) ? $getMoviesHeroSection[0] : null;
        if (!$heroFirst): ?>
            <div class="flex items-center justify-center h-full">
                <p class="text-gray-400 text-xl">Chưa có phim nào. Hãy crawl phim trước!</p>
            </div>
        <?php else: ?>
            <!-- Background Image: Hardcoded initial style for immediate rendering -->
            <div id="heroBackground" class="absolute inset-0 bg-cover bg-center transition-all duration-700 ease-in-out" style="background-image: url('<?php echo $heroFirst['thumbnail']; ?>');"></div>

            <!-- Gradients -->
            <div class="absolute inset-0 bg-gradient-to-r from-background-dark via-background-dark/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/40 to-transparent"></div>


            <div class="relative z-10 px-4 md:px-12 w-full max-w-5xl mt-16 sm:mt-24 md:mt-32 flex flex-col justify-center h-full pb-8 sm:pb-12">
                <h2 id="heroTitle" class="text-2xl sm:text-3xl md:text-6xl lg:text-7xl font-bold mb-1 sm:mb-2 drop-shadow-xl text-white leading-tight uppercase font-heading"><?php echo $heroFirst['tittle']; ?></h2>
                <h3 id="heroSubtitle" class="text-base sm:text-xl md:text-2xl text-yellow-500 font-semibold mb-3 sm:mb-6 drop-shadow-md"><?php echo $heroFirst['original_tittle']; ?></h3>

                <div id="heroMeta" class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2 sm:mb-4 text-xs sm:text-sm font-bold text-white">
                    <span class="js-meta-imdb bg-[#e2b616] text-black px-1 rounded font-bold">IMDb <?php echo $heroFirst['imdb_rating']; ?></span>
                    <span class="js-meta-age bg-red-600 px-1 rounded"><?php echo $heroFirst['age']; ?></span>
                    <span class="js-meta-year text-gray-300"><?php echo $heroFirst['release_year']; ?></span>
                    <span class="js-meta-duration text-gray-300"><?php echo convertMinutesToHours($heroFirst['duration']); ?></span>
                    <span class="js-meta-type bg-gray-700 px-1 rounded text-xs"><?php echo $heroFirst['type_name']; ?></span>
                </div>

                <div id="heroGenres" class="hidden sm:flex flex-wrap items-center gap-2 sm:gap-3 mb-4 sm:mb-6 text-xs sm:text-sm text-gray-300 font-medium">
                    <?php
                    $genres = explode(',', $heroFirst['genre_name'] ?? '');
                    foreach ($genres as $genre) { ?>
                        <span><?php echo trim($genre); ?></span>
                        <span class="text-gray-500 mx-1">•</span>
                    <?php } ?>
                </div>

                <p id="heroDesc" class="hidden sm:block text-sm sm:text-base md:text-lg text-gray-200 mb-4 sm:mb-8 drop-shadow-md max-w-2xl leading-relaxed" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;"><?php echo $heroFirst['description']; ?></p>
                <div class="flex gap-2 sm:gap-4">
                    <a id="heroPlay" href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $heroFirst['id'] ?>" class="group/btn flex items-center gap-2 sm:gap-3 bg-[#e2b616] hover:bg-[#ffc107] text-black px-5 py-2.5 sm:px-8 sm:py-3.5 rounded-full font-bold transition-all duration-300 transform hover:scale-105 shadow-[0_0_20px_rgba(226,182,22,0.4)]">
                        <i data-lucide="play" class="w-4 h-4 sm:w-5 sm:h-5 fill-current"></i>
                    </a>

                    <button id="heroFav"
                        class="group/fav flex items-center gap-2 sm:gap-3 px-5 py-2.5 sm:px-8 sm:py-3.5 rounded-full font-bold text-white transition-all duration-300 transform hover:scale-105 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md js-favorite-btn <?= $favClass ?>"
                        data-movie-id="<?php echo $heroFirst['id']; ?>">
                        <i data-lucide="heart" class="w-4 h-4 sm:w-5 sm:h-5 group-hover/fav:text-red-500 group-hover/fav:fill-red-500 transition-colors"></i>
                    </button>
                </div>
            </div>

            <div class="absolute bottom-4 sm:bottom-8 right-2 sm:right-4 md:right-12 z-30 flex gap-2 sm:gap-4 overflow-x-auto max-w-[70vw] sm:max-w-[80vw] md:max-w-[70vw] pb-2 no-scrollbar pl-2 sm:pl-4 items-end" id="heroThumbnails">
                <?php foreach ($getMoviesHeroSection as $key => $value): ?>
                    <div class="hero-thumb flex-shrink-0 w-[45px] h-[26px] sm:w-[60px] sm:h-[35px] rounded-md overflow-hidden cursor-pointer border-2 <?php echo ($key == 0) ? 'border-white opacity-100 scale-105' : 'border-transparent opacity-60 hover:opacity-100'; ?> transition-all duration-300"
                        data-index="<?php echo $key ?>"
                        data-id="<?php echo $value['id'] ?>"
                        data-bg="<?php echo $value['thumbnail'] ?>"

                        data-imdb="<?php echo $value['imdb_rating'] ?>"
                        data-year="<?php echo $value['release_year'] ?>"
                        data-age="<?php echo $value['age']; ?>"
                        data-duration="<?php echo convertMinutesToHours($value['duration']); ?>"
                        data-type="<?php echo $value['type_name']; ?>"

                        data-info="<?php echo htmlspecialchars($value['genre_name'] ?? '', ENT_QUOTES); ?>"
                        data-title="<?php echo htmlspecialchars($value['tittle'], ENT_QUOTES); ?>"
                        data-subtitle="<?php echo htmlspecialchars($value['original_tittle'], ENT_QUOTES); ?>"
                        data-desc="<?php echo htmlspecialchars($value['description'], ENT_QUOTES); ?>">

                        <img loading="lazy" src="<?php echo $value['poster_url'] ?>" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; ?>
            </div>
    </div>
<?php endif; ?>

<!-- Main Content -->
<div class="relative z-20 -mt-8 sm:-mt-16 md:-mt-24 pb-12 sm:pb-20 space-y-6 sm:space-y-8">
    <!-- Continue Watching List -->
    <?php if (!empty($getContinueWatching)): ?>
        <div class="relative z-20 px-3 sm:px-4 md:px-12 pt-16 sm:pt-24 md:pt-28 pb-6 sm:pb-10 group/continue section-continue">
            <div class="flex items-center gap-2 sm:gap-4 mb-4 sm:mb-8">
                <div class="w-1 sm:w-1.5 h-6 sm:h-8 bg-yellow-500 rounded-full shadow-[0_0_10px_rgba(234,179,8,0.8)]"></div>
                <h3 class="text-xl sm:text-2xl md:text-3xl font-black uppercase text-white tracking-wide">
                    <span style="color: var(--primary-color);">Đang Xem</span>
                </h3>
            </div>

            <div class="relative group/slider">
                <!-- Navigation -->
                <button type="button" class="sw-button sw-prev-continue absolute top-1/2 -left-4 z-30 transform -translate-y-1/2 w-10 h-10 bg-black/50 hover:bg-yellow-500 text-white hover:text-black rounded-full flex items-center justify-center transition-all opacity-0 group-hover/slider:opacity-100 backdrop-blur-sm border border-white/10">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="sw-button sw-next-continue absolute top-1/2 -right-4 z-30 transform -translate-y-1/2 w-10 h-10 bg-black/50 hover:bg-yellow-500 text-white hover:text-black rounded-full flex items-center justify-center transition-all opacity-0 group-hover/slider:opacity-100 backdrop-blur-sm border border-white/10">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <div class="swiper swiper-continue overflow-visible">
                    <div class="swiper-wrapper">
                        <?php foreach ($getContinueWatching as $item):
                            $totalDuration = ($item['episode_id'] > 0 && !empty($item['episode_duration'])) ? $item['episode_duration'] : $item['movie_duration'];
                            $currentMinutes = $item['current_time'] / 60;
                            $percent = 0;
                            if ($totalDuration > 0) {
                                $percent = min(100, max(0, ($currentMinutes / $totalDuration) * 100));
                            }
                            $link = _HOST_URL . '/watch?id=' . $item['movie_id'] . '&episode_id=' . $item['episode_id'];
                            $image = !empty($item['thumbnail']) ? $item['thumbnail'] : $item['poster_url'];
                        ?>
                            <div class="swiper-slide w-[200px] sm:w-[260px] md:w-[350px]">
                                <div class="relative group/card cursor-pointer rounded-xl overflow-hidden bg-background-dark/50 ring-1 ring-white/10 hover:ring-yellow-500/50 transition-all duration-300 shadow-lg">
                                    <!-- Image Container -->
                                    <div class="relative aspect-video w-full overflow-hidden">
                                        <!-- Remove Button (X) -->
                                        <button onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/delete-history-dashboard?id=<?php echo $item['id']; ?>';"
                                            class="js-remove-history absolute top-2 right-2 z-30 w-8 h-8 rounded-full bg-black/80 hover:bg-red-500 text-white/70 hover:text-white flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-all duration-300 backdrop-blur-sm border border-white/30 hover:border-red-500 hover:scale-110"
                                            data-movie-id="<?php echo $item['movie_id']; ?>"
                                            data-episode-id="<?php echo $item['episode_id']; ?>"
                                            title="Xóa khỏi danh sách">
                                            <span class="material-symbols-outlined text-base">close</span>
                                        </button>

                                        <img loading="lazy" src="<?php echo $image; ?>" class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-700">

                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-black/40 group-hover/card:bg-black/20 transition-colors"></div>

                                        <!-- Play Button -->
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 transform scale-90 group-hover/card:scale-100">
                                            <a href="<?php echo $link; ?>" class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-black shadow-[0_0_20px_rgba(234,179,8,0.6)] hover:scale-110 transition-transform">
                                                <i class="fa-solid fa-play ml-1"></i>
                                            </a>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-700/50">
                                            <div class="h-full bg-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.8)]" style="width: <?php echo $percent; ?>%;"></div>
                                        </div>
                                    </div>

                                    <!-- Info -->
                                    <div class="p-3">
                                        <h4 class="text-white font-bold text-base truncate group-hover/card:text-yellow-500 transition-colors"><?php echo $item['tittle']; ?></h4>
                                        <p class="text-yellow-500/70 text-xs truncate mb-2"><?php echo $item['original_tittle']; ?></p>
                                        <div class="flex items-center justify-between text-xs text-gray-400">
                                            <?php
                                            // Ẩn phần "Tập Full" nếu là phim lẻ 
                                            $isSeriesEpisode = !empty($item['season_name']) || (!empty($item['episode_name']) && strtolower($item['episode_name']) !== 'full');
                                            ?>
                                            <?php if ($isSeriesEpisode): ?>
                                                <span class="truncate pr-2">
                                                    <?php if (!empty($item['season_name'])): ?>
                                                        <?php echo $item['season_name']; ?><span class="mx-1">•</span>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Kiểm tra nếu episode_name đã chứa "Tập" thì không thêm nữa
                                                    $episodeName = !empty($item['episode_name']) ? $item['episode_name'] : '1';
                                                    $hasPrefix = (stripos($episodeName, 'Tập') !== false || stripos($episodeName, 'Episode') !== false);
                                                    echo $hasPrefix ? $episodeName : 'Tập ' . $episodeName;
                                                    ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="truncate pr-2"></span>
                                            <?php endif; ?>
                                            <span class="font-medium text-yellow-500/80 whitespace-nowrap"><?php echo round($currentMinutes) . 'm / ' . convertMinutesToHours($totalDuration); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Country Movie -->
    <div class="relative z-20 px-3 sm:px-4 md:px-12 space-y-8 sm:space-y-12 pb-8 sm:pb-12 <?php echo empty($getContinueWatching) ? 'pt-32 sm:pt-36 md:pt-40' : ''; ?>">

        <!-- Korea Section -->
        <div class="flex flex-col md:flex-row gap-4 sm:gap-6 items-start group/section cards-slide wide section-korea">
            <div class="w-full md:w-64 flex-shrink-0 flex flex-col justify-center md:h-[200px] space-y-2 sm:space-y-3">
                <h3 class="text-xl sm:text-2xl md:text-3xl font-bold uppercase leading-tight bg-gradient-to-br from-white to-purple-600 bg-clip-text text-transparent drop-shadow-sm">
                    Phim Hàn<br class="hidden md:block"> Quốc mới
                </h3>
                <a href="/c/phim-han-quoc-moi" class="flex items-center text-gray-400 hover:text-white transition-colors text-sm font-medium group/link">
                    Xem toàn bộ
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="flex-1 w-full overflow-hidden relative group/slider">
                <div class="cards-slide-wrapper">
                    <div class="sw-navigation">
                        <button type="button" class="sw-button sw-prev-korea">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="sw-button sw-next-korea">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="swiper swiper-korea">
                        <div class="swiper-wrapper">
                            <?php foreach ($getMoviesKorean as $item): ?>
                                <div class="swiper-slide">
                                    <div class="sw-item">
                                        <a class="v-thumbnail" href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>">
                                            <div class="pin-new m-pin-new">
                                                <div class="line-center line-pd">Full HD</div>
                                                <div class="line-center line-tm">Vietsub</div>
                                            </div>
                                            <div class="image-wrapper">
                                                <img loading="lazy" class="movie-thumb" src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['tittle']; ?>">
                                                <div class="play-overlay">
                                                    <div class="btn-action btn-play" onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';"><i class="fa-solid fa-play"></i></div>
                                                    <button class="btn-action btn-fav js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-5 h-5"></i></button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="info">
                                            <h4 class="item-title lim-1"><a href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>"><?php echo $item['tittle']; ?></a></h4>
                                            <h4 class="alias-title lim-1"><?php echo $item['original_tittle']; ?></h4>
                                            <div class="meta-info">
                                                <span><?php echo $item['release_year']; ?></span> <span class="dot">•</span> <span><?php echo convertMinutesToHours($item['duration']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-800 to-transparent w-full"></div>

        <!-- China Section -->
        <div class="flex flex-col md:flex-row gap-6 items-start group/section cards-slide wide section-china">
            <div class="w-full md:w-64 flex-shrink-0 flex flex-col justify-center md:h-[200px] space-y-3">
                <h3 class="text-2xl md:text-3xl font-bold uppercase leading-tight bg-gradient-to-br from-white to-orange-500 bg-clip-text text-transparent drop-shadow-sm">
                    Phim Trung<br class="hidden md:block"> Quốc mới
                </h3>
                <a href="/c/phim-trung-quoc-moi" class="flex items-center text-gray-400 hover:text-white transition-colors text-sm font-medium group/link">
                    Xem toàn bộ
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="flex-1 w-full overflow-hidden relative group/slider">
                <div class="cards-slide-wrapper">
                    <div class="sw-navigation">
                        <button type="button" class="sw-button sw-prev-china">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="sw-button sw-next-china">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="swiper swiper-china">
                        <div class="swiper-wrapper">
                            <?php foreach ($getMoviesChinese as $item): ?>
                                <div class="swiper-slide">
                                    <div class="sw-item">
                                        <a class="v-thumbnail" href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>">
                                            <div class="pin-new m-pin-new">
                                                <div class="line-center line-pd">Full HD</div>
                                                <div class="line-center line-tm">Vietsub</div>
                                            </div>
                                            <div class="image-wrapper">
                                                <img loading="lazy" class="movie-thumb" src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['tittle']; ?>">
                                                <div class="play-overlay">
                                                    <div class="btn-action btn-play" onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';"><i class="fa-solid fa-play"></i></div>
                                                    <button class="btn-action btn-fav js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-5 h-5"></i></button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="info">
                                            <h4 class="item-title lim-1"><a href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>"><?php echo $item['tittle']; ?></a></h4>
                                            <h4 class="alias-title lim-1"><?php echo $item['original_tittle']; ?></h4>
                                            <div class="meta-info">
                                                <span><?php echo $item['release_year']; ?></span> <span class="dot">•</span> <span><?php echo convertMinutesToHours($item['duration']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-800 to-transparent w-full"></div>

        <!-- US-UK Section -->
        <div class="flex flex-col md:flex-row gap-6 items-start group/section cards-slide wide section-usuk">
            <div class="w-full md:w-64 flex-shrink-0 flex flex-col justify-center md:h-[200px] space-y-3">
                <h3 class="text-2xl md:text-3xl font-bold uppercase leading-tight bg-gradient-to-br from-white to-pink-500 bg-clip-text text-transparent drop-shadow-sm">
                    Phim US-UK<br class="hidden md:block"> mới
                </h3>
                <a href="/c/phim-us-uk-moi" class="flex items-center text-gray-400 hover:text-white transition-colors text-sm font-medium group/link">
                    Xem toàn bộ
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="flex-1 w-full overflow-hidden relative group/slider">
                <div class="cards-slide-wrapper">
                    <div class="sw-navigation">
                        <button type="button" class="sw-button sw-prev-usuk">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="sw-button sw-next-usuk">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="swiper swiper-usuk">
                        <div class="swiper-wrapper">
                            <?php foreach ($getMoviesUSUK as $item): ?>
                                <div class="swiper-slide">
                                    <div class="sw-item">
                                        <a class="v-thumbnail" href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>">
                                            <div class="pin-new m-pin-new">
                                                <div class="line-center line-pd">Full HD</div>
                                                <div class="line-center line-tm">Vietsub</div>
                                            </div>
                                            <div class="image-wrapper">
                                                <img loading="lazy" class="movie-thumb" src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['tittle']; ?>">
                                                <div class="play-overlay">
                                                    <div class="btn-action btn-play" onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';"><i class="fa-solid fa-play"></i></div>
                                                    <button class="btn-action btn-fav js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-5 h-5"></i></button>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="info">
                                            <h4 class="item-title lim-1"><a href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>"><?php echo $item['tittle']; ?></a></h4>
                                            <h4 class="alias-title lim-1"><?php echo $item['original_tittle']; ?></h4>
                                            <div class="meta-info">
                                                <span><?php echo $item['release_year']; ?></span> <span class="dot">•</span> <span><?php echo convertMinutesToHours($item['duration']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-800 to-transparent w-full"></div>

    </div>

    <!-- Top 10 Phim Bo Section -->
    <div class="relative z-20 px-3 sm:px-4 md:px-12 pb-8 sm:pb-12 group/top10-series cards-slide wide section-top10-series">
        <div class="flex items-center justify-between mb-4 sm:mb-8 relative">
            <div>
                <h3 class="text-lg sm:text-2xl md:text-4xl font-black uppercase text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-yellow-500 to-orange-500 drop-shadow-sm tracking-tight">
                    Top 10 Phim Bộ Hôm Nay
                </h3>
                <p class="text-gray-400 text-xs sm:text-sm mt-1 font-medium hidden sm:block">Bảng xếp hạng phim bộ được xem nhiều nhất trong ngày</p>
            </div>
        </div>

        <div class="relative group/slider">
            <div class="cards-slide-wrapper">
                <div class="sw-navigation">
                    <button type="button" class="sw-button sw-prev-top10-series">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" class="sw-button sw-next-top10-series">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <div class="swiper swiper-top10-series">
                    <div class="swiper-wrapper">
                        <?php
                        $rank = 1;
                        foreach ($getTopDailyByType2 as $item):
                        ?>
                            <div class="swiper-slide">
                                <div class="relative flex-shrink-0 w-full snap-start group/card cursor-pointer"> <!-- Removed fixed width -->
                                    <!-- Rank Number -->
                                    <div class="absolute -left-3 sm:-left-6 -top-2 sm:-top-4 z-40 select-none pointer-events-none">
                                        <span class="font-black text-5xl sm:text-6xl md:text-8xl italic text-transparent bg-clip-text bg-gradient-to-b from-yellow-300 to-yellow-700 drop-shadow-2xl"
                                            style="-webkit-text-stroke: 2px rgba(255,255,255,0.05); filter: drop-shadow(4px 4px 6px rgba(0,0,0,0.8)); font-family: 'Arial', sans-serif;">
                                            <?php echo $rank++; ?>
                                        </span>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden ring-1 ring-white/10 shadow-[0_0_15px_rgba(0,0,0,0.5)] group-hover/card:ring-yellow-500/50 group-hover/card:shadow-[0_0_30px_rgba(234,179,8,0.3)] transition-all duration-500 transform group-hover/card:-translate-y-2 bg-background-dark/50">
                                        <img loading="lazy" src="<?php echo $item['poster_url']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-700 ease-out filter brightness-90 group-hover/card:brightness-110">

                                        <!-- Premium Shine Effect -->
                                        <div class="absolute inset-0 bg-gradient-to-tr from-white/10 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-500"></div>

                                        <!-- Info Overlay -->
                                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black via-black/80 to-transparent pt-12 transform translate-y-2 group-hover/card:translate-y-0 transition-transform duration-300">
                                            <h4 class="text-white font-bold text-lg leading-tight line-clamp-2 mb-1 shadow-black drop-shadow-md"><?php echo $item['tittle']; ?></h4>
                                            <p class="text-yellow-500/90 text-xs font-semibold uppercase tracking-wide truncate mb-2"><?php echo $item['original_tittle']; ?></p>

                                            <div class="flex items-center justify-between opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 delay-75">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] bg-red-600 px-1.5 py-0.5 rounded font-bold text-white shadow-sm">T16</span>
                                                    <span class="text-[10px] text-gray-300 font-medium"><?php echo $item['release_year']; ?></span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <i onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';" data-lucide="play-circle" class="w-8 h-8 text-white fill-white/20 hover:scale-110 transition-transform cursor-pointer"></i>
                                                    <button class="js-favorite-btn border-0 bg-transparent p-0" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-8 h-8 text-white/70 hover:text-red-500 hover:scale-110 transition-transform cursor-pointer"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Phim Le Section -->
    <div class="relative z-20 px-3 sm:px-4 md:px-12 pb-8 sm:pb-12 group/top10-movies cards-slide wide section-top10-movies">
        <div class="flex items-center justify-between mb-4 sm:mb-8 relative">
            <div>
                <h3 class="text-lg sm:text-2xl md:text-4xl font-black uppercase text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-red-600 to-rose-500 drop-shadow-sm tracking-tight">
                    Top 10 Phim Lẻ Hôm Nay
                </h3>
                <p class="text-gray-400 text-xs sm:text-sm mt-1 font-medium hidden sm:block">Bảng xếp hạng phim lẻ được xem nhiều nhất trong ngày</p>
            </div>
        </div>

        <div class="relative group/slider">
            <div class="cards-slide-wrapper">
                <div class="sw-navigation">
                    <button type="button" class="sw-button sw-prev-top10-movies">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" class="sw-button sw-next-top10-movies">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <div class="swiper swiper-top10-movies">
                    <div class="swiper-wrapper">
                        <?php
                        $rank = 1;
                        foreach ($getTopDailyByType1 as $item):
                        ?>
                            <div class="swiper-slide">
                                <div class="relative flex-shrink-0 w-full snap-start group/card cursor-pointer"> <!-- Removed fixed width -->
                                    <!-- Rank Number -->
                                    <div class="absolute -left-3 sm:-left-6 -top-2 sm:-top-4 z-40 select-none pointer-events-none">
                                        <span class="font-black text-5xl sm:text-6xl md:text-8xl italic text-transparent bg-clip-text bg-gradient-to-b from-red-400 to-red-700 drop-shadow-2xl"
                                            style="-webkit-text-stroke: 2px rgba(255,255,255,0.05); filter: drop-shadow(4px 4px 6px rgba(0,0,0,0.8)); font-family: 'Arial', sans-serif;">
                                            <?php echo $rank++; ?>
                                        </span>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden ring-1 ring-white/10 shadow-[0_0_15px_rgba(0,0,0,0.5)] group-hover/card:ring-red-500/50 group-hover/card:shadow-[0_0_30px_rgba(239,68,68,0.3)] transition-all duration-500 transform group-hover/card:-translate-y-2 bg-background-dark/50">
                                        <img loading="lazy" src="<?php echo $item['poster_url']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-700 ease-out filter brightness-90 group-hover/card:brightness-110">

                                        <!-- Premium Shine Effect -->
                                        <div class="absolute inset-0 bg-gradient-to-tr from-white/10 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-500"></div>

                                        <!-- Info Overlay -->
                                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black via-black/80 to-transparent pt-12 transform translate-y-2 group-hover/card:translate-y-0 transition-transform duration-300">
                                            <h4 class="text-white font-bold text-lg leading-tight line-clamp-2 mb-1 shadow-black drop-shadow-md"><?php echo $item['tittle']; ?></h4>
                                            <p class="text-red-400 text-xs font-semibold uppercase tracking-wide truncate mb-2"><?php echo $item['original_tittle']; ?></p>

                                            <div class="flex items-center justify-between opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 delay-75">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] bg-red-600 px-1.5 py-0.5 rounded font-bold text-white shadow-sm">T16</span>
                                                    <span class="text-[10px] text-gray-300 font-medium"><?php echo $item['release_year']; ?></span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <i onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';" data-lucide="play-circle" class="w-8 h-8 text-white fill-white/20 hover:scale-110 transition-transform cursor-pointer"></i>
                                                    <button class="js-favorite-btn border-0 bg-transparent p-0" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-8 h-8 text-white/70 hover:text-red-500 hover:scale-110 transition-transform cursor-pointer"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cinema Section -->
    <div class="relative z-20 px-3 sm:px-4 md:px-12 py-8 sm:py-12 group/theater cards-slide wide section-cinema">

        <div class="flex items-end justify-between mb-4 sm:mb-8">
            <div class="relative">
                <h3 class="text-xl sm:text-3xl md:text-4xl font-black uppercase text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-500 to-red-500 drop-shadow-sm tracking-tighter">
                    Mãn Nhãn Phim Chiếu Rạp
                </h3>
                <div class="h-0.5 sm:h-1 w-1/2 bg-gradient-to-r from-amber-400 to-transparent mt-2 rounded-full"></div>
            </div>
            <a href="#" class="hidden md:flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm font-semibold group/link">
                <span>Xem tất cả</span>
                <i data-lucide="arrow-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="relative group/slider">
            <div class="cards-slide-wrapper">
                <div class="sw-navigation">
                    <button type="button" class="sw-button sw-prev-cinema">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" class="sw-button sw-next-cinema">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <div class="swiper swiper-cinema">
                    <div class="swiper-wrapper">
                        <?php foreach ($getCinemaMovie as $item) : ?>
                            <div class="swiper-slide">
                                <div class="movie-card-wrapper relative flex-shrink-0 w-full snap-start group/card cursor-pointer"
                                    data-title="<?php echo $item['tittle']; ?>" data-year="2024" data-genre="Action" data-image="<?php echo $item['poster_url']; ?>" data-desc="Kong và Godzilla phải hợp tác chống lại một mối đe dọa khổng lồ ẩn sâu trong Trái Đất."> <!-- Removed fixed width -->

                                    <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-background-dark/50 ring-1 ring-white/10 group-hover/card:ring-amber-500 transition-all duration-500 shadow-lg group-hover/card:shadow-[0_0_25px_rgba(245,158,11,0.3)]">
                                        <img loading="lazy" src="<?php echo $item['poster_url']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-700 ease-out">

                                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                                            <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-md uppercase tracking-wider">Hot</span>
                                            <span class="bg-black/80 backdrop-blur-md text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-500/30">Vietsub</span>
                                        </div>

                                        <div class="absolute top-2 right-2 bg-amber-400 text-black text-[11px] font-extrabold px-1.5 py-0.5 rounded shadow-md">
                                            7.2
                                        </div>

                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                            <div class="transform translate-y-4 group-hover/card:translate-y-0 transition-transform duration-300 flex flex-col items-center gap-4">
                                                <div class="flex items-center gap-3">
                                                    <button class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-black hover:scale-110 transition-transform shadow-lg shadow-amber-500/50 quick-view-btn">
                                                        <i onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';" data-lucide="play" class="w-5 h-5 fill-current ml-1"></i>
                                                    </button>
                                                    <button class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 hover:scale-110 transition-all shadow-lg js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>">
                                                        <i data-lucide="heart" class="w-5 h-5"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 px-1 transition-transform duration-300 group-hover/card:translate-x-1">
                                        <h4 class="text-white font-bold text-lg truncate group-hover/card:text-amber-400 transition-colors leading-tight"><?php echo $item['tittle']; ?></h4>
                                        <p class="text-gray-500 text-xs truncate mt-1 font-medium flex items-center gap-2">
                                            <span><?php echo $item['original_tittle']; ?></span>
                                            <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                                            <span><?php echo $item['release_year']; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anime Section (New Design) -->
    <div class="max-w-[1900px] px-3 sm:px-5 md:px-12 mx-auto mt-16 sm:mt-20 md:mt-12 mb-44 sm:mb-52 md:mb-64">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-2xl md:text-3xl font-bold text-white uppercase tracking-tight">Kho Tàng Anime Mới Nhất</h2>
            <a href="/c/anime" class="flex items-center gap-2 text-gray-400 hover:text-[#FFD875] transition-colors text-xs sm:text-sm font-semibold group">
                <span>Xem thêm</span>
                <i data-lucide="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="relative w-full group/anime-section">
            <!-- Main Display Area (Reduced Height) -->
            <div id="anime-main-display" class="relative w-full h-[300px] sm:h-[400px] md:h-[500px] rounded-xl overflow-hidden shadow-2xl bg-background-dark/80">
                <?php foreach ($getAnimeMovies as $key => $item):
                    $activeClass = ($key == 0) ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none';
                ?>
                    <div class="anime-slide absolute inset-0 transition-all duration-700 ease-in-out <?php echo $activeClass; ?>" data-index="<?php echo $key; ?>">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img loading="lazy" src="<?php echo $item['thumbnail']; ?>" class="w-full h-full object-cover">
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-background-dark via-background-dark/80 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/60 to-transparent"></div>
                        </div>

                        <!-- Content (Adjusted spacing to accommodate overlays) -->
                        <div class="absolute inset-0 flex flex-col justify-start pt-6 sm:pt-12 md:pt-20 px-4 sm:px-6 md:px-16 w-full md:w-3/5 z-20 space-y-1.5 sm:space-y-3 md:space-y-4">
                            <h3 class="text-xl sm:text-2xl md:text-4xl font-black text-white leading-tight drop-shadow-lg animate-fade-in-up">
                                <a href="#" class="hover:text-[#FFD875] transition-colors"><?php echo $item['tittle']; ?></a>
                            </h3>
                            <h4 class="text-base sm:text-lg md:text-xl text-[#FFD875] font-bold tracking-wide opacity-90 line-clamp-1">
                                <?php echo $item['original_tittle']; ?>
                            </h4>

                            <!-- Tags -->
                            <div class="flex flex-wrap items-center gap-2 text-[10px] md:text-xs font-bold">
                                <span class="bg-[#e2b616] text-black px-2 py-0.5 rounded shadow-sm">IMDb <?php echo $item['imdb_rating']; ?></span>
                                <span class="bg-white/10 text-white border border-white/20 px-2 py-0.5 rounded backdrop-blur-sm"><?php echo $item['age']; ?></span>
                                <span class="bg-white/10 text-white border border-white/20 px-2 py-0.5 rounded backdrop-blur-sm"><?php echo $item['release_year']; ?></span>
                                <span class="text-[#FFD875] border border-[#FFD875] px-2 py-0.5 rounded bg-[#FFD700]/10">Tập 10</span>
                            </div>

                            <div class="hidden sm:flex flex-wrap gap-2 text-xs md:text-sm text-gray-400 font-medium">
                                <?php
                                $genres = explode(',', $item['genre_name'] ?? '');
                                foreach ($genres as $g): ?>
                                    <span class="hover:text-white cursor-pointer"><?php echo trim($g); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <p class="hidden sm:block text-gray-300 text-xs md:text-sm line-clamp-2 md:line-clamp-3 leading-relaxed max-w-2xl drop-shadow-md">
                                <?php echo $item['description']; ?>
                            </p>

                            <!-- Buttons -->
                            <div class="flex items-center gap-2 sm:gap-4 pt-2 sm:pt-4">
                                <a href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>" class="group/play w-10 h-10 md:w-12 md:h-12 bg-[#FFD875] hover:bg-[#ffc107] rounded-full flex items-center justify-center text-[#191B24] transition-all hover:scale-110 shadow-[0_0_20px_rgba(255,216,117,0.4)]">
                                    <i data-lucide="play" class="w-4 h-4 md:w-5 md:h-5 fill-current ml-1 group-hover/play:scale-110 transition-transform"></i>
                                </a>
                                <div class="flex gap-3">
                                    <button class="w-8 h-8 md:w-10 md:h-10 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full flex items-center justify-center text-white transition-all hover:scale-105 backdrop-blur-sm js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>">
                                        <i data-lucide="heart" class="w-4 h-4 md:w-5 md:h-5"></i>
                                    </button>
                                    <button class="w-8 h-8 md:w-10 md:h-10 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full flex items-center justify-center text-white transition-all hover:scale-105 backdrop-blur-sm">
                                        <i data-lucide="info" class="w-4 h-4 md:w-5 md:h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Thumbnails Strip -->
            <div class="absolute -bottom-24 sm:-bottom-20 md:-bottom-16 left-0 right-0 z-30 flex justify-center px-2 sm:px-4 w-full">
                <div class="flex gap-2 sm:gap-3 overflow-x-auto no-scrollbar max-w-full pb-2 px-2 sm:px-4 pt-4 sm:pt-6" id="anime-thumbs-list">
                    <?php foreach ($getAnimeMovies as $key => $item):
                        $activeThumb = ($key == 0) ? 'border-[#FFD875] opacity-100 scale-110 -translate-y-4 shadow-2xl z-40' : 'border-transparent opacity-60 hover:opacity-100 hover:-translate-y-2';
                    ?>
                        <div class="anime-thumb flex-shrink-0 w-[50px] h-[75px] sm:w-[60px] sm:h-[90px] md:w-[80px] md:h-[120px] rounded-lg overflow-hidden cursor-pointer border-2 <?php echo $activeThumb; ?> transition-all duration-300 shadow-xl bg-background-dark/50"
                            onclick="changeAnimeSlide(<?php echo $key; ?>)"
                            data-index="<?php echo $key; ?>">
                            <img loading="lazy" src="<?php echo $item['poster_url']; ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Horror section  -->
    <div class="cards-row px-3 sm:px-4 md:px-12 cards-slide wide section-horror pt-8 sm:pt-12 md:pt-16">
        <div class="row-header">
            <h2 class="category-name">
                <span style="color: var(--primary-color);">Cảm Giác Mạnh &</span> Kinh Dị
            </h2>
            <div class="cat-more">
                <a class="line-center" href="#">
                    <span>Xem tất cả</span>
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            </div>
        </div>

        <div class="cards-slide-wrapper">
            <div class="sw-navigation">
                <button type="button" class="sw-button sw-prev-horror">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="sw-button sw-next-horror">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <div class="swiper swiper-horror">
                <div class="swiper-wrapper">
                    <?php foreach ($getHorrorMovies as $key => $item): ?>
                        <div class="swiper-slide">
                            <div class="sw-item">
                                <a class="v-thumbnail" href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>">

                                    <div class="pin-new m-pin-new">
                                        <div class="line-center line-pd">Full HD</div>
                                    </div>
                                    <div class="image-wrapper">
                                        <img loading="lazy" class="movie-thumb" src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['tittle']; ?>">
                                        <div class="play-overlay">
                                            <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';" class="btn-action btn-play"><i class="fa-solid fa-play"></i></div>
                                            <button class="btn-action btn-fav js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-5 h-5"></i></button>
                                        </div>
                                    </div>
                                </a>
                                <div class="info">
                                    <h4 class="item-title lim-1"><a href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>"><?php echo $item['tittle']; ?></a></h4>
                                    <h4 class="alias-title lim-1"><?php echo $item['original_tittle']; ?></h4>
                                    <div class="meta-info">
                                        <span><?php echo $item['release_year']; ?></span> <span class="dot">•</span> <span><?php echo convertMinutesToHours($item['duration']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Love section  -->
    <div class="cards-row px-3 sm:px-4 md:px-12 cards-slide wide section-love pt-12 sm:pt-20 md:pt-28">
        <div class="row-header">
            <h2 class="category-name">
                <span style="color: #FF69B4;">Tình yêu Chảy Nước</span>
            </h2>
            <div class="cat-more">
                <a class="line-center" href="#">
                    <span>Xem tất cả</span>
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            </div>
        </div>

        <div class="cards-slide-wrapper">
            <div class="sw-navigation">
                <button type="button" class="sw-button sw-prev-love">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="sw-button sw-next-love">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <div class="swiper swiper-love">
                <div class="swiper-wrapper">
                    <?php foreach ($getLoveMovies as $key => $item): ?>
                        <div class="swiper-slide">
                            <div class="sw-item">
                                <a class="v-thumbnail" href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>">
                                    <div class="pin-new m-pin-new">
                                        <div class="line-center line-pd">Full HD</div>
                                        <div class="line-center line-tm">Vietsub</div>
                                    </div>
                                    <div class="image-wrapper">
                                        <img loading="lazy" class="movie-thumb" src="<?php echo $item['poster_url']; ?>" alt="<?php echo $item['tittle']; ?>">
                                        <div class="play-overlay">
                                            <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>';" class="btn-action btn-play"><i class="fa-solid fa-play"></i></div>
                                            <button class="btn-action btn-fav js-favorite-btn" data-movie-id="<?php echo $item['id']; ?>"><i data-lucide="heart" class="w-5 h-5"></i></button>
                                        </div>
                                    </div>
                                </a>
                                <div class="info">
                                    <h4 class="item-title lim-1"><a href="<?php echo _HOST_URL; ?>/detail?id=<?php echo $item['id'] ?>"><?php echo $item['tittle']; ?></a></h4>
                                    <h4 class="alias-title lim-1"><?php echo $item['original_tittle']; ?></h4>
                                    <div class="meta-info">
                                        <span><?php echo $item['release_year']; ?></span> <span class="dot">•</span> <span><?php echo convertMinutesToHours($item['duration']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.hero-thumb');
        const heroThumbnailsContainer = document.getElementById('heroThumbnails');

        if (thumbnails.length === 0) return;

        // Các phần tử cần thay đổi trên Hero Section
        const heroBg = document.getElementById('heroBackground');
        const heroTitle = document.getElementById('heroTitle');
        const heroSubtitle = document.getElementById('heroSubtitle');
        const heroDesc = document.getElementById('heroDesc');

        let currentIndex = 0;
        let autoSlideInterval;
        const AUTO_SLIDE_DELAY = 5000; // 5 giây

        // Hàm chuyển slide
        function goToSlide(index) {
            if (index < 0) index = thumbnails.length - 1;
            if (index >= thumbnails.length) index = 0;

            currentIndex = index;
            const targetThumb = thumbnails[index];

            // 1. Xử lý hiệu ứng Active cho thumbnail
            thumbnails.forEach(t => {
                t.style.borderColor = 'transparent';
                t.style.opacity = '0.6';
                t.classList.remove('scale-105');
            });
            targetThumb.style.borderColor = 'white';
            targetThumb.style.opacity = '1';
            targetThumb.classList.add('scale-105');

            // 2. Lấy dữ liệu từ thẻ data-
            const data = {
                bg: targetThumb.getAttribute('data-bg'),
                title: targetThumb.getAttribute('data-title'),
                subtitle: targetThumb.getAttribute('data-subtitle'),
                desc: targetThumb.getAttribute('data-desc')
            };

            // 3. Cập nhật giao diện Hero Section
            if (heroBg && data.bg) {
                heroBg.style.opacity = 0;
                setTimeout(() => {
                    heroBg.style.backgroundImage = `url('${data.bg}')`;
                    heroBg.style.opacity = 1;
                }, 300);
            }

            if (heroTitle && data.title) heroTitle.innerHTML = data.title;
            if (heroSubtitle && data.subtitle) heroSubtitle.textContent = data.subtitle;
            if (heroDesc && data.desc) heroDesc.textContent = data.desc;

            // Scroll thumbnail vào view - chỉ khi Hero Section đang visible
            if (heroThumbnailsContainer) {
                const heroSection = document.querySelector('.relative.h-\\[60vh\\]') || heroBg?.parentElement;
                if (heroSection) {
                    const rect = heroSection.getBoundingClientRect();
                    const isVisible = rect.bottom > 0 && rect.top < window.innerHeight;
                    // Chỉ scroll thumbnail khi Hero Section đang hiển thị trên màn hình
                    if (isVisible) {
                        targetThumb.scrollIntoView({
                            behavior: 'smooth',
                            inline: 'center',
                            block: 'nearest'
                        });
                    }
                }
            }
        }

        // Chuyển slide tiếp theo
        function nextSlide() {
            goToSlide(currentIndex + 1);
        }

        // Chuyển slide trước
        function prevSlide() {
            goToSlide(currentIndex - 1);
        }

        // Bắt đầu auto-slide
        function startAutoSlide() {
            stopAutoSlide();
            autoSlideInterval = setInterval(nextSlide, AUTO_SLIDE_DELAY);
        }

        // Dừng auto-slide
        function stopAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
        }

        // Reset auto-slide (khi người dùng tương tác)
        function resetAutoSlide() {
            stopAutoSlide();
            startAutoSlide();
        }

        // Click vào thumbnail
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', function() {
                goToSlide(index);
                resetAutoSlide();
            });
        });

        // === SWIPE/TOUCH SUPPORT ===
        let touchStartX = 0;
        let touchEndX = 0;
        const heroSection = document.querySelector('.relative.h-\\[60vh\\]') || heroBg?.parentElement;

        if (heroSection) {
            heroSection.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                stopAutoSlide();
            }, {
                passive: true
            });

            heroSection.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                resetAutoSlide();
            }, {
                passive: true
            });
        }

        function handleSwipe() {
            const swipeThreshold = 50; // Minimum swipe distance
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left -> next slide
                    nextSlide();
                } else {
                    // Swipe right -> prev slide
                    prevSlide();
                }
            }
        }

        // Pause auto-slide when page is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoSlide();
            } else {
                startAutoSlide();
            }
        });

        // Pause on hover (desktop)
        if (heroSection) {
            heroSection.addEventListener('mouseenter', stopAutoSlide);
            heroSection.addEventListener('mouseleave', startAutoSlide);
        }

        // Bắt đầu auto-slide
        startAutoSlide();
    });
</script>

</div>

<!-- FOOTER -->
<?php layout('client/footer') ?>