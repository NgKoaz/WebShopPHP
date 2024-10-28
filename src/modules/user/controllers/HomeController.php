<?php

namespace App\modules\user\controllers;

use App\core\attributes\HttpGet;
use App\core\attributes\HttpPost;
use App\core\Controller;
use App\middleware\AuthMiddleware;
use App\validator\LoginValidator;


class HomeController extends Controller
{
    public function __construct() {}

    public static function registerRoutes($router, $module)
    {
        HomeController::$module = $module;
        // $router->get("/", HomeController::class . "@getIndex", AuthMiddleware::class);
    }

    #[AuthMiddleware]
    #[HttpGet("/")]
    public function getIndex()
    {
        $loginValidator = new LoginValidator;
        if (!$loginValidator->validate([])) {
            var_dump($loginValidator->getErrors());
            return;
        }
        echo "GET HEHE";
        $this->view("index");
    }

    #[HttpPost("/")]
    public function postIndex()
    {
        $loginValidator = new LoginValidator;
        if (!$loginValidator->validate([])) {
            var_dump($loginValidator->getErrors());
            return;
        }
        echo "POST HEHE";

        $this->view("index");
    }
}
