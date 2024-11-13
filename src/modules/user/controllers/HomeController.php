<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\middleware\AuthMiddleware;
use App\services\LoginManager;
use App\validator\LoginValidator;


class HomeController extends Controller
{
    public function __construct(private LoginManager $loginManager) {}

    #[HttpGet("/")]
    public function getIndex()
    {
        var_dump($this->loginManager->getCurrentUser());
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
