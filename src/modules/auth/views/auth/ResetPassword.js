const inputs = {
    "password": document.querySelector("#passwordInput"),
    "confirm": document.querySelector("#confirmInput"),
}

const feedbacks = {
    "password": document.querySelector("#passwordInputFeedback"),
    "confirm": document.querySelector("#confirmPasswordFeedback"),
}


function clearInputs() {
    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        inputs[key].value = "";
        feedbacks[key].innerHTML = "";
    });
}

function handleErrorLogin(response) {
    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    });

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

    if (inputs["confirm"].value !== inputs["password"].value) {
        handleErrorLogin({ "code": 400, "errors": { "confirm": ["Password doesn't match!"] } })
        return;
    }

    const form = new FormData(event.target);
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    params.forEach((value, key) => {
        form.append(key, value);
    });

    $.ajax({
        url: "/api/reset-password",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            openToast(response.message + ". Move to login page in 2 seconds");
            clearInputs();
            setTimeout(() => {
                window.location.href = "/login";
            }, 2000);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            handleErrorLogin(JSON.parse(xhr.responseText));
        }
    });
}