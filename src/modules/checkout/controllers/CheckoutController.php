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

#[Auth("/login")]
class CheckoutController extends Controller
{
    public function __construct(
        private MomoPayment $momoPayment,
        private LoginManager $loginManager,
        private CartManager $cartManager,
        private CheckoutManager $checkoutManager,
    ) {}

    #[HttpGet("/checkout")]
    public function getCheckout()
    {
        return $this->view("Checkout");
    }

    #[HttpPost("/checkout")]
    public function postCheckout()
    {
        $bill = $this->checkoutManager->createOrderForCurrentUser();
        if ($bill === null) {
            return $this->redirect("/cart");
        } else {
            $result = json_decode($this->momoPayment->createUrl($bill), true);
            if (!$result || !isset($result['payUrl'])) {
                $this->checkoutManager->deleteByObject($bill);
                $viewData = new ArrayList;
                $viewData["TempMessage"] = "Momo Pay has occured an error! Please wait or choose others";
                return $this->view("checkout", viewData: $viewData);
            }
            $this->cartManager->clear();
            return $this->redirect($result['payUrl']);
        }
    }

    #[HttpGet("/checkout/momo/callback")]
    public function callbackMomo(string $resultCode, string $message, string $requestId)
    {
        // if (+$resultCode != 0) {
        //     $this->checkoutManager->deleteById($requestId);
        //     return $this->redirect("/order")
        // }

        // $file = ROOT_DIR . "/src/cache/global/log/momo/callback.txt";
        // $fileStream = fopen($file, 'a');
        // if ($fileStream) {
        //     $data = print_r($_GET, true);
        //     fwrite($fileStream, $data);
        //     fclose($fileStream);
        // }

        if (+$resultCode !== 0) {
        } else {
            $this->checkoutManager->onPaidBill($requestId, BILL_PAID, ONLINE_METHOD, MOMO_PSP);
        }
        return $this->redirect("/orders");
    }
}
