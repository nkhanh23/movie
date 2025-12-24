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
// (print_r($oldData));
// echo '</pre>';
// die();
?>
<!-- ADD COUNTRY VIEW -->
<section id="add-country-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Chỉnh sửa Quốc gia</h2>
        <button class="btn cancel-to-country-list"><i class="fa-solid fa-arrow-left"></i> Quay lại</button>
    </div>
    <div class="card">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label>Tên Quốc gia <span class="required">*</span></label>
                <input name="name" type="text" id="country_name" placeholder="VD: Hoa Kỳ" value="<?php if (!empty($oldData)) {
                                                                                                        echo oldData($oldData, 'name');
                                                                                                    } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'name');
                }
                ?>
            </div>
            <div class="form-group">
                <label>Slug (Đường dẫn tĩnh)</label>
                <input name="slug" type="text" id="country_slug" placeholder="VD: hoa-ky" value="<?php if (!empty($oldData)) {
                                                                                                        echo oldData($oldData, 'slug');
                                                                                                    } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'slug');
                }
                ?>
            </div>
            <div class="form-actions" style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary">Lưu Quốc gia</button>
            </div>
        </form>
    </div>
</section>
<script>
    // Hàm giúp chuyển text thành slug
    function createSlug(string) {
        return string.toLowerCase()
            .normalize('NFD') // chuyển ký tự có dấu thành tổ hợp: é -> e + '
            .replace(/[\u0300-\u036f]/g, '') // xoá dấu
            .replace(/đ/g, 'd') // thay đ -> d
            .replace(/[^a-z0-9\s-]/g, '') // xoá ký tự đặc biệt
            .trim() // bỏ khoảng trắng đầu/cuối
            .replace(/\s+/g, '-') // thay khoảng trắng -> -
            .replace(/-+/g, '-'); // bỏ trùng dấu -
    }

    document.getElementById('country_name').addEventListener('input', function() {
        const getValue = this.value;
        document.getElementById('country_slug').value = createSlug(getValue);
    });
</script>