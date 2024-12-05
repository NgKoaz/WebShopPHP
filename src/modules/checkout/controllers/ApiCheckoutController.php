<?php

// namespace App\modules\checkout\controllers;

// use App\core\ArrayList;
// use App\core\Attributes\Http\HttpGet;
// use App\core\Controller;
// use App\Interface\IPaymentMethod;
// use App\Middleware\Auth;


// #[Auth("/api/errors/unauthorize")]
// class ApiCheckoutController extends Controller
// {
//     public function __construct(private IPaymentMethod $iPaymentMethod) {}

//     #[HttpGet("/api/checkout/momo/create")]
//     public function checkoutMomo()
//     {
//         $result = json_decode($this->iPaymentMethod->createUrl(10000), true);
//         if (!$result || !isset($result['payUrl'])) return "Momo error!";
//         return $this->redirect($result['payUrl']);
//     }

//     #[HttpGet("/api/checkout/momo/callback")]
//     public function callbackMomo(string $resultCode, string $message)
//     {
//         $file = ROOT_DIR . "/src/cache/global/log/momo/callback.txt";
//         $fileStream = fopen($file, 'a');
//         if ($fileStream) {
//             $data = print_r($_GET, true);
//             fwrite($fileStream, $data);
//             fclose($fileStream);
//         }

//         if (+$resultCode != 0) return $this->content($message);
//         return $this->redirect("/");
//     }

//     #[HttpGet("/api/checkout/momo/ipn")]
//     public function getIpnMomo()
//     {
//         $file = ROOT_DIR . "/src/cache/global/log/momo/ipn.txt";
//         $fileStream = fopen($file, 'a');
//         if ($fileStream) {
//             $data = print_r($_GET, true);
//             fwrite($fileStream, $data);
//             fclose($fileStream);
//         }
//     }
// }
