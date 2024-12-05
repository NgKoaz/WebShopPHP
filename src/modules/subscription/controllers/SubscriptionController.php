<?php

namespace App\modules\subscription\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\subscription\models\PostSubscriptionModel;
use App\services\JWTService;
use App\services\MailService;
use App\services\SubscriptionManager;
use DateTime;


class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionManager $subscriptionManager,
        private MailService $mailService,
        private JWTService $jwtService
    ) {}

    #[HttpPost("/api/subscribe")]
    public function postSubscription(PostSubscriptionModel $model)
    {
        if ($model->isValid()) {
            $subscription = $this->subscriptionManager->findByEmail($model->email);
            if ($subscription !== null) {
                if ($subscription->isVerified) return $this->json(["code" => 200, "message" => "You have already subscribed!"]);

                $currentTime = new DateTime();
                if ($subscription->sentVerifyEmailAt < $currentTime->modify('-5 minutes')) {
                    $this->subscriptionManager->renewSendingTime($model->email);
                    $this->sendTokenToEmail($model->email);
                    return $this->json(["code" => 200, "message" => "Please, confirm at your email!"]);
                } else {
                    return $this->json(["code" => 425, "message" => "Please, you can resend in "], 425);
                }
            } else {
                $this->subscriptionManager->subscribe($model->email);
                $this->sendTokenToEmail($model->email);
                return $this->json(["code" => 200, "message" => "Please, confirm at your email!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    private function sendTokenToEmail(string $email)
    {
        $token = $this->jwtService->generateSubscribeConfirmationToken($email);
        $link = HTTP_HOST . "/api/subscribe/confirm?token=$token";
        $this->mailService->send([$email], "Confirm your subscription!", "Click into this link to verify that you prefer to get information from us: <a href=" . $link . ">Click here</a>");
    }

    #[HttpGet("/api/subscribe/confirm")]
    public function confirmSubscription(?string $token = null)
    {
        if ($token === null) return $this->redirect("/");
        $payload = $this->jwtService->decode($token);
        if ($payload === null) return $this->redirect("/");

        $viewData = new ArrayList;
        if (new DateTime($payload["expiredAt"]) > new DateTime) {
            $this->subscriptionManager->verify($payload["email"]);
            $viewData["title"] = "Thank you!";
            $viewData["message"] = "Thank you for your subscription! You will be the first one who will see our new arrival products.";
            return $this->view("Subscription", viewData: $viewData);
        } else {
            $viewData["title"] = "Please, try again!";
            $viewData["message"] = "We thank for your interesting but the confirmation token has been expired. You can re-submit the form to get the new one at your email!";
            return $this->view("Subscription", viewData: $viewData);
        }
    }
}
