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
<section id="support-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Yêu cầu hỗ trợ (Support)</h2>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="GET">
        <div class="toolbar">
            <div class="filters-group">
                <div id="filter-type-wrapper">
                    <select name="type" onchange="this.form.submit()">
                        <option value="">-- Tất cả loại --</option>
                        <?php if (!empty($getAllSupportType)): ?>
                            <?php foreach ($getAllSupportType as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($type) && $type == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="filter-status-wrapper">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <?php if (!empty($getAllStatus)): ?>
                            <?php foreach ($getAllStatus as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($status) && $status == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['status'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <a href="support" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-left"></i> &nbsp; Reset
                </a>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Nhập nội dung cần tìm..."
                    value="<?php echo isset($keyword) ? $keyword : '' ?>">
                <input type="hidden" name="page" value="1">
            </div>
        </div>
    </form>
    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người gửi (Fullname/Email)</th>
                    <th>Loại</th>
                    <th>Nội dung</th>
                    <th>Trạng thái</th>
                    <th>Ngày gửi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllSupport as $item):
                ?>
                    <tr>
                        <td><?php echo $count++ ?></td>
                        <td>
                            <div style="font-weight: 600;"><?php echo $item['fullname'] ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);"><?php echo $item['email'] ?></div>
                        </td>
                        <td><span class="badge info"><?php echo $item['support_type_name'] ?></span></td>
                        <td>
                            <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $item['content'] ?></div>
                        </td>
                        <td>
                            <?php
                            // Xác định màu badge theo trạng thái
                            $badgeClass = 'secondary'; // Mặc định
                            if ($item['support_status_id'] == 1) {
                                $badgeClass = 'warning'; // Chờ xử lý
                            } elseif ($item['support_status_id'] == 2) {
                                $badgeClass = 'info'; // Đang xử lý
                            } elseif ($item['support_status_id'] == 3) {
                                $badgeClass = 'success'; // Đã thực hiện
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass ?>"><?php echo $item['status_name'] ?></span>
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);"><?php echo $item['created_at'] ?></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/support/reply?id=<?php echo $item['id'] ?>'" class="btn-icon-sm" title="Phản hồi"><i class="fa-solid fa-reply"></i></button>
                                <button class="btn-icon-sm delete-btn" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $countAllSupport ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                    <button
                        onclick="window.location.href='support?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled
                        onclick="window.location.href='support?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                    <button onclick="window.location.href='support?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button
                        onclick="window.location.href='support?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
layout('admin/footer');
