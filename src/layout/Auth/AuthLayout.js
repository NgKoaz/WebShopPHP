const selector = {};
let toastTimer = null;
selector.toastContainer = document.querySelector("#toastContainer");

function openToast(message) {
    selector.toastContainer.style.pointerEvents = "all";
    selector.toastContainer.innerHTML = `
    <div id="toast" class="toast">
        <div class="toast-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="toast-content">
            <div class="toast-title">Success!</div>
            <div class="toast-message">${message}</div>
        </div>
        <div class="toast-close" data-close-toast="#toast">
            <i class=" bi bi-x-lg" onclick="closeToast(event)"></i>
        </div>
    </div>`;

    const toast = document.querySelector(`#toast`);
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);
    toastTimer = setTimeout(() => {
        selector.toastContainer.style.pointerEvents = "";
        toast.classList.remove("show");
    }, 3000);
}

function closeToast(event) {
    const toastClose = event.target.closest(".toast-close");
    if (toastClose) {
        selector.toastContainer.style.pointerEvents = "";

        const toastId = toastClose.dataset.closeToast;
        const toast = document.querySelector(`${toastId}`);
        clearTimeout(toastTimer);
        toast.classList.remove("show");
    }
}