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
// print_r($getAllGroup);
// echo '</pre>';
// die();
?>

<!-- ADD SUPPORT TYPE VIEW -->
<section id="add-support-type-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Thêm Loại Hỗ Trợ Mới</h2>
        <button class="btn cancel-to-support-types-list"><i class="fa-solid fa-arrow-left"></i> Quay lại</button>
    </div>
    <div class="card" style="max-width: 600px;">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="form-grid" method="POST" action="" style="grid-template-columns: 1fr;">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <div class="form-group">
                <label>Tên chủ đề <span class="required">*</span></label>
                <input name="name" type="text" id="support_type_name" placeholder="VD: Khiếu nại, Góp ý, Lỗi kỹ thuật..."
                    value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'name');
                            } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'name');
                }
                ?>
            </div>
            <div class="form-actions" style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary">Lưu Chủ đề</button>
            </div>
        </form>
    </div>
</section>