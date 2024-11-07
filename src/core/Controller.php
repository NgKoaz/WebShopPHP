<?php

namespace App\core;

use ReflectionClass;

abstract class Controller
{
    private array $stylesheets = [];
    private array $scripts = [];
    private string $controllerPath = "";

    private function getControllerPath(): string
    {
        if (strlen($this->controllerPath) > 0)
            return $this->controllerPath;

        $reflection = new ReflectionClass($this);
        $controller = $this->reduceControllerName($reflection->getShortName());
        $module = explode("\\", $reflection->getNamespaceName())[2];

        return $this->controllerPath = "/src/modules/$module/views/$controller/";
    }

    public function reduceControllerName(string $longName)
    {
        $lowername = strtolower($longName);
        if (str_ends_with($lowername, 'controller')) {
            return substr($lowername, 0, -10);
        }
        return $lowername;
    }

    protected function addScript(string $filename): Controller
    {
        $this->scripts[] = $filename;
        return $this;
    }

    protected function addStylesheet(string $filename): Controller
    {
        $this->stylesheets[] = $filename;
        return $this;
    }

    protected function loadScripts()
    {
        $scripts = $this->scripts;
        foreach ($scripts as $script) {
            $path = $this->getControllerPath() . "$script";
            echo "<script src=\"" . $path . "\" defer></script>";
        }
    }

    protected function loadStylesheets()
    {
        $stylesheets = $this->stylesheets;
        foreach ($stylesheets as $stylesheet) {
            $path = $this->getControllerPath() . "$stylesheet";
            echo "<link rel=\"stylesheet\" href=\"" . $path . "\">";
        }
    }

    protected function view($view = "index", $viewData = [])
    {
        require_once App::getRootDirectory() . $this->getControllerPath() . "$view.php";
    }
}
