<?php

class SettingController extends baseController
{
    private $activityModel;
    private $settingModel;
    public function __construct()
    {
        $this->activityModel = new Activity;
        $this->settingModel = new Setting;
    }

    public function showGeneral()
    {
        $rawSettings = $this->settingModel->getAllSettings();

        // BIẾN ĐỔI dữ liệu: Chuyển thành dạng ['key' => 'value']
        $settings = [];
        if (!empty($rawSettings)) {
            foreach ($rawSettings as $item) {
                // Lấy cột 'setting_key' làm key cho mảng mới
                // Lấy cột 'setting_value' làm giá trị
                $settings[$item['setting_key']] = $item['setting_value'];
            }
        }

        // Lấy tab hiện tại từ URL (mặc định là 'general')
        $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'general';

        // Validate tab (chỉ cho phép các tab hợp lệ)
        $validTabs = ['general', 'email'];
        if (!in_array($currentTab, $validTabs)) {
            $currentTab = 'general';
        }



        $data = [
            'settings' => $settings,
            'currentTab' => $currentTab
        ];
        $this->renderView('/layout-part/admin/setting/general', $data);
    }

    public function updateGeneral()
    {
        if (isPost()) {
            $filter = filterData();
            $generalKeys = [
                'site_name',
                'site_slogan',
                'site_email',
                'site_facebook',
                'site_instagram',
                'site_description',
                'site_keywords',
                'maintenance_message',
                'maintenance_start',
                'maintenance_end'
            ];

            foreach ($generalKeys as $key) {
                if (isset($filter[$key])) {

                    $this->settingModel->saveSetting($key, $filter[$key]);
                }
            }

            // Xử lý checkbox maintenance_mode
            $maintenance = isset($_POST['maintenance_mode']) ? '1' : '0';
            $this->settingModel->saveSetting('maintenance_mode', $maintenance);

            // Xử lý File Upload (Tách riêng từng file)
            $this->handleUpload('site_logo');
            $this->handleUpload('site_favicon');

            setSessionFlash('msg', 'Cập nhật cài đặt chung thành công!');
            setSessionFlash('msg_type', 'success');

            reload('/admin/settings?tab=general');
        }
    }
    private function handleUpload($inputName)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {

            $uploadDir = 'public/img/logo/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Validate đuôi file
            $fileName = basename($_FILES[$inputName]['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $allowTypes = ['jpg', 'png', 'jpeg', 'gif', 'ico', 'svg'];

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                    $this->settingModel->saveSetting($inputName, $targetFile);
                }
            }
        }
    }

    public function updateEmail()
    {
        if (isPost()) {
            $filter = filterData();

            // Chỉ lưu các settings liên quan đến Email tab
            $emailKeys = [
                'smtp_host',
                'smtp_port',
                'smtp_encryption',
                'smtp_username',
                'smtp_from_name'
            ];

            foreach ($emailKeys as $key) {
                if (isset($filter[$key])) {
                    $this->settingModel->saveSetting($key, $filter[$key]);
                }
            }

            // Logic: Nếu ô password KHÔNG rỗng => Người dùng muốn đổi pass => Lưu cái mới
            //        Nếu ô password rỗng => Người dùng không muốn đổi => Không làm gì cả (Giữ cái cũ)
            if (!empty($filter['smtp_password'])) {
                $this->settingModel->saveSetting('smtp_password', trim($filter['smtp_password']));
            }

            //Kiem tra hanh dong
            $action = $_POST['action'] ?? 'save';
            if ($action == 'test') {
                $testEmailTo = $filter['smtp_username'];
                $subject = '[PHEPHIM] Kiểm tra cấu hình Email';
                $content = 'Đây là email kiểm tra cấu hình Email. Nếu bạn nhận được email này, nghĩa là cấu hình Email của bạn đã được cấu hình đúng.';
                $replyToEmail = $filter['smtp_username'];
                $replyToName = $filter['smtp_from_name'];

                $result = sendMail($testEmailTo, $subject, $content, $replyToEmail, $replyToName);
                if ($result) {
                    setSessionFlash('msg', 'Gửi email thử nghiệm thành công!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Gửi email thử nghiệm thất bại!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Cập nhật cài đặt email thành công!');
                setSessionFlash('msg_type', 'success');
            }

            reload('/admin/settings?tab=email');
        }
    }
}
