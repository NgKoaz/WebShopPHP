<?php

class CategoryController extends Controller
{
    public static function registerRoutes($router, $module)
    {
        CategoryController::$module = $module;
        $router->get("/category", "CategoryController@getIndex");
    }

    public function getIndex()
    {
        $this->view(CategoryController::$module, "category", "index");
    }
}
