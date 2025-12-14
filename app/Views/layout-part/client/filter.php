<?php
// Lấy các giá trị đang được chọn từ URL ($_GET) hoặc từ Controller ($filters)
// Ưu tiên $_GET, nhưng nếu không có thì lấy từ $filters (cho giá trị mặc định)
$currentGenre   = $filters['genres'] ?? null;
$currentCountry = $filters['countries'] ?? null;
$currentType    = $filters['types'] ?? null;
$currentYear    = $filters['release_year'] ?? null;
$currentQuality = $filters['quality'] ?? null;
$currentAge     = $filters['age'] ?? null;
$currentLang    = $filters['language'] ?? null;
$currentSort    = $sort ?? 'newest';

// CSS Class cho trạng thái: Đang chọn (Active) và Bình thường (Inactive)
$clsActive   = "bg-primary/20 text-primary border-primary/30 shadow-[0_0_10px_rgba(var(--primary-rgb),0.3)]";
$clsInactive = "bg-white/5 text-gray-400 border border-white/10 hover:border-primary/30 hover:text-primary";
// echo '<pre>';
// print_r($filters);
// echo '</pre>';
// die();
?>

<!-- Filter Toggle Button (Always Visible) -->
<button id="filterToggle"
    class="glass-panel rounded-full p-4 mb-4 border border-glass-border hover:bg-primary/20 hover:shadow-neon-sm transition-all cursor-pointer group">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-2xl group-hover:rotate-180 transition-transform duration-300">tune</span>
        <span class="text-white font-semibold hidden md:inline">Bộ lọc</span>
    </div>
</button>

<!-- Filter Panel (Collapsible) -->
<div id="filterPanel" class="glass-panel rounded-2xl p-6 md:p-10 mb-8 border border-glass-border hidden">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">tune</span>
            Bộ lọc
        </h2>
    </div>

    <div class="space-y-6 mb-6">

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">movie_filter</span>
                <span class="text-sm font-semibold text-white">Thể loại</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= getUrlParams('genres', '') ?>"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= empty($currentGenre) ? $clsActive : $clsInactive ?>">
                    Tất cả
                </a>
                <?php foreach ($getAllGenres as $genre) : ?>
                    <a href="<?= getUrlParams('genres', $genre['id']) ?>"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= ($currentGenre == $genre['id']) ? $clsActive : $clsInactive ?>">
                        <?= $genre['name'] ?>
                    </a>
                <?php endforeach ?>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">public</span>
                <span class="text-sm font-semibold text-white">Quốc gia</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= getUrlParams('countries', '') ?>"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= empty($currentCountry) ? $clsActive : $clsInactive ?>">
                    Tất cả
                </a>
                <?php foreach ($getAllCountries as $country) : ?>
                    <a href="<?= getUrlParams('countries', $country['id']) ?>"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= ($currentCountry == $country['id']) ? $clsActive : $clsInactive ?>">
                        <?= $country['name'] ?>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">theaters</span>
                <span class="text-sm font-semibold text-white">Loại phim</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($getAllTypes as $type) : ?>
                    <a href="<?= getUrlParams('types', $type['id']) ?>"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= ($currentType == $type['id']) ? $clsActive : $clsInactive ?>"><?= $type['name'] ?></a>
                <?php endforeach ?>

            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">calendar_month</span>
                <span class="text-sm font-semibold text-white">Năm phát hành</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= getUrlParams('release_year', '') ?>"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= empty($currentYear) ? $clsActive : $clsInactive ?>">
                    Tất cả
                </a>
                <?php
                // Kiểm tra xem có đang hiển thị tất cả năm không
                $showAllYears = isset($_GET['show_all_years']);

                // Lấy 13 năm đầu tiên hoặc tất cả năm
                $yearsToShow = $showAllYears ? $getAllReleaseYear : array_slice($getAllReleaseYear, 0, 13);
                $hasMoreYears = count($getAllReleaseYear) > 13;

                foreach ($yearsToShow as $year) :
                ?>
                    <a href="<?= getUrlParams('release_year', $year['id']) ?>"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= ($currentYear == $year['id']) ? $clsActive : $clsInactive ?>">
                        <?= $year['year'] ?>
                    </a>
                <?php endforeach; ?>

                <?php if ($hasMoreYears && !$showAllYears): ?>
                    <a href="<?= getUrlParams('show_all_years', '1') ?>"
                        class="px-2 py-1.5 rounded-full text-xs font-medium text-gray-500 hover:text-primary transition-colors">
                        Khác
                    </a>
                <?php elseif ($showAllYears): ?>
                    <a href="<?= getUrlParams('show_all_years', '') ?>"
                        class="px-2 py-1.5 rounded-full text-xs font-medium text-primary hover:text-secondary transition-colors">
                        Thu gọn
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">verified_user</span>
                <span class="text-sm font-semibold text-white">Xếp hạng</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= getUrlParams('age', '') ?>"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= empty($currentAge) ? $clsActive : $clsInactive ?>">
                    Tất cả
                </a>
                <?php if (isset($getAllAge)): foreach ($getAllAge as $age) : ?>
                        <a href="<?= getUrlParams('age', $age['id']) ?>"
                            class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= ($currentAge == $age['id']) ? $clsActive : $clsInactive ?>">
                            <?= $age['age'] ?> </a>
                <?php endforeach;
                endif; ?>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">language</span>
                <span class="text-sm font-semibold text-white">Phiên bản</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= getUrlParams('language', '') ?>"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= empty($currentLang) ? $clsActive : $clsInactive ?>">
                    Tất cả
                </a>
                <?php if (isset($getAllVoiceType)): foreach ($getAllVoiceType as $voice) : ?>
                        <a href="<?= getUrlParams('language', $voice['id']) ?>"
                            class="px-3 py-1.5 rounded-full text-xs font-medium transition-all <?= ($currentLang == $voice['voice_type']) ? $clsActive : $clsInactive ?>">
                            <?= $voice['voice_type'] ?>
                        </a>
                <?php endforeach;
                endif; ?>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">settings</span>
                <span class="text-sm font-semibold text-white">Chất lượng</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php
                // Xử lý hiển thị mảng chất lượng (tương tự như code cũ của bạn nhưng chuyển thành Link)
                // Lưu ý: Nếu $getAllQuality có dữ liệu động thì dùng foreach, ở đây tôi demo theo code cũ của bạn
                $qualities = isset($getAllQuality) ? $getAllQuality : [];
                foreach ($qualities as $q):
                    $isActive = ($currentQuality == $q['id']);
                    $icon = 'videocam';
                    if (strpos($q['name'], '4K') !== false) $icon = '4k';
                    if (strpos($q['name'], 'HD') !== false) $icon = 'hd';
                ?>
                    <a href="<?= getUrlParams('quality', $isActive ? '' : $q['id']) ?>"
                        class="group flex items-center gap-2 px-3 py-1.5 rounded-full border cursor-pointer transition-all <?= $isActive ? 'bg-primary/20 border-primary/30' : 'bg-white/5 border-white/10 hover:border-primary/30' ?>">

                        <span class="material-symbols-outlined text-[16px] <?= $isActive ? 'text-primary' : 'text-gray-400' ?>"><?= $icon ?></span>
                        <span class="text-xs font-medium <?= $isActive ? 'text-primary' : 'text-gray-400' ?>"><?= $q['name'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-primary text-[20px]">sort</span>
                <span class="text-sm font-semibold text-white">Sắp xếp</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <select onchange="window.location.href=this.value"
                    class="px-4 py-2 rounded-lg bg-white/5 text-gray-300 border border-white/10 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/50 hover:border-primary/30 transition-all text-sm cursor-pointer w-full">

                    <option value="<?= getUrlParams('sort', 'newest') ?>" <?= ($currentSort == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="<?= getUrlParams('sort', 'rating') ?>" <?= ($currentSort == 'rating') ? 'selected' : '' ?>>Đánh giá cao</option>
                    <option value="<?= getUrlParams('sort', 'views') ?>" <?= ($currentSort == 'views') ? 'selected' : '' ?>>Lượt xem nhiều</option>
                    <option value="<?= getUrlParams('sort', 'name_asc') ?>" <?= ($currentSort == 'name_asc') ? 'selected' : '' ?>>Tên A-Z</option>
                    <option value="<?= getUrlParams('sort', 'name_desc') ?>" <?= ($currentSort == 'name_desc') ? 'selected' : '' ?>>Tên Z-A</option>
                    <option value="<?= getUrlParams('sort', 'year_desc') ?>" <?= ($currentSort == 'year_desc') ? 'selected' : '' ?>>Năm giảm dần</option>
                    <option value="<?= getUrlParams('sort', 'year_asc') ?>" <?= ($currentSort == 'year_asc') ? 'selected' : '' ?>>Năm tăng dần</option>
                </select>
            </div>
        </div>

    </div>
</div>

<script>
    const filterToggle = document.getElementById('filterToggle');
    const filterPanel = document.getElementById('filterPanel');

    filterToggle.addEventListener('click', function() {
        filterPanel.classList.toggle('hidden');

        // Smooth scroll to panel when opening
        if (!filterPanel.classList.contains('hidden')) {
            setTimeout(() => {
                filterPanel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 100);
        }
    });
</script>