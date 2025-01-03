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
    <link rel="stylesheet" href="/src/layout/Admin/AdminLayout.css">
    <?php $this->loadStylesheets() ?>
</head>

<body>

    <div class="root-container">
        <aside class="left show">
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
                <li>
                    <a href="/admin/subscriptions">
                        <i class="bi bi-megaphone icon-24"></i>
                        <span>Subscriptions</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/orders">
                        <i class="bi bi-box-seam icon-24"></i>
                        <span>Orders</span>
                    </a>
                </li>
            </ul>
            <button id="toggleNavBtn">
                <i class="bi bi-arrow-left-circle-fill active"></i>
            </button>
        </aside>
        <div class="right">
            <header>
                <h2 class="header-left"><?= $viewData["title"] ?></h2>
                <div class="header-right dropdown">
                    <i class="bi bi-gear-fill icon-24 setting-icon dropdown-menu-btn"></i>
                    <ul class="menu">
                        <a href="/" style="color: black;">
                            <li class="item">Switch to user</li>
                        </a>
                        <li class="item danger" data-logout-btn>Logout</li>
                    </ul>
                </div>
            </header>
            <main>
                <?= $content; ?>
            </main>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#ff3838"></rect>
                </svg>
                <strong class="me-auto toast-title" style="color: #ff3838;"></strong>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog custom-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalButton">Close</button>
                    <button type="button" class="btn btn-primary" id="submitModalButton"></button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <?php $this->loadLibraryScripts() ?>
    <script src="/src/layout/Admin/AdminLayout.js" defer></script>
    <?php $this->loadScripts() ?>
    <script src="https://kit.fontawesome.com/f521236fc5.js" crossorigin="anonymous" defer></script>
</body>

</html>