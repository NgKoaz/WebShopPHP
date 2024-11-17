<?php

namespace App\modules\admin;

use App\core\Module;
use App\modules\admin\controllers\ApiUserController;
use App\modules\admin\controllers\AdminController;

class AdminModule extends Module
{
    public function getControllers(): array
    {
        return [
            AdminController::class,
            ApiUserController::class
        ];
    }
}
