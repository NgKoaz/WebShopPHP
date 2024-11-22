const quantityContainer = document.querySelector(".quantity-modifier .quantity");
state.quantity = 1;


function setHeightReviewContainer() {
    const reviewContainer = $('.review-container');
    const elements = $('.reviews');
    let maxHeight = 0;
    elements.each(function () {
        maxHeight = Math.max(maxHeight, $(this).outerHeight());
        console.log(maxHeight);
        reviewContainer.width("100%").height(maxHeight + "px");
    });
}




documentReadyCallback.push(setHeightReviewContainer);


function addProductIntoCart(event) {
    const productId = event.target.dataset.productId;
    const quantity = state.quantity;

    const form = new FormData();
    form.append("productId", productId);
    form.append("quantity", quantity);

    $.ajax({
        url: `/api/cart`,
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            state.quantity = 1;
            quantityContainer.innerHTML = state.quantity;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


function changeQuantity(productId, changeValue) {
    if (state.quantity + changeValue <= 0) return;
    state.quantity += changeValue;
    quantityContainer.innerHTML = state.quantity;
}
