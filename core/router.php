<?php
class Router
{
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

    // Hàm phụ trợ để chạy Controller
    private function executeRoute($action, $params = [])
    {
        // Tách Controller và Method
        [$controllerName, $methodName] = explode('@', $action);

        // Kiểm tra xem file/class có tồn tại không
        // Ở đây giả định Autoload đã làm việc

        if (class_exists($controllerName)) {
            $controllerInstance = new $controllerName();

            if (method_exists($controllerInstance, $methodName)) {
                // Gọi hàm và truyền tham số (Ví dụ: $slug) vào
                call_user_func_array([$controllerInstance, $methodName], $params);
            } else {
                echo "Lỗi: Method '$methodName' không tồn tại trong $controllerName";
            }
        } else {
            echo "Lỗi: Controller '$controllerName' không tồn tại";
        }
    }

    public function handlePath($method, $url)
    {

        $url = $url ?: '/';

        // Kiểm tra khớp chính xác
        if (isset($this->routers[$method][$url])) {
            $this->executeRoute($this->routers[$method][$url]);
            return;
        }

        // Nếu không khớp chính xác, chuyển sang khớp mẫu (Regex) cho URL động
        // Ví dụ: /phim/{slug}
        if (isset($this->routers[$method])) {
            foreach ($this->routers[$method] as $routePath => $action) {
                // Bỏ qua các route tĩnh đã check ở trên
                if (strpos($routePath, '{') === false) {
                    continue;
                }

                // Chuyển đổi route path thành Regex
                // Biến đổi {slug}, {id}... thành (.+) để bắt giá trị
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath);
                $pattern = "#^" . $pattern . "$#";

                // Kiểm tra xem URL hiện tại có khớp pattern không
                if (preg_match($pattern, $url, $matches)) {
                    // Xóa phần tử đầu tiên (là toàn bộ chuỗi khớp), chỉ giữ lại các tham số
                    array_shift($matches);

                    // Thực thi route và truyền tham số (slug/id) vào
                    $this->executeRoute($action, $matches);
                    return;
                }
            }
        }


        // //Kiểm tra trong router có phương thức và url được truyền vào không
        // if (isset($this->routers[$method][$url])) {
        //     $action = $this->routers[$method][$url];
        //     // Tách action ra thành 2 biến controller và func
        //     //VD : HomeControllerController@clientDashboard thành $controller=HomeControllerController và $function=clientDashboard
        //     [$controller, $func] = explode('@', $action);
        //     // require_once './app/Controller/' . $controller . '.php';
        //     $controllerOne = new $controller;
        //     $controllerOne->$func();
        // }
    }
}
