<?php

require_once 'command/RouteCacheCommand.php';

$command = isset($argv[1]) ? $argv[1] : '';

$routeCacheCommand = new RouteCacheCommand();


switch ($command) {
    case 'route:cache':
        $routeCacheCommand->cacheRoutes();
        break;
    case 'route:clear':
        $routeCacheCommand->clearCache();
        break;
    default:
        echo "Lệnh không hợp lệ. Sử dụng 'route:cache' hoặc 'route:clear'.\n";
        break;
}
