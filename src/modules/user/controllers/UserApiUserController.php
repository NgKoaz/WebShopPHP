<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\Auth;
use App\modules\user\models\BasicInfoModel;
use App\modules\user\models\ChangeEmailModel;
use App\modules\user\models\ChangePasswordModel;
use App\services\JWTService;
use App\services\LoginManager;
use App\services\MailService;
use App\services\SessionManager;
use App\services\UserManager;
use DateTime;

#[Auth("/api/errors/unauthorize")]
class UserApiUserController extends Controller
{
    public function __construct(
        private LoginManager $loginManager,
        private UserManager $userManager,
        private MailService $mailService,
        private JWTService $jwtService,
        private SessionManager $sessionManager,
    ) {}

    #[HttpGet("/api/user")]
    public function getCurrentUser()
    {
        $user = $this->loginManager->getCurrentUser();
        return $this->json(["code" => 200, "data" => $user]);
    }

    #[HttpPost("/api/user/change-basic-info")]
    public function changeBasicInfo(BasicInfoModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            $user = $this->loginManager->getCurrentUser();
            if ($user === null) {
                $model->setError("others", "User is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->changeBasicInfo($user->id, $model->firstname, $model->lastname, $model->address);
                return $this->json(["code" => 200, "message" => "Changed!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/user/change-password")]
    public function changePassword(ChangePasswordModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            $user = $this->loginManager->getCurrentUser();
            if ($user === null) {
                $model->setError("others", "User is not found!");
                $isError = true;
            }

            if ($this->loginManager->login($user->email, $model->currentPassword, true) === null) {
                $model->setError("currentPassword", "Current Password is wrong!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->changePassword($user->email, $model->newPassword);
                return $this->json(["code" => 200, "message" => "Changed!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/user/change-email")]
    public function changeEmail(ChangeEmailModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            $user = $this->loginManager->getCurrentUser();
            if ($this->userManager->hasEmailExceptId($user->id, $model->email)) {
                $model->setError("email", "This Email does exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->changeEmail($user->id, $model->email);
                return $this->json(["code" => 200, "message" => "Email has been changed!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/user/auth-email")]
    public function authEmail()
    {
        $user = $this->loginManager->getCurrentUser();
        if ($user->isVerifiedEmail) return $this->json(["code" => 200, "message" => "Your email was verified!"]);
        if ($this->sendTokenToEmail($user->email)) {
            return $this->json(["code" => 200, "message" => "Please, check your email to confirm!"]);
        } else {
            return $this->json(["code" => 500, "message" => "Please wait a second then re-send again! (Server error)"]);
        }
    }

    private function sendTokenToEmail(string $email): bool
    {
        $token = $this->jwtService->generateEmailAuthenticationToken($email);
        $link = HTTP_HOST . "/api/user/auth-email?token=$token&email=$email";
        return $this->mailService->send([$email], "Confirm your subscription!", "Click into this link to verify that you prefer to get information from us: <a href=" . $link . ">Click here</a>");
    }

    #[HttpGet("/api/user/auth-email")]
    public function handleAuthEmailCallback(string $token, string $email)
    {
        if ($token === null) return $this->redirect("/");
        $payload = $this->jwtService->decode($token, $email);
        if ($payload === null) return $this->redirect("/");

        if (new DateTime($payload["expiredAt"]) > new DateTime) {
            if ($payload["email"] === $email) {
                $this->userManager->authEmail($email);
                $this->sessionManager->setFlash("TempMessage", "Auth successful!");
            } else {
                $this->sessionManager->setFlash("TempMessage", "Wrong token!");
                $this->sessionManager->setFlash("IsErrorMessage", "true");
            }
        } else {
            $this->sessionManager->setFlash("TempMessage", "Token expired!");
            $this->sessionManager->setFlash("IsErrorMessage", "true");
        }
        return $this->redirect("/");
    }
}
