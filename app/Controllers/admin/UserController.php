<?php
class UserController extends baseController
{
    private $userModel;
    private $activityModel;
    public function __construct()
    {
        $this->userModel = new User;
        $this->activityModel = new Activity;
    }

    public function list()
    {
        $filter = filterData();
        $group = '';
        $status = '';
        $keyword = '';
        $chuoiWhere = '';


        if (isset($filter['keyword'])) {
            $keyword = $filter['keyword'];
        }
        if (isset($filter['group'])) {
            $group = $filter['group'];
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
            $chuoiWhere .= "u.fullname LIKE '%$keyword%' OR u.email LIKE '%$keyword%' OR u.address LIKE '%$keyword%'";
        }

        if (!empty($status)) {
            if (strpos($chuoiWhere, 'WHERE') == false) {
                $chuoiWhere .= ' WHERE ';
            } else {
                $chuoiWhere .= ' AND ';
            }
            $chuoiWhere .= "us.id = $status";
        }

        if (!empty($group)) {
            if (strpos($chuoiWhere, 'WHERE') == false) {
                $chuoiWhere .= ' WHERE ';
            } else {
                $chuoiWhere .= ' AND ';
            }
            $chuoiWhere .= "g.id = $group";
        }

        $countAllUser = $this->userModel->countAllUser("SELECT u.*, g.name as group_name
        FROM users u
        LEFT JOIN groups g ON g.id = u.group_id
        LEFT JOIN user_status us ON us.id = u.status
        $chuoiWhere");
        $maxData = $countAllUser;
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

        $getAllUser = $this->userModel->getAllUser("SELECT u.*, g.name as group_name
        FROM users u
        LEFT JOIN groups g ON g.id = u.group_id
        LEFT JOIN user_status us ON us.id = u.status
        $chuoiWhere
        GROUP BY u.id
        ORDER BY u.created_at ASC
        LIMIT $offset , $perPage
        ");
        $getAllGroup = $this->userModel->getAllGroup();
        $getAllUserStatus = $this->userModel->getAllUserStatus();

        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'getAllUser' => $getAllUser,
            'getAllGroup' => $getAllGroup,
            'getAllUserStatus' => $getAllUserStatus,
            'countAllUser' => $countAllUser,
            'group' => $group,
            'status' => $status,
            'maxPage' => $maxPage,
            'page' => $page,
            'queryString' => $queryString,
        ];
        $this->renderView('/layout-part/admin/user/list', $data);
    }

    public function showAdd()
    {
        $getAllGroup = $this->userModel->getAllGroup();
        $getAllUserStatus = $this->userModel->getAllUserStatus();

        $data = [
            'getAllGroup' => $getAllGroup,
            'getAllUserStatus' => $getAllUserStatus,
        ];
        $this->renderView('/layout-part/admin/user/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            //validate tittle
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = ' Tên phim bắt buộc phải nhập';
            }

            //validate original_tittle
            if (empty(trim($filter['email']))) {
                $errors['email']['email'] = ' Tên gốc bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'group_id' => $filter['group_id'],
                    'status' => $filter['status_id'],
                    'created_at' => date('Y:m:d H:i:s'),
                    'avartar' => _HOST_URL . '/public/uploads/9-anh-dai-dien-trang-inkythuatso-03-15-27-03.jpg',
                ];
                $checkInsert = $this->userModel->insertUser($data);
                if ($checkInsert) {
                    // GHI LOG
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'insert',
                        'users',
                        $filter['id'],
                        $data, // Lưu data cũ để audit
                        null
                    );
                    setSessionFlash('msg', 'Thêm người dùng mới thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/user');
                } else {
                    setSessionFlash('msg', 'Thêm người dùng mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/user/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $idUser = $filter['id'];
        $conditionGetOneUser = 'id= ' . $idUser;
        $getOneUser = $this->userModel->getOneUser($conditionGetOneUser);
        $getAllGroup = $this->userModel->getAllGroup();
        $getAllUserStatus = $this->userModel->getAllUserStatus();
        $data = [
            'oldData' => $getOneUser,
            'getAllGroup' => $getAllGroup,
            'getAllUserStatus' => $getAllUserStatus,
            'idUser' => $idUser
        ];
        $this->renderView('/layout-part/admin/user/edit', $data);
    }

    public function edit()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            $idUser = $filter['idUser'];

            $conditionGetOneUser = 'id= ' . $filter['idUser'];
            $getOneUser = $this->userModel->getOneUser($conditionGetOneUser);
            //validate tittle
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = ' Tên phim bắt buộc phải nhập';
            }

            //validate original_tittle
            if (empty(trim($filter['email']))) {
                $errors['email']['email'] = ' Tên gốc bắt buộc phải nhập';
            }

            //validate mật khẩu
            if (empty(trim($filter['password']))) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc nhập';
            } else {
                if (strlen(trim($filter['password'])) < 8) {
                    $errors['password']['length'] = ' Mật khẩu phải trên 8 kí tự';
                }
            }

            if (empty($errors)) {
                $dataUpdate = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'group_id' => $filter['group_id'],
                    'status' => $filter['status_id'],
                    'updated_at' => date('Y:m:d H:i:s'),
                ];
                $checkPassword = password_verify($filter['password'], $getOneUser['password']);
                if ($checkPassword) {
                    $conditionUpdateUser = 'id=' . $filter['idUser'];
                    $oldData = $this->userModel->getOneUser($conditionUpdateUser);
                    $checkUpdate = $this->userModel->updateUser($dataUpdate, $conditionUpdateUser);
                    if ($checkUpdate) {
                        // Lặp qua dataUpdate để xem trường nào thay đổi
                        $changes = [];
                        foreach ($dataUpdate as $key => $value) {
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
                                'users',
                                $filter['idUser'],
                                $oldData,
                                $dataUpdate
                            );
                        }
                        setSessionFlash('msg', 'Cập nhật thành công');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/user');
                    } else {
                        setSessionFlash('msg', 'Cập nhật thất bại');
                        setSessionFlash('msg_type', 'danger');
                        reload('/admin/user');
                    }
                } else {
                    setSessionFlash('msg', 'Mật khẩu không đúng.');
                    setSessionFlash('msg_type', 'danger');
                    reload('/admin/user/edit?id=' . $idUser);
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                reload('/admin/user/edit?id=' . $idUser);
            }
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter['id'])) {
            $user_id = $filter['id'];
            $condition = 'id=' . $user_id;
            $checkId = $this->userModel->getOneUser($condition);
            if (!empty($checkId)) {
                $conditionDeleteUser = 'id=' . $user_id;
                $deleteUser = $this->userModel->deleteUser($conditionDeleteUser);
                if ($deleteUser) {
                    // GHI LOG
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'delete',
                        'users',
                        $user_id,
                        $checkId, // Lưu data cũ để audit
                        null
                    );
                    setSessionFlash('msg', 'Xoá người dùng thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/user');
                } else {
                    setSessionFlash('msg', 'Xoá người dùng thất bại.');
                    setSessionFlash('msg_type', 'danger');
                    reload('/admin/user');
                }
            } else {
                setSessionFlash('msg', 'Người dùng không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/user');
            }
        } else {
            setSessionFlash('msg', 'Xoá người dùng thất bại.');
            setSessionFlash('msg_type', 'danger');
            reload('/admin/user');
        }
    }
}
