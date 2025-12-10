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
// (print_r($getAllPerson));
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
                <button type="button" class="btn btn-info" onclick="searchTMDB()">
                    <i class="fa-solid fa-search"></i> Tìm trên TMDB
                </button>
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'tittle');
                }
                ?>
                <div id="tmdb-results"
                    style="margin-top: 10px; display: none; border: 1px solid #ddd; max-height: 300px; overflow-y: auto; background: #fff; color: #333;">
                </div>
            </div>

            <div class="form-group">
                <label for="original_title">Tên Gốc (original_title)</label>
                <input type="text" name="original_title" id="original_title" placeholder="Tên gốc tiếng Anh..." value="<?php
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
                <label for="imdb_rating">Điểm imdb (imdb_rating)</label>
                <input type="number" step="0.0001" name="imdb_rating" id="imdb_rating" value="<?php
                                                                                                if (!empty($oldData)) {
                                                                                                    echo oldData($oldData, 'imdb_rating');
                                                                                                } ?>"
                    placeholder="imdb" min="1" max="10">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'imdb_rating');
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
                <label for="total_views">Tổng views</label>
                <input type="number" name="total_views" id="total_views" placeholder="120" value="<?php
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
                        <?php echo is_array($errors['genre_id']) ? reset($errors['genre_id']) : NULL; ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="status_id">Trạng thái</label>
                <select name="status_id" id="status_id">
                    <option value="">-- Chọn trạng thái --</option>
                    <?php foreach ($getAllStatus as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="type_id">Loại phim</label>
                <select name="type_id" id="type_id">
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
                <input name="trailer_url" type="text" id="trailer_url" placeholder="https://youtube.com/..."
                    value="<?php
                            if (!empty($oldData)) {
                                echo oldData($oldData, 'trailer_url');
                            } ?>">
                <?php
                if (!empty($errors)) {
                    echo formError($errors, 'trailer_url');
                }
                ?>
            </div>

            <div class="form-group full-width">
                <label>Diễn viên & Đạo diễn (Cast & Crew)</label>
                <table class="table table-bordered" id="cast-table">
                    <thead>
                        <tr>
                            <th>Nhân sự (Person)</th>
                            <th>Vai trò (Role)</th>
                            <th width="50px">Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="cast-body">
                        <?php
                        // DÀNH RIÊNG CHO TRANG EDIT: Hiển thị dữ liệu cũ
                        if (!empty($currentCast)):
                            foreach ($currentCast as $cast):
                        ?>
                                <tr class="cast-row">
                                    <td>
                                        <select name="cast_person[]" class="form-control">
                                            <option value="">-- Chọn người --</option>
                                            <?php foreach ($getAllPersons as $item): ?>
                                                <option value="<?php echo $p['id']; ?>"
                                                    <?php echo ($item['id'] == $cast['person_id']) ? 'selected' : ''; ?>>
                                                    <?php echo $item['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="cast_role[]" class="form-control">
                                            <option value="">-- Chọn vai trò --</option>
                                            <?php foreach ($getAllRoles as $item): ?>
                                                <option value="<?php echo $r['id']; ?>"
                                                    <?php echo ($item['id'] == $cast['role_id']) ? 'selected' : ''; ?>>
                                                    <?php echo $item['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeCastRow(this)"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm" onclick="addCastRow()" style="margin-top: 10px;">
                    <i class="fa fa-plus"></i> Thêm nhân sự
                </button>
            </div>

            <!-- Description (Full width) -->
            <div class="form-group full-width">
                <label for="description">Mô tả phim (description)</label>
                <textarea name="description" id="description" rows="4"
                    placeholder="Nhập tóm tắt nội dung phim..."><?php
                                                                if (!empty($oldData)) {
                                                                    echo oldData($oldData, 'description');
                                                                } ?></textarea>
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
        return string.toLowerCase()
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
<script>
    // --- CẤU HÌNH ---
    const TMDB_API_KEY = '0e3b943475e881fdc65dcdcbcc13cbaf';
    const TMDB_IMAGE_BASE = 'https://image.tmdb.org/t/p/original';

    // Helper: Chuyển text thành Slug
    function createSlug(string) {
        if (!string) return '';
        return string.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    // Event: Auto slug
    document.getElementById('tittle').addEventListener('input', function() {
        document.getElementById('slug').value = createSlug(this.value);
    });

    // Helper: Dropdown
    function toggleDropdown(id) {
        document.querySelector('#' + id + ' .dropdown-content').classList.toggle('show');
    }
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-btn') && !event.target.matches('.dropdown-btn *')) {
            const dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].classList.contains('show')) dropdowns[i].classList.remove('show');
            }
        }
    }

    // --- LOGIC TMDB ---

    // Hàm 1: Tìm kiếm phim
    async function searchTMDB() {
        const query = document.getElementById('tittle').value.trim();
        const resultBox = document.getElementById('tmdb-results');

        if (!query) {
            alert('Vui lòng nhập tên phim!');
            return;
        }

        resultBox.style.display = 'block';
        resultBox.innerHTML = '<p style="padding:10px;">Đang tìm kiếm...</p>';

        try {
            const res = await fetch(
                `https://api.themoviedb.org/3/search/movie?api_key=${TMDB_API_KEY}&query=${encodeURIComponent(query)}&language=vi-VN`
            );
            const data = await res.json();

            if (data.results?.length > 0) {
                let html = '<ul style="list-style:none; padding:0; margin:0;">';
                data.results.forEach(m => {
                    const year = m.release_date?.split('-')[0] || 'N/A';
                    const img = m.poster_path ? `https://image.tmdb.org/t/p/w92${m.poster_path}` : '';
                    html += `
                        <li style="padding:10px; border-bottom:1px solid #eee; cursor:pointer; display:flex; gap:10px; align-items:center;" 
                            onclick="selectMovie(${m.id})"
                            onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'">
                            <img src="${img}" style="width:40px; height:60px; object-fit:cover; background:#ddd;">
                            <div>
                                <strong>${m.title}</strong> (${year})<br>
                                <small style="color:#666;">${m.original_title}</small>
                            </div>
                        </li>`;
                });
                resultBox.innerHTML = html + '</ul>';
            } else {
                resultBox.innerHTML = '<p style="padding:10px;">Không tìm thấy phim nào.</p>';
            }
        } catch (e) {
            console.error(e);
            resultBox.innerHTML = '<p style="padding:10px; color:red;">Lỗi kết nối API.</p>';
        }
    }

    // Hàm 2: Chọn phim & Auto-fill (BẢN UPDATE: 4K BACKDROP)
    async function selectMovie(movieId) {
        const resultBox = document.getElementById('tmdb-results');
        resultBox.innerHTML = '<p style="padding:10px;">Đang lấy dữ liệu chi tiết...</p>';

        try {
            // Bước 1: Lấy info Tiếng Việt + Videos + Images (Thêm images vào đây)
            const res = await fetch(
                `https://api.themoviedb.org/3/movie/${movieId}?api_key=${TMDB_API_KEY}&language=vi-VN&append_to_response=videos,images&include_image_language=null,vi,en`
            );
            const movie = await res.json();

            // Khởi tạo biến fallback
            let finalOverview = movie.overview;
            let finalVideos = movie.videos?.results || [];
            let finalImages = movie.images?.backdrops || []; // Danh sách tất cả backdrop

            // --- KIỂM TRA & FALLBACK DỮ LIỆU TIẾNG ANH ---
            if (!finalOverview || finalVideos.length === 0) {
                console.log("Thiếu dữ liệu tiếng Việt, đang lấy tiếng Anh...");
                const resEn = await fetch(
                    `https://api.themoviedb.org/3/movie/${movieId}?api_key=${TMDB_API_KEY}&language=en-US&append_to_response=videos`
                );
                const movieEn = await resEn.json();

                if (!finalOverview) finalOverview = movieEn.overview;
                if (finalVideos.length === 0) finalVideos = movieEn.videos?.results || [];
            }

            console.log("Movie Data:", movie);

            // --- A. ĐIỀN CÁC TRƯỜNG CƠ BẢN ---
            document.getElementById('tittle').value = movie.title || '';
            document.getElementById('original_title').value = movie.original_title || '';
            document.getElementById('slug').value = createSlug(movie.title || '');

            if (movie.release_date) document.getElementById('release_year').value = movie.release_date.split('-')[0];
            if (movie.runtime) document.getElementById('duration').value = movie.runtime;
            if (movie.vote_average) document.getElementById('imdb_rating').value = movie.vote_average;

            // --- B. XỬ LÝ ẢNH (LOGIC MỚI CHO THUMBNAIL) ---

            // 1. Poster URL (Vẫn giữ nguyên)
            if (movie.poster_path) {
                document.getElementById('poster_url').value = TMDB_IMAGE_BASE + movie.poster_path;
            }

            // 2. Backdrop Image URL (Trường img - Vẫn giữ mặc định tốt nhất)
            if (movie.backdrop_path) {
                document.getElementById('img').value = TMDB_IMAGE_BASE + movie.backdrop_path;
            }

            // 3. THUMBNAIL URL (Xử lý 4K Backdrop)
            // Tìm ảnh có kích thước chính xác 3840x2160
            const backdrop4k = finalImages.find(img => img.width === 3840 && img.height === 2160);

            if (backdrop4k) {
                // Nếu tìm thấy ảnh 4K
                console.log("Đã tìm thấy backdrop 4K:", backdrop4k.file_path);
                document.getElementById('thumbnail').value = TMDB_IMAGE_BASE + backdrop4k.file_path;
            } else {
                // Nếu không có 4K, lấy fallback là backdrop mặc định
                console.log("Không có backdrop 4K, dùng backdrop mặc định.");
                if (movie.backdrop_path) {
                    document.getElementById('thumbnail').value = TMDB_IMAGE_BASE + movie.backdrop_path;
                } else if (movie.poster_path) {
                    // Nếu không có cả backdrop, dùng tạm poster
                    document.getElementById('thumbnail').value = TMDB_IMAGE_BASE + movie.poster_path;
                }
            }

            // --- C. XỬ LÝ TRAILER ---
            const trailer = finalVideos.find(v => v.type === 'Trailer' && v.site === 'YouTube');
            if (trailer) {
                document.getElementById('trailer_url').value = `https://www.youtube.com/watch?v=${trailer.key}`;
            }

            // --- D. XỬ LÝ MÔ TẢ ---
            if (finalOverview) {
                const desc = document.getElementById('description');
                if (desc) desc.value = finalOverview;
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['description']) {
                    CKEDITOR.instances['description'].setData(finalOverview);
                }
            }

            // --- E. AUTO SELECT QUỐC GIA ---
            if (movie.production_countries && movie.production_countries.length > 0) {
                const tmdbCountry = movie.production_countries[0].name.toLowerCase();
                const selectCountry = document.getElementById('country_id');
                const countryMap = {
                    'south korea': 'hàn quốc',
                    'united states of america': 'mỹ',
                    'united kingdom': 'anh',
                    'china': 'trung quốc',
                    'japan': 'nhật bản'
                };
                let targetCountry = countryMap[tmdbCountry] || tmdbCountry;
                for (let i = 0; i < selectCountry.options.length; i++) {
                    const optText = selectCountry.options[i].text.toLowerCase();
                    if (optText.includes(targetCountry) || targetCountry.includes(optText)) {
                        selectCountry.selectedIndex = i;
                        break;
                    }
                }
            }

            // --- F. AUTO CHECK THỂ LOẠI ---
            if (movie.genres && movie.genres.length > 0) {
                const checkboxes = document.querySelectorAll('input[name="genre_id[]"]');
                checkboxes.forEach(cb => cb.checked = false);
                movie.genres.forEach(g => {
                    const tmdbGenre = g.name.toLowerCase();
                    checkboxes.forEach(cb => {
                        const labelSpan = cb.nextElementSibling;
                        if (labelSpan) {
                            const sysGenre = labelSpan.innerText.toLowerCase();
                            if (sysGenre.includes(tmdbGenre) || tmdbGenre.includes(sysGenre)) {
                                cb.checked = true;
                            }
                        }
                    });
                });
            }

            resultBox.style.display = 'none';
            alert('Đã điền dữ liệu thành công (Đã ưu tiên Thumbnail 4K)!');

        } catch (e) {
            console.error(e);
            alert('Có lỗi xảy ra! Xem console để biết chi tiết.');
        }
    }
</script>
<script>
    // Dữ liệu từ PHP
    const allPersons = <?php echo json_encode($getAllPersons ?? []); ?>;
    const allRoles = <?php echo json_encode($getAllRoles ?? []); ?>;

    function addCastRow() {
        const tbody = document.getElementById('cast-body');
        const tr = document.createElement('tr');
        tr.className = 'cast-row';

        // 1. Tạo Select Person (Danh sách diễn viên)
        let personOptions = '<option value="">-- Chọn người --</option>';
        allPersons.forEach(p => {
            personOptions += `<option value="${p.id}">${p.name}</option>`;
        });

        // 2. Tạo Select Role (Mặc định chọn ID = 1)
        let roleOptions = '<option value="">-- Chọn vai trò --</option>';
        allRoles.forEach(r => {
            // Kiểm tra: Nếu ID là 1 thì thêm 'selected', ngược lại để trống
            const isSelected = (r.id == 1) ? 'selected' : '';

            roleOptions += `<option value="${r.id}" ${isSelected}>${r.name}</option>`;
        });

        // 3. Render HTML
        tr.innerHTML = `
            <td>
                <select name="cast_person[]" class="form-control" style="width:100%">
                    ${personOptions}
                </select>
            </td>
            <td>
                <select name="cast_role[]" class="form-control" style="width:100%">
                    ${roleOptions}
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeCastRow(this)">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
    }

    function removeCastRow(btn) {
        btn.closest('tr').remove();
    }
</script>
<?php
layout('admin/footer');
