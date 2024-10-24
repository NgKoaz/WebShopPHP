<?php

require_once "Router.php";


class App
{
    private $router;

    public function __construct()
    {
        $this->router = new Router;
    }

    public function resolve()
    {
        $this->router->resolve();
    }

    // module: "<ModuleName>"
    // controllers: "["...Controller", "...Controller", ...]"
    public function declareModule($module, ...$controllers)
    {
        if (!isset($controllers)) return;
        foreach ($controllers as $controller) {
            $this->router->addControllerInModule($module, $controller);
        }
    }






    // public function addControllers(...$controllers)
    // {
    //     foreach ($controllers as $controller) {
    //         $this->router->addController($controller);
    //     }
    // }
}
