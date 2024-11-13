<?php

namespace App\modules\auth\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\auth\models\RegisterModel;

class AuthController extends Controller
{
    public function __construct() {}

    #[HttpGet("/login")]
    public function getLogin()
    {
        $this->view("login");
    }

    #[HttpGet("/register")]
    public function getRegister()
    {
        $this->view("Register");
    }

    #[HttpPost("/register")]
    public function postRegister(RegisterModel $model)
    {
        $model->isValid();
        // if ($model->isValid()) {
        //     // $model->getErrors();
        //     return $this->redirect("/");
        // }

        return $this->view("Register", $model);
    }
}
