<?php

namespace App\core;

use ReflectionClass;

abstract class Controller
{
    public function reduceControllerName(string $longName)
    {
        $lowername = strtolower($longName);
        if (str_ends_with($lowername, 'controller')) {
            return substr($lowername, 0, -10);
        }
        return $lowername;
    }

    public function view($view = "index", $viewData = [])
    {
        $reflection = new ReflectionClass($this);
        $controller = $this->reduceControllerName($reflection->getShortName());
        $module = explode("\\", $reflection->getNamespaceName())[2];

        require_once App::getRootDirectory() . "/src/modules/$module/views/$controller/$view.php";
    }

    public function getNamespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }
}
