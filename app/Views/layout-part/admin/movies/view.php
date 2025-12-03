<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Streamscape - Movie Watch Page</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-panel {
            background-color: rgba(255, 255, 255, 0.05);
            -webkit-backdrop-filter: blur(16px);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#258cf4",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101922",
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
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-white">
    <div class="relative min-h-screen w-full flex-col overflow-x-hidden">
        <!-- Background Gradients -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute -top-1/4 -left-1/4 w-1/2 h-1/2 bg-primary/20 rounded-full blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-cyan-500/20 rounded-full blur-3xl animate-pulse delay-700">
            </div>
        </div>
        <!-- Main Content -->
        <div class="relative z-10 flex h-full grow flex-col">
            <!-- TopNavBar -->
            <header
                class="flex items-center justify-between whitespace-nowrap border-b border-solid border-white/10 px-6 sm:px-10 lg:px-16 py-4 glass-panel sticky top-0">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3 text-white">
                        <div class="size-6 text-primary">
                            <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 45.8096C19.6865 45.8096 15.4698 44.5305 11.8832 42.134C8.29667 39.7376 5.50128 36.3314 3.85056 32.3462C2.19985 28.361 1.76794 23.9758 2.60947 19.7452C3.451 15.5145 5.52816 11.6284 8.57829 8.5783C11.6284 5.52817 15.5145 3.45101 19.7452 2.60948C23.9758 1.76795 28.361 2.19986 32.3462 3.85057C36.3314 5.50129 39.7376 8.29668 42.134 11.8833C44.5305 15.4698 45.8096 19.6865 45.8096 24L24 24L24 45.8096Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <h2 class="text-white text-xl font-bold leading-tight tracking-[-0.015em]">Streamscape</h2>
                    </div>
                    <nav class="hidden lg:flex items-center gap-9">
                        <a class="text-white/80 hover:text-white text-sm font-medium leading-normal" href="#">Home</a>
                        <a class="text-white text-sm font-medium leading-normal" href="#">Movies</a>
                        <a class="text-white/80 hover:text-white text-sm font-medium leading-normal" href="#">TV
                            Shows</a>
                        <a class="text-white/80 hover:text-white text-sm font-medium leading-normal" href="#">My
                            List</a>
                    </nav>
                </div>
                <div class="flex flex-1 justify-end gap-2 sm:gap-4 lg:gap-8">
                    <label class="hidden md:flex flex-col min-w-40 !h-10 max-w-64">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full glass-panel">
                            <div class="text-white/60 flex items-center justify-center pl-3">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </div>
                            <input
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-white/60 px-2 text-base font-normal leading-normal"
                                placeholder="Search" value="" />
                        </div>
                    </label>
                    <div class="flex gap-2">
                        <button
                            class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 aspect-square glass-panel text-white/80 hover:text-white hover:bg-white/10">
                            <span class="material-symbols-outlined text-xl">notifications</span>
                        </button>
                        <button
                            class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 aspect-square glass-panel text-white/80 hover:text-white hover:bg-white/10">
                            <span class="material-symbols-outlined text-xl">settings</span>
                        </button>
                    </div>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-primary/50"
                        data-alt="User avatar with a colorful abstract pattern"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDO3TUVvnCHHByfyxtTx98291Ogeh36Z7KUg9gCGbM-6N52Xwd3zmHSmgYdG0CKr7dEI7QqrMEdxtK2o3LLneJIWd2bTm51aynLGupqBpcMy2cjQD74jnbPSeN1betpW8dnnvLZHWfz8RlZmYjjJGq1Noao9UR8OR4UCVcE1RKX5TfPV9-P6DfC1TQhyDzu6wyBlxs7K7VJODH7lPK0TQW2jlD_neL-9DhwlWp1_mqP3kI_t5-lXa2Kg4bJvTonzCahZH4Y5c842Bsv");'>
                    </div>
                </div>
            </header>
            <!-- Main Content Area -->
            <main class="flex-1 p-6 sm:p-8 lg:p-12">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left Column: Player and Info -->
                    <div class="flex-1 flex flex-col gap-6">
                        <!-- MediaPlayer -->
                        <div
                            class="relative w-full rounded-xl shadow-2xl shadow-primary/20 border border-primary/50 p-1 bg-black/50 overflow-hidden">
                            <?php
                            // Link phim (Hardcode để test)
                            $movieUrl = "https://hglink.to/e/8dltdp53fvm1";

                            // Gọi hàm từ core/function.php để xuất Player
                            // Hàm này đã tự xử lý aspect-ratio (tỷ lệ khung hình) nên div ngoài không cần class 'aspect-video' nữa
                            echo renderMoviePlayer($movieUrl);
                            ?>
                        </div>
                        <!-- Actions and Info panels -->
                        <div class="flex flex-col gap-6">
                            <!-- Headline -->
                            <h1 class="text-white tracking-tight text-4xl font-bold leading-tight">Cybernetic Dreams
                            </h1>
                            <!-- Actions Bar -->
                            <div class="@container glass-panel p-2 rounded-xl">
                                <div class="gap-2 px-2 grid-cols-[repeat(auto-fit,minmax(80px,_1fr))] grid">
                                    <div
                                        class="flex flex-col items-center gap-2 py-2.5 text-center rounded-lg hover:bg-white/10 cursor-pointer transition-colors">
                                        <div class="rounded-full bg-white/10 p-2.5">
                                            <span class="material-symbols-outlined text-white text-xl">thumb_up</span>
                                        </div>
                                        <p class="text-white text-sm font-medium leading-normal">Like</p>
                                    </div>
                                    <div
                                        class="flex flex-col items-center gap-2 py-2.5 text-center rounded-lg hover:bg-white/10 cursor-pointer transition-colors">
                                        <div class="rounded-full bg-white/10 p-2.5">
                                            <span class="material-symbols-outlined text-white text-xl">share</span>
                                        </div>
                                        <p class="text-white text-sm font-medium leading-normal">Share</p>
                                    </div>
                                    <div
                                        class="flex flex-col items-center gap-2 py-2.5 text-center rounded-lg hover:bg-white/10 cursor-pointer transition-colors">
                                        <div class="rounded-full bg-white/10 p-2.5">
                                            <span
                                                class="material-symbols-outlined text-white text-xl">playlist_add</span>
                                        </div>
                                        <p class="text-white text-sm font-medium leading-normal">Save</p>
                                    </div>
                                    <div
                                        class="flex flex-col items-center gap-2 py-2.5 text-center rounded-lg hover:bg-white/10 cursor-pointer transition-colors">
                                        <div class="rounded-full bg-white/10 p-2.5">
                                            <span class="material-symbols-outlined text-white text-xl">flag</span>
                                        </div>
                                        <p class="text-white text-sm font-medium leading-normal">Report</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Movie Info Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Poster and synopsis -->
                                <div class="md:col-span-2 flex flex-col sm:flex-row gap-6 glass-panel p-4 rounded-xl">
                                    <img class="w-full sm:w-1/3 aspect-[2/3] object-cover rounded-lg"
                                        data-alt="Movie poster for Cybernetic Dreams, showing a robot looking over a futuristic city."
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdyQZUjSdQqwlJpX8Z9pLEL1PZ5wmd-zt45hswbVLL3WztGcED0GUbMdfqwMnKlstXVMIY9oR79Ib5f64z3d_mk6jNXkpex_r7Isda41R9Hq7MVzOZy_dnFMunGbERureo2sbxUw8_N6QUiSmcEPitzj6GRUucA6X3j7NwXaXHXOlubW02_AamO6b14PX5mN8xNrhKaGnaJgaoJhzss2TBnhvTxrhNmWF-vFTuELHCB5I7GFKAPiDq4KTXgQQgHovTNA8cL06NOifR" />
                                    <div class="flex-1">
                                        <p class="text-white/80 text-sm leading-relaxed">In a neon-drenched future, a
                                            rogue android discovers a hidden truth that could shatter the fragile peace
                                            between humans and machines. Pursued by corporate enforcers, it must
                                            navigate the gleaming towers and shadowed underbelly of the megacity to
                                            expose a conspiracy that reaches the highest echelts of power.</p>
                                    </div>
                                </div>
                                <!-- Tags and Metadata -->
                                <div class="md:col-span-1 flex flex-col gap-4 glass-panel p-4 rounded-xl">
                                    <h3 class="text-lg font-bold">Details</h3>
                                    <div class="flex gap-2 flex-wrap">
                                        <div
                                            class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white/10 px-3">
                                            <p class="text-white text-sm font-medium leading-normal">Sci-Fi</p>
                                        </div>
                                        <div
                                            class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white/10 px-3">
                                            <p class="text-white text-sm font-medium leading-normal">Thriller</p>
                                        </div>
                                        <div
                                            class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white/10 px-3">
                                            <p class="text-white text-sm font-medium leading-normal">Action</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 mt-2 text-white/80">
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">calendar_today</span>
                                            2024</div>
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">schedule</span>
                                            2h 23m</div>
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">public</span> USA
                                        </div>
                                        <div class="flex items-center gap-3 text-sm"><span
                                                class="material-symbols-outlined text-xl text-primary">hd</span> 4K
                                            Ultra HD</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment Section -->
                            <div class="flex flex-col gap-4 glass-panel p-4 rounded-xl">
                                <h3 class="text-lg font-bold">Comments (1,284)</h3>
                                <div class="flex gap-4 items-start">
                                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                                        data-alt="User avatar with a colorful abstract pattern"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDydl7iW-ZleKtIT8uQJXWF3lmEO3LBAgWYN0bsWyOSsp0QB7FxZsQZTTtdEjvnOkTFTfhi0QdACEsM8xLDtgG_KpLPTsTpgDbFnEf_-2jaSUtFV3IoYy4YnLerMCdmFUCpqfgY1Ch2un0GehvmFbYb5tvadjs5idG0h5vHSyiFCEfiuSzBaEZlRULG06QDmldFTydHXvqulCdrJujU_IqfaLtsMPglcKdRdT_VXJAOUenKxcUNQZHHJCVc5WyElsYj5l9v5Sub77Nm");'>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm">User123</p>
                                        <p class="text-white/80 text-sm mt-1">This movie was incredible! The visual
                                            effects were out of this world. A must-watch for any sci-fi fan.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4 items-start">
                                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                                        data-alt="User avatar with a colorful abstract pattern"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD6nEFzF0NTPAWSCp0BthqT7kSrJCFuE9sqBeTHhp79UXEstWLX4_YmGngSBFQ9uO0OOPp6x1JV5fW8fVSIrOQOUelaOO43Akh2UtbGgclxEShU6NqURpV3hOe9XR1qGgpIQa6OKyMADrXZ3JuGBpv7xaJmhabpeXuOCxm4ELRzaJr7logMiYr-9rN1Ff-_oz3ks5_ilCYHqwC6UyxP7AZlmj9hrPti7Qb1E7nzUFV8v4vpX2qea4cNQHxfXtVc47awAnDdOyyzT9mf");'>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm">Cinephile_Pro</p>
                                        <p class="text-white/80 text-sm mt-1">Mind-bending plot with a satisfying twist.
                                            The world-building is top-notch. Can't wait for the sequel.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Sidebar -->
                    <aside class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-8">
                        <div class="glass-panel p-4 rounded-xl">
                            <h3 class="font-bold mb-4">Live Viewers (2,451)</h3>
                            <div class="flex -space-x-2">
                                <div class="size-10 rounded-full bg-cover bg-center border-2 border-background-dark"
                                    data-alt="Avatar of viewer 1"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB-E_FiL47cAbgDo7-F3uss3mClsob8UO2ea4Faq8Iu67k0WgIBa0OPsG603GH6m0_1q8mBCb5xnFJxcYserUDDEyzcNC52RwK7_A0w5BdkphJXrQXfSbYLsVrHCK1K1yOmCcgtKXWZYr6-pDBYznpdkzryDnP46devR3JIoLqdHHZwLxUE0IM91wVOPVTE5TsVatqVLcDVGM9wNWusjoItMN4Pt9MmMbyaOk5hThqJNU0A4XauLWgWzq_W4cuUQfyzI0DO5Ls44PRn");'>
                                </div>
                                <div class="size-10 rounded-full bg-cover bg-center border-2 border-background-dark"
                                    data-alt="Avatar of viewer 2"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA-UG5ShIOapIkOENv3fRYA1ftm3sIiptTqQibgqB0y0b-1iqJX7B1YKq5S2swin2REv_h4a96KwvPfuYNsfHaWC8MuaD3RRJmhtjW3aOc6eRWM9mY-wQwt_OhrphPYTVQEgS4pHZofQUY84a1bkhHY3atiiC70FMkNs3HTl2Y02NpDC3ByEKidysE9-aP1ohxm5amnCxf4-Xz4knpVRjGCaL3y8zZBJ4QEM3SGDQ5ZgcUlkrpPIVCIW-WoP1aqD31cBBZmsPsitEws");'>
                                </div>
                                <div class="size-10 rounded-full bg-cover bg-center border-2 border-background-dark"
                                    data-alt="Avatar of viewer 3"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCquAPjGxZ3PqHpCBgsMPQnfpHLeZJY41B1BpM_J7JRzO1hsHL5u8y2YW6Q9ZMV2oGp2tlXi1Y8f2YUNbjZIigTe5TUA8dotUfV2z-WXieDBhLzr5k7j0V_jNJtH7ocXnBLtoP1uAbirE87l5c-Nl6gWBH82j8xaK1475QskDzu1jmWCd722r8TH7yo1Dnegs4bqXxiLUZbYQNFZs-KfeNbOBfCOVhV_q3KSBk7zavh_tYDlo2VCe_D3CQ4zwsn5A2Nx8LAixjXh4gu");'>
                                </div>
                                <div class="size-10 rounded-full bg-cover bg-center border-2 border-background-dark"
                                    data-alt="Avatar of viewer 4"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCPnBZfiaKQ5BGauCNeE0S7d4cubTmyVKJ0PYnCCivLy5XTeQvzb1F_npJKVjc3aqtcNiNrssXLVLvdag85zEUtcuymKNdXtxtv4RKtib6gs8zg-6vRKAGyzxk4LlBtfRnrCeyz8jBIofWYSzXMt6TKhssLHwDKpSVLWsVi9FQMQPpxbcJ4dawXOjG7kQ7n7JWzmOd89TcS9Ap6wDHbjlBcx__8GU0wK2i5o6kyXVYyq2shO0xsJMSjUZ23HME6kqL5GhztKE8neJY6");'>
                                </div>
                                <div
                                    class="size-10 rounded-full bg-primary/30 border-2 border-background-dark flex items-center justify-center text-xs font-bold">
                                    +2k</div>
                            </div>
                        </div>
                        <div class="glass-panel p-4 rounded-xl">
                            <h3 class="font-bold mb-4">Recommended Movies</h3>
                            <div class="flex flex-col gap-4">
                                <div class="flex gap-4 group">
                                    <img class="w-16 h-24 object-cover rounded-md"
                                        data-alt="Poster for movie 'Quantum Echo'"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuASO_Y3nZL9xyzLo78YeJOazV-dpeU5aXkedurY77Vm0oAJ9UuhQpAUvvjGuOVnlUTaKA1seRn3f6qusHsICjnnaKmAWapXyIzvyAkqn3Je2GLR3TussfRTe-tpyHtjB0g4Zhn-BilMe_ZBHb5AVoA1Dgt2fvWL1qxqNAZTgkj7aKbhObzHdNax0g4qV91F4gj27QYGs7LQTJ58TsT0FD1RUoev7W4rKsIyP7srs0_gkS9VFIqlpXIvXghIBjQ8PxfqqSoK76Nbb7fA" />
                                    <div>
                                        <p class="font-semibold group-hover:text-primary transition-colors">Quantum Echo
                                        </p>
                                        <p class="text-xs text-white/60">Sci-Fi, Mystery</p>
                                    </div>
                                </div>
                                <div class="flex gap-4 group">
                                    <img class="w-16 h-24 object-cover rounded-md"
                                        data-alt="Poster for movie 'Neon City Run'"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCeHDaQw8tp2yyw4Co4MOyThWG9oECfF1oAbQDw5GyPPLjLWzr-dpfuUaX8DnsudRUq7kir0Z8vWanrKpplOW7t8fzGODubuHkX7W9TO_YPyG_QwEFXUC47zHf8PvvNzIXNQLvdKow5ih3hdriBw-50z0Wx6KMudy3cHcLwXol1cl__F5CgHyBKPc0TKooMuKG8vE1uCryoLwY4etxbjCnIwEyMtAYPLBqhPmdtBrHEwe93sjYu8pSpp1YDkQNJ_cThZYFtUjBOIblh" />
                                    <div>
                                        <p class="font-semibold group-hover:text-primary transition-colors">Neon City
                                            Run</p>
                                        <p class="text-xs text-white/60">Action, Cyberpunk</p>
                                    </div>
                                </div>
                                <div class="flex gap-4 group">
                                    <img class="w-16 h-24 object-cover rounded-md"
                                        data-alt="Poster for movie 'The Last Starship'"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWV2pQNj-2OumLIS05pcMgT9Dkztmf5lc4l7ovqGetG8xKFMbNEllIgQeaq7k71bNNsKi3LB0SgDn4gdFA1_qMxli0S8O43rrQqSbEjaffZQNcTX4Y0U9-U1y3w75ftj3rHHp5Lp8uGJkGGnatt20qySn4W98yvlYNBiqBoP3_DLb5PRztEphvRR3PEzWbYrCDo_lYTRqWUpSs-upBjYQqJ6uxn-8JRrkJ4L8KNhz22ql5G2wNz2lu0oRqNPFk6fOy2Q_ahns0L4XD" />
                                    <div>
                                        <p class="font-semibold group-hover:text-primary transition-colors">The Last
                                            Starship</p>
                                        <p class="text-xs text-white/60">Sci-Fi, Adventure</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>

</html>