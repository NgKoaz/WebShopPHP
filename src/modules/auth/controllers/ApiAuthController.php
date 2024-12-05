<?php

namespace App\modules\auth\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\auth\models\ForgotPasswordModel;
use App\modules\auth\models\LoginModel;
use App\modules\auth\models\LogoutModel;
use App\modules\auth\models\RegisterModel;
use App\modules\auth\models\ResetPasswordModel;
use App\services\JWTService;
use App\services\LoginManager;
use App\services\MailService;
use App\services\SessionManager;
use App\services\UserManager;
use DateTime;
use Google\Client;
use Google\Service\Oauth2;
use Google_Client;

// #[Anonymous]
class ApiAuthController extends Controller
{
    public function __construct(
        private UserManager $userManager,
        private SessionManager $sessionManager,
        private LoginManager $loginManager,
        private Client $client,
        private JWTService $jwtService,
        private MailService $mailService
    ) {}

    #[HttpPost("/api/login")]
    public function postLogin(LoginModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (
                $this->userManager->findByUsername($model->username) === null
                && $this->userManager->findByEmail($model->username) === null
            ) {
                $model->setError("username", "Username or Email does not exist!");
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
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
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
            if ($this->userManager->findByEmail(trim($model->username)) !== null) {
                $model->setError("username", "Username is already exist!");
                $isError = true;
            }

            if ($this->userManager->findByEmail($model->email) !== null) {
                $model->setError("email", "Email is already exist!");
                $isError = true;
            }

            $isVeriedEmail = false;

            $token = $this->sessionManager->getEntry(SessionManager::$GOOGLE_AUTH);
            if (count($token) > 0) {
                $this->client->setAccessToken($token["ACCESS_TOKEN"]);
                if ($this->client->isAccessTokenExpired())
                    $this->client->refreshToken($token["REFRESH_TOKEN"]);

                $oauth2 = new Oauth2($this->client);
                $userInfo = $oauth2->userinfo->get();
                $isVeriedEmail = ($userInfo->getEmail() === $model->email);
            }

            if (!$isError) {
                $this->userManager->register(
                    $model->firstname,
                    $model->lastname,
                    $model->username,
                    $model->email,
                    $model->phone,
                    $model->password,
                    [],
                    $isVeriedEmail
                );
                $user = $this->userManager->findByUsername($model->username);
                return $this->json($user);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/logout")]
    public function postLogout(LogoutModel $model)
    {
        if ($model->isValid()) {
            $this->loginManager->logout();
            return $this->json(["message" => "Logout successfully!"]);
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/forgot-password")]
    public function postForgotPassword(ForgotPasswordModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if ($this->userManager->findByEmail($model->email) === null) {
                $model->setError("email", "The email does not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->sendTokenToEmail($model->email);
                return $this->json(["code" => 200, "message" => "Please, check your email to reset your password!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/reset-password")]
    public function postResetPassword(ResetPasswordModel $model)
    {
        if ($model->isValid()) {
            $user = $this->userManager->findByEmail($model->email);
            if ($user === null) {
                // process error
            }

            $payload = $this->jwtService->decode($model->token, $user->passwordHash);
            if ($payload === null) return $this->redirect("/forgot-password");

            $this->userManager->changePassword($model->email, $model->password);
            return $this->json(["code" => 200, "message" => "Password changed!"]);
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    private function sendTokenToEmail(string $email)
    {
        $user = $this->userManager->findByEmail($email);
        $token = $this->jwtService->generateForgotPasswordToken($email, $user->passwordHash);
        $link = HTTP_HOST . "/reset-password?token=$token&email=$email";
        $this->mailService->send([$email], "Reset password!", "Reset your password at <a href=" . $link . ">here</a>.");
    }
}
