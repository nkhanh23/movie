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

// echo '<pre>';
// (print_r($errors));
// echo '</pre>';

?>
<section id="add-movie-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Phim Mới</h2>
        <button id="btn-cancel-movie" class="btn"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</button>
    </div>

    <div class="card">
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="form-grid" method="POST" action="" enctype="multipart/form-data">
            <!-- Cột 1 -->
            <div class="form-group">
                <label for="title">Tên Phim (title) <span class="required">*</span></label>
                <input type="text" name="tittle" id="tittle" placeholder="Nhập tên phim..." value="<?php
                                                                                                    if (!empty($oldData)) {
                                                                                                        echo oldData($oldData, 'tittle');
                                                                                                    } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'tittle');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="original_title">Tên Gốc (original_title)</label>
                <input type="text" name="original_title" id="original_title" placeholder="Tên gốc tiếng Anh..."
                    value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'original_title');
                            } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'original_title');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="slug">Đường dẫn tĩnh (slug)</label>
                <input type="text" name="slug" id="slug" placeholder="ten-phim-2023" value="<?php
                                                                                            if (!empty($oldData)) {
                                                                                                echo oldData($oldData, 'slug');
                                                                                            } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'slug');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="release_year">Năm phát hành (release_year)</label>
                <input type="number" name="release_year" id="release_year" value="<?php
                                                                                    if (!empty($oldData)) {
                                                                                        echo oldData($oldData, 'release_year');
                                                                                    } ?>" placeholder="2023" min="1900"
                    max="2100">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'release_year');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="duration">Thời lượng (phút) (duration)</label>
                <input type="number" name="duration" id="duration" placeholder="120" value="<?php
                                                                                            if (!empty($oldData)) {
                                                                                                echo oldData($oldData, 'duration');
                                                                                            } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'duration');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="country_id">Quốc gia (country_id)</label>
                <select name="country_id" id="country_id">
                    <option value="">-- Chọn quốc gia --</option>
                    <option value="1">Việt Nam</option>
                </select>
            </div>

            <!-- Cột 2 -->
            <div class="form-group">
                <label for="type_id">Loại phim (type_id)</label>
                <select name="type_id" id="type_id">
                    <option value="1">Phim lẻ (Movie)</option>
                    <option value="2">Phim bộ (Series)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status_id">Trạng thái (status_id)</label>
                <select name="status_id" id="status_id">
                    <option value="1">Xuất bản (Published)</option>
                    <option value="0">Bản nháp (Draft)</option>
                    <option value="2">Sắp chiếu (Coming Soon)</option>
                </select>
            </div>

            <!-- URLs -->
            <div class="form-group">
                <label for="poster_url">Poster URL</label>
                <input name="poster_url" type="text" id="poster_url" placeholder="https://..." value="<?php
                                                                                                        if (!empty($oldData)) {
                                                                                                            echo oldData($oldData, 'poster_url');
                                                                                                        } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'poster_url');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="thumbnail">Thumbnail URL</label>
                <input name="thumbnail" type="text" id="thumbnail" placeholder="https://..." value="<?php
                                                                                                    if (!empty($oldData)) {
                                                                                                        echo oldData($oldData, 'thumbnail');
                                                                                                    } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'thumbnail');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="img">Backdrop Image URL (img)</label>
                <input name="img" type="text" id="img" placeholder="https://..." value="<?php
                                                                                        if (!empty($oldData)) {
                                                                                            echo oldData($oldData, 'img');
                                                                                        } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'img');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="trailer_url">Trailer URL</label>
                <input name="trailer_url" type="text" id="trailer_url" placeholder="https://youtube.com/..." value="<?php
                                                                                                                    if (!empty($oldData)) {
                                                                                                                        echo oldData($oldData, 'trailer_url');
                                                                                                                    } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'trailer_url');
                }
                ?>
            </div>

            <!-- Description (Full width) -->
            <div class="form-group full-width">
                <label for="description">Mô tả phim (description)</label>
                <textarea name="description" id="description" rows="4" placeholder="Nhập tóm tắt nội dung phim..."
                    value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'description');
                            } ?>"></textarea>
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'description');
                }
                ?>
            </div>

            <!-- Footer Actions -->
            <div class="form-actions full-width">
                <button type="button" class="btn">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Phim</button>
            </div>
        </form>
    </div>
</section>
<?php
layout('admin/footer');
