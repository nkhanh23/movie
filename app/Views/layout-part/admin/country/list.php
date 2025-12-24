<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
// echo '<pre>';
// (print_r($getAllCountryWithCount));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>
<!-- COUNTRIES VIEW -->
<section id="countries-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Danh sách Quốc gia</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/country/add'" id="btn-add-country" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Quốc gia</button>
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
                    <th style="width: 80px;">ID</th>
                    <th>Tên Quốc gia</th>
                    <th>Slug</th>
                    <th style="width: 150px;">Số lượng phim</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllCountryWithCount as $item):
                ?>
                    <tr>
                        <td><?php echo $count;
                            $count++; ?></td>
                        <td style="font-weight: 500;"><?php echo $item['name'] ?></td>
                        <td><code><?php echo $item['slug'] ?></code></td>
                        <td><span class="badge info"><?php echo $item['count_movies'] ?> phim</span></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/country/edit?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/country/delete?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm delete-btn"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-3 trên <?php echo $countAllCountries ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                    <button
                        onclick="window.location.href='country?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled
                        onclick="window.location.href='country?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                    <button onclick="window.location.href='country?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button
                        onclick="window.location.href='country?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>