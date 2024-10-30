<?php

$GLOBALS['IS_CACHING_ROUTES'] = true;

require_once "index.php";

use App\core\Router;
use App\core\App;

class RouteCacheCommand
{
    private string $cacheFile;

    public function __construct()
    {
        $this->cacheFile = App::getCacheRoutingTablePath();
    }

    // Phương thức để tải routes
    public function loadRoutes(): array
    {
        return Router::getRoutingTable() ?? [];
    }

    // Phương thức để lưu routes vào cache
    public function cacheRoutes(): void
    {
        $routingTable = $this->loadRoutes();
        file_put_contents($this->cacheFile, json_encode($routingTable));
        echo "Routes have already been cached ;)!\n";
    }

    // Phương thức để xóa cache
    public function clearCache(): void
    {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
            echo "Routes have already been deleted ;)!\n";
        } else {
            echo "Not found Routes to delete!\n";
        }
    }
}
