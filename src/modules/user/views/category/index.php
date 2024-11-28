<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");


$products = $viewData["products"] ?? [];
$totalPages = $viewData["totalPages"] ?? 1;
$currentPage = $viewData["currentPage"] ?? 1;
$ancestorCategories = $viewData["ancestorCategories"] ?? [];
$lenCategories =  count($ancestorCategories);
$categoryName = $lenCategories > 0 ? $ancestorCategories[$lenCategories - 1]['name'] : "Shop";
$blankPageUrl = $viewData["blankPageUrl"];

ob_start();
?>

<div class="app-container">
    <div class="breadcrumb">
        <span>
            <a href="/">Home</a> > <a href="/categories">Shop</a>
            <?php
            foreach ($ancestorCategories as $category) {
                echo " > <a href=/categories/" . $category["slug"] . ">" . $category['name'] . "</a>";
            }
            ?>
        </span>
    </div>
    <div class="main-content">
        <div class="sidebar">
            <div class="filter-sidebar">
                <div class="top">
                    <div class="title">Filters</div>
                    <i class="bi bi-sliders2-vertical"></i>
                </div>
                <div class="bottom">
                    <div class="price">
                        <h4 class="title">Price</h4>
                        <div id="priceSlider">
                            <input id="minPrice" type="range" min="0" max="1500">
                            <input id="maxPrice" type="range" min="0" max="1500">
                        </div>
                    </div>

                    <div class="styles">
                        <h4 class="title">Dress Style</h4>
                        <ul class="style-selection">
                            <li>
                                <label for="casual-style">Casual</label>
                                <input id="casual-style" type="radio" name="category" value="casual">
                            </li>
                            <li>
                                <label for="formal-style">Formal</label>
                                <input id="formal-style" type="radio" name="category" value="formal">
                            </li>
                            <li>
                                <label for="party-style">Party</label>
                                <input id="party-style" type="radio" name="category" value="party">

                            </li>
                            <li>
                                <label for="gym-style">Gym</label>
                                <input id="gym-style" type="radio" name="category" value="gym">
                            </li>
                        </ul>
                    </div>
                    <button class="apply-btn" onclick="filterProducts(event)">Apply</button>
                </div>
            </div>

        </div>
        <div class="search-area">
            <div class="top">
                <h4 class="main-title left"><?= $categoryName ?></h4>
                <div class="right">
                    <div>Showing 1-10 of 100 <span>Sort by: Most Popular</span></div>
                    <button class="filter-btn" onclick="showFilterMobile(event)"><i class="bi bi-sliders2-vertical"></i></button>
                </div>
            </div>

            <div class="items">
                <?php
                foreach ($products as $product) {
                    echo '
                    <a class="card" href="/products/' . $product["slug"] . '"> 
                        <img src="/public/images/newarrivals/cloth1.png">
                        <h3 class="title">' . $product["name"] . '</h3>
                        <div class="stars">
                            <i class="bi bi-star-fill star-ic"></i>
                            <i class="bi bi-star-fill star-ic"></i>
                            <i class="bi bi-star-fill star-ic"></i>
                            <i class="bi bi-star-half star-ic"></i>
                            <i class="bi bi-star star-ic"></i>
                            <span>5/5</span>
                        </div>
                        <div class="price">
                            $' . $product["price"] . '
                    </div>
                    </a>
                    ';
                }
                ?>
            </div>

            <hr>

            <div class="pagination">
                <?php
                echo '<a class="prev" href="' . str_replace(":page", ($currentPage - 1) > 0 ? $currentPage - 1 : 1, $blankPageUrl) . '">Previous</a>';
                ?>

                <div>
                    <?php

                    if ($currentPage >= 3) {
                        echo '<a class="page-num" href="' . str_replace(":page", 1, $blankPageUrl) . '"><span>' . 1 . '</span></a>';
                        if ($currentPage >= 4) {
                            echo '<a class="page-num"><span>...</span></a>';
                        }
                    }

                    for ($i = 1; $i <= $totalPages; $i = $i + 1) {
                        if ($i === $currentPage - 1 || $i === $currentPage || $i === $currentPage + 1) {
                            echo '<a class="page-num ' . ($i == $currentPage ? "active" : "") . '" href="' . str_replace(":page", $i, $blankPageUrl) . '"><span>' . $i . '</span></a>';
                        }
                    }

                    if ($currentPage <= $totalPages - 2) {
                        if ($currentPage <= $totalPages - 3) {
                            echo '<a class="page-num"><span>...</span></a>';
                        }
                        echo '<a class="page-num" href="' . str_replace(":page", $totalPages, $blankPageUrl) . '"><span>' . $totalPages . '</span></a>';
                    }
                    ?>

                </div>
                <?php
                echo '<a class="next" href="' . str_replace(":page", ($currentPage + 1) <= $totalPages ? $currentPage + 1 :  $totalPages, $blankPageUrl) . '">Next</a>';
                ?>
            </div>
        </div>
    </div>

    <div id="filterModal" class="filter-modal">
        <div class="top">
            <h4 class="title">Filters</h4>
            <button class="close-btn" onclick="closeFilterModal(event)"><i class="bi bi-x"></i></button>
        </div>
        <div class="bottom">

            <div class="price">
                <div class="title">Price</div>
                <div>Price Slide</div>
            </div>

            <div class="options">
                <h4 class="title">Dress Style</h4>
                <div class="styles">
                    <ul class="style-selection">
                        <li>
                            <label for="casual-style">Casual</label>
                            <input id="casual-style" type="radio" name="category" value="casual">
                        </li>
                        <li>
                            <label for="formal-style">Formal</label>
                            <input id="formal-style" type="radio" name="category" value="formal">
                        </li>
                        <li>
                            <label for="party-style">Party</label>
                            <input id="party-style" type="radio" name="category" value="party">

                        </li>
                        <li>
                            <label for="gym-style">Gym</label>
                            <input id="gym-style" type="radio" name="category" value="gym">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <button class="aplly-btn" onclick=filterProducts(event)>Apply Filter</button>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>