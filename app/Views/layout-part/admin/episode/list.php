<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');

// echo '<pre>';
// (print_r($getAllEpisode));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>
<section id="episodes-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Tập Phim</h2>
        <?php if (!empty($filterGet['filter-movie-id'])): ?>
            <button
                onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/episode/add?id=<?php echo $filterGet['filter-movie-id'] ?>&season_id=<?php echo $filterGet['season_id'] ?>'"
                id="btn-add-episode" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
        <?php else: ?>
            <button onclick="alert('Vui lòng chọn và lọc một bộ phim và mùa trước khi thêm tập mới!');"
                class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
        <?php endif; ?>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="">
        <div class="toolbar">
            <div class="filters-group">
                <!-- REPLACED: Native Select -> Custom Searchable Select -->
                <div class="searchable-select" id="filter-movie-select-container">
                    <?php
                    // 1. Khởi tạo giá trị mặc định
                    $displayMovieName = '-- Chọn Phim (Tìm kiếm) --';
                    $selectedMovieTypeId = ''; // Biến này sẽ dùng để truyền xuống JS logic

                    // 2. Nếu có dữ liệu cũ (đã lọc), tìm tên phim để hiển thị
                    if (!empty($oldData['filter-movie-id'])) {
                        foreach ($getAllMovies as $m) {
                            if ($m['id'] == $oldData['filter-movie-id']) {
                                $displayMovieName = $m['tittle']; // Lấy tên phim
                                $selectedMovieTypeId = $m['type_id']; // Lấy loại phim (để xử lý hiện/ẩn season)
                                break; // Tìm thấy rồi thì dừng vòng lặp
                            }
                        }
                    }
                    ?>

                    <div class="select-trigger">
                        <?php echo $displayMovieName; ?> <i class="fa-solid fa-chevron-down"></i>
                    </div>

                    <input type="hidden" id="filter-movie-select" name="filter-movie-id"
                        value="<?php echo !empty($oldData['filter-movie-id']) ? $oldData['filter-movie-id'] : '' ?>">

                    <div class="select-dropdown">
                        <div class="select-search-box">
                            <input type="text" placeholder="Gõ tên phim để tìm...">
                        </div>
                        <ul class="select-options-list">
                            <li class="select-option" data-value="">-- Chọn Phim --</li>

                            <?php foreach ($getAllMovies as $item): ?>
                                <li class="select-option" data-value="<?php echo $item['id']; ?>"
                                    data-type-id="<?php echo $item['type_id']; ?>">

                                    <?php echo $item['tittle']; ?> (<?php echo $item['original_tittle']; ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <select name="season_id" id="filter-season-select" style="min-width: 150px;" disabled>
                    <option value="">-- Chọn Mùa --</option>
                    <!-- Sẽ được load bằng JS -->
                </select>

                <button class="btn btn-primary"><i class="fa-solid fa-filter"></i> Lọc</button>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Tìm tên tập..."
                    value="<?php echo !empty($oldData['keyword']) ? $oldData['keyword'] : '' ?>">
            </div>
        </div>
    </form>

    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Tập</th>
                    <th>Thuộc Phim</th>
                    <th>Mùa (Season)</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllEpisode as $item):
                ?>
                    <tr>

                        <td><?php echo $count;
                            $count++ ?></td>
                        <td><?php echo $item['episode_name'] ?></td>
                        <td><?php echo $item['movie_name'] ?></td>
                        <td><?php echo $item['season_name'] ?></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/episode/edit?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/episode/delete?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm delete-btn" data-id="101"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $countResult; ?> kết quả</span>
            <div class="page-controls">
                <?php
                // Logic nối chuỗi: Nếu còn dữ liệu lọc thì thêm dấu &, nếu không thì thôi
                $prefixLink = !empty($queryString) ? "?$queryString&page=" : "?page=";
                ?>
                <?php if ($page > 1): ?>
                    <button onclick="window.location.href='<?php echo $prefixLink . ($page - 1) ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled onclick="window.location.href='<?php echo $prefixLink . ($page - 1) ?>">Trước</button>
                <?php endif; ?>
                <?php
                $start = $page - 1;
                if ($start < 1) {
                    $start = 1;
                }
                $end = $page + 1;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <button onclick="window.location.href='<?php echo $prefixLink . $i ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button onclick="window.location.href='<?php echo $prefixLink . ($page + 1) ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>



<!-- Khi click chọn phim -> Lấy data-type-id -> Kiểm tra -> Mở/Khóa ô Season. -->
<script>
    // --- KHAI BÁO DỮ LIỆU TỪ PHP SANG JS (QUAN TRỌNG) ---
    // Chuyển mảng PHP thành mảng Object JSON để JS xử lý
    const allSeasonsData = <?php echo json_encode($getAllSeasons); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. KHAI BÁO DOM ELEMENTS ---
        const selectContainer = document.getElementById('filter-movie-select-container');
        const trigger = selectContainer.querySelector('.select-trigger');
        const triggerText = trigger;
        const dropdown = selectContainer.querySelector('.select-dropdown');
        const seasonSelect = document.getElementById('filter-season-select');
        const hiddenMovieInput = document.getElementById('filter-movie-select');

        // Data config
        const oldSeasonId = "<?php echo !empty($oldData['season_id']) ? $oldData['season_id'] : ''; ?>";
        // Lấy type_id của phim đang chọn (nếu có)
        const currentMovieTypeId = "<?php echo $selectedMovieTypeId; ?>";

        // --- 2. SETUP PORTAL (Chuyển menu ra body để tránh lỗi z-index/overflow) ---
        dropdown.classList.add('global-dropdown-portal');
        dropdown.classList.remove('select-dropdown');
        document.body.appendChild(dropdown);

        const searchInput = dropdown.querySelector('.select-search-box input');
        const movieOptions = dropdown.querySelectorAll('.select-option');

        // Hàm update vị trí dropdown
        function updatePosition() {
            const rect = trigger.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            dropdown.style.top = (rect.bottom + scrollTop + 5) + 'px';
            dropdown.style.left = (rect.left + scrollLeft) + 'px';
            dropdown.style.width = rect.width + 'px';
        }

        // --- 3. SỰ KIỆN CLICK MỞ/ĐÓNG ---
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            } else {
                updatePosition();
                dropdown.classList.add('show');
                searchInput.focus();
            }
        });

        // Sự kiện cuộn/resize để cập nhật vị trí
        window.addEventListener('scroll', () => {
            if (dropdown.classList.contains('show')) updatePosition();
        });
        window.addEventListener('resize', () => {
            if (dropdown.classList.contains('show')) updatePosition();
        });

        // Click ra ngoài thì đóng
        document.addEventListener('click', function(e) {
            if (!selectContainer.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // --- 4. TÌM KIẾM PHIM ---
        searchInput.addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            movieOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                const val = option.getAttribute('data-value');
                // Luôn hiện option mặc định (value="") hoặc option khớp từ khóa
                if (text.includes(keyword) || val === "") {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        // --- 5. HÀM LOAD SEASON (SỬ DỤNG DỮ LIỆU ĐÃ FIX) ---
        function loadSeasonsForMovie(movieId, typeId) {
            // Reset ô chọn season
            seasonSelect.innerHTML = '<option value="">-- Chọn Mùa --</option>';
            seasonSelect.value = "";

            if (!movieId) {
                seasonSelect.disabled = true;
                return;
            }

            // Kiểm tra type_id = 2 (Phim bộ) thì mới hiện season
            // Lưu ý: typeId lấy từ HTML attribute là string, nên so sánh lỏng (==) hoặc ép kiểu
            if (typeId == '2') {
                seasonSelect.disabled = false;

                // Lọc season thuộc movie_id này
                // allSeasonsData bây giờ đã có dữ liệu
                const filteredSeasons = allSeasonsData.filter(item => item.movie_id == movieId);

                if (filteredSeasons.length > 0) {
                    filteredSeasons.forEach(season => {
                        const opt = document.createElement('option');
                        opt.value = season.id;
                        opt.textContent = season.name;
                        seasonSelect.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.textContent = "Chưa có dữ liệu mùa";
                    seasonSelect.appendChild(opt);
                }
            } else {
                // Phim lẻ (type_id != 2) -> Khóa ô season
                seasonSelect.disabled = true;
            }
        }

        // --- 6. XỬ LÝ KHI CHỌN PHIM ---
        movieOptions.forEach(option => {
            option.addEventListener('click', function() {
                const movieId = this.getAttribute('data-value');
                const typeId = this.getAttribute('data-type-id');
                const movieName = this.textContent.trim();

                hiddenMovieInput.value = movieId;

                // Cập nhật giao diện nút trigger
                if (movieId === "") {
                    triggerText.innerHTML =
                        `-- Chọn Phim (Tìm kiếm) -- <i class="fa-solid fa-chevron-down"></i>`;
                } else {
                    triggerText.innerHTML = `${movieName} <i class="fa-solid fa-chevron-down"></i>`;
                }

                dropdown.classList.remove('show');

                // Gọi hàm load season
                loadSeasonsForMovie(movieId, typeId);
            });
        });

        // --- 7. TỰ ĐỘNG CHẠY KHI RELOAD (GIỮ TRẠNG THÁI CŨ) ---
        if (hiddenMovieInput.value !== "") {
            // currentMovieTypeId đã được PHP xử lý ở đầu file
            loadSeasonsForMovie(hiddenMovieInput.value, currentMovieTypeId);

            if (oldSeasonId !== "") {
                seasonSelect.value = oldSeasonId;
            }
        } else {
            seasonSelect.disabled = true;
        }
    });
</script>

<?php
layout('admin/footer');
