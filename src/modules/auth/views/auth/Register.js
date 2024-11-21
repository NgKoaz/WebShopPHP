function handleSuccessLogin(response) {
    // console.log(response);
    window.location.href = "/login";
}

function handleErrorLogin(response) {
    inputs = {
        "firstname": document.querySelector("#firstnameInput"),
        "lastname": document.querySelector("#lastnameInput"),
        "username": document.querySelector("#usernameInput"),
        "email": document.querySelector("#emailInput"),
        "phone": document.querySelector("#phoneInput"),
        "password": document.querySelector("#passwordInput"),
        "confirmPassword": document.querySelector("#confirmInput"),
    }

    feedbacks = {
        "firstname": document.querySelector("#firstnameInvalidFeedback"),
        "lastname": document.querySelector("#lastnameInvalidFeedback"),
        "username": document.querySelector("#usernameInvalidFeedback"),
        "email": document.querySelector("#emailInvalidFeedback"),
        "phone": document.querySelector("#phoneInvalidFeedback"),
        "password": document.querySelector("#passwordInvalidFeedback"),
        "confirmPassword": document.querySelector("#confirmInvalidFeedback"),
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
}

function checkBeforeSend() {
    password = document.querySelector("#passwordInput").value
    confirmPassword = document.querySelector("#confirmInput").value
    if (password === confirmPassword) return true;
    response = {
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

    for (var pair of form.entries()) {
        console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    }

    $.ajax({
        url: "/api/register",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessLogin(response);
        },
        error: function (xhr, status, error) {
            handleErrorLogin(JSON.parse(xhr.responseText));
        }
    });
}