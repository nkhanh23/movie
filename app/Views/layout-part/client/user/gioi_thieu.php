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

                <main class="flex-1 layout-content-container flex flex-col gap-6">
                    <!-- Page Header -->
                    <div class="px-2 py-2">
                        <div class="flex flex-col gap-2">
                            <h2 class="text-white text-3xl font-bold tracking-tight drop-shadow-lg">Giới thiệu</h2>
                            <p class="text-slate-400 text-sm">Khám phá tầm nhìn và sứ mệnh của nền tảng phim trực tuyến hàng đầu.</p>
                        </div>
                    </div>

                    <!-- Hero Section with Animation -->
                    <div class="user-glassmorphic rounded-2xl p-8 lg:p-12 relative overflow-hidden min-h-[600px] flex items-center justify-center">
                        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary/20 rounded-full blur-[100px] animate-pulse-glow"></div>
                        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-secondary/15 rounded-full blur-[120px] animate-pulse-glow delay-700"></div>

                        <div class="relative z-10 w-full max-w-4xl h-[500px] perspective-[1000px]">
                            <div class="relative w-full h-full transform-style-3d">
                                <!-- Center Logo -->
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 glass-panel-heavy rounded-3xl border border-white/10 flex items-center justify-center animate-float z-20">
                                    <div class="relative w-40 h-40">
                                        <div class="absolute inset-0 border-2 border-primary/30 rounded-full animate-spin-slow"></div>
                                        <div class="absolute inset-4 border border-secondary/30 rounded-full animate-spin-reverse-slow"></div>
                                        <div class="absolute inset-12 bg-white/5 backdrop-blur-md rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(217,108,22,0.3)]">
                                            <span class="material-symbols-outlined text-5xl text-primary drop-shadow-[0_0_10px_rgba(217,108,22,0.8)]">movie_filter</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Card 1: Mission -->
                                <div class="absolute top-[10%] left-[10%] w-48 h-36 user-glassmorphic rounded-xl border border-white/5 p-4 flex flex-col gap-3 animate-float [animation-delay:4s] hover:scale-105 transition-transform duration-500 cursor-default group z-10">
                                    <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-sm">rocket_launch</span>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-white text-xs font-semibold">Sứ mệnh</p>
                                        <p class="text-slate-400 text-[10px]">Mang phim đến mọi nhà</p>
                                    </div>
                                    <div class="absolute -right-2 -top-2 w-4 h-4 bg-primary rounded-full shadow-[0_0_10px_#D96C16] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>

                                <!-- Floating Card 2: Vision -->
                                <div class="absolute bottom-[15%] right-[10%] w-56 h-40 user-glassmorphic rounded-xl border border-white/5 p-5 flex flex-col gap-4 animate-float [animation-delay:2s] hover:scale-105 transition-transform duration-500 cursor-default z-10">
                                    <div class="flex items-center justify-between">
                                        <div class="w-10 h-10 rounded-lg bg-secondary/20 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-secondary text-lg">visibility</span>
                                        </div>
                                        <div class="flex gap-1">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white/20"></div>
                                            <div class="w-1.5 h-1.5 rounded-full bg-white/20"></div>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-white text-xs font-semibold">Tầm nhìn</p>
                                        <p class="text-slate-400 text-[10px]">Nền tảng phim hàng đầu Việt Nam</p>
                                    </div>
                                </div>

                                <!-- Floating Card 3: Global -->
                                <div class="absolute top-[15%] right-[15%] w-40 h-40 rounded-full user-glassmorphic border border-white/5 p-4 flex flex-col items-center justify-center gap-2 animate-float [animation-delay:3s] hover:scale-105 transition-transform duration-500 cursor-default z-10">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-3xl text-highlight mb-2 block animate-pulse">public</span>
                                        <p class="text-white text-xs font-semibold">Toàn cầu</p>
                                        <p class="text-slate-400 text-[10px]">100+ quốc gia</p>
                                    </div>
                                    <div class="absolute inset-[-10px] animate-spin-slow">
                                        <div class="w-2 h-2 bg-highlight rounded-full absolute top-0 left-1/2 -translate-x-1/2 shadow-[0_0_8px_#F2CB05]"></div>
                                    </div>
                                </div>

                                <!-- Floating Card 4: Community -->
                                <div class="absolute bottom-[20%] left-[15%] w-44 h-24 user-glassmorphic rounded-lg border border-white/5 p-4 flex items-center gap-4 animate-float [animation-delay:0.5s] hover:scale-105 transition-transform duration-500 z-10">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary opacity-80"></div>
                                        <div class="absolute -right-2 -bottom-1 w-6 h-6 rounded-full bg-slate-700 border-2 border-slate-800 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[10px] text-white">group</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <p class="text-white text-xs font-semibold">Cộng đồng</p>
                                        <p class="text-slate-400 text-[10px]">5M+ thành viên</p>
                                    </div>
                                </div>

                                <!-- Connection Lines -->
                                <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-20" style="z-index: -1;">
                                    <line stroke="#D96C16" stroke-dasharray="4 4" stroke-width="1" x1="50%" x2="20%" y1="50%" y2="20%"></line>
                                    <line stroke="#F29F05" stroke-dasharray="4 4" stroke-width="1" x1="50%" x2="80%" y1="50%" y2="25%"></line>
                                    <line stroke="#F2CB05" stroke-dasharray="4 4" stroke-width="1" x1="50%" x2="25%" y1="50%" y2="75%"></line>
                                    <line stroke="#D96C16" stroke-dasharray="4 4" stroke-width="1" x1="50%" x2="80%" y1="50%" y2="75%"></line>
                                </svg>

                                <!-- Particles -->
                                <div class="absolute top-[40%] left-[80%] w-2 h-2 bg-primary rounded-full blur-[1px] animate-pulse"></div>
                                <div class="absolute top-[60%] left-[10%] w-1.5 h-1.5 bg-secondary rounded-full blur-[1px] animate-pulse delay-300"></div>
                                <div class="absolute top-[20%] left-[40%] w-1 h-1 bg-highlight rounded-full blur-[0.5px] animate-pulse delay-500"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card 1: Kho Phim Khổng Lồ -->
                        <div class="user-glassmorphic rounded-2xl p-6 flex flex-col items-center justify-center min-h-[180px] kinetic-hover group">
                            <div class="w-16 h-16 relative flex items-center justify-center mb-4">
                                <div class="absolute inset-0 bg-primary/10 rounded-lg transform rotate-45 group-hover:rotate-90 transition-transform duration-700"></div>
                                <div class="absolute inset-2 bg-primary/5 rounded-lg transform -rotate-12 group-hover:rotate-0 transition-transform duration-700"></div>
                                <span class="material-symbols-outlined text-3xl text-primary z-10">hub</span>
                            </div>
                            <h3 class="text-white text-lg font-bold mb-2">Kho Phim Khổng Lồ</h3>
                            <p class="text-slate-400 text-sm text-center">Hơn 50,000+ bộ phim và series từ khắp nơi trên thế giới</p>
                        </div>

                        <!-- Card 2: Chất Lượng Cao -->
                        <div class="user-glassmorphic rounded-2xl p-6 flex flex-col items-center justify-center min-h-[180px] kinetic-hover group">
                            <div class="w-16 h-16 relative flex items-center justify-center mb-4">
                                <div class="absolute inset-0 bg-secondary/10 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                                <div class="absolute inset-0 border border-secondary/20 rounded-full animate-ping opacity-20"></div>
                                <span class="material-symbols-outlined text-3xl text-secondary z-10">all_inclusive</span>
                            </div>
                            <h3 class="text-white text-lg font-bold mb-2">Chất Lượng 4K</h3>
                            <p class="text-slate-400 text-sm text-center">Trải nghiệm xem phim với độ phân giải cao nhất</p>
                        </div>

                        <!-- Card 3: Bảo Mật Tuyệt Đối -->
                        <div class="user-glassmorphic rounded-2xl p-6 flex flex-col items-center justify-center min-h-[180px] kinetic-hover group">
                            <div class="w-16 h-16 relative flex items-center justify-center mb-4">
                                <div class="absolute w-full h-full border-t-2 border-highlight/50 rounded-full animate-spin"></div>
                                <div class="absolute w-3/4 h-3/4 border-b-2 border-highlight/30 rounded-full animate-spin-reverse-slow"></div>
                                <span class="material-symbols-outlined text-3xl text-highlight z-10">security</span>
                            </div>
                            <h3 class="text-white text-lg font-bold mb-2">Bảo Mật Cao</h3>
                            <p class="text-slate-400 text-sm text-center">Thông tin cá nhân được bảo vệ tuyệt đối</p>
                        </div>
                    </div>

                    <!-- About Content -->
                    <div class="user-glassmorphic rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <h2 class="text-white text-2xl font-bold mb-6 flex items-center gap-2">
                                <span class="w-1 h-6 bg-primary rounded-full shadow-[0_0_10px_#D96C16]"></span>
                                Về Chúng Tôi
                            </h2>
                            <div class="space-y-4 text-slate-300 leading-relaxed">
                                <p>
                                    <strong class="text-white">Phê Phim</strong> là nền tảng xem phim trực tuyến hàng đầu tại Việt Nam,
                                    mang đến cho bạn trải nghiệm giải trí đỉnh cao với kho phim khổng lồ từ khắp nơi trên thế giới.
                                </p>
                                <p>
                                    Chúng tôi cam kết cung cấp nội dung chất lượng cao, giao diện thân thiện và dịch vụ khách hàng
                                    tận tâm. Với hơn <strong class="text-primary">5 triệu</strong> người dùng tin tưởng,
                                    Phê Phim đã trở thành người bạn đồng hành không thể thiếu trong những giây phút thư giãn.
                                </p>
                                <p>
                                    Hãy tham gia cùng chúng tôi để khám phá thế giới điện ảnh đầy màu sắc,
                                    từ những bộ phim bom tấn Hollywood đến những tác phẩm nghệ thuật độc đáo từ châu Á và châu Âu.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="user-glassmorphic rounded-xl p-5 text-center kinetic-hover">
                            <div class="text-3xl font-bold text-primary mb-1">50K+</div>
                            <div class="text-slate-400 text-sm">Bộ phim</div>
                        </div>
                        <div class="user-glassmorphic rounded-xl p-5 text-center kinetic-hover">
                            <div class="text-3xl font-bold text-secondary mb-1">5M+</div>
                            <div class="text-slate-400 text-sm">Người dùng</div>
                        </div>
                        <div class="user-glassmorphic rounded-xl p-5 text-center kinetic-hover">
                            <div class="text-3xl font-bold text-highlight mb-1">100+</div>
                            <div class="text-slate-400 text-sm">Quốc gia</div>
                        </div>
                        <div class="user-glassmorphic rounded-xl p-5 text-center kinetic-hover">
                            <div class="text-3xl font-bold text-primary mb-1">24/7</div>
                            <div class="text-slate-400 text-sm">Hỗ trợ</div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>