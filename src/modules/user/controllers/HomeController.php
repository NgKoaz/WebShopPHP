<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\services\LoginManager;
use App\services\SessionManager;


class HomeController extends Controller
{
    public function __construct(private LoginManager $loginManager, private SessionManager $sessionManager) {}

    #[HttpGet("/")]
    public function getIndex()
    {
        $viewData = new ArrayList;
        $viewData["TempMessage"] = $this->sessionManager->getFlash("TempMessage");
        $viewData["IsErrorMessage"] = $this->sessionManager->getFlash("IsErrorMessage");
        $this->view("index", viewData: $viewData);
    }
}
