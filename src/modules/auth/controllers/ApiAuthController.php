<?php

namespace App\modules\auth\controllers;

use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\Anonymous;
use App\modules\auth\models\LoginModel;
use App\modules\auth\models\LogoutModel;
use App\modules\auth\models\RegisterModel;
use App\services\LoginManager;
use App\services\SessionManager;
use App\services\UserManager;

// #[Anonymous]
class ApiAuthController extends Controller
{
    public function __construct(
        private UserManager $userManager,
        private SessionManager $sessionManager,
        private LoginManager $loginManager
    ) {}

    #[HttpPost("/api/login")]
    public function postLogin(LoginModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if ($this->userManager->findByUsername($model->username) === null) {
                $model->setError("username", "Username is not exist!");
                $isError = true;
            }

            $user = $this->loginManager->login($model->username, $model->password, isset($model->rememberMe));
            if ($user === null) {
                $model->setError("password", "Password is wrong!");
                $isError = true;
            }

            if (!$isError) {
                return $this->json($user);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/register")]
    public function postRegister(RegisterModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if ($this->userManager->findByUsername($model->username) !== null) {
                $model->setError("username", "Username is already exist!");
                $isError = true;
            }
            if ($this->userManager->findByEmail($model->username) !== null) {
                $model->setError("username", "Username is already exist!");
                $isError = true;
            }
            if ($this->userManager->findByEmail($model->email) !== null) {
                $model->setError("email", "Email is already exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->register(
                    $model->firstname,
                    $model->lastname,
                    $model->username,
                    $model->email,
                    $model->phone,
                    $model->password
                );
                $user = $this->userManager->findByUsername($model->username);
                return $this->json($user);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/logout")]
    public function postLogout(LogoutModel $model)
    {
        if ($model->isValid()) {
            $this->loginManager->logout();
            return $this->json(["message" => "Logout successfully!"]);
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }
}
