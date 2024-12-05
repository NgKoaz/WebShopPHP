<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\RoleMiddleware;
use App\modules\admin\models\BroadcastModel;
use App\modules\admin\models\DeleteSubscriptionModel;
use App\services\MailService;
use App\services\SubscriptionManager;
use App\services\UserManager;

#[RoleMiddleware("/api/errors/roles", "Admin")]
class ApiSubscriptionController extends Controller
{
    public function __construct(
        private UserManager $userManager,
        private SubscriptionManager $subscriptionManager,
        private MailService $mailService,
    ) {}

    #[HttpGet("/api/admin/subscriptions")]
    public function getRoles()
    {
        return $this->json($this->subscriptionManager->findAll());
    }

    #[HttpPost("/api/admin/subscriptions/delete")]
    public function deleteSubscription(DeleteSubscriptionModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->subscriptionManager->hasEmail($model->email)) {
                $model->setError("email", "Email doesn't exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->subscriptionManager->deleteByEmail($model->email);
                return $this->json(["code" => 200, "message" => "Delete successful!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/admin/subscriptions/broadcast")]
    public function broadcast(BroadcastModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$isError) {
                $subscriptions = $this->subscriptionManager->findAllVerified();
                $emails = array_map(fn($sub) => $sub->email, $subscriptions);

                $this->mailService->send($emails, "New Product", $model->content);
                return $this->json(["code" => 200, "message" => "Broadcast successful!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }
}
