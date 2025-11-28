<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// die();
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
            <input type="hidden" name="id" value="<?php echo $idSeason ?>">
            <div class="form-group">
                <label for="name">Tên Mùa (VD: Tập 1, The Beginning...) <span class="required">*</span></label>
                <input type="text" name="name" id="name" placeholder="Nhập tên mùa..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'name') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'name') : ''); ?>
            </div>

            <div class="form-group">
                <label for="description">Chi tiết</label>
                <input type="text" name="description" id="description" placeholder="1"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'description') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'description') : ''); ?>
            </div>

            <div class="form-group">
                <label for="poster_url">Poster URL</label>
                <input type="text" name="poster_url" id="poster_url" placeholder="Poster URL..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'poster_url') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'trailer_url') : ''); ?>

            </div>

            <div class="form-group">
                <label for="trailer_url">Trailer URL</label>
                <input type="text" name="trailer_url" id="trailer_url" placeholder="Trailer URL..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'trailer_url') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'trailer_url') : ''); ?>
            </div>

            <div class="form-group">
                <label for="status_id">Trạng thái</label>
                <select name="status_id" id="genre_id">
                    <option value="">-- Chọn trạng thái --</option>
                    <?php foreach ($getAllStatus as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo ($oldData['status_id'] == $item['id']) ? 'selected' : '' ?>>
                            <?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
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