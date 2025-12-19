<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
$current_status = $getAllComment['status'] ?? 1;
// echo '<pre>';
// (print_r($getAllComment));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>

<section id="comments-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Quản lý Bình luận</h2>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="GET">
        <div class="toolbar">
            <div class="filters-group">

                <select name="movie_id" id="movie_id" onchange="this.form.submit()">
                    <option value="">Tất cả phim</option>
                    <?php foreach ($getAllMovies as $movie): ?>
                        <option value="<?= $movie['id'] ?>"
                            <?php echo (isset($movie_id) && $movie_id == $movie['id']) ? 'selected' : '' ?>>
                            <?= $movie['tittle'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="user_id" id="user_id" onchange="this.form.submit()">
                    <option value="">Tất cả người dùng</option>
                    <?php foreach ($getAllUsers as $user): ?>
                        <option value="<?= $user['id'] ?>"
                            <?php echo (isset($user_id) && $user_id == $user['id']) ? 'selected' : '' ?>>
                            <?= $user['fullname'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="" <?php echo ($status === '') ? 'selected' : ''; ?>>
                        -- Tất cả trạng thái --
                    </option>

                    <option value="1" <?php echo (isset($status) && (string)$status === '1') ? 'selected' : ''; ?>>
                        Hiển thị
                    </option>

                    <option value="0" <?php echo (isset($status) && (string)$status === '0') ? 'selected' : ''; ?>>
                        Đang ẩn
                    </option>
                </select>
            </div>

            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input name="keyword" type="text" placeholder="Tìm nội dung bình luận..."
                    value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
            </div>
        </div>
    </form>

    <div class="card table-container">

        <table>
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Nội dung</th>
                    <th>Phim / Tập</th>
                    <th>Ngày đăng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($getAllComment as $comment): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;">
                                <?= $comment['fullname'] ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #94a3b8;">
                                <?= $comment['email'] ?>
                            </div>
                        </td>
                        <td><?= $comment['content'] ?></td>
                        <td><?= $comment['tittle'] ?></td>
                        <td><?= $comment['created_at'] ?></td>
                        <td class="actions">
                            <div class="action-buttons">
                                <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/comments/delete?id=<?php echo $comment['id'] ?>'" class="btn-icon-sm delete-btn"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <span>Hiển thị 1-5 trên <?php echo $maxData ?> kết quả</span>
            <div class="page-controls">
                <?php if ($page > 1): ?>
                    <button
                        onclick="window.location.href='comments?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
                <?php elseif ($page == 1): ?>
                    <button disabled
                        onclick="window.location.href='comments?<?php echo $queryString ?>&page=<?php echo $page - 1 ?>'">Trước</button>
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
                    <button onclick="window.location.href='comments?<?php echo $queryString ?>&page=<?php echo $i; ?>'"
                        class=" <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i ?>
                    </button>
                <?php endfor; ?>
                <?php if ($page < $maxPage): ?>
                    <button
                        onclick="window.location.href='comments?<?php echo $queryString ?>&page=<?php echo $page + 1 ?>'">Sau</button>
                <?php elseif ($page == $maxPage): ?>
                    <button disabled>Sau</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>