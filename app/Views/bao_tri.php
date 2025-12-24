<?php
// Lấy thông tin logo từ database
$site_logo = '';
try {
    if (isset($connect)) {
        $stmt = $connect->prepare("SELECT setting_value FROM settings WHERE setting_key = 'site_logo' LIMIT 1");
        $stmt->execute();
        $site_logo = $stmt->fetchColumn();
    }
} catch (Exception $e) {
    // Nếu lỗi, để trống
}
?>
<!DOCTYPE html>
<html class="dark" lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Hệ Thống Bảo Trì</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#D96C16", // Cam Cháy
                        "secondary": "#F29F05", // Vàng Mật Ong
                        "highlight": "#F2CB05", // Vàng Sáng
                        "background-dark": "#050505",
                        "glass-surface": "rgba(30, 25, 20, 0.6)",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"],
                        "sans": ["Space Grotesk", "sans-serif"],
                    },
                    backgroundImage: {
                        'warm-gradient': 'linear-gradient(135deg, #D96C16 0%, #F29F05 50%, #F2CB05 100%)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        shimmer: {
                            '0%': {
                                backgroundPosition: '200% 0'
                            },
                            '100%': {
                                backgroundPosition: '-200% 0'
                            },
                        }
                    }
                },
            },
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .glass-panel {
            background: rgba(20, 15, 10, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 165, 0, 0.15);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .glass-button {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .glass-button:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(217, 108, 22, 0.5);
            box-shadow: 0 0 15px rgba(217, 108, 22, 0.3);
        }

        .neon-border {
            box-shadow: 0 0 10px rgba(217, 108, 22, 0.3), inset 0 0 20px rgba(217, 108, 22, 0.05);
        }

        .neon-text {
            text-shadow: 0 0 20px rgba(242, 159, 5, 0.6);
        }

        .bg-particle {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            z-index: 0;
        }
    </style>
</head>

<body class="relative flex min-h-screen w-full flex-col bg-background-dark text-white font-display overflow-hidden selection:bg-primary selection:text-white justify-between">
    <!-- Animated Background Particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="bg-particle bg-primary/30 w-[600px] h-[600px] -top-32 -left-32 animate-pulse-slow"></div>
        <div class="bg-particle bg-secondary/20 w-[800px] h-[800px] bottom-[-200px] right-[-200px] animate-pulse-slow" style="animation-delay: 1.5s;"></div>
        <div class="bg-particle bg-highlight/10 w-[400px] h-[400px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:64px_64px] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_50%,#000_60%,transparent_100%)]"></div>
    </div>

    <!-- Main Content -->
    <main class="relative z-10 flex flex-1 flex-col items-center justify-center p-6 w-full">
        <div class="glass-panel group relative w-full max-w-2xl overflow-hidden rounded-3xl p-10 lg:p-14 animate-float shadow-[0_0_80px_-20px_rgba(217,108,22,0.4)] text-center">
            <!-- Animated Border -->
            <div class="absolute inset-0 rounded-3xl border border-primary/30 opacity-60 animate-pulse-slow pointer-events-none"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent rounded-3xl pointer-events-none"></div>

            <div class="relative z-20 flex flex-col items-center gap-8">
                <!-- Logo với hiệu ứng quay -->
                <div class="relative flex items-center justify-center h-40 w-40">
                    <div class="absolute inset-0 rounded-full border border-white/10 border-t-primary/50 animate-spin-slow"></div>
                    <div class="absolute inset-2 rounded-full border border-white/5 border-b-secondary/30 animate-spin-slow" style="animation-direction: reverse; animation-duration: 8s;"></div>
                    <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full animate-pulse-slow"></div>

                    <?php if (!empty($site_logo)): ?>
                        <img src="<?php echo htmlspecialchars($site_logo); ?>"
                            alt="Site Logo"
                            class="relative z-10 h-24 w-auto object-contain drop-shadow-[0_0_20px_rgba(217,108,22,0.8)]">
                    <?php else: ?>
                        <span class="material-symbols-outlined text-6xl text-white drop-shadow-[0_0_15px_rgba(217,108,22,0.8)] relative z-10">
                            play_arrow
                        </span>
                    <?php endif; ?>

                    <div class="absolute top-0 right-0 h-2 w-2 bg-highlight rounded-full blur-[1px] animate-ping"></div>
                </div>

                <!-- Title -->
                <div class="space-y-4">
                    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight text-white neon-text uppercase">
                        Bảo Trì Hệ Thống
                    </h1>

                    <!-- Maintenance Message -->
                    <div class="mt-6 px-6 py-4 bg-white/5 border border-primary/20 rounded-xl backdrop-blur-sm">
                        <p class="text-base lg:text-lg text-slate-300 font-light leading-relaxed">
                            <?php
                            if (isset($maintenance_message) && !empty($maintenance_message)) {
                                echo htmlspecialchars($maintenance_message);
                            } else {
                                echo 'Chúng tôi đang nâng cấp hệ thống để mang đến trải nghiệm tốt hơn. Vui lòng quay lại sau.';
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Thời gian dự kiến (nếu có) -->
                    <?php if (isset($maintenance_end) && !empty($maintenance_end)): ?>
                        <div class="mt-4 flex items-center justify-center gap-2 text-sm text-slate-400">
                            <span class="material-symbols-outlined text-lg">schedule</span>
                            <span>Dự kiến hoàn thành: <span class="text-highlight font-semibold"><?php echo htmlspecialchars($maintenance_end); ?></span></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Refresh Button -->
                <button onclick="location.reload()" class="mt-4 flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-highlight text-white font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-primary/50 transform hover:scale-105 group">
                    <span class="material-symbols-outlined text-lg group-hover:rotate-180 transition-transform duration-500">refresh</span>
                    <span>Kiểm Tra Lại</span>
                </button>
            </div>

            <!-- Light Effect -->
            <div class="pointer-events-none absolute -top-[50%] -left-[20%] h-[200%] w-[200px] rotate-[35deg] bg-gradient-to-b from-transparent via-primary/5 to-transparent blur-3xl mix-blend-overlay"></div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full p-6 text-center">
        <p class="text-[10px] uppercase tracking-widest text-slate-600">
            Trạng Thái: <span class="text-primary font-semibold">503_Đang_Bảo_Trì</span>
        </p>
    </footer>

</body>

</html>