<?php

use App\core\App;


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
$count = $viewData["count"];
$from = $viewData["from"];
$to = $viewData["to"];


$title = $categoryName;
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
                    <div class="price" data-device="PC">
                        <h4 class="title">Price</h4>
                        <div class="price-slider">
                            <div class="slider-range"></div>
                            <span class="tooltip min-tooltip hide">$0</span>
                            <input class="min-price-slider" type="range" min="0" max="300" value="0">
                            <span class="tooltip max-tooltip hide">$300</span>
                            <input class="max-price-slider" type="range" min="0" max="300" value="300">
                        </div>
                        <div class="price-input-container">
                            <div class="price-input">
                                <label class="addon">$</label>
                                <input class="min-price-input" type="number" name="min" value="0">
                            </div>
                            <div class="price-input">
                                <label class="addon">$</label>
                                <input class="max-price-input" type="number" name="max" value="300">
                            </div>
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
                    <button class="apply-btn" onclick="filterProducts(event, true)">Apply</button>
                </div>
            </div>

        </div>
        <div class="search-area">
            <div class="top">
                <h4 class="main-title left"><?= $categoryName ?></h4>
                <div class="right">
                    <div><?php echo "Showing $from-$to of $count" ?> <span>Sort by: Most Popular</span></div>
                    <button class="filter-btn" onclick="showFilterMobile(event)"><i class="bi bi-sliders2-vertical"></i></button>
                </div>
            </div>

            <div class="items">
                <?php
                foreach ($products as $product) {
                    $numStar = ($product["total_reviews"] != 0) ? round($product["total_rates"] /  $product["total_reviews"] / 20, 1) : 0;
                    $fillStar = floor($numStar);
                    $isHalf = (round($numStar * 10) - $fillStar * 10) == 0 ? 0 : 1;
                    $noFillStar = 5 - $fillStar - $isHalf;

                    $imgs = json_decode($product["images"], true);
                    $lgImage = "/public/images/no_image.webp";
                    if (count($imgs) > 0) {
                        $lgImage = $imgs[0]["lg"];
                    }

                    echo '
                    <a class="card" href="/products/' . $product["slug"] . '"> 
                        <img src="' . $lgImage . '">
                        <h3 class="title">' . $product["name"] . '</h3>
                        <div class="stars">
                            ' . ($fillStar > 0 ? str_repeat('<i class="bi bi-star-fill star-ic"></i> ', $fillStar) : '') . '
                            ' . ($isHalf > 0 ? str_repeat('<i class="bi bi-star-half star-ic"></i> ', $isHalf) : '') . '
                            ' . ($noFillStar > 0 ? str_repeat('<i class="bi bi-star star-ic"></i> ', $noFillStar) : '') . '
                            <span>' .  $numStar . '/5</span>
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
                echo '<a class="prev ' . (+$currentPage <= 1 ? "disabled" : "") . '" href="' . str_replace(":page", ($currentPage - 1) > 0 ? $currentPage - 1 : 1, $blankPageUrl) . '">Previous</a>';
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
                echo '<a class="next ' . (+$currentPage >= +$totalPages ? "disabled" : "") . '" href="' . str_replace(":page", ($currentPage + 1) <= $totalPages ? $currentPage + 1 :  $totalPages, $blankPageUrl) . '">Next</a>';
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
            <div class="price" data-device="Mobile">
                <h4 class="title">Price</h4>
                <div class="price-slider">
                    <div class="slider-range"></div>
                    <span class="tooltip min-tooltip hide">$0</span>
                    <input class="min-price-slider" type="range" min="0" max="300" value="0">
                    <span class="tooltip max-tooltip hide">$300</span>
                    <input class="max-price-slider" type="range" min="0" max="300" value="300">
                </div>
                <div class="price-input-container">
                    <div class="price-input">
                        <label class="addon">$</label>
                        <input class="min-price-input" type="number" name="min" value="0">
                    </div>
                    <div class="price-input">
                        <label class="addon">$</label>
                        <input class="max-price-input" type="number" name="max" value="300">
                    </div>
                </div>
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
        <button class="aplly-btn" onclick="filterProducts(event, false)">Apply Filter</button>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>