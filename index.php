<?php

require_once "./vendor/autoload.php";

use App\core\App;
use App\modules\user\UserModule;
use App\modules\auth\AuthModule;
use App\core\Container;
use App\modules\admin\AdminModule;
use App\services\CategoryManager;
use App\services\LoginManager;
use App\services\ProductManager;
use App\services\RoleManager;
use App\services\SessionManager;
use App\services\UserManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$entitiesPath = [__DIR__ . '/src/Entities'];
$connectionConfig = [
    'dbname' => $_ENV["DB_NAME"],
    'user' => $_ENV["DB_USER"],
    'password' => $_ENV["DB_PASS"],
    'host' => $_ENV["DB_HOST"],
    'driver' => $_ENV["DB_DRIVER"],
];

Container::getInstance()
    ->addSingleton(
        EntityManager::class,
        function () use ($entitiesPath, $connectionConfig) {
            $ORMConfig = ORMSetup::createAttributeMetadataConfiguration($entitiesPath, true);
            $connection = DriverManager::getConnection($connectionConfig);
            return new EntityManager($connection, $ORMConfig);
        }
    )
    ->addSingleton(SessionManager::class, SessionManager::class)
    ->addSingleton(UserManager::class, UserManager::class)
    ->addSingleton(LoginManager::class, LoginManager::class)
    ->addSingleton(RoleManager::class, RoleManager::class)
    ->addSingleton(ProductManager::class, ProductManager::class)
    ->addSingleton(CategoryManager::class, CategoryManager::class);

$app = new App();

$app->importModule(new UserModule);
$app->importModule(new AuthModule);
$app->importModule(new AdminModule);







$app->run();
