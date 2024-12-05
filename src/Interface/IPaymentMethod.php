<?php

namespace App\Interface;

use App\Entities\Bill;

define("MOMO_PAY", 0);

interface IPaymentMethod
{
    public function createUrl(Bill $bill): string;
}
