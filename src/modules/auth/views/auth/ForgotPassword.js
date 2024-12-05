const inputs = {
    "email": document.querySelector("#emailInput"),
}

const feedbacks = {
    "email": document.querySelector("#emailInputFeedback"),
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

    const form = new FormData(event.target);

    $.ajax({
        url: "/api/forgot-password",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            openToast(response.message);
            clearInputs();
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            handleErrorLogin(JSON.parse(xhr.responseText));
        }
    });
}