<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// echo '<pre>';
// print_r($getCinemaMovie);
// echo '</pre>';
// die();
?>



<!-- Hero Section -->
<div class="relative h-[80vh] md:h-[95vh] w-full flex items-center overflow-hidden group transition-all duration-500">
    <?php
    $heroFirst = $getMoviesHeroSection[0];
    ?>
    <!-- Background Image: Hardcoded initial style for immediate rendering -->
    <div id="heroBackground" class="absolute inset-0 bg-cover bg-center transition-all duration-700 ease-in-out" style="background-image: url('<?php echo $heroFirst['thumbnail']; ?>');"></div>

    <!-- Gradients -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#141414] via-[#141414]/60 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-[#141414]/40 to-transparent"></div>


    <div class="relative z-10 px-4 md:px-12 w-full max-w-5xl mt-16 flex flex-col justify-center h-full pb-12">
        <h2 id="heroTitle" class="text-3xl md:text-6xl lg:text-7xl font-bold mb-2 drop-shadow-xl text-white leading-tight uppercase font-heading"><?php echo $heroFirst['tittle']; ?></h2>
        <h3 id="heroSubtitle" class="text-xl md:text-2xl text-yellow-500 font-semibold mb-6 drop-shadow-md"><?php echo $heroFirst['original_tittle']; ?></h3>

        <div id="heroMeta" class="flex flex-wrap items-center gap-3 mb-4 text-sm font-bold text-white">
            <span class="js-meta-imdb bg-[#e2b616] text-black px-1 rounded font-bold">IMDb <?php echo $heroFirst['imdb_rating']; ?></span>
            <span class="js-meta-age bg-red-600 px-1 rounded"><?php echo $heroFirst['age']; ?></span>
            <span class="js-meta-year text-gray-300"><?php echo $heroFirst['release_year']; ?></span>
            <span class="js-meta-duration text-gray-300"><?php echo convertMinutesToHours($heroFirst['duration']); ?></span>
            <span class="js-meta-type bg-gray-700 px-1 rounded text-xs"><?php echo $heroFirst['type_name']; ?></span>
        </div>

        <div id="heroGenres" class="flex flex-wrap items-center gap-3 mb-6 text-sm text-gray-300 font-medium">
            <?php
            $genres = explode(',', $heroFirst['genre_name']);
            foreach ($genres as $genre) { ?>
                <span><?php echo trim($genre); ?></span>
                <span class="text-gray-500 mx-1">•</span>
            <?php } ?>
        </div>

        <p id="heroDesc" class="text-lg text-gray-200 mb-8 drop-shadow-md line-clamp-3 max-w-2xl leading-relaxed"><?php echo $heroFirst['description']; ?></p>
        <div class="flex gap-4">
        </div>
    </div>

    <div class="absolute bottom-8 right-4 md:right-12 z-30 flex gap-4 overflow-x-auto max-w-[90vw] md:max-w-[70vw] pb-2 no-scrollbar pl-4 items-end" id="heroThumbnails">
        <?php foreach ($getMoviesHeroSection as $key => $value): ?>
            <div class="hero-thumb flex-shrink-0 w-[60px] h-[35px] rounded-md overflow-hidden cursor-pointer border-2 <?php echo ($key == 0) ? 'border-white opacity-100 scale-105' : 'border-transparent opacity-60 hover:opacity-100'; ?> transition-all duration-300"
                data-index="<?php echo $key ?>"
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

                <img src="<?php echo $value['thumbnail'] ?>" class="w-full h-full object-cover">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Main Content -->
<div class="relative z-20 -mt-24 pb-20 space-y-8">

    <!-- Category Grid -->
    <div class="w-full py-8 mt-12">
        <h2 class="text-xl md:text-2xl font-bold mb-4 px-4 md:px-8 text-white">Bạn đang quan tâm gì?</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-4 px-4 md:px-8">

            <!-- Card 1 -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[0]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-slate-500 to-slate-700 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[1]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-emerald-500 to-teal-700 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[2]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-purple-500 to-indigo-700 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[3]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-orange-400 to-red-400 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[4]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 6: Cổ Trang -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-red-500 to-rose-700 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[5]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 7: Action (New) -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-red-500 to-pink-600 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-between p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white whitespace-pre-line leading-tight"><?php echo $getGenresGrid[6]['name']; ?></h3>
                    <div class="flex items-center text-xs font-medium text-white/80 group-hover:text-white transition-colors mt-auto">
                        <span>Xem chủ đề</span>
                        <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                    </div>
                </div>
            </div>

            <!-- Card 8: +4 -->
            <div class="relative w-full h-[110px] md:h-[135px] rounded-lg overflow-hidden cursor-pointer hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 bg-gradient-to-br from-gray-600 to-gray-800 shadow-lg group">
                <svg class="absolute bottom-0 right-0 w-full h-full opacity-20 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 Q 25 50 50 100 T 100 100 V 50 Q 75 100 50 50 T 0 50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 90 Q 25 40 50 90 T 100 90 V 40 Q 75 90 50 40 T 0 40 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <path d="M0 80 Q 25 30 50 80 T 100 80 V 30 Q 75 80 50 30 T 0 30 Z" fill="none" stroke="white" stroke-width="0.5" />
                </svg>
                <div class="relative z-10 h-full flex flex-col justify-center items-center p-4">
                    <h3 class="text-lg md:text-xl font-bold text-white text-center">+4 chủ đề</h3>
                </div>
            </div>

        </div>
    </div>

    <!-- Country Movie -->
    <div class="relative z-20 px-4 md:px-12 space-y-12 pb-12">

        <!-- Korea Section -->
        <div class="flex flex-col md:flex-row gap-6 items-start group/section">
            <div class="w-full md:w-64 flex-shrink-0 flex flex-col justify-center md:h-[200px] space-y-3">
                <h3 class="text-2xl md:text-3xl font-bold uppercase leading-tight bg-gradient-to-br from-white to-purple-600 bg-clip-text text-transparent drop-shadow-sm">
                    Phim Hàn<br class="hidden md:block"> Quốc mới
                </h3>
                <a href="/c/phim-han-quoc-moi" class="flex items-center text-gray-400 hover:text-white transition-colors text-sm font-medium group/link">
                    Xem toàn bộ
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="flex-1 w-full overflow-hidden relative group/slider">
                <!-- Navigation Buttons (Overlay) -->
                <button id="koreaLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <button id="koreaRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                <div id="koreaCarousel" class="flex gap-4 overflow-x-auto no-scrollbar pb-4 snap-x scroll-smooth">
                    <?php foreach ($getMoviesKorean as $item): ?>
                        <div class="flex-shrink-0 w-[260px] md:w-[300px] snap-start cursor-pointer group/card">
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden mb-3 border border-white/5 hover:border-white/30 transition-all duration-300 shadow-lg">
                                <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-105 transition-transform duration-500">
                                <div class="absolute top-2 left-2 bg-black/60 backdrop-blur-md border border-white/10 px-2 py-0.5 rounded text-[10px] font-bold text-gray-200">PD: 8</div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <i data-lucide="play-circle" class="w-10 h-10 text-white opacity-80 scale-90 group-hover/card:scale-100 transition-all"></i>
                                </div>
                            </div>
                            <div class="pr-2">
                                <h4 class="text-white font-bold text-sm md:text-base truncate group-hover/card:text-purple-400 transition-colors"><?php echo $item['tittle']; ?></h4>
                                <p class="text-xs text-gray-500 truncate mt-0.5"><?php echo $item['original_tittle']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-800 to-transparent w-full"></div>

        <!-- China Section -->
        <div class="flex flex-col md:flex-row gap-6 items-start group/section">
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
                <!-- Navigation Buttons (Overlay) -->
                <button id="chinaLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <button id="chinaRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                <div id="chinaCarousel" class="flex gap-4 overflow-x-auto no-scrollbar pb-4 snap-x scroll-smooth">
                    <?php foreach ($getMoviesChinese as $item): ?>
                        <div class="flex-shrink-0 w-[260px] md:w-[300px] snap-start cursor-pointer group/card">
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden mb-3 border border-white/5 hover:border-white/30 transition-all duration-300 shadow-lg">
                                <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['tittle']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-105 transition-transform duration-500">
                                <div class="absolute top-2 left-2 bg-black/60 backdrop-blur-md border border-white/10 px-2 py-0.5 rounded text-[10px] font-bold text-gray-200">PD: 26</div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <i data-lucide="play-circle" class="w-10 h-10 text-white opacity-80 scale-90 group-hover/card:scale-100 transition-all"></i>
                                </div>
                            </div>
                            <div class="pr-2">
                                <h4 class="text-white font-bold text-sm md:text-base truncate group-hover/card:text-orange-400 transition-colors"><?php echo $item['tittle']; ?></h4>
                                <p class="text-xs text-gray-500 truncate mt-0.5"><?php echo $item['original_tittle']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-800 to-transparent w-full"></div>

        <!-- US-UK Section -->
        <div class="flex flex-col md:flex-row gap-6 items-start group/section">
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
                <!-- Navigation Buttons (Overlay) -->
                <button id="usukLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>
                <button id="usukRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                <div id="usukCarousel" class="flex gap-4 overflow-x-auto no-scrollbar pb-4 snap-x scroll-smooth">
                    <?php foreach ($getMoviesUSUK as $item): ?>
                        <div class="flex-shrink-0 w-[260px] md:w-[300px] snap-start cursor-pointer group/card">
                            <div class="relative w-full aspect-video rounded-lg overflow-hidden mb-3 border border-white/5 hover:border-white/30 transition-all duration-300 shadow-lg">
                                <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['tittle']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-105 transition-transform duration-500">
                                <div class="absolute top-2 left-2 bg-black/60 backdrop-blur-md border border-white/10 px-2 py-0.5 rounded text-[10px] font-bold text-gray-200">PD: 2</div>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <i data-lucide="play-circle" class="w-10 h-10 text-white opacity-80 scale-90 group-hover/card:scale-100 transition-all"></i>
                                </div>
                            </div>
                            <div class="pr-2">
                                <h4 class="text-white font-bold text-sm md:text-base truncate group-hover/card:text-pink-400 transition-colors"><?php echo $item['tittle']; ?></h4>
                                <p class="text-xs text-gray-500 truncate mt-0.5"><?php echo $item['original_tittle']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="h-[1px] bg-gradient-to-r from-transparent via-gray-800 to-transparent w-full"></div>

    </div>

    <!-- Top 10 Phim Bo Section -->
    <div class="relative z-20 px-4 md:px-12 pb-12 group/top10-series">
        <div class="flex items-center justify-between mb-8 relative">
            <div>
                <h3 class="text-2xl md:text-4xl font-black uppercase text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-yellow-500 to-orange-500 drop-shadow-sm tracking-tight">
                    Top 10 Phim Bộ Hôm Nay
                </h3>
                <p class="text-gray-400 text-sm mt-1 font-medium">Bảng xếp hạng phim bộ được xem nhiều nhất trong ngày</p>
            </div>
        </div>

        <div class="relative group/slider">
            <!-- Navigation Buttons (Overlay) -->
            <button id="top10SeriesLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <button id="top10SeriesRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>

            <div id="top10SeriesCarousel" class="flex gap-8 overflow-x-auto no-scrollbar pb-12 pt-6 snap-x px-4 scroll-smooth">
                <?php
                $rank = 1;
                foreach ($getTopDailyByType2 as $item):
                ?>
                    <div class="relative flex-shrink-0 w-[200px] md:w-[240px] snap-start group/card cursor-pointer">
                        <!-- Rank Number -->
                        <div class="absolute -left-6 -top-4 z-40 select-none pointer-events-none">
                            <span class="font-black text-8xl italic text-transparent bg-clip-text bg-gradient-to-b from-yellow-300 to-yellow-700 drop-shadow-2xl"
                                style="-webkit-text-stroke: 2px rgba(255,255,255,0.05); filter: drop-shadow(4px 4px 6px rgba(0,0,0,0.8)); font-family: 'Arial', sans-serif;">
                                <?php echo $rank++; ?>
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden ring-1 ring-white/10 shadow-[0_0_15px_rgba(0,0,0,0.5)] group-hover/card:ring-yellow-500/50 group-hover/card:shadow-[0_0_30px_rgba(234,179,8,0.3)] transition-all duration-500 transform group-hover/card:-translate-y-2 bg-[#1a1a1a]">
                            <img src="<?php echo $item['thumbnail']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-700 ease-out filter brightness-90 group-hover/card:brightness-110">

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
                                    <i data-lucide="play-circle" class="w-8 h-8 text-white fill-white/20 hover:scale-110 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Top 10 Phim Le Section -->
    <div class="relative z-20 px-4 md:px-12 pb-12 group/top10-movies">
        <div class="flex items-center justify-between mb-8 relative">
            <div>
                <h3 class="text-2xl md:text-4xl font-black uppercase text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-red-600 to-rose-500 drop-shadow-sm tracking-tight">
                    Top 10 Phim Lẻ Hôm Nay
                </h3>
                <p class="text-gray-400 text-sm mt-1 font-medium">Bảng xếp hạng phim lẻ được xem nhiều nhất trong ngày</p>
            </div>
        </div>

        <div class="relative group/slider">
            <!-- Navigation Buttons (Overlay) -->
            <button id="top10MoviesLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <button id="top10MoviesRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>

            <div id="top10MoviesCarousel" class="flex gap-8 overflow-x-auto no-scrollbar pb-12 pt-6 snap-x px-4 scroll-smooth">
                <?php
                $rank = 1;
                foreach ($getTopDailyByType1 as $item):
                ?>
                    <div class="relative flex-shrink-0 w-[200px] md:w-[240px] snap-start group/card cursor-pointer">
                        <!-- Rank Number -->
                        <div class="absolute -left-6 -top-4 z-40 select-none pointer-events-none">
                            <span class="font-black text-8xl italic text-transparent bg-clip-text bg-gradient-to-b from-red-400 to-red-700 drop-shadow-2xl"
                                style="-webkit-text-stroke: 2px rgba(255,255,255,0.05); filter: drop-shadow(4px 4px 6px rgba(0,0,0,0.8)); font-family: 'Arial', sans-serif;">
                                <?php echo $rank++; ?>
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden ring-1 ring-white/10 shadow-[0_0_15px_rgba(0,0,0,0.5)] group-hover/card:ring-red-500/50 group-hover/card:shadow-[0_0_30px_rgba(239,68,68,0.3)] transition-all duration-500 transform group-hover/card:-translate-y-2 bg-[#1a1a1a]">
                            <img src="<?php echo $item['thumbnail']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-700 ease-out filter brightness-90 group-hover/card:brightness-110">

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
                                    <i data-lucide="play-circle" class="w-8 h-8 text-white fill-white/20 hover:scale-110 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Phim Chieu Rap Section -->
    <div class="relative z-20 px-4 md:px-12 py-12 group/theater">

        <div class="flex items-end justify-between mb-8">
            <div class="relative">
                <h3 class="text-3xl md:text-4xl font-black uppercase text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-500 to-red-500 drop-shadow-sm tracking-tighter">
                    Mãn Nhãn Phim Chiếu Rạp
                </h3>
                <div class="h-1 w-1/2 bg-gradient-to-r from-amber-400 to-transparent mt-2 rounded-full"></div>
            </div>
            <a href="#" class="hidden md:flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm font-semibold group/link">
                <span>Xem tất cả</span>
                <i data-lucide="arrow-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="relative group/slider">
            <button id="theaterLeft" class="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-amber-500 hover:text-black text-white flex items-center justify-center rounded-full backdrop-blur-md border border-white/10 transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] opacity-0 group-hover/slider:opacity-100 hidden md:flex transform hover:scale-110">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>

            <div id="theaterCarousel" class="flex gap-6 overflow-x-auto no-scrollbar pb-10 pt-4 snap-x px-2 scroll-smooth">

                <?php foreach ($getCinemaMovie as $movie) : ?>
                    <div class="movie-card-wrapper relative flex-shrink-0 w-[200px] md:w-[240px] snap-start group/card cursor-pointer"
                        data-title="<?php echo $movie['tittle']; ?>" data-year="2024" data-genre="Action" data-image="<?php echo $movie['thumbnail']; ?>" data-desc="Kong và Godzilla phải hợp tác chống lại một mối đe dọa khổng lồ ẩn sâu trong Trái Đất.">

                        <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-[#1a1a1a] ring-1 ring-white/10 group-hover/card:ring-amber-500 transition-all duration-500 shadow-lg group-hover/card:shadow-[0_0_25px_rgba(245,158,11,0.3)]">
                            <img src="<?php echo $movie['thumbnail']; ?>" class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-700 ease-out">

                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-md uppercase tracking-wider">Hot</span>
                                <span class="bg-black/80 backdrop-blur-md text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-500/30">Vietsub</span>
                            </div>

                            <div class="absolute top-2 right-2 bg-amber-400 text-black text-[11px] font-extrabold px-1.5 py-0.5 rounded shadow-md">
                                7.2
                            </div>

                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                <div class="transform translate-y-4 group-hover/card:translate-y-0 transition-transform duration-300 flex flex-col items-center gap-2">
                                    <button class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-black hover:scale-110 transition-transform shadow-lg shadow-amber-500/50 quick-view-btn">
                                        <i data-lucide="play" class="w-5 h-5 fill-current ml-1"></i>
                                    </button>
                                    <span class="text-white text-xs font-bold tracking-wide">Xem Ngay</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 px-1 transition-transform duration-300 group-hover/card:translate-x-1">
                            <h4 class="text-white font-bold text-lg truncate group-hover/card:text-amber-400 transition-colors leading-tight"><?php echo $movie['tittle']; ?></h4>
                            <p class="text-gray-500 text-xs truncate mt-1 font-medium flex items-center gap-2">
                                <span><?php echo $movie['original_tittle']; ?></span>
                                <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                                <span><?php echo $movie['release_year']; ?></span>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button id="theaterRight" class="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-amber-500 hover:text-black text-white flex items-center justify-center rounded-full backdrop-blur-md border border-white/10 transition-all shadow-[0_0_20px_rgba(0,0,0,0.8)] opacity-0 group-hover/slider:opacity-100 hidden md:flex transform hover:scale-110">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <!-- Anime Section (New Design) -->
    <div class="max-w-[1900px] md:px-12 px-5 mx-auto my-12 mb-52 md:mb-64"> <!-- Increased mb to accommodate absolute thumbnails -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-white uppercase tracking-tight">Kho Tàng Anime Mới Nhất</h2>
            <a href="/c/anime" class="flex items-center gap-2 text-gray-400 hover:text-[#FFD875] transition-colors text-sm font-semibold group">
                <span>Xem thêm</span>
                <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="relative w-full group/anime-section">
            <!-- Main Display Area (Reduced Height) -->
            <div id="anime-main-display" class="relative w-full h-[400px] md:h-[500px] rounded-xl overflow-hidden shadow-2xl bg-[#202331]">
                <?php foreach ($getAnimeMovies as $key => $item):
                    $activeClass = ($key == 0) ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none';
                ?>
                    <div class="anime-slide absolute inset-0 transition-all duration-700 ease-in-out <?php echo $activeClass; ?>" data-index="<?php echo $key; ?>">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <img src="<?php echo $item['thumbnail']; ?>" class="w-full h-full object-cover">
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-[#0F111A] via-[#0F111A]/80 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0F111A] via-[#0F111A]/60 to-transparent"></div>
                        </div>

                        <!-- Content (Adjusted spacing to accommodate overlays) -->
                        <div class="absolute inset-0 flex flex-col justify-start pt-16 md:pt-20 px-6 md:px-16 w-full md:w-3/5 z-20 space-y-4">
                            <h3 class="text-2xl md:text-4xl font-black text-white leading-none drop-shadow-lg animate-fade-in-up">
                                <a href="#" class="hover:text-[#FFD875] transition-colors"><?php echo $item['tittle']; ?></a>
                            </h3>
                            <h4 class="text-base md:text-lg text-[#FFD875] font-bold tracking-wide opacity-90">
                                <?php echo $item['original_tittle']; ?>
                            </h4>

                            <!-- Tags -->
                            <div class="flex flex-wrap items-center gap-2 text-[10px] md:text-xs font-bold">
                                <span class="bg-[#e2b616] text-black px-2 py-0.5 rounded shadow-sm">IMDb <?php echo $item['imdb_rating']; ?></span>
                                <span class="bg-white/10 text-white border border-white/20 px-2 py-0.5 rounded backdrop-blur-sm"><?php echo $item['age']; ?></span>
                                <span class="bg-white/10 text-white border border-white/20 px-2 py-0.5 rounded backdrop-blur-sm"><?php echo $item['release_year']; ?></span>
                                <span class="text-[#FFD875] border border-[#FFD875] px-2 py-0.5 rounded bg-[#FFD700]/10">Tập 10</span>
                            </div>

                            <div class="flex flex-wrap gap-2 text-xs md:text-sm text-gray-400 font-medium">
                                <?php
                                $genres = explode(',', $item['genre_name']);
                                foreach ($genres as $g): ?>
                                    <span class="hover:text-white cursor-pointer"><?php echo trim($g); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <p class="text-gray-300 text-xs md:text-sm line-clamp-2 md:line-clamp-3 leading-relaxed max-w-2xl drop-shadow-md">
                                <?php echo $item['description']; ?>
                            </p>

                            <!-- Buttons -->
                            <div class="flex items-center gap-4 pt-4">
                                <a href="#" class="group/play w-10 h-10 md:w-12 md:h-12 bg-[#FFD875] hover:bg-[#ffc107] rounded-full flex items-center justify-center text-[#191B24] transition-all hover:scale-110 shadow-[0_0_20px_rgba(255,216,117,0.4)]">
                                    <i data-lucide="play" class="w-4 h-4 md:w-5 md:h-5 fill-current ml-1 group-hover/play:scale-110 transition-transform"></i>
                                </a>
                                <div class="flex gap-3">
                                    <button class="w-8 h-8 md:w-10 md:h-10 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full flex items-center justify-center text-white transition-all hover:scale-105 backdrop-blur-sm">
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
            <div class="absolute -bottom-16 left-0 right-0 z-30 flex justify-center px-4 w-full">
                <div class="flex gap-3 overflow-x-auto no-scrollbar max-w-full pb-2 px-4 pt-6" id="anime-thumbs-list">
                    <?php foreach ($getAnimeMovies as $key => $item):
                        $activeThumb = ($key == 0) ? 'border-[#FFD875] opacity-100 scale-110 -translate-y-4 shadow-2xl z-40' : 'border-transparent opacity-60 hover:opacity-100 hover:-translate-y-2';
                    ?>
                        <div class="anime-thumb flex-shrink-0 w-[60px] h-[90px] md:w-[80px] md:h-[120px] rounded-lg overflow-hidden cursor-pointer border-2 <?php echo $activeThumb; ?> transition-all duration-300 shadow-xl bg-[#1a1a1a]"
                            onclick="changeAnimeSlide(<?php echo $key; ?>)"
                            data-index="<?php echo $key; ?>">
                            <img src="<?php echo $item['thumbnail']; ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Section -->
    <div class="px-4 md:px-12 group/carousel pt-40">
        <h3 class="text-xl font-bold mb-4 text-white">Trending Now</h3>

        <div class="relative">
            <!-- Left Button -->
            <button id="trendingLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/carousel:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>

            <div id="trendingCarousel" class="flex gap-4 overflow-x-auto no-scrollbar pb-4">

                <!-- Movie 1 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Stranger Things"
                    data-year="2022"
                    data-genre="Sci-Fi"
                    data-image="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=800&auto=format&fit=crop"
                    data-desc="When a young boy vanishes, a small town uncovers a mystery involving secret experiments, terrifying supernatural forces, and one strange little girl.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2022</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Sci-Fi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Stranger Things</h4>
                    <p class="text-xs text-gray-400">Sci-Fi • 2022</p>
                </div>

                <!-- Movie 2 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Neon Racer"
                    data-year="2024"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?q=80&w=800&auto=format&fit=crop"
                    data-desc="In a cyberpunk future, an underground street racer must participate in a deadly cross-country competition to save his sister from a ruthless crime syndicate.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2024</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Neon Racer</h4>
                    <p class="text-xs text-gray-400">Action • 2024</p>
                </div>

                <!-- Movie 3 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="The Dark Knight"
                    data-year="2008"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1596727147705-54a9d0c2094c?q=80&w=800&auto=format&fit=crop"
                    data-desc="When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1596727147705-54a9d0c2094c?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2008</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">The Dark Knight</h4>
                    <p class="text-xs text-gray-400">Action • 2008</p>
                </div>

                <!-- Movie 4 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Interstellar"
                    data-year="2014"
                    data-genre="Sci-Fi"
                    data-image="https://images.unsplash.com/photo-1534809027769-b00d750a6bac?q=80&w=800&auto=format&fit=crop"
                    data-desc="A team of explorers travel through a wormhole in space in an attempt to ensure humanity's survival as Earth becomes uninhabitable.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1534809027769-b00d750a6bac?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2014</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Sci-Fi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Interstellar</h4>
                    <p class="text-xs text-gray-400">Sci-Fi • 2014</p>
                </div>

                <!-- Movie 5 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Avengers: Endgame"
                    data-year="2019"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1616530940355-351fabd9524b?q=80&w=800&auto=format&fit=crop"
                    data-desc="After the devastating events of Infinity War, the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos' actions and restore balance to the universe.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1616530940355-351fabd9524b?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2019</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Avengers: Endgame</h4>
                    <p class="text-xs text-gray-400">Action • 2019</p>
                </div>

                <!-- Movie 6 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="The Matrix"
                    data-year="1999"
                    data-genre="Sci-Fi"
                    data-image="https://images.unsplash.com/photo-1509347528160-9a9e33742cd4?q=80&w=800&auto=format&fit=crop"
                    data-desc="When a beautiful stranger leads computer hacker Neo to a forbidding underworld, he discovers the shocking truth--the life he knows is the elaborate deception of an evil cyber-intelligence.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1509347528160-9a9e33742cd4?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">1999</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Sci-Fi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">The Matrix</h4>
                    <p class="text-xs text-gray-400">Sci-Fi • 1999</p>
                </div>

                <!-- Movie 7 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Cyberpunk 2077"
                    data-year="2077"
                    data-genre="Sci-Fi"
                    data-image="https://images.unsplash.com/photo-1512149177596-f817c7ef5d4c?q=80&w=800&auto=format&fit=crop"
                    data-desc="A mercenary outlaw chases a one-of-a-kind implant that is the key to immortality in the vast, open-world megalopolis of Night City.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1512149177596-f817c7ef5d4c?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2077</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Sci-Fi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Cyberpunk 2077</h4>
                    <p class="text-xs text-gray-400">Sci-Fi • 2077</p>
                </div>

                <!-- Movie 8 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Gravity"
                    data-year="2013"
                    data-genre="Sci-Fi"
                    data-image="https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=800&auto=format&fit=crop"
                    data-desc="Two astronauts work together to survive after an accident leaves them stranded in space.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2013</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Sci-Fi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Gravity</h4>
                    <p class="text-xs text-gray-400">Sci-Fi • 2013</p>
                </div>

            </div>

            <!-- Right Button -->
            <button id="trendingRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/carousel:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <!-- Phim Điện Ảnh Section -->
    <div class="px-4 md:px-12 pb-12 group/carousel">
        <h3 class="text-xl font-bold mb-4 text-white">Phim Điện Ảnh</h3>

        <div class="relative">
            <!-- Left Button -->
            <button id="cinemaLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/carousel:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -ml-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>

            <div id="cinemaCarousel" class="flex gap-4 overflow-x-auto no-scrollbar pb-4">

                <!-- Movie 1 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Oppenheimer"
                    data-year="2023"
                    data-genre="History"
                    data-image="https://images.unsplash.com/photo-1533613220915-609f661a6fe1?q=80&w=800&auto=format&fit=crop"
                    data-desc="The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1533613220915-609f661a6fe1?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2023</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">History</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Oppenheimer</h4>
                    <p class="text-xs text-gray-400">History • 2023</p>
                </div>

                <!-- Movie 2 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Avatar: The Way of Water"
                    data-year="2022"
                    data-genre="Sci-Fi"
                    data-image="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=800&auto=format&fit=crop"
                    data-desc="Jake Sully lives with his newfound family formed on the extrasolar moon Pandora. Once a familiar threat returns to finish what was previously started, Jake must work with Neytiri and the army of the Na'vi race to protect their home.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2022</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Sci-Fi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Avatar: The Way of Water</h4>
                    <p class="text-xs text-gray-400">Sci-Fi • 2022</p>
                </div>

                <!-- Movie 3 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Top Gun: Maverick"
                    data-year="2022"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1442544213729-6a15f1611937?q=80&w=800&auto=format&fit=crop"
                    data-desc="After thirty years, Maverick is still pushing the envelope as a top naval aviator, but must confront ghosts of his past when he leads TOP GUN's elite graduates on a mission that demands the ultimate sacrifice from those chosen to fly it.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1442544213729-6a15f1611937?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2022</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Top Gun: Maverick</h4>
                    <p class="text-xs text-gray-400">Action • 2022</p>
                </div>

                <!-- Movie 4 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Spider-Man: Across the Spider-Verse"
                    data-year="2023"
                    data-genre="Animation"
                    data-image="https://images.unsplash.com/photo-1635805737707-575885ab0820?q=80&w=800&auto=format&fit=crop"
                    data-desc="Miles Morales catapults across the Multiverse, where he encounters a team of Spider-People charged with protecting its very existence.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1635805737707-575885ab0820?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2023</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Animation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Spider-Man: Across the Spider-Verse</h4>
                    <p class="text-xs text-gray-400">Animation • 2023</p>
                </div>

                <!-- Movie 5 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="The Batman"
                    data-year="2022"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1509347528160-9a9e33742cd4?q=80&w=800&auto=format&fit=crop"
                    data-desc="When the Riddler, a sadistic serial killer, begins murdering key political figures in Gotham, Batman is forced to investigate the city's hidden corruption and question his family's involvement.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1509347528160-9a9e33742cd4?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2022</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">The Batman</h4>
                    <p class="text-xs text-gray-400">Action • 2022</p>
                </div>

                <!-- Movie 6 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="John Wick: Chapter 4"
                    data-year="2023"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1542259681-d3d6232695c0?q=80&w=800&auto=format&fit=crop"
                    data-desc="John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy with powerful alliances across the globe.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1542259681-d3d6232695c0?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2023</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">John Wick: Chapter 4</h4>
                    <p class="text-xs text-gray-400">Action • 2023</p>
                </div>

                <!-- Movie 7 -->
                <div class="movie-card-wrapper flex-shrink-0 w-[150px] md:w-[200px] group cursor-pointer transition-all duration-300 hover:-translate-y-2 hover:shadow-xl rounded-lg"
                    data-title="Fast X"
                    data-year="2023"
                    data-genre="Action"
                    data-image="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=800&auto=format&fit=crop"
                    data-desc="Dom Toretto and his family are targeted by the vengeful son of drug kingpin Hernan Reyes.">
                    <div class="w-full h-[225px] md:h-[300px] rounded-md overflow-hidden mb-2 relative">
                        <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center">
                            <i data-lucide="play-circle" class="w-12 h-12 text-white fill-white/20 mb-2 transition-transform duration-300 group-hover:scale-110"></i>
                            <div class="flex items-center gap-2 mb-6">
                                <button class="quick-view-btn px-4 py-1.5 bg-gray-500/70 hover:bg-gray-500/90 text-white text-xs font-bold rounded-full flex items-center gap-1 transition-colors backdrop-blur-sm">
                                    <i data-lucide="info" class="w-3 h-3"></i> Quick View
                                </button>
                                <button class="p-2 bg-gray-500/70 hover:bg-gray-500/90 text-white rounded-full transition-colors backdrop-blur-sm group/fav" aria-label="Add to Favorites">
                                    <i data-lucide="heart" class="w-4 h-4 group-hover/fav:fill-red-500 group-hover/fav:text-red-500 transition-colors"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 pb-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-green-400 font-bold text-sm shadow-black drop-shadow-md">2023</span>
                                    <span class="px-2 py-0.5 border border-white/30 rounded text-[10px] font-bold text-white uppercase tracking-wider bg-white/10 backdrop-blur-sm shadow-sm">Action</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-white font-medium text-sm md:text-base truncate">Fast X</h4>
                    <p class="text-xs text-gray-400">Action • 2023</p>
                </div>

            </div>

            <!-- Right Button -->
            <button id="cinemaRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-50 w-12 h-12 bg-black/60 hover:bg-black/90 text-white flex items-center justify-center rounded-full backdrop-blur-sm transition-all shadow-[0_0_20px_rgba(0,0,0,0.5)] hover:scale-110 opacity-0 group-hover/carousel:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed -mr-6 border border-white/10 hidden md:flex">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>
        </div>
    </div>
</div>

<!-- Movie Details Modal -->
<div id="movieModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modalTitle" aria-describedby="modalDesc" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/80 transition-opacity backdrop-blur-sm" id="modalBackdrop"></div>

    <!-- Panel -->
    <div class="flex h-full items-center justify-center p-4">
        <div class="relative w-full max-w-3xl overflow-hidden rounded-xl bg-[#181818] text-left shadow-2xl ring-1 ring-white/10 transition-all">
            <!-- Close Button -->
            <button id="closeModalBtn" aria-label="Close movie details" class="absolute top-4 right-4 z-20 p-2 rounded-full bg-black/50 hover:bg-black/70 text-white transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            <!-- Image Header -->
            <div class="relative h-[350px] w-full">
                <img id="modalImage" src="" alt="Movie Cover" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#181818] via-[#181818]/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full">
                    <h2 id="modalTitle" class="text-4xl font-bold text-white mb-3 drop-shadow-lg"></h2>
                    <div class="flex items-center gap-4 text-sm text-gray-300">
                        <span id="modalYear" class="font-semibold text-green-400"></span>
                        <span id="modalGenre" class="px-2 py-0.5 border border-gray-500 rounded text-xs uppercase tracking-wider"></span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 pb-8 pt-4">
                <div class="flex gap-4 mb-6">
                    <button class="flex-1 bg-white text-black font-bold py-3 px-6 rounded hover:bg-gray-200 transition-colors flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        <i data-lucide="play" class="w-5 h-5 fill-current"></i> Play
                    </button>
                    <button class="flex-1 bg-gray-600/60 text-white font-bold py-3 px-6 rounded hover:bg-gray-600/80 transition-colors flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                        <i data-lucide="plus" class="w-5 h-5"></i> My List
                    </button>
                </div>
                <p id="modalDesc" class="text-gray-300 leading-relaxed text-base md:text-lg"></p>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.hero-thumb');

        // Các phần tử cần thay đổi trên Hero Section
        const heroBg = document.getElementById('heroBackground');
        const heroTitle = document.getElementById('heroTitle');
        const heroSubtitle = document.getElementById('heroSubtitle');
        const heroDesc = document.getElementById('heroDesc');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // 1. Xử lý hiệu ứng Active cho thumbnail
                thumbnails.forEach(t => {
                    t.style.borderColor = 'transparent'; // Bỏ viền cũ
                    t.style.opacity = '0.6'; // Làm mờ cũ
                    t.classList.remove('scale-105'); // Bỏ phóng to
                });
                this.style.borderColor = 'white'; // Viền trắng cho cái đang chọn
                this.style.opacity = '1'; // Sáng lên
                this.classList.add('scale-105'); // Phóng to nhẹ

                // 2. Lấy dữ liệu từ thẻ data-
                const data = {
                    bg: this.getAttribute('data-bg'),
                    title: this.getAttribute('data-title'),
                    subtitle: this.getAttribute('data-subtitle'),
                    desc: this.getAttribute('data-desc')
                };

                // 3. Cập nhật giao diện Hero Section
                // Đổi ảnh nền (có hiệu ứng mờ nhẹ)
                if (heroBg && data.bg) {
                    heroBg.style.opacity = 0;
                    setTimeout(() => {
                        heroBg.style.backgroundImage = `url('${data.bg}')`;
                        heroBg.style.opacity = 1;
                    }, 300);
                }

                // Đổi TEXT thông tin
                if (heroTitle && data.title) heroTitle.innerHTML = data.title;
                if (heroSubtitle && data.subtitle) heroSubtitle.textContent = data.subtitle;
                if (heroDesc && data.desc) heroDesc.textContent = data.desc;
            });
        });
    });
</script>

<!-- FOOTER -->
<?php layout('client/footer') ?>