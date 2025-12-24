<?php
if (!defined('_nkhanhh')) {
    die('Truy cập không hợp lệ');
}
layout('admin/header');
layout('admin/sidebar');
// echo '<pre>';
// (print_r($settings));
// echo '</pre>';
// die();
$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>

<!-- SETTINGS VIEW -->
<section id="settings-view" class="content-section active" style="padding: 30px;">
    <div class="page-header">
        <h2>Cài đặt hệ thống</h2>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" style="padding: 12px 20px; margin-bottom: 24px; border-radius: 8px; background: <?php echo $msg_type === 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>; border: 1px solid <?php echo $msg_type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'; ?>; color: <?php echo $msg_type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'; ?>;">
            <i class="fa-solid fa-<?php echo $msg_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="settings-container">
        <div class="settings-tabs" style="display: flex; gap: 10px; margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <button class="tab-btn <?php echo ($currentTab === 'general') ? 'active' : ''; ?>" data-tab="general">Chung</button>
            <button class="tab-btn <?php echo ($currentTab === 'email') ? 'active' : ''; ?>" data-tab="email">Cấu hình Email</button>
        </div>

        <!-- TAB: GENERAL -->
        <div class="tab-content <?php echo ($currentTab === 'general') ? 'active' : ''; ?>" id="general-settings">
            <form method="POST" action="<?php echo _HOST_URL; ?>/admin/settings/general" enctype="multipart/form-data">
                <div class="grid-2-col" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div class="card">
                        <div class="card-header" style="margin-bottom: 20px;">
                            <h3 style="font-size: 1.1rem;"><i class="fa-solid fa-globe"></i> Thông tin Website</h3>
                        </div>
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group">
                                <label>Tên Website</label>
                                <input name="site_name" type="text" value="<?php echo $settings['site_name']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Slogan / Tagline</label>
                                <input name="site_description" type="text" value="<?php echo $settings['site_description']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ Email liên hệ</label>
                                <input name="site_email" type="email" value="<?php echo $settings['site_email']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Facebook</label>
                                <input name="site_facebook" type="text" value="<?php echo $settings['site_facebook']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Instagram</label>
                                <input name="site_instagram" type="text" value="<?php echo $settings['site_instagram']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="margin-bottom: 20px;">
                            <h3 style="font-size: 1.1rem;"><i class="fa-solid fa-sliders"></i> Trạng thái hệ thống</h3>
                        </div>
                        <div class="form-grid" style="grid-template-columns: 1fr;">
                            <div class="form-group" style="flex-direction: row; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div>
                                    <label style="margin: 0; display: block;">Chế độ bảo trì</label>
                                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Ngắt kết nối người dùng để bảo trì</span>
                                </div>
                                <label class="switch">
                                    <input name="maintenance_mode" type="checkbox" <?php echo $settings['maintenance_mode'] == '1' ? 'checked' : ''; ?>><span class="slider round"></span></label>
                            </div>
                            <div class="form-group">
                                <label>Thông báo bảo trì</label>
                                <input name="maintenance_message" type="text" value="<?php echo $settings['maintenance_message']; ?>">
                            </div>
                            <div class="form-group" style="border-top: 1px solid var(--border-color); padding-top: 15px;">
                                <label>Thời gian bắt đầu bảo trì</label>
                                <input name="maintenance_start" type="datetime-local" value="<?php echo $settings['maintenance_start']; ?>">
                            </div>
                            <div class="form-group" style="padding-top: 5px;">
                                <label>Thời gian dự kiến kết thúc</label>
                                <input name="maintenance_end" type="datetime-local" value="<?php echo $settings['maintenance_end']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Brand Identity Section -->
                <div class="card">
                    <div class="card-header" style="margin-bottom: 20px;">
                        <h3 style="font-size: 1.1rem;"><i class="fa-solid fa-image"></i> Nhận diện thương hiệu (Logo & Favicon)</h3>
                    </div>
                    <div style="display: flex; gap: 40px; align-items: flex-start; flex-wrap: wrap;">

                        <div style="text-align: center;">
                            <p style="margin-bottom: 10px; color: var(--text-secondary); font-size: 0.85rem;">Logo chính</p>

                            <div id="logo-preview-box" style="width: 150px; height: 150px; background: var(--bg-dark); border: 2px dashed var(--border-color); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; overflow: hidden;">
                                <?php if (!empty($settings['site_logo'])): ?>
                                    <img src="<?php echo _HOST_URL . '/' . $settings['site_logo']; ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                                <?php else: ?>
                                    <i class="fa-solid fa-film" style="font-size: 3rem; color: var(--accent-blue);"></i>
                                <?php endif; ?>
                            </div>

                            <input type="file" class="btn-icon-sm" style="width: auto; padding: 0 15px;"
                                name="site_logo" accept="image/*"
                                onchange="previewImage(this, 'logo-preview-box')">
                        </div>

                        <div style="text-align: center;">
                            <p style="margin-bottom: 10px; color: var(--text-secondary); font-size: 0.85rem;">Favicon</p>

                            <div id="favicon-preview-box" style="width: 64px; height: 64px; background: var(--bg-dark); border: 2px dashed var(--border-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; overflow: hidden;">
                                <?php if (!empty($settings['site_favicon'])): ?>
                                    <img src="<?php echo _HOST_URL . '/' . $settings['site_favicon']; ?>" alt="Favicon" style="width: 100%; height: 100%; object-fit: contain;">
                                <?php else: ?>
                                    <i class="fa-solid fa-clapperboard" style="font-size: 1.5rem; color: var(--accent-blue);"></i>
                                <?php endif; ?>
                            </div>

                            <input type="file" class="btn-icon-sm" style="width: auto; padding: 0 15px;"
                                name="site_favicon" accept="image/*"
                                onchange="previewImage(this, 'favicon-preview-box')">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="form-actions" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu cài đặt chung</button>
                </div>
            </form>
        </div>

        <!-- TAB: EMAIL CONFIGURATION -->
        <div class="tab-content <?php echo ($currentTab === 'email') ? 'active' : ''; ?>" id="email-settings">
            <form method="POST" action="<?php echo _HOST_URL; ?>/admin/settings/email">
                <div class="card" style="max-width: 800px;">
                    <div class="card-header" style="margin-bottom: 20px;">
                        <h3 style="font-size: 1.1rem;"><i class="fa-solid fa-envelope-open-text"></i> Cấu hình SMTP Email</h3>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>SMTP Host</label>
                            <input name="smtp_host" type="text" value="<?php echo $settings['smtp_host'] ?? ''; ?>" placeholder="VD: smtp.gmail.com">
                        </div>
                        <div class="form-group">
                            <label>SMTP Port</label>
                            <input name="smtp_port" type="text" value="<?php echo $settings['smtp_port'] ?? ''; ?>" placeholder="VD: 587">
                        </div>
                        <div class="form-group">
                            <label>Tên người gửi (From Name)</label>
                            <input name="smtp_from_name" type="text" value="<?php echo $settings['smtp_from_name'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Tài khoản (Username)</label>
                            <input name="smtp_username" type="text" value="<?php echo $settings['smtp_username'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu (Password)</label>
                            <div class="password-wrapper" style="position: relative;">
                                <input
                                    id="smtp_password_input"
                                    name="smtp_password"
                                    type="password"
                                    value="<?php echo $settings['smtp_password'] ?? ''; ?>"
                                    placeholder="Nhập mật khẩu SMTP"
                                    style="padding-right: 40px;">

                                <span
                                    onclick="togglePasswordVisibility()"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-secondary); z-index: 10;">
                                    <i class="fa-solid fa-eye" id="eye_icon"></i>
                                </span>
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 5px; display: block;">
                                <i class="fa-solid fa-shield-halved"></i> Mật khẩu được mã hóa hiển thị
                            </small>
                        </div>
                    </div>

                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="submit" name="action" value="test" class="btn" style="background: var(--bg-hover);"><i class="fa-solid fa-paper-plane"></i> Gửi Email thử nghiệm</button>
                        <button type="submit" name="action" value="save" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu cài đặt Email</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
<?php
layout('admin/footer');
?>
<script>
    // Hàm xem trước ảnh
    function previewImage(input, previewBoxId) {
        const previewBox = document.getElementById(previewBoxId);
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Xóa nội dung cũ (icon hoặc ảnh cũ)
                previewBox.innerHTML = '';

                // Tạo thẻ img mới
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'contain'; // Giữ tỷ lệ ảnh đẹp

                // Chèn vào khung
                previewBox.appendChild(img);
            }

            // Đọc file dưới dạng URL data
            reader.readAsDataURL(file);
        }
    }

    // Logic chuyển Tab (Giữ lại code cũ của bạn nếu cần)
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class
            btn.classList.add('active');
            const tabId = btn.getAttribute('data-tab');
            document.getElementById(tabId + '-settings').classList.add('active');
        });
    });
</script>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('smtp_password_input');
        const eyeIcon = document.getElementById('eye_icon');

        // Kiểm tra trạng thái hiện tại
        if (passwordInput.type === 'password') {
            // Đang ẩn -> Chuyển thành hiện (text)
            passwordInput.type = 'text';

            // Đổi icon thành mắt gạch chéo
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            // Đang hiện -> Chuyển thành ẩn (password)
            passwordInput.type = 'password';

            // Đổi icon thành mắt mở
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>