const quantityContainer = document.querySelector(".quantity-modifier .quantity");
state.quantity = 1;

const modalContainer = document.querySelector("#modalContainer");
const reviewsSelector = document.querySelector(".review-container .reviews");
state.reviews = [];

const numReviewSelector = document.querySelector("#numReview");

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


function showReviewModal(event, productId) {
    modalContainer.innerHTML = `
        <div id="reviewModal" class="modal" data-close-modal="#reviewModal">
            <div class="modal-content">
            <form id="reviewForm" onsubmit="sendReview(event)">
                <input type="hidden" name="productId" value="${productId}">
                <div class="rating">
                    <input type="radio" name="rate" id="rate-5s" value="100">
                    <label for="rate-5s" class="bi bi-star-fill"></label>
                    <input type="radio" name="rate" id="rate-4s" value="80">
                    <label for="rate-4s" class="bi bi-star-fill"></label>
                    <input type="radio" name="rate" id="rate-3s" value="60">
                    <label for="rate-3s" class="bi bi-star-fill"></label>
                    <input type="radio" name="rate" id="rate-2s" value="40">
                    <label for="rate-2s" class="bi bi-star-fill"></label>
                    <input type="radio" name="rate" id="rate-1s" value="20">
                    <label for="rate-1s" class="bi bi-star-fill"></label>
                </div>
                <textarea placeholder="Write your review" id="reviewTextInput" name="comment"></textarea>
            </form>
            </div>
            <div class="modal-action">
                <button class="w-50 btn btn-secondary btn-close" onclick="closeReviewModal(event)">Cancel</button>
                <button class="w-50 btn btn-primary btn-submit" onclick="submitReviewForm(event)">Send</button>
            </div>
            <div class="modal-close">
                <i class=" bi bi-x-lg" onclick="closeReviewModal(event)"></i>
            </div>
        </div>`;

    const modal = modalContainer.querySelector(".modal");
    setTimeout(() => {
        modal.classList.add("show");
    }, 100);
}

function closeReviewModal(event) {
    const modal = event.target.closest(".modal");
    modal.classList.remove("show");
    setTimeout(() => {
        modal?.remove();
    }, 300);
}

function submitReviewForm(event) {
    const form = $("#reviewForm")[0];
    $(form).trigger("submit");
}

function sendReview(event) {
    event.preventDefault();

    const form = new FormData(event.target);
    for (var pair of form.entries()) {
        console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    }

    $.ajax({
        url: `/api/reviews`,
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
        },
        error: function (xhr, status, error) {
            const errors = JSON.parse(xhr.responseText).errors;
            if (errors?.user) window.location.href = "/login";
        }
    });
}



function loadReviews(response) {
    const reviews = response.reviews;
    if (!reviews || reviews.length <= 0) return;

    numReviewSelector.innerHTML = ` (${response.count})`;

    const content = reviews.reduce((content, review) => {
        const stars = review.rate / 20;
        const noStars = 5 - stars;
        return content + `
            <div class="review-card">
                <div class="card">
                    <div class="stars">
                        ${`<i class="bi bi-star-fill star-ic"></i> `.repeat(stars)}
                        ${`<i class="bi bi-star star-ic"></i> `.repeat(noStars)}                       
                    </div>
                    <div class="author-name">
                        ${review.username} <i class="bi bi-check-circle-fill verify-ic"></i>
                    </div>
                    <p class="comment">
                        ${review.comment}
                    </p>
                    <small class="date">Post On ${review.created_at}</small>
                </div>
            </div>
        `;
    }, "");

    reviewsSelector.innerHTML = content;

}

function getReviews(page = 1) {
    const productId = reviewsSelector.dataset.productId;
    $.ajax({
        url: `/api/reviews?page=${page}&limit=4&productId=${productId}`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            loadReviews(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

getReviews();


