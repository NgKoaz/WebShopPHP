<?php

namespace App\core;

abstract class Controller
{
    static $module = "";

    abstract public static function registerRoutes($router, $module);

    public function view($module, $controller, $view = "index", $viewData = [])
    {
        require_once "/phppractice/src/modules/$module/views/$controller/$view.php";
    }
}
