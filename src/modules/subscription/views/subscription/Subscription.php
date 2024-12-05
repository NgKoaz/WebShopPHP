<?php

use App\core\App;

$title = "Subscribe successfully!";
$this
    ->addScript("Subscription.js")
    ->addStylesheet("Subscription.css");

ob_start();
?>

<div class="app-container">
    <h4><?= $viewData["title"] ?></h4>
    <p><?= $viewData["message"] ?></p>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>