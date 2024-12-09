document.addEventListener("DOMContentLoaded", () => {
    PrepareOrderTable.gI().init();
    OrderTable.gI().init();
    Pagination.gI().init();
});



function PrepareOrderTable() {
    PrepareOrderTable.instance = null;
    this.tBody = "#prepareOrderTable tbody";
    PrepareOrderTable.orders = [];
    return this;
}

PrepareOrderTable.gI = function () {
    if (!PrepareOrderTable.instance) PrepareOrderTable.instance = new PrepareOrderTable();
    return PrepareOrderTable.instance;
}

PrepareOrderTable.prototype.init = function () {
    this.tBody = document.querySelector(this.tBody);
    this.fetch();
}

PrepareOrderTable.prototype.fetch = function () {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/admin/orders/prepare', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            this.updateView(response.data);
            PrepareOrderTable.orders = response.data.sort((a, b) => new Date(a.created_at) <= new Date(b.created_at) ? -1 : 1);
        } else {
            console.error('Error:', xhr.status, xhr.statusText);
        }
    }.bind(this);
    // Handle network errors
    xhr.onerror = function () {
        Toast.gI().showError('Fetch data failed!');
    };
    // Send the request
    xhr.send();
}

PrepareOrderTable.prototype.updateView = function (orders) {
    const content = orders.reduce((content, order) => {
        return content + `
            <tr data-bill-id=${order.id}>
                <td scope="col">
                    <span title="Click to copy" data-copy-text="${order.id}" style="cursor: pointer;">${Utility.shortString(order.id, 5)}</span>
                </td>
                <td scope="col">${order.created_at}</td>
                <td scope="col">${order.pay_method}</td>
                <td scope="col">${order.payment_service_provider}</td>
                <td scope="col">${order.order_status}</td>
                <td scope="col">${order.status}</td>
                <td scope="col">$${order.total_price}</td>
                <td scope="col">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal" data-action="view">
                        View products
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal" data-action="done">
                        Done
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal" data-action="cancel">
                        Cancel
                    </button>
                </td>
            </tr>
        `;
    }, "");

    // Copy ID features.
    this.tBody.innerHTML = content;
    [...this.tBody.querySelectorAll("[data-copy-text]")].forEach(obj => {
        const value = obj.dataset.copyText;
        obj.addEventListener("click", () => {
            navigator.clipboard.writeText(value).then(() => {
                Toast.gI().showSuccess("Copied to clipboard: " + value);
            }).catch(err => {
                console.error("Failed to copy:", err);
            });
        });
    });

    // Bind button events
    const buttons = this.tBody.querySelectorAll("button");
    buttons.forEach(button => {
        button.addEventListener("click", this.handleButtonEvent);
    });
}

PrepareOrderTable.prototype.handleButtonEvent = (event) => {
    const billId = event.target.closest("tr").dataset.billId;
    switch (event.target.dataset.action) {
        case "view":
            const viewTitle = `View order: ${billId}`;
            const viewBody = PrepareOrderTable.orders.reduce((content, order) => {
                if (order.id !== billId) return content + "";
                const products = JSON.parse(order.products);

                return content + products.reduce((subContent, product) => {
                    return subContent + `
                        <div class="card" style="width: 18rem">
                            <img src="${product?.image?.lg ? product?.image?.lg : `/public/images/no_image.webp`}" class="card-img-top" style="width:100%; max-height: 289px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text" style="color: ${Color.Red}; font-weight: 600;">$${product.price}</p>
                                <p class="card-text" style="color: rgb(0, 0, 0, 0.6);">Quantity: ${product.quantity}</p>
                                <a href="/admin/products/watch/${product.id}" class="btn btn-primary">Watch product</a>
                            </div>
                        </div>
                    `;
                }, "");
            }, "");

            const divContainer = `
                <div class="d-flex flex-wrap justify-content-around gap-3">
                    ${viewBody}
                </div>
            `;

            Modal.gI().show(viewTitle, divContainer, false, "", "", null, null);
            break;
        case "done":
            let doneTitle = `Confirm this order: ${billId}`;
            let doneBody = `Do you sure this order have been prepared?`;
            Modal.gI().show(doneTitle, doneBody, true, "Done", "btn-success", () => {
                PrepareOrderTable.gI().done(billId);
            }, null);

            break;
        case "cancel":
            let cancelTitle = `Cancel this order: ${billId}`;
            let cancelBody = `Do you want to cancel this order, <b>you may need to pay money back to customer?</b>`;
            Modal.gI().show(cancelTitle, cancelBody, true, "Cancel", "btn-danger", () => {
                PrepareOrderTable.gI().cancel(billId)
            }, null);
            break;
        default:
    }
}

PrepareOrderTable.prototype.done = (billId) => {
    const form = new FormData();
    form.append("billId", billId);

    $.ajax({
        url: "/api/admin/orders/prepare",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            Toast.gI().showSuccess(response.message);
            Modal.gI().close();
            PrepareOrderTable.gI().fetch();
            OrderTable.gI().fetch();
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            Toast.gI().showError(response.message);
        }
    });
}

PrepareOrderTable.prototype.cancel = (billId) => {
    const form = new FormData();
    form.append("billId", billId);

    $.ajax({
        url: "/api/admin/orders/cancel",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            Toast.gI().showSuccess(response.message);
            Modal.gI().close();
            PrepareOrderTable.gI().fetch();
            OrderTable.gI().fetch();
        },
        error: function (xhr, status, error) {
            Toast.gI().showError("Non-expected error. Reload page!");
        }
    });
}






function OrderTable() {
    OrderTable.instance = null;
    this.tBody = "#orderTable tbody";
    OrderTable.orders = [];
    return this;
}

OrderTable.gI = function () {
    if (!OrderTable.instance) OrderTable.instance = new OrderTable();
    return OrderTable.instance;
}

OrderTable.prototype.init = function () {
    this.tBody = document.querySelector(this.tBody);
    this.fetch();
}

OrderTable.prototype.fetch = function (page = 1, id = null) {
    let xhr = new XMLHttpRequest();
    let url = `/api/admin/orders?page=${page}`;
    url += (id ? `&id=${id}` : ``);
    xhr.open('GET', url, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            Pagination.currentPage = response.data.currentPage;
            Pagination.totalPages = response.data.totalPages;
            OrderTable.orders = response.data.orders;
            this.updateView(response.data.orders.sort((a, b) => new Date(a.created_at) <= new Date(b.created_at) ? -1 : 1));
            Pagination.gI().updateView();
        } else {
            console.error('Error:', xhr.status, xhr.statusText);
        }
    }.bind(this);
    // Handle network errors
    xhr.onerror = function () {
        Toast.gI().showError('Fetch data failed!');
    };
    // Send the request
    xhr.send();
}

OrderTable.prototype.updateView = function (orders) {
    const content = orders.reverse().reduce((content, order) => {
        return content + `
            <tr data-bill-id=${order.id}>
                <td scope="col">
                    <span title="Click to copy" data-copy-text="${order.id}" style="cursor: pointer;">${Utility.shortString(order.id, 5)}</span>
                </td>
                <td scope="col">${order.created_at}</td>
                <td scope="col">${order.pay_method}</td>
                <td scope="col">${order.payment_service_provider}</td>
                <td scope="col">${order.order_status}</td>
                <td scope="col">${order.status}</td>
                <td scope="col">$${order.total_price}</td>
                <td scope="col">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal" data-action="view">
                        View products
                    </button>
                </td>
            </tr>
        `;
    }, "");

    this.tBody.innerHTML = content;
    [...this.tBody.querySelectorAll("[data-copy-text]")].forEach(obj => {
        const value = obj.dataset.copyText;
        obj.addEventListener("click", () => {
            navigator.clipboard.writeText(value).then(() => {
                Toast.gI().showSuccess("Copied to clipboard: " + value);
            }).catch(err => {
                console.error("Failed to copy:", err);
            });
        });
    });

    // Bind button events
    const buttons = this.tBody.querySelectorAll("button");
    buttons.forEach(button => {
        button.addEventListener("click", OrderTable.gI().handleButtonEvent);
    });
}

OrderTable.prototype.handleButtonEvent = (event) => {
    const billId = event.target.closest("tr").dataset.billId;
    const viewTitle = `View order: ${billId}`;
    const viewBody = OrderTable.orders.reduce((content, order) => {
        if (order.id !== billId) return content + "";
        const products = JSON.parse(order.products);
        return content + products.reduce((subContent, product) => {
            return subContent + `
                    <div class="card" style="width: 18rem">
                        <img src="${product?.image?.lg ? product?.image?.lg : `/public/images/no_image.webp`}" class="card-img-top" style="width:100%; max-height: 289px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text" style="color: ${Color.Red}; font-weight: 600;">$${product.price}</p>
                            <p class="card-text" style="color: rgb(0, 0, 0, 0.6);">Quantity: ${product.quantity}</p>
                            <a href="/admin/products/watch/${product.id}" class="btn btn-primary">Watch product</a>
                        </div>
                    </div>
                `;
        }, "");
    }, "");

    const divContainer = `
            <div class="d-flex flex-wrap justify-content-around gap-3">
                ${viewBody}
            </div>
        `;

    Modal.gI().show(viewTitle, divContainer, false, "", "", null, null);
}

OrderTable.prototype.findById = function (event) {
    event.preventDefault();
    const form = new FormData(event.target);
    const id = form.get("billId");
    this.fetch(1, id);
}



function Pagination() {
    Pagination.instance = null;
    Pagination.currentPage = 0;
    Pagination.totalPages = 0;
    this.pagination = ".pagination";
}

Pagination.gI = function () {
    if (!Pagination.instance) Pagination.instance = new Pagination();
    return Pagination.instance;
}

Pagination.prototype.init = function () {
    this.pagination = document.querySelector(this.pagination);
}

Pagination.prototype.updateView = function () {
    let content = `<li class="page-item"><a class="page-link ${Pagination.currentPage === 1 ? "disabled" : ""}" href="#orderTable" data-page="${Pagination.currentPage > 1 ? Pagination.currentPage - 1 : 1}">Previous</a></li>`;
    for (let i = 1; i <= Pagination.totalPages; i++) {
        content += `<li class="page-item"><a class="page-link ${Pagination.currentPage === i ? "active" : ""}" href="#orderTable" data-page="${i}">${i}</a></li>`;
    }
    content += `<li class="page-item"><a class="page-link ${Pagination.currentPage === Pagination.totalPages ? "disabled" : ""}" href="#orderTable" data-page=${Pagination.currentPage >= Pagination.totalPages ? Pagination.totalPages : Pagination.currentPage > 1 ? Pagination.currentPage - 1 : 1}>Next</a></li>`;

    this.pagination.innerHTML = content;

    const buttons = this.pagination.querySelectorAll("a");
    [...buttons].forEach(button => {
        button.addEventListener("click", (event) => {
            const newPage = event.target.dataset.page;
            if (+newPage === +Pagination.currentPage) return;
            OrderTable.gI().fetch(newPage);
        })
    })
}
