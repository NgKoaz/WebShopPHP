<?php

$title = "Home Page";
ob_start();

?>

<h1>H!LL</h1>
<form action="/" method="POST">
    <button>Click here</button>
</form>

<?php
$content = ob_get_clean();
include "/phppractice/src/layout/layout.php";
?>