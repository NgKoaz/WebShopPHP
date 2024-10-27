<?php

require_once "../vendor/autoload.php";

use App\core\App;
use App\modules\user\controllers;

$app = new App;

try {
    $app->declareModule(
        "user",
        "HomeController",
        "CategoryController",
        "ProductController",
        "CartController"
    );
} catch (Exception $e) {
    echo "<pre>" . $e . "</pre>";
}

$app->resolve();
