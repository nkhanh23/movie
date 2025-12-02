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
<section id="add-user-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Phim Mới</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/film/list'" id="btn-cancel-user"
            class="btn"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</button>
    </div>

    <div class="card">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="idUser" value="<?php echo $idUser ?>">


            <!-- Cột 1 -->
            <div class="form-group">
                <label for="fullname">Tên <span class="required">*</span></label>
                <input type="text" name="fullname" id="fullname" placeholder="Nhập tên người dùng..." value="<?php
                                                                                                                if (!empty($oldData)) {
                                                                                                                    echo oldData($oldData, 'fullname');
                                                                                                                } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'fullname');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="Email..." value="<?php
                                                                                            if (!empty($oldData)) {
                                                                                                echo oldData($oldData, 'email');
                                                                                            } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'email');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" placeholder="Password...">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'password');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="group_id">Vai trò</label>
                <select name="group_id" id="group_id">
                    <option value="">-- Chọn vai trò --</option>
                    <?php foreach ($getAllGroup as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (!empty($oldData['group_id']) && $oldData['group_id'] == $item['id']) ? 'selected' : '' ?>>
                            <?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status_id">Trạng thái</label>
                <select name="status_id" id="status_id">
                    <option value="">-- Chọn trạng thái --</option>
                    <?php foreach ($getAllUserStatus as $item): ?>
                        <option value="<?php echo $item['id']; ?>"
                            <?php echo (!empty($oldData['status']) && $oldData['status'] == $item['id']) ? 'selected' : '' ?>>
                            <?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Footer Actions -->
            <div class="form-actions full-width">
                <button type="button" class="btn">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Phim</button>
            </div>
        </form>
    </div>
</section>
<script>
    // Hàm bật tắt dropdown
    function toggleDropdown(id) {
        var content = document.querySelector('#' + id + ' .dropdown-content');
        content.classList.toggle('show');
    }

    // Sự kiện: Bấm ra ngoài thì đóng tất cả dropdown
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-btn') && !event.target.matches('.dropdown-btn *')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

<?php
layout('admin/footer');
