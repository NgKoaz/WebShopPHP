<?php

namespace App\modules\auth;

use App\core\Module;
use App\modules\auth\controllers\ApiAuthController;
use App\modules\auth\controllers\AuthController;
use App\modules\auth\controllers\ExternalLoginController;

class AuthModule extends Module
{
    public function getControllers(): array
    {
        return [
            AuthController::class,
            ApiAuthController::class,
            ExternalLoginController::class
        ];
    }
}
