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
<!-- ADD PERSON VIEW (New Section) -->
<section id="add-person-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Diễn viên / Đạo diễn</h2>
        <button type="button" class="btn cancel-to-person-list"><i class="fa-solid fa-arrow-left"></i> Quay lại</button>
    </div>

    <div class="card">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $idPerson ?>">
            <div class="form-group full-width" style="position: relative;"> <label for="name">Họ và tên (name) <span
                        class="required">*</span></label>

                <div style="display: flex; gap: 10px;">
                    <input name="name" type="text" id="name" placeholder="Ví dụ: Leonardo DiCaprio" style="flex: 1;"
                        value="<?php if (!empty($oldData)) {
                                    echo oldData($oldData, 'name');
                                } ?>">
                </div>
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'name');
                }
                ?>
            </div>

            <div class="form-group full-width">
                <label for="slug">Slug</label>
                <input name="slug" type="text" id="slug" placeholder="leonardo-dicaprio" value="<?php if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'slug');
                                                                                                } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'slug');
                }
                ?>
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <div class="custom-dropdown" id="dropdown-genres">
                    <div class="dropdown-btn" onclick="toggleDropdown('dropdown-genres')">
                        <span>-- Chọn vai trò --</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>

                    <div class="dropdown-content">
                        <?php
                        $checkRole = [];
                        //TH1 : Submit lỗi, lấy lại những gì user vừa tick
                        if (isset($oldData['role_id']) && is_array($oldData['role_id'])) {
                            $checkedGenres = $oldData['role_id'];
                        } elseif (isset($selectedRoleId)) {
                            $checkRole = $selectedRoleId;
                        }
                        ?>
                        <?php foreach ($getAllPersonRole as $item): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="role_id[]" value="<?php echo $item['id']; ?>" <?php
                                                                                                            echo in_array($item['id'], $checkRole) ? 'checked' : '';
                                                                                                            ?>>
                                <span><?php echo $item['name']; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if (!empty($errors['genre_id'])): ?>
                    <span class="required" style="font-size: 0.85rem; margin-top: 5px;">
                        <?php echo is_array($errors['genre_id']) ? reset($errors['genre_id']) : $errors['type_id']; ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="form-group full-width">
                <label for="avatar">Avatar URL</label>
                <input name="avatar" type="text" id="avatar" placeholder="https://..." value="<?php if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'avatar');
                                                                                                } ?>">

                <div style="margin-top: 10px;">
                    <?php
                    $currentAvatar = (!empty($oldData) && !empty(oldData($oldData, 'avatar'))) ? oldData($oldData, 'avatar') : '';
                    $displayStyle = !empty($currentAvatar) ? 'block' : 'none';
                    ?>
                    <img id="preview-avatar" src="<?php echo $currentAvatar; ?>"
                        style="max-width: 150px; display: <?php echo $displayStyle; ?>; border-radius: 5px; border: 1px solid #ddd;">
                </div>

                <?php if (!empty($errors)): ?>
                    <?php echo formError($errors, 'avatar'); ?>
                <?php endif; ?>
            </div>

            <div class="form-group full-width">
                <label for="bio">Tiểu sử (bio)</label>
                <textarea name="bio" id="bio" rows="4" placeholder="Nhập thông tin tiểu sử..."><?php if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'bio');
                                                                                                } ?></textarea>
            </div>

            <div class="form-actions full-width">
                <button type="button" class="btn cancel-to-person-list">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Người</button>
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

    document.getElementById('name').addEventListener('input', function() {
        const getValue = this.value;
        document.getElementById('slug').value = createSlug(getValue);
    });

    // 2. Hàm hiển thị Preview ảnh (Giữ lại theo yêu cầu)
    document.getElementById('avatar').addEventListener('input', function() {
        const url = this.value.trim();
        const previewImg = document.getElementById('preview-avatar');

        if (url) {
            previewImg.src = url;
            previewImg.style.display = 'block';
        } else {
            previewImg.src = '';
            previewImg.style.display = 'none';
        }
    });

    // Xử lý fallback nếu ảnh lỗi
    document.getElementById('preview-avatar').addEventListener('error', function() {
        this.style.display = 'none'; // Ẩn nếu link ảnh chết
    });
</script>


<?php
layout('admin/footer');
