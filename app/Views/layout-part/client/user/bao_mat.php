<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>

<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-hidden" style="background-color: #050505;">
    <!-- Background Effects -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-primary/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[10%] right-[-5%] w-[600px] h-[600px] bg-secondary/10 rounded-full blur-[100px] animate-pulse delay-1000"></div>
        <div class="absolute top-[40%] left-[30%] w-[400px] h-[400px] bg-highlight/10 rounded-full blur-[80px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-150 contrast-150 mix-blend-overlay"></div>
    </div>

    <div class="layout-container relative z-10 flex h-full grow flex-col pt-20 lg:pt-24">
        <div class="flex flex-1 justify-center py-5 px-3 md:px-10 lg:px-40">
            <div class="flex w-full max-w-7xl flex-col gap-6 lg:flex-row lg:gap-8">
                <!-- SIDE BAR -->
                <?php layout('client/sidebarUser'); ?>
                <!-- END SIDE BAR -->

                <main class="flex-1 layout-content-container flex flex-col gap-6">
                    <!-- Page Header -->
                    <div class="px-2 py-2">
                        <div class="flex flex-col gap-2">
                            <h2 class="text-white text-2xl lg:text-3xl font-bold tracking-tight drop-shadow-lg flex items-center gap-2 lg:gap-3">
                                <span class="material-symbols-outlined text-primary text-2xl lg:text-3xl animate-pulse">shield_lock</span>
                                Bảo Mật & An Toàn
                            </h2>
                            <p class="text-slate-400 text-sm">Quản lý mật khẩu và cài đặt bảo mật cho tài khoản của bạn</p>
                        </div>
                    </div>

                    <div class="user-glassmorphic rounded-2xl p-4 md:p-8 relative overflow-visible group">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-b from-primary/5 to-transparent blur-[80px] -translate-y-1/2 translate-x-1/2 rounded-full pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-secondary/5 blur-[60px] translate-y-1/2 -translate-x-1/4 rounded-full pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col gap-10">
                            <!-- Change Password Section -->
                            <section>
                                <h3 class="text-white text-lg font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">
                                    <span class="w-1 h-5 bg-primary rounded-full shadow-[0_0_8px_orange]"></span>
                                    Đổi Mật Khẩu
                                </h3>
                                <?php
                                if (!empty($msg) && !empty($msg_type)) {
                                    getMsg($msg, $msg_type);
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2 ml-1">Mật khẩu hiện tại</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">key</span>
                                                    <input type="password" name="current_password" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="Nhập mật khẩu hiện tại">
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                            <?php
                                            if (!empty($errors)) {
                                                echo formError($errors, 'current_password');
                                            }
                                            ?>
                                        </div>
                                        <div class="col-span-1">
                                            <label class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2 ml-1">Mật khẩu mới</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">lock_reset</span>
                                                    <input type="password" name="new_password" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="Nhập mật khẩu mới">
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                            <?php
                                            if (!empty($errors)) {
                                                echo formError($errors, 'new_password');
                                            }
                                            ?>
                                        </div>
                                        <div class="col-span-1">
                                            <label class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2 ml-1">Xác nhận mật khẩu</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">lock</span>
                                                    <input type="confirmpassword" name="confirm_password" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="Nhập lại mật khẩu mới">
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                            <?php
                                            if (!empty($errors)) {
                                                echo formError($errors, 'confirm_password');
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-end">
                                        <button type="button" onclick="window.location.href='<?php echo _HOST_URL; ?>/forgot'" class="relative group overflow-hidden px-6 py-2.5 rounded-xl border border-white/10 text-slate-300 hover:text-white hover:bg-white/5 transition-all duration-300">
                                            <span class="relative z-10 font-semibold tracking-wide flex items-center gap-2 text-sm">
                                                <span class="material-symbols-outlined text-[18px]">help</span>
                                                Quên mật khẩu?
                                            </span>
                                        </button>
                                        <button type="submit" class="relative group overflow-hidden px-6 py-2.5 rounded-xl bg-primary/20 border border-primary/40 text-white shadow-[0_0_20px_rgba(217,108,22,0.3)] hover:shadow-[0_0_30px_rgba(217,108,22,0.5)] transition-all duration-300">
                                            <div class="absolute inset-0 bg-gradient-to-r from-primary/0 via-white/20 to-primary/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                                            <span class="relative z-10 font-bold tracking-wide flex items-center gap-2 text-sm">
                                                <span class="material-symbols-outlined text-[18px]">save</span>
                                                Cập nhật mật khẩu
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </section>

                            <!-- Recovery Method (Placeholder for UI consistency) -->
                            <section>
                                <h3 class="text-white text-lg font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">
                                    <span class="w-1 h-5 bg-secondary rounded-full shadow-[0_0_8px_orange]"></span>
                                    Phương Thức Khôi Phục
                                </h3>
                                <div class="w-full max-w-2xl">
                                    <label class="block text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2 ml-1">Email khôi phục</label>
                                    <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                        <div class="flex items-center px-4">
                                            <span class="material-symbols-outlined text-slate-400 text-[20px]">mark_email_read</span>
                                            <input type="email" value="<?php echo !empty($_SESSION['auth']['email']) ? $_SESSION['auth']['email'] : ''; ?>" readonly class="w-full bg-transparent border-none text-slate-300 placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm cursor-not-allowed">
                                            <span class="text-green-500 text-xs font-bold px-2 py-1 bg-green-500/10 rounded border border-green-500/20">Đã xác minh</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2 ml-1">Email này được sử dụng để khôi phục tài khoản khi bạn quên mật khẩu.</p>
                                </div>
                            </section>

                            <!-- Login Alerts -->
                            <section>
                                <h3 class="text-white text-lg font-bold mb-6 flex items-center gap-2 border-b border-white/5 pb-4">
                                    <span class="w-1 h-5 bg-highlight rounded-full shadow-[0_0_8px_yellow]"></span>
                                    Cảnh Báo & Giám Sát
                                </h3>
                                <div class="flex items-center justify-between p-4 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.04] transition-colors hover:shadow-[0_0_15px_rgba(255,255,255,0.05)]">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 text-primary">
                                            <span class="material-symbols-outlined">notifications_active</span>
                                        </div>
                                        <div>
                                            <h4 class="text-white font-medium">Thông báo đăng nhập</h4>
                                            <p class="text-slate-400 text-xs">Nhận email khi tài khoản của bạn được truy cập từ thiết bị lạ.</p>
                                        </div>
                                    </div>
                                    <div class="relative inline-block w-12 mr-2 align-middle select-none">
                                        <input class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-slate-700 appearance-none cursor-pointer transition-all duration-300 outline-none" id="toggle-alert" name="toggle-alert" type="checkbox" checked />
                                        <label class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-800 border border-slate-700 cursor-pointer transition-colors duration-300 relative" for="toggle-alert"></label>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="text-center text-slate-600 text-xs mt-4">
                        <p>Lần kiểm tra bảo mật cuối: <?php echo date('H:i d/m/Y'); ?> - Hệ thống an toàn</p>
                    </div>

                </main>
            </div>
        </div>
    </div>
</div>

<style>
    .user-glass-input {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .user-glass-input:focus-within {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(217, 108, 22, 0.5);
        box-shadow: 0 0 15px rgba(217, 108, 22, 0.2);
    }

    .user-glass-input input:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .user-glassmorphic {
        background: rgba(20, 20, 20, 0.5);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }

    .toggle-checkbox:checked {
        right: 0;
        border-color: #d96c16;
    }

    .toggle-checkbox:checked+.toggle-label {
        background-color: rgba(217, 108, 22, 0.3);
        border-color: rgba(217, 108, 22, 0.5);
    }

    .toggle-checkbox:checked+.toggle-label:before {
        transform: translateX(100%);
    }

    /* Error Message Styling */
    .error {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 8px 12px;
        background: rgba(239, 68, 68, 0.1);
        border-left: 3px solid #ef4444;
        border-radius: 8px;
        color: #fca5a5;
        font-size: 13px;
        font-weight: 500;
        backdrop-filter: blur(10px);
        animation: slideDown 0.3s ease-out;
    }

    .error::before {
        content: "⚠";
        font-size: 16px;
        color: #ef4444;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Alert Message Styling (for getMsg function) */
    .announce-message {
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        backdrop-filter: blur(10px);
        animation: slideDown 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .announce-message::before {
        font-size: 20px;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #86efac;
    }

    .alert-success::before {
        content: "✓";
        color: #22c55e;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
    }

    .alert-danger::before {
        content: "✕";
        color: #ef4444;
    }

    .alert-warning {
        background: rgba(251, 191, 36, 0.15);
        border: 1px solid rgba(251, 191, 36, 0.3);
        color: #fde68a;
    }

    .alert-warning::before {
        content: "⚠";
        color: #fbbf24;
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #93c5fd;
    }

    .alert-info::before {
        content: "ℹ";
        color: #3b82f6;
    }
</style>