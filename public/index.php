<?php

require_once "../src/init.php";

$app = new App;


$app->addControllers(
    "HomeController",
    "ProductDetailController",
    "CategoryController",
    "CartController",
);

$app->declareModule([
    "module" => "admin",
    "controllers" => ["HomeController"]
]);

$app->resolve();
