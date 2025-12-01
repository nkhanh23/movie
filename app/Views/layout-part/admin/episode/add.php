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
                <label for="name">Tên Tập (VD: Tập 1, The Beginning...) <span class="required">*</span></label>
                <input type="text" name="name" id="name" placeholder="Nhập tên tập..."
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'name') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'name') : ''); ?>
            </div>

            <div class="form-group">
                <label for="server_name">Tên Server (VD: Vietsub #1)</label>
                <input type="text" name="server_name" id="server_name" placeholder="Vietsub #1"
                    value="<?php echo !empty($oldData) ? oldData($oldData, 'server_name') : ''; ?>">
                <?php echo (!empty($errors) ? formError($errors, 'server_name') : ''); ?>

            </div>

            <div class="form-group">
                <label for="video_source_id">Nguồn Video</label>
                <select name="video_source_id" id="video_source_id">
                    <option value="">-- Chọn nguồn video --</option>
                    <?php foreach ($getAllVideoSource as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['source_name']; ?></option>
                    <?php endforeach; ?>
                </select>
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




<?php
layout('admin/footer');
?>