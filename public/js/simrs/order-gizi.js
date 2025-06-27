// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class OrderGiziHandler {
    /**
     * All orders in the datatable, displayed or not
     * @type {OrderGizi[]}
     */
    #Orders;

    constructor() {
        document.addEventListener("DOMContentLoaded", this.#init.bind(this));

        // @ts-ignore
        this.#Orders = window._orders;
    }

    #init() {
        this.#addEventListeners(".send-btn", this.#handleSendButtonClick);
        this.#addEventListeners(".print-label-btn", this.#handlePrintLabelButtonClick);
        this.#addEventListeners(".bulk-send-btn", this.#handleBulkSendButtonClick);
        this.#addEventListeners(".bulk-print-btn", this.#handleBulkPrintButtonClick);
        this.#addEventListeners(".print-nota-btn", this.#handlePrintNotaButtonClick);
        this.#addEventListeners(".edit-btn", this.#handleEditButtonClick);
    }

    /**
     * Add event listeners
     * @param {string} selector 
     * @param {Function} handler 
     * @param {string} event
     */
    #addEventListeners(selector, handler, event = 'click') {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach((button) => {
            button.addEventListener(event, handler.bind(this));
        });
    }

    /**
     * Handle edit order button click
     * @param {Event} event 
     */
    #handleEditButtonClick(event){
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = button.getAttribute("data-id");
        const url = "/simrs/gizi/popup/edit/" + id;
        const width = screen.width / 2;
        const height = screen.height / 2;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_editOrderGizi",
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    /**
     * Handle print order button click event
     * @param {Event} event 
     */
    #handlePrintNotaButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = button.getAttribute("data-id");
        const url = "/simrs/gizi/popup/print-nota/" + id;
        const width = screen.width;
        const height = screen.height;
        window.open(
            url,
            "popupWindow_printNotaOrderGizi" + id,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes"
        );
    }

    /**
     * Handle bulk print label button click event
     * @param {Event} event 
     */
    #handleBulkPrintButtonClick(event) {
        event.preventDefault();

        // create an input array
        // with name order_ids
        const orderIds = [];
        this.#Orders.forEach((order) => {
            orderIds.push(order.id);
        });

        const url = "/simrs/gizi/popup/bulk-label/" + JSON.stringify(orderIds);
        const width = screen.width / 2;
        const height = screen.height / 2;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_bulkLabelOrderGizi",
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    /**
     * Handle bulk send button click event
     * @param {Event} event 
     */
    #handleBulkSendButtonClick(event) {
        event.preventDefault();

        // fire Swal confirmation alert
        Swal.fire({
            title: 'Update status semua pesanan?',
            text: "Ubah status semua pesanan yang ada di table menjadi terkirim?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ubah semua',
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // send request to server
                this.#bulkUpdateOrderStatus();
            }
        });
    }

    #bulkUpdateOrderStatus() {
        // first, get every order
        // where "status_order" is false or 0
        const orders = this.#Orders.filter(order => order.status_order == 0);
        if (orders.length === 0) {
            showErrorAlertNoRefresh('Semua pesanan sudah terkirim!');
            return;
        }

        // second, loop through orders
        // and update status_order to 1
        // with #updateOrderStatus
        orders.forEach(async (order) => {
            await this.#updateOrderStatus(order.id, false);
        });

        // lastly, prompt success alert
        showSuccessAlert('Data berhasil disimpan');
        setTimeout(() => window.location.reload(), 2000);
    }

    /**
     * Handle send button click event
     * @param {Event} event 
     */
    #handleSendButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);

        // get id from attribute data-id
        const dataId = button.getAttribute('data-id');
        if (!dataId) {
            showErrorAlertNoRefresh('Data ID tidak ditemukan');
            return;
        }
        const id = parseInt(dataId);

        // fire Swal confirmation alert
        Swal.fire({
            title: 'Update status pesanan?',
            text: "Ubah status pesanan menjadi terkirim?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ubah',
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // send request to server
                this.#updateOrderStatus(id);
            }
        });
    }

    /**
     * Update order status after confirmation
     * @param {number} id 
     * @param {boolean} alert
     */
    #updateOrderStatus(id, alert = true) {
        if (!id || id == 0) return showErrorAlertNoRefresh("ID tidak valid!");
        const formData = new FormData();
        formData.append("id", String(id));

        return fetch('/api/simrs/gizi/order/update/status', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
            }
        })
            .then(response => {
                if (response.status != 200) {
                    throw new Error('Error: ' + response.statusText);
                }
                if (alert) {
                    showSuccessAlert('Data berhasil disimpan');
                    setTimeout(() => window.location.reload(), 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }

    /**
     * Handle print label button click event
     * @param {Event} event 
     */
    #handlePrintLabelButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);

        // get id from attribute data-id
        const dataId = button.getAttribute('data-id');
        if (!dataId) {
            showErrorAlertNoRefresh('Data ID tidak ditemukan');
            return;
        }
        const id = parseInt(dataId);

        const url = "/simrs/gizi/popup/label/" + id;
        const width = 400;
        const height = 400;
        const left = width - (screen.width / 2);
        const top = height - (screen.height / 2);
        window.open(
            url,
            "popupWindow_labelOrderGizi_" + id,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }


}

const OrderGiziClass = new OrderGiziHandler();