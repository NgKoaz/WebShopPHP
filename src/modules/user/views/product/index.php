<?php

use App\core\App;
use App\Entities\Product;

$this
    ->addScript("index.js")
    ->addStylesheet("index.css");


/**
 * @var Product
 */
$product = $viewData["product"];
$ancestorCategories = $viewData["ancestorCategories"] ?? [];

$imgs = json_decode($product->images, true);
$lgImage = "/public/images/no_image.webp";

if (count($imgs) === 0) $imgs[] = ["lg" => "/public/images/no_image.webp", "sm" => "/public/images/sm_no_image.webp"];

$title = $product->name;


ob_start();
?>

<div class="app-container">
    <div class="breadcrumb">
        <span>
            <a href="/">Home</a> > <a href="/categories">Shop</a>
            <?php
            foreach ($ancestorCategories as $category) {
                echo " > <a href=/categories/" . $category["slug"] . " >" . $category['name'] . "</a>";
            }
            ?>
        </span>
    </div>
    <div class="main-content">
        <div class="product">
            <div class="left product-images">
                <div class="large-image">
                    <img src="<?= $imgs[0]["lg"] ?>">
                    <?php
                    if ($product->quantity == 0)
                        echo '<div class="out-of-order"></div>';
                    ?>
                </div>
                <div class="small-images">
                    <?php

                    foreach ($imgs as $img) {
                        echo '<div class="small-image-container"><img src="' . $img["sm"] . '"></div>';
                    }
                    ?>
                </div>
            </div>

            <div class="right">
                <div class="product-info">
                    <h4 class="title"><?= $product->name ?></h4>
                    <div class="stars">
                        <?php
                        $numStar = ($product->totalReviews != 0) ? round($product->totalRates /  $product->totalReviews / 20, 1) : 0;
                        $fillStar = floor($numStar);
                        $isHalf = (round($numStar * 10) - $fillStar * 10) == 0 ? 0 : 1;
                        $noFillStar = 5 - $fillStar - $isHalf;

                        echo '
                            ' . ($fillStar > 0 ? str_repeat('<i class="bi bi-star-fill star-ic"></i> ', $fillStar) : '') . '
                            ' . ($isHalf > 0 ? str_repeat('<i class="bi bi-star-half star-ic"></i> ', $isHalf) : '') . '
                            ' . ($noFillStar > 0 ? str_repeat('<i class="bi bi-star star-ic"></i> ', $noFillStar) : '') . '
                            <span>' .  $numStar . '/5</span>
                            ';
                        ?>
                    </div>
                    <div class="price">$<?= $product->price ?></div>
                    <p class="quantity">Remaining: <?= $product->quantity ?> item(s)</p>
                    <p class="description"><?= $product->description ?></p>
                </div>

                <div class="cart-section">
                    <div class="quantity-modifier">
                        <div class="quantity">1</div>
                        <i class="bi bi-dash-lg minus" onclick="changeQuantity('<?= $product->id ?>', -1)"></i>
                        <i class="bi bi-plus-lg plus" onclick="changeQuantity('<?= $product->id ?>', 1)"></i>
                    </div>

                    <button class="add-cart-btn" data-product-id="<?= $product->id ?>" onclick="addProductIntoCart(event)">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>


        <div class="tabs">
            <div class="top">
                <div class="active" data-id-toggle="#detailTab" data-tab="0">Product Details</div>
                <div data-id-toggle="#reviewTab" data-tab="1">Rating & Reviews</div>
            </div>


            <div class="product-tab" id="detailTab">
                <?= ((isset($product->details) && strlen($product->details) > 0) ? $product->details : "No further details!")  ?>
            </div>

            <div class="review-tab disabled" id="reviewTab">
                <div class="top">
                    <h4 class="title">Reviews <span id="numReview">(0)</span></h4>
                    <div class="btns">
                        <button class="filter-btn">
                            <i class="bi bi-sliders2-vertical"></i>
                        </button>
                        <button class="write-btn" onclick="showReviewModal(event, '<?= $product->id ?>')">Write a Review</button>
                    </div>
                </div>
                <div class="review-container">
                    <div class="reviews" data-product-id="<?= $product->id ?>"></div>
                </div>

                <div class="more-review-btn-container">
                    <button id="moreReviewBtn">Load More Reviews</button>
                </div>
            </div>
        </div>
    </div>


</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>