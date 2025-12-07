<?php
class PersonController extends baseController
{
    private $personModel;
    public function __construct()
    {
        $this->personModel = new Person;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $keyword = '';
        $role = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['role'])) {
                $role = $filter['role'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "p.name LIKE '%$keyword%'";
            }

            if (!empty($role)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "pr.id = $role";
            }
        }

        //Phân trang
        $sql = "SELECT p.*, COUNT( DISTINCT mp.movie_id) as count_movies, GROUP_CONCAT( DISTINCT pr.name SEPARATOR ', ') as role_name
        FROM persons p
        LEFT JOIN movie_person mp ON p.id = mp.person_id
        LEFT JOIN person_roles pr ON pr.id = mp.role_id
        $chuoiWhere
        GROUP BY p.id";
        $countPerson = $this->personModel->countPerson($sql);
        $maxData = $countPerson;
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
        if ($maxPage > 0 && $page > $maxPage) {
            $page = $maxPage;
        }
        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        $countAllPersons = $this->personModel->countAllPersons();
        $getAllPersonRole = $this->personModel->getAllPersonRole();
        $getAllPersonWithCount = $this->personModel->getAllPersonWithCount("SELECT p.*, COUNT(mp.movie_id) as count_movies, GROUP_CONCAT( DISTINCT pr.name SEPARATOR ', ') as role_name
        FROM persons p
        LEFT JOIN movie_person mp ON p.id = mp.person_id
        LEFT JOIN person_roles pr ON pr.id = mp.role_id
        $chuoiWhere
        GROUP BY p.id
        ORDER BY created_at DESC
        LIMIT $offset , $perPage
        ");
        //Xử lý query
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'getAllPersonWithCount' => $getAllPersonWithCount,
            'getAllPersonRole' => $getAllPersonRole,
            'role' => $role,
            'keyword' => $keyword,
            'page' => $page,
            'maxPage' => $maxPage,
            'queryString' => $queryString,
            'countAllPersons' => $countAllPersons

        ];
        $this->renderView('/layout-part/admin/person/list', $data);
    }

    public function showAdd()
    {
        $getAllPersonRole = $this->personModel->getAllPersonRole();

        $data = [
            'getAllPersonRole' => $getAllPersonRole
        ];
        $this->renderView('/layout-part/admin/person/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate name
            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên phim bắt buộc phải nhập';
            } else {
                $name = trim($filter['name']);
                $checkName = $this->personModel->countPerson("SELECT * FROM persons WHERE name = '$name'");
                if ($checkName >= 1) {
                    $errors['name']['required'] = ' Diễn viên đã tồn tại ';
                }
            }
            //validate slug
            if (empty(trim($filter['slug']))) {
                $errors['slug']['required'] = ' Slug bắt buộc phải nhập';
            }
            //validate avartar
            if (empty(trim($filter['avatar']))) {
                $errors['avatar']['required'] = ' Đường dẫn ảnh bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $filter['name'],
                    'slug' => $filter['slug'],
                    'name' => $filter['name'],
                    'avatar' => $filter['avatar'],
                    'bio' => $filter['bio'],
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $checkInsert = $this->personModel->insertPerson($data);
                if ($checkInsert) {
                    $person_id = $this->personModel->getLastIdPerson();
                    $role_id = $filter['role_id'];
                    if (!empty($role_id)) {
                        foreach ($role_id as $item) {
                            $data = [
                                'person_id' => $person_id,
                                'role_id' => $item
                            ];
                            $checkInsertMoviePerson = $this->personModel->insertMoviePerson($data);
                        }
                        if ($checkInsertMoviePerson) {
                            setSessionFlash('msg', 'Thêm người dùng mới thành công');
                            setSessionFlash('msg_type', 'success');
                            reload('/admin/person');
                        } else {
                            setSessionFlash('msg', 'Thêm người dùng mới thất bại');
                            setSessionFlash('msg_type', 'danger');
                            setSessionFlash('oldData', $filter);
                            setSessionFlash('errors', $errors);
                            reload('/admin/person/add');
                        }
                    }
                    setSessionFlash('msg', 'Thêm người dùng mới thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/person');
                } else {
                    setSessionFlash('msg', 'Thêm người dùng mới thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                    reload('/admin/person/add');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/person/add');
            }
        }
    }

    public function showEdit()
    {
        $getAllPersonRole = $this->personModel->getAllPersonRole();
        $filter = filterData('get');
        $idPerson = $filter['id'];
        $sql = "SELECT * FROM persons WHERE id = $idPerson";
        $result = $this->personModel->getOnePerson($sql);
        $conditionmoviePersonData = 'person_id=' . $idPerson;
        $moviePersonData = $this->personModel->getAllMoviePerson($conditionmoviePersonData);
        $selectedRoleId = [];
        if (!empty($moviePersonData)) {
            $selectedRoleId = array_column($moviePersonData, 'role_id');
        }
        $data = [
            'getAllPersonRole' => $getAllPersonRole,
            'oldData' => $result,
            'idPerson' => $idPerson,
            'selectedRoleId' => $selectedRoleId
        ];
        $this->renderView('/layout-part/admin/person/edit', $data);
    }

    public function edit()
    {
        $filter = filterData();
        $errors = [];

        //validate name
        if (empty(trim($filter['name']))) {
            $errors['name']['required'] = ' Tên phim bắt buộc phải nhập';
        }
        //validate slug
        if (empty(trim($filter['slug']))) {
            $errors['slug']['required'] = ' Slug bắt buộc phải nhập';
        }
        //validate avartar
        if (empty(trim($filter['avatar']))) {
            $errors['avatar']['required'] = ' Đường dẫn ảnh bắt buộc phải nhập';
        }

        if (empty($errors)) {
            $dataUpdate = [
                'name' => $filter['name'],
                'slug' => $filter['slug'],
                'avatar' => $filter['avatar'],
                'bio' => $filter['bio'],
                'updated_at' => date('Y:m:d H:i:s')
            ];
            $conditionUpdate = 'id=' . $filter['id'];
            $checkUpdate = $this->personModel->updatePerson($dataUpdate, $conditionUpdate);
            if ($checkUpdate) {
                // $conditionGetOne = 'id=' . $filter['id'];
                // $getOnePerson = $this->personModel->getOnePerson("SELECT * FROM persons WHERE $conditionGetOne");
                $person_id = $filter['id'];
                $role_id = $filter['role_id'];
                $conditionDeleteMoviePerson = 'person_id=' . $person_id;
                $deleteMoviePerson = $this->personModel->deleteMoviePerson($conditionDeleteMoviePerson);
                if ($deleteMoviePerson) {
                    if (!empty($role_id)) {
                        foreach ($role_id as $item) {
                            $data = [
                                'person_id' => $person_id,
                                'role_id' => $item
                            ];
                            $checkInsertMoviePerson = $this->personModel->insertMoviePerson($data);
                        }
                    }
                    if ($checkInsertMoviePerson) {
                        setSessionFlash('msg', 'Cập nhật người dùng thành công');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/person');
                    } else {
                        setSessionFlash('msg', 'Cập nhật người dùng thất bại');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('oldData', $filter);
                        setSessionFlash('errors', $errors);
                        reload('/admin/person/edit?id=' . $filter['id']);
                    }
                }
            } else {
                setSessionFlash('msg', 'Cập nhật thất bại, vui lòng thử lại');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                reload('/admin/person/edit?id=' . $filter['id']);
            }
        } else {
            setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('errors', $errors);
            reload('/admin/person/edit?id=' . $filter['id']);
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter)) {
            $person_id = $filter['id'];
            $condition = 'id=' . $person_id;
            $checkID = $this->personModel->getOnePerson("SELECT * FROM persons WHERE $condition");
            if (!empty($checkID)) {
                $conditionDeleteMoviePerson = 'person_id=' . $person_id;
                $deleteMoviePerson = $this->personModel->deleteMoviePerson($conditionDeleteMoviePerson);
                if ($deleteMoviePerson) {
                    $conditionDeletePerson = 'id=' . $person_id;
                    $deletePerson = $this->personModel->deletePerson($conditionDeletePerson);
                    if ($deletePerson) {
                        setSessionFlash('msg', 'Xoá diễn viên thành công.');
                        setSessionFlash('msg_type', 'success');
                        reload('/admin/person');
                    } else {
                        setSessionFlash('msg', 'Xoá diễn viên thất bại.');
                        setSessionFlash('msg_type', 'danger');
                        reload('/admin/person');
                    }
                }
            } else {
                setSessionFlash('msg', 'Diễn viên không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/person');
            }
        } else {
            setSessionFlash('msg', 'Xoá diễn viên thất bại.');
            setSessionFlash('msg_type', 'danger');
        }
    }
}
