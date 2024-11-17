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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/src/layout/global.css">
    <link rel="stylesheet" href="/src/layout/Admin/AdminLayout.css">
    <?php $this->loadStylesheets() ?>
</head>

<body>

    <div class="root-container">
        <aside class="left">
            <div class="logo"><a href="/admin">ADMIN</a></div>
            <ul class="my-navbar">
                <li>
                    <a class="" href="/admin">
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
                <li>
                    <a href="/admin/categories">
                        <i class="bi bi-tags-fill icon-24"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/roles">
                        <i class="bi bi-person-lines-fill icon-24"></i>
                        <span>Roles</span>
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
                <h2 class="header-left"><?= $viewData["title"] ?></h2>
                <div class="header-right">
                    <i class="bi bi-gear-fill icon-24 setting-icon"></i>
                </div>
            </header>
            <main>
                <?= $content; ?>
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="/src/layout/Admin/AdminLayout.js" defer></script>
    <?php $this->loadScripts() ?>
    <script src="https://kit.fontawesome.com/f521236fc5.js" crossorigin="anonymous" defer></script>
</body>

</html>