<?php

namespace App\modules\subscription;

use App\core\Module;
use App\modules\subscription\controllers\SubscriptionController;

class SubscriptionModule extends Module
{
    public function getControllers(): array
    {
        return [
            SubscriptionController::class,
        ];
    }
}
