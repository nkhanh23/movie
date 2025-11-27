<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errors = getSessionFlash('errors');
?>

<section id="add-episode-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Tập Phim Mới</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/episode/list'" id="btn-cancel-episode"
            class="btn"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</button>
    </div>

    <div class="card">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">

            <div class="form-group">
                <label for="movie_id">Chọn Phim <span class="required">*</span></label>

                <div class="searchable-select" id="movie-select-container">
                    <?php
                    // 1. Logic hiển thị tên phim đã chọn (nếu có lỗi validate trả về)
                    $displayMovieName = '-- Chọn phim --';
                    $selectedMovieId = !empty($oldData['movie_id']) ? $oldData['movie_id'] : '';

                    if (!empty($selectedMovieId)) {
                        foreach ($getAllMovies as $m) {
                            if ($m['id'] == $selectedMovieId) {
                                $displayMovieName = $m['tittle'];
                                break;
                            }
                        }
                    }
                    ?>

                    <div class="select-trigger" id="movie-trigger">
                        <?php echo $displayMovieName; ?> <i class="fa-solid fa-chevron-down"></i>
                    </div>

                    <input type="hidden" id="movie-select-input" name="movie_id"
                        value="<?php echo $selectedMovieId; ?>">

                    <div class="select-dropdown">
                        <div class="select-search-box">
                            <input type="text" id="movie-search-box" placeholder="Gõ tên phim để tìm...">
                        </div>
                        <ul class="select-options-list" id="movie-options-list">
                            <?php if (!empty($getAllMovies)): ?>
                                <?php foreach ($getAllMovies as $item): ?>
                                    <li class="select-option" data-value="<?php echo $item['id']; ?>"
                                        data-type-id="<?php echo $item['type_id']; ?>">
                                        <?php echo $item['tittle']; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php echo (!empty($errors) ? formError($errors, 'movie_id') : ''); ?>
            </div>

            <div class="form-group">
                <label for="season_id">Chọn Mùa (Season)</label>
                <select name="season_id" id="season-select" disabled>
                    <option value="">-- Vui lòng chọn phim trước --</option>
                </select>
                <?php echo (!empty($errors) ? formError($errors, 'season_id') : ''); ?>
            </div>

            <div class="form-group">
                <label for="name">Tên Tập (VD: Tập 1, The Beginning...) <span class="required">*</span></label>
                <input type="text" name="name" id="name" placeholder="Nhập tên tập..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'name') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'name') : ''); ?>
            </div>

            <div class="form-group">
                <label for="episode_number">Số Tập (episode_number)</label>
                <input type="number" name="episode_number" id="episode_number" placeholder="1"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'episode_number') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'episode_number') : ''); ?>
            </div>

            <div class="form-group">
                <label for="server_name">Tên Server (VD: Vietsub #1)</label>
                <input type="text" name="server_name" id="server_name" placeholder="Vietsub #1"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'server_name') : 'Vietsub #1'; ?>">
            </div>

            <div class="form-group">
                <label for="video_source_id">ID Nguồn Video (video_source_id)</label>
                <input type="number" name="video_source_id" id="video_source_id" placeholder="Nhập ID nguồn..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'video_source_id') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'video_source_id') : ''); ?>
            </div>

            <div class="form-group">
                <label for="duration">Thời lượng (phút)</label>
                <input type="number" name="duration" id="duration" placeholder="45"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'duration') : ''; ?>">
            </div>

            <div class="form-group">
                <label for="sort_order">Thứ tự sắp xếp</label>
                <input type="number" name="sort_order" id="sort_order" placeholder="1"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'sort_order') : '1'; ?>">
            </div>

            <div class="form-actions full-width">
                <button type="button" class="btn" onclick="window.history.back()">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Tập Phim</button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. KHAI BÁO BIẾN ---
        // Nếu không có dữ liệu hoặc bị null, trả về mảng rỗng [] để JS không bị lỗi
        const allSeasons = <?php echo !empty($getAllSeasons) ? json_encode($getAllSeasons) : '[]'; ?>;
        const oldSeasonId = "<?php echo isset($oldData['season_id']) ? $oldData['season_id'] : ''; ?>";

        // Element liên quan đến Custom Select Phim
        const container = document.getElementById('movie-select-container');
        const trigger = document.getElementById('movie-trigger');
        const hiddenInput = document.getElementById('movie-select-input');
        const dropdown = container.querySelector('.select-dropdown');
        const searchBox = document.getElementById('movie-search-box');
        const optionsList = document.getElementById('movie-options-list');
        const options = optionsList.querySelectorAll('.select-option');

        // Element chọn Season (Vẫn là Select thường)
        const seasonSelect = document.getElementById('season-select');

        // --- 2. HÀM LOAD SEASON (Logic cũ) ---
        function loadSeasons(movieId) {
            seasonSelect.innerHTML = '<option value="">-- Chọn Mùa --</option>';
            seasonSelect.disabled = true;

            if (!movieId) return;

            // Lọc season theo movie_id
            const filteredSeasons = allSeasons.filter(season => season.movie_id == movieId);

            if (filteredSeasons.length > 0) {
                seasonSelect.disabled = false;
                filteredSeasons.forEach(season => {
                    const option = document.createElement('option');
                    option.value = season.id;
                    option.textContent = season.name + ' (' + season.season_number + ')';
                    if (oldSeasonId == season.id) option.selected = true;
                    seasonSelect.appendChild(option);
                });
            } else {
                const opt = document.createElement('option');
                opt.textContent = "-- Phim này chưa có Season nào --";
                seasonSelect.appendChild(opt);
            }
        }

        // --- 3. XỬ LÝ GIAO DIỆN TÌM KIẾM PHIM ---

        // Toggle Dropdown khi bấm vào trigger
        trigger.addEventListener('click', function(e) {
            container.classList.toggle('active'); // Bạn cần CSS class .active để hiện dropdown
            if (container.classList.contains('active')) {
                searchBox.focus(); // Focus vào ô tìm kiếm ngay
            }
        });

        // Đóng dropdown khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!container.contains(e.target)) {
                container.classList.remove('active');
            }
        });

        // Tìm kiếm (Filter) phim
        searchBox.addEventListener('input', function() {
            const filterText = this.value.toLowerCase();
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(filterText)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        // Xử lý khi chọn một phim trong danh sách
        options.forEach(option => {
            option.addEventListener('click', function() {
                const movieId = this.getAttribute('data-value');
                const movieName = this.textContent;

                // 1. Cập nhật UI
                trigger.innerHTML = movieName + ' <i class="fa-solid fa-chevron-down"></i>';
                hiddenInput.value = movieId;

                // 2. Đóng dropdown
                container.classList.remove('active');

                // 3. GỌI HÀM LOAD SEASON (Quan trọng nhất)
                loadSeasons(movieId);
            });
        });

        // --- 4. TỰ ĐỘNG CHẠY KHI LOAD TRANG (Sticky Data) ---
        if (hiddenInput.value) {
            loadSeasons(hiddenInput.value);
        }
    });
</script>


<?php
layout('admin/footer');
?>