<?php

namespace App\core;

use App\core\Attributes\CoreAttribute;
use Error;
use Exception;
use ReflectionClass;

class Router
{
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
                foreach ($route as $patternUri => $handler) {
                    self::$routingTable[$method][$patternUri] = new RouteHandler(...$handler);
                }
            }
        } else {
            die("Don't have routing table. Please, enter command `php cli.php route:cache` to create cache routing table.");
        }
    }

    public function addRoute(string $method, string $patternUri, string $controller, string $action, array $middlewares)
    {
        self::$routingTable[$method][$patternUri] = new RouteHandler($controller, $action, $middlewares);
    }

    public static function getRoutingTable(): array
    {
        return self::$routingTable;
    }

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
        $route = [];
        $reflection = new ReflectionClass($controller);

        // Get from class
        $classAttrs = $reflection->getAttributes();
        foreach ($classAttrs as $attr) {
            $attrIns = $attr->newInstance();
            switch ($attrIns->getType()) {
                case CoreAttribute::MIDDLEWARE_TYPE:
                    $route["middlewares"][] = $attrIns::class;
                    break;

                default:
            }
        }

        // Get from method
        $methods = $reflection->getMethods();
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            if (count($attributes) == 0) continue;

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

    public function resolve2(Request $request): bool
    {
        if (empty(self::$routingTable)) return false;
        $method = $_SERVER["REQUEST_METHOD"];
        $uri = $_SERVER["REQUEST_URI"];
        $uri = explode("?", $uri)[0];

        // 1. Determine route by method first.
        $methodRoutes = self::$routingTable[$method];

        // 2. Iterate routes to find a valid route.
        foreach ($methodRoutes as $patternUri => $routeHandler) {
            $paramNames = $this->getParamNamesInRoute($patternUri);
            $regexUri = $this->exchangeRegexUri($patternUri);

            if (preg_match($regexUri, $uri, $matches)) {
                array_shift($matches);
                $assocArray = array_combine($paramNames, $matches);

                $routeHandler->run($request, $assocArray);
                return true;
            }
        }

        return false;
    }

    public function hasRoutingTable(): bool
    {
        return empty($this->routingTable);
    }

    private function getParamNamesInRoute($uri): array
    {
        preg_match_all('/:([A-z]\w*)/', $uri, $matches);
        return $matches[1];
    }

    private function exchangeRegexUri($uri): string
    {
        $pattern = preg_replace('/:([A-z]\w*)/', '(\w+)', $uri);
        $regex = "@^" . $pattern . "$@";
        return $regex;
    }
}
