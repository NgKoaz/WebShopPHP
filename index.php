<?php

require_once "./vendor/autoload.php";

use App\core\App;
use App\modules\user\UserModule;
use App\modules\auth\AuthModule;
use App\core\Container;
use App\Interface\IPaymentMethod;
use App\modules\admin\AdminModule;
use App\modules\checkout\CheckoutModule;
use App\modules\error\ErrorModule;
use App\modules\subscription\SubscriptionModule;
use App\services\CartManager;
use App\services\CategoryManager;
use App\services\CheckoutManager;
use App\services\JWTService;
use App\services\LoginManager;
use App\services\MailService;
use App\services\MomoPayment;
use App\services\ProductManager;
use App\services\ReviewManager;
use App\services\RoleManager;
use App\services\SessionManager;
use App\services\SubscriptionManager;
use App\services\UserManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;
use Google\Client;

define("ROOT_DIR", __DIR__);
define("HTTP_HOST", "http://localhost:8080");

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();



Container::getInstance()
    ->addSingleton(
        EntityManager::class,
        function () {
            $entitiesPath = [__DIR__ . '/src/Entities'];
            $connectionConfig = [
                'dbname' => $_ENV["DB_NAME"],
                'user' => $_ENV["DB_USER"],
                'password' => $_ENV["DB_PASS"],
                'host' => $_ENV["DB_HOST"],
                'driver' => $_ENV["DB_DRIVER"],
            ];
            $ORMConfig = ORMSetup::createAttributeMetadataConfiguration($entitiesPath, true);
            $connection = DriverManager::getConnection($connectionConfig);
            return new EntityManager($connection, $ORMConfig);
        }
    )
    ->addSingleton(
        IPaymentMethod::class,
        function () {
            return new MomoPayment(
                $_ENV["MOMO_ENDPOINT"],
                $_ENV["MOMO_PARTNER_CODE"],
                $_ENV["MOMO_ACCESS_KEY"],
                $_ENV["MOMO_SECRET_KEY"],
                "http://localhost:8080/checkout/momo/callback",
                "http://localhost:8080/api/checkout/momo/ipn"
            );
        }
    )
    ->addSingleton(
        MomoPayment::class,
        function () {
            return new MomoPayment(
                $_ENV["MOMO_ENDPOINT"],
                $_ENV["MOMO_PARTNER_CODE"],
                $_ENV["MOMO_ACCESS_KEY"],
                $_ENV["MOMO_SECRET_KEY"],
                "http://localhost:8080/checkout/momo/callback",
                "http://localhost:8080/api/checkout/momo/ipn"
            );
        }
    )
    ->addSingleton(Client::class, function () {
        $client = new Client();
        $client->setClientId($_ENV["GOOGLE_CLIENT_ID"]);
        $client->setClientSecret($_ENV["GOOGLE_CLIENT_SECRET"]);
        $client->setRedirectUri('http://localhost:8080/api/auth/google');
        $client->addScope('openid');
        $client->addScope('email');
        $client->addScope('profile');
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        return $client;
    })
    ->addSingleton(MailService::class, function () {
        return new MailService(
            $_ENV["EMAIL_HOST"],
            $_ENV["EMAIL_USERNAME"],
            $_ENV["EMAIL_PASSWORD"],
            $_ENV["EMAIL_PORT"],
            $_ENV["EMAIL_NAME_DISPLAY"]
        );
    })
    ->addSingleton(JWTService::class, function () {
        return new JWTService($_ENV["JWT_SECRET_KEY"]);
    })
    ->addSingleton(SessionManager::class, SessionManager::class)
    ->addSingleton(UserManager::class, UserManager::class)
    ->addSingleton(LoginManager::class, LoginManager::class)
    ->addSingleton(RoleManager::class, RoleManager::class)
    ->addSingleton(ProductManager::class, ProductManager::class)
    ->addSingleton(CategoryManager::class, CategoryManager::class)
    ->addSingleton(CartManager::class, CartManager::class)
    ->addSingleton(ReviewManager::class, ReviewManager::class)
    ->addSingleton(CheckoutManager::class, CheckoutManager::class)
    ->addSingleton(SubscriptionManager::class, SubscriptionManager::class);

$app = new App();

$app->importModule(new UserModule);
$app->importModule(new AuthModule);
$app->importModule(new AdminModule);
$app->importModule(new ErrorModule);
$app->importModule(new CheckoutModule);
$app->importModule(new SubscriptionModule);

$app->run();
