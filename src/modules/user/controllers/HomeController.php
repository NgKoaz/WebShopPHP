<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\middleware\Auth;
use App\services\LoginManager;
use App\validator\LoginValidator;


class HomeController extends Controller
{
    public function __construct(private LoginManager $loginManager) {}

    #[HttpGet("/")]
    public function getIndex()
    {
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        $this->view("index", viewData: $viewData);
    }

    // #[HttpPost("/")]
    // public function postIndex()
    // {
    //     $loginValidator = new LoginValidator;
    //     if (!$loginValidator->validate([])) {
    //         var_dump($loginValidator->getErrors());
    //         return;
    //     }
    //     echo "POST HEHE";

    //     $this->view("index");
    // }
}
