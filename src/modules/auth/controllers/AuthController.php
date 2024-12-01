<?php

namespace App\modules\auth\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\Anonymous;
use App\modules\auth\models\RegisterModel;
use App\services\LoginManager;
use App\services\SessionManager;
use App\services\UserManager;
use Google\Client;
use Google\Service\Oauth2;

#[Anonymous]
class AuthController extends Controller
{
    public function __construct(
        private UserManager $userManager,
        private SessionManager $sessionManager,
        private LoginManager $loginManager,
        private Client $client
    ) {}

    #[HttpGet("/login")]
    public function getLogin()
    {
        $viewData = new ArrayList;
        $viewData["GoogleAuthURL"] = $this->client->createAuthUrl();
        $this->view("Login", viewData: $viewData);
    }

    #[HttpGet("/register")]
    public function getRegister()
    {
        $viewData = new ArrayList;
        $viewData["GoogleAuthURL"] = $this->client->createAuthUrl();
        $token = $this->sessionManager->getEntry(SessionManager::$GOOGLE_AUTH);
        if (count($token) > 0) {
            $this->client->setAccessToken($token["ACCESS_TOKEN"]);
            if ($this->client->isAccessTokenExpired())
                $this->client->refreshToken($token["REFRESH_TOKEN"]);

            $oauth2 = new Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();
            $viewData["email"] = $userInfo->getEmail();
            $viewData["lastname"] = $userInfo->getFamilyName();
            $viewData["firstname"] = $userInfo->getGivenName();
        }

        $this->view("Register", viewData: $viewData);
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
        $this->userManager->register(
            $model->firstname,
            $model->lastname,
            $model->username,
            $model->email,
            $model->phone,
            $model->password,
            []
        );
        return $isValid;
    }
}
