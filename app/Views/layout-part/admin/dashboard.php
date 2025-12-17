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
                    <h3>1,245</h3>
                    <p>Tổng số phim</p>
                </div>
            </div>
            <div class="card stats-card">
                <div class="stats-icon color-green"><i class="fa-solid fa-users"></i></div>
                <div class="stats-info">
                    <h3>8,520</h3>
                    <p>Người dùng mới</p>
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
                    <h3>342</h3>
                    <p>Bình luận mới</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid (2 Columns) -->
        <div class="dashboard-grid">
            <!-- Column 1: Recent Movies -->
            <div class="dashboard-col-main">
                <div class="card h-full">
                    <div class="card-header">
                        <h3>Phim mới cập nhật</h3>
                        <a href="#" class="view-all-link">Xem tất cả</a>
                    </div>
                    <div class="table-container" style="margin-top: 15px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tên phim</th>
                                    <th>Thể loại</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;">The Matrix Resurrections</div>
                                    </td>
                                    <td>Hành động</td>
                                    <td><span class="badge success">Hoàn thành</span></td>
                                    <td>20/10/2023</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;">Inception</div>
                                    </td>
                                    <td>Sci-Fi</td>
                                    <td><span class="badge success">Hoàn thành</span></td>
                                    <td>18/10/2023</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;">Stranger Things S4</div>
                                    </td>
                                    <td>Kinh dị</td>
                                    <td><span class="badge warning">Đang cập nhật</span></td>
                                    <td>15/10/2023</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;">Loki Season 2</div>
                                    </td>
                                    <td>Fantasy</td>
                                    <td><span class="badge warning">Đang cập nhật</span></td>
                                    <td>12/10/2023</td>
                                </tr>
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
                        <button class="btn-icon-sm"><i class="fa-solid fa-ellipsis"></i></button>
                    </div>

                    <div class="activity-feed">
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log) : ?>
                                <?php
                                // 1. Xử lý dữ liệu JSON
                                $logData = json_decode($log['new_values'], true) ?? json_decode($log['old_values'], true);

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