<?php

class HomeController extends Controller
{
    public function __construct() {}

    public static function registerRoutes($router, $module)
    {
        HomeController::$module = $module;

        $router->get("/", "HomeController@getIndex");
    }

    public function getIndex()
    {
        $this->view(ProductController::$module, "home", "index");
    }
}
