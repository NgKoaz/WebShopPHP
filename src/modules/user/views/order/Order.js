const orderManager = new OrderManager();
const tabManager = new TabManager();

document.addEventListener("DOMContentLoaded", () => {
    orderManager.fetchOrders(() => {
        tabManager.reloadCurrentTab();
    });
});

function OrderManager() {
    this.tbodyOrderTable = document.querySelector("#orderTable tbody");
    OrderManager.orders = [];
    return this;
}

OrderManager.prototype.fetchOrders = function (onDone) {
    $.ajax({
        url: `/api/orders`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            OrderManager.orders = response;
            if (onDone) onDone();
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}

OrderManager.prototype.loadOrder = function () {

}

OrderManager.prototype.loadTableRows = function (orders) {
    console.log(orders);
    const content = orders.reverse(order => order.created_at).reduce((content, order) => {
        return content +
            `<tr>
                <td>${order.id}</td>
                <td>${order.created_at}</td>
                <td>${order.order_status}</td>
                <td>${order.status}</td>
                <td>$${order.total_price}</td>
                <td>
                    <button>Detail</button>
                    <form method="POST" action="/order-rebuy">
                        <input type="hidden" name="billId" value=${order.id}> 
                        <button>Rebuy</button>
                    </form>
                    <form method="POST" action="/order-pay">
                        <input type="hidden" name="billId" value=${order.id}> 
                        <button>Pay</button>
                    </form>
                    <button>Review</button>
                    <form onsubmit="orderManager.cancelOrder(event)">
                        <input type="hidden" name="billId" value=${order.id}> 
                        <button>Cancel</button>
                    </form>
                </td>
            </tr>`
    }, "");

    this.tbodyOrderTable.innerHTML = content;
}

OrderManager.prototype.cancelOrder = function (event) {
    event.preventDefault();

    const form = new FormData(event.target);
    $.ajax({
        url: `/api/orders/cancel`,
        method: 'POST',
        data: form,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            this.fetchOrders(() => {
                tabManager.reloadCurrentTab();
            });
        }.bind(this),
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}



function TabManager() {
    this.tabContainer = document.querySelector(".tab-slider");
    this.tabItems = document.querySelectorAll(".tab-item");
    this.cardList = document.querySelector(".card-list");
    this.tabItems.forEach(item => {
        item.addEventListener("click", (event) => {
            this.tabItems.forEach(item => item.classList.remove("selected"));
            event.target.classList.add("selected");
            const newState = event.target.dataset.state;
            if (this.tabContainer.dataset.state === newState) return;
            this.tabContainer.dataset.state = newState;
            this.setTabState(newState);
        });
    });

    return this;
}

TabManager.prototype.reloadCurrentTab = function () {
    this.setTabState(this.tabContainer.dataset.state);
}

TabManager.prototype.setTabState = function (state) {
    switch (state) {
        case "UNPAID":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.status === "UNPAID"),
                "Unpaid",
                `<button class="card-btn card-btn-secondary" onclick="OrderActions.cancel(event)">Cancel</button>
                <button class="card-btn card-btn-primary" onclick="OrderActions.pay(event)">Pay</button>`
            );
            break;
        case "PREPARING":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "PREPARING"),
                "Preparing",
                `<button class="card-btn card-btn-secondary" onclick="OrderActions.cancel(event)">Cancel</button>
                <button class="card-btn card-btn-secondary">Detail</button>`
            );
            break;
        case "SHIPPING":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "SHIPPING"),
                "Shipping",
                `<button class="card-btn card-btn-secondary">Detail</button>`
            );
            break;
        case "SHIPPED":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "SHIPPED"),
                "Shipped",
                `<button class="card-btn card-btn-secondary">Detail</button>
                <button class="card-btn card-btn-primary">Received</button>`
            );
            break;
        case "RECEIVED":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "RECEIVED"),
                "Received",
                `<button class="card-btn card-btn-secondary" onclick="OrderActions.rebuy(event)">Rebuy</button>
                <button class="card-btn card-btn-primary">Review</button>`
            );
            break;
        case "CANCELLED":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.status === "CANCELLED"),
                "Cancelled",
                `<button class="card-btn card-btn-primary" onclick="OrderActions.rebuy(event)">Rebuy</button>`
            );
            break;
        default:
    }
}

TabManager.prototype.loadOrderWithOrderList = function (orders, status, actionHTML) {
    const content = orders.reduce((content, order) => {
        const products = JSON.parse(order.products);
        const totalProduct = products.reduce((totalProduct, product) => totalProduct + product.quantity, 0);
        const totalPrice = products.reduce((totalPrice, product) => totalPrice + product.price * product.quantity, 0);
        const subContent = products.reduce((subContent, product) => {
            return subContent + `
            <div class="product">
                <img src="${product?.image?.sm ? product?.image?.sm : `/public/images/sm_no_image.webp`}">
                <div class="product-info">
                    <div class="product-title">${product.name}</div>
                    <div class="product-quantity">x${product.quantity}</div>
                    <div class="product-price">$${product.price}</div>
                </div>
            </div>
            `
        }, "")
        return content +
            `<div class="card-container">
                <div class="card">
                    <div class="card-top">
                        <div class="shop-name">BK.CO</div>
                        <div class="status">${status} ${status === "Preparing" && order.status === "UNPAID" ? "- Unpaid" : "- Paid"}</div>
                    </div>
                    <div class="product-container">
                        <div class="product-list">
                            ${subContent}
                        </div>
                        ${products.length > 1 ? `<button class="card-more-btn">More <i class="bi bi-arrow-bar-down"></i></button>` : ``} 
                    </div>
                    <div class="total-price">Total price (${totalProduct} product${totalProduct > 1 ? "s" : ""}): $${Math.round(totalPrice * 100) / 100}</div>
                    <div class="actions" data-bill-id="${order.id}">
                        ${actionHTML}
                    </div>
                </div>
            </div>`
    }, "");
    this.cardList.innerHTML = content ? content : `<p style="font-size: var(--md-font-size); text-align: center; color: rgba(0,0,0,0.6); flex-grow: 1;">Empty!</p>`;
}


function OrderActions() { }

OrderActions.cancel = function (event) {
    const form = new FormData();
    form.append("billId", event.target.closest(".actions").dataset.billId);
    $.ajax({
        url: `/api/orders/cancel`,
        method: 'POST',
        data: form,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            orderManager.fetchOrders(() => {
                tabManager.reloadCurrentTab();
            });
        }.bind(this),
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}

OrderActions.pay = function (event) {
    const billId = event.target.closest(".actions").dataset.billId;
    window.location.href = `/order-pay/${billId}`;
}

OrderActions.receive = function () {

}

OrderActions.rebuy = function (event) {
    const billId = event.target.closest(".actions").dataset.billId;
    window.location.href = `/order-rebuy/${billId}`;
}


