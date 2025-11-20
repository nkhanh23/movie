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
    <section id="episodes-view" class="content-section">
        <div class="page-header">
            <h2>Quản lý Tập Phim</h2>
            <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tập Mới</button>
        </div>
        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" placeholder="Tìm tên tập hoặc tên phim...">
            </div>
        </div>
        <div class="card table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Tập</th>
                        <th>Thuộc Phim</th>
                        <th>Mùa / Tập</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>101</td>
                        <td>Winter Is Coming</td>
                        <td>Game of Thrones</td>
                        <td>S01 E01</td>
                        <td><span class="badge success">Published</span></td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="101"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>102</td>
                        <td>The Kingsroad</td>
                        <td>Game of Thrones</td>
                        <td>S01 E02</td>
                        <td><span class="badge success">Published</span></td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="102"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- GENRES VIEW -->
    <section id="genres-view" class="content-section">
        <div class="page-header">
            <h2>Quản lý Thể loại</h2>
            <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Thể loại</button>
        </div>
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
                    <tr>
                        <td>1</td>
                        <td>Hành động (Action)</td>
                        <td>action</td>
                        <td>1,200</td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="g1"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Khoa học viễn tưởng</td>
                        <td>sci-fi</td>
                        <td>850</td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="g2"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- COMMENTS VIEW -->
    <section id="comments-view" class="content-section">
        <div class="page-header">
            <h2>Quản lý Bình luận</h2>
        </div>
        <div class="card table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Nội dung</th>
                        <th>Phim</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>55</td>
                        <td>nguyen_van_a</td>
                        <td>Phim hay quá, mong chờ phần 2!</td>
                        <td>Inception</td>
                        <td><span class="badge success">Approved</span></td>
                        <td class="actions">
                            <button class="btn-icon-sm delete-btn" data-id="c55"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>56</td>
                        <td>hacker_123</td>
                        <td>Click link này để nhận quà...</td>
                        <td>Interstellar</td>
                        <td><span class="badge danger">Spam</span></td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-check"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="c56"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- USERS VIEW -->
    <section id="users-view" class="content-section">
        <div class="page-header">
            <h2>Quản lý Người dùng</h2>
            <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm User</button>
        </div>
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
                    <tr>
                        <td>1</td>
                        <td>admin_boss</td>
                        <td>admin@system.com</td>
                        <td>Admin</td>
                        <td><span class="badge success">Active</span></td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>user_vip</td>
                        <td>vip@gmail.com</td>
                        <td>Subscriber</td>
                        <td><span class="badge success">Active</span></td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="u2"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- PERSONS VIEW -->
    <section id="persons-view" class="content-section">
        <div class="page-header">
            <h2>Quản lý Diễn viên / Đạo diễn</h2>
            <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Người</button>
        </div>
        <div class="card table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Vai trò chính</th>
                        <th>Số phim</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Christopher Nolan</td>
                        <td>Director</td>
                        <td>12</td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="p1"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Leonardo DiCaprio</td>
                        <td>Actor</td>
                        <td>35</td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="p2"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ROLES VIEW -->
    <section id="roles-view" class="content-section">
        <div class="page-header">
            <h2>Quản lý Vai trò (Roles)</h2>
            <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Vai trò</button>
        </div>
        <div class="card table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Vai trò</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Actor</td>
                        <td>Diễn viên tham gia diễn xuất</td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="r1"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Director</td>
                        <td>Đạo diễn chỉ đạo sản xuất</td>
                        <td class="actions">
                            <button class="btn-icon-sm"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-icon-sm delete-btn" data-id="r2"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</main>

<!-- FOOTER -->
<?php layout('admin/footer'); ?>