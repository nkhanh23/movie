<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');

// echo '<pre>';
// (print_r($getAllSources));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>

<!-- SOURCES VIEW -->
<section id="sources-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Nguồn Video (Video Sources)</h2>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="">
        <div class="toolbar">
            <div class="filters-group">
                <div class="searchable-select" id="filter-movie-select-container">
                    <?php
                    // 1. Khởi tạo giá trị mặc định
                    $displayMovieName = '-- Chọn Phim (Tìm kiếm) --';
                    $selectedMovieTypeId = ''; // Biến này sẽ dùng để truyền xuống JS logic

                    // 2. Nếu có dữ liệu cũ (đã lọc), tìm tên phim để hiển thị
                    if (!empty($oldData['filter-movie-id'])) {
                        foreach ($getAllMovies as $m) {
                            if ($m['id'] == $oldData['filter-movie-id']) {
                                $displayMovieName = $m['tittle']; // Lấy tên phim
                                $selectedMovieTypeId = $m['type_id']; // Lấy loại phim (để xử lý hiện/ẩn season)
                                break; // Tìm thấy rồi thì dừng vòng lặp
                            }
                        }
                    }
                    ?>

                    <div class="select-trigger">
                        <?php echo $displayMovieName; ?> <i class="fa-solid fa-chevron-down"></i>
                    </div>

                    <input type="hidden" id="filter-movie-select" name="filter-movie-id"
                        value="<?php echo !empty($oldData['filter-movie-id']) ? $oldData['filter-movie-id'] : '' ?>">

                    <div class="select-dropdown">
                        <div class="select-search-box">
                            <input type="text" placeholder="Gõ tên phim để tìm...">
                        </div>
                        <ul class="select-options-list">
                            <li class="select-option" data-value="">-- Chọn Phim --</li>

                            <?php foreach ($getAllMovies as $item): ?>
                                <li class="select-option" data-value="<?php echo $item['id']; ?>"
                                    data-type-id="<?php echo $item['type_id']; ?>">

                                    <?php echo $item['tittle']; ?> (<?php echo $item['original_tittle']; ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <select name="season_id" id="filter-season-select" style="min-width: 150px;" disabled>
                    <option value="">-- Chọn Mùa --</option>
                </select>

                <select name="episode_id" id="filter-episode-select" style="min-width: 150px;" disabled>
                    <option value="">-- Chọn Tập --</option>
                </select>

                <button class="btn btn-primary"><i class="fa-solid fa-filter"></i> Lọc</button>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Tìm tên tập..."
                    value="<?php echo !empty($oldData['keyword']) ? $oldData['keyword'] : '' ?>">
            </div>
        </div>
    </form>
    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th>Phim / Tập</th>
                    <th>Loại</th>
                    <th>URL Nguồn</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($getAllSources)): ?>
                    <?php foreach ($getAllSources as $item): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500;"><?php echo $item['movie_name'] ?? 'N/A'; ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">
                                    <?php echo $item['episode_name'] ?? 'N/A'; ?>
                                    <?php if (!empty($item['season_name'])): ?>
                                        (<?php echo $item['season_name']; ?>)
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php echo $item['voice_type']; ?>
                            </td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--accent-blue);">
                                <?php echo $item['source_url']; ?>
                            </td>
                            <td class="actions">
                                <div class="action-buttons">
                                    <button
                                        onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/source/edit?id=<?php echo $item['id'] ?>'"
                                        class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                                    <button
                                        onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/source/delete?id=<?php echo $item['id'] ?>'"
                                        class="btn-icon-sm delete-btn"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                            <i class="fa-solid fa-database" style="font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                            Không có nguồn video nào
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $countResult; ?> kết quả</span>
            <div class="page-controls">
                <?php
                // Logic nối chuỗi: Nếu còn dữ liệu lọc thì thêm dấu &, nếu không thì thôi
                $prefixLink = !empty($queryString) ? "?$queryString&page=" : "?page=";
                ?>
                <?php if ($page > 1): ?>
                    <button onclick="window.location.href='<?php echo $prefixLink . ($page - 1) ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled onclick="window.location.href='<?php echo $prefixLink . ($page - 1) ?>">Trước</button>
                <?php endif; ?>
                <?php
                $start = $page - 1;
                if ($start < 1) {
                    $start = 1;
                }
                $end = $page + 1;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <button onclick="window.location.href='<?php echo $prefixLink . $i ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button onclick="window.location.href='<?php echo $prefixLink . ($page + 1) ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    const allSeasonsData = <?php echo json_encode($getAllSeasons); ?>;
    const allEpisodesData = <?php echo json_encode($getAllEpisodes); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const selectContainer = document.getElementById('filter-movie-select-container');
        const trigger = selectContainer.querySelector('.select-trigger');
        const triggerText = trigger;
        const dropdown = selectContainer.querySelector('.select-dropdown');
        const seasonSelect = document.getElementById('filter-season-select');
        const episodeSelect = document.getElementById('filter-episode-select');
        const hiddenMovieInput = document.getElementById('filter-movie-select');

        const oldSeasonId = "<?php echo !empty($oldData['season_id']) ? $oldData['season_id'] : ''; ?>";
        const oldEpisodeId = "<?php echo !empty($oldData['episode_id']) ? $oldData['episode_id'] : ''; ?>";
        const currentMovieTypeId = "<?php echo $selectedMovieTypeId; ?>";

        dropdown.classList.add('global-dropdown-portal');
        dropdown.classList.remove('select-dropdown');
        document.body.appendChild(dropdown);

        const searchInput = dropdown.querySelector('.select-search-box input');
        const movieOptions = dropdown.querySelectorAll('.select-option');

        function updatePosition() {
            const rect = trigger.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            dropdown.style.top = (rect.bottom + scrollTop + 5) + 'px';
            dropdown.style.left = (rect.left + scrollLeft) + 'px';
            dropdown.style.width = rect.width + 'px';
        }

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            } else {
                updatePosition();
                dropdown.classList.add('show');
                searchInput.focus();
            }
        });

        window.addEventListener('scroll', () => {
            if (dropdown.classList.contains('show')) updatePosition();
        });
        window.addEventListener('resize', () => {
            if (dropdown.classList.contains('show')) updatePosition();
        });

        document.addEventListener('click', function(e) {
            if (!selectContainer.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        searchInput.addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            movieOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                const val = option.getAttribute('data-value');
                if (text.includes(keyword) || val === "") {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        function loadSeasonsForMovie(movieId, typeId) {
            seasonSelect.innerHTML = '<option value="">-- Chọn Mùa --</option>';
            seasonSelect.value = "";
            episodeSelect.innerHTML = '<option value="">-- Chọn Tập --</option>';
            episodeSelect.value = "";
            episodeSelect.disabled = true;

            if (!movieId) {
                seasonSelect.disabled = true;
                return;
            }

            if (typeId == '2') {
                seasonSelect.disabled = false;

                const filteredSeasons = allSeasonsData.filter(item => item.movie_id == movieId);

                if (filteredSeasons.length > 0) {
                    filteredSeasons.forEach(season => {
                        const opt = document.createElement('option');
                        opt.value = season.id;
                        opt.textContent = season.name;
                        seasonSelect.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.textContent = "Chưa có dữ liệu mùa";
                    seasonSelect.appendChild(opt);
                }
            } else {
                seasonSelect.disabled = true;
                // Phim lẻ - load episodes trực tiếp từ movie_id
                loadEpisodesForMovie(movieId);
            }
        }

        function loadEpisodesForMovie(movieId) {
            episodeSelect.innerHTML = '<option value="">-- Chọn Tập --</option>';
            episodeSelect.value = "";

            if (!movieId) {
                episodeSelect.disabled = true;
                return;
            }

            episodeSelect.disabled = false;
            const filteredEpisodes = allEpisodesData.filter(item => item.movie_id == movieId);

            if (filteredEpisodes.length > 0) {
                filteredEpisodes.forEach(episode => {
                    const opt = document.createElement('option');
                    opt.value = episode.id;
                    opt.textContent = episode.name;
                    episodeSelect.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.textContent = "Chưa có dữ liệu tập";
                episodeSelect.appendChild(opt);
            }
        }

        function loadEpisodesForSeason(seasonId) {
            episodeSelect.innerHTML = '<option value="">-- Chọn Tập --</option>';
            episodeSelect.value = "";

            if (!seasonId) {
                episodeSelect.disabled = true;
                return;
            }

            episodeSelect.disabled = false;
            const filteredEpisodes = allEpisodesData.filter(item => item.season_id == seasonId);

            if (filteredEpisodes.length > 0) {
                filteredEpisodes.forEach(episode => {
                    const opt = document.createElement('option');
                    opt.value = episode.id;
                    opt.textContent = episode.name;
                    episodeSelect.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.textContent = "Chưa có dữ liệu tập";
                episodeSelect.appendChild(opt);
            }
        }

        movieOptions.forEach(option => {
            option.addEventListener('click', function() {
                const movieId = this.getAttribute('data-value');
                const typeId = this.getAttribute('data-type-id');
                const movieName = this.textContent.trim();

                hiddenMovieInput.value = movieId;

                if (movieId === "") {
                    triggerText.innerHTML =
                        `-- Chọn Phim (Tìm kiếm) -- <i class="fa-solid fa-chevron-down"></i>`;
                } else {
                    triggerText.innerHTML = `${movieName} <i class="fa-solid fa-chevron-down"></i>`;
                }

                dropdown.classList.remove('show');

                loadSeasonsForMovie(movieId, typeId);
            });
        });

        // Event listener cho season dropdown
        seasonSelect.addEventListener('change', function() {
            const seasonId = this.value;
            if (seasonId) {
                loadEpisodesForSeason(seasonId);
            } else {
                episodeSelect.innerHTML = '<option value="">-- Chọn Tập --</option>';
                episodeSelect.disabled = true;
            }
        });

        if (hiddenMovieInput.value !== "") {
            loadSeasonsForMovie(hiddenMovieInput.value, currentMovieTypeId);

            if (oldSeasonId !== "") {
                seasonSelect.value = oldSeasonId;
                loadEpisodesForSeason(oldSeasonId);
            } else if (currentMovieTypeId != '2') {
                // Phim lẻ - load episodes từ movie
                loadEpisodesForMovie(hiddenMovieInput.value);
            }

            if (oldEpisodeId !== "") {
                setTimeout(() => {
                    episodeSelect.value = oldEpisodeId;
                }, 100);
            }
        } else {
            seasonSelect.disabled = true;
            episodeSelect.disabled = true;
        }
    });
</script>

<?php
layout('admin/footer');
