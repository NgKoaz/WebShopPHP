<?php

namespace App\modules\user;

use App\core\Module;
use App\modules\user\controllers\CartController;
use App\modules\user\controllers\CategoryController;
use App\modules\user\controllers\HomeController;
use App\modules\user\controllers\ProductController;
use App\modules\user\controllers\UserApiCartController;
use App\modules\user\controllers\UserApiProductController;
use App\modules\user\controllers\UserApiReviewController;

class UserModule extends Module
{
    public function getControllers(): array
    {
        return [
            HomeController::class,
            ProductController::class,
            CategoryController::class,
            CartController::class,
            UserApiProductController::class,
            UserApiCartController::class,
            UserApiReviewController::class
        ];
    }
}
