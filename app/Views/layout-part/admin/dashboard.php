<!-- HEADER -->
<?php layout('admin/header'); ?>

<!-- SIDEBAR -->
<?php layout('admin/sidebar'); ?>


<!-- MAIN CONTENT -->
<main class="main-content">

    <!-- DASHBOARD VIEW -->
    <section id="dashboard-view" class="content-section active">
        <div class="page-header">
            <h2>Tổng quan hệ thống</h2>
        </div>
        <div class="dashboard-cards">
            <div class="card stats-card">
                <div class="stats-icon color-blue"><i class="fa-solid fa-film"></i></div>
                <div class="stats-info">
                    <h3>1,240</h3>
                    <p>Tổng số Phim</p>
                </div>
            </div>
            <div class="card stats-card">
                <div class="stats-icon color-green"><i class="fa-solid fa-users"></i></div>
                <div class="stats-info">
                    <h3>8,500</h3>
                    <p>Người dùng</p>
                </div>
            </div>
            <div class="card stats-card">
                <div class="stats-icon color-purple"><i class="fa-solid fa-comments"></i></div>
                <div class="stats-info">
                    <h3>450</h3>
                    <p>Bình luận mới</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MOVIES VIEW -->
    <?php layoutPart('admin/movies/list'); ?>

    <!-- EPISODES VIEW -->
    <?php layoutPart('admin/episode'); ?>

</main>

<!-- FOOTER -->
<?php layout('admin/footer'); ?>