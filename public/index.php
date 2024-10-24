<?php

require_once "../src/init.php";

$app = new App;

$app->declareModule(
    "user",
    "HomeController",
    "CategoryController",
    "ProductController",
    "CartController"
);

$app->resolve();
