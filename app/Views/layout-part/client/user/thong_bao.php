<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// echo '<pre>';
// print_r($notices);
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

                <main class="flex-1 layout-content-container flex flex-col h-[700px]">
                    <div class="flex-1 user-glassmorphic rounded-2xl relative overflow-hidden flex flex-col">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/5 rounded-full blur-[80px] pointer-events-none"></div>

                        <div class="relative z-10 p-8 h-full flex flex-col custom-scroll overflow-y-auto">
                            <div class="space-y-4">
                                <?php if (!empty($notices)): ?>
                                    <?php foreach ($notices as $item): ?>
                                        <?php
                                        // 1. XỬ LÝ LOGIC GIAO DIỆN THEO TYPE
                                        // Mặc định (System)
                                        $config = [
                                            'icon' => 'info',
                                            'color' => 'bg-white/10', // Màu nền accent
                                            'icon_bg' => 'bg-white/5',
                                            'icon_color' => 'text-white/20',
                                            'title' => 'Thông báo hệ thống',
                                            'shadow' => 'shadow-[0_0_8px_#ffffff50]'
                                        ];

                                        switch ($item['type']) {
                                            case 'new_episode':
                                                $config = [
                                                    'icon' => 'movie', // Icon phim
                                                    'color' => 'bg-primary',
                                                    'icon_bg' => 'bg-primary/20',
                                                    'icon_color' => 'text-primary',
                                                    'title' => 'Tập phim mới',
                                                    'shadow' => 'shadow-[0_0_8px_#D96C16]'
                                                ];
                                                break;
                                            case 'reply':
                                                $config = [
                                                    'icon' => 'chat_bubble', // Icon chat
                                                    'color' => 'bg-secondary',
                                                    'icon_bg' => 'bg-secondary/20',
                                                    'icon_color' => 'text-secondary',
                                                    'title' => 'Phản hồi mới',
                                                    'shadow' => 'shadow-[0_0_8px_#F29F05]'
                                                ];
                                                break;
                                            case 'like':
                                                $config = [
                                                    'icon' => 'favorite', // Icon tim
                                                    'color' => 'bg-red-500',
                                                    'icon_bg' => 'bg-red-500/20',
                                                    'icon_color' => 'text-red-500',
                                                    'title' => 'Lượt thích mới',
                                                    'shadow' => 'shadow-[0_0_8px_#ef4444]'
                                                ];
                                                break;
                                        }
                                        ?>

                                        <a href="<?php echo $item['link']; ?>" class="block group">
                                            <div class="notification-card notification-unread relative flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all duration-300">

                                                <div class="notification-accent absolute left-0 top-0 bottom-0 w-1 rounded-l-xl <?php echo $config['color']; ?>"></div>

                                                <div class="w-16 h-16 rounded-lg flex items-center justify-center flex-shrink-0 relative overflow-hidden border border-white/10 <?php echo $config['icon_bg']; ?>">
                                                    <div class="absolute inset-0 blur-md opacity-50 <?php echo $config['color']; ?>"></div>
                                                    <span class="material-symbols-outlined relative z-10 text-3xl <?php echo $config['icon_color']; ?>">
                                                        <?php echo $config['icon']; ?>
                                                    </span>
                                                </div>

                                                <div class="flex flex-col gap-1 flex-1">
                                                    <h3 class="text-white font-semibold text-base group-hover:text-primary transition-colors">
                                                        <?php echo $config['title']; ?>
                                                    </h3>
                                                    <div class="text-slate-400 text-sm line-clamp-2">
                                                        <?php echo html_entity_decode($item['message']); ?>
                                                    </div>
                                                    <span class="text-slate-500 text-xs mt-1">
                                                        <?php echo timeAgo($item['created_at']); ?>
                                                    </span>
                                                </div>

                                                <div class="notification-indicator mt-2">
                                                    <div class="w-3 h-3 rounded-full animate-pulse-glow <?php echo $config['color']; ?> <?php echo $config['shadow']; ?>"></div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="flex flex-col items-center justify-center h-64 text-slate-500">
                                        <span class="material-symbols-outlined text-6xl mb-4 opacity-50">notifications_off</span>
                                        <p>Bạn chưa có thông báo nào</p>
                                    </div>
                                <?php endif; ?>
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