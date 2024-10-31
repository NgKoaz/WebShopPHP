<?php

namespace App\core;

use Exception;

class App
{
    private static string $ROOT_DIRECTORY = __DIR__ . "/../..";

    private static string $CACHE_ROUTING_TABLE_PATH = __DIR__ . "/../cache/local/RoutingTable.cache";

    private Request $request;

    private Router $router;

    public function __construct()
    {
        $this->router = new Router;
        $this->request = new Request($_REQUEST);
    }

    public static function getRootDirectory()
    {
        return self::$ROOT_DIRECTORY;
    }

    public static function getLayoutDirectory(): string
    {
        return self::$ROOT_DIRECTORY . "/src/layout";
    }

    public static function getCacheRoutingTablePath()
    {
        return self::$CACHE_ROUTING_TABLE_PATH;
    }

    public function run()
    {
        try {
            if (isset($GLOBALS['IS_CACHING_ROUTES']) && $GLOBALS['IS_CACHING_ROUTES'] == true) return;
            $this->router->resolve2($this->request);
        } catch (Exception $e) {
        }
    }

    public function importModule(Module $module)
    {
        if (!(isset($GLOBALS['IS_CACHING_ROUTES']) && $GLOBALS['IS_CACHING_ROUTES'] == true)) return;
        $controllers = $module->getControllers();
        if (isset($controllers)) {
            foreach ($controllers as $controller) {
                $this->router->registerControllerRoutes($controller);
            }
            return;
        }

        $this->notFoundView(); // throw instead
        return;
    }

    public function notFoundView()
    {
        echo "404 - Not Found!";
    }
}
