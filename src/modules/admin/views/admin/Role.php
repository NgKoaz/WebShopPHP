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


    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modalCloseBtn">Close</button>
                    <button type="button" class="btn btn-danger" id="modalSubmitBtn">Delete</button>
                </div>
            </div>
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
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>