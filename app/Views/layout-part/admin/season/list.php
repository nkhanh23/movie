<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');

// echo '<pre>';
// (print_r($getAllSeason));
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
                onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/season/add?id=<?php echo $filterGet['filter-movie-id'] ?>'"
                id="btn-add-episode" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
        <?php else: ?>
            <button onclick="alert('Vui lòng chọn và lọc một bộ phim trước khi thêm tập mới!');" class="btn btn-primary"><i
                    class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
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
                    <th>Poster</th>
                    <th>Tên Mùa</th>
                    <th>Thuộc Phim</th>
                    <th>Chi tiết</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllSeason as $item):
                ?>
                    <tr>

                        <td><?php echo $count;
                            $count++ ?></td>
                        <td><img width="180px" src="<?php echo $item['poster_url']; ?>" alt="" referrerpolicy="no-referrer">
                        </td>
                        <td><?php echo $item['name'] ?></td>
                        <td><?php echo $item['movie_name'] ?></td>
                        <td><?php echo $item['description'] ?></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/season/edit?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/season/delete?id=<?php echo $item['id'] ?>'"
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
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. KHAI BÁO ---
        const selectContainer = document.getElementById('filter-movie-select-container');
        const trigger = selectContainer.querySelector('.select-trigger');
        const triggerText = trigger;

        // Lấy menu dropdown gốc
        const dropdown = selectContainer.querySelector('.select-dropdown');

        // Các input khác
        const seasonSelect = document.getElementById('filter-season-select');
        const hiddenMovieInput = document.getElementById('filter-movie-select');

        // Data PHP
        const allSeasonsData = <?php echo json_encode(!empty($getAllSeason) ? $getAllSeason : []); ?>;
        const oldSeasonId = "<?php echo !empty($oldData['season_id']) ? $oldData['season_id'] : ''; ?>";
        const currentMovieTypeId = "<?php echo $selectedMovieTypeId; ?>";

        // --- 2. SETUP PORTAL (Chuyển menu ra body) ---
        // Thêm class portal để nhận CSS mới
        dropdown.classList.add('global-dropdown-portal');
        // Xóa class cũ để tránh xung đột CSS (QUAN TRỌNG)
        dropdown.classList.remove('select-dropdown');

        // Di chuyển ra body
        document.body.appendChild(dropdown);

        // Lấy lại các element con sau khi di chuyển (để JS tìm thấy được)
        const searchInput = dropdown.querySelector('.select-search-box input');
        const movieOptions = dropdown.querySelectorAll('.select-option');

        // Hàm tính toán vị trí
        function updatePosition() {
            const rect = trigger.getBoundingClientRect();

            // Tính toán tọa độ theo Document (bao gồm cả thanh cuộn)
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            dropdown.style.top = (rect.bottom + scrollTop + 5) + 'px';
            dropdown.style.left = (rect.left + scrollLeft) + 'px';

            // Ép chiều rộng menu bằng đúng chiều rộng nút bấm
            dropdown.style.width = rect.width + 'px';
        }

        // --- 3. SỰ KIỆN CLICK MỞ MENU ---
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();

            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            } else {
                updatePosition(); // Tính vị trí trước khi hiện
                dropdown.classList.add('show');
                searchInput.focus();
            }
        });

        // Cập nhật vị trí liên tục khi cuộn trang hoặc resize
        window.addEventListener('scroll', () => {
            if (dropdown.classList.contains('show')) updatePosition();
        });
        window.addEventListener('resize', () => {
            if (dropdown.classList.contains('show')) updatePosition();
        });

        // Đóng khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!selectContainer.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // --- 4. LOGIC SEARCH ---
        searchInput.addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            movieOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(keyword) || option.getAttribute('data-value') === "") {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        // --- 5. LOGIC LOAD SEASON ---
        function loadSeasonsForMovie(movieId, typeId) {
            seasonSelect.innerHTML = '<option value="">-- Chọn Mùa --</option>';
            seasonSelect.value = "";

            if (typeId == '2') { // Phim bộ
                seasonSelect.disabled = false;
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
            } else { // Phim lẻ
                seasonSelect.disabled = true;
            }
        }

        // Click chọn phim
        movieOptions.forEach(option => {
            option.addEventListener('click', function() {
                const movieId = this.getAttribute('data-value');
                const typeId = this.getAttribute('data-type-id');
                const movieName = this.textContent.trim();

                hiddenMovieInput.value = movieId;
                // Cập nhật text cho nút trigger
                triggerText.innerHTML = `${movieName} <i class="fa-solid fa-chevron-down"></i>`;

                dropdown.classList.remove('show'); // Đóng menu
                loadSeasonsForMovie(movieId, typeId);
            });
        });

        // Auto Run
        if (hiddenMovieInput.value !== "") {
            loadSeasonsForMovie(hiddenMovieInput.value, currentMovieTypeId);
            if (oldSeasonId !== "") {
                seasonSelect.value = oldSeasonId;
            }
        }
    });
</script>
<?php
layout('admin/footer');
