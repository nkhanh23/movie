<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// echo '<pre>';
// print_r($getAllActors);
// echo '</pre>';
// die();
?>

<body class="font-display text-white overflow-x-hidden min-h-screen relative">
    <div class="layout-container flex flex-col min-h-screen pt-20 md:pt-32">
        <div class="flex-1 flex justify-center py-6 md:py-8 px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="flex flex-col max-w-[1920px] w-full gap-8">

                <div class="flex justify-center">
                    <div class="glass-panel rounded-full p-1 flex gap-1 shadow-neon">
                        <a href="?tab=actors"
                            class="px-4 md:px-8 py-2.5 md:py-3 rounded-full text-xs md:text-sm font-medium transition-all flex items-center
               <?php echo ($currentTab === 'actors') ? 'bg-primary text-white shadow-lg' : 'text-gray-400 hover:text-white'; ?>">
                            <span class="material-symbols-outlined text-lg mr-2">person</span>
                            Diễn Viên
                        </a>

                        <a href="?tab=directors"
                            class="px-4 md:px-8 py-2.5 md:py-3 rounded-full text-xs md:text-sm font-medium transition-all flex items-center
               <?php echo ($currentTab === 'directors') ? 'bg-secondary text-white shadow-lg' : 'text-gray-400 hover:text-white'; ?>">
                            <span class="material-symbols-outlined text-lg mr-2">movie</span>
                            Đạo Diễn
                        </a>
                    </div>
                </div>

                <?php if ($currentTab === 'actors'): ?>
                    <div id="actorsSection" class="content-section animate-fade-in">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-white tracking-wide flex items-center gap-3">
                                <span class="w-1 h-6 bg-primary rounded-full shadow-neon"></span>
                                Danh Sách Diễn Viên
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-6">
                            <?php if (!empty($getAllActors)): ?>
                                <?php foreach ($getAllActors as $actor): ?>
                                    <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/dien_vien/chi_tiet?id=<?php echo $actor['id'] ?>';" class="glass-card rounded-xl p-4 flex flex-col items-center text-center gap-4 group cursor-pointer hover:shadow-neon transition-all">
                                        <div class="relative w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-primary/50 to-secondary/30">
                                            <div class="w-full h-full rounded-full bg-cover bg-center overflow-hidden border-2 border-background-dark"
                                                style="background-image: url('<?php echo $actor['avatar'] ?? 'default.jpg' ?>');">
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-1 w-full">
                                            <h3 class="text-white font-bold truncate group-hover:text-primary transition-colors">
                                                <?php echo $actor['name'] ?>
                                            </h3>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-400 col-span-full text-center">Chưa có dữ liệu diễn viên.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($currentTab === 'directors'): ?>
                    <div id="directorsSection" class="content-section animate-fade-in">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-white tracking-wide flex items-center gap-3">
                                <span class="w-1 h-6 bg-secondary rounded-full shadow-neon"></span>
                                Danh Sách Đạo Diễn
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3 md:gap-6">
                            <?php if (!empty($getAllDirectors)): ?>
                                <?php foreach ($getAllDirectors as $director): ?>
                                    <div class="glass-card rounded-xl p-4 flex gap-4 items-center group cursor-pointer hover:shadow-neon transition-all overflow-hidden">
                                        <div class="w-24 h-24 md:w-32 md:h-32 shrink-0 rounded-lg bg-cover bg-center shadow-lg"
                                            style="background-image: url('<?php echo $director['avatar'] ?? 'default.jpg' ?>');">
                                        </div>
                                        <div class="flex flex-col justify-center flex-1 gap-2 min-w-0">
                                            <h3 class="text-white font-bold group-hover:text-primary transition-colors">
                                                <?php echo $director['name'] ?>
                                            </h3>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-400 col-span-full text-center">Chưa có dữ liệu đạo diễn.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="w-full flex justify-center mt-12 mb-10">
                    <div class="flex items-center gap-2">
                        <?php
                        // Tạo base URL cho phân trang bao gồm cả tab hiện tại
                        $baseUrl = "dien_vien?" . ($queryString ? $queryString . '&' : '') . "tab=$currentTab&page=";
                        ?>

                        <?php if ($page > 1): ?>
                            <a href="<?php echo $baseUrl . ($page - 1) ?>"
                                class="group w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-primary hover:border-primary text-gray-400 hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary/30">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </a>
                        <?php else: ?>
                            <button disabled class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-gray-700 cursor-not-allowed">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                        <?php endif; ?>

                        <div class="flex items-center gap-2 mx-2">
                            <?php
                            $start = max(1, $page - 2);
                            $end = min($maxPage, $page + 2);

                            for ($i = $start; $i <= $end; $i++):
                                $isActive = ($page == $i);
                                $btnClass = $isActive
                                    ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-110 font-bold border-primary'
                                    : 'bg-transparent text-gray-400 hover:text-white hover:bg-white/5 border-transparent';
                            ?>
                                <a href="<?php echo $baseUrl . $i ?>"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl text-sm border transition-all duration-300 <?php echo $btnClass ?>">
                                    <?php echo $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $maxPage): ?>
                            <a href="<?php echo $baseUrl . ($page + 1) ?>"
                                class="group w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-primary hover:border-primary text-gray-400 hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary/30">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </a>
                        <?php else: ?>
                            <button disabled class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-gray-700 cursor-not-allowed">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        const actorsTab = document.getElementById('actorsTab');
        const directorsTab = document.getElementById('directorsTab');
        const actorsSection = document.getElementById('actorsSection');
        const directorsSection = document.getElementById('directorsSection');

        actorsTab.addEventListener('click', () => {
            actorsTab.classList.add('active');
            directorsTab.classList.remove('active');
            actorsSection.classList.remove('hidden');
            directorsSection.classList.add('hidden');
        });

        directorsTab.addEventListener('click', () => {
            directorsTab.classList.add('active');
            actorsTab.classList.remove('active');
            directorsSection.classList.remove('hidden');
            actorsSection.classList.add('hidden');
        });
    </script>

    <style>
        .tab-btn {
            color: #9ca3af;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #D96C16, #F29F05);
            color: white;
            box-shadow: 0 0 20px rgba(217, 108, 22, 0.4);
        }

        .tab-btn:hover:not(.active) {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</body>

</html>