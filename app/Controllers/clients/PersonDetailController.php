<?php
class PersonDetailController extends baseController
{
    private $personModel;
    private $movieModel;
    public function __construct()
    {
        $this->personModel = new Person();
        $this->movieModel = new Movies();
    }

    public function showPerson()
    {
        $filter = filterData('get');
        $idPerson = $filter['id'];
        //Lấy thông tin chi tiết của người
        $personDetail = $this->personModel->getPersonDetail($idPerson);

        //Lấy danh sách phim của người
        $maxData = $this->personModel->countPersonMovies($idPerson);
        $perPage = 10;
        $maxPage = ceil($maxData / $perPage);
        $offset = 0;
        $page = 1;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }
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
        $getPersonMovies = $this->personModel->getPersonMovies($idPerson, $offset, $perPage);

        //Xử lý query
        $queryString = '';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }

        $data = [
            'personDetail' => $personDetail,
            'getPersonMovies' => $getPersonMovies,
            'page' => $page,
            'maxPage' => $maxPage,
            'queryString' => $queryString,
            'countMovies' => $maxData,
        ];
        $this->renderView('layout-part/client/persons', $data);
    }
}
