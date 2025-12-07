<?php
class RoleController extends baseController
{
    private $roleModel;
    public function __construct()
    {
        $this->roleModel = new Role;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $keyword = '';
        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "name LIKE '%$keyword%'";
            }
        }

        $maxData = $this->roleModel->countRole("SELECT * 
        FROM person_roles
        $chuoiWhere
        ORDER BY created_at DESC");
        $perPage = 2;
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

        $getAllRole = $this->roleModel->getAllRole("SELECT * 
        FROM person_roles
        $chuoiWhere
        ORDER BY created_at ASC
        LIMIT $offset , $perPage
        ");
        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'getAllRole' => $getAllRole,
            'keyword'    => $keyword,
            'maxPage' => $maxPage,
            'maxData' => $maxData,
            'queryString' => $queryString,
            'page' => $page,
        ];
        $this->renderView('/layout-part/admin/role/list', $data);
    }

    public function showAdd()
    {
        $data = [];
        $this->renderView('/layout-part/admin/role/add', $data);
    }

    public function add()
    {
        $filter = filterData();
        $errors = [];

        //validate name
        if (empty(trim($filter['name']))) {
            $errors['name']['required'] = ' Tên vai trò bắt buộc phải nhập';
        } else {
            $name = $filter['name'];
            $checkName = $this->roleModel->countRole("SELECT * FROM person_roles WHERE name = '$name'");
            if ($checkName >= 1) {
                $errors['name']['check'] = 'Vai trò đã tồn tại';
            }
        }

        //validate name
        if (empty(trim($filter['slug']))) {
            $errors['slug']['required'] = ' Slug bắt buộc phải nhập';
        }

        //validate name
        if (empty(trim($filter['description']))) {
            $errors['description']['required'] = ' Mô tả bắt buộc phải nhập';
        }

        if (empty($errors)) {
            $data = [
                'name' => $filter['name'],
                'slug' => $filter['slug'],
                'description' => $filter['description'],
                'created_at' => date('Y:m:d'),
            ];
            $checkInsert = $this->roleModel->insertRole($data);
            if ($checkInsert) {
                setSessionFlash('msg', 'Thêm vai trò mới thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/role');
            } else {
                setSessionFlash('msg', 'Thêm vai trò mới thất bại');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/role/add');
            }
        } else {
        }
        setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
        reload('/admin/role/add');
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $role_id = $filter['id'];
        $condition  = 'id=' . $role_id;
        $getOneRole = $this->roleModel->getOneRole($condition);
        $data = [
            'oldData' => $getOneRole,
            'role_id' => $role_id
        ];
        $this->renderView('/layout-part/admin/role/edit', $data);
    }

    public function edit()
    {
        $filter = filterData();
        $errors = [];

        //validate name
        if (empty(trim($filter['name']))) {
            $errors['name']['required'] = ' Tên vai trò bắt buộc phải nhập';
        }

        //validate name
        if (empty(trim($filter['slug']))) {
            $errors['slug']['required'] = ' Slug bắt buộc phải nhập';
        }

        //validate name
        if (empty(trim($filter['description']))) {
            $errors['description']['required'] = ' Mô tả bắt buộc phải nhập';
        }

        if (empty($errors)) {
            $data = [
                'name' => $filter['name'],
                'slug' => $filter['slug'],
                'description' => $filter['description'],
                'updated_at' => date('Y:m:d'),
            ];
            $condition = 'id=' . $filter['id'];
            $checkUpdate = $this->roleModel->updateRole($data, $condition);
            if ($checkUpdate) {
                setSessionFlash('msg', 'Cập nhật vai trò mới thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/role');
            } else {
                setSessionFlash('msg', 'Cập nhật vai trò mới thất bại');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/role/edit?id=' . $filter['id']);
            }
        } else {
        }
        setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setSessionFlash('msg_type', 'danger');
        setSessionFlash('oldData', $filter);
        setSessionFlash('errors', $errors);
        reload('/admin/role/edit?id=' . $filter['id']);
    }

    public function delete()
    {
        $filter = filterData();
        if (!empty($filter['id'])) {
            $role_id = $filter['id'];
            $conditionGetOne = 'id=' . $role_id;
            $checkID = $this->roleModel->getOneRole($conditionGetOne);
            if (!empty($checkID)) {
                $conditionDeleteMoviePerson = 'role_id=' . $role_id;
                $deletemoviePerson = $this->roleModel->deleteMoviePerson($conditionDeleteMoviePerson);
                if ($deletemoviePerson) {
                    $conditionDelete = 'id=' . $role_id;
                    $deleteRole = $this->roleModel->deleteRole($conditionDelete);
                    if ($deleteRole) {
                        setSessionFlash('msg', 'Xoá vai trò thành công.');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/role');
                    } else {
                        setSessionFlash('msg', 'Xoá vai trò thành công.');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/role');
                    }
                }
            } else {
                setSessionFlash('msg', 'Bài viết không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/role');
            }
        }
    }
}
