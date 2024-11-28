const newArrivalCardList = document.querySelector(".new-arrival .card-list");
const newTopSellingList = document.querySelector(".top-selling .card-list");

const prev = document.querySelector("#prevTestimonial");
const next = document.querySelector("#nextTestimonial");

const cardsContainer = document.querySelector(".testimonial-cards");
const cards = document.querySelectorAll(".testimonial-card-container");

const numberOfComment = cards.length;
let numCardDisplay = 3;
let startIndex = numCardDisplay;
let endIndex = numberOfComment - numCardDisplay * 2;
let cardIndex = 3;

document.addEventListener('DOMContentLoaded', () => {
    refreshNewArrivalList();
    refreshTopSellingList();

    handleResize();

    reloadTestiCard();
});

window.addEventListener("resize", handleResize)

function handleResize(event) {
    const width = window.innerWidth;
    if (width >= 1240) {
        numCardDisplay = 3;
        startIndex = numCardDisplay;
        endIndex = numberOfComment - numCardDisplay * 2;
        cardIndex = 3;
    } else if (width >= 768) {
        numCardDisplay = 2;
        startIndex = numCardDisplay;
        endIndex = numberOfComment - numCardDisplay * 2;
        cardIndex = 2;
    } else {
        numCardDisplay = 1;
        startIndex = numCardDisplay;
        endIndex = numberOfComment - numCardDisplay * 2;
        cardIndex = 1;
    }

    cardsContainer.style.width = `${100 * numberOfComment / numCardDisplay}%`;
}

function refreshNewArrivalList() {
    $.ajax({
        url: `/api/products?limit=4&options=%7B%0A%22order%22%3A%20%22created_at%22%0A%7D`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            const products = response.products;
            const content = products.reduce((content, product) => {
                return content + `
                <a class="card" href="/products/${product.slug}">
                    <img src="/public/images/newarrivals/cloth1.png">
                    <h3 class="title">${product.name}</h3>
                    <div class="stars">
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-half star-ic"></i>
                        <i class="bi bi-star star-ic"></i>
                        <span>5/5</span>
                    </div>
                    <div class="price">
                        $${product.price}
                    </div>
                </a>`;
            }, "");
            newArrivalCardList.innerHTML = content;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


function refreshTopSellingList() {
    $.ajax({
        url: `/api/products?limit=4&options=%7B%0A%22order%22%3A%20%22sold_number%22%0A%7D`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            const products = response.products;
            const content = products.reduce((content, product) => {
                return content + `
                <a class="card" href="/products/${product.slug}">
                    <img src="/public/images/newarrivals/cloth1.png">
                    <h3 class="title">${product.name}</h3>
                    <div class="stars">
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-fill star-ic"></i>
                        <i class="bi bi-star-half star-ic"></i>
                        <i class="bi bi-star star-ic"></i>
                        <span>5/5</span>
                    </div>
                    <div class="price">
                        $${product.price}
                    </div>
                </a>`;
            }, "");
            newTopSellingList.innerHTML = content;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}



prev.addEventListener("click", (event) => {
    cardIndex--;
    reloadTestiCard(true);
})

next.addEventListener("click", (event) => {
    cardIndex++;
    reloadTestiCard(true);
})


cardsContainer.addEventListener('transitionend', () => {
    const isTouchCeil = (cardIndex >= (numberOfComment - numCardDisplay));
    const isTouchFloor = cardIndex <= 0;
    if (!isTouchCeil && !isTouchFloor) return;
    cardIndex = (isTouchCeil) ? startIndex : endIndex;
    reloadTestiCard(false);
});

function reloadTestiCard(isSmooth) {
    cardsContainer.style.transition = isSmooth ? "" : "none";
    cardsContainer.style.transform = `translateX(-${100 / numberOfComment * cardIndex}%)`;
    [...cards].forEach((card, index) => {
        const isDisplay = cardIndex <= index && index < cardIndex + numCardDisplay;
        card.style.transition = isSmooth ? "" : "none";
        card.style.filter = isDisplay ? "" : "blur(1.5px)";
        card.style.opacity = isDisplay ? "" : "0.2";
    });
}

