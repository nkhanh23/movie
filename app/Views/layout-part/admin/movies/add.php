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
<script>
    // CẤU HÌNH API KEY (Thay thế bằng Key của bạn ở Bước 1)
    const TMDB_API_KEY = '0e3b943475e881fdc65dcdcbcc13cbaf';
    const TMDB_IMAGE_BASE = 'https://image.tmdb.org/t/p/original';

    // Hàm 1: Tìm kiếm phim
    async function searchTMDB() {
        const query = document.getElementById('tittle').value;
        const resultBox = document.getElementById('tmdb-results');

        if (!query.trim()) {
            alert('Vui lòng nhập tên phim để tìm kiếm!');
            return;
        }

        resultBox.style.display = 'block';
        resultBox.innerHTML = '<p style="padding: 10px;">Đang tìm kiếm...</p>';

        try {
            // Gọi API Search của TMDB
            const response = await fetch(
                `https://api.themoviedb.org/3/search/movie?api_key=${TMDB_API_KEY}&query=${encodeURIComponent(query)}&language=vi-VN`
            );
            const data = await response.json();

            if (data.results && data.results.length > 0) {
                let html = '<ul style="list-style: none; padding: 0; margin: 0;">';
                data.results.forEach(movie => {
                    const year = movie.release_date ? movie.release_date.split('-')[0] : 'N/A';
                    // Tạo từng dòng kết quả
                    html += `
                        <li style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; display: flex; align-items: center; gap: 10px;" 
                            onclick="selectMovie(${movie.id})"
                            onmouseover="this.style.background='#f0f0f0'" 
                            onmouseout="this.style.background='#fff'">
                            <img src="${movie.poster_path ? 'https://image.tmdb.org/t/p/w92' + movie.poster_path : ''}" style="width: 40px; height: 60px; object-fit: cover;">
                            <div>
                                <strong>${movie.title}</strong> (${year})<br>
                                <small>${movie.original_title}</small>
                            </div>
                        </li>
                    `;
                });
                html += '</ul>';
                resultBox.innerHTML = html;
            } else {
                resultBox.innerHTML = '<p style="padding: 10px;">Không tìm thấy phim nào.</p>';
            }
        } catch (error) {
            console.error('Lỗi:', error);
            resultBox.innerHTML = '<p style="padding: 10px; color: red;">Lỗi khi gọi API.</p>';
        }
    }

    // Hàm 2: Chọn phim và tự động điền (Fill) dữ liệu
    async function selectMovie(movieId) {
        const resultBox = document.getElementById('tmdb-results');
        resultBox.innerHTML = '<p style="padding: 10px;">Đang lấy chi tiết...</p>';

        try {
            // Gọi API chi tiết phim để lấy đầy đủ thông tin (thời lượng, thể loại...)
            const response = await fetch(
                `https://api.themoviedb.org/3/movie/${movieId}?api_key=${TMDB_API_KEY}&language=vi-VN&append_to_response=videos`
            );
            const movie = await response.json();

            // 1. Điền Tên phim & Tên gốc
            document.getElementById('tittle').value = movie.title;
            document.getElementById('original_title').value = movie.original_title;

            // 2. Điền Slug (Sử dụng hàm createSlug có sẵn của bạn)
            if (typeof createSlug === 'function') {
                document.getElementById('slug').value = createSlug(movie.title);
            }

            // 3. Điền Năm phát hành
            if (movie.release_date) {
                document.getElementById('release_year').value = movie.release_date.split('-')[0];
            }

            // 4. Điền Thời lượng
            if (movie.runtime) {
                document.getElementById('duration').value = movie.runtime;
            }

            // 5. Điền Mô tả
            if (movie.overview) {
                document.getElementById('description').value = movie.overview;
            }

            // 6. Điền URL Ảnh (Poster & Backdrop)
            if (movie.poster_path) {
                document.getElementById('poster_url').value = TMDB_IMAGE_BASE + movie.poster_path;
                document.getElementById('thumbnail').value = TMDB_IMAGE_BASE + movie
                    .poster_path; // Tạm dùng poster làm thumbnail
            }
            if (movie.backdrop_path) {
                document.getElementById('img').value = TMDB_IMAGE_BASE + movie.backdrop_path;
            }

            // 7. Điền Trailer (Tìm video loại Trailer trên Youtube)
            if (movie.videos && movie.videos.results) {
                const trailer = movie.videos.results.find(v => v.type === 'Trailer' && v.site === 'YouTube');
                if (trailer) {
                    document.getElementById('trailer_url').value = `https://www.youtube.com/watch?v=${trailer.key}`;
                }
            }

            // Ẩn bảng kết quả sau khi chọn
            resultBox.style.display = 'none';
            alert('Đã điền dữ liệu thành công! Hãy kiểm tra lại Thể loại và Quốc gia.');

        } catch (error) {
            console.error('Lỗi chi tiết:', error);
            alert('Có lỗi khi lấy chi tiết phim.');
        }
    }
</script>
<?php
layout('admin/footer');
