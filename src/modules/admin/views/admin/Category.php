<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$viewData["title"] = "Product Manager";

$this
    ->addScript("Category.js")
    ->addStylesheet("Category.css");

ob_start();
?>

<div class="category-manager">
    <div class="actions d-flex justify-content-between">
        <div>
            <button class="btn btn-primary" onclick="addCategory()">
                Create new category
            </button>
        </div>
        <div>
            <div class="btn-group" role="group">
                <input type="checkbox" class="btn-check" id="autoSlugBtnCheck" checked onchange="toggleAutoGenerate()">
                <label class="btn btn-outline-success" for="autoSlugBtnCheck">Auto Generate Slug</label>
            </div>
        </div>
    </div>

    <div class="categories">
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
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>