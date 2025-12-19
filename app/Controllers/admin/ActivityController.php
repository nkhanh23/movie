<?php
class ActivityController extends baseController
{
    private $activityModel;
    public function __construct()
    {
        $this->activityModel = new Activity;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $action = '';
        $user = '';
        $keyword = '';
        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['action'])) {
                $action = $filter['action'];
            }
            if (isset($filter['user'])) {
                $user = $filter['user'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "u.fullname LIKE '%$keyword%' OR l.old_values LIKE '%$keyword%' OR l.new_values LIKE '%$keyword%'";
            }

            if (!empty($action)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "action LIKE '%$action%'";
            }

            if (!empty($user)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "u.group_id = $user";
            }
        }

        //Xử lí phân trang
        $countLogs = $this->activityModel->pagination($chuoiWhere);
        // echo '<pre>';
        // print_r($countLogs);
        // echo '</pre>';
        // die();
        $maxData = $countLogs[0]['total'];
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
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }
        $logs = $this->activityModel->getAllLogs($chuoiWhere, $perPage, $offset);
        //Xử lý quẻy
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }
        $data = [
            'logs' => $logs,
            'action' => $action,
            'user' => $user,
            'keyword' => $keyword,
            'countResult' => $maxData,
            'page' => $page,
            'maxPage' => $maxPage,
            'queryString' => $queryString
        ];
        $this->renderView('/layout-part/admin/logs/list', $data);
    }

    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter['id'])) {
            $checkDelete = $this->activityModel->deleteLog($filter['id'], 'id=' . $filter['id']);
            if ($checkDelete) {
                setSessionFlash('msg', 'Xoá thành công.');
                setSessionFlash('msg_type', 'success');
                reload('/admin/logs');
            } else {
                setSessionFlash('msg', 'Xoá log thất bại.');
                setSessionFlash('msg_type', 'danger');
                reload('/admin/logs');
            }
        }
    }
}
