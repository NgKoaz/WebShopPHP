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
use App\services\SessionManager;

#[Auth("/login")]
class OrderController extends Controller
{
    public function __construct(
        private LoginManager $loginManager,
        private CheckoutManager $checkoutManager,
        private MomoPayment $momoPayment,
        private SessionManager $sessionManager
    ) {}

    #[HttpGet("/orders")]
    public function getOrders()
    {
        $viewData = new ArrayList;
        $viewData["TempMessage"] = $this->sessionManager->getFlash("TempMessage");
        $viewData["IsErrorMessage"] = $this->sessionManager->getFlash("IsErrorMessage");
        $this->view("Order", viewData: $viewData);
    }

    #[HttpGet("/orders/:id")]
    public function getOrder(string $id)
    {
        $result = $this->checkoutManager->findByIdAssociate($id);
        $order = Util::getFirstInArray($result);
    }

    #[HttpGet("/order-rebuy/:billId")]
    public function rebuy(string $billId)
    {
        $bill = $this->checkoutManager->findById($billId);
        $userId = $this->loginManager->getCurrentUserId();
        if ($bill === null || $bill->userId !== $userId) return $this->notfound();
        $this->checkoutManager->rebuy($billId);
        return $this->redirect("/cart");
    }

    #[HttpGet("/order-pay/:billId")]
    public function payOrder(string $billId)
    {
        $bill = $this->checkoutManager->findById($billId);
        $userId = $this->loginManager->getCurrentUserId();
        if ($bill === null || $bill->userId !== $userId) return $this->notfound();

        $result = json_decode($this->momoPayment->createUrl($bill), true);
        if (!$result || !isset($result['payUrl'])) {
            $viewData = new ArrayList;
            $viewData["TempMessage"] = "Momo Pay has occured an error! Please wait or choose others";
            $viewData["IsErrorMessage"] = true;
            return $this->view("Order", viewData: $viewData);
        }
        return $this->redirect($result['payUrl']);
    }

    // #[http]
}
