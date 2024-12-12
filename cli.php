<?php

use Dotenv\Dotenv;

require_once 'command/RouteCacheCommand.php';

$command = isset($argv[1]) ? $argv[1] : '';
$routeCacheCommand = new RouteCacheCommand();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


switch ($command) {
    case 'route:cache':
        $routeCacheCommand->cacheRoutes();
        break;
    case 'route:clear':
        $routeCacheCommand->clearCache();
        break;
    case 'create:sitemap':
        generateSitemap();
        break;
    case 'create:admin':
        $username = isset($argv[2]) ? $argv[2] : null;
        $password = isset($argv[3]) ? $argv[3] : null;
        $email = isset($argv[4]) ? $argv[4] : null;
        if ($username  === null || $password === null || $email === null) echo "Lệnh không hợp lệ. \n";
        else createAdminAccount($username, $password, $email);
        break;
    default:
        echo "Lệnh không hợp lệ. Sử dụng 'route:cache' hoặc 'route:clear' hoặc 'create:sitemap' hoặc 'create:admin'.\n";
        break;
}





function createAdminAccount($username, $password, $email)
{
    $pdo = getPDOConnection();
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("
        INSERT INTO webshop.users
            (username, email, password_hash, phone_number, first_name, last_name, address, roles, is_deleted, used_promo_codes, deleted_at, created_at, is_verified_email, can_reviews)
        VALUES (:username, :email, :passwordHash, :phoneNumber, '', '', 'None', :roles, 0, NULL, NULL, :createdAt, 1, NULL);
    ");

    $phoneNumber = "0987654321";
    $roles = '["1"]';
    $email = "admin@gmail.com";
    $createdAt = date('Y-m-d H:i:s');
    $stmt->execute([
        ':username' => $username,
        ':passwordHash' => $passwordHash,
        ':phoneNumber' => $phoneNumber,
        ':roles' => $roles,
        ':createdAt' => $createdAt,
        ':email' => $email
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}



function generateSitemap()
{
    $baseUrl = $_ENV["WEB_HOST_URL"] . "/";
    $sitemapFilePath = "sitemap.xml";

    $products = getProducts();
    $categories = getCategories();

    $file = fopen($sitemapFilePath, 'w');
    if ($file) {
        fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
        fwrite($file, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

        fwrite($file, "  <url>" . PHP_EOL);
        fwrite($file, "    <loc>" . $baseUrl . "</loc>" . PHP_EOL);
        fwrite($file, "    <changefreq>weekly</changefreq>" . PHP_EOL);
        fwrite($file, "    <priority>0.8</priority>" . PHP_EOL);
        fwrite($file, "  </url>" . PHP_EOL);

        fwrite($file, "  <url>" . PHP_EOL);
        fwrite($file, "    <loc>" . $baseUrl . "categories" . "</loc>" . PHP_EOL);
        fwrite($file, "    <changefreq>weekly</changefreq>" . PHP_EOL);
        fwrite($file, "    <priority>0.8</priority>" . PHP_EOL);
        fwrite($file, "  </url>" . PHP_EOL);

        foreach ($categories as $category) {
            fwrite($file, "  <url>" . PHP_EOL);
            fwrite($file, "    <loc>" . $baseUrl . "categories/" . $category["slug"] . "</loc>" . PHP_EOL);
            fwrite($file, "    <changefreq>weekly</changefreq>" . PHP_EOL);
            fwrite($file, "    <priority>0.8</priority>" . PHP_EOL);
            fwrite($file, "  </url>" . PHP_EOL);
        }

        foreach ($products as $product) {
            fwrite($file, "  <url>" . PHP_EOL);
            fwrite($file, "    <loc>" . $baseUrl . "products/" . $product["slug"] . "</loc>" . PHP_EOL);
            fwrite($file, "    <changefreq>weekly</changefreq>" . PHP_EOL);
            fwrite($file, "    <priority>0.8</priority>" . PHP_EOL);
            fwrite($file, "  </url>" . PHP_EOL);
        }

        fwrite($file, '</urlset>' . PHP_EOL);

        fclose($file);

        echo "Sitemap generated successfully at: $sitemapFilePath" . PHP_EOL;
    } else {
        echo "Failed to create the sitemap file." . PHP_EOL;
    }
}



function getCategories()
{
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("SELECT c.slug FROM categories c");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $categories;
}


function getProducts()
{
    $pdo = getPDOConnection();

    $stmt = $pdo->prepare("SELECT p.slug FROM products p");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $products;
}

function getPDOConnection()
{
    $host =  $_ENV["DB_HOST"];
    $dbname =  $_ENV["DB_NAME"];
    $username = $_ENV["DB_USER"];
    $password = $_ENV["DB_PASS"];

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
