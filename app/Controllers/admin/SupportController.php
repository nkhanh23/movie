<?php

class SupportController extends baseController
{
    private $supportModel;
    public function __construct()
    {
        $this->supportModel = new Support();
    }
    public function list()
    {
        $filter = filterData();
        $status = '';
        $type = '';
        $keyword = '';
        $chuoiWhere = '';
        if (isset($filter['keyword'])) {
            $keyword = $filter['keyword'];
        }
        if (isset($filter['type'])) {
            $type = $filter['type'];
        }
        if (isset($filter['status'])) {
            $status = $filter['status'];
        }

        if (!empty($keyword)) {
            if (strpos($chuoiWhere, 'WHERE') == false) {
                $chuoiWhere .= ' WHERE ';
            } else {
                $chuoiWhere .= ' AND ';
            }
            $chuoiWhere .= "u.fullname LIKE '%$keyword%' OR u.email LIKE '%$keyword%'";
        }

        if (!empty($status)) {
            if (strpos($chuoiWhere, 'WHERE') == false) {
                $chuoiWhere .= ' WHERE ';
            } else {
                $chuoiWhere .= ' AND ';
            }
            $chuoiWhere .= "ss.id = $status";
        }

        if (!empty($type)) {
            if (strpos($chuoiWhere, 'WHERE') == false) {
                $chuoiWhere .= ' WHERE ';
            } else {
                $chuoiWhere .= ' AND ';
            }
            $chuoiWhere .= "st.id = $type";
        }
        $countAllSupport = $this->supportModel->countAllSupport($chuoiWhere);
        $maxData = $countAllSupport[0]['total'];
        $perPage = 5;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        // Nếu không có dữ liệu (maxPage = 0), gán mặc định là 1 để tránh lỗi chia/trừ số âm
        if ($maxPage < 1) {
            $maxPage = 1;
        }

        if ($page < 1) {
            $page = 1;
        }
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }
        $getAllSupportType = $this->supportModel->getAllSupportType();
        $getAllStatus = $this->supportModel->getAllStatus();
        $getAllSupport = $this->supportModel->getAllSupport($chuoiWhere, $perPage, $offset);
        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'getAllSupportType' => $getAllSupportType,
            'getAllStatus' => $getAllStatus,
            'getAllSupport' => $getAllSupport,
            'countAllSupport' => $maxData,
            'page' => $page,
            'maxPage' => $maxPage,
            'queryString' => $queryString,
            'status' => $status,
            'type' => $type,
            'keyword' => $keyword
        ];
        $this->renderView('/layout-part/admin/support/list', $data);
    }

    public function showReply()
    {
        $filter = filterData('get');
        $id = $filter['id'];
        $getSupport = $this->supportModel->getSupportById($id);
        $getAllStatus = $this->supportModel->getAllStatus();
        $data = [
            'oldData' => $getSupport,
            'getAllStatus' => $getAllStatus
        ];
        $this->renderView('/layout-part/admin/support/reply', $data);
    }

    public function reply()
    {
        $filter = filterData('post');
        $id = $filter['id'];
        $getSupport = $this->supportModel->getSupportById($id);
        $user_email = $getSupport['user_email'];
        $user_name = $getSupport['user_name'];
        $new_status = $filter['new_status'];
        $reply_content_text = $filter['reply_content_text'];
        $errors = [];
        if (empty(trim($filter['reply_content_text']))) {
            $errors['reply_content_text']['required'] = ' Nội dung phản hồi bắt buộc phải nhập';
        }

        if (empty($errors)) {
            // Lấy tên loại hỗ trợ và trạng thái
            $supportTypeName = '';
            $allSupportTypes = $this->supportModel->getAllSupportType();
            foreach ($allSupportTypes as $type) {
                if ($type['id'] == $getSupport['support_type_id']) {
                    $supportTypeName = $type['name'];
                    break;
                }
            }
            $statusName = '';
            $statusColor = '#64748b';
            $allStatuses = $this->supportModel->getAllStatus();
            foreach ($allStatuses as $status) {
                if ($status['id'] == $new_status) {
                    $statusName = $status['status'];
                    // Set màu theo trạng thái
                    if ($status['id'] == 1) $statusColor = '#f59e0b'; // Pending - vàng
                    if ($status['id'] == 2) $statusColor = '#3b82f6'; // Processing - xanh dương
                    if ($status['id'] == 3) $statusColor = '#10b981'; // Resolved - xanh lá
                    break;
                }
            }

            $emailTo = $getSupport['email'];
            $subject = '[Phê Phim] Phản hồi: ' . $supportTypeName;
            $content = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);">
    <div style="max-width: 680px; margin: 40px auto; background: linear-gradient(135deg, rgba(18, 24, 33, 0.95) 0%, rgba(10, 14, 20, 0.98) 100%); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 1px rgba(255, 255, 255, 0.1) inset;">
        
        <!-- Header with Logo -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 20px; text-align: center; position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);"></div>
            <img src="' . _HOST_URL_PUBLIC . '/img/logo/PhePhim.png" alt="Phê Phim" style="height: 60px; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));">
            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">✅ Phản Hồi Yêu Cầu Hỗ Trợ</h1>
            <p style="margin: 8px 0 0 0; color: rgba(255, 255, 255, 0.95); font-size: 14px;">Chúng tôi đã xử lý yêu cầu của bạn!</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 35px 30px; color: #e2e8f0; line-height: 1.8;">
            
            <!-- Greeting -->
            <p style="font-size: 15px; color: #e2e8f0; margin-bottom: 20px;">
                Xin chào <strong style="color: #fff;">' . htmlspecialchars($user_name) . '</strong>,
            </p>
            <p style="font-size: 14px; color: #cbd5e1; margin-bottom: 25px;">
                Cảm ơn bạn đã liên hệ với <strong style="color: #F29F05;">Phê Phim</strong>. Chúng tôi đã xem xét yêu cầu hỗ trợ của bạn và gửi phản hồi như sau:
            </p>

            <!-- Status Badge -->
            <div style="text-align: center; margin: 25px 0;">
                <div style="display: inline-block; background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 15px 25px; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <p style="margin: 0 0 8px 0; font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Trạng thái mới</p>
                    <span style="display: inline-block; background: ' . $statusColor . '20; border: 1px solid ' . $statusColor . '50; color: ' . $statusColor . '; padding: 6px 16px; border-radius: 8px; font-size: 14px; font-weight: 700; text-transform: uppercase;">' . htmlspecialchars($statusName) . '</span>
                </div>
            </div>
            
            <!-- Original Request -->
            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <span style="font-size: 18px;">📝</span>
                    <h3 style="margin: 0; color: #94a3b8; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Yêu cầu ban đầu của bạn</h3>
                </div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; font-size: 13px; width: 100px;">📁 Loại:</td>
                        <td style="padding: 6px 0;">
                            <span style="background: rgba(217, 108, 22, 0.15); border: 1px solid rgba(217, 108, 22, 0.3); color: #F29F05; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">' . htmlspecialchars($supportTypeName) . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; font-size: 13px;">🕐 Thời gian:</td>
                        <td style="padding: 6px 0; color: #cbd5e1; font-size: 13px;">' . htmlspecialchars($getSupport['created_at']) . '</td>
                    </tr>
                </table>
                <div style="background: rgba(15, 23, 42, 0.5); border-radius: 8px; padding: 12px; margin-top: 12px;">
                    <p style="margin: 0; color: #cbd5e1; font-size: 13px; line-height: 1.6; white-space: pre-wrap;">' . htmlspecialchars($getSupport['content']) . '</p>
                </div>
            </div>

            <!-- Admin Reply -->
            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.05) 100%); border-left: 4px solid #10b981; border-radius: 12px; padding: 22px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                        <span style="font-size: 18px;">💬</span>
                    </div>
                    <h3 style="margin: 0; color: #6ee7b7; font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Phản hồi từ đội ngũ hỗ trợ</h3>
                </div>
                <div style="background: rgba(15, 23, 42, 0.6); border-radius: 10px; padding: 18px; border: 1px solid rgba(16, 185, 129, 0.2);">
                    <p style="margin: 0; color: #e2e8f0; font-size: 14px; line-height: 1.8; white-space: pre-wrap;">' . htmlspecialchars($reply_content_text) . '</p>
                </div>
            </div>
            
            <!-- Need More Help -->
            <div style="background: rgba(59, 130, 246, 0.05); border-left: 3px solid #3b82f6; border-radius: 8px; padding: 18px; margin-bottom: 25px;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <span style="font-size: 20px; line-height: 1;">💡</span>
                    <div>
                        <p style="margin: 0 0 8px 0; color: #93c5fd; font-size: 14px; font-weight: 600;">Cần thêm sự hỗ trợ?</p>
                        <p style="margin: 0; color: #cbd5e1; font-size: 13px; line-height: 1.6;">
                            Nếu bạn có thêm câu hỏi hoặc cần hỗ trợ thêm, vui lòng trả lời email này hoặc liên hệ với chúng tôi qua trang hỗ trợ.
                        </p>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . _HOST_URL . '/lien_he" style="display: inline-block; background: linear-gradient(135deg, #D96C16 0%, #F29F05 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 600; font-size: 14px; box-shadow: 0 4px 15px rgba(217, 108, 22, 0.3);">
                    � Liên hệ lại với chúng tôi
                </a>
            </div>

            <!-- Thank You Note -->
            <div style="text-align: center; margin-top: 30px; padding-top: 25px; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                <p style="margin: 0 0 8px 0; font-size: 14px; color: #e2e8f0;">
                    Cảm ơn bạn đã sử dụng dịch vụ của <strong style="color: #F29F05;">Phê Phim</strong>! ❤️
                </p>
                <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                    Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: rgba(15, 23, 42, 0.7); padding: 25px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <p style="margin: 0; font-size: 13px; color: #64748b;">Email tự động từ hệ thống <strong style="color: #F29F05;">Phê Phim</strong> ✨</p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #475569;">© 2024 Phê Phim. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';
            sendMail($emailTo, $subject, $content);
            $dataUpdate = [
                'support_status_id' => $new_status,
            ];
            $conditionUpdate = 'id=' . $id;
            $checkUpdate = $this->supportModel->updateSupport($conditionUpdate, $dataUpdate);
            if ($checkUpdate) {
                setSessionFlash('msg', 'Cập nhật thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/support');
            } else {
                setSessionFlash('msg', 'Cập nhật thất bại');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/support');
            }
        }
    }
}
