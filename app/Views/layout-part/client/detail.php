<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
$favClass = $movieIsFavorited ? 'is-favorited' : '';

// echo '<pre>';
// print_r($idEpisode);
// echo '</pre>';
// die();
?>

<body class="font-display text-white overflow-x-hidden min-h-screen relative">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute -top-1/4 -left-1/4 size-1/2 rounded-full bg-primary/10 blur-3xl animate-[spin_20s_linear_infinite]"></div>
        <div class="absolute -bottom-1/4 -right-1/4 size-1/2 rounded-full bg-secondary/10 blur-3xl animate-[spin_25s_linear_infinite_reverse]"></div>
    </div>

    <div class="relative min-h-screen w-full flex-col px-4 pb-4 sm:px-6 sm:pb-6 md:px-8 md:pb-8 pt-0">


        <div class="movie-hero-container">
            <div class="hero-image-wrapper">
                <img src="<?php echo $movieDetail['thumbnail']; ?>" alt="<?php echo $movieDetail['tittle']; ?>"
                    class="hero-img">
            </div>

            <div class="hero-gradient-overlay"></div>
        </div>

        <div class="relative z-20 mx-auto max-w-7xl">
            <main class="mt-20 sm:mt-28 md:mt-32 grid grid-cols-1 lg:grid-cols-3 lg:gap-8">
                <!-- Movie Poster -->
                <div class="lg:col-span-1 flex justify-center items-start">
                    <div class="glow-border w-52 sm:w-60 md:w-72 mx-auto sticky top-8">
                        <div class="w-full overflow-hidden aspect-[2/3] rounded-xl bg-[#1a1a2e]">
                            <img src="<?php echo $movieDetail['poster_url']; ?>"
                                alt="<?php echo $movieDetail['tittle']; ?>"
                                class="w-full h-full object-cover"
                                onerror="this.src='<?php echo _HOST_URL; ?>/public/img/no-poster.png';">
                        </div>
                        <div class="flex items-center gap-2">
                        </div>
                    </div>
                </div>

                <!-- Movie Info -->
                <div class="lg:col-span-2 mt-6 sm:mt-8 lg:mt-0 flex flex-col gap-4 sm:gap-6">
                    <div class="glass-panel p-4 sm:p-6 rounded-xl">
                        <h1 class="text-white tracking-light text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                            <?php echo $movieDetail['tittle']; ?></h1>

                        <div class="movie-meta-tags">
                            <div class="glass-tag tag-imdb">
                                IMDb <span><?php echo $movieDetail['imdb_rating']; ?></span>
                            </div>

                            <div class="glass-tag tag-age">T16</div>

                            <div class="glass-tag tag-info">
                                <?php echo $movieDetail['release_year']; ?>
                            </div>

                            <div class="glass-tag tag-info">
                                <?php echo convertMinutesToHours($movieDetail['duration']); ?>
                            </div>
                        </div>

                        <?php
                        $genres = explode(',', $movieDetail['genre_name']);
                        ?>
                        <div class="movie-genres">
                            <?php foreach ($genres as $genre) { ?>
                                <a href="#" class="genre-pill">
                                    <?php echo trim($genre); ?>
                                </a>
                            <?php } ?>
                        </div>

                        <p class="text-white/80 mt-4 text-base leading-relaxed">
                            <?php echo $movieDetail['description']; ?></p>
                        <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-6">
                            <?php
                            // Tạo URL xem phim với episode_id của tập đầu tiên
                            $watchUrl = _HOST_URL . '/watch?id=' . $movieDetail['id'];
                            if (!empty($episodeDetail) && isset($episodeDetail[0]['id'])) {
                                $watchUrl .= '&episode_id=' . $episodeDetail[0]['id'];
                            }
                            ?>
                            <button onclick="window.location.href='<?php echo $watchUrl; ?>'"
                                class="button-glow flex flex-1 sm:flex-none min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-11 sm:h-12 px-4 sm:px-6 bg-primary text-white text-sm sm:text-base font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105">
                                <span class="material-symbols-outlined mr-2 text-xl">play_arrow</span>
                                <span>Xem Ngay</span>
                            </button>
                            <button
                                class="button-glow flex flex-1 sm:flex-none min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-11 sm:h-12 px-4 sm:px-6 bg-white/10 hover:bg-white/20 text-white text-sm sm:text-base font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105 js-favorite-btn <?= $favClass ?>"
                                data-movie-id="<?php echo $movieDetail['id']; ?>">
                                <span class="material-symbols-outlined mr-2 text-xl">favorite</span>
                                <span class="hidden sm:inline">Thêm vào yêu thích</span>
                                <span class="sm:hidden">Yêu thích</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-4">
                            <div class="relative inline-block text-left" id="season-dropdown-container">
                                <?php
                                $isSeries = ($movieDetail['type_id'] == 2);
                                $hasSeasons = ($isSeries && !empty($seasonDetail));
                                //Phim bộ có season
                                if ($hasSeasons): ?>
                                    <?php
                                    $currentDisplayName = "Chọn Phần";
                                    if (!empty($seasonDetail)) {
                                        foreach ($seasonDetail as $item) {
                                            if ($item['id'] == $currentSeasonId) {
                                                $currentDisplayName = $item['name'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <button onclick="toggleSeasonDropdown()"
                                        class="flex items-center gap-2 text-white font-bold text-lg hover:text-primary transition-colors group focus:outline-none">
                                        <span class="material-symbols-outlined text-primary">sort</span>
                                        <span id="current-season-text"><?php echo $currentDisplayName; ?></span>
                                        <span id="season-arrow"
                                            class="material-symbols-outlined text-sm transition-transform duration-300">expand_more</span>
                                    </button>

                                    <div id="season-list"
                                        class="hidden absolute left-0 z-50 mt-2 w-56 origin-top-left rounded-xl bg-[#202331]/95 backdrop-blur-xl border border-white/10 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none transform transition-all duration-200">
                                        <div class="py-1">
                                            <?php foreach ($seasonDetail as $item): ?>
                                                <?php
                                                $isActive = ($item['id'] == $currentSeasonId);
                                                $textClass = $isActive ? 'text-primary font-bold bg-white/5' : 'text-gray-300 hover:bg-white/5';
                                                $iconClass = $isActive ? '' : 'invisible';
                                                ?>
                                                <a href="javascript:void(0)"
                                                    onclick="selectSeason(<?php echo $item['id']; ?>, '<?php echo $item['name']; ?>', this)"
                                                    class="season-item flex items-center justify-between px-4 py-3 text-sm transition-colors <?php echo $textClass; ?>">
                                                    <span><?php echo $item['name']; ?></span>
                                                    <span
                                                        class="season-check material-symbols-outlined text-base <?php echo $iconClass; ?>">check</span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <!-- Phim lẻ -->
                                    <div
                                        class="flex items-center gap-2 text-white font-bold text-lg select-none cursor-default">
                                        <span class="material-symbols-outlined text-primary">video_library</span>
                                        <span>
                                            <?php echo $isSeries ? 'Danh sách tập' : 'Danh sách phát'; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1 max-h-[450px] overflow-y-auto custom-scroll pr-1">
                                <!-- Phim bộ  -->
                                <?php

                                // Phim bộ (type_id = 2) → Grid layout (bất kể có season hay không)
                                // Phim lẻ (type_id != 2) → List layout
                                $layoutClass = ($movieDetail['type_id'] == 2)
                                    ? 'grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-2'
                                    : 'flex flex-col gap-3';
                                ?>

                                <div id="episode-list" class="<?php echo $layoutClass; ?>">

                                    <?php if (!empty($episodeDetail)): ?>

                                        <?php if ($isSeries): ?>
                                            <?php foreach ($episodeDetail as $item): ?>
                                                <a href="<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>&episode_id=<?php echo $item['id']; ?>"
                                                    class="group relative flex items-center justify-center py-2.5 px-2 rounded-lg bg-[#282B3A] border border-white/5 hover:bg-primary hover:border-primary hover:text-[#191B24] transition-all duration-300 text-gray-300 hover:shadow-[0_0_15px_rgba(255,216,117,0.3)]">

                                                    <span class="text-sm font-semibold truncate">
                                                        <?php echo $item['name']; ?>
                                                    </span>
                                                </a>
                                            <?php endforeach; ?>

                                        <?php else: ?>
                                            <?php foreach ($episodeDetail as $item): ?>
                                                <a href="<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>&episode_id=<?php echo $item['id']; ?>"
                                                    class="group relative flex items-center gap-3 p-2 rounded-xl bg-[#282B3A] border border-white/5 hover:bg-[#2F3346] hover:border-primary/50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">

                                                    <div class="relative w-[70px] h-[95px] shrink-0 rounded-lg overflow-hidden border border-white/5">
                                                        <img src="<?php echo $movieDetail['thumbnail']; ?>"
                                                            alt="<?php echo $movieDetail['tittle']; ?>" loading="lazy"
                                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-primary opacity-0 scale-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 text-3xl drop-shadow-md">
                                                            play_circle
                                                        </span>
                                                    </div>

                                                    <div class="flex-1 min-w-0 py-1">
                                                        <div class="flex items-center gap-2 mb-1.5">
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-primary/10 text-primary border border-primary/20">
                                                                <span class="material-symbols-outlined text-[12px]">closed_caption</span>
                                                                <?php echo !empty($item['voice_type']) ? $item['voice_type'] : 'Vietsub'; ?>
                                                            </span>
                                                        </div>
                                                        <h4 class="text-white font-bold text-sm leading-tight truncate group-hover:text-primary transition-colors mb-1">
                                                            <?php echo $movieDetail['tittle']; ?>
                                                        </h4>
                                                        <div class="flex items-center justify-between mt-1">
                                                            <span class="text-xs text-gray-400 font-medium">
                                                                Server: <span class="text-white"><?php echo $item['name']; ?></span>
                                                            </span>
                                                            <span onclick="window.location.href='<?php echo _HOST_URL; ?>/watch?id=<?php echo $movieDetail['id']; ?>&episode_id=<?php echo $item['id']; ?>'"
                                                                class="text-[11px] bg-white/5 text-gray-300 px-2 py-1 rounded group-hover:bg-primary group-hover:text-[#191B24] transition-colors font-bold flex items-center gap-1 cursor-pointer">
                                                                Xem ngay <span class="material-symbols-outlined text-[10px]">arrow_forward</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <h3 class="text-white text-xl font-bold px-2">Diễn viên</h3>
                            <div class="glass-panel p-4 rounded-xl flex-1 max-h-[300px] overflow-y-auto custom-scroll">
                                <div class="space-y-1">
                                    <?php foreach ($getCastByMovieId as $item): ?>

                                        <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/dien_vien/chi_tiet?id=<?php echo $item['id'] ?>';" class="group flex items-center gap-3 p-2 rounded-lg transition-all duration-300 hover:bg-white/10 hover:shadow-lg cursor-pointer hover:scale-[1.02]">

                                            <img class="size-12 rounded-full object-cover border border-white/10 group-hover:border-primary transition-colors duration-300"
                                                src="<?php echo $item['avatar'] ?>"
                                                alt="<?php echo $item['name'] ?>" />

                                            <div>
                                                <p class="text-white font-medium text-sm group-hover:text-primary transition-colors duration-300">
                                                    <?php echo $item['name'] ?>
                                                </p>

                                                <?php if (!empty($item['character_name'])): ?>
                                                    <p class="text-gray-400 text-xs group-hover:text-gray-300 transition-colors duration-300">
                                                        trong vai <?php echo $item['character_name'] ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <div class="mt-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- SIMILAR MOVIES -->
                <div class="lg:col-span-4 flex flex-col gap-6 order-2 lg:order-1">
                    <h3 class="text-white text-xl font-bold pl-2 border-l-4 border-primary">Phim tương tự</h3>

                    <div class="flex flex-col gap-4">
                        <?php foreach ($similarMovies as $movie): ?>
                            <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';"
                                class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                                <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                    <img src="<?php echo $movie['poster_url']; ?>" alt="<?php echo $movie['tittle']; ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                        <?php echo $movie['tittle']; ?></h4>
                                    <p class="text-white/40 text-xs mt-1"><?php echo $movie['original_tittle']; ?></p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span
                                            class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded"><?php echo $movie['imdb_rating']; ?></span>
                                        <span class="text-white/60 text-xs font-medium"><?php echo $movie['release_year']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- ============================================= -->
                <!-- Review & Comments -->
                <!-- ============================================= -->
                <?php
                $data = [
                    'idMovie' => $idMovie,
                    'idEpisode' => $idEpisode,
                    'comments' => $comments,
                    'listComments' => $listComments,
                    'totalComments' => $totalComments,
                    'countAllCommentsByMovie' => $countAllCommentsByMovie,
                ];
                layoutPart('client/comment', $data); ?>
            </div>
            </main>

        </div>
    </div>
</body>
<script>
    // -----------------------------------------------------------------------
    // 1. CÁC HÀM XỬ LÝ GIAO DIỆN (UI)
    // -----------------------------------------------------------------------

    function toggleSeasonDropdown() {
        const dropdown = document.getElementById('season-list');
        const arrow = document.getElementById('season-arrow');

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            dropdown.classList.add('block', 'animate-fade-in-up');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('block');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Sự kiện: Click ra ngoài thì tự đóng menu
    window.addEventListener('click', function(e) {
        const container = document.getElementById('season-dropdown-container');
        // Kiểm tra nếu container tồn tại (phòng trường hợp xem phim lẻ không có nút này)
        if (container && !container.contains(e.target)) {
            const dropdown = document.getElementById('season-list');
            const arrow = document.getElementById('season-arrow');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    });

    // -----------------------------------------------------------------------
    // 2. LOGIC CHỌN SEASON & GỌI API (AJAX)
    // -----------------------------------------------------------------------

    function selectSeason(seasonId, seasonName, element) {
        // A. Cập nhật text hiển thị
        const textElement = document.getElementById('current-season-text');
        if (textElement) textElement.innerText = seasonName;

        // B. Đóng menu
        toggleSeasonDropdown();

        // C. Cập nhật style cho item trong menu
        const allItems = document.querySelectorAll('.season-item');
        allItems.forEach(item => {
            const checkIcon = item.querySelector('.season-check');
            item.classList.remove('text-primary', 'font-bold', 'bg-white/5');
            item.classList.add('text-gray-300', 'hover:bg-white/5');
            if (checkIcon) checkIcon.classList.add('invisible');
        });

        if (element) {
            element.classList.remove('text-gray-300', 'hover:bg-white/5');
            element.classList.add('text-primary', 'font-bold', 'bg-white/5');
            const activeCheck = element.querySelector('.season-check');
            if (activeCheck) activeCheck.classList.remove('invisible');
        }

        // D. Gọi Ajax load lại danh sách tập phim
        loadEpisodes(seasonId);
    }
    // -----------------------------------------------------------------------
    // 3. LOGIC LẤY DANH SÁCH TẬP PHIM (AJAX)
    // -----------------------------------------------------------------------
    function loadEpisodes(seasonId) {
        const listContainer = document.getElementById('episode-list');
        if (!listContainer) return;

        // 1. Hiện thông báo đang tải (dùng col-span-full để căn giữa khung grid)
        listContainer.innerHTML =
            '<div class="col-span-full text-white/50 text-sm py-8 text-center">Đang tải danh sách tập...</div>';

        // 2. Gọi fetch (DÙNG ĐÚNG LINK BẠN YÊU CẦU)
        fetch('./api/get-episodes?season_id=' + seasonId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi kết nối server');
                }
                return response.json();
            })
            .then(data => {
                // 3. Xóa nội dung loading
                listContainer.innerHTML = '';

                if (data && data.length > 0) {
                    let html = '';
                    const hostUrl = '<?php echo _HOST_URL; ?>';
                    const movieId = '<?php echo $movieDetail['id']; ?>'; // ID của phim hiện tại
                    data.forEach(ep => {
                        // --- Tạo HTML nút bấm Grid ---
                        // Sử dụng movieId cho id và ep.id cho episode_id
                        html += `
                            <a href="${hostUrl}/watch?id=${movieId}&episode_id=${ep.id}"
                               class="group relative flex items-center justify-center py-2.5 px-2 rounded-lg bg-[#282B3A] border border-white/5 hover:bg-primary hover:border-primary hover:text-[#191B24] transition-all duration-300 text-gray-300 hover:shadow-[0_0_15px_rgba(255,216,117,0.3)]">
                                
                                <span class="text-sm font-semibold truncate">
                                    ${ep.name}
                                </span>
                            </a>
                        `;
                    });
                    listContainer.innerHTML = html;
                } else {
                    listContainer.innerHTML =
                        '<div class="col-span-full text-gray-400 text-sm py-4 text-center">Chưa có tập phim nào.</div>';
                }
            })
            .catch(err => {
                console.error(err);
                listContainer.innerHTML =
                    '<div class="col-span-full text-red-400 text-sm py-4 text-center">Lỗi tải dữ liệu. Vui lòng thử lại.</div>';
            });
    }
</script>
<!-- FOOTER -->
<?php layout('client/footer') ?>

</html>