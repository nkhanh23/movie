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
<!-- SUPPORT TYPES VIEW -->
<section id="support-types-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Loại hỗ trợ (Support Types)</h2>
        <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/support_type/add'" id="btn-add-support-type" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Chủ đề</button>
    </div>
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <div class="card table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Tên chủ đề</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                if (!empty($getAllSupportType)): ?>
                    <?php foreach ($getAllSupportType as $item): ?>
                        <tr>
                            <td><?php echo $count++ ?></td>
                            <td><?php echo $item['name'] ?></td>
                            <td class="actions">
                                <div class="action-buttons">
                                    <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/support_type/edit?id=<?php echo $item['id'] ?>'" class="btn-icon-sm" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button onclick="window.location.href='<?php echo _HOST_URL; ?>/admin/support_type/delete?id=<?php echo $item['id'] ?>'" class="btn-icon-sm delete-btn" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--text-secondary); padding: 30px;">
                            Chưa có loại hỗ trợ nào
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php layout('admin/footer'); ?>