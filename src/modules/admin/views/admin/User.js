const tbody = document.querySelector("table tbody");
const modal = document.querySelector("#modal");
const modalTitle = document.querySelector("#modal .modal-title")
const modalBody = document.querySelector("#modal .modal-body")
const submitModalButton = document.querySelector("#submitModalButton");
const closeModalButton = document.querySelector("#closeModalButton");

const pagination = document.querySelector(".pagination")

const toastLive = document.getElementById('liveToast');
const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLive);
const toastTitle = document.querySelector(".toast .toast-title");
const toastBody = document.querySelector(".toast .toast-body");
const toastRect = document.querySelector(".toast rect");



const greenColor = "#32ff7e";
const darkGreenColor = "#3ae374";
const redColor = "#ff3838";


tempUsers = [];
isDeletedState = false;
currentPageState = 1;
totalPagesState = 1;


// START OF TOAST
function showErrorToast(title, message) {
    toastRect.setAttribute('fill', redColor);
    toastTitle.innerHTML = title;
    toastTitle.style.color = redColor;
    toastBody.innerHTML = message;
    toastBootstrap.show();
}

function showSuccessToast(title, message) {
    toastRect.setAttribute('fill', greenColor);
    toastTitle.innerHTML = title;
    toastTitle.style.color = greenColor;
    toastBody.innerHTML = message;
    toastBootstrap.show();
}
// END OF TOAST


// START TABLE
function updateTable(data) {
    users = data["users"] ?? [];
    tempUsers = users;

    tbody.innerHTML = ""
    users.forEach(user => {
        tbody.innerHTML += `
        <tr>
            <th scope="row">${user.id}</th>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>${user.phoneNumber}</td>
            <th style="color: ${user.isDeleted ? redColor : darkGreenColor}">${user.isDeleted ? "Deactive" : "Active"}</th>
            <td data-id="${user.id}">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal" onclick="showDetailModal(event)">
                    Detail
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal" onclick="showEditModal(event)">
                    Edit
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal" onclick="showDeleteModal(event)">
                    Delete
                </button>
            </td>
        </tr>`
    });
}

function changePage(page) {
    if (page <= 0 || page > toastTitle || page === currentPageState) return;
    refreshDataForTable(page);
}

function updatePagination(data) {
    totalPages = data["totalPages"] ?? 1;
    currentPage = data["currentPage"] ?? 1;

    currentPageState = currentPage;
    totalPagesState = totalPages

    page = 1;
    pagination.innerHTML = `<li class="page-item ${currentPageState <= 1 ? "disabled" : ""}"><a class="page-link" href="#" onclick="changePage(${currentPageState - 1})">Previous</a></li>`;
    while (page <= totalPages) {
        pagination.innerHTML += `<li class="page-item ${currentPageState == page ? "active" : ""}"><a class="page-link" href="#" onclick="changePage(${page})">${page}</a></li>`;
        page++;
    }
    pagination.innerHTML += `<li class="page-item ${currentPageState >= totalPagesState ? "disabled" : ""}"><a class="page-link" href="#" onclick="changePage(${currentPageState + 1})">Next</a></li>`;
}

function refreshDataForTable(page = 1, limit = 12) {
    $.ajax({
        url: `/api/admin/users?page=${page}&limit=${limit}`,
        method: 'GET',
        success: function (response) {
            updateTable(response);
            updatePagination(response);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", error);
        }
    });
}

refreshDataForTable();


// END TABLE


// MODAL 
function closeModal() {
    closeModalButton.click();
}

function updateModalSubmitButton(content, setDeleteButton) {
    submitModalButton.classList.remove("btn-primary");
    submitModalButton.classList.remove("btn-danger");
    submitModalButton.style.display = "";
    if (content) submitModalButton.innerHTML = content;
    else submitModalButton.style.display = "none";
    if (setDeleteButton) {
        submitModalButton.classList.add("btn-danger");
    } else {
        submitModalButton.classList.add("btn-primary");
    }
}
// END MODAL 


// START OF CREATE MODAL
function showCreateModal() {
    modalTitle.innerHTML = "Create an user";
    modalBody.innerHTML = `
        <form id="modalForm" class="g-3 needs-validation" novalidate onsubmit="onCreateSubmit(event)">
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">First name</span>
                <input id="firstnameInput" type="text" placeholder="First name" class="form-control" 
                    name="firstname" value="">
                <span class="input-group-text">Last name</span>
                <input
                    id="lastnameInput" type="text" placeholder="Last name" class="form-control"
                    name="lastname"
                    value="">
                <div class="invalid-feedback d-flex">
                    <div id="firstnameInvalidFeedback" class="col-md-6">
                    </div>
                    <div id="lastnameInvalidFeedback" class="col-md-6">
                    </div>
                </div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Username</span>
                <input type="text" class="form-control" id="usernameInput" name="username" value="" placeholder="Username" autocomplete="username" required>
                <div id="usernameInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">@</span>
                <input type="email" class="form-control" id="emailInput" name="email" value="" placeholder="Email" required>
                <div id="emailInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Phone</span>
                <input type="text" class="form-control" id="phoneInput" name="phone" placeholder="0987654321" value="" required>
                <div id="phoneInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Password</span>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" value="" autocomplete="current-password" required>
                <div id="passwordInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Roles</span>
                <div id="roleSelect" class="select-menu form-control">
                    <div class="chosen-item">Nothing selected!</div>
                    <i class="bi bi-caret-down-fill select-caret"></i>
                    <ul class="option-list">
                        
                    </ul>
                </div>
                
                <div id="roleInvalidFeedback" class="invalid-feedback"></div>
            </div>
        </form>
    `;
    updateModalSubmitButton('Create', false);
    submitModalButton.onclick = () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }

    refreshRoleSelect("roleSelect")
}

function refreshDataRoleSelect(selectorId) {
    const optionList = document.querySelector(`#${selectorId} .option-list`);
    optionList.innerHTML = ``;

    $.ajax({
        url: "/api/admin/roles",
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            const content = response.reduce((content, role) => {
                return content + `
                    <li class="option">
                        <input type="checkbox" class="checkbox" name="role[]" value="${role.id}" data-name="${role.name}">
                        ${role.name}
                    </li>`
            }, "");
            optionList.innerHTML = content;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText)
        }
    });
}

function refreshRoleSelect(categoryId) {
    const select = document.querySelector(`#${categoryId}`);
    select.onclick = (event) => {
        const target = event.target;

        if (target.tagName === "DIV") {
            const optionList = select.querySelector(".option-list");
            optionList.classList.toggle("show");
        } else if (target.tagName === "LI") {
            const checkbox = target.querySelector("input");
            if (target.type !== "checkbox")
                checkbox.click()
            if (checkbox.checked) target.classList.add("active")
            else target.classList.remove("active")

            // RELOAD CHOSEN FIELD
            const chosenItem = select.querySelector(".chosen-item");
            const checkboxes = select.querySelectorAll(".option input");
            chosenItem.innerHTML = "";

            const content = [...checkboxes]
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.dataset.name)
                .join(", ");

            chosenItem.innerHTML = (content ? content : "Nothing selected!")
        }
    }

    documentOnClickCallback[categoryId] = (event) => {
        const selectMenu = event.target.closest(".select-menu");
        if (!selectMenu) {
            const optionList = document.querySelector(".select-menu .option-list");
            optionList?.classList.remove("show");
        }
    }

    refreshDataRoleSelect(categoryId);
}

function handleErrorCreateRequest(response) {
    if (response?.errors === null) return;

    inputs = {
        "firstname": document.querySelector("#firstnameInput"),
        "lastname": document.querySelector("#lastnameInput"),
        "username": document.querySelector("#usernameInput"),
        "email": document.querySelector("#emailInput"),
        "phone": document.querySelector("#phoneInput"),
        "password": document.querySelector("#passwordInput")
    }

    feedbacks = {
        "firstname": document.querySelector("#firstnameInvalidFeedback"),
        "lastname": document.querySelector("#lastnameInvalidFeedback"),
        "username": document.querySelector("#usernameInvalidFeedback"),
        "email": document.querySelector("#emailInvalidFeedback"),
        "phone": document.querySelector("#phoneInvalidFeedback"),
        "password": document.querySelector("#passwordInvalidFeedback")
    }

    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    errors = response.errors;
    Object.keys(errors).forEach(errKey => {
        // console.log(errKey, errors[errKey]);
        if (inputs[errKey]) {
            inputs[errKey].classList.add("is-invalid");
            feedbacks[errKey].innerHTML = errors[errKey].join("<br>");
        }
    })
    showErrorToast("Error!", "Check your error message!");
}

function handleSuccessCreateRequest(response) {
    refreshDataForTable();
    closeModal();
    showSuccessToast("Success!", "User has been created!");
}

function onCreateSubmit(event) {
    event.preventDefault();
    form = new FormData(event.target);
    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    // }
    $.ajax({
        url: "/api/admin/users/create",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessCreateRequest(response);
        },
        error: function (xhr, status, error) {
            handleErrorCreateRequest(JSON.parse(xhr.responseText));
        }
    });
}
// END OF CREATE MODAL


// START OF DETAIL MODAL
function showDetailModal(event) {
    parent = event.target.parentElement;
    userId = parent.dataset.id;

    user = tempUsers.filter(u => +u.id === +userId)?.[0];
    if (user === null) {
        showErrorToast("Error!", "Non-expected error. Reload page!");
        return;
    };

    modalTitle.innerHTML = `Detail User ID: ${user.id}`;
    modalBody.innerHTML = `
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Fistname</span>
            <input type="text" class="form-control" value="${user.firstName}" disabled>
            <span class="input-group-text" id="basic-addon1">Lastname</span>
            <input type="text" class="form-control" value="${user.lastName}" disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Username</span>
            <input type="text" class="form-control" value="${user.username}" disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">@</span>
            <input type="text" class="form-control" value=${user.email} disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Phone</span>
            <input type="text" class="form-control" value=${user.phoneNumber} disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Status</span>
            <input type="text" class="form-control" value=${user.isDeleted ? "Inactive" : "Active"} disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Create At</span>
            <input type="text" class="form-control" value=${user.createdAt.date} disabled>
        </div>
    `;
    updateModalSubmitButton("", false);
}
// END OF DETAIL MODAL


// START OF EDIT MODAL
function toggleIsDeleted(event) {
    event.preventDefault()
    isDeletedState = !isDeletedState;
    input = event.target.previousElementSibling;
    input.value = isDeletedState ? "Inactive" : "Active";
    input.style.color = isDeletedState ? redColor : darkGreenColor;

    // console.log(event.target);
    event.target.classList.remove("btn-danger");
    event.target.classList.remove("btn-success");
    event.target.classList.add((!isDeletedState ? "btn-danger" : "btn-success"));
    event.target.innerHTML = !isDeletedState ? "Inactive" : "Active";
}

function handleSuccessEditRequest(response) {
    refreshDataForTable();
    closeModal();
    showSuccessToast("Success!", "User has been editted!");
}

function handleErrorEditRequest(response) {
    if (response?.errors === null) return;

    inputs = {
        "firstname": document.querySelector("#firstnameInput"),
        "lastname": document.querySelector("#lastnameInput"),
        "phone": document.querySelector("#phoneInput"),
        "password": document.querySelector("#passwordInput")
    }

    feedbacks = {
        "firstname": document.querySelector("#firstnameInvalidFeedback"),
        "lastname": document.querySelector("#lastnameInvalidFeedback"),
        "phone": document.querySelector("#phoneInvalidFeedback"),
        "password": document.querySelector("#passwordInvalidFeedback")
    }

    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    errors = response.errors;
    Object.keys(errors).forEach(errKey => {
        // console.log(errKey, errors[errKey]);
        if (inputs[errKey]) {
            inputs[errKey].classList.add("is-invalid");
            feedbacks[errKey].innerHTML = errors[errKey].join("<br>");
        }
    })
    showErrorToast("Error!", "Check your error message!");
}

function onEditSubmit(event) {
    event.preventDefault();

    form = new FormData(event.target)
    form.append("isDeleted", isDeletedState ? 1 : 0);
    $.ajax({
        url: "/api/admin/users/edit",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessEditRequest(response);
        },
        error: function (xhr, status, error) {
            handleErrorEditRequest(JSON.parse(xhr.responseText));
        }
    });
}

function showEditModal(event) {
    parent = event.target.parentElement;
    userId = parent.dataset.id;
    user = tempUsers.filter(u => +u.id === +userId)?.[0];
    if (user === null) {
        showErrorToast("Error!", "Non-expected error. Reload page!");
        return;
    };
    isDeletedState = user.isDeleted;
    modalTitle.innerHTML = `Edit User ID: ${user.id}`;
    modalBody.innerHTML = `
        <form id="modalForm" class="g-3 needs-validation" novalidate onsubmit="onEditSubmit(event)">
            <input type="hidden" name="id" value="  ${user.id}">
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">Fistname</span>
                <input id="firstnameInput" type="text" class="form-control" value="${user.firstName}" placeholder="Firstname" name="firstname">
                <span class="input-group-text" id="basic-addon1">Lastname</span>
                <input id="lastnameInput" type="text" class="form-control" value="${user.lastName}" placeholder="Lastname" name="lastname">
                <div class="invalid-feedback d-flex">
                    <div id="firstnameInvalidFeedback" class="col-md-6">
                    </div>
                    <div id="lastnameInvalidFeedback" class="col-md-6">
                    </div>
                </div>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text" id="basic-addon1">Username</span>
                <input type="text" class="form-control" value="${user.username}" disabled>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" value=${user.email} disabled>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">New password</span>
                <input type="password" id="passwordInput" class="form-control" value="" placeholder="New password" name="password">
                <div id="passwordInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text" name="phone">Phone</span>
                <input type="text" id="phoneInput" class="form-control" value=${user.phoneNumber} placeholder="Phone" name="phone">
                <div id="phoneInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text" name="status">Status</span>
                <input type="text" class="form-control" 
                    style="font-weight: 700; 
                            color: ${user.isDeleted ? redColor : darkGreenColor};"
                            value=${user.isDeleted ? "Inactive" : "Active"} 
                    disabled>
                <button class="btn ${!user.isDeleted ? "btn-danger" : "btn-success"}" onclick="toggleIsDeleted(event)">${!user.isDeleted ? "Inactive" : "Active"}</button>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">Create At</span>
                <input type="text" class="form-control" value=${user.createdAt.date} disabled>
            </div>
        </form>
    `;
    updateModalSubmitButton("Edit", false);
    submitModalButton.onclick = () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }
}
// END OF EDIT MODAL


// START OF DELETE MODAL
function handleSuccessDeleteRequest(response) {
    refreshDataForTable();
    closeModal();
    showSuccessToast("Success!", "User has been deleted!");
}

function handleErrorDeleteRequest(response) {
    showErrorToast("Error!", "Non-expected error. Please, reload page!");
}

function onDeleteSubmit(event) {
    event.preventDefault();
    form = new FormData(event.target)
    $.ajax({
        url: "/api/admin/users/delete",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessDeleteRequest(response);
        },
        error: function (xhr, status, error) {
            handleErrorDeleteRequest(JSON.parse(xhr.responseText));
        }
    });
}

function showDeleteModal(event) {
    parent = event.target.parentElement;
    userId = parent.dataset.id;
    user = tempUsers.filter(u => +u.id === +userId)?.[0];
    if (user === null) {
        showErrorToast("Error!", "Non-expected error. Reload page!");
        return;
    };
    isDeletedState = user.isDeleted;
    modalTitle.innerHTML = `Delete User ID: ${user.id}`;
    modalBody.innerHTML = `
        <form id="modalForm" class="g-3 needs-validation" novalidate onsubmit="onDeleteSubmit(event)">
            <input type="hidden" name="id" value="${user.id}">
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text" id="basic-addon1">Username</span>
                <input type="text" class="form-control" value="${user.username}" disabled>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">@</span>
                <input type="text" class="form-control" value=${user.email} disabled>
            </div>
        </form>
    `;
    updateModalSubmitButton("Delete", true);
    submitModalButton.onclick = () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }
}
// END OF DELETE MODAL

