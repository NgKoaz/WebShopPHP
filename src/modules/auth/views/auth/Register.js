const inputs = {
    "firstname": document.querySelector("#firstnameInput"),
    "lastname": document.querySelector("#lastnameInput"),
    "username": document.querySelector("#usernameInput"),
    "email": document.querySelector("#emailInput"),
    "phone": document.querySelector("#phoneInput"),
    "password": document.querySelector("#passwordInput"),
    "confirmPassword": document.querySelector("#confirmInput"),
}

const feedbacks = {
    "firstname": document.querySelector("#firstnameInvalidFeedback"),
    "lastname": document.querySelector("#lastnameInvalidFeedback"),
    "username": document.querySelector("#usernameInvalidFeedback"),
    "email": document.querySelector("#emailInvalidFeedback"),
    "phone": document.querySelector("#phoneInvalidFeedback"),
    "password": document.querySelector("#passwordInvalidFeedback"),
    "confirmPassword": document.querySelector("#confirmInvalidFeedback"),
}


function handleSuccessLogin(response) {
    clearErrors();
    clearInputs();
    openToast("Registered! Moving to login page in 2 seconds");
    setTimeout(() => window.location.href = "/login", 2000);
}

function clearInputs() {
    Object.keys(inputs).forEach(key => {
        inputs[key].value = "";
        feedbacks[key].innerHTML = "";
    });
}

function clearErrors() {
    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    });
}

function handleErrorLogin(response) {
    clearErrors();

    if (response?.errors === null) return;

    errors = response.errors;
    Object.keys(errors).forEach(errKey => {
        // console.log(errKey, errors[errKey]);
        if (inputs[errKey]) {
            inputs[errKey].classList.add("is-invalid");
            feedbacks[errKey].innerHTML = errors[errKey].join("<br>");
        }
    })
}

function checkBeforeSend() {
    const password = document.querySelector("#passwordInput").value
    const confirmPassword = document.querySelector("#confirmInput").value
    if (password === confirmPassword) return true;
    const response = {
        errors: {
            confirmPassword: ["Confirm password doesn't match!"]
        }
    }
    handleErrorLogin(response)
    return false;
}

function onSubmitForm(event) {
    event.preventDefault();

    if (!checkBeforeSend()) return;

    const form = new FormData(event.target);

    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    // }

    $.ajax({
        url: "/api/register",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            handleSuccessLogin(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            handleErrorLogin(JSON.parse(xhr.responseText));
        }
    });
}