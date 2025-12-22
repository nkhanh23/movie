<?php

class SupportTypeController extends baseController
{
    private $supportModel;
    private $activityModel;
    public function __construct()
    {
        $this->supportModel = new Support;
        $this->activityModel = new Activity;
    }

    public function list()
    {
        $getAllSupportType = $this->supportModel->getAllSupportType();
        $data = [
            'getAllSupportType' => $getAllSupportType,
        ];
        $this->renderView('/layout-part/admin/support_type/list', $data);
    }

    public function showAdd()
    {
        $this->renderView('/layout-part/admin/support_type/add');
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            //validate tittle
            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên phim bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $filter['name'],
                    'created_at' => date('Y:m:d H:i:s'),
                ];
                $checkInsert = $this->supportModel->insertSupportType($data);
                if ($checkInsert) {
                    // GHI LOG
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'insert',
                        'support_types',
                        $filter['id'],
                        $data, // Lưu data cũ để audit
                        null
                    );
                    setSessionFlash('msg', 'Thêm loại hỗ trợ mới thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/support_type');
                } else {
                    setSessionFlash('msg', 'Thêm loại hỗ trợ mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/support_type/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $id = $filter['id'];
        $getSupportTypeById = $this->supportModel->getSupportTypeById($id);
        $data = [
            'id' => $id,
            'oldData' => $getSupportTypeById,
        ];
        $this->renderView('/layout-part/admin/support_type/edit', $data);
    }

    public function edit()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            //validate tittle
            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên loại hỗ trợ bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $filter['name'],
                    'updated_at' => date('Y:m:d H:i:s'),
                ];
                $condition = 'id=' . $filter['id'];
                $oldData = $this->supportModel->getSupportTypeById($filter['id']);
                $checkUpdate = $this->supportModel->updateSupportType($data, $condition);
                if ($checkUpdate) {
                    // Lặp qua dataUpdate để xem trường nào thay đổi
                    $changes = [];
                    foreach ($data as $key => $value) {
                        if ($oldData[$key] != $value) {
                            $changes[$key] = [
                                'from' => $oldData[$key],
                                'to' => $value
                            ];
                        }
                    }
                    //ghi log
                    if (!empty($changes)) {
                        $this->activityModel->log(
                            $_SESSION['auth']['id'],
                            'update',
                            'support_types',
                            $filter['id'],
                            $oldData,
                            $data
                        );
                    }
                    setSessionFlash('msg', 'Sửa loại hỗ trợ thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/support_type');
                } else {
                    setSessionFlash('msg', 'Sửa loại hỗ trợ thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/support_type/edit');
            }
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        $id = $filter['id'];
        $getSupportTypeById = $this->supportModel->getSupportTypeById($id);
        $checkDelete = $this->supportModel->deleteSupportType($id);
        if ($checkDelete) {
            // ghi log
            $this->activityModel->log(
                $_SESSION['auth']['id'],
                'delete',
                'support_types',
                $id,
                $getSupportTypeById,
                null
            );
            setSessionFlash('msg', 'Xóa loại hỗ trợ thành công');
            setSessionFlash('msg_type', 'success');
            reload('/admin/support_type');
        } else {
            setSessionFlash('msg', 'Xóa loại hỗ trợ thất bại');
            setSessionFlash('msg_type', 'danger');
            reload('/admin/support_type');
        }
    }
}
