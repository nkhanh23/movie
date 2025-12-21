<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}

// Logic từ file cũ: Đọc flash xác định tab đang active và message
$activeTab = getSessionFlash('active_tab') ?? 'login';
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errorsArr = getSessionFlash('errors');
$oldData = getSessionFlash('oldData');

// Tách lỗi cho từng form
$errorsLogin    = $activeTab === 'login'  ? $errorsArr : [];
$errorsRegister = $activeTab === 'signup' ? $errorsArr : [];
?>
<!DOCTYPE html>
<html class="dark" lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Đăng Nhập / Đăng Ký - Phê Phim</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#D96C16", // Cam Cháy
                        "secondary": "#F29F05", // Vàng Mật Ong
                        "accent-cyan": "#F2CB05",
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

        .tab-btn {
            position: relative;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #fff;
            text-shadow: 0 0 10px rgba(217, 108, 22, 0.5);
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: #D96C16;
            box-shadow: 0 0 10px #D96C16;
            border-radius: 99px;
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

        /* Error msg styling override for getMsg function output to match layout */
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

        /* Form error message styling */
        .error {
            color: #fca5a5;
            font-size: 12px;
            margin-top: 4px;
            margin-left: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .error::before {
            content: "⚠";
        }
    </style>
</head>

<body class="font-display overflow-x-hidden min-h-screen selection:bg-primary/30 selection:text-white">
    <div class="relative flex min-h-screen w-full flex-col justify-center items-center overflow-hidden bg-background-dark py-10">
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-20%] left-[-10%] w-[800px] h-[800px] bg-primary/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[600px] h-[600px] bg-secondary/10 rounded-full blur-[100px] animate-pulse delay-1000"></div>
            <div class="absolute top-[30%] left-[50%] -translate-x-1/2 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[90px]"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-150 contrast-150 mix-blend-overlay"></div>
            <div class="volumetric-beam absolute top-[-50%] left-[10%] w-[200px] h-[200vh] bg-gradient-to-b from-primary/5 via-primary/0 to-transparent rotate-[25deg] blur-[40px]"></div>
            <div class="volumetric-beam absolute top-[-50%] right-[20%] w-[150px] h-[200vh] bg-gradient-to-b from-secondary/5 via-secondary/0 to-transparent -rotate-[15deg] blur-[30px]"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg px-6">
            <div class="glassmorphic rounded-3xl p-8 md:p-10 relative overflow-hidden animate-pulse-glow border-primary/30">
                <div class="absolute -top-32 -left-32 w-64 h-64 bg-primary/20 rounded-full blur-[70px] pointer-events-none"></div>
                <div class="absolute -bottom-32 -right-32 w-64 h-64 bg-secondary/10 rounded-full blur-[70px] pointer-events-none"></div>

                <div class="relative z-20 flex flex-col gap-6">
                    <!-- Header -->
                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <div class="relative group cursor-pointer" onclick="window.location.href='<?php echo _HOST_URL; ?>'">
                                <div class="absolute inset-0 bg-primary/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo/PhePhim.png" alt="Phê Phim Logo" class="h-20 w-auto relative z-10 drop-shadow-[0_0_15px_rgba(217,108,22,0.5)] animate-float">
                            </div>
                        </div>

                        <!-- Tab Switcher -->
                        <div class="flex items-center justify-center gap-12 font-bold text-lg mb-6">
                            <button class="tab-btn <?php echo ($activeTab === 'login') ? 'active' : 'text-slate-500 hover:text-slate-300'; ?>" onclick="switchTab('login')">
                                Đăng Nhập
                            </button>
                            <button class="tab-btn <?php echo ($activeTab === 'signup') ? 'active' : 'text-slate-500 hover:text-slate-300'; ?>" onclick="switchTab('signup')">
                                Đăng Ký
                            </button>
                        </div>
                    </div>

                    <!-- Global Messages -->
                    <?php if (!empty($msg) && !empty($msg_type)) {
                        getMsg($msg, $msg_type);
                    } ?>

                    <!-- Login Form -->
                    <div id="login-form" class="<?php echo ($activeTab === 'login') ? 'block' : 'hidden'; ?> animate-fade-in">
                        <form method="POST" action="" enctype="multipart/form-data" class="flex flex-col gap-5">
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 ml-1 mb-1">Email</label>
                                <div class="relative group/input">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">alternate_email</span>
                                    <input type="email" name="email"
                                        class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]"
                                        placeholder="name@example.com"
                                        autocomplete="off"
                                        value="<?php if (!empty($oldData)) {
                                                    echo oldData($oldData, 'email');
                                                } ?>">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-20 group-focus-within/input:opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-primary/20 group-focus-within/input:bg-primary"></span>
                                    </span>
                                </div>
                                <?php if (!empty($errorsLogin)) {
                                    echo formError($errorsLogin, 'email');
                                } ?>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between items-center px-1">
                                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">Mật khẩu</label>
                                    <a href="<?php echo _HOST_URL; ?>/forgot" class="text-xs text-primary hover:text-secondary transition-colors font-medium">Quên mật khẩu?</a>
                                </div>
                                <div class="relative group/input">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 group-focus-within/input:text-primary transition-colors duration-300">lock</span>
                                    <input type="password" name="password"
                                        class="w-full bg-slate-950/40 border border-white/10 rounded-2xl px-5 pl-14 py-4 text-white placeholder-slate-600 focus:placeholder-slate-500 transition-all duration-300 input-neon focus:border-primary/50 focus:shadow-[0_0_20px_rgba(217,108,22,0.15)]"
                                        placeholder="••••••••"
                                        autocomplete="off">
                                </div>
                                <?php if (!empty($errorsLogin)) {
                                    echo formError($errorsLogin, 'password');
                                } ?>
                            </div>

                            <button type="submit" class="relative overflow-hidden btn-shimmer group w-full flex items-center justify-center gap-3 px-8 py-4 rounded-xl text-white text-base font-bold tracking-wide shadow-[0_0_25px_rgba(217,108,22,0.3)] hover:shadow-[0_0_40px_rgba(217,108,22,0.5)] transition-all transform hover:scale-[1.02] border border-white/20 mt-2">
                                <span class="relative z-10 flex items-center gap-2 drop-shadow-md">
                                    Đăng Nhập
                                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">login</span>
                                </span>
                                <div class="absolute inset-0 bg-white/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>

                            <div class="relative flex items-center gap-4 py-2">
                                <div class="h-px flex-1 bg-white/10"></div>
                                <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Hoặc</span>
                                <div class="h-px flex-1 bg-white/10"></div>
                            </div>

                            <a href="<?php echo $google_login_url; ?>" class="relative group w-full flex items-center justify-center gap-3 px-6 py-3.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-white transition-all duration-300 hover:border-white/20">
                                <i class="fa-brands fa-google text-lg group-hover:scale-110 transition-transform text-white"></i>
                                <span class="font-medium text-sm">Đăng nhập bằng Google</span>
                            </a>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div id="signup-form" class="<?php echo ($activeTab === 'signup') ? 'block' : 'hidden'; ?> animate-fade-in">
                        <?php
                        layoutPart('auth/register', [
                            'msg'            => $msg,
                            'msg_type'       => $msg_type,
                            'errorsRegister' => $errorsRegister,
                            'oldData'        => $oldData,
                        ]);
                        ?>
                    </div>

                </div>
            </div>

            <div class="mt-8 flex items-center justify-center gap-4 opacity-50 hover:opacity-100 transition-opacity">
                <div class="h-px w-12 bg-gradient-to-r from-transparent to-slate-600"></div>
                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-medium">Bảo Mật Tuyệt Đối</p>
                <div class="h-px w-12 bg-gradient-to-l from-transparent to-slate-600"></div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            const tabs = document.querySelectorAll('.tab-btn');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                signupForm.classList.add('hidden');
                tabs[0].classList.add('active', 'text-white');
                tabs[0].classList.remove('text-slate-500');
                tabs[1].classList.remove('active', 'text-white');
                tabs[1].classList.add('text-slate-500');
            } else {
                loginForm.classList.add('hidden');
                signupForm.classList.remove('hidden');
                tabs[1].classList.add('active', 'text-white');
                tabs[1].classList.remove('text-slate-500');
                tabs[0].classList.remove('active', 'text-white');
                tabs[0].classList.add('text-slate-500');
            }
        }
    </script>
</body>

</html>