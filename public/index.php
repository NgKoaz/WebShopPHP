<?php

require_once "../src/init.php";

$app = new App;

$app->addControllers(
    "HomeController"
);

$app->resolve();
