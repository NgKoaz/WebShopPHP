<?php

namespace App\modules\error\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;


class ApiErrorController extends Controller
{
    public function __construct() {}

    #[HttpGet("/api/errors/roles")]
    public function unauthorize()
    {
        return $this->code(401);
    }
}
