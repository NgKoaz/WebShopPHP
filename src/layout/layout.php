<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
</head>

<body>
    <div class="root-container">
        <?php include "/phppractice/src/shared/header.php"; ?>

        <div class="app-container">
            <?= $content; ?>
        </div>

        <?php include "/phppractice/src/shared/footer.php"; ?>
    </div>
</body>

</html>