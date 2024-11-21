<?php

namespace App\modules\auth\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Entities\User;
use App\Middleware\Anonymous;
use App\modules\auth\models\LoginModel;
use App\modules\auth\models\RegisterModel;
use App\services\LoginManager;
use App\services\SessionManager;
use App\services\UserManager;

#[Anonymous]
class AuthController extends Controller
{
    public function __construct(
        private UserManager $userManager,
        private SessionManager $sessionManager,
        private LoginManager $loginManager
    ) {}

    #[HttpGet("/login")]
    public function getLogin()
    {
        $viewData = new ArrayList;
        $viewData["TempMessage"] = $this->sessionManager->getTempMessage();
        $this->view("Login", viewData: $viewData);
    }

    #[HttpGet("/register")]
    public function getRegister()
    {
        $this->view("Register");
    }

    #[HttpPost("/register")]
    public function postRegister(RegisterModel $model)
    {
        if ($model->isValid()) {
            $this->registerUser($model);
            $this->sessionManager->setTempMessage("Register successfully!");
            return $this->redirect("/login");
        }
        return $this->view("Register", $model);
    }

    private function registerUser(RegisterModel $model): bool
    {
        $isValid =
            $this->userManager->hasEmail($model->email) &&
            $this->userManager->hasUsername($model->username) &&
            $this->userManager->hasEmail($model->username);
        $this->userManager->register($model->firstName, $model->lastName, $model->username, $model->email, $model->phone, $model->password);
        return $isValid;
    }
}
