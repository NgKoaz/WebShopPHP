selector = {}
state = {}
state.roles = []
storage = {}

selector.tBody = document.querySelector("#roleTable tbody");

selector.toastLive = document.getElementById('liveToast');
selector.toastBootstrap = bootstrap.Toast.getOrCreateInstance(selector.toastLive);
selector.toastTitle = document.querySelector(".toast .toast-title");
selector.toastBody = document.querySelector(".toast .toast-body");
selector.toastRect = document.querySelector(".toast rect");

storage.greenColor = "#32ff7e";
storage.darkGreenColor = "#3ae374";
storage.redColor = "#ff3838";

//#region TOAST
function showErrorToast(title, message) {
    selector.toastRect.setAttribute('fill', storage.redColor);
    selector.toastTitle.innerHTML = title;
    selector.toastTitle.style.color = storage.redColor;
    selector.toastBody.innerHTML = message;
    selector.toastBootstrap.show();
}

function showSuccessToast(title, message) {
    selector.toastRect.setAttribute('fill', storage.greenColor);
    selector.toastTitle.innerHTML = title;
    selector.toastTitle.style.color = storage.greenColor;
    selector.toastBody.innerHTML = message;
    selector.toastBootstrap.show();
}
//#endregion

function handleErrorEditRoleRequest(response, element, value) {
    element.innerHTML = value;
    const content = Object.keys(response.errors).reduce((content, errKey) => {
        return content + response.errors[errKey].join("<br>");
    }, "");
    showErrorToast("Error!", content);
}

function onBlur(event, roleId, previousValue) {
    const newValue = event.target.value;
    const parentElement = event.target.parentElement;
    parentElement.innerHTML = newValue.trim();
    if (newValue === previousValue) return;
    const form = new FormData();
    form.append("id", roleId);
    form.append("name", newValue);
    $.ajax({
        url: "/api/admin/roles/update",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            showSuccessToast("Success!", "Category name has been updated!");
        },
        error: function (xhr, status, error) {
            handleErrorEditRoleRequest(JSON.parse(xhr.responseText), parentElement, previousValue);
        }
    });
}

function onEdit(event, roleId) {
    const nameField = event.target.parentElement.previousElementSibling;
    const nameValue = nameField.innerHTML.trim();
    nameField.innerHTML = `<input type="text" class="form-control" value="${nameValue}" onblur="onBlur(event, '${roleId}','${nameValue}')">`;
    const inputField = nameField.querySelector('input');
    inputField.focus();
}

function handleSuccessDeleteRequest(response) {
    showSuccessToast("Success!", "Category name has been deleted!");
    refreshRoleTable();
}

function onDelete(roleId) {
    const form = new FormData();
    form.append("id", roleId);

    $.ajax({
        url: "/api/admin/roles/delete",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            handleSuccessDeleteRequest(response);
        },
        error: function (xhr, status, error) {
            const response = JSON.parse(xhr.responseText);
            showErrorToast("Error!", (response?.errors?.message) ? response.errors.message : "Non-expected error. Please, reload page!");
        }
    });
}

function openConfirmDeleteModal(roleId) {
    const modalSubmitBtn = document.querySelector("#modalSubmitBtn");
    const modalTitle = document.querySelector("#modal .modal-title");
    const modalBody = document.querySelector("#modal .modal-body");

    modalTitle.innerHTML = "Delete role";
    modalBody.innerHTML = `Do you want to delete role has id [${roleId}]?`;

    modalSubmitBtn.onclick = () => {
        onDelete(roleId);
        document.querySelector("#modalCloseBtn")?.click();
    }
}

function renderRoleTable(response) {
    state.roles = response;

    selector.tBody.innerHTML = '';
    state.roles.forEach(r => {
        selector.tBody.innerHTML += `
            <tr>
                <th scope="row">${r.id}</th>
                <td>${r.name}</td>
                <td>
                    <button class="btn btn-success" onclick="onEdit(event, '${r.id}')">Edit</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal" onclick="openConfirmDeleteModal('${r.id}')">
                        Delete
                    </button>
                </td>
            </tr>
        `
    })
}

function refreshRoleTable() {
    $.ajax({
        url: "/api/admin/roles",
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            // console.log(response);
            renderRoleTable(response);
        },
        error: function (xhr, status, error) {
            showErrorToast("Error!", "Non-expected error. Reload page!");
        }
    });
}

refreshRoleTable()



function handleSuccessRequest(response) {
    const inputObj = document.querySelector("#nameInput");
    inputObj.value = "";
    inputObj.classList.remove("is-invalid");
    document.querySelector("#nameInvalidFeedback").innerHTML = "";
    showSuccessToast("Success!", "Role has been created");
    refreshRoleTable();
}

function handleErrorRoleRequest(response) {
    inputs = {
        "name": document.querySelector("#nameInput"),
    }

    feedbacks = {
        "name": document.querySelector("#nameInvalidFeedback"),
    }

    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    if (response?.errors === null) return;

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

function onCreateRoleSubmit(event) {
    event.preventDefault();
    form = new FormData(event.target);
    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    // }
    $.ajax({
        url: "/api/admin/roles/create",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessRequest();
        },
        error: function (xhr, status, error) {
            handleErrorRoleRequest(JSON.parse(xhr.responseText));
        }
    });
}