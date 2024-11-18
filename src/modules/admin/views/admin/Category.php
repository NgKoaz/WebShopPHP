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
    <!-- Button trigger modal -->
    <!-- <button draggable="true" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Create new category
    </button> -->

    <div class="actions d-flex justify-content-between">
        <div>
            <button class="btn btn-primary" onclick="addCategory()">
                Create new category
            </button>
        </div>
        <div>
            <div class="btn-group" role="group">
                <input type="checkbox" class="btn-check" id="autoSlugBtnCheck" checked onchange="toggleAutoGenerate()">
                <label class="btn btn-outline-primary" for="autoSlugBtnCheck">Auto Generate Slug</label>
            </div>
            <div class="btn-group" role="group">
                <input type="checkbox" class="btn-check" id="autoSaveBtnCheck" checked onchange="toggleAutoSave()">
                <label class="btn btn-outline-success" for="autoSaveBtnCheck">Auto Save</label>
            </div>
        </div>
    </div>

    <div class="categories">
        <!-- Category profab -->
        <!-- <div class="category row align-items-baseline">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text">Category</span>
                    <input type="text" class="form-control" placeholder="Category's name" aria-label="Category's name">
                    <span class="input-group-text">Slug</span>
                    <input type="text" class="form-control" placeholder="Slug" aria-label="Slug">
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-start align-items-center">
                <button class="btn btn-success">Save</button>

                <button class="plus-btn"><i class="bi bi-patch-plus-fill"></i></button>

                <button class="minus-btn"><i class="bi bi-patch-minus-fill"></i></button>
            </div>
        </div> -->
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>