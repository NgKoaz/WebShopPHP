const newArrivalCardList = document.querySelector(".new-arrival .card-list");


documentReadyCallback.push(() => {
    const browseCards = document.querySelectorAll(".styles .style-card")
    browseCards.forEach(card => {
        card.onclick = onClickBrowseCard;
    })

    refreshNewArrivalList();
});

function onClickBrowseCard(event) {
    const target = event.target.closest(".style-card");
    console.log(target);
    window.location.href = target.dataset.href;
}


function refreshNewArrivalList() {
    $.ajax({
        url: `/api/products?limit=4&options=%7B%0A%22order%22%3A%20%22createdAt%22%0A%7D`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            const products = response.products;
            const content = products.reduce((content, product) => {
                return content + `
                <div class="card" data-href="/products/${product.slug}">
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
                </div>`;
            }, "");

            newArrivalCardList.innerHTML = content;

            document.querySelectorAll("[data-href]").forEach(o => {
                o.onclick = () => {
                    window.location.href = o.dataset.href;
                }
            })
        },
        error: function (xhr, status, error) {
            // console.log(xhr.responseText);
            console.log(JSON.parse(xhr.responseText));
        }
    });
}


// function refreshNewArrivalList() {
//     $.ajax({
//         url: `/api/products?limit=4&options=%7B%0A%22order%22%3A%20%22createdAt%22%0A%7D`,
//         method: "GET",
//         processData: false,
//         contentType: false,
//         success: function (response) {
//             const products = response.products;
//             const content = products.reduce((content, product) => {
//                 return content + `
//                 <div class="card" data-href="/products/${product.slug}">
//                     <img src="/public/images/newarrivals/cloth1.png">
//                     <h3 class="title">${product.name}</h3>
//                     <div class="stars">
//                         <i class="bi bi-star-fill star-ic"></i>
//                         <i class="bi bi-star-fill star-ic"></i>
//                         <i class="bi bi-star-fill star-ic"></i>
//                         <i class="bi bi-star-half star-ic"></i>
//                         <i class="bi bi-star star-ic"></i>
//                         <span>5/5</span>
//                     </div>
//                     <div class="price">
//                         $${product.price}
//                     </div>
//                 </div>`;
//             }, "");

//             newArrivalCardList.innerHTML = content;
//         },
//         error: function (xhr, status, error) {
//             // console.log(xhr.responseText);
//             console.log(JSON.parse(xhr.responseText));
//         }
//     });
// }
