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
<script>
    // Chuyển đổi dữ liệu PHP sang JSON để JS sử dụng
    // Lưu ý: Xóa đoạn debug print_r($getAllSeasons) cũ của bạn đi nhé
    const allSeasonsData = <?php echo json_encode($getAllSeasons); ?>;

    console.log("Dữ liệu Seasons đã tải:", allSeasonsData); // F12 để kiểm tra
</script>
<section id="episodes-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Tập Phim</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/episode/add'" id="btn-add-episode"
            class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
    </div>
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
                                <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn-icon-sm delete-btn" data-id="101"><i
                                        class="fa-solid fa-trash"></i></button>
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
        // --- KHAI BÁO BIẾN ---
        const movieOptions = document.querySelectorAll('#filter-movie-select-container .select-option');
        const seasonSelect = document.getElementById('filter-season-select');
        const hiddenMovieInput = document.getElementById('filter-movie-select');
        const triggerText = document.querySelector('.select-trigger');

        // Lấy dữ liệu cũ từ PHP in ra (nếu có)
        const oldSeasonId = "<?php echo !empty($oldData['season_id']) ? $oldData['season_id'] : ''; ?>";
        // Lấy Type ID của phim đang chọn (được tính toán ở Bước 1 PHP)
        const currentMovieTypeId = "<?php echo $selectedMovieTypeId; ?>";

        // --- HÀM XỬ LÝ LOAD MÙA (Tách ra để tái sử dụng) ---
        function loadSeasonsForMovie(movieId, typeId) {
            // Reset ô chọn mùa
            seasonSelect.innerHTML = '<option value="">-- Chọn Mùa --</option>';
            seasonSelect.value = "";

            if (typeId == '2') { // Phim bộ
                seasonSelect.disabled = false;

                // Lọc dữ liệu từ biến JSON allSeasonsData (đã khai báo ở đầu file)
                const filteredSeasons = allSeasonsData.filter(function(item) {
                    return item.movie_id == movieId;
                });

                if (filteredSeasons.length > 0) {
                    filteredSeasons.forEach(function(season) {
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

        // --- SỰ KIỆN CLICK CHỌN PHIM (Code cũ của bạn, đã tối ưu gọi hàm) ---
        movieOptions.forEach(option => {
            option.addEventListener('click', function() {
                const movieId = this.getAttribute('data-value');
                const typeId = this.getAttribute('data-type-id');
                const movieName = this.textContent;

                // Cập nhật UI
                hiddenMovieInput.value = movieId;
                triggerText.innerHTML = movieName + ' <i class="fa-solid fa-chevron-down"></i>';

                // Gọi hàm load mùa
                loadSeasonsForMovie(movieId, typeId);
            });
        });

        // --- QUAN TRỌNG: TỰ ĐỘNG CHẠY KHI RELOAD TRANG ---
        // Nếu input hidden đang có giá trị (tức là vừa bấm lọc xong)
        if (hiddenMovieInput.value !== "") {
            // 1. Load lại danh sách mùa dựa trên ID phim đang có
            loadSeasonsForMovie(hiddenMovieInput.value, currentMovieTypeId);

            // 2. Nếu trước đó có chọn mùa, thì gán lại giá trị cho ô select
            if (oldSeasonId !== "") {
                seasonSelect.value = oldSeasonId;
            }
        }
    });
</script>
<?php
layout('admin/footer');
