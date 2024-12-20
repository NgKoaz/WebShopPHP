const methods = document.querySelectorAll(".method");

document.addEventListener("DOMContentLoaded", () => {
    [...methods].forEach(method => {
        method.addEventListener("click", () => {
            const methodSelector = method.querySelector("input[type='radio']");
            methodSelector.click();
        });
    });
});
