const subscriptionTable = new SubscriptionTable();
const textEditor = new TextEditor("#emailComposer", "#broadcastBtn");

document.addEventListener("DOMContentLoaded", () => {
    subscriptionTable.fetchSubscriptions(subscriptionTable.refreshSubscriptionTable);
    textEditor.initTinyMCE();
    textEditor.bind();
})

function SubscriptionTable() {
    SubscriptionTable.subscriptions = [];
    return this;
}

SubscriptionTable.prototype.fetchSubscriptions = function (onDone) {
    $.ajax({
        url: "/api/admin/subscriptions",
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            SubscriptionTable.subscriptions = response.sort((a, b) => a.isVerified ? -1 : 1);
            if (onDone) onDone();
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

SubscriptionTable.prototype.refreshSubscriptionTable = function () {
    const content = SubscriptionTable.subscriptions.reduce((content, sub) => {
        return content + `
            <tr>
                <td scope="col">${sub.email}</td>
                <td scope="col">${sub.isVerified}</td>
                <td scope="col">
                    <button class="btn btn-danger" onclick="SubscriptionTable.delete(event, '${sub.email}')">Delete</button>
                </td>
            </tr>
        `;
    }, "");

    document.querySelector("#subscriptionTable tbody").innerHTML = content;
}

SubscriptionTable.delete = function (event, email) {
    const form = new FormData();
    form.append("email", email);

    $.ajax({
        url: "/api/admin/subscriptions/delete",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            Toast.gI().showSuccess(response.message);
            subscriptionTable.fetchSubscriptions(subscriptionTable.refreshSubscriptionTable);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}



function TextEditor(textEditorSelector, broadcastBtnSelector) {
    this.textEditorSelector = textEditorSelector;
    this.broadcastBtnSelector = broadcastBtnSelector;
    return this;
}

TextEditor.prototype.initTinyMCE = function () {
    tinymce.init({
        selector: this.textEditorSelector,
        plugins: [
            // Core editing features
            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
            // Your account includes a free trial of TinyMCE premium features
            // Try the most popular premium features until Dec 9, 2024:
            'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
            // Early access to document converters
            'importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
        exportpdf_converter_options: { 'format': 'Letter', 'margin_top': '1in', 'margin_right': '1in', 'margin_bottom': '1in', 'margin_left': '1in' },
        exportword_converter_options: { 'document': { 'size': 'Letter' } },
        importword_converter_options: { 'formatting': { 'styles': 'inline', 'resets': 'inline', 'defaults': 'inline', } },
    });
}

TextEditor.prototype.bind = function () {
    const btn = document.querySelector(this.broadcastBtnSelector)
    btn?.addEventListener("click", (event) => {
        // console.log(event);
        this.broadcast();
    }, { passive: true })
}

TextEditor.prototype.broadcast = function () {
    const form = new FormData();
    const editor = tinymce.get("emailComposer");
    form.append("content", editor.getContent());

    $.ajax({
        url: "/api/admin/subscriptions/broadcast",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            Toast.gI().showSuccess(response.message);
            editor.setContent("");
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}
