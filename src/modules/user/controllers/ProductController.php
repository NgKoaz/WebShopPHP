<?php

namespace App\modules\user\controllers;

use App\core\Controller;

class ProductController extends Controller
{
    public static function registerRoutes($router, $module)
    {
        ProductController::$module = $module;

        $router->get("/product", ProductController::class . "@getIndex");
    }

    public function getIndex()
    {
        $this->view(self::$module, "product", "index");
    }
}
