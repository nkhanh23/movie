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
                        <div class="activity-item">
                            <div class="activity-icon bg-blue">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text"><span class="fw-bold">Admin</span> đã thêm phim mới <span class="text-highlight">The Marvels</span></p>
                                <span class="activity-time">5 phút trước</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-green">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text"><span class="fw-bold">Editor_Huy</span> cập nhật tập 5 <span class="text-highlight">One Piece Live Action</span></p>
                                <span class="activity-time">30 phút trước</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-red">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text"><span class="fw-bold">System</span> đã xóa bình luận spam của user <span class="fw-bold">bot_123</span></p>
                                <span class="activity-time">2 giờ trước</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-purple">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text"><span class="fw-bold">User_New</span> vừa đăng ký tài khoản VIP</p>
                                <span class="activity-time">5 giờ trước</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-blue">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text"><span class="fw-bold">Admin</span> đã thêm thể loại <span class="text-highlight">Phim Tài Liệu</span></p>
                                <span class="activity-time">1 ngày trước</span>
                            </div>
                        </div>
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