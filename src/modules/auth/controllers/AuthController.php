<?php

namespace App\modules\auth\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;

class AuthController extends Controller
{
    public function __construct() {}

    #[HttpGet("/login")]
    public function getLogin()
    {
        $this->view("login");
    }

    #[HttpGet("/register")]
    public function getRegister()
    {
        $this->view("Register");
    }
}
