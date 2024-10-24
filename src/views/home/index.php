<?php

// $title = "Im Pro!!";
ob_start();
?>


<h1> WE asfsfsdre ffi</h1>
<div>fskdfsdkf </div>


<?php
$content = ob_get_clean();
include "/phppractice/src/layout/layout.php";
