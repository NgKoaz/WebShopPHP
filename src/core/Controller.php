<?php

abstract class Controller
{
    abstract public static function registerRoutes($router);

    public function view($viewPath, $viewData = [])
    {
        $viewPath = trim($viewPath, "/");
        require_once "/phppractice/src/views/$viewPath.php";
    }
}
