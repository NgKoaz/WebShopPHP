// const itemsContainer = document.querySelector(".items");


// function refreshItems(page = 1, name = "", options = "") {
//     let queryString = `page=${page}`;
//     if (name) queryString += `&q=${name}`;
//     if (options) queryString += `&options=${options}`;

//     $.ajax({
//         url: `/api/products?${queryString}`,
//         method: "GET",
//         processData: false,
//         contentType: false,
//         success: function (response) {
//             console.log(response);
//             const products = response.products;
//             const currentPage = response.currentPage;
//             const totalPages = response.totalPages;

//             const content = products.reduce((content, product) => {
//                 return content + `
//                 <a class= "card" href="/products/${product.slug}">
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
//                 </a>`
//             }, "");

//             itemsContainer.innerHTML = content;
//         },
//         error: function (xhr, status, error) {
//             // console.log(xhr.responseText);
//             console.log(JSON.parse(xhr.responseText));
//         }
//     });
// }

