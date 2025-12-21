<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
// echo '<pre>';
// print_r($userInfor);
// echo '</pre>';
// die();
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
                            <h2 class="text-white text-3xl font-bold tracking-tight drop-shadow-lg">Thông Tin Cá Nhân</h2>
                            <p class="text-slate-400 text-sm">Quản lý thông tin tài khoản và tùy chỉnh cá nhân của bạn.</p>
                        </div>
                    </div>
                    <?php
                    if (!empty($msg) && !empty($msg_type)) {
                        getMsg($msg, $msg_type);
                    }
                    ?>

                    <!-- Profile Card -->
                    <div class="user-glassmorphic rounded-2xl p-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2"></div>


                        <div class="relative z-10 flex w-full flex-col gap-8 md:flex-row md:justify-between md:items-center">
                            <div class="flex flex-col md:flex-row gap-8 items-center">
                                <!-- Avatar with Animations -->
                                <div class="profile-avatar-container">
                                    <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full"></div>
                                    <div class="profile-avatar-ring profile-avatar-ring-1"></div>
                                    <div class="profile-avatar-ring profile-avatar-ring-2"></div>
                                    <div class="profile-avatar-orbit profile-avatar-orbit-1">
                                        <div class="absolute top-1/2 -right-1.5 w-2 h-2 bg-primary rounded-full shadow-[0_0_10px_#D96C16]"></div>
                                    </div>
                                    <div class="profile-avatar-orbit profile-avatar-orbit-2">
                                        <div class="absolute bottom-0 left-1/2 w-1.5 h-1.5 bg-secondary rounded-full shadow-[0_0_10px_#F29F05]"></div>
                                    </div>
                                    <div class="relative z-10 w-32 h-32 rounded-full overflow-hidden border-2 border-white/10 shadow-inner">
                                        <div class="absolute inset-0 bg-center bg-no-repeat bg-cover" style='background-image: url("<?php echo !empty($_SESSION['auth']['avatar']) ? $_SESSION['auth']['avatar'] : 'https://lh3.googleusercontent.com/aida-public/AB6AXuDp5z9ZOQaU3DTgLKIV6PUXCrR683wgb-cfCtJkQb8fjthg6JZpJSoLnJAB_yLhGXcB50ZUZavHxOwTg49G1jP75MI4G4Ze4X59DwMVmAw5WSNPMbtKXDfKAQ_gbF3HBmgak9heLsPTafhUNnl0XnjySGe2aXePkhP3jNqlHLilcq_MOq77GLgj8f7DUbiYQ69J76kGeQi_Jc4pRNRZmiN24BpItbsEpLMeh0vaXya_5iTPRrZAibG83nrS3UDYSj-8bXuXmQPaH3hG'; ?>");'></div>
                                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/10 to-primary/30 mix-blend-overlay"></div>
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-30 mix-blend-overlay"></div>
                                    </div>
                                </div>

                                <!-- User Info -->
                                <div class="flex flex-col justify-center text-center md:text-left space-y-1">
                                    <h2 class="text-white text-3xl font-bold tracking-tight"><?php echo !empty($_SESSION['auth']['fullname']) ? $_SESSION['auth']['fullname'] : 'Guest User'; ?></h2>
                                    <div class="flex items-center justify-center md:justify-start gap-2">
                                        <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary border border-primary/20 shadow-[0_0_10px_rgba(217,108,22,0.2)]">
                                            Premium User
                                        </span>
                                    </div>
                                    <p class="text-slate-400 text-sm font-light pt-1">ID: #<?php echo !empty($_SESSION['auth']['id']) ? str_pad($_SESSION['auth']['id'], 6, '0', STR_PAD_LEFT) : '000000'; ?></p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                                <button onclick="window.location.href='<?php echo _HOST_URL; ?>/tai_khoan/chinh_sua'" class="kinetic-hover flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg border border-white/10 bg-white/5 text-white text-sm font-semibold tracking-wide hover:shadow-[0_0_15px_rgba(255,255,255,0.1)] transition-all">
                                    <span class="material-symbols-outlined text-[18px] text-slate-300">edit</span>
                                    Chỉnh sửa
                                </button>
                                <button onclick="window.location.href='<?php echo _HOST_URL; ?>/tai_khoan/bao_mat'" class="kinetic-hover flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-primary/20 border border-primary/40 text-white text-sm font-semibold tracking-wide hover:bg-primary/30 hover:shadow-[0_0_15px_rgba(217,108,22,0.3)] transition-all">
                                    <span class="material-symbols-outlined text-[18px]">lock</span>
                                    Bảo mật
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Stat 1: Favorite Genre -->
                        <div class="user-glassmorphic rounded-2xl p-5 flex flex-col items-center justify-center gap-3 kinetic-hover group">
                            <div class="p-3 rounded-full bg-primary/10 border border-primary/20 group-hover:bg-primary/20 transition-colors shadow-[0_0_15px_rgba(217,108,22,0.15)]">
                                <span class="material-symbols-outlined text-3xl text-primary icon-glow">theater_comedy</span>
                            </div>
                            <div class="text-center">
                                <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Thể loại yêu thích</p>
                                <p class="text-white text-xl font-bold mt-1">Hành động</p>
                            </div>
                        </div>

                        <!-- Stat 2: Watch Time -->
                        <div class="user-glassmorphic rounded-2xl p-5 flex flex-col items-center justify-center gap-3 kinetic-hover group">
                            <div class="p-3 rounded-full bg-secondary/10 border border-secondary/20 group-hover:bg-secondary/20 transition-colors shadow-[0_0_15px_rgba(242,159,5,0.15)]">
                                <span class="material-symbols-outlined text-3xl text-secondary" style="text-shadow: 0 0 15px rgba(242, 159, 5, 0.6);">hourglass_top</span>
                            </div>
                            <div class="text-center">
                                <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Thời gian xem</p>
                                <p class="text-white text-xl font-bold mt-1">1,248 Giờ</p>
                            </div>
                        </div>

                        <!-- Stat 3: Watchlist -->
                        <div class="user-glassmorphic rounded-2xl p-5 flex flex-col items-center justify-center gap-3 kinetic-hover group">
                            <div class="p-3 rounded-full bg-highlight/10 border border-highlight/20 group-hover:bg-highlight/20 transition-colors shadow-[0_0_15px_rgba(242,203,5,0.15)]">
                                <span class="material-symbols-outlined text-3xl text-highlight" style="text-shadow: 0 0 15px rgba(242, 203, 5, 0.6);">playlist_add_check</span>
                            </div>
                            <div class="text-center">
                                <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Danh sách</p>
                                <p class="text-white text-xl font-bold mt-1">42 Phim</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Actions -->
                    <div class="user-glassmorphic rounded-2xl p-1 shadow-[0_0_40px_-10px_rgba(217,108,22,0.1)]">
                        <div class="p-6">
                            <h2 class="text-white text-xl font-bold mb-6 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary rounded-full shadow-[0_0_10px_#D96C16]"></span>
                                Quản lý tài khoản
                            </h2>
                            <div class="grid grid-cols-[repeat(auto-fit,minmax(240px,1fr))] gap-4">
                                <!-- Action 1: Subscription -->
                                <div class="profile-action-card">
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/20 text-primary ring-1 ring-inset ring-primary/20 shadow-[0_0_15px_rgba(217,108,22,0.2)]">
                                            <span class="material-symbols-outlined text-2xl">credit_card</span>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-semibold text-white">Quản lý gói cước</h3>
                                            <p class="text-xs text-slate-400 mt-1">Cập nhật phương thức thanh toán</p>
                                        </div>
                                        <span class="material-symbols-outlined ml-auto text-slate-500 group-hover:text-white transition-colors">chevron_right</span>
                                    </div>
                                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-primary/10 blur-2xl rounded-full group-hover:bg-primary/20 transition-all"></div>
                                </div>

                                <!-- Action 2: Billing History -->
                                <div class="profile-action-card">
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-secondary/20 text-secondary ring-1 ring-inset ring-secondary/20 shadow-[0_0_15px_rgba(242,159,5,0.2)]">
                                            <span class="material-symbols-outlined text-2xl">receipt_long</span>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-semibold text-white">Lịch sử thanh toán</h3>
                                            <p class="text-xs text-slate-400 mt-1">Tải xuống hóa đơn</p>
                                        </div>
                                        <span class="material-symbols-outlined ml-auto text-slate-500 group-hover:text-white transition-colors">chevron_right</span>
                                    </div>
                                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-secondary/10 blur-2xl rounded-full group-hover:bg-secondary/20 transition-all"></div>
                                </div>

                                <!-- Action 3: Device Management -->
                                <div class="profile-action-card">
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-500/20 text-red-400 ring-1 ring-inset ring-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                            <span class="material-symbols-outlined text-2xl">devices</span>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-semibold text-white">Quản lý thiết bị</h3>
                                            <p class="text-xs text-slate-400 mt-1">Quản lý phiên đăng nhập</p>
                                        </div>
                                        <span class="material-symbols-outlined ml-auto text-slate-500 group-hover:text-white transition-colors">chevron_right</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>