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

<!-- Filter Toggle Button-->
<button id="filterToggle"
    class="glass-panel rounded-full p-4 mb-4 border border-glass-border hover:bg-primary/20 hover:shadow-neon-sm transition-all cursor-pointer group">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-2xl group-hover:rotate-180 transition-transform duration-300">tune</span>
        <span class="text-white font-semibold hidden md:inline">Bộ lọc</span>
    </div>
</button>

<!-- Filter Panel (Collapsible) -->
<div id="filterPanel" class="glass-panel rounded-2xl p-4 md:p-6 lg:p-10 mb-8 border border-glass-border">

    <form method="GET" id="filterForm">
        <!-- Hidden fields để giữ route -->
        <input type="hidden" name="mod" value="<?= $_GET['mod'] ?? '' ?>">
        <input type="hidden" name="act" value="<?= $_GET['act'] ?? '' ?>">
        <div class="space-y-6 mb-6">

            <!-- Thể loại (Multi-select) -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">movie_filter</span>
                    <span class="text-sm font-semibold text-white">Thể loại</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($getAllGenres as $genre) :
                        $checked = in_array($genre['id'], (array)($filters['genres'] ?? [])) ? 'checked' : '';
                    ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>" <?= $checked ?>>
                            <span class="filter-label"><?= $genre['name'] ?></span>
                        </label>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- Quốc gia (Multi-select) -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">public</span>
                    <span class="text-sm font-semibold text-white">Quốc gia</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($getAllCountries as $country) :
                        $checked = in_array($country['id'], (array)($filters['countries'] ?? [])) ? 'checked' : '';
                    ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" name="countries[]" value="<?= $country['id'] ?>" <?= $checked ?>>
                            <span class="filter-label"><?= $country['name'] ?></span>
                        </label>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Loại phim -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">theaters</span>
                    <span class="text-sm font-semibold text-white">Loại phim</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($getAllTypes as $type) :
                        $checked = ($currentType == $type['id']) ? 'checked' : '';
                    ?>
                        <label class="filter-checkbox">
                            <input type="radio" name="types" value="<?= $type['id'] ?>" <?= $checked ?>>
                            <span class="filter-label"><?= $type['name'] ?></span>
                        </label>
                    <?php endforeach ?>
                </div>
            </div>

            <!-- Năm phát hành -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">calendar_month</span>
                    <span class="text-sm font-semibold text-white">Năm phát hành</span>
                </div>
                <select name="release_year" class="filter-select">
                    <option value="">Tất cả</option>
                    <?php foreach ($getAllReleaseYear as $year) : ?>
                        <option value="<?= $year['id'] ?>" <?= ($currentYear == $year['id']) ? 'selected' : '' ?>>
                            <?= $year['year'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Xếp hạng -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">verified_user</span>
                    <span class="text-sm font-semibold text-white">Xếp hạng</span>
                </div>
                <select name="age" class="filter-select">
                    <option value="">Tất cả</option>
                    <?php if (isset($getAllAge)): foreach ($getAllAge as $age) : ?>
                            <option value="<?= $age['id'] ?>" <?= ($currentAge == $age['id']) ? 'selected' : '' ?>>
                                <?= $age['age'] ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>

            <!-- Phiên bản -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">language</span>
                    <span class="text-sm font-semibold text-white">Phiên bản</span>
                </div>
                <select name="language" class="filter-select">
                    <option value="">Tất cả</option>
                    <?php if (isset($getAllVoiceType)): foreach ($getAllVoiceType as $voice) : ?>
                            <option value="<?= $voice['voice_type'] ?>" <?= ($currentLang == $voice['voice_type']) ? 'selected' : '' ?>>
                                <?= $voice['voice_type'] ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>

            <!-- Chất lượng -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">settings</span>
                    <span class="text-sm font-semibold text-white">Chất lượng</span>
                </div>
                <select name="quality" class="filter-select">
                    <option value="">Tất cả</option>
                    <?php
                    $qualities = isset($getAllQuality) ? $getAllQuality : [];
                    foreach ($qualities as $q):
                    ?>
                        <option value="<?= $q['id'] ?>" <?= ($currentQuality == $q['id']) ? 'selected' : '' ?>>
                            <?= $q['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sắp xếp -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-primary text-[20px]">sort</span>
                    <span class="text-sm font-semibold text-white">Sắp xếp</span>
                </div>
                <select name="sort" class="filter-select">
                    <option value="newest" <?= ($currentSort == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="rating" <?= ($currentSort == 'rating') ? 'selected' : '' ?>>Đánh giá cao</option>
                    <option value="views" <?= ($currentSort == 'views') ? 'selected' : '' ?>>Lượt xem nhiều</option>
                    <option value="name_asc" <?= ($currentSort == 'name_asc') ? 'selected' : '' ?>>Tên A-Z</option>
                    <option value="name_desc" <?= ($currentSort == 'name_desc') ? 'selected' : '' ?>>Tên Z-A</option>
                    <option value="year_desc" <?= ($currentSort == 'year_desc') ? 'selected' : '' ?>>Năm giảm dần</option>
                    <option value="year_asc" <?= ($currentSort == 'year_asc') ? 'selected' : '' ?>>Năm tăng dần</option>
                </select>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6 md:mt-8">
            <button type="button" onclick="resetFilters()" class="btn-reset">
                <span class="material-symbols-outlined text-[18px]">refresh</span>
                Xóa bộ lọc
            </button>
            <button type="submit" class="btn-submit">
                <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                Áp Dụng Bộ Lọc
            </button>
        </div>
    </form>
</div>

<script>
    const filterToggle = document.getElementById('filterToggle');
    const filterPanel = document.getElementById('filterPanel');

    filterToggle.addEventListener('click', function() {
        // Chỉ cần toggle class - CSS transition sẽ xử lý animation mượt mà
        filterPanel.classList.toggle('filter-open');

        // Smooth scroll khi mở (optional)
        if (filterPanel.classList.contains('filter-open')) {
            // Đợi animation bắt đầu rồi mới scroll
            requestAnimationFrame(() => {
                filterPanel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            });
        }
    });

    // Reset filters - redirect to base URL
    function resetFilters() {
        const mod = '<?= $_GET['mod'] ?? '' ?>';
        const act = '<?= $_GET['act'] ?? '' ?>';

        // Redirect về base URL tương ứng
        window.location.href = `<?= _HOST_URL ?>/${mod}/${act}`;
    }
</script>