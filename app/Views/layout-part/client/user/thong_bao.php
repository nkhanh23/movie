<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
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

                <main class="flex-1 layout-content-container flex flex-col h-[700px]">
                    <div class="flex-1 user-glassmorphic rounded-2xl relative overflow-hidden flex flex-col">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/5 rounded-full blur-[80px] pointer-events-none"></div>

                        <div class="relative z-10 p-8 h-full flex flex-col custom-scroll overflow-y-auto">
                            <div class="space-y-4">
                                <!-- Notification 1: New Movie -->
                                <div class="notification-card notification-unread">
                                    <div class="notification-accent bg-primary"></div>
                                    <div class="w-16 h-24 rounded-lg bg-gradient-to-br from-white/10 to-transparent border border-white/10 flex-shrink-0 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-primary/20 blur-md opacity-50"></div>
                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white/20 text-3xl">movie</span>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h3 class="text-white font-semibold text-base">Phim mới: "Cyber Dystopia 2024"</h3>
                                        <p class="text-slate-400 text-sm">Bộ phim khoa học viễn tưởng mới nhất đã có mặt trên nền tảng. Xem ngay!</p>
                                        <span class="text-slate-500 text-xs">2 giờ trước</span>
                                    </div>
                                    <div class="notification-indicator">
                                        <div class="w-3 h-3 bg-primary rounded-full shadow-[0_0_8px_#D96C16] animate-pulse-glow"></div>
                                    </div>
                                </div>

                                <!-- Notification 2: Recommendation -->
                                <div class="notification-card notification-unread">
                                    <div class="notification-accent bg-secondary"></div>
                                    <div class="w-16 h-24 rounded-lg bg-gradient-to-br from-white/10 to-transparent border border-white/10 flex-shrink-0 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-secondary/20 blur-md opacity-50"></div>
                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white/20 text-3xl">play_circle</span>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h3 class="text-white font-semibold text-base">Gợi ý cho bạn</h3>
                                        <p class="text-slate-400 text-sm">Dựa trên lịch sử xem của bạn, chúng tôi nghĩ bạn sẽ thích "Neon Nights"</p>
                                        <span class="text-slate-500 text-xs">5 giờ trước</span>
                                    </div>
                                    <div class="notification-indicator">
                                        <div class="w-3 h-3 bg-secondary rounded-full shadow-[0_0_8px_#F29F05] animate-pulse-glow"></div>
                                    </div>
                                </div>

                                <!-- Notification 3: System Update (Read) -->
                                <div class="notification-card notification-read">
                                    <div class="notification-accent bg-white/10"></div>
                                    <div class="w-16 h-24 rounded-lg bg-gradient-to-br from-white/5 to-transparent border border-white/5 flex-shrink-0 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-white/5 blur-md opacity-30"></div>
                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white/20 text-3xl">info</span>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h3 class="text-slate-300 font-semibold text-base">Cập nhật hệ thống</h3>
                                        <p class="text-slate-500 text-sm">Chúng tôi đã cải thiện trải nghiệm xem phim với chất lượng 4K HDR mới</p>
                                        <span class="text-slate-600 text-xs">1 ngày trước</span>
                                    </div>
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/5 border border-white/10">
                                        <span class="material-symbols-outlined text-white/40 text-sm">arrow_forward</span>
                                    </div>
                                </div>

                                <!-- Notification 4: New Episode -->
                                <div class="notification-card notification-unread">
                                    <div class="notification-accent bg-primary"></div>
                                    <div class="w-16 h-24 rounded-lg bg-gradient-to-br from-white/10 to-transparent border border-white/10 flex-shrink-0 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-primary/20 blur-md opacity-50"></div>
                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white/20 text-3xl">tv</span>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h3 class="text-white font-semibold text-base">Tập mới đã ra mắt!</h3>
                                        <p class="text-slate-400 text-sm">"Stellar Drift" - Season 2, Episode 5 đã có sẵn để xem</p>
                                        <span class="text-slate-500 text-xs">1 ngày trước</span>
                                    </div>
                                    <div class="notification-indicator">
                                        <div class="w-3 h-3 bg-primary rounded-full shadow-[0_0_8px_#D96C16] animate-pulse-glow"></div>
                                    </div>
                                </div>

                                <!-- Notification 5: Watchlist Reminder -->
                                <div class="notification-card notification-unread">
                                    <div class="notification-accent bg-highlight"></div>
                                    <div class="w-16 h-24 rounded-lg bg-gradient-to-br from-white/10 to-transparent border border-white/10 flex-shrink-0 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-highlight/20 blur-md opacity-50"></div>
                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white/20 text-3xl">bookmark</span>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h3 class="text-white font-semibold text-base">Nhắc nhở danh sách</h3>
                                        <p class="text-slate-400 text-sm">Bạn có 3 phim trong danh sách chưa xem. Đừng bỏ lỡ!</p>
                                        <span class="text-slate-500 text-xs">2 ngày trước</span>
                                    </div>
                                    <div class="notification-indicator">
                                        <div class="w-3 h-3 bg-highlight rounded-full shadow-[0_0_8px_#F2CB05] animate-pulse-glow"></div>
                                    </div>
                                </div>

                                <!-- Notification 6: Account (Read) -->
                                <div class="notification-card notification-read">
                                    <div class="notification-accent bg-white/10"></div>
                                    <div class="w-16 h-24 rounded-lg bg-gradient-to-br from-white/5 to-transparent border border-white/5 flex-shrink-0 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-white/5 blur-md opacity-30"></div>
                                        <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white/20 text-3xl">account_circle</span>
                                    </div>
                                    <div class="flex flex-col gap-2 flex-1">
                                        <h3 class="text-slate-300 font-semibold text-base">Cập nhật tài khoản</h3>
                                        <p class="text-slate-500 text-sm">Hồ sơ của bạn đã được cập nhật thành công</p>
                                        <span class="text-slate-600 text-xs">3 ngày trước</span>
                                    </div>
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/5 border border-white/10">
                                        <span class="material-symbols-outlined text-white/20 text-sm">check</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Particles -->
                        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-primary rounded-full animate-float opacity-50 blur-[1px]"></div>
                        <div class="absolute bottom-1/4 right-1/3 w-1.5 h-1.5 bg-secondary rounded-full animate-float opacity-40 blur-[1px]" style="animation-delay: 2s;"></div>
                        <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-highlight rounded-full animate-float opacity-30 blur-[2px]" style="animation-delay: 4s;"></div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>