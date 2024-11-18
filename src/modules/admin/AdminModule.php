<?php

namespace App\modules\admin;

use App\core\Module;
use App\modules\admin\controllers\ApiUserController;
use App\modules\admin\controllers\AdminController;
use App\modules\admin\controllers\ApiCategoryController;
use App\modules\admin\controllers\ApiProductController;

class AdminModule extends Module
{
    public function getControllers(): array
    {
        return [
            AdminController::class,
            ApiUserController::class,
            ApiProductController::class,
            ApiCategoryController::class
        ];
    }
}
