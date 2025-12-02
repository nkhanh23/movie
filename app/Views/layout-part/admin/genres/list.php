<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
// echo '<pre>';
// (print_r($getAllGenres));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');

?>
<!-- GENRES VIEW -->
<section id="genres-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Thể loại</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/genres/add'" class="btn btn-primary"><i
                class="fa-solid fa-plus"></i> Thêm Thể loại</button>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="GET">
        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Nhập tên thể loại cần tìm..."
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
                    <th>Tên Thể loại</th>
                    <th>Slug</th>
                    <th>Số lượng phim</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllGenres as $item): ?>
                <tr>
                    <td><?php echo $count;
                            $count++; ?></td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo $item['slug'] ?></td>
                    <td><?php echo $item['count_movies'] ?></td>
                    <td class="actions">
                        <div class="action-buttons">
                            <button
                                onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/genres/edit?id=<?php echo $item['id'] ?>'"
                                class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button
                                onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/genres/delete?id=<?php echo $item['id'] ?>'"
                                class="btn-icon-sm delete-btn" data-id="g2"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $countGenres ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                <button
                    onclick="window.location.href='genres?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                <button disabled
                    onclick="window.location.href='genres?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                <button onclick="window.location.href='genres?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                    class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i ?>
                </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                <button
                    onclick="window.location.href='genres?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
layout('admin/footer');