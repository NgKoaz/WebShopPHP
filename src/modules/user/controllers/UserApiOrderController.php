<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\Auth;
use App\modules\user\models\ApiOrderModel;
use App\services\CategoryManager;
use App\services\CheckoutManager;
use App\services\LoginManager;
use App\services\ProductManager;

#[Auth("/api/errors/unauthorize")]
class UserApiOrderController extends Controller
{
    public function __construct(
        private LoginManager $loginManager,
        private ProductManager $productManager,
        private CategoryManager $categoryManager,
        private CheckoutManager $checkoutManager,
    ) {}

    #[HttpGet("/api/orders")]
    public function getOrders()
    {
        $user = $this->loginManager->getCurrentUser();
        return $this->json($this->checkoutManager->findBillByUserId($user->id));
    }

    #[HttpPost("/api/orders/cancel")]
    public function cancelOrder(ApiOrderModel $model)
    {
        if ($model->isValid()) {

            $bill = $this->checkoutManager->findById($model->billId);
            if ($bill === null) return $this->json(["code" => 400, "message" => "Bill ID is not found!"], 400);
            $user = $this->loginManager->getCurrentUser();
            if ($user->id != $bill->user->id) return $this->json(["code" => 400, "message" => "Unauthorized!"], 400);
            if ($bill->order->status !== ORDER_PREPARING) return  $this->json(["code" => 400, "message" => "Cannot cancel this order!"], 400);
            $this->checkoutManager->cancelBill($model->billId);
            return $this->json(["code" => 200, "message" => "Cancelled!"]);
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/orders/receive")]
    public function receiveOrder(ApiOrderModel $model)
    {
        if ($model->isValid()) {
            $bill = $this->checkoutManager->findById($model->billId);
            if ($bill === null) return $this->json(["code" => 400, "message" => "Bill ID is not found!"], 400);
            $user = $this->loginManager->getCurrentUser();
            if ($user->id != $bill->user->id) return $this->json(["code" => 400, "message" => "Unauthorized!"], 400);
            if ($bill->order->status !== ORDER_SHIPPED) return  $this->json(["code" => 400, "message" => "Package is still delivering or done before!"], 400);

            $this->checkoutManager->receive($model->billId);
            return $this->json(["code" => 200, "message" => "Confirm successful!"]);
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }
}
