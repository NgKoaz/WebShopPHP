<?php

namespace App\services;

use App\Entities\Bill;
use App\Interface\IPaymentMethod;

class MomoPayment implements IPaymentMethod
{

    public function __construct(
        private string $endpoint,
        private string $partnerCode,
        private string $accessKey,
        private string $secretKey,
        private string $redirectUrl,
        private string $ipnUrl
    ) {}

    public function createUrl(Bill $bill): string
    {
        // $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        // $partnerCode = 'MOMOBKUN20180529';
        // $accessKey = 'klm05TvNBzhg7h7j';
        // $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $rate = 25400;
        $orderInfo = "Thanh toán qua MoMo";
        // $amount = $bill->totalPrice * $rate;
        $amount = 10000;
        $orderId = $bill->id;
        // $redirectUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        // $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        $extraData =    "";

        $requestId = $bill->id;
        $requestType = "payWithMethod";

        $rawHash = "accessKey=" . $this->accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $this->ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $this->partnerCode . "&redirectUrl=" . $this->redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
        $data = json_encode(array(
            'partnerCode' => $this->partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' =>  $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl' => $this->ipnUrl,
            'lang' => 'en',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ));

        $httpRequest = curl_init($this->endpoint);
        curl_setopt($httpRequest, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $httpRequest,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 5);
        curl_setopt($httpRequest, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($httpRequest);
        curl_close($httpRequest);

        return $result;
    }

    public function getExchangeRate(): string
    {
        $requestId = time() . "";
        $rawHash = "accessKey=" . $this->accessKey . "&partnerCode=" . $this->partnerCode . "&requestId=" . $requestId;
        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
        $data = json_encode(array(
            'partnerCode' => $this->partnerCode,
            'requestId' => $requestId,
            "baseCurrency" => "USD",
            "lang" => "en",
            "signature" => $signature
        ));

        $httpRequest = curl_init("https://test-payment.momo.vn/v2/gateway/api/remittance/exchange-rate");
        curl_setopt($httpRequest, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $httpRequest,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($httpRequest, CURLOPT_TIMEOUT, 5);
        curl_setopt($httpRequest, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($httpRequest);
        curl_close($httpRequest);
        return $result;
    }
}
