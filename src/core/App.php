<?php

namespace App\core;

use Exception;

class App
{
    private static string $ROOT_DIRECTORY = __DIR__ . "/../..";

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

    public function run()
    {
        try {
            $this->router->resolve2($this->request);
        } catch (Exception $e) {
        }
    }

    // module: "<ModuleName>"
    // controllers: "["...Controller", "...Controller", ...]"
    // public function declareModule($module, ...$controllers)
    // {
    //     if (!isset($controllers)) return;
    //     foreach ($controllers as $controller) {
    //         $this->router->addControllerInModule($module, $controller);
    //     }
    // }

    public function importModule(Module $module)
    {
        $controllers = $module->getControllers();
        if (isset($controllers)) {
            foreach ($controllers as $controller) {
                $this->router->registerControllerRoutes($controller);
            }
            return;
        }

        $this->notFoundView();
        return;
    }

    public function notFoundView()
    {
        echo "404 - Not Found!";
    }
}
