<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
// echo '<pre>';
// (print_r($getMovies));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>
<section id="movies-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Phim</h2>

        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/film/add'" class="btn btn-primary"><i
                class="fa-solid fa-plus"></i>
            Thêm Phim Mới</button>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="GET">
        <div class="toolbar">
            <div class="filters-group">
                <div id="filter-genre-wrapper">
                    <select name="genres" onchange="this.form.submit()">
                        <option value="">-- Tất cả Thể loại --</option>
                        <?php if (!empty($getAllGenres)): ?>
                            <?php foreach ($getAllGenres as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($genres) && $genres == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="filter-genre-wrapper">
                    <select name="types" onchange="this.form.submit()">
                        <option value="">-- Tất cả Loại Phim --</option>
                        <?php if (!empty($getAllMovieTypes)): ?>
                            <?php foreach ($getAllMovieTypes as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($types) && $types == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="filter-status-wrapper">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">-- Tất cả Trạng thái --</option>
                        <?php if (!empty($getStatus)): ?>
                            <?php foreach ($getStatus as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($status) && $status == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="filter-country-wrapper">
                    <select name="countries" onchange="this.form.submit()">
                        <option value="">-- Tất cả Quốc gia --</option>
                        <?php if (!empty($getCountries)): ?>
                            <?php foreach ($getCountries as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($countries) && $countries == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <a href="list" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> &nbsp; Reset
                </a>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Nhập tên phim cần tìm..."
                    value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
                <input type="hidden" name="page" value="1">
            </div>
        </div>
    </form>


    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Poster</th>
                    <th>Tên Phim</th>
                    <th>Thể loại</th>
                    <th>Quốc Gia</th>
                    <th>Loại phim</th>
                    <th>Trạng thái</th>
                    <th>IMDB</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $count = 1;
                foreach ($getMovies as $item):
                ?>
                    <tr>
                        <td><?php echo $count;
                            $count++ ?></td>
                        <td><img loading="lazy" width="100px" src="<?php echo $item['thumbnail']; ?>" alt=""></td>
                        <td><?php echo $item['tittle']; ?></td>
                        <td><?php echo $item['genres']; ?></td>
                        <td><?php echo $item['country_name']; ?></td>
                        <td><?php echo $item['type_name']; ?></td>
                        <td><span class="badge success"><?php echo $item['movie_status']; ?></span></td>
                        <td><?php echo $item['imdb_rating']; ?></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/film/view'"
                                    class="btn-icon-sm" title="Chi tiết"><i class="fa-solid fa-eye"></i></button>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/film/edit?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm" title="Sửa"><i class="fa-solid fa-pen"></i></button>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/film/delete?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm delete-btn" data-id="1" title="Xóa"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $countMovies ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                    <button
                        onclick="window.location.href='list?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled
                        onclick="window.location.href='list?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                    <button onclick="window.location.href='list?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button
                        onclick="window.location.href='list?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
layout('admin/footer');
