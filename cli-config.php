<?php

require 'vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$connectionConfig = [
    'dbname' => $_ENV["DB_NAME"],
    'user' => $_ENV["DB_USER"],
    'password' => $_ENV["DB_PASS"],
    'host' => $_ENV["DB_HOST"],
    'driver' => $_ENV["DB_DRIVER"],
];

$entitiesPath = [__DIR__ . '/src/Entities'];
$ORMConfig = ORMSetup::createAttributeMetadataConfiguration($entitiesPath, true);
$connection = DriverManager::getConnection($connectionConfig);
$entityManager = new EntityManager($connection, $ORMConfig);

$config = new PhpFile(__DIR__ . '/src/config/Migrations.php');
$dependencyFactory = DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));

return $dependencyFactory;
