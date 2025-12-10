<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// echo '<pre>';
// print_r($getCastByMovieId);
// echo '</pre>';
// die();
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Streamscape - Movie Details</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
    .glass-panel {
        background-color: rgba(26, 26, 26, 0.6);
        /* #1A1A1A with 60% opacity */
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        /* For Safari */
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .glow-border {
        position: relative;
    }

    .glow-border::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 1rem;
        /* Matches rounded-xl */
        background: conic-gradient(from 180deg at 50% 50%, #00C2FF 0deg, #E040FB 180deg, #00C2FF 360deg);
        z-index: -1;
        filter: blur(20px);
        animation: rotate-glow 8s linear infinite;
    }

    .trailer-glow::before {
        border-radius: 0.75rem;
        /* Matches rounded-lg */
    }

    @keyframes rotate-glow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .button-glow {
        position: relative;
        overflow: hidden;
    }

    .button-glow::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: radial-gradient(circle, rgba(37, 140, 244, 0.4) 0%, rgba(37, 140, 244, 0) 60%);
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
        z-index: 0;
        opacity: 0;
    }

    .button-glow:hover::before {
        opacity: 1;
    }

    .button-glow>* {
        position: relative;
        z-index: 1;
    }

    /* Custom scrollbar for Cast/Comments if needed */
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    /* Container chính cho phần nền */
    .movie-hero-container {
        position: relative;
        /* Thêm margin âm để bù lại padding của body nếu cần thiết, hoặc để width: 100vw */
        width: 100vw;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;

        height: 80vh;
        overflow: hidden;
        margin-bottom: -200px;
        /* Kéo nội dung lên */
        z-index: 0;
    }

    /* Phần bao quanh ảnh để xử lý animation */
    .hero-image-wrapper {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    /* Ảnh nền */
    .hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Đảm bảo ảnh không bị méo */
        object-position: center top;
        /* Căn ảnh lấy phần trên làm trọng tâm */
        transition: transform 3s ease;
        /* Hiệu ứng chuyển động mượt trong 3 giây */
    }

    /* Hiệu ứng khi di chuột vào vùng banner: Zoom nhẹ lên */
    .movie-hero-container:hover .hero-img {
        transform: scale(1.1);
        /* Phóng to 10% */
    }

    /* Lớp phủ Gradient (Làm mờ ảnh để hòa vào nền đen) */
    .hero-gradient-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Gradient từ trong suốt -> đen, và từ trái -> phải */
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, #000 100%),
            linear-gradient(to right, #000 0%, rgba(0, 0, 0, 0) 50%);
        pointer-events: none;
        /* Để chuột vẫn click được vào các nút bên dưới nếu bị che */
        z-index: 1;
    }

    /* Container cho các thẻ tags (Hàng 1) */
    .movie-meta-tags {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
        margin-bottom: 16px;
    }

    /* Base style cho tất cả các thẻ kính */
    .glass-tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 12px;
        border-radius: 8px;
        /* Bo góc mềm mại */
        font-size: 14px;
        font-weight: 600;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: all 0.3s ease;
    }

    /* 1. Thiết kế thẻ IMDb (Premium Gold) */
    .tag-imdb {
        background: rgba(255, 216, 117, 0.1);
        /* Màu primary mờ */
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        box-shadow: 0 0 10px rgba(255, 216, 117, 0.15);
        /* Glow nhẹ */
    }

    .tag-imdb span {
        color: #fff;
        margin-left: 6px;
        font-weight: 700;
    }

    /* 2. Thiết kế thẻ Độ tuổi (T16) - Nổi bật */
    .tag-age {
        background: rgba(255, 255, 255, 0.9);
        color: #000;
        font-weight: 800;
        border: 1px solid #fff;
    }

    /* 3. Thiết kế Năm & Thời lượng (Glass Classic) */
    .tag-info {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #e0e0e0;
    }

    .tag-info:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Container cho Thể loại (Hàng 2) */
    .movie-genres {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
    }

    /* Thiết kế thẻ Thể loại */
    .genre-pill {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(30, 33, 48, 0.6);
        /* --top-bg-default mờ */
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-base);
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }

    .genre-pill:hover {
        background: rgba(255, 216, 117, 0.15);
        border-color: var(--primary-color);
        color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
    <script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#258cf4",
                    "background-light": "#ffffff",
                    "background-dark": "#000000",
                },
                fontFamily: {
                    "display": ["Space Grotesk", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
            },
        },
    }
    </script>

    <div class="relative min-h-screen w-full flex-col overflow-x-hidden px-4 pb-4 sm:px-6 sm:pb-6 md:px-8 md:pb-8 pt-0">
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div
                class="absolute -top-1/4 -left-1/4 size-1/2 rounded-full bg-cyan-500/10 blur-3xl animate-[spin_20s_linear_infinite]">
            </div>
            <div
                class="absolute -bottom-1/4 -right-1/4 size-1/2 rounded-full bg-fuchsia-500/10 blur-3xl animate-[spin_25s_linear_infinite_reverse]">
            </div>
        </div>

        <div class="movie-hero-container">
            <div class="hero-image-wrapper">
                <img src="<?php echo $movieDetail['thumbnail']; ?>" alt="<?php echo $movieDetail['tittle']; ?>"
                    class="hero-img">
            </div>

            <div class="hero-gradient-overlay"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl">
            <main class="mt-8 grid grid-cols-1 lg:grid-cols-3 lg:gap-8">
                <!-- Movie Poster -->
                <div class="lg:col-span-1 flex justify-center items-start">
                    <div class="glow-border w-72 mx-auto sticky top-8">
                        <div class="w-full bg-center bg-no-repeat bg-cover flex flex-col justify-end overflow-hidden aspect-[2/3] rounded-xl"
                            data-alt="<?php echo $movieDetail['tittle']; ?>"
                            style='background-image: url("<?php echo $movieDetail['poster_url']; ?>");'></div>
                        <div class="flex items-center gap-2">
                        </div>
                    </div>
                </div>

                <!-- Movie Info -->
                <div class="lg:col-span-2 mt-8 lg:mt-0 flex flex-col gap-6">
                    <div class="glass-panel p-6 rounded-xl">
                        <h1 class="text-white tracking-light text-4xl md:text-5xl font-bold leading-tight">
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
                        <div class="flex flex-wrap gap-3 mt-6">
                            <button
                                class="button-glow flex flex-1 sm:flex-none min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105">
                                <span class="material-symbols-outlined mr-2">play_arrow</span>
                                <span class="truncate">Xem Ngay</span>
                            </button>
                            <button
                                class="button-glow flex flex-1 sm:flex-none min-w-[84px] items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-white/10 hover:bg-white/20 text-white text-base font-bold leading-normal tracking-[0.015em] transition-transform hover:scale-105">
                                <span class="material-symbols-outlined mr-2">add</span>
                                <span class="truncate">Thêm vào yêu thích</span>
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
                                $layoutClass = $isSeries
                                    ? 'grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-2'
                                    : 'flex flex-col gap-3';
                                ?>

                                <div id="episode-list" class="<?php echo $layoutClass; ?>">

                                    <?php if (!empty($episodeDetail)): ?>

                                    <?php if ($isSeries): ?>
                                    <?php foreach ($episodeDetail as $item): ?>
                                    <a href="?mod=client&act=watch&id=<?php echo $item['id']; ?>"
                                        class="group relative flex items-center justify-center py-2.5 px-2 rounded-lg bg-[#282B3A] border border-white/5 hover:bg-primary hover:border-primary hover:text-[#191B24] transition-all duration-300 text-gray-300 hover:shadow-[0_0_15px_rgba(255,216,117,0.3)]">

                                        <span class="text-sm font-semibold truncate">
                                            <?php echo $item['name']; ?>
                                        </span>
                                    </a>
                                    <?php endforeach; ?>

                                    <?php else: ?>
                                    <?php foreach ($episodeDetail as $item): ?>
                                    <a href="?mod=client&act=watch&id=<?php echo $item['id']; ?>"
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

                        <div class="flex flex-col gap-6">
                            <h3 class="text-white text-xl font-bold px-2">Cast</h3>
                            <div class="glass-panel p-4 rounded-xl flex-1 max-h-[300px] overflow-y-auto custom-scroll">
                                <div class="space-y-4">
                                    <?php foreach ($getCastByMovieId as $item): ?>
                                    <div class="flex items-center gap-3">
                                        <img class="size-12 rounded-full object-cover border border-white/10"
                                            src="<?php echo $item['avatar'] ?>" alt="<?php echo $item['name'] ?>" />
                                        <div>
                                            <p class="text-white font-medium text-sm"><?php echo $item['name'] ?></p>

                                            <?php if (!empty($item['character_name'])): ?>
                                            <p class="text-gray-400 text-xs">trong vai
                                                <?php echo $item['character_name'] ?>
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
                    <h3 class="text-white text-xl font-bold pl-2 border-l-4 border-primary">Similar Movies</h3>

                    <div class="flex flex-col gap-4">

                        <div
                            class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                            <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                <img src="https://image.tmdb.org/t/p/w200/qA5kPYZA7FkVvqcEfJRoOy4kpHg.jpg" alt="Poster"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    Nova Drift</h4>
                                <p class="text-white/40 text-xs mt-1">Drifting into the void</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span
                                        class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded">9.2</span>
                                    <span class="text-white/60 text-xs font-medium">2023</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                            <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                <img src="https://image.tmdb.org/t/p/w200/t6HIqrRAclMCA60cKCwd5ishZvS.jpg" alt="Poster"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    The Void Walker</h4>
                                <p class="text-white/40 text-xs mt-1">Action • Sci-Fi</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span
                                        class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded">8.5</span>
                                    <span class="text-white/60 text-xs font-medium">2022</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                            <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                <img src="https://image.tmdb.org/t/p/w200/8Cd164V76A96C2W2582Q6M0Vl67.jpg" alt="Poster"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    Chrome Souls</h4>
                                <p class="text-white/40 text-xs mt-1">Cyberpunk • Thriller</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span
                                        class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded">7.4</span>
                                    <span class="text-white/60 text-xs font-medium">2024</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                            <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                <img src="https://image.tmdb.org/t/p/w200/3bhkrj58Vtu7enYsRolD1fZdja1.jpg" alt="Poster"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    Helios Rising</h4>
                                <p class="text-white/40 text-xs mt-1">Adventure • Space</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span
                                        class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded">6.9</span>
                                    <span class="text-white/60 text-xs font-medium">2021</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex gap-4 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-white/5 transition-colors items-center">
                            <div class="w-20 shrink-0 aspect-[2/3] rounded-lg overflow-hidden relative shadow-lg">
                                <img src="https://image.tmdb.org/t/p/w200/kDp1vUBnMpe8ak4rjgl3cLELqjU.jpg" alt="Poster"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-white font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    Quantum Leap</h4>
                                <p class="text-white/40 text-xs mt-1">Mystery • Drama</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span
                                        class="text-primary text-xs font-bold bg-primary/10 border border-primary/20 px-1.5 py-0.5 rounded">8.0</span>
                                    <span class="text-white/60 text-xs font-medium">2020</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="lg:col-span-8 flex flex-col gap-6 order-1 lg:order-2">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-white text-xl font-bold">Reviews & Comments</h3>
                        <span class="text-white/60 text-sm">14 comments</span>
                    </div>

                    <div class="glass-panel p-6 rounded-xl">
                        <div class="flex gap-4 mb-8">
                            <div
                                class="size-10 rounded-full bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                ME</div>
                            <div class="flex-1">
                                <form onsubmit="event.preventDefault();">
                                    <textarea
                                        class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white placeholder-white/40 focus:ring-1 focus:ring-primary focus:border-primary text-sm transition-colors resize-none"
                                        rows="3" placeholder="Write your review about this movie..."></textarea>
                                    <div class="flex justify-between items-center mt-3">
                                        <div class="flex gap-1">
                                            <button
                                                class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                            <button
                                                class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                            <button
                                                class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                            <button
                                                class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                            <button
                                                class="text-white/20 material-symbols-outlined text-[20px]">star</button>
                                        </div>
                                        <button type="submit"
                                            class="bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded-lg text-sm font-bold transition-transform active:scale-95">Post
                                            Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <hr class="border-white/10 mb-8">

                        <div class="space-y-8">
                            <div class="flex gap-4 group">
                                <img src="https://i.pravatar.cc/150?u=user1"
                                    class="size-10 rounded-full border border-white/10 shrink-0">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-white font-bold text-sm">John Doe</h4>
                                        <span class="text-white/40 text-xs">2 hours ago</span>
                                    </div>
                                    <div class="flex text-yellow-500 text-[14px] mb-2">
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                    </div>
                                    <p class="text-white/80 text-sm leading-relaxed">Absolutely stunning visuals! The
                                        story felt a bit rushed in the second act, but the ending made up for it.
                                        Definitely watching this again in IMAX.</p>
                                    <div class="flex gap-4 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            class="text-white/40 hover:text-primary text-xs flex items-center gap-1"><span
                                                class="material-symbols-outlined text-[14px]">thumb_up</span> Helpful
                                            (2)</button>
                                        <button class="text-white/40 hover:text-white text-xs">Reply</button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4 group">
                                <img src="https://i.pravatar.cc/150?u=user2"
                                    class="size-10 rounded-full border border-white/10 shrink-0">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-white font-bold text-sm">Alice Smith</h4>
                                        <span class="text-white/40 text-xs">1 day ago</span>
                                    </div>
                                    <div class="flex text-yellow-500 text-[14px] mb-2">
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="material-symbols-outlined text-[16px] text-white/20">star</span>
                                        <span class="material-symbols-outlined text-[16px] text-white/20">star</span>
                                    </div>
                                    <p class="text-white/80 text-sm leading-relaxed">It was okay. Good acting but the
                                        plot is very generic for a sci-fi movie.</p>
                                    <div class="flex gap-4 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            class="text-white/40 hover:text-primary text-xs flex items-center gap-1"><span
                                                class="material-symbols-outlined text-[14px]">thumb_up</span>
                                            Helpful</button>
                                        <button class="text-white/40 hover:text-white text-xs">Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center mt-8">
                            <button
                                class="text-white/60 hover:text-white text-sm font-medium px-4 py-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">Load
                                more comments</button>
                        </div>
                    </div>
                </div>
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
                data.forEach(ep => {
                    // --- Tạo HTML nút bấm Grid ---
                    // Lưu ý: Sửa đường dẫn href theo đúng logic routing của bạn
                    // Ví dụ: ?mod=client&act=watch&id=...
                    html += `
                            <a href="?mod=client&act=watch&id=${ep.id}"
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

</html>