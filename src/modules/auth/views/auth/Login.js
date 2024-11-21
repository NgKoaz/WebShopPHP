function handleSuccessLogin(response) {
    window.location.href = "/";
}

function handleErrorLogin(response) {
    inputs = {
        "username": document.querySelector("#usernameInput"),
        "password": document.querySelector("#passwordInput"),
    }

    feedbacks = {
        "username": document.querySelector("#usernameInvalidFeedback"),
        "password": document.querySelector("#passwordInvalidFeedback"),
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

function onSubmitForm(event) {
    event.preventDefault();
    const form = new FormData(event.target);

    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    // }

    $.ajax({
        url: "/api/login",
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