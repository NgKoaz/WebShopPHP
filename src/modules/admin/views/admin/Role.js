selector = {}
state = {}
state.roles = []
storage = {}

selector.tBody = document.querySelector("#roleTable tbody");


function handleErrorEditRoleRequest(response, element, value) {
    element.innerHTML = value;
    const content = Object.keys(response.errors).reduce((content, errKey) => {
        return content + response.errors[errKey].join("<br>");
    }, "");
    Toast.gI().showError(content);
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
            Toast.gI().showSuccess("Category name has been updated!");
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
    Toast.gI().showSuccess("Category name has been deleted!");
    Modal.gI().close();
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
            Toast.gI().showError((response?.errors?.message) ? response.errors.message : "Non-expected error. Please, reload page!");
        }
    });
}

function openConfirmDeleteModal(roleId) {
    const title = "Delete role";
    const body = `Do you want to delete role has id [${roleId}]?`;
    Modal.gI().show(title, body, true, "Delete", "btn-danger", () => {
        onDelete(roleId);
        document.querySelector("#modalCloseBtn")?.click();
    }, null);
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
            Toast.gI().showError("Non-expected error. Reload page!");
        }
    });
}

refreshRoleTable();



function handleSuccessRequest(response) {
    const inputObj = document.querySelector("#nameInput");
    inputObj.value = "";
    inputObj.classList.remove("is-invalid");
    document.querySelector("#nameInvalidFeedback").innerHTML = "";
    Toast.gI().showSuccess("Role has been created");
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
    Toast.gI().showError("Check your error message!");
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