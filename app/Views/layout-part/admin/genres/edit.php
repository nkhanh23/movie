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
<!-- ADD GENRE VIEW (NEW SECTION) -->
<section id="add-genre-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Thể loại Mới</h2>
        <button type="button" class="btn cancel-to-genre-list"><i class="fa-solid fa-arrow-left"></i> Quay lại</button>
    </div>

    <div class="card">
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $idGenres ?>">

            <div class="form-group full-width">
                <label for="name">Tên Thể loại <span class="required">*</span></label>
                <input type="text" name="name" id="name" placeholder="Ví dụ: Hành động" value="<?php if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'name');
                                                                                                } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'name');
                }
                ?>
            </div>

            <div class="form-group full-width">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" placeholder="hanh-dong" value="<?php if (!empty($oldData)) {
                                                                                            echo oldData($oldData, 'slug');
                                                                                        } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'slug');
                }
                ?>
            </div>

            <div class="form-actions full-width">
                <button type="button" class="btn cancel-to-genre-list">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Thể loại</button>
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
