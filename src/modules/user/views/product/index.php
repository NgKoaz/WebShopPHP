<?php

use App\core\App;
use App\Entities\Product;

$title = "Bach Khoa Clothes";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");


/**
 * @var Product
 */
$product = $viewData["product"];
$ancestorCategories = $viewData["ancestorCategories"] ?? [];
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
                    <img src="/public/images/product/lg.png">
                </div>
                <div class="small-images">
                    <div class="small-image-container"><img src="/public/images/product/sm2.png"></div>
                    <div class="small-image-container"><img src="/public/images/product/sm2.png"></div>
                    <div class="small-image-container"><img src="/public/images/product/sm3.png"></div>
                </div>
            </div>

            <div class="right">
                <div class="product-info">
                    <h4 class="title"><?= $product->name ?></h4>
                    <div class="stars">
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-half star-ic"></i>
                        <i class="bi bi-star star-ic"></i>
                    </div>
                    <div class="price">$<?= $product->price ?></div>
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

        <hr>

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