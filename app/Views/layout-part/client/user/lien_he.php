<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
?>

<div class="min-h-screen bg-background-dark">
    <div class="flex flex-1 justify-center py-5 md:px-10 lg:px-40 pt-24">
        <div class="flex w-full max-w-7xl flex-col gap-6 lg:flex-row lg:gap-8">
            <!-- SIDE BAR -->
            <?php layout('client/sidebarUser'); ?>
            <!-- END SIDE BAR -->

            <main class="flex-1 layout-content-container flex flex-col gap-6">
                <!-- Page Header -->
                <div class="px-2 py-2">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-white text-3xl font-bold tracking-tight drop-shadow-lg">Liên hệ hỗ trợ</h2>
                        <p class="text-slate-400 text-sm">Chúng tôi luôn sẵn sàng giải đáp mọi thắc mắc của bạn 24/7. Hãy kết nối với chúng tôi.</p>
                    </div>
                </div>

                <!-- Contact Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Contact Form -->
                    <div class="lg:col-span-7 flex flex-col gap-6">
                        <div class="user-glassmorphic rounded-2xl p-8 relative overflow-hidden h-full">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                            <div class="absolute bottom-10 left-10 w-20 h-20 bg-secondary/10 rounded-full blur-[40px] pointer-events-none animate-pulse"></div>

                            <form class="flex flex-col gap-6 relative z-10 h-full justify-between">
                                <div class="flex flex-col gap-6">
                                    <!-- Name & Email Row -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="flex flex-col gap-2 group">
                                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider pl-1">Họ và tên</label>
                                            <div class="relative">
                                                <input class="contact-input" placeholder="Tên của bạn" type="text" />
                                                <span class="material-symbols-outlined contact-input-icon">person</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2 group">
                                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider pl-1">Email</label>
                                            <div class="relative">
                                                <input class="contact-input" placeholder="email@example.com" type="email" />
                                                <span class="material-symbols-outlined contact-input-icon">alternate_email</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subject -->
                                    <div class="flex flex-col gap-2 group">
                                        <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider pl-1">Chủ đề</label>
                                        <div class="relative">
                                            <select class="contact-select">
                                                <option class="bg-slate-900 text-slate-300">Vấn đề tài khoản</option>
                                                <option class="bg-slate-900 text-slate-300">Thanh toán & Gói cước</option>
                                                <option class="bg-slate-900 text-slate-300">Báo lỗi kỹ thuật</option>
                                                <option class="bg-slate-900 text-slate-300">Khác</option>
                                            </select>
                                            <span class="material-symbols-outlined contact-input-icon">topic</span>
                                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[20px]">expand_more</span>
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <div class="flex flex-col gap-2 group">
                                        <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider pl-1">Nội dung</label>
                                        <div class="relative">
                                            <textarea class="contact-textarea" placeholder="Mô tả chi tiết vấn đề..." rows="5"></textarea>
                                            <span class="material-symbols-outlined absolute left-3 top-4 text-slate-500 group-focus-within:text-primary transition-colors text-[20px]">description</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-2">
                                    <button class="contact-submit-btn" type="button">
                                        <div class="absolute inset-0 bg-white/20 group-hover:opacity-0 transition-opacity"></div>
                                        <div class="relative bg-[#0f172a] hover:bg-transparent rounded-[11px] px-8 py-3.5 flex items-center justify-center gap-3 transition-all duration-300">
                                            <span class="text-white font-bold tracking-wide">Gửi tin nhắn</span>
                                            <span class="material-symbols-outlined text-white group-hover:translate-x-1 transition-transform">send</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Info Cards -->
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        <!-- Hotline -->
                        <div class="user-glassmorphic rounded-2xl p-6 flex items-center gap-5 kinetic-hover group cursor-pointer border-l-4 border-l-primary/50">
                            <div class="relative">
                                <div class="absolute inset-0 bg-primary blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0 relative z-10 group-hover:scale-105 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-primary icon-glow text-3xl">headset_mic</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-1">Hotline 24/7</p>
                                <h3 class="text-white font-bold text-xl tracking-tight group-hover:text-primary transition-colors">1900 6868</h3>
                            </div>
                            <span class="material-symbols-outlined ml-auto text-slate-600 group-hover:text-white transition-colors">arrow_forward_ios</span>
                        </div>

                        <!-- Email -->
                        <div class="user-glassmorphic rounded-2xl p-6 flex items-center gap-5 kinetic-hover group cursor-pointer border-l-4 border-l-secondary/50">
                            <div class="relative">
                                <div class="absolute inset-0 bg-secondary blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                <div class="w-14 h-14 rounded-2xl bg-secondary/10 border border-secondary/20 flex items-center justify-center shrink-0 relative z-10 group-hover:scale-105 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-secondary icon-glow text-3xl">mail</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-1">Hỗ trợ qua Email</p>
                                <h3 class="text-white font-bold text-lg tracking-tight group-hover:text-secondary transition-colors">nkhanh2305@gmail.com</h3>
                            </div>
                            <span class="material-symbols-outlined ml-auto text-slate-600 group-hover:text-white transition-colors">arrow_forward_ios</span>
                        </div>

                        <!-- Location -->
                        <div class="user-glassmorphic rounded-2xl p-6 flex items-center gap-5 kinetic-hover group cursor-pointer border-l-4 border-l-highlight/50">
                            <div class="relative">
                                <div class="absolute inset-0 bg-highlight blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                <div class="w-14 h-14 rounded-2xl bg-highlight/10 border border-highlight/20 flex items-center justify-center shrink-0 relative z-10 group-hover:scale-105 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-highlight icon-glow text-3xl">location_on</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs font-medium uppercase tracking-wider mb-1">Văn phòng chính</p>
                                <h3 class="text-white font-bold text-lg tracking-tight group-hover:text-highlight transition-colors">Đại học Nha Trang</h3>
                            </div>
                            <span class="material-symbols-outlined ml-auto text-slate-600 group-hover:text-white transition-colors">arrow_forward_ios</span>
                        </div>

                        <!-- Partnership Card -->
                        <div class="user-glassmorphic rounded-2xl p-0 relative overflow-hidden flex-1 min-h-[250px] flex items-center justify-center group">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-secondary/10 opacity-50"></div>
                            <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-primary/30 rounded-full blur-[50px] animate-pulse"></div>
                            <div class="absolute bottom-1/3 right-1/4 w-40 h-40 bg-secondary/20 rounded-full blur-[60px] animate-pulse delay-700"></div>

                            <div class="relative z-10 flex flex-col items-center gap-4 text-center p-6">
                                <div class="w-20 h-20 rounded-full border border-white/10 flex items-center justify-center bg-white/5 backdrop-blur-md shadow-[0_0_30px_rgba(255,255,255,0.05)] animate-float">
                                    <span class="material-symbols-outlined text-4xl text-primary drop-shadow-[0_0_10px_rgba(217,108,22,0.5)]">rocket_launch</span>
                                </div>
                                <h4 class="text-white font-bold text-lg">Hợp tác kinh doanh?</h4>
                                <button class="px-6 py-2 rounded-full border border-white/20 hover:bg-white/10 text-slate-300 hover:text-white text-sm transition-all duration-300">
                                    Liên hệ đối tác
                                </button>
                            </div>

                            <!-- Decorative particles -->
                            <div class="absolute top-10 left-10 w-1 h-1 bg-white rounded-full animate-[ping_3s_linear_infinite]"></div>
                            <div class="absolute bottom-10 right-20 w-1.5 h-1.5 bg-primary rounded-full animate-[bounce_4s_infinite]"></div>
                            <div class="absolute top-1/2 right-10 w-1 h-1 bg-secondary rounded-full opacity-60"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>