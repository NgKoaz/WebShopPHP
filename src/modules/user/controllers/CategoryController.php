<?php

namespace App\modules\user\controllers;

use App\core\Controller;

class CategoryController extends Controller
{
    public static function registerRoutes($router, $module)
    {
        CategoryController::$module = $module;
        $router->get("/category", "CategoryController@getIndex");
    }

    public function getIndex()
    {
        $this->view(self::$module, "category", "index");
    }
}
