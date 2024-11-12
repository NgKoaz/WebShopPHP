<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;

class AdminController extends Controller
{

    public function __construct() {}

    #[HttpGet("/admin")]
    public function getAdmin()
    {
        $this->view("Admin");
    }
}
