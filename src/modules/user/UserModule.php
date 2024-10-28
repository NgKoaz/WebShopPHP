<?php

namespace App\modules\user;

use App\core\Module;
use App\modules\user\controllers\HomeController;

class UserModule extends Module
{
    public function getControllers(): array
    {
        return [
            HomeController::class,
            // ProductController::class
        ];
    }
}
