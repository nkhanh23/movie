<?php
class Router
{
    //$router->get('admin/dasboard', 'HomeController@adminDashboard');
    /* VD:
    Array
(
    [GET] => Array
        (
            [/admin/dasboard] => HomeController@adminDashboard
            [/clients/dasboard] => HomeControllerController@clientDashboard
            [/login] => AuthController@index
        )

    [POST] => Array
        (
            [/login] => AuthControllerController@index
        )

)
    */
    protected $routers = [];
    public function get($url, $action)
    {
        //Tạo ra mảng get để lưu trữ phương thức GET
        $this->routers['GET'][$url] = $action;
    }

    public function post($url, $action)
    {
        //Tạo ra mảng post để lưu trữ phương thức POST
        $this->routers['POST'][$url] = $action;
    }

    public function getrout()
    {
        return $this->routers;
    }

    public function handlePath($method, $url)
    {
        $url = $url ?: '/';
        //Kiểm tra trong router có phương thức và url được truyền vào không
        if (isset($this->routers[$method][$url])) {
            $action = $this->routers[$method][$url];
            // Tách action ra thành 2 biến controller và func
            //VD : HomeControllerController@clientDashboard thành $controller=HomeControllerController và $function=clientDashboard
            [$controller, $func] = explode('@', $action);
            require_once './app/Controller/' . $contrller . '.php';
            $controllerOne = new $controller;
            $controllerOne->$func;
        }
    }
}
