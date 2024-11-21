const quantityContainer = document.querySelector(".cart-section .quantity-modifier .quantity");


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

$(document).ready(setHeightReviewContainer);


$(window).on('load', setHeightReviewContainer);




function addProductIntoCart(event) {
    const productId = event.target.dataset.productId;
    const quantity = quantityContainer.innerHTML;

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
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            // console.log(JSON.parse(xhr.responseText));
        }
    });
}
