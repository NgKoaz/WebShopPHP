

document.addEventListener("DOMContentLoaded", () => {
    PrepareOrderTable.gI().init();
    OrderTable.gI().init();
});




function Utility() { }
Utility.shortString = function (inputStr, maxLen) {
    return inputStr.length > maxLen ? inputStr.slice(0, maxLen) + "..." : inputStr;
}




function PrepareOrderTable(tBody) {
    this.instance = null;
    this.tBody = "#prepareOrderTable tbody";
    return this;
}

PrepareOrderTable.gI = function () {
    if (this.instance) return this.instance;
    return this.instance = new PrepareOrderTable();
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
            const response = JSON.parse(xhr.responseText);
            console.log(response);
            this.updateView(response.data);
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
            <tr>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
                        View products
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal">
                        Done
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal">
                        Cancel
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
}




function OrderTable(tBody) {
    this.tBody = "#orderTable tbody";
    return this;
}

OrderTable.gI = function () {
    if (this.instance) return this.instance;
    return this.instance = new OrderTable();
}

OrderTable.prototype.init = function () {
    this.tBody = document.querySelector(this.tBody);
    this.fetch();
}

OrderTable.prototype.fetch = function () {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/api/admin/orders', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            console.log(response);
            this.updateView(response.data);
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

OrderTable.prototype.updateView = function ({ orders, currentPage, totalPages }) {
    const content = orders.reverse().reduce((content, order) => {
        return content + `
            <tr>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
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
}





