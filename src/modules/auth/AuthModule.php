<?php

namespace App\modules\auth;

use App\core\Module;
use App\modules\auth\controllers\AuthController;

class AuthModule extends Module
{
    public function getControllers(): array
    {
        return [
            AuthController::class
        ];
    }
}
