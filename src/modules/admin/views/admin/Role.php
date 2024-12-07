<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$viewData["title"] = "Product Manager";

$this
    ->addScript("Role.js")
    ->addStylesheet("Role.css");

ob_start();
?>

<div class="role-manager">

    <div class="accordion" id="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <b>Role Table</b>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordion">
                <div class="accordion-body">
                    <!-- Role Manipulation -->
                    <div class="row col-md-6">
                        <form id="createRoleForm" class="g-3 needs-validation" novalidate onsubmit="onCreateRoleSubmit(event)">
                            <div class="input-group mb-3 ">
                                <input type="text" id="nameInput" class="form-control" placeholder="New role name" name="name">
                                <button class="btn btn-primary">Create role</button>
                                <div id="nameInvalidFeedback" class="invalid-feedback d-flex"></div>
                            </div>
                        </form>
                    </div>
                    <table id="roleTable" class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- Role Manipulation END -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>