<?php

namespace App\modules\auth\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\services\LoginManager;
use App\services\SessionManager;
use App\services\UserManager;
use Google\Client;
use Google\Service\Oauth2;

class ExternalLoginController extends Controller
{
    public function __construct(
        private SessionManager $sessionManager,
        private UserManager $userManager,
        private LoginManager $loginManager,
        private Client $client,
    ) {}

    #[HttpGet("/api/auth/google")]
    public function handleGoogleCallback(string $code = "")
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        if (!isset($token['error'])) {
            $saveData = [
                "ACCESS_TOKEN" => $token["access_token"],
                "REFRESH_TOKEN" => $token["refresh_token"]
            ];
            $this->sessionManager->setEntry(SessionManager::$GOOGLE_AUTH, $saveData);

            $this->client->setAccessToken($token["access_token"]);
            $oauth2 = new Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            $user = $this->userManager->findByEmail($userInfo->getEmail());
            if ($user === null) {
                return $this->redirect("/register");
            } else {
                $this->loginManager->externalLogin($userInfo->getEmail());
                return $this->redirect("/");
            }
            var_dump($userInfo);
        }
        return $this->content("Google OAuth Error!");
    }
}
