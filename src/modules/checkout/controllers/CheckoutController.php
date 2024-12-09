<?php

namespace App\modules\checkout\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\Auth;
use App\services\CartManager;
use App\services\CheckoutManager;
use App\services\LoginManager;
use App\services\MomoPayment;
use App\services\SessionManager;
use Google\Service\FirebaseRules\Arg;

#[Auth("/login")]
class CheckoutController extends Controller
{
    public function __construct(
        private MomoPayment $momoPayment,
        private LoginManager $loginManager,
        private CartManager $cartManager,
        private CheckoutManager $checkoutManager,
        private SessionManager $sessionManager,
    ) {}

    #[HttpGet("/checkout")]
    public function getCheckout()
    {
        return $this->view("Checkout");
    }

    #[HttpPost("/checkout")]
    public function postCheckout(?string $method = null)
    {
        // Checking begin
        if ($method === null) {
            $viewData = new ArrayList;
            $viewData["TempMessage"] = "Select payment method";
            $viewData["IsErrorMessage"] = "true";
            return $this->view("Checkout", viewData: $viewData);
        }
        $user = $this->loginManager->getCurrentUser();
        if (!$user->isVerifiedEmail) {
            $viewData = new ArrayList;
            $viewData["TempMessage"] = "Please, verify your email!<br>(Profile Icon -> Settings).";
            $viewData["IsErrorMessage"] = "true";
            return $this->view("Checkout", viewData: $viewData);
        }
        if (!isset($user->address)) {
            $viewData = new ArrayList;
            $viewData["TempMessage"] = "Please, change your true address!<br>(Profile Icon -> Settings).";
            $viewData["IsErrorMessage"] = "true";
            return $this->view("Checkout", viewData: $viewData);
        }
        // Checking end


        $bill = $this->checkoutManager->createOrderForCurrentUser();
        if ($bill === null) {
            $viewData["TempMessage"] = "Cannot create bill, please re-create!";
            $viewData["IsErrorMessage"] = "true";
            return $this->redirect("/cart");
        } else {
            $result = json_decode($this->momoPayment->createUrl($bill), true);
            if (!$result || !isset($result['payUrl'])) {
                $this->checkoutManager->deleteByObject($bill);
                $viewData = new ArrayList;
                $viewData["TempMessage"] = "Momo Pay has occured an error! Please wait or choose others";
                return $this->view("Checkout", viewData: $viewData);
            }
            $this->cartManager->clear();
            return $this->redirect($result['payUrl']);
        }
    }

    #[HttpGet("/checkout/momo/callback")]
    public function callbackMomo(string $resultCode, string $message, string $requestId)
    {
        if (+$resultCode !== 0) {
            $this->sessionManager->setFlash("IsErrorMessage", "true");
            $this->sessionManager->setFlash("TempMessage", "Failed transaction!");
        } else {
            $this->checkoutManager->onPaidBill($requestId, ONLINE_METHOD, MOMO_PSP);
            $this->sessionManager->setFlash("TempMessage", "Successful transaction!");
        }
        return $this->redirect("/orders");
    }
}
