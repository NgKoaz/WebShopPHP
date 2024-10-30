<?php

namespace App\core;

use App\core\Attributes\CoreAttribute;
use Exception;
use ReflectionClass;

class Router
{
    // private array $routes = [];

    private static array $routingTable = [];

    public function __construct()
    {
        $this->loadRoutingTableFromCacheFile();
    }

    public function loadRoutingTableFromCacheFile(): void
    {
        if (isset($GLOBALS['IS_CACHING_ROUTES']) && $GLOBALS['IS_CACHING_ROUTES'] == true) return;
        if (file_exists(App::getCacheRoutingTablePath())) {
            $table = json_decode(file_get_contents(App::getCacheRoutingTablePath()), true);
            foreach ($table as $method => $route) {
                foreach ($route as $uri => $handler) {
                    self::$routingTable[$method][$uri] = new RouteHandler(...$handler);
                }
            }
        } else {
            die("Don't have routing table. Please, enter command `php cli.php route:cache` to create cache routing table.");
        }
    }

    public function addRoute(string $method, string $uri, string $controller, string $action, array $middlewares)
    {
        self::$routingTable[$method][$uri] = new RouteHandler($controller, $action, $middlewares);
    }

    public static function getRoutingTable(): array
    {
        return self::$routingTable;
    }

    // public function get($uri, $callback, ...$middlewares)
    // {
    //     $this->routes["GET"][$uri] = [
    //         "callback" => $callback,
    //         "middlewares" => $middlewares
    //     ];
    // }

    // public function post($uri, $callback)
    // {
    //     $this->routes["POST"][$uri] = $callback;
    // }


    public function registerControllerRoutes($controller)
    {
        if (class_exists($controller)) {
            $this->getRoutesByAttributes($controller);
        } else {
            throw new Exception("[ERROR] Not found any proper class in `$controller.php` file.");
        }
    }

    public function getRoutesByAttributes($controller)
    {
        $reflection = new ReflectionClass($controller);
        $methods = $reflection->getMethods();
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            if (count($attributes) == 0) continue;

            $route = [];
            foreach ($attributes as $attribute) {
                $attribute = $attribute->newInstance();
                switch ($attribute->getType()) {
                    case CoreAttribute::HTTP_METHOD_TYPE:
                        $route["method"] = $attribute->getHttpMethod();
                        $route["uri"] = $attribute->getPath();
                        break;

                    case CoreAttribute::MIDDLEWARE_TYPE:
                        $route["middlewares"][] = $attribute::class;
                        break;

                    default:
                }
            }

            if (isset($route["method"]) && isset($route["uri"])) {
                $this->addRoute(
                    $attribute->getHttpMethod(),
                    $attribute->getPath(),
                    $controller,
                    $method->getName(),
                    $route["middlewares"] ?? []
                );
            }
        }
    }


    public function resolve2(Request $request)
    {
        if (empty(self::$routingTable)) throw new Exception("ERROR: Set first URI for app!");
        $method = $_SERVER["REQUEST_METHOD"];
        $path = $_SERVER["REQUEST_URI"];
        if (isset(self::$routingTable[$method][$path])) {
            /** 
             * @var RouteHandler 
             */
            $handler = self::$routingTable[$method][$path];
            $handler->run($request);
            return;
        }
        $this->notFoundView();
    }

    public function hasRoutingTable(): bool
    {
        return empty($this->routingTable);
    }

    // public function resolve()
    // {
    //     if (empty($this->routes))
    //         $request = $_REQUEST;
    //     $method = $_SERVER["REQUEST_METHOD"];
    //     $path = $_SERVER["REQUEST_URI"];
    //     $handler = $this->routes[$method][$path];
    //     if (isset($handler)) {

    //         $callback = $handler["callback"] ?? false;
    //         if (!$callback) throw new Exception("ERROR: No callback for method: $method and path: $path");

    //         $next = function ($request) use ($callback) {
    //             $this->callControllerMethod($callback);
    //         };

    //         $middlewares = $handler["middlewares"];
    //         if (is_array($middlewares)) {
    //             while ($middleware = array_pop($middlewares)) {
    //                 $next = function ($request) use ($middleware, $next) {
    //                     $mdInstance = new $middleware;
    //                     $mdInstance->handle($request, $next);
    //                 };
    //             }
    //         }
    //         $next($request);
    //         return;
    //     }

    //     $this->notFoundView();
    //     return;
    // }

    private function notFoundView()
    {
        echo "404 - NOT FOUND!";
    }

    // @result: boolean type;
    // private function callControllerMethod($callback)
    // {
    //     list($controller, $method) = explode("@", $callback);

    //     if (!class_exists($controller)) {
    //         echo "Controller $controller not found!";
    //         return false;
    //     }

    //     $conInstance = new $controller();
    //     if (method_exists($conInstance, $method)) {
    //         $conInstance->$method();
    //         return true;
    //     }

    //     echo "Method $method not found in controller $controller!";
    //     return false;
    // }
}
