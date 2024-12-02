<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\services\LoginManager;

class CartController extends Controller
{
    public function __construct(private LoginManager $loginManager) {}

    #[HttpGet("/cart")]
    public function getIndex()
    {
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        $this->view(viewData: $viewData);
    }
}
