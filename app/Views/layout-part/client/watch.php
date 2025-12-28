<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// echo '<pre>';
// print_r($movieDetail);
// echo '</pre>';
// die();

if (isset($_GET['debug']) && $_GET['debug'] == 1) {
    echo "<div style='background:white; color:black; padding:20px; z-index:9999; position:relative;'>";
    echo "<h3>DEBUG DỮ LIỆU TẬP PHIM:</h3>";
    echo "<pre>";
    print_r($episodeDetail);
    echo "</pre>";
    echo "</div>";
    die(); // Dừng trang web để xem
}
?>

<!-- Player CSS -->
<link rel="stylesheet" href="<?php echo _HOST_URL; ?>/public/assets/css/client/player.css">

<div class="relative min-h-screen w-full flex-col overflow-x-hidden">
    <!-- Background Gradients -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-primary/20 rounded-full blur-3xl animate-pulse">
        </div>
        <div
            class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-secondary/20 rounded-full blur-3xl animate-pulse delay-700">
        </div>
    </div>
    <!-- Main Content -->
    <div class="relative z-10 flex h-full grow flex-col">
        <!-- TopNavBar -->
        <!-- Main Content Area -->
        <main class="flex-1 p-3 sm:p-6 md:p-8 lg:p-12 pt-20 sm:pt-24 md:pt-28">
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                <!-- Left Column: Player and Info -->
                <div class="flex-1 flex flex-col gap-4 sm:gap-6">
                    <!-- MediaPlayer -->
                    <div
                        class="relative w-full rounded-xl shadow-2xl shadow-primary/20 border border-primary/50 p-1 bg-black/50 overflow-hidden">
                        <?php
                        // KHỞI TẠO URL MẶC ĐỊNH
                        $movieUrl = '';

                        // LOGIC LẤY LINK
                        if (!empty($episodeDetail) && is_array($episodeDetail)) {

                            // 1. Lấy ID tập đang xem từ URL (nếu có)
                            $currentEpisodeId = isset($_GET['episode_id']) ? $_GET['episode_id'] : null;

                            // Biến tạm để lưu tập đầu tiên
                            $firstEpLink = '';

                            foreach ($episodeDetail as $index => $ep) {
                                // Lấy link từ các key có thể có (source_url hoặc link)
                                $link = isset($ep['source_url']) ? $ep['source_url'] : ($ep['link'] ?? '');

                                // Lưu link tập đầu tiên để dự phòng
                                if ($index === 0) {
                                    $firstEpLink = $link;
                                }

                                // Nếu ID trùng với URL -> Lấy link này và dừng vòng lặp
                                if ($currentEpisodeId && $ep['id'] == $currentEpisodeId) {
                                    $movieUrl = $link;
                                    break;
                                }
                            }

                            // 2. Nếu không tìm thấy link theo ID (hoặc mới vào trang chưa chọn tập)
                            // -> Lấy link của tập đầu tiên
                            if (empty($movieUrl)) {
                                $movieUrl = $firstEpLink;
                            }
                        }

                        // 3. Render Player
                        echo renderMoviePlayer($movieUrl);
                        ?>
                    </div>
                    <!-- Actions and Info panels -->
                    <div class="flex flex-col gap-6">
                        <div class="w-full space-y-6">
                            <!-- Tiêu đề và các nút hành động -->
                            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                                <h1 class="text-white tracking-tight text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold leading-tight flex-1">
                                    <?php echo $movieDetail['tittle']; ?>
                                </h1>
                                <!-- Nút hành động -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <?php
                                    // Nút Tập tiếp theo - chỉ hiển thị cho phim bộ
                                    $isSeries = ($movieDetail['type_id'] == 2);
                                    if ($isSeries && !empty($episodeDetail)):
                                        // Tìm tập tiếp theo
                                        $currentEpId = isset($_GET['episode_id']) ? $_GET['episode_id'] : null;
                                        $nextEpisodeUrl = null;

                                        // Nếu không có episode_id, tập tiếp theo là tập thứ 2 (nếu có)
                                        if (!$currentEpId && count($episodeDetail) > 1) {
                                            $nextEpisodeUrl = "?mod=client&act=watch&id={$idMovie}&episode_id=" . $episodeDetail[1]['id'];
                                        } else {
                                            // Tìm vị trí tập hiện tại và lấy tập tiếp theo
                                            foreach ($episodeDetail as $index => $ep) {
                                                if ($ep['id'] == $currentEpId && isset($episodeDetail[$index + 1])) {
                                                    $nextEpisodeUrl = "?mod=client&act=watch&id={$idMovie}&episode_id=" . $episodeDetail[$index + 1]['id'];
                                                    break;
                                                }
                                            }
                                        }

                                        if ($nextEpisodeUrl):
                                    ?>
                                            <a href="<?php echo $nextEpisodeUrl; ?>" class="group flex items-center justify-center gap-1.5 h-10 px-4 rounded-full bg-primary/20 hover:bg-primary border border-primary/50 hover:border-primary transition-all" title="Tập tiếp theo">
                                                <span class="text-primary group-hover:text-[#191B24] text-sm font-semibold transition-colors">Tập tiếp</span>
                                                <span class="material-symbols-outlined text-primary group-hover:text-[#191B24] text-xl transition-colors">skip_next</span>
                                            </a>
                                    <?php
                                        endif;
                                    endif;
                                    ?>

                                    <button class="group flex items-center justify-center w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 border border-white/10 transition-all js-favorite-btn" data-movie-id="<?php echo $movieDetail['id']; ?>" title="Thêm vào yêu thích">
                                        <span class="material-symbols-outlined text-white/70 group-hover:text-red-500 text-xl transition-colors">favorite</span>
                                    </button>

                                    <button class="group flex items-center justify-center w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 border border-white/10 transition-all" title="Chia sẻ">
                                        <span class="material-symbols-outlined text-white/70 group-hover:text-green-400 text-xl transition-colors">share</span>
                                    </button>

                                    <button class="group flex items-center justify-center w-10 h-10 rounded-full bg-white/5 hover:bg-white/20 border border-white/10 transition-all" title="Báo lỗi">
                                        <span class="material-symbols-outlined text-white/70 group-hover:text-yellow-400 text-xl transition-colors">flag</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Movie Info Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Poster and synopsis -->
                                <div class="md:col-span-2 flex flex-col sm:flex-row gap-3 sm:gap-6 glass-panel p-3 sm:p-4 rounded-xl">
                                    <img class="w-full mx-auto max-w-[180px] sm:w-1/3 sm:max-w-none aspect-[2/3] object-cover rounded-lg"
                                        data-alt="Movie poster for Cybernetic Dreams, showing a robot looking over a futuristic city."
                                        src="<?php echo $movieDetail['poster_url']; ?>" />
                                    <div class="flex-1">
                                        <p class="text-white/80 text-sm leading-relaxed"><?php echo $movieDetail['description']; ?></p>
                                    </div>
                                </div>
                                <!-- Tags and Metadata -->
                                <div class="md:col-span-1 flex flex-col gap-3 sm:gap-4 glass-panel p-3 sm:p-4 rounded-xl">
                                    <h3 class="text-base sm:text-lg font-bold">Chi tiết phim</h3>
                                    <div class="flex gap-2 flex-wrap">
                                        <?php
                                        $genreName = isset($movieDetail['genre_name']) ? $movieDetail['genre_name'] : '';
                                        $genres = !empty($genreName) ? explode(',', $genreName) : [];
                                        ?>
                                        <?php foreach ($genres as $genre): ?>
                                            <div
                                                class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white/10 px-3">
                                                <p class="text-white text-sm font-medium leading-normal"><?php echo trim($genre); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex flex-col gap-3 mt-2 text-white/80">
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">calendar_today</span>
                                            <?php echo $movieDetail['release_year']; ?></div>
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">schedule</span>
                                            <?php echo convertMinutesToHours($movieDetail['duration']); ?></div>
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">public</span> <?php echo $movieDetail['country_name']; ?>
                                        </div>
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">hd</span> 4K
                                            Ultra HD</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Season Dropdown -->
                            <div class="glass-panel bg-white/5 backdrop-blur-md rounded-xl border border-white/10 overflow-hidden">

                                <div class="px-4 py-3 border-b border-white/10 bg-white/5">
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
                                            <div class="flex items-center gap-2 text-white font-bold text-lg select-none cursor-default">
                                                <span class="material-symbols-outlined text-primary">video_library</span>
                                                <span>
                                                    <?php echo $isSeries ? 'Danh sách tập' : 'Danh sách phát'; ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="flex-1 max-h-[450px] overflow-y-auto custom-scroll p-4">
                                    <?php
                                    $layoutClass = $isSeries
                                        ? 'grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 gap-2'
                                        : 'flex flex-col gap-3';
                                    ?>

                                    <div id="episode-list" class="<?php echo $layoutClass; ?>">

                                        <?php if (!empty($episodeDetail)): ?>

                                            <?php if ($isSeries): ?>
                                                <?php
                                                // Lấy episode_id từ URL để xác định tập đang xem
                                                $currentEpisodeIdForHighlight = isset($_GET['episode_id']) ? $_GET['episode_id'] : null;
                                                // Nếu không có episode_id trong URL, mặc định là tập đầu tiên
                                                if (!$currentEpisodeIdForHighlight && !empty($episodeDetail)) {
                                                    $currentEpisodeIdForHighlight = $episodeDetail[0]['id'];
                                                }
                                                ?>
                                                <?php foreach ($episodeDetail as $item): ?>
                                                    <?php
                                                    $isActiveEpisode = ($item['id'] == $currentEpisodeIdForHighlight);
                                                    $activeClass = $isActiveEpisode
                                                        ? 'bg-primary border-primary text-[#191B24] shadow-[0_0_15px_rgba(255,216,117,0.3)]'
                                                        : 'bg-[#282B3A] border border-white/5 hover:bg-primary hover:border-primary hover:text-[#191B24] transition-all duration-300 text-gray-300 hover:shadow-[0_0_15px_rgba(255,216,117,0.3)]';
                                                    ?>
                                                    <a href="?mod=client&act=watch&id=<?php echo $idMovie; ?>&episode_id=<?php echo $item['id']; ?>"
                                                        class="group relative flex items-center justify-center py-2.5 px-2 rounded-lg <?php echo $activeClass; ?>">

                                                        <span class="text-sm font-semibold truncate">
                                                            <?php echo $item['name']; ?>
                                                        </span>
                                                    </a>
                                                <?php endforeach; ?>


                                            <?php else: ?>
                                                <?php foreach ($episodeDetail as $item): ?>
                                                    <a href="?mod=client&act=watch&id=<?php echo $idMovie; ?>&episode_id=<?php echo $item['id']; ?>"
                                                        class="group relative flex items-center gap-3 p-2 rounded-xl bg-[#282B3A] border border-white/5 hover:bg-[#2F3346] hover:border-primary/50 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">

                                                        <div
                                                            class="relative w-[70px] h-[95px] shrink-0 rounded-lg overflow-hidden border border-white/5">
                                                            <img src="<?php echo $movieDetail['thumbnail']; ?>"
                                                                alt="<?php echo $movieDetail['tittle']; ?>" loading="lazy"
                                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                            <div
                                                                class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors">
                                                            </div>
                                                            <span
                                                                class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-primary opacity-0 scale-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 text-3xl drop-shadow-md">
                                                                play_circle
                                                            </span>
                                                        </div>

                                                        <div class="flex-1 min-w-0 py-1">
                                                            <div class="flex items-center gap-2 mb-1.5">
                                                                <span
                                                                    class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-primary/10 text-primary border border-primary/20">
                                                                    <span
                                                                        class="material-symbols-outlined text-[12px]">closed_caption</span>
                                                                    <?php echo !empty($item['voice_type']) ? $item['voice_type'] : 'Vietsub'; ?>
                                                                </span>
                                                            </div>
                                                            <h4
                                                                class="text-white font-bold text-sm leading-tight truncate group-hover:text-primary transition-colors mb-1">
                                                                <?php echo $movieDetail['tittle']; ?>
                                                            </h4>
                                                            <div class="flex items-center justify-between mt-1">
                                                                <span class="text-xs text-gray-400 font-medium">
                                                                    Server: <span class="text-white"><?php echo $item['name']; ?></span>
                                                                </span>
                                                                <span
                                                                    class="text-[11px] bg-white/5 text-gray-300 px-2 py-1 rounded group-hover:bg-primary group-hover:text-[#191B24] transition-colors font-bold flex items-center gap-1">
                                                                    Xem ngay <span
                                                                        class="material-symbols-outlined text-[10px]">arrow_forward</span>
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

                        </div>

                        <!-- Review & Comments -->
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
                </div>
                <!-- Right Sidebar -->
                <aside class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-8">
                    <div class="glass-panel p-4 rounded-xl">
                        <h3 class="font-bold mb-4">Diễn Viên</h3>

                        <div class="flex -space-x-2 p-1">
                            <?php
                            $limit = 5;
                            $totalCast = count($getCastByMovieId);
                            $displayCount = ($totalCast > $limit) ? $limit - 1 : $totalCast;
                            ?>

                            <?php for ($i = 0; $i < $displayCount; $i++): ?>
                                <?php $item = $getCastByMovieId[$i]; ?>
                                <div class="relative size-10 rounded-full bg-cover bg-center border-2 border-background-dark cursor-pointer 
                        transition-all duration-300 ease-out
                        hover:z-20 hover:scale-125 hover:-translate-y-2 hover:border-primary hover:shadow-lg"
                                    style='background-image: url("<?php echo $item['avatar']; ?>");'
                                    title="<?php echo $item['name']; ?>">
                                </div>
                            <?php endfor; ?>

                            <?php if ($totalCast > $limit): ?>
                                <?php $remaining = $totalCast - $displayCount; ?>
                                <div class="relative size-10 rounded-full bg-primary/20 backdrop-blur-sm border-2 border-background-dark 
                        flex items-center justify-center text-xs font-bold text-primary cursor-default z-10
                        transition-colors hover:bg-primary/40">
                                    +<?php echo $remaining; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="glass-panel p-4 rounded-xl">
                        <h3 class="font-bold mb-4">Phim liên quan</h3>
                        <div class="flex flex-col gap-4">
                            <?php foreach ($similarMovies as $movie): ?>
                                <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';" class="flex gap-4 group cursor-pointer">
                                    <img class="w-16 h-24 object-cover rounded-md"
                                        data-alt="Poster for movie 'Quantum Echo'"
                                        src="<?= $movie['poster_url'] ?>" />
                                    <div>
                                        <p class="font-semibold group-hover:text-primary transition-colors"><?= $movie['tittle'] ?></p>
                                        <p class="text-xs text-white/60"><?= $movie['original_tittle'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>
</body>
<!-- MODULE 1: CORE PLAYER + SPEED CONTROL (X2) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // -----------------------------------------------------------------------
        // MODULE 1: CORE PLAYER + SPEED CONTROL (X2)
        // -----------------------------------------------------------------------

        const container = document.querySelector('.video-container[data-player-id]');
        if (!container) return;

        const playerId = container.getAttribute('data-player-id');
        const video = document.getElementById(playerId);
        if (!video) return;

        // --- 1. KÍCH HOẠT HLS ---
        const videoSrc = video.getAttribute('data-src') || video.querySelector('source')?.src;
        if (videoSrc && videoSrc.includes('.m3u8')) {
            if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(videoSrc);
                hls.attachMedia(video);
                hls.on(Hls.Events.ERROR, function(event, data) {
                    if (data.fatal) {
                        switch (data.type) {
                            case Hls.ErrorTypes.NETWORK_ERROR:
                                hls.startLoad();
                                break;
                            case Hls.ErrorTypes.MEDIA_ERROR:
                                hls.recoverMediaError();
                                break;
                            default:
                                hls.destroy();
                                break;
                        }
                    }
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = videoSrc;
            }
        }

        // --- 2. LOGIC TUA NHANH (SPEED) ---

        const HOLD_THRESHOLD = 200;
        let speedTimer = null;
        let isSpeeding = false;
        let preventNextClick = false;
        let spacePressTime = 0;

        function enableSpeed() {
            if (!isSpeeding) {
                isSpeeding = true;
                video.playbackRate = 2.0;
                preventNextClick = true;
                if (video.paused) video.play();
            }
        }

        function disableSpeed() {
            if (speedTimer) {
                clearTimeout(speedTimer);
                speedTimer = null;
            }
            if (isSpeeding) {
                isSpeeding = false;
                video.playbackRate = 1.0;
                if (video.paused) video.play();
            }
        }

        // A. Chuột (PC)
        container.addEventListener('mousedown', function(e) {
            if (e.button === 0) { // Chuột trái
                preventNextClick = false;
                speedTimer = setTimeout(enableSpeed, HOLD_THRESHOLD);
            }
        });
        container.addEventListener('mouseup', disableSpeed);
        container.addEventListener('mouseleave', disableSpeed);

        // Chặn Click Pause sau khi tua
        container.addEventListener('click', function(e) {
            if (preventNextClick) {
                e.preventDefault();
                e.stopPropagation();
                setTimeout(() => {
                    preventNextClick = false;
                }, 50);
            }
        }, true);

        // B. Phím Space (PC)
        document.addEventListener('keydown', function(e) {
            if (e.code === 'Space') {
                const tag = e.target.tagName.toUpperCase();
                if (tag !== 'INPUT' && tag !== 'TEXTAREA') {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!e.repeat) {
                        spacePressTime = Date.now();
                        speedTimer = setTimeout(enableSpeed, HOLD_THRESHOLD);
                    }
                }
            }
        }, true); // Capture Phase

        document.addEventListener('keyup', function(e) {
            if (e.code === 'Space') {
                const tag = e.target.tagName.toUpperCase();
                if (tag !== 'INPUT' && tag !== 'TEXTAREA') {
                    e.preventDefault();
                    e.stopPropagation();
                    if (speedTimer) clearTimeout(speedTimer);

                    if (isSpeeding) {
                        disableSpeed(); // Tắt tua, chạy tiếp
                    } else {
                        // Bấm nhanh -> Toggle Play/Pause
                        if (video.paused) video.play();
                        else video.pause();
                    }
                }
            }
        }, true); // Capture Phase

        // C. Cảm ứng (Mobile)
        container.addEventListener('touchstart', function(e) {
            // Kiểm tra: Nếu chạm vào nút Zoom (được tạo ở script kia) thì không tua
            if (e.target.closest('.btn-zoom-mobile')) return;

            preventNextClick = false;
            speedTimer = setTimeout(enableSpeed, HOLD_THRESHOLD);
        }, {
            passive: true
        });

        container.addEventListener('touchend', disableSpeed);
        container.addEventListener('touchcancel', disableSpeed);
    });
</script>

<!-- MODULE 2: WATCH HISTORY -->
<script>
    // ========================================================================
    // MODULE 2: WATCH HISTORY 
    // ========================================================================
    const MOVIE_ID = <?php echo isset($idMovie) ? (int)$idMovie : 0; ?>;
    const EPISODE_ID = <?php echo isset($idEpisode) && $idEpisode ? (int)$idEpisode : 0; ?>;
    const SEASON_ID = <?php echo isset($currentSeasonId) && $currentSeasonId ? (int)$currentSeasonId : 'null'; ?>;
    const SERVER_START_TIME = <?php echo isset($startTime) ? (float)$startTime : 0; ?>;
    const API_URL = '<?php echo _HOST_URL; ?>/api/save-history';

    console.log("=== INIT VIDEO PLAYER ===");
    console.log("Movie:", MOVIE_ID, "| Episode:", EPISODE_ID);

    document.addEventListener("DOMContentLoaded", function() {
        // Tìm video player bằng selector - hỗ trợ dynamic ID
        const container = document.querySelector('.video-container[data-player-id]');
        const player = container ? document.getElementById(container.getAttribute('data-player-id')) : null;

        if (!player) {
            console.warn("Player not found (iframe mode?)");
            return;
        }

        console.log("✓ Player found:", player.id);

        // ====================================================================
        // 2. WATCH HISTORY - RESUME PLAYBACK
        // ====================================================================
        player.addEventListener('loadedmetadata', function() {
            console.log(" Video duration:", player.duration);

            let seekTime = SERVER_START_TIME;

            // Fallback to localStorage if server returns 0
            if (seekTime <= 0) {
                const localKey = `progress_m${MOVIE_ID}_e${EPISODE_ID}`;
                const localTime = localStorage.getItem(localKey);
                if (localTime) {
                    seekTime = parseFloat(localTime);
                    console.log(" Found localStorage:", seekTime);
                }
            }

            if (seekTime > 0) {
                player.currentTime = seekTime;
                console.log(`Resumed at: ${seekTime}s`);
            }
        });

        // ====================================================================
        // 3. WATCH HISTORY - SYNC TO SERVER
        // ====================================================================
        let lastSyncTime = 0;

        function syncProgressToDB(time) {
            console.log(` Syncing progress: ${time}s`);

            fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        movie_id: MOVIE_ID,
                        episode_id: EPISODE_ID,
                        season_id: SEASON_ID,
                        current_time: time
                    })
                })
                .then(response => {
                    console.log(" API Status:", response.status);
                    return response.text();
                })
                .then(data => {
                    console.log(" Server response:", data);
                })
                .catch(err => console.error(" Sync error:", err));
        }

        // Auto-save every 10 seconds
        player.addEventListener('timeupdate', function() {
            const currentTime = player.currentTime;

            // Always save to localStorage (instant backup)
            localStorage.setItem(`progress_m${MOVIE_ID}_e${EPISODE_ID}`, currentTime);

            // Sync to server every 10s (throttled)
            if (currentTime > 5 && Math.abs(currentTime - lastSyncTime) > 10) {
                syncProgressToDB(currentTime);
                lastSyncTime = currentTime;
            }
        });

        // Save on pause
        player.addEventListener('pause', function() {
            console.log("Paused, saving...");
            syncProgressToDB(player.currentTime);
        });
    });
</script>
<?php
//footer
layout('client/footer');
?>