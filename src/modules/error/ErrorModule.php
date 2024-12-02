<?php

namespace App\modules\error;

use App\core\Module;
use App\modules\error\controllers\ApiErrorController;


class ErrorModule extends Module
{
    public function getControllers(): array
    {
        return [
            ApiErrorController::class,
        ];
    }
}
