<?php
// echo '<pre>';
// print_r($getLatestMoviesLogs);
// echo '</pre>';
// die();
?>
<!-- HEADER -->
<?php layout('admin/header'); ?>

<!-- SIDEBAR -->
<?php layout('admin/sidebar'); ?>


<!-- MAIN CONTENT -->
<main class="main-content">

    <!-- DASHBOARD VIEW -->
    <section id="dashboard-view" class="content-section active">
        <div class="page-header">
            <h2>Dashboard</h2>
            <button class="btn"><i class="fa-solid fa-download"></i> Xuất báo cáo</button>
        </div>

        <!-- Stats Cards -->
        <div class="dashboard-cards">
            <div class="card stats-card">
                <div class="stats-icon color-blue"><i class="fa-solid fa-film"></i></div>
                <div class="stats-info">
                    <h3><?php echo number_format($totalMovies ?? 0); ?></h3>
                    <p>Tổng số phim</p>
                </div>
            </div>
            <div class="card stats-card">
                <div class="stats-icon color-green"><i class="fa-solid fa-users"></i></div>
                <div class="stats-info">
                    <h3><?php echo number_format($totalUsers ?? 0); ?></h3>
                    <p>Tổng số người dùng</p>
                </div>
            </div>
            <div class="card stats-card">
                <div class="stats-icon color-purple"><i class="fa-solid fa-eye"></i></div>
                <div class="stats-info">
                    <h3>45.2K</h3>
                    <p>Lượt xem tuần</p>
                </div>
            </div>
            <div class="card stats-card">
                <div class="stats-icon color-orange"><i class="fa-solid fa-comment-dots"></i></div>
                <div class="stats-info">
                    <h3><?php echo number_format($totalComments ?? 0); ?></h3>
                    <p>Tổng số bình luận</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid (2 Columns) -->
        <div class="dashboard-grid">
            <!-- Column 1: Support -->
            <div class="dashboard-col-main">
                <div class="card h-full">
                    <div class="card-header">
                        <h3 style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-inbox" style="color: var(--accent-blue);"></i> Hộp thư hỗ trợ mới
                        </h3>
                        <a href="<?php echo _HOST_URL; ?>/admin/support" class="view-all-link">Xem tất cả</a>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Người gửi</th>
                                    <th>Chủ đề</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($latestSupports)): ?>
                                    <?php foreach ($latestSupports as $support): ?>
                                        <?php
                                        // Xác định màu badge theo trạng thái
                                        $statusBadge = 'secondary';
                                        if ($support['support_status_id'] == 1) {
                                            $statusBadge = 'warning'; // Chờ xử lý
                                        } elseif ($support['support_status_id'] == 2) {
                                            $statusBadge = 'info'; // Đang xử lý
                                        } elseif ($support['support_status_id'] == 3) {
                                            $statusBadge = 'success'; // Đã thực hiện
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="font-weight: 500;"><?php echo htmlspecialchars($support['fullname']) ?></div>
                                                <div style="font-size: 0.8rem; color: var(--text-secondary);"><?php echo htmlspecialchars($support['email']) ?></div>
                                            </td>
                                            <td><span class="badge info"><?php echo htmlspecialchars($support['support_type_name']) ?></span></td>
                                            <td><span class="badge <?php echo $statusBadge ?>"><?php echo htmlspecialchars($support['status_name']) ?></span></td>
                                            <td class="actions">
                                                <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/support/reply?id=<?php echo $support['id'] ?>'" class="btn-icon-sm" title="Xem chi tiết">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 30px;">
                                            Chưa có yêu cầu hỗ trợ nào
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Column 2: Activity Feed -->
            <div class="dashboard-col-side">
                <div class="card h-full">
                    <div class="card-header">
                        <h3>Hoạt động gần đây</h3>
                        <a href="<?php echo _HOST_URL; ?>/admin/logs" class="view-all-link">Xem tất cả</a>
                    </div>

                    <div class="activity-feed">
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log) : ?>
                                <?php
                                // 1. Xử lý dữ liệu JSON (kiểm tra null trước khi decode)
                                $newValues = !empty($log['new_values']) ? json_decode($log['new_values'], true) : null;
                                $oldValues = !empty($log['old_values']) ? json_decode($log['old_values'], true) : null;
                                $logData = $newValues ?? $oldValues ?? [];

                                // Tìm tên hiển thị mặc định
                                $targetName = $logData['tittle'] ?? $logData['name'] ?? $logData['fullname'] ?? ('ID: ' . $log['entity_id']);

                                // Định nghĩa Entity Map (Đưa lên trước switch để có thể ghi đè trong case login)
                                $entityMap = [
                                    'movies'   => 'phim',
                                    'users'    => 'người dùng',
                                    'episodes' => 'tập phim',
                                    'seasons'  => 'mùa phim',
                                    'genres'   => 'thể loại',
                                    'actors'   => 'diễn viên',
                                    'comments' => 'bình luận'
                                ];
                                $entityName = $entityMap[$log['entity_type']] ?? $log['entity_type'];

                                // 2. Cấu hình giao diện theo hành động
                                $iconClass = 'fa-info';
                                $bgClass = 'bg-gray';
                                $actionText = 'thao tác';

                                switch ($log['action']) {
                                    case 'create':
                                        $iconClass = 'fa-plus';
                                        $bgClass = 'bg-blue';
                                        $actionText = 'đã thêm mới';
                                        break;
                                    case 'update':
                                        $iconClass = 'fa-pen-to-square';
                                        $bgClass = 'bg-green';
                                        $actionText = 'đã cập nhật';
                                        break;
                                    case 'delete':
                                        $iconClass = 'fa-trash-can';
                                        $bgClass = 'bg-red';
                                        $actionText = 'đã xóa';
                                        break;
                                    // --- XỬ LÝ RIÊNG CHO LOGIN ---
                                    case 'login':
                                        $iconClass = 'fa-right-to-bracket';
                                        $bgClass = 'bg-purple';
                                        $actionText = 'đã đăng nhập tại IP:';
                                        $entityName = '';
                                        $targetName = $log['ip_address'] ?? 'Unknown IP';
                                        break;
                                }
                                ?>

                                <div class="activity-item">
                                    <div class="activity-icon <?= $bgClass ?>">
                                        <i class="fa-solid <?= $iconClass ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p class="activity-text">
                                            <span class="fw-bold"><?= htmlspecialchars($log['fullname'] ?? 'Unknown') ?></span>
                                            <?= $actionText ?> <?= $entityName ?>
                                            <span class="text-highlight"><?= htmlspecialchars($targetName) ?></span>
                                        </p>
                                        <span class="activity-time"><?= timeAgo($log['created_at']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted" style="padding: 20px;">Chưa có hoạt động nào.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EPISODES VIEW -->
    <?php layoutPart('admin/episode'); ?>

</main>

<!-- FOOTER -->
<?php layout('admin/footer'); ?>