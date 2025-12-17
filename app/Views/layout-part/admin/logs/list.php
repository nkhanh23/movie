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
<!-- ACTIVITY LOG FULL VIEW (NEW) -->
<section id="activities-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Nhật ký hệ thống (Activity Log)</h2>
        <button class="btn"><i class="fa-solid fa-filter"></i> Bộ lọc</button>
    </div>

    <div class="card table-container">
        <div class="toolbar" style="padding: 16px 24px;">
            <div class="filters-group">
                <select>
                    <option>Tất cả hành động</option>
                    <option>Thêm mới (Create)</option>
                    <option>Cập nhật (Update)</option>
                    <option>Xóa (Delete)</option>
                </select>
                <select>
                    <option>Người thực hiện</option>
                    <option>Admin</option>
                    <option>Editor</option>
                    <option>System</option>
                </select>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" placeholder="Tìm kiếm logs...">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Người dùng</th>
                    <th>Hành động</th>
                    <th>Đối tượng</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-secondary">20/10/2023 10:30</td>
                    <td><span class="badge-user">Admin</span></td>
                    <td><span class="badge success">Create</span></td>
                    <td>Phim</td>
                    <td>Thêm phim mới "The Marvels" (ID: 1024)</td>
                </tr>
                <tr>
                    <td class="text-secondary">20/10/2023 09:15</td>
                    <td><span class="badge-user">Editor_Huy</span></td>
                    <td><span class="badge warning">Update</span></td>
                    <td>Tập phim</td>
                    <td>Cập nhật link server VIP tập 5</td>
                </tr>
                <tr>
                    <td class="text-secondary">20/10/2023 08:00</td>
                    <td><span class="badge-user">System</span></td>
                    <td><span class="badge danger">Delete</span></td>
                    <td>Comment</td>
                    <td>Xóa comment vi phạm chính sách ID #9921</td>
                </tr>
                <tr>
                    <td class="text-secondary">19/10/2023 23:45</td>
                    <td><span class="badge-user">Admin</span></td>
                    <td><span class="badge success">Login</span></td>
                    <td>Auth</td>
                    <td>Đăng nhập thành công IP 192.168.1.1</td>
                </tr>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-10 trên 150 dòng</span>
            <div class="page-controls">
                <button disabled><i class="fa-solid fa-chevron-left"></i></button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>

<?php
layout('admin/footer');
