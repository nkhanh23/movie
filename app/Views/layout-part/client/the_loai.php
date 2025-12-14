<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('client/header');
// echo '<pre>';
// print_r($movies);
// echo '</pre>';
// die();
?>

<body class="bg-background-dark text-white font-display antialiased min-h-screen selection:bg-secondary/30 selection:text-white pt-20 relative">
    <!-- Ambient Header Gradient -->
    <div class="fixed top-0 left-0 w-full h-[300px] bg-gradient-to-b from-primary/10 via-secondary/5 to-transparent pointer-events-none z-0"></div>

    <!-- Main Container -->
    <div class="w-full max-w-[1920px] mx-auto px-6 md:px-10 relative z-10">
        <!-- Filter Bar -->
        <?php
        $data = [
            'getAllGenres' => $getAllGenres,
            'getAllCountries' => $getAllCountries,
            'getAllTypes' => $getAllTypes,
            'getAllVoiceType' => $getAllVoiceType,
            'getAllQuality' => $getAllQuality,
            'getAllAge' => $getAllAge,
            'getAllReleaseYear' => $getAllReleaseYear,
            'filters' => $filters
        ];
        layoutPart('client/filter', $data)
        ?>

        <!-- Ambient Background Effects -->
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-primary/8 to-transparent pointer-events-none -z-10"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-secondary/15 rounded-full blur-[100px] pointer-events-none -z-10"></div>
        <div class="absolute top-20 -left-40 w-80 h-80 bg-highlight/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
        <!-- Scrollable Grid Container -->
        <div class="mb-8">
            <!-- Movie Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-6">
                <!-- Card  -->
                <?php foreach ($movies as $movie): ?>
                    <div onclick="event.preventDefault(); window.location.href='<?php echo _HOST_URL; ?>/detail?id=<?php echo $movie['id'] ?>';" class="glass-card rounded-xl p-3 flex flex-col gap-3 group cursor-pointer relative overflow-hidden">
                        <div class="relative w-full aspect-[2/3] rounded-lg overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-10 opacity-60"></div>
                            <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" data-alt="<?php echo $movie['tittle']; ?>" style='background-image: url("<?php echo $movie['poster_url']; ?>");'></div>
                            <div class="absolute top-2 right-2 z-20 bg-black/60 backdrop-blur-md px-2 py-0.5 rounded-md border border-white/10 flex items-center gap-1">
                                <span class="material-symbols-outlined text-yellow-400 text-[12px]">star</span>
                                <span class="text-xs font-bold"><?php echo $movie['imdb_rating'] ?></span>
                            </div>
                        </div>
                        <div class="px-1">
                            <h3 class="text-white font-medium truncate group-hover:text-primary transition-colors"><?php echo $movie['tittle'] ?></h3>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-gray-500 text-xs"><?php echo $movie['original_tittle'] ?></p>
                                <span class="material-symbols-outlined text-gray-600 group-hover:text-white transition-colors text-sm">favorite</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <!-- Floating Pagination Dock -->
            <div class="w-full flex justify-center mt-12 mb-10">
                <div class="flex items-center gap-2">

                    <?php if ($page > 1): ?>
                        <a href="<?= getUrlParams('page', $page - 1) ?>"
                            class="group w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-primary hover:border-primary text-gray-400 hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary/30">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </a>
                    <?php else: ?>
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-gray-700 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </span>
                    <?php endif; ?>

                    <div class="flex items-center gap-2 mx-2">
                        <?php
                        // Logic tính toán số trang hiển thị
                        $start = $page - 1;
                        if ($start < 1) $start = 1;
                        $end = $page + 1;
                        if ($end > $maxPage) $end = $maxPage;

                        // Nếu muốn luôn hiện ít nhất 3 trang nếu có thể (Optional improvement)
                        if ($end - $start < 2 && $maxPage >= 3) {
                            if ($start == 1) $end = 3;
                            elseif ($end == $maxPage) $start = $maxPage - 2;
                        }

                        for ($i = $start; $i <= $end; $i++):
                            $isActive = ($page == $i);
                            // Class CSS động
                            $btnClass = $isActive
                                ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-110 font-bold border-primary'
                                : 'bg-transparent text-gray-400 hover:text-white hover:bg-white/5 border-transparent';
                        ?>
                            <a href="<?= getUrlParams('page', $i) ?>"
                                class="w-10 h-10 flex items-center justify-center rounded-xl text-sm border transition-all duration-300 <?= $btnClass ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <?php if ($page < $maxPage): ?>
                        <a href="<?= getUrlParams('page', $page + 1) ?>"
                            class="group w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 hover:bg-primary hover:border-primary text-gray-400 hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary/30">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                    <?php else: ?>
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/5 text-gray-700 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </span>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple script to toggle shimmer/pulse animations on load
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.group');
            cards.forEach((card, i) => {
                setTimeout(() => {
                    card.style.opacity = 1;
                }, i * 100);
            });
        });
    </script>
</body>

</html>