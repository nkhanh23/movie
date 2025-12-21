<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
$data = [
    'tittle' => 'Quên mật khẩu'
];
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errorsArr = getSessionFlash('errors');
$oldData = getSessionFlash('oldData');
// echo '<pre>';
// print_r($userInfor);
// echo '</pre>';
// die();
?>
<!DOCTYPE html>
<html class="dark" lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Quên Mật Khẩu - Phê Phim</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#D96C16", // Cam Cháy
                        "secondary": "#F29F05", // Vàng Mật Ong
                        "accent-cyan": "#F2CB05", // Replaced with Highlight Yellow
                        "accent-magenta": "#D96C16", // Replaced with Primary Orange
                        "background-dark": "#050505",
                        "glass-border": "rgba(255, 255, 255, 0.08)",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    },
                    animation: {
                        'spin-slow': 'spin 12s linear infinite',
                        'float': 'float 10s ease-in-out infinite',
                        'float-delayed': 'float 12s ease-in-out infinite 2s',
                        'pulse-glow': 'pulse-glow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'shimmer': 'shimmer 3s linear infinite',
                        'neon-flicker': 'neon-flicker 4s infinite',
                    },
                    keyframes: {
                        'pulse-glow': {
                            '0%, 100%': {
                                opacity: 1,
                                boxShadow: '0 0 30px rgba(217, 108, 22, 0.4), inset 0 0 20px rgba(217, 108, 22, 0.05)'
                            },
                            '50%': {
                                opacity: .9,
                                boxShadow: '0 0 15px rgba(217, 108, 22, 0.1), inset 0 0 5px rgba(217, 108, 22, 0)'
                            },
                        },
                        'float': {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        },
                        'shimmer': {
                            '0%': {
                                backgroundPosition: '200% center'
                            },
                            '100%': {
                                backgroundPosition: '-200% center'
                            }
                        },
                        'neon-flicker': {
                            '0%, 19%, 21%, 23%, 25%, 54%, 56%, 100%': {
                                boxShadow: '0 0 10px rgba(217, 108, 22, 0.3), inset 0 0 5px rgba(217, 108, 22, 0.1)',
                                borderColor: 'rgba(217, 108, 22, 0.6)'
                            },
                            '20%, 24%, 55%': {
                                boxShadow: '0 0 2px rgba(217, 108, 22, 0.1), inset 0 0 0 transparent',
                                borderColor: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                },
            },
        }
    </script>
    <style>
        body {
            background-color: #020617;
            color: #e2e8f0;
        }

        .glassmorphic {
            background: linear-gradient(135deg, rgba(18, 24, 33, 0.8) 0%, rgba(10, 14, 20, 0.9) 100%);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            border-left: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.7);
            position: relative;
            overflow: hidden;
        }

        .glassmorphic::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            opacity: 0.5;
            pointer-events: none;
        }

        .glassmorphic::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(217, 108, 22, 0.05), transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .input-neon:focus {
            animation: neon-flicker 2s infinite;
            outline: none;
            background-color: rgba(217, 108, 22, 0.05);
        }

        .btn-shimmer {
            background: linear-gradient(90deg, rgba(217, 108, 22, 0.8) 0%, rgba(242, 159, 5, 0.8) 25%, rgba(255, 255, 255, 0.4) 50%, rgba(242, 159, 5, 0.8) 75%, rgba(217, 108, 22, 0.8) 100%);
            background-size: 200% auto;
            animation: shimmer 4s linear infinite;
        }

        .volumetric-beam {
            transform-origin: top;
            opacity: 0.3;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: white;
            border-radius: 50%;
            opacity: 0.2;
            pointer-events: none;
        }

        /* Error Message Styling */
        .error {
            color: #fca5a5;
            font-size: 12px;
            margin-top: 6px;
            margin-left: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .error::before {
            content: "⚠";
            font-size: 14px;
        }

        /* Alert Message Styling (for getMsg function) */
        .announce-message {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        .alert-warning {
            background: rgba(251, 191, 36, 0.15);
            border: 1px solid rgba(251, 191, 36, 0.3);
            color: #fde68a;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
        }
    </style>
</head>

<body class="font-display overflow-x-hidden min-h-screen selection:bg-primary/30 selection:text-white">
    <div class="relative flex min-h-screen w-full flex-col justify-center items-center overflow-hidden bg-background-dark">
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-primary/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[600px] h-[600px] bg-secondary/10 rounded-full blur-[100px] animate-pulse delay-1000"></div>
            <div class="absolute top-[30%] left-[50%] -translate-x-1/2 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[90px]"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-150 contrast-150 mix-blend-overlay"></div>
            <div class="volumetric-beam absolute top-[-50%] left-[10%] w-[200px] h-[200vh] bg-gradient-to-b from-primary/5 via-primary/0 to-transparent rotate-[25deg] blur-[40px]"></div>
            <div class="volumetric-beam absolute top-[-50%] right-[20%] w-[150px] h-[200vh] bg-gradient-to-b from-secondary/5 via-secondary/0 to-transparent -rotate-[15deg] blur-[30px]"></div>

            <!-- Particles -->
            <div class="particle w-1 h-1 top-20 right-40 animate-float"></div>
            <div class="particle w-1.5 h-1.5 bottom-40 left-20 animate-float-delayed"></div>
            <div class="particle w-0.5 h-0.5 top-1/2 left-1/4 animate-float opacity-50"></div>
            <div class="particle w-1 h-1 bottom-1/4 right-1/4 animate-float-delayed opacity-40"></div>
            <div class="particle w-2 h-2 top-1/3 right-1/3 animate-float opacity-30"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg px-6">
            <div class="glassmorphic rounded-3xl p-8 md:p-12 relative overflow-hidden animate-pulse-glow border-primary/30">
                <div class="absolute -top-32 -left-32 w-64 h-64 bg-primary/20 rounded-full blur-[70px] pointer-events-none"></div>
                <div class="absolute -bottom-32 -right-32 w-64 h-64 bg-secondary/10 rounded-full blur-[70px] pointer-events-none"></div>

                <div class="relative z-20 flex flex-col gap-8">
                    <div class="text-center">
                        <!-- Logo Centered -->
                        <div class="flex justify-center mb-6">
                            <div class="relative group">
                                <div class="absolute inset-0 bg-primary/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo/PhePhim.png" alt="Phê Phim Logo" class="h-20 w-auto relative z-10 drop-shadow-[0_0_15px_rgba(217,108,22,0.5)] animate-float">
                            </div>
                        </div>

                        <h1 class="text-3xl font-bold text-white tracking-tight mb-3 drop-shadow-lg">Quên Mật Khẩu?</h1>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-xs mx-auto">
                            Đừng lo lắng, hãy nhập email của bạn bên dưới để khôi phục tài khoản.
                        </p>
                    </div>
                    <!-- Global Messages -->
                    <?php if (!empty($msg) && !empty($msg_type)) {
                        getMsg($msg, $msg_type);
                    } ?>

                    <form class="flex flex-col gap-6" action="" method="POST">
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 ml-1 mb-1">Email</label>
                            <div class="relative group/input">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">alternate_email</span>
                                <input name="email" class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]" placeholder="name@example.com" type="email" required
                                    value="<?php echo !empty($userInfor) ? $userInfor : null;  ?>" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-20 group-focus-within/input:opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary/20 group-focus-within/input:bg-primary"></span>
                                </span>
                            </div>
                            <?php if (!empty($errorsArr)) {
                                echo formError($errorsArr, 'email');
                            } ?>
                        </div>

                        <button type="submit" class="relative overflow-hidden btn-shimmer group w-full flex items-center justify-center gap-3 px-8 py-4 rounded-xl text-white text-base font-bold tracking-wide shadow-[0_0_25px_rgba(217,108,22,0.3)] hover:shadow-[0_0_40px_rgba(217,108,22,0.5)] transition-all transform hover:scale-[1.02] border border-white/20 mt-2">
                            <span class="relative z-10 flex items-center gap-2 drop-shadow-md">
                                Gửi Yêu Cầu
                                <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </span>
                            <div class="absolute inset-0 bg-white/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </form>

                    <div class="text-center pt-2">
                        <a class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-white transition-colors duration-300 group py-2" href="<?php echo _HOST_URL; ?>/login">
                            <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform duration-300 text-primary">arrow_back</span>
                            <span>Quay lại <span class="font-semibold text-slate-400 group-hover:text-primary transition-colors">Đăng nhập</span></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex items-center justify-center gap-4 opacity-50 hover:opacity-100 transition-opacity">
                <div class="h-px w-12 bg-gradient-to-r from-transparent to-slate-600"></div>
                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-medium">Bảo Mật Tuyệt Đối</p>
                <div class="h-px w-12 bg-gradient-to-l from-transparent to-slate-600"></div>
            </div>
        </div>
    </div>
</body>

</html>