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
// (print_r($getAllGenres));
// echo '</pre>';
// die();
?>
<!-- ADD ROLE VIEW (New Section) -->
<section id="add-role-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Vai trò Mới</h2>
        <button type="button" class="btn cancel-to-role-list"><i class="fa-solid fa-arrow-left"></i> Quay lại</button>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>

    <div class="card">
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $role_id; ?>">
            <div class="form-group full-width">
                <label for="role_name">Tên Vai trò (name) <span class="required">*</span></label>
                <input name="name" type="text" id="name" placeholder="Ví dụ: Producer (Nhà sản xuất)"
                    value="<?php echo (!empty($oldData)) ? oldData($oldData, 'name') : NULL ?>">
                <?php echo (!empty($errors)) ? formError($errors, 'name') : NULL ?>
            </div>

            <div class="form-group full-width">
                <label for="role_slug">Slug</label>
                <input name="slug" type="text" id="slug" placeholder="producer"
                    value="<?php echo (!empty($oldData)) ? oldData($oldData, 'slug') : NULL ?>">
                <?php echo (!empty($errors)) ? formError($errors, 'slug') : NULL ?>
            </div>

            <div class="form-group full-width">
                <label for="role_description">Mô tả (description)</label>
                <textarea name="description" id="description" rows="3"
                    placeholder="Mô tả ngắn về vai trò này..."><?php echo (!empty($oldData)) ? oldData($oldData, 'description') : NULL ?></textarea>
                <?php echo (!empty($errors)) ? formError($errors, 'description') : NULL ?>
            </div>

            <div class="form-actions full-width">
                <button type="button" class="btn cancel-to-role-list">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Vai trò</button>
            </div>
        </form>
    </div>
</section>

<script>
// Hàm giúp chuyển text thành slug
function createSlug(string) {
    return strig.toLowerCase()
        .normalize('NFD') // chuyển ký tự có dấu thành tổ hợp: é -> e + '
        .replace(/[\u0300-\u036f]/g, '') // xoá dấu
        .replace(/đ/g, 'd') // thay đ -> d
        .replace(/[^a-z0-9\s-]/g, '') // xoá ký tự đặc biệt
        .trim() // bỏ khoảng trắng đầu/cuối
        .replace(/\s+/g, '-') // thay khoảng trắng -> -
        .replace(/-+/g, '-'); // bỏ trùng dấu -
}

document.getElementById('name').addEventListener('input', function() {
    const getValue = this.value;
    document.getElementById('slug').value = createSlug(getValue);
});
</script>

<?php
layout('admin/footer');