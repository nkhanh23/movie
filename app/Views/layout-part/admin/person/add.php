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
                <label>Loại phim</label>
                <div class="custom-dropdown" id="dropdown-genres">
                    <div class="dropdown-btn" onclick="toggleDropdown('dropdown-genres')">
                        <span>-- Chọn vai trò --</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>

                    <div class="dropdown-content">
                        <?php foreach ($getAllPersonRole as $item): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="role_id[]" value="<?php echo $item['id']; ?>" <?php
                                                                                                            echo (isset($oldData['role_id']) && is_array($oldData['role_id']) && in_array($item['id'], $oldData['genre_id'])) ? 'checked' : '';
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
                    <img id="preview-avatar" src=""
                        style="max-width: 150px; display: none; border-radius: 5px; border: 1px solid #ddd;">
                </div>
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'avatar');
                }
                ?>
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
</script>

<script>
    // CẤU HÌNH API KEY
    const TMDB_API_KEY = '<?php echo defined('_TMDB_API_KEY') ? _TMDB_API_KEY : ''; ?>';
    const TMDB_IMAGE_BASE = '<?php echo defined('_TMDB_IMAGE_BASE') ? _TMDB_IMAGE_BASE : 'https://image.tmdb.org/t/p/original'; ?>';
    const TMDB_AVATAR_THUMB = '<?php echo defined('_TMDB_AVATAR_THUMB') ? _TMDB_AVATAR_THUMB : 'https://image.tmdb.org/t/p/w185'; ?>'; // Ảnh nhỏ cho list gợi ý

    // Hàm 1: Tìm kiếm NGƯỜI (Person)
    async function searchTMDB() {
        const query = document.getElementById('name').value;
        const resultBox = document.getElementById('tmdb-results');

        if (!query.trim()) {
            alert('Vui lòng nhập tên diễn viên/đạo diễn để tìm kiếm!');
            return;
        }

        resultBox.style.display = 'block';
        resultBox.innerHTML = '<p style="padding: 10px; text-align: center;">Đang tìm kiếm...</p>';

        try {
            // Gọi API Search Person
            const response = await fetch(
                `https://api.themoviedb.org/3/search/person?api_key=${TMDB_API_KEY}&query=${encodeURIComponent(query)}&language=vi-VN`
            );
            const data = await response.json();

            if (data.results && data.results.length > 0) {
                let html = '<ul style="list-style: none; padding: 0; margin: 0;">';
                data.results.forEach(person => {
                    const knownFor = person.known_for_department || 'N/A';
                    const avatar = person.profile_path ?
                        TMDB_AVATAR_THUMB + person.profile_path :
                        'https://via.placeholder.com/45x60?text=No+Img';

                    // Tạo từng dòng kết quả
                    html += `
                        <li style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; align-items: center; gap: 10px;" 
                            onclick="selectPerson(${person.id})"
                            onmouseover="this.style.background='#f0f0f0'" 
                            onmouseout="this.style.background='#fff'">
                            <img src="${avatar}" style="width: 45px; height: 60px; object-fit: cover; border-radius: 4px;">
                            <div>
                                <strong style="font-size: 14px; color: #333;">${person.name}</strong><br>
                                <small style="color: #666;">Phòng ban: ${knownFor}</small>
                            </div>
                        </li>
                    `;
                });
                html += '</ul>';
                resultBox.innerHTML = html;
            } else {
                resultBox.innerHTML = '<p style="padding: 10px; color: #d9534f;">Không tìm thấy người này.</p>';
            }
        } catch (error) {
            console.error('Lỗi:', error);
            resultBox.innerHTML = '<p style="padding: 10px; color: red;">Lỗi kết nối đến TMDB.</p>';
        }
    }

    // Hàm 2: Chọn người và tự động điền (Fill) dữ liệu

    async function selectPerson(personId) {
        const resultBox = document.getElementById('tmdb-results');
        // Ẩn bảng kết quả
        if (resultBox) resultBox.style.display = 'none';

        try {
            // BƯỚC 1: Gọi API lấy thông tin tiếng Việt
            const resVi = await fetch(
                `https://api.themoviedb.org/3/person/${personId}?api_key=${TMDB_API_KEY}&language=vi-VN`
            );
            const personVi = await resVi.json();

            // --- Điền các thông tin cơ bản (Tên, Slug, Avatar) từ kết quả tiếng Việt ---

            // 1. Tên
            document.getElementById('name').value = personVi.name;

            // 2. Slug
            if (typeof createSlug === 'function') {
                document.getElementById('slug').value = createSlug(personVi.name);
            }

            // 3. Avatar
            if (personVi.profile_path) {
                const fullAvatarUrl = TMDB_IMAGE_BASE + personVi.profile_path;
                document.getElementById('avatar').value = fullAvatarUrl;

                // Hiển thị ảnh preview
                const previewImg = document.getElementById('preview-avatar');
                if (previewImg) {
                    previewImg.src = fullAvatarUrl;
                    previewImg.style.display = 'block';
                }
            }

            // --- BƯỚC 2: XỬ LÝ TIỂU SỬ (FALLBACK TIẾNG ANH) ---

            // Kiểm tra xem có tiểu sử tiếng Việt không
            if (personVi.biography && personVi.biography.trim() !== "") {
                document.getElementById('bio').value = personVi.biography;
            } else {
                // Nếu KHÔNG có -> Gọi tiếp API lấy tiếng Anh
                try {
                    // Hiển thị thông báo tạm trong lúc tải tiếng Anh
                    document.getElementById('bio').placeholder = "Đang tải tiểu sử tiếng Anh...";

                    const resEn = await fetch(
                        `https://api.themoviedb.org/3/person/${personId}?api_key=${TMDB_API_KEY}&language=en-US`
                    );
                    const personEn = await resEn.json();

                    if (personEn.biography && personEn.biography.trim() !== "") {
                        document.getElementById('bio').value = personEn.biography;
                    } else {
                        document.getElementById('bio').value = "";
                        document.getElementById('bio').placeholder = "Không tìm thấy tiểu sử trên TMDB.";
                    }
                } catch (errEn) {
                    console.error("Lỗi lấy tiếng Anh:", errEn);
                }
            }

            alert(`Đã lấy dữ liệu: ${personVi.name}`);

        } catch (error) {
            console.error('Lỗi chi tiết:', error);
            alert('Có lỗi khi lấy chi tiết diễn viên.');
        }
    }

    // Click ra ngoài thì đóng bảng tìm kiếm
    document.addEventListener('click', function(e) {
        const resultBox = document.getElementById('tmdb-results');
        const searchBtn = document.querySelector('.btn-info'); // Nút tìm kiếm
        // Nếu click không trúng box kết quả và không trúng nút tìm kiếm
        if (resultBox && e.target !== resultBox && !resultBox.contains(e.target) && !searchBtn.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });
</script>

<?php
layout('admin/footer');
