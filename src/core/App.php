<?php

require_once "Router.php";

require_once "/phppractice/src/controllers/HomeController.php";

class App
{
    private $router;

    public function __construct()
    {
        $this->router = new Router;
    }

    public function addControllers(...$controllers)
    {
        foreach ($controllers as $controller) {
            $this->router->declareRoutesFromController($controller);
        }
    }

    public function resolve()
    {
        $this->router->resolve();
    }
}
