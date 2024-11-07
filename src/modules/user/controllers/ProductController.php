<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;


class ProductController extends Controller
{
    public function __construct() {}

    #[HttpGet("/product/:productId")]
    public function getDetail(string $productId)
    {
        $this->view();
    }
}
