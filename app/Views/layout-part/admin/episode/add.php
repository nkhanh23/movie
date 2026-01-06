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

// Xử lý link quay lại: Giữ nguyên filter movie nếu có
$backUrl = _HOST_URL . '/admin/episode';
if (isset($_GET['filter-movie-id'])) {
    $backUrl .= '?filter-movie-id=' . $_GET['filter-movie-id'];
    if (isset($_GET['season_id'])) {
        $backUrl .= '&season_id=' . $_GET['season_id'];
    }
} else {
    // Fallback nếu không có param (như code cũ của bạn)
    $backUrl .= '/list';
}
?>

<section id="add-episode-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Tập Phim Mới</h2>
        <button onclick="window.location.href='<?php echo $backUrl; ?>'" id="btn-cancel-episode"
            class="btn"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</button>
    </div>

    <div class="card">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">

            <div class="form-group full-width" style="background: #f0f8ff; padding: 15px; border: 1px dashed #007bff; border-radius: 5px; margin-bottom: 15px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: bold; color: #0056b3;">
                    <input type="checkbox" name="is_bulk" id="is_bulk" style="width: 20px; height: 20px;"
                        <?php echo (!empty($oldData['is_bulk']) && $oldData['is_bulk'] == 'on') ? 'checked' : ''; ?>
                        onchange="toggleBulkMode()">
                    BẬT CHẾ ĐỘ THÊM NHANH (Nhiều tập cùng lúc)
                </label>
                <small style="display: block; margin-top: 5px; color: #666; margin-left: 30px;">
                    Ví dụ: Nhập từ 1 đến 16. Hệ thống tự tạo "Tập 1" -> "Tập 16" với cùng Server và Thời lượng.
                </small>
            </div>

            <div class="form-group" id="single-mode-group">
                <label for="name">Tên Tập (VD: Tập 1, The Beginning...) <span class="required">*</span></label>
                <input type="text" name="name" id="name" placeholder="Nhập tên tập..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'name') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'name') : ''); ?>
            </div>

            <div id="bulk-mode-wrapper" style="display: contents;">
                <div class="form-group" id="group-from" style="display: none;">
                    <label for="episode_from">Từ tập số <span class="required">*</span></label>
                    <input type="number" name="episode_from" id="episode_from" placeholder="VD: 1" min="1"
                        value="<?php echo !empty($oldData) ? oldData($oldData, 'episode_from') : '1'; ?>">
                    <?php echo (!empty($errors) ? formError($errors, 'episode_from') : ''); ?>
                </div>

                <div class="form-group" id="group-to" style="display: none;">
                    <label for="episode_to">Đến tập số <span class="required">*</span></label>
                    <input type="number" name="episode_to" id="episode_to" placeholder="VD: 16" min="1"
                        value="<?php echo !empty($oldData) ? oldData($oldData, 'episode_to') : ''; ?>">
                    <?php echo (!empty($errors) ? formError($errors, 'episode_to') : ''); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="server_name">Tên Server (VD: Vietsub #1) <span class="required">*</span></label>
                <input type="text" name="server_name" id="server_name" placeholder="Vietsub #1"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'server_name') : 'Vietsub #1'; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'server_name') : ''); ?>
            </div>

            <div class="form-group">
                <label for="duration">Thời lượng (phút)</label>
                <input type="number" name="duration" id="duration" placeholder="45"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'duration') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'duration') : ''); ?>
            </div>

            <div class="form-actions full-width">
                <button type="button" class="btn" onclick="window.history.back()">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Tập Phim</button>
            </div>
        </form>
    </div>
</section>

<script>
    function toggleBulkMode() {
        // 1. Lấy trạng thái checkbox
        var isBulk = document.getElementById('is_bulk').checked;

        // 2. Lấy các phần tử cần ẩn/hiện
        var singleGroup = document.getElementById('single-mode-group');
        var groupFrom = document.getElementById('group-from');
        var groupTo = document.getElementById('group-to');

        // 3. Xử lý Logic
        if (isBulk) {
            // Chế độ Thêm Nhiều: Ẩn Tên Tập -> Hiện Từ...Đến
            singleGroup.style.display = 'none';
            groupFrom.style.display = 'block';
            groupTo.style.display = 'block';
        } else {
            // Chế độ Thêm Lẻ: Hiện Tên Tập -> Ẩn Từ...Đến
            singleGroup.style.display = 'block';
            groupFrom.style.display = 'none';
            groupTo.style.display = 'none';
        }
    }

    // Chạy hàm này ngay khi trang tải xong để set đúng trạng thái (giữ lại oldData khi validate lỗi)
    window.addEventListener('DOMContentLoaded', (event) => {
        toggleBulkMode();
    });
</script>

<?php
layout('admin/footer');
?>