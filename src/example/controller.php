<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\middleware\AuthMiddleware;
use App\modules\user\models\PostModel;
use App\services\TestService;
use App\services\UserManager;
use App\validator\LoginValidator;


class HomeController extends Controller
{
    public function __construct() {}

    #[AuthMiddleware]
    #[HttpGet("/")]
    public function getIndex()
    {
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

    #[HttpGet("/:id/:name")]
    public function getDetail(string $id, string $name, PostModel $postModel)
    {
        echo "HIOT HEllo getDetail here $id <br>";
        var_dump($_GET);
    }
}
