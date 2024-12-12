<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$viewData["title"] = "Category Manager";

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
                <input type="checkbox" class="btn-check" id="draggableBtn" onchange="toggleDraggable()">
                <label class="btn btn-outline-danger" for="draggableBtn">Draggable</label>
            </div>
            <div class="btn-group" role="group">
                <input type="checkbox" class="btn-check" id="autoSlugBtnCheck" checked onchange="toggleAutoGenerate()">
                <label class="btn btn-outline-success" for="autoSlugBtnCheck">Auto Generate Slug</label>
            </div>
        </div>
    </div>

    <div class="categories">
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>