<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
// echo '<pre>';
// (print_r($getAllPersonWithCount));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>

<!-- PERSONS VIEW -->
<section id="persons-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Diễn viên / Đạo diễn</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/person/add'" class="btn btn-primary"><i
                class="fa-solid fa-plus"></i> Thêm Người</button>
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
                    <select name="role" onchange="this.form.submit()">
                        <option value="">-- Tất cả Vai trò --</option>
                        <?php if (!empty($getAllPersonRole)): ?>
                            <?php foreach ($getAllPersonRole as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($role) && $role == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <a href="person" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> &nbsp; Reset
                </a>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Nhập tên phim cần tìm..."
                    value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
                <input type="hidden" name="page" value="<?php echo (!isset($keyword)) ? $keyword : '' ?>">
            </div>
        </div>
    </form>
    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Poster</th>
                    <th>Họ tên</th>
                    <th>Vai trò chính</th>
                    <th>Số phim</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllPersonWithCount as $item):
                ?>
                    <tr>
                        <td><?php echo $count;
                            $count++; ?></td>
                        <td><img loading="lazy" width="100px" src="<?php echo $item['avatar']; ?>" alt=""></td>
                        <td><?php echo $item['name'] ?></td>
                        <td><?php echo $item['role_name'] ?></td>
                        <td><?php echo $item['count_movies'] ?></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/person/edit?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/person/delete?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm delete-btn" data-id="p1"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-3 trên <?php echo $countAllPersons ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                    <button
                        onclick="window.location.href='person?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled
                        onclick="window.location.href='person?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                    <button onclick="window.location.href='person?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button
                        onclick="window.location.href='person?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
layout('admin/footer');
