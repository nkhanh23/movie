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
    <title>Standalone Movies - Filter &amp; Pagination</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#D96C16", // Cam Cháy (Burnt Orange)
                        "secondary": "#F29F05", // Vàng Mật Ong (Honey/Amber)
                        "highlight": "#F2CB05", // Vàng Sáng (Bright Yellow)
                        "background-light": "#f5f7f8",
                        "background-dark": "#121212", // Nền tối để logo nổi bật
                        "glass-border": "rgba(217, 108, 22, 0.15)", // Viền cam nhạt
                        "glass-surface": "rgba(217, 108, 22, 0.05)",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    boxShadow: {
                        "neon": "0 0 15px rgba(217, 108, 22, 0.6), 0 0 30px rgba(217, 108, 22, 0.3)",
                        "neon-sm": "0 0 8px rgba(217, 108, 22, 0.5)",
                    }
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar for glassmorphism */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(217, 108, 22, 0.4);
            /* Cam nhạt khi hover */
        }

        /* Utility for range input reset */
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #ffffff;
            cursor: pointer;
            margin-top: -6px;
            box-shadow: 0 0 10px #D96C16;
        }

        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #3b4754;
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-background-dark text-white font-display antialiased min-h-screen selection:bg-secondary/30 selection:text-white pt-20 relative">
    <!-- Ambient Header Gradient -->
    <div class="fixed top-0 left-0 w-full h-[300px] bg-gradient-to-b from-primary/10 via-secondary/5 to-transparent pointer-events-none z-0"></div>

    <!-- Main Container -->
    <div class="w-full max-w-[1920px] mx-auto px-6 md:px-10 relative z-10">
        <!-- Filter Bar -->
        <?php
        $data = [
            'getAllGenres' => $getAllGenres,
        ];
        layoutPart('client/filter', $data)
        ?>

        <!-- Ambient Background Effects -->
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-primary/8 to-transparent pointer-events-none -z-10"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-secondary/15 rounded-full blur-[100px] pointer-events-none -z-10"></div>
        <div class="absolute top-20 -left-40 w-80 h-80 bg-highlight/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
        <!-- Scrollable Grid Container -->
        <div class="mb-8">
            <!-- Grid Header Info (Optional context) -->
            <div class="flex items-center justify-between mb-8">
                <p class="text-gray-400">Showing <span class="text-white font-medium">24</span> of <span class="text-white font-medium">148</span> standalone movies</p>
                <div class="flex gap-2">
                    <button class="p-2 rounded-md bg-white/5 hover:bg-white/10 text-white transition-colors">
                        <span class="material-symbols-outlined text-[20px]">grid_view</span>
                    </button>
                    <button class="p-2 rounded-md bg-transparent hover:bg-white/5 text-gray-500 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[20px]">view_list</span>
                    </button>
                </div>
            </div>
            <!-- Movie Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                <!-- Card 1 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Dark moody cinematic movie poster" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYsn6dhxZlmVx1yuCAbYmmW3-hE7wQSvwPibQibMjXYUca1xNajWVe0uBj-9a-8D4Ft3Fuweu8i5R2XjgJNSPWkxwjmAwpXla0ck0HWRK9NzF9nSAvkvr3av3P5g_jqfyyBBqg3j2n1WG983g9o7Hj6jba1uKNwrbhgZU2Lq5f4E1z0igwjlT0qUYhRNNCp8Q4DA_qKXoiSVW84PMI63AdJaQqUktA9iLk-fK8FimsfJBqI4FfJNmrfQThuLYOPuWMj7NYVUdAyyRv" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <!-- Floating Badge -->
                        <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 transform translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                            <div class="size-8 rounded-full bg-black/60 backdrop-blur-md flex items-center justify-center border border-white/10 hover:bg-primary hover:border-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined text-white text-[16px]">bookmark</span>
                            </div>
                            <div class="size-8 rounded-full bg-black/60 backdrop-blur-md flex items-center justify-center border border-white/10 hover:bg-primary hover:border-primary transition-colors cursor-pointer">
                                <span class="material-symbols-outlined text-white text-[16px]">favorite</span>
                            </div>
                        </div>
                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Neon Horizon</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 8.4
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2024</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Sci-Fi</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>2h 14m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">4K</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">HDR</span>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Abstract dark cyberpunk city scene" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuChEZt6uHKuBTlyWRSCFnr97Xg_d99d6mLsT9QJ-Go3rablzQfB9guBWyN3__Ej3eIjlcMqnUsjMGFvGmFZVdQGSbAF96AOIMKrtSz3yVN3VKK0GZAy-BMFNscou_lm2feuDKjZM04jzODDJoOloav0cwDSCt2FgqjaB83i63CBAgVzpntSJFqMTK-SHe9JhDw4pmZ5EMFQ68UP-DL0AYBPlxJR0F-zkrT5wAfOlZ2zAwkJZMmr5MJFn9cpz-sC-OF-XIIpVoNv5WGl" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Echoes of Silence</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 7.9
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2023</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Thriller</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>1h 45m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">HD</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">5.1</span>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Misty forest with dark lighting" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_KNf5XRO_wFTvVE99MqVv9LVxxwbRqocRbsnUqDR0M5tDgwXCSW6uuez7FoW-bs1YJ00ZMe0KxcwKNFyEtQ6uV8ocSlmOZ55I8K-MifXp4NH8eemzZUU0WvHbT06DbwcWFCLQuPbC9uiuppXi8H8QkdsySOZjUeXuxQplUd5FqLK8lAGLeXeeObm2Q1D0iUCnyd8oA434C3cMwd4Fb9At-nDroorkPJCeQBy95f1FUy_u8TcCkcnr54Dhjh4x2EesDVx4rxwnIA42" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">The Last Frontier</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 9.1
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2021</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Drama</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>2h 30m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">4K</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">Dolby</span>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Dark shadowy figure in red light" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGyGA6JnuPYEixRV_WQmVukKM1Zgdcc7_7KBKnq0Z6cXzX-coFtqfxTvbobYZGkfIyVnAX11sEA-oRk2Vl6wxM-zYHpCCLZwer7-A45uKDpcEU0DnYhdYmulHyU4zH7c5XyacRF1NY1Ezg7K1CwhrapZhQ-uXkoHsjvWt-rZgm0mEEaIi-pUFzEweqbd2N1XRcBrmIPY-s9wJTBKIlT4UgCdHkC5yTlg0JQ6XGlBrv4tGQvc-VZtx3xwXeYvtRQQ4UfFT26YU84Umf" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Red District</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 6.5
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2023</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Action</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>1h 50m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">HD</span>
                        </div>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Futuristic code on a screen" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAhvotkFJklKFIFiWtVw6iuLINatOLRUDg7VUnKfg1QmALp5BSOcIMTbboSeHdq_wMzNTeZ-0VkuOtwY0MJed57EAhxG6C5DEGNz2rRn582ykMcYFNRxxnUVdwFFqc9LWFb9mYxRlNqN3GSvd4PILpIfQJ3XYgT1WMRhesj9x4HW60X81dfeC5h15KAB2cM9Qtq881uFYT0c5Z4x-2VJxNSgmKNWiirM5h9MW-M8v9vet9j1Ruul0ii_lmIS3HSe27ip4S64ceE4TPQ" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Binary Soul</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 8.8
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2025</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Sci-Fi</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>2h 05m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">4K</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">Atmos</span>
                        </div>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Mysterious figure walking in rain" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpyFNe4mqV-ZkdK9TFJ_46CWNi5my6rZ6wkUc3M2SsDcnlIIZzGnWJMEfXzkwYs02EUZqVOoLXxgsH1CeVIi1LVBLx2XPKBopM41JvJn9Abe7GTHD3GYflfLVw0KhRrvTUH4ObfORJ8BYcxLV731DRQoTe5qkZjbKs9SfK9Q7Id2rsXiODocwQUEfMG36yDhJE-49Jp0KOKy6AHc4CYaHnSVWkfwNUbgb1TWYPx_E5FRSmfnoWYXWyjfIbyhbf-9x7FY1Ze-a4xi86" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Rainwalker</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 7.2
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2022</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Mystery</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>1h 55m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">HD</span>
                        </div>
                    </div>
                </div>
                <!-- Card 7 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Person looking at mountains landscape" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAA5TW_yj2bZdjugmjQnSQOcFVVGwQr-sUpFyttBYU3qNq4K05DJ6md0W-YM9NRrooQi8MsiPjg8o7P2_1AGPHlCvzV9insVeq-hRTD07ivftzuQPDpJ2vj50173CPGggULRnOYkI0rwxcGYoe3lIL97fdH5M8sdsH1GsED2B1NX-muvEPg74aMaCt9Ayv_NA5bflpUcnGDRi-oaNAnXi7Y5KTU_80HK2JAbpmzmxDk6ibAhajj2powaCk3kzS4kPjAnVQtyxyPPoXE" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Summit</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 8.0
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2020</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Documentary</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>1h 30m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">4K</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">HDR</span>
                        </div>
                    </div>
                </div>
                <!-- Card 8 -->
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-glass-border bg-glass-surface transition-all duration-300 hover:border-primary/50 hover:shadow-neon hover:-translate-y-1">
                    <div class="relative aspect-[2/3] w-full overflow-hidden">
                        <img alt="Neon city street at night" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBWf-DUJoAsBveonijx6MMnuFslVlK8ns-dqvDoclAYY565XR7Oyw72r5tuOE3TMYahLQShTxfpAUBcmRlEIZU-NwEJucecUMdd0ssKM9C8yjN5j-98q3oKQywCQ6AcAUvb9KFeVjBBEYnDSmUypwesNdiG8vyWZdT4ZhHzsPWuGI4kLTVjt753h0HdwPge_FPGxM9Z6OogX9p7vLti1nV9Dh_qYqLxCFMnXla8QdUvFfYMOanrW55hVLplv5HC-PrToDxLklxdv5nQ" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="size-14 rounded-full bg-primary/90 flex items-center justify-center shadow-neon backdrop-blur-sm cursor-pointer hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px] ml-1">play_arrow</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex flex-col gap-1 p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-white leading-tight group-hover:text-primary transition-colors">Tokyo Drift</h3>
                            <span class="flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-400/10 px-1.5 py-0.5 rounded border border-amber-400/20">
                                <span class="material-symbols-outlined text-[12px]">star</span> 7.5
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>2019</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>Action</span>
                            <span class="size-1 rounded-full bg-gray-600"></span>
                            <span>1h 52m</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium border border-white/10 bg-white/5 text-gray-400 uppercase">HD</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Floating Pagination Dock -->
            <div class="fixed bottom-8 left-0 right-0 flex justify-center pointer-events-none z-50">
                <div class="pointer-events-auto flex items-center gap-2 rounded-full border border-glass-border bg-[#13161c]/80 px-2 py-2 backdrop-blur-xl shadow-neon-sm">
                    <button class="flex size-10 items-center justify-center rounded-full bg-transparent text-gray-400 hover:bg-white/10 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <div class="h-6 w-[1px] bg-white/10 mx-1"></div>
                    <div class="flex items-center gap-1">
                        <button class="size-10 rounded-full bg-primary text-white font-bold text-sm shadow-[0_0_15px_rgba(217,108,22,0.6)] hover:bg-secondary transition-colors">1</button>
                        <button class="size-10 rounded-full bg-transparent text-gray-400 font-medium text-sm hover:bg-white/5 hover:text-white transition-colors">2</button>
                        <button class="size-10 rounded-full bg-transparent text-gray-400 font-medium text-sm hover:bg-white/5 hover:text-white transition-colors">3</button>
                        <button class="hidden sm:flex size-10 rounded-full bg-transparent text-gray-400 font-medium text-sm hover:bg-white/5 hover:text-white transition-colors">4</button>
                        <span class="text-gray-500 px-2">...</span>
                        <button class="hidden sm:flex size-10 rounded-full bg-transparent text-gray-400 font-medium text-sm hover:bg-white/5 hover:text-white transition-colors">12</button>
                    </div>
                    <div class="h-6 w-[1px] bg-white/10 mx-1"></div>
                    <button class="flex size-10 items-center justify-center rounded-full bg-transparent text-gray-400 hover:bg-white/10 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple script to toggle shimmer/pulse animations on load
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.group');
            cards.forEach((card, i) => {
                setTimeout(() => {
                    card.style.opacity = 1;
                }, i * 100);
            });
        });
    </script>
</body>

</html>