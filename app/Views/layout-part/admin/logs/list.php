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
<!-- ACTIVITY LOG FULL VIEW -->
<section id="activities-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Nhật ký hệ thống (Activity Log)</h2>
        <button class="btn"><i class="fa-solid fa-filter"></i> Bộ lọc</button>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="GET">
        <div class="toolbar">
            <div class="filters-group">
                <select name="action" onchange="this.form.submit()">
                    <option value="">Tất cả hành động</option>
                    <option value="create" <?php echo (isset($action) && $action == 'create') ? 'selected' : '' ?>>Thêm mới (Create)</option>
                    <option value="update" <?php echo (isset($action) && $action == 'update') ? 'selected' : '' ?>>Cập nhật (Update)</option>
                    <option value="delete" <?php echo (isset($action) && $action == 'delete') ? 'selected' : '' ?>>Xóa (Delete)</option>
                    <option value="login" <?php echo (isset($action) && $action == 'login') ? 'selected' : '' ?>>Đăng nhập (Login)</option>
                </select>
                <select name="user" onchange="this.form.submit()">
                    <option value="">Người thực hiện</option>
                    <option value="2" <?php echo (isset($user) && $user == '2') ? 'selected' : '' ?>>Admin</option>
                    <option value="1" <?php echo (isset($user) && $user == '1') ? 'selected' : '' ?>>Người dùng</option>
                </select>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Tìm kiếm logs..." value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </div>
    </form>
    <div class="card table-container">


        <table>
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Người dùng</th>
                    <th>Hành động</th>
                    <th>Đối tượng</th>
                    <th>Chi tiết</th>
                    <th>Hành động</th>

                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <?php
                        // Xử lý dữ liệu JSON
                        $logData = json_decode($log['new_values'], true) ?? json_decode($log['old_values'], true);
                        $targetName = $logData['tittle'] ?? $logData['name'] ?? $logData['fullname'] ?? ('ID: ' . $log['entity_id']);

                        // Entity Map
                        $entityMap = [
                            'movies'   => 'Phim',
                            'users'    => 'Người dùng',
                            'episodes' => 'Tập phim',
                            'seasons'  => 'Mùa phim',
                            'genres'   => 'Thể loại',
                            'comments' => 'Bình luận',
                            'persons' => 'Diễn viên/Đạo diễn',
                            'video_source' => 'Nguồn video'
                        ];
                        $entityName = $entityMap[$log['entity_type']] ?? $log['entity_type'];

                        // Badge và text theo action
                        $badgeClass = '';
                        $actionText = '';
                        switch ($log['action']) {
                            case 'create':
                                $badgeClass = 'success';
                                $actionText = 'Thêm mới';
                                break;
                            case 'update':
                                $badgeClass = 'warning';
                                $actionText = 'Cập nhật';
                                break;
                            case 'delete':
                                $badgeClass = 'danger';
                                $actionText = 'Xóa';
                                break;
                            case 'login':
                                $badgeClass = 'success';
                                $actionText = 'Đăng nhập';
                                $entityName = 'Auth';
                                $targetName = 'IP: ' . ($log['ip_address'] ?? 'Unknown');
                                break;
                            default:
                                $badgeClass = '';
                                $actionText = ucfirst($log['action']);
                        }
                        ?>
                        <tr>
                            <td class="text-secondary"><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></td>
                            <td><span class="badge-user"><?php echo $log['fullname'] ?? 'System'; ?></span></td>
                            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $actionText; ?></span></td>
                            <td><?php echo $entityName; ?></td>
                            <td><?php echo $targetName; ?></td>
                            <td class="actions">
                                <div class="action-buttons">
                                    <button
                                        onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/logs/delete?id=<?php echo $log['id'] ?>'"
                                        class="btn-icon-sm delete-btn" data-id="101"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            Chưa có hoạt động nào được ghi nhận
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

<?php
layout('admin/footer');
