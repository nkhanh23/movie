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
// (print_r($getAllGenres));
// echo '</pre>';
// die();
?>
<section id="add-movie-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Thêm Phim Mới</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/film/list'" id="btn-cancel-movie"
            class="btn"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</button>
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
                <label for="duration">Tổng views</label>
                <input type="number" name="total_views" id="duration" placeholder="120" value="<?php
                                                                                                if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'total_views');
                                                                                                } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'total_views');
                }
                ?>
            </div>

            <div class="form-group">
                <label for="country_id">Quốc gia (country_id)</label>
                <select name="country_id" id="country_id">
                    <option value="">-- Chọn quốc gia --</option>
                    <?php foreach ($getAllCountries as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Cột 2 -->
            <div class="form-group">
                <label>Loại phim</label>
                <div class="custom-dropdown" id="dropdown-genres">
                    <div class="dropdown-btn" onclick="toggleDropdown('dropdown-genres')">
                        <span>-- Chọn loại phim (Nhiều) --</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>

                    <div class="dropdown-content">
                        <?php foreach ($getAllGenres as $item): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="genre_id[]" value="<?php echo $item['id']; ?>" <?php
                                                                                                            echo (isset($oldData['genre_id']) && is_array($oldData['genre_id']) && in_array($item['id'], $oldData['genre_id'])) ? 'checked' : '';
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

            <div class="form-group">
                <label for="status_id">Trạng thái</label>
                <select name="status_id" id="genre_id">
                    <option value="">-- Chọn trạng thái --</option>
                    <?php foreach ($getAllStatus as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="country_id">Loại phim</label>
                <select name="status_id" id="genre_id">
                    <option value="">-- Chọn loại phim --</option>
                    <?php foreach ($getAllType as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
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

    document.getElementById('tittle').addEventListener('input', function() {
        const getValue = this.value;
        document.getElementById('slug').value = createSlug(getValue);
    });
</script>
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
