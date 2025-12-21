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

    <div class="layout-container relative z-10 flex h-full grow flex-col pt-24">
        <div class="flex flex-1 justify-center py-5 md:px-10 lg:px-40">
            <div class="flex w-full max-w-7xl flex-col gap-6 lg:flex-row lg:gap-8">
                <!-- SIDE BAR -->
                <?php layout('client/sidebarUser'); ?>
                <!-- END SIDE BAR -->

                <main class="flex-1 layout-content-container flex flex-col gap-6">
                    <!-- Page Header -->
                    <div class="px-2 py-2">
                        <div class="flex flex-col gap-2">
                            <h2 class="text-white text-3xl font-bold tracking-tight drop-shadow-lg">Chỉnh Sửa Thông Tin</h2>
                            <p class="text-slate-400 text-sm">Cập nhật thông tin tài khoản của bạn</p>
                        </div>
                    </div>
                    <?php
                    if (!empty($msg) && !empty($msg_type)) {
                        getMsg($msg, $msg_type);
                    }
                    ?>
                    <!-- Edit Profile Form -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="user-glassmorphic rounded-2xl p-8 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2"></div>

                            <div class="relative z-10 flex flex-col gap-10">
                                <!-- Avatar & Form Fields Section -->
                                <div class="flex flex-col md:flex-row gap-10 items-start">
                                    <!-- Avatar Upload Section -->
                                    <div class="flex flex-col items-center gap-4 shrink-0 mx-auto md:mx-0">
                                        <!-- Hidden File Input -->
                                        <input type="file" id="avatarInput" name="avartar" accept="image/*" style="display: none;" onchange="previewAvatar(event)">

                                        <div onclick="document.getElementById('avatarInput').click()" class="relative w-48 h-48 flex items-center justify-center group/avatar cursor-pointer">
                                            <!-- Glow Effect -->
                                            <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full opacity-50 group-hover/avatar:opacity-80 transition-opacity duration-500"></div>

                                            <!-- Rotating Rings -->
                                            <div class="absolute inset-[-4px] border border-primary/30 rounded-full border-t-transparent border-l-transparent animate-spin-slow group-hover/avatar:border-primary/60 transition-colors"></div>
                                            <div class="absolute inset-[-12px] border border-secondary/20 rounded-full border-b-transparent border-r-transparent animate-spin-reverse-slow group-hover/avatar:border-secondary/50 transition-colors"></div>

                                            <!-- Orbiting Dots -->
                                            <div class="absolute inset-[-20px] animate-orbit">
                                                <div class="absolute top-1/2 -right-1.5 w-2 h-2 bg-primary rounded-full shadow-[0_0_10px_#D96C16]"></div>
                                            </div>
                                            <div class="absolute inset-[-28px] animate-orbit [animation-delay:-4s] opacity-60">
                                                <div class="absolute bottom-0 left-1/2 w-1.5 h-1.5 bg-secondary rounded-full shadow-[0_0_10px_#F29F05]"></div>
                                            </div>

                                            <!-- Avatar Image -->
                                            <?php
                                            $avatarUrl = !empty($_SESSION['auth']['avatar']) ? $_SESSION['auth']['avatar'] : (_HOST_URL_PUBLIC . '/img/avartar_default/default-avatar.png');
                                            // var_dump('Avatar URL: ' . $avatarUrl);
                                            ?>
                                            <div id="avatarPreview" class="relative z-10 w-40 h-40 rounded-full overflow-hidden border-2 border-white/10 shadow-inner group-hover/avatar:border-primary/50 transition-colors">
                                                <div class="absolute inset-0 bg-center bg-no-repeat bg-cover transform group-hover/avatar:scale-105 transition-transform duration-700" style="background-image: url('<?php echo $avatarUrl; ?>');"></div>
                                                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/10 to-primary/30 mix-blend-overlay"></div>
                                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-30 mix-blend-overlay"></div>

                                                <!-- Upload Overlay -->
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-300">
                                                    <span class="material-symbols-outlined text-white text-3xl drop-shadow-[0_0_10px_rgba(255,255,255,0.8)]">cloud_upload</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" onclick="document.getElementById('avatarInput').click()" class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors flex items-center gap-1 justify-center">
                                                <span class="material-symbols-outlined text-base">refresh</span>
                                                Thay đổi ảnh đại diện
                                            </button>
                                            <p class="text-xs text-slate-500 mt-1">*.jpeg, *.jpg, *.png, *.gif</p>
                                        </div>
                                    </div>

                                    <!-- Form Fields Grid -->
                                    <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Full Name -->
                                        <div class="flex flex-col gap-2 md:col-span-2">
                                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wider ml-1">Họ và Tên</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">person</span>
                                                    <input name="fullname" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="Nhập họ và tên" type="text" value="<?php echo !empty($_SESSION['auth']['fullname']) ? $_SESSION['auth']['fullname'] : ''; ?>" />
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                            <?php
                                            if (!empty($errors)) {
                                                echo formError($errors, 'fullname');
                                            }
                                            ?>
                                        </div>

                                        <!-- Email -->
                                        <div class="flex flex-col gap-2 md:col-span-2">
                                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wider ml-1">Email</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">mail</span>
                                                    <input name="email" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="email@example.com" type="email" value="<?php echo !empty($_SESSION['auth']['email']) ? $_SESSION['auth']['email'] : ''; ?>" />
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                            <?php
                                            if (!empty($errors)) {
                                                echo formError($errors, 'email');
                                            }
                                            ?>
                                        </div>

                                        <!-- Phone -->
                                        <div class="flex flex-col gap-2">
                                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wider ml-1">Số điện thoại</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">phone</span>
                                                    <input name="phone" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="0901234567" type="tel" value="<?php echo !empty($_SESSION['auth']['phone']) ? $_SESSION['auth']['phone'] : ''; ?>" />
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="flex flex-col gap-2">
                                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wider ml-1">Địa chỉ</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <div class="flex items-center px-4">
                                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">location_on</span>
                                                    <input name="address" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 py-3 pl-3 rounded-lg text-sm" placeholder="Nhập địa chỉ" type="text" value="<?php echo !empty($_SESSION['auth']['address']) ? $_SESSION['auth']['address'] : ''; ?>" />
                                                </div>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                        </div>

                                        <!-- Bio -->
                                        <div class="flex flex-col gap-2 md:col-span-2">
                                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wider ml-1">Giới thiệu</label>
                                            <div class="user-glass-input rounded-xl p-0.5 relative group/input">
                                                <textarea name="bio" class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:ring-0 px-4 py-3 rounded-lg text-sm min-h-[120px] resize-none" placeholder="Giới thiệu một chút về bản thân bạn..."><?php echo !empty($_SESSION['auth']['bio']) ? $_SESSION['auth']['bio'] : ''; ?></textarea>
                                                <div class="absolute inset-0 rounded-xl pointer-events-none border border-transparent group-focus-within/input:border-primary/30 transition-colors"></div>
                                            </div>
                                            <div class="flex justify-end">
                                                <span class="text-[10px] text-slate-500">0/500 ký tự</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-white/5 justify-end">
                                    <button type="button" onclick="window.location.href='<?php echo _HOST_URL; ?>/tai_khoan'" class="px-6 py-3 rounded-xl border border-white/10 text-slate-300 hover:text-white hover:bg-white/5 transition-all duration-300 text-sm font-semibold tracking-wide">
                                        Hủy
                                    </button>
                                    <button type="submit" class="relative group overflow-hidden px-8 py-3 rounded-xl bg-primary/20 border border-primary/40 text-white shadow-[0_0_20px_rgba(217,108,22,0.3)] hover:shadow-[0_0_30px_rgba(217,108,22,0.5)] transition-all duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-r from-primary/0 via-white/20 to-primary/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                                        <span class="relative z-10 font-bold tracking-wide flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">save</span>
                                            Lưu Thay Đổi
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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

    /* Remove default outline/ring from inputs */
    .user-glass-input input,
    .user-glass-input textarea {
        outline: none !important;
        box-shadow: none !important;
    }

    .user-glass-input input:focus,
    .user-glass-input textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
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

    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes spin-reverse-slow {
        from {
            transform: rotate(360deg);
        }

        to {
            transform: rotate(0deg);
        }
    }

    @keyframes orbit {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 12s linear infinite;
    }

    .animate-spin-reverse-slow {
        animation: spin-reverse-slow 15s linear infinite;
    }

    .animate-orbit {
        animation: orbit 8s linear infinite;
    }
</style>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update avatar preview
                const avatarPreview = document.getElementById('avatarPreview');
                const avatarDiv = avatarPreview.querySelector('.absolute.inset-0.bg-center');
                if (avatarDiv) {
                    avatarDiv.style.backgroundImage = `url('${e.target.result}')`;
                }
            }
            reader.readAsDataURL(file);
        }
    }
</script>