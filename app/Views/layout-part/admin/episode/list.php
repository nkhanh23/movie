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
<section id="episodes-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Tập Phim</h2>
        <button id="btn-add-episode" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
    </div>
    <div class="toolbar">
        <div class="filters-group">
            <select id="filter-movie-select" style="min-width: 200px;">
                <option value="">-- Chọn Phim --</option>
                <option value="got">Game of Thrones</option>
                <option value="bb">Breaking Bad</option>
                <option value="st">Stranger Things</option>
            </select>

            <select id="filter-season-select" disabled style="min-width: 150px;">
                <option value="">-- Chọn Mùa --</option>
                <!-- Sẽ được load bằng JS -->
            </select>

            <button class="btn btn-primary"><i class="fa-solid fa-filter"></i> Lọc</button>
        </div>

        <div class="search-box" style="flex: 0 0 250px;">
            <i class="fa-solid fa-search"></i>
            <input type="text" placeholder="Tìm tên tập...">
        </div>
    </div>
    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Tập</th>
                    <th>Thuộc Phim</th>
                    <th>Mùa (Season)</th>
                    <th>Tập (Episode)</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>101</td>
                    <td>Winter Is Coming</td>
                    <td>Game of Thrones</td>
                    <td>Season 1</td>
                    <td>Tập 1</td>
                    <td><span class="badge success">Published</span></td>
                    <td class="actions">
                        <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn-icon-sm delete-btn" data-id="101"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>102</td>
                    <td>The Kingsroad</td>
                    <td>Game of Thrones</td>
                    <td>Season 1</td>
                    <td>Tập 2</td>
                    <td><span class="badge success">Published</span></td>
                    <td class="actions">
                        <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn-icon-sm delete-btn" data-id="102"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>201</td>
                    <td>The North Remembers</td>
                    <td>Game of Thrones</td>
                    <td>Season 2</td>
                    <td>Tập 1</td>
                    <td><span class="badge warning">Draft</span></td>
                    <td class="actions">
                        <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn-icon-sm delete-btn" data-id="201"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<?php
layout('admin/footer');
