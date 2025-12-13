<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
?>
<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>StreamFlow - Search Results</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#258cf4",
                        "background-light": "#f5f7f8",
                        "background-dark": "#05070a",
                        "glass-dark": "rgba(20, 25, 35, 0.7)",
                        "glass-border": "rgba(255, 255, 255, 0.08)",
                        "neon-glow": "rgba(37, 140, 244, 0.5)",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #05070a;
            color: white;
            overflow-x: hidden;
        }

        /* Ambient background effects */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: 0;
            opacity: 0.3;
        }

        /* Glassmorphism utility */
        .glass-panel {
            background: rgba(30, 35, 45, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        /* Neon pulse for search */
        @keyframes neonPulse {
            0% {
                box-shadow: 0 0 5px rgba(37, 140, 244, 0.2), inset 0 0 2px rgba(37, 140, 244, 0.1);
                border-color: rgba(37, 140, 244, 0.5);
            }

            50% {
                box-shadow: 0 0 15px rgba(37, 140, 244, 0.5), inset 0 0 5px rgba(37, 140, 244, 0.2);
                border-color: rgba(37, 140, 244, 0.8);
            }

            100% {
                box-shadow: 0 0 5px rgba(37, 140, 244, 0.2), inset 0 0 2px rgba(37, 140, 244, 0.1);
                border-color: rgba(37, 140, 244, 0.5);
            }
        }

        .search-neon {
            animation: neonPulse 3s infinite;
        }

        /* Card hover glow */
        .movie-card {
            transition: all 0.3s ease;
        }

        .movie-card:hover {
            transform: translateY(-4px);
            border-color: #258cf4;
            box-shadow: 0 0 20px rgba(37, 140, 244, 0.4);
        }

        .movie-card:hover .play-btn {
            opacity: 1;
            transform: scale(1);
        }

        /* Drifting particles simulation */
        .particle {
            position: fixed;
            background: white;
            border-radius: 50%;
            opacity: 0.2;
            pointer-events: none;
            z-index: 1;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0e14;
        }

        ::-webkit-scrollbar-thumb {
            background: #2a3545;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #258cf4;
        }
    </style>
</head>

<body class="bg-background-dark text-white min-h-screen relative flex flex-col font-display selection:bg-primary selection:text-white">
    <!-- Ambient Background Lighting -->
    <div class="ambient-glow w-[600px] h-[600px] bg-primary top-[-200px] left-[-100px]"></div>
    <div class="ambient-glow w-[500px] h-[500px] bg-purple-600 bottom-[10%] right-[-100px]"></div>
    <!-- Micro Particles (Static representation) -->
    <div class="particle w-1 h-1 top-20 left-1/4"></div>
    <div class="particle w-2 h-2 top-1/2 left-10 opacity-10"></div>
    <div class="particle w-1 h-1 bottom-40 right-1/3"></div>
    <div class="particle w-1.5 h-1.5 top-32 right-20"></div>
    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-8 z-10">
        <!-- Search Section -->
        <form action="" method="GET">
            <div class="flex flex-col items-center mb-12 w-full">
                <div class="w-full max-w-2xl relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-primary/80">search</span>
                    </div>
                    <input name="tu_khoa" class="block w-full pl-12 pr-12 py-4 bg-black/40 border border-primary/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-primary search-neon backdrop-blur-md transition-all text-lg" placeholder="Tìm kiếm phim, diễn viên" type="text" value="<?php echo isset($tu_khoa) ? htmlspecialchars($tu_khoa) : '' ?>" />
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer">
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
                        <div class="glass-panel p-4 rounded-xl flex flex-col items-center gap-3 hover:bg-white/5 transition-colors group cursor-pointer border-transparent hover:border-primary/30">
                            <div class="w-20 h-20 rounded-full p-[2px] bg-gradient-to-br from-primary/50 to-transparent group-hover:from-primary group-hover:to-primary/50 transition-all shadow-[0_0_15px_rgba(37,140,244,0.15)] group-hover:shadow-[0_0_20px_rgba(37,140,244,0.4)]">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if (!empty($getAllMovies) && is_array($getAllMovies)) : ?>

                    <?php foreach ($getAllMovies as $movie) : ?>
                        <div class="movie-card glass-panel rounded-xl overflow-hidden group cursor-pointer relative h-[420px]">
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
                                <button class="w-14 h-14 rounded-full bg-primary/90 text-white flex items-center justify-center shadow-[0_0_20px_rgba(37,140,244,0.6)] hover:bg-primary transition-colors">
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
    <footer class="glass-panel border-t border-t-glass-border mt-12 py-10 z-10">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-2xl font-bold tracking-tighter mb-4 text-white">StreamFlow</h2>
            <div class="flex justify-center gap-6 mb-6">
                <a class="text-gray-400 hover:text-primary transition-colors" href="#">Terms</a>
                <a class="text-gray-400 hover:text-primary transition-colors" href="#">Privacy</a>
                <a class="text-gray-400 hover:text-primary transition-colors" href="#">Help</a>
            </div>
            <p class="text-xs text-gray-500">© 2024 StreamFlow Inc. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>