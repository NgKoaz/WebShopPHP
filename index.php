<?php

require_once "./vendor/autoload.php";

use App\core\App;
use App\modules\user\UserModule;
use App\modules\auth\AuthModule;
use App\core\Container;
use App\modules\admin\AdminModule;
use App\services\TestService;
use App\services\UserManager;

Container::getInstance()
    ->addSingleton(TestService::class, TestService::class)
    ->addSingleton(UserManager::class, UserManager::class);

$app = new App();

$app->importModule(new UserModule);
$app->importModule(new AuthModule);
$app->importModule(new AdminModule);


$app->run();
