<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\core\Util;
use App\Middleware\Auth;
use App\modules\user\models\ApiOrderModel;
use App\services\CheckoutManager;
use App\services\LoginManager;
use App\services\MomoPayment;

#[Auth("/login")]
class OrderController extends Controller
{
    public function __construct(
        private LoginManager $loginManager,
        private CheckoutManager $checkoutManager,
        private MomoPayment $momoPayment
    ) {}

    #[HttpGet("/orders")]
    public function getOrders()
    {
        $this->view("Order");
    }

    #[HttpGet("/orders/:id")]
    public function getOrder(string $id)
    {
        $result = $this->checkoutManager->findByIdAssociate($id);
        $order = Util::getFirstInArray($result);
    }

    #[HttpPost("/order-rebuy")]
    public function rebuy(ApiOrderModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->checkoutManager->hasBill($model->billId)) {
                $model->setError("billId", "Bill id is not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->checkoutManager->rebuy($model->billId);
                return $this->redirect("/cart");
            }
        }
        return $this->view("Order");
    }

    #[HttpPost("/order-pay")]
    public function payOrder(ApiOrderModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if (!$this->checkoutManager->hasBill($model->billId)) {
                $model->setError("billId", "Bill id is not exist!");
                $isError = true;
            }
            if (!$isError) {
                $result = json_decode($this->momoPayment->createUrl($this->checkoutManager->findById($model->billId)), true);
                if (!$result || !isset($result['payUrl'])) {
                    $viewData = new ArrayList;
                    $viewData["TempMessage"] = "Momo Pay has occured an error! Please wait or choose others";
                    return $this->view("/checkout", viewData: $viewData);
                }
                return $this->redirect($result['payUrl']);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    // #[http]
}
