const quantityContainer = document.querySelector(".quantity-modifier .quantity");
const modalContainer = document.querySelector("#modalContainer");
const reviewsSelector = document.querySelector(".review-container .reviews");
const numReviewSelector = document.querySelector("#numReview");
const moreReviewBtn = document.querySelector("#moreReviewBtn");
const tabs = document.querySelectorAll("[data-tab]");

state.currentPage = 0;
state.totalPages = 0;
state.quantity = 1;
state.reviews = [];
state.tabIndex = 0;


document.addEventListener("DOMContentLoaded", () => {
    moreReviewBtn.addEventListener("click", (event) => {
        if (state.currentPage >= state.totalPages) {
            moreReviewBtn.classList.add("disabled");
            return;
        }
        getReviews(state.currentPage + 1);
    });

    [...tabs].forEach(tab => {
        tab.addEventListener("click", (event) => {
            const target = event.target;
            if (state.tabIndex === +target.dataset.tab) return;
            state.tabIndex = +target.dataset.tab;
            [...tabs].forEach(t => {
                t.classList.remove("active");
                const content = document.querySelector(t.dataset.idToggle);
                content.classList.add("disabled");
            });
            tab.classList.add("active");
            const tabContent = document.querySelector(tab.dataset.idToggle);
            tabContent.classList.remove("disabled");
        });
    });
});



//#region Add Product Into Cart
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
            openToast("Added!");

            const totalQuantity = response.reduce((quantity, product) => {
                return quantity + product.quantity;
            }, 0);
            document.querySelector("#numInCart").innerHTML = `${totalQuantity}`;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            openToast(response.message, true);
        }
    });
}

function changeQuantity(productId, changeValue) {
    if (state.quantity + changeValue <= 0) return;
    state.quantity += changeValue;
    quantityContainer.innerHTML = state.quantity;
}
//#endregion


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

function closeReviewModal() {
    const modal = document.querySelector("#reviewModal");
    modal.classList.remove("show");
    setTimeout(() => {
        modal.remove();
    }, 300);
}

function submitReviewForm(event) {
    const form = document.querySelector("#reviewForm");

    const formData = new FormData(form);
    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    // }

    $.ajax({
        url: `/api/reviews`,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            closeReviewModal();
            getReviews();
            openToast("Review has been sent!");
        },
        error: function (xhr, status, error) {
            const errors = JSON.parse(xhr.responseText).errors;
            if (errors?.user) window.location.href = "/login";
        }
    });
}

function loadReviews(response) {
    moreReviewBtn.classList.remove("disabled");

    const reviews = response.reviews;
    if (!reviews || reviews.length <= 0) {
        moreReviewBtn.classList.add("disabled");
        return;
    }

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

    if (response.currentPage == 1) {
        reviewsSelector.innerHTML = content;
    } else {
        reviewsSelector.innerHTML += content;
    }

    if (response.currentPage == response.totalPages) {
        moreReviewBtn.classList.add("disabled");
    }
}

function getReviews(page = 1) {
    const productId = reviewsSelector.dataset.productId;
    $.ajax({
        url: `/api/reviews?page=${page}&limit=6&productId=${productId}`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            state.totalPages = response.totalPages;
            state.currentPage = response.currentPage;
            loadReviews(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

getReviews();


