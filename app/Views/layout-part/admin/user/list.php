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

<!-- USERS VIEW -->
<section id="users-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Người dùng</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/user/add'" class="btn btn-primary"
            class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm người dùng</button>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="GET">
        <div class="toolbar">
            <div class="filters-group">
                <div id="filter-status-wrapper">
                    <select name="group" onchange="this.form.submit()">
                        <option value="">-- Tất cả vai trò --</option>
                        <?php if (!empty($getAllGroup)): ?>
                            <?php foreach ($getAllGroup as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($group) && $group == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="filter-status-wrapper">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <?php if (!empty($getAllUserStatus)): ?>
                            <?php foreach ($getAllUserStatus as $item): ?>
                                <option value="<?php echo $item['id'] ?>"
                                    <?php echo (isset($status) && $status == $item['id']) ? 'selected' : '' ?>>
                                    <?php echo $item['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <a href="user" class="btn btn-secondary">
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
                    <th>Username</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                foreach ($getAllUser as $item):
                ?>
                    <tr>
                        <td><?php echo $count;
                            $count++ ?></td>
                        <td><?php echo $item['fullname'] ?></td>
                        <td><?php echo $item['email'] ?></td>
                        <td><?php echo $item['group_name'] ?></td>
                        <?php if ($item['status'] == 1): ?>
                            <td><span class="badge success">Active</span></td>
                        <?php else: ?>
                            <td><span class="badge danger">Inactive</span></td>
                        <?php endif; ?>
                        <td class="actions">
                            <div class="action-buttons">
                                <butto
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/user/edit?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm"><i class="fa-solid fa-pen"></i></butto>
                                <button
                                    onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/user/delete?id=<?php echo $item['id'] ?>'"
                                    class="btn-icon-sm delete-btn" data-id="1" title="Xóa"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $countAllUser ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                    <button
                        onclick="window.location.href='user?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled
                        onclick="window.location.href='user?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                    <button onclick="window.location.href='user?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button
                        onclick="window.location.href='user?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
layout('admin/footer');
