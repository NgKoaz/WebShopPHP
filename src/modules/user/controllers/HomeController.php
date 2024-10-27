<?php

namespace App\modules\user\controllers;

use App\core\Controller;
use App\middleware\AuthMiddleware;
use App\validator\LoginValidator;


class HomeController extends Controller
{
    public function __construct() {}

    public static function registerRoutes($router, $module)
    {
        HomeController::$module = $module;

        $router->get("/", "HomeController@getIndex", AuthMiddleware::class);
    }

    public function getIndex()
    {
        $loginValidator = new LoginValidator;
        if (!$loginValidator->validate([])) {
            var_dump($loginValidator->getErrors());
            return;
        }

        $this->view(ProductController::$module, "home", "index");
    }
}
