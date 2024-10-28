<?php

require_once "../vendor/autoload.php";

use App\core\App;
use App\modules\user\UserModule;



$app = new App();



try {
    $app->importModule(new UserModule);
    // $app->declareModule(
    //     "user",
    //     HomeController::class,
    //     // "CategoryController",
    //     // "ProductController",
    //     // "CartController"
    // );
} catch (Exception $e) {
    echo "<pre>" . $e . "</pre>";
}

$app->run();
