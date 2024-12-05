<?php

namespace App\modules\checkout;

use App\core\Module;
use App\modules\checkout\controllers\CheckoutController;

class CheckoutModule extends Module
{
    public function getControllers(): array
    {
        return [
            CheckoutController::class
        ];
    }
}
