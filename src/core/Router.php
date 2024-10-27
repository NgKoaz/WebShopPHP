<?php

namespace App\core;

use Exception;

class Router
{
    private $routes = [];

    public function get($uri, $callback, ...$middlewares)
    {
        $this->routes["GET"][$uri] = [
            "callback" => $callback,
            "middlewares" => $middlewares
        ];
    }

    public function post($uri, $callback)
    {
        $this->routes["POST"][$uri] = $callback;
    }

    public function addControllerInModule($module, $controller)
    {
        $path = "/phppractice/src/modules/$module/controllers/$controller.php";
        $namespace = "App\\modules\\$module\\controllers\\$controller";
        if (file_exists($path)) {
            require_once $path;
            if (class_exists($namespace)) {
                call_user_func([$namespace, "registerRoutes"], $this, $module);
            } else {
                throw new Exception("ERROR: Not found any proper class in `$controller.php` file.");
            }
        } else {
            throw new Exception("ERROR: $controller.php file is not found! Please check!");
        }
    }

    public function resolve()
    {
        if (empty($this->routes)) throw new Exception("ERROR: Set first URI for app!");
        $request = $_REQUEST;
        $method = $_SERVER["REQUEST_METHOD"];
        $path = $_SERVER["REQUEST_URI"];
        $handler = $this->routes[$method][$path];
        if (isset($handler)) {

            $callback = $handler["callback"] ?? false;
            if (!$callback) throw new Exception("ERROR: No callback for method: $method and path: $path");

            $next = function ($request) use ($callback) {
                $this->callControllerMethod($callback);
            };

            $middlewares = $handler["middlewares"];
            if (is_array($middlewares)) {
                while ($middleware = array_pop($middlewares)) {
                    $next = function ($request) use ($middleware, $next) {
                        $mdInstance = new $middleware;
                        $mdInstance->handle($request, $next);
                    };
                }
            }
            $next($request);
            return;
        }

        $this->notFoundView();
        return;
    }

    private function notFoundView()
    {
        echo "404 - NOT FOUND!";
    }

    // @result: boolean type;
    private function callControllerMethod($callback)
    {
        list($controller, $method) = explode("@", $callback);

        if (!class_exists($controller)) {
            echo "Controller $controller not found!";
            return false;
        }

        $conInstance = new $controller();
        if (method_exists($conInstance, $method)) {
            $conInstance->$method();
            return true;
        }

        echo "Method $method not found in controller $controller!";
        return false;
    }








    // public function addController($controller)
    // {
    //     $controllerPath = "/phppractice/src/controllers/$controller.php";
    //     if (file_exists($controllerPath)) {
    //         require_once $controllerPath;
    //         if (class_exists($controller)) {
    //             call_user_func([$controller, "registerRoutes"], $this);
    //         } else {
    //             echo "ERROR: Not found any proper class in `$controller.php` file.";
    //         }
    //     } else {
    //         echo "ERROR: Controller File is not found! Please check!";
    //     }
    // }


    // private function registerRoutesForController($controller)
    // {
    //     // get_class_methods($controller);
    //     var_dump(get_class_methods($controller));
    //     $methodArray = get_class_methods($controller);

    //     foreach ($methodArray as $method) {
    //         $method = strtolower($method);
    //         $method.
    //         print_r("\n" . strtolower($method) . "\n");
    //     }
    // }
}
