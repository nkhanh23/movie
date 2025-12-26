<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// die();
?>
<section id="reply-support-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Phản hồi yêu cầu hỗ trợ</h2>
        <button class="btn cancel-to-support-list"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</button>
    </div>
    <div class="card">
        <div class="reply-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="request-details">
                <h4 style="margin-bottom: 20px; color: var(--accent-blue); display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-info"></i> Thông tin yêu cầu
                </h4>
                <div style="background: var(--bg-dark); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Người gửi:</label>
                        <p style="font-weight: 600; font-size: 1rem; margin-top: 4px;" id="reply-fullname"><?php echo $oldData['user_name']; ?></p>
                        <p style="color: var(--text-secondary); font-size: 0.85rem;" id="reply-email"><?php echo $oldData['user_email']; ?></p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Loại hỗ trợ:</label>
                        <p style="margin-top: 4px;"><span class="badge info" id="reply-type"><?php echo $oldData['support_type_name']; ?></span></p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Thời gian gửi:</label>
                        <p style="margin-top: 4px; font-size: 0.9rem;" id="reply-date"><?php echo $oldData['created_at']; ?></p>
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Nội dung yêu cầu:</label>
                        <div style="margin-top: 8px; padding: 15px; background: var(--bg-card); border-radius: 8px; border-left: 4px solid var(--accent-blue); line-height: 1.6; font-size: 0.95rem;" id="reply-content">
                            <?php echo $oldData['content']; ?>
                        </div>
                    </div>
                    <?php if (!empty($oldData['image'])): ?>
                        <div style="margin-top: 15px;">
                            <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Ảnh đính kèm:</label>
                            <div style="margin-top: 8px; padding: 15px; background: var(--bg-card); border-radius: 8px; border-left: 4px solid #8b5cf6;">
                                <a href="<?php echo $oldData['image']; ?>" target="_blank" style="display: inline-block;">
                                    <img src="<?php echo $oldData['image']; ?>" alt="Ảnh đính kèm" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid var(--border-color); cursor: pointer; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'" />
                                </a>
                                <p style="margin-top: 10px; font-size: 0.8rem; color: var(--text-secondary);">
                                    <a href="<?php echo $oldData['image']; ?>" target="_blank" style="color: #8b5cf6; text-decoration: none;">
                                        <i class="fa-solid fa-external-link"></i> Xem ảnh gốc
                                    </a>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="reply-form">
                <h4 style="margin-bottom: 20px; color: var(--success-color); display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-paper-plane"></i> Gửi phản hồi
                </h4>
                <form class="form-grid" method="POST" style="grid-template-columns: 1fr;">
                    <input type="hidden" name="id" value="<?php echo $oldData['id']; ?>">
                    <div class="form-group">
                        <label>Trạng thái mới <span class="required">*</span></label>
                        <select name="new_status" id="new_status">
                            <?php foreach ($getAllStatus as $item): ?>
                                <option value="<?php echo $item['id']; ?>"><?php echo $item['status']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nội dung phản hồi <span class="required">*</span></label>
                        <textarea name="reply_content_text" id="reply_content_text" rows="8" placeholder="Nhập nội dung phản hồi gửi đến email người dùng..."></textarea>
                    </div>
                    <div class="form-actions" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-envelope"></i> Gửi phản hồi qua Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>