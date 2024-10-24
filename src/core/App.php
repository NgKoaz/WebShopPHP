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
            $this->router->addController($controller);
        }
    }

    public function resolve()
    {
        $this->router->resolve();
    }

    // module: "<ModuleName>"
    // controllers: "["...Controller", "...Controller", ...]"
    public function declareModule($moduleInfo)
    {
        echo "CHECK FILE";
        echo file_exists("/phppractice/src/modules/admin");
        // if (isset($moduleInfo["module"]) && file_exists("/phppractice/src/modules/admin")) {
        // }
        // $module = [
        //     "Name" => "gdlgs"
        // ];
        // throw new Exception("An error occurred. Stopping execution.");
    }
}
