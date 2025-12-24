<?php

class CountryController extends baseController
{
    private $countryModel;
    private $activityModel;
    public function __construct()
    {
        $this->countryModel = new Country;
        $this->activityModel = new Activity;
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
                $chuoiWhere .= "c.name LIKE '%$keyword%'";
            }
        }

        $countCountry = $this->countryModel->countCountry($chuoiWhere);
        $maxData = $countCountry[0]['count'];
        $perPage = 10;
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

        //Xử lý query
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $getAllCountryWithCount = $this->countryModel->getAllCountryWithCount($chuoiWhere, $offset, $perPage);
        $data = [
            'getAllCountryWithCount' => $getAllCountryWithCount,
            'page' => $page,
            'maxPage' => $maxPage,
            'queryString' => $queryString,
            'keyword' => $keyword,
            'countAllCountries' => $maxData
        ];
        $this->renderView('/layout-part/admin/country/list', $data);
    }

    public function showAdd()
    {
        $data = [];
        $this->renderView('/layout-part/admin/country/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate name
            if (empty(trim($filter['name']))) {
                $errors['name']['required'] = ' Tên Quốc gia bắt buộc phải nhập';
            } else {
                $name = trim($filter['name']);
                $checkName = $this->countryModel->countCheckCountry($name);
                if ($checkName > 0) {
                    $errors['name']['required'] = ' Quốc gia đã tồn tại ';
                }
            }
            //validate slug
            if (empty(trim($filter['slug']))) {
                $errors['slug']['required'] = ' Slug bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $data = [
                    'name' => $filter['name'],
                    'slug' => $filter['slug'],
                ];
                $checkInsert = $this->countryModel->insertCountry($data);
                if ($checkInsert) {
                    //ghi log
                    $this->activityModel->log(
                        $_SESSION['auth']['id'],
                        'create',
                        'Thêm Quốc gia',
                        'Quốc gia: ' . $filter['name']
                    );
                    setSessionFlash('msg', 'Thêm Quốc gia thành công');
                    setSessionFlash('msg_type', 'success');
                    reload('/admin/country');
                } else {
                    setSessionFlash('msg', 'Thêm Quốc gia thất bại');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('oldData', $filter);
                    setSessionFlash('errors', $errors);
                    reload('/admin/country/add');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/country/add');
            }
        }
    }

    public function showEdit()
    {
        $filter = filterData('get');
        $id = $filter['id'];
        $getOneCountry = $this->countryModel->getOneCountry($id);
        $data = [
            'id' => $id,
            'oldData' => $getOneCountry
        ];
        $this->renderView('/layout-part/admin/country/edit', $data);
    }

    public function edit()
    {
        $filter = filterData();
        $errors = [];
        //validate name
        if (empty(trim($filter['name']))) {
            $errors['name']['required'] = ' Tên Quốc gia bắt buộc phải nhập';
        }
        //validate slug
        if (empty(trim($filter['slug']))) {
            $errors['slug']['required'] = ' Slug bắt buộc phải nhập';
        }

        if (empty($errors)) {
            $data = [
                'name' => $filter['name'],
                'slug' => $filter['slug'],
            ];
            $condition = "id = " . $filter['id'];
            $oldData = $this->countryModel->getOneCountry($filter['id']);
            $checkUpdate = $this->countryModel->updateCountry($data, $condition);
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
                        'countries',
                        $filter['id'],
                        $oldData,
                        $data
                    );
                }
                setSessionFlash('msg', 'Cập nhật Quốc gia thành công');
                setSessionFlash('msg_type', 'success');
                reload('/admin/country');
            } else {
                setSessionFlash('msg', 'Cập nhật Quốc gia thất bại');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/admin/country/edit?id=' . $filter['id']);
            }
        } else {
            setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
            setSessionFlash('msg_type', 'danger');
            setSessionFlash('oldData', $filter);
            setSessionFlash('errors', $errors);
            reload('/admin/country/edit?id=' . $filter['id']);
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        $id = $filter['id'];
        $condition = "id = " . $id;
        $checkDelete = $this->countryModel->deleteCountry($condition);
        if ($checkDelete) {
            //ghi log
            $this->activityModel->log(
                $_SESSION['auth']['id'],
                'delete',
                'countries',
                $id,
                $this->countryModel->getOneCountry($id),
                null
            );
            setSessionFlash('msg', 'Xóa Quốc gia thành công');
            setSessionFlash('msg_type', 'success');
            reload('/admin/country');
        } else {
            setSessionFlash('msg', 'Xóa Quốc gia thất bại');
            setSessionFlash('msg_type', 'danger');
            reload('/admin/country');
        }
    }
}
