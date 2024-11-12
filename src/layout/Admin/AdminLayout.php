<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/src/layout/global.css">
    <link rel="stylesheet" href="/src/layout/Admin/AdminLayout.css">
    <?php $this->loadStylesheets() ?>
</head>

<body>

    <div class="root-container">
        <aside class="left">
            <div class="logo"><a href="/admin">ADMIN</a></div>
            <ul class="nav">
                <li>
                    <a class="active" href="/admin">
                        <i class="bi bi-house-door-fill icon-24"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/users">
                        <i class="bi bi-people-fill icon-24"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/products">
                        <i class="bi bi-boxes icon-24"></i>
                        <span>Products</span>
                    </a>
                </li>
            </ul>
            <button id="close-nav">
                <i class="bi bi-arrow-left-circle-fill active"></i>
                <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </aside>
        <div class="right">
            <header>
                <h2 class="header-left">Overview</h2>
                <div class="header-right">
                    <i class="bi bi-gear-fill icon-24 setting-icon"></i>
                </div>
            </header>
            <main>
                <?= $content; ?>
            </main>
        </div>
    </div>


    <script src="/src/layout/Admin/AdminLayout.js" defer></script>
    <?php $this->loadScripts() ?>
    <script src="https://kit.fontawesome.com/f521236fc5.js" crossorigin="anonymous" defer></script>
</body>

</html>