<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;



class CategoryController extends Controller
{
    public function __construct() {}

    #[HttpGet("/category")]
    public function getIndex()
    {
        $this->view();
    }
}
