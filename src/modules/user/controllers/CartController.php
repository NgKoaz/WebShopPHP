<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;


class CartController extends Controller
{
    public function __construct() {}

    #[HttpGet("/cart")]
    public function getIndex()
    {
        $this->view();
    }
}
