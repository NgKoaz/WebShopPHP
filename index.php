<?php

require_once "./vendor/autoload.php";

use App\core\App;
use App\modules\user\UserModule;

$app = new App();
$app->importModule(new UserModule);

$app->run();
