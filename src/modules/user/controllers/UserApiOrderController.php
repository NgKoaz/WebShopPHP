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
            $isError = false;

            if (!$this->checkoutManager->hasBill($model->billId)) {
                $model->setError("billId", "Bill id is not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->checkoutManager->cancelBill($model->billId);
                return $this->json([]);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }
}
