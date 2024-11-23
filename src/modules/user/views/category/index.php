<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");


$products = $viewData["products"] ?? [];
$ancestorCategories = $viewData["ancestorCategories"] ?? [];

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
                    <ul class="types">
                        <li>
                            <label for="t-shirts-type">T-shirts</label>
                            <input id="t-shirts-type" type="checkbox">
                        </li>
                        <li>
                            <label for="shorts-type">Shorts</label>
                            <input id="shorts-type" type="checkbox">
                        </li>
                        <li>
                            <label for="shirts-type">Shirts</label>
                            <input id="shirts-type" type="checkbox">

                        </li>
                        <li>
                            <label for="hoodie-type">Hoodie</label>
                            <input id="hoodie-type" type="checkbox">
                        </li>
                        <li>
                            <label for="jeans-type">Jeans</label>
                            <input id="jeans-type" type="checkbox">
                        </li>
                    </ul>

                    <div class="price">
                        <h4 class="title">Price</h4>
                        <div>Price Slide</div>
                    </div>


                    <!-- <div class="colors">
                    <h4 class="title">Colors</h4>
                    <div>Color Picker</div>
                </div>


                <div class="sizes">
                    <h4 class="title">Size</h4>
                    <div class="size-options">
                        <button>123</button>
                        <button>123</button>
                        <button>123</button>
                    </div>
                </div> -->

                    <div class="styles">
                        <h4 class="title">Dress Style</h4>
                        <ul>
                            <li>
                                <label for="casual-style">Casual</label>
                                <input id="casual-style" type="checkbox">
                            </li>
                            <li>
                                <label for="formal-style">Formal</label>
                                <input id="formal-style" type="checkbox">
                            </li>
                            <li>
                                <label for="party-style">Party</label>
                                <input id="party-style" type="checkbox">

                            </li>
                            <li>
                                <label for="gym-style">Gym</label>
                                <input id="gym-style" type="checkbox">
                            </li>
                        </ul>
                    </div>

                    <button class="apply-btn">Apply</button>
                </div>
            </div>

        </div>
        <div class="search-area">
            <div class="top">
                <h4 class="main-title left">Casual</h4>
                <div class="right">
                    <div>Showing 1-10 of 100 <span>Sort by: Most Popular</span></div>
                    <button class="filter-btn"><i class="bi bi-sliders2-vertical"></i></button>
                </div>
            </div>

            <div class="items">
                <?php
                foreach ($products as $product) {
                    var_dump($product);
                }
                ?>
                <div class="card">
                    <img src="/public/images/newarrivals/cloth1.png">
                    <h3 class="title">T-SHIRT WITH TAPE DETAILS</h3>
                    <div class="stars">
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-half star-ic"></i>
                        <i class="bi bi-star star-ic"></i>
                        <span>5/5</span>
                    </div>
                    <div class="price">
                        $140
                    </div>
                </div>


            </div>

            <hr>

            <div class="pagination">
                <a class="prev">-Previous</a>
                <div>
                    <a class="page-num"><span>1</span></a>
                    <a class="page-num"><span>2</span></a>
                    <a class="page-num"><span>3</span></a>
                    <a class="page-num"><span>344</span></a>
                </div>
                <a class="next">Next </a>
            </div>
        </div>
    </div>

    <div class="overlay"></div>
    <div class="filter-modal">
        <div class="top">
            <h4 class="title">Filters</h4>
            <button class="close-btn"><i class="bi bi-x"></i></button>
        </div>
        <div class="bottom">
            <div class="types">
                <div>
                    <div>T-shirts</div>
                    <input type="checkbox">
                </div>
                <div>
                    <div>Shorts</div>
                    <input type="checkbox">
                </div>
                <div>
                    <div>Shirts</div>
                    <input type="checkbox">
                </div>
                <div>
                    <div>Hoodie</div>
                    <input type="checkbox">
                </div>
                <div>
                    <div>Jeans</div>
                    <input type="checkbox">
                </div>
            </div>

            <!-- <hr> -->

            <div class="price">
                <div class="title">Price</div>
                <div>Price Slide</div>
            </div>

            <!-- <hr>

        <div class="droplist">
            <h4 class="title">Colors</h4>
            <div>Color Picker</div>
        </div>

        <hr>

        <div class="droplist">
            <h4 class="title">Size</h4>
            <div class="size-options">
                <button>123</button>
                <button>123</button>
                <button>123</button>
            </div>
        </div> -->

            <!-- <hr> -->

            <div class="options">
                <h4 class="title">Dress Style</h4>
                <div class="styles">
                    <div>
                        <div>Casual</div>
                        <input type="checkbox">
                    </div>
                    <div>
                        <div>Formal</div>
                        <input type="checkbox">
                    </div>
                    <div>
                        <div>Party</div>
                        <input type="checkbox">
                    </div>
                    <div>
                        <div>Gym</div>
                        <input type="checkbox">
                    </div>
                </div>
                <button class="aplly-btn">Apply Filter</button>
            </div>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>