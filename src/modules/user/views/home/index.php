<?php
$title = "Home Page";
ob_start();
?>

<h1>H!LL</h1>

<?php
$content = ob_get_clean();
include "/phppractice/src/layout/layout.php";
?>