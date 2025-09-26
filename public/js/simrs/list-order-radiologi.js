// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class RadiologiOrderHandler {
    constructor() {
        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
    }

    #init() {
        this.#addEventListeners("a.nota-btn", this.#handleNotaClick);
        this.#addEventListeners("a.edit-btn", this.#handleEditClick);
        this.#addEventListeners("a.pay-btn", this.#handlePayClick);
        this.#addEventListeners("a.result-btn", this.#handleResultClick);
        this.#addEventListeners("a.label-btn", this.#handleLabelClick);
    }

    /**
     * Add event listeners
     * @param {string} selector
     * @param {Function} handler
     * @param {string} event
     */
    #addEventListeners(selector, handler, event = "click") {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach((button) => {
            button.addEventListener(event, handler.bind(this));
        });
    }

    /**
     * Handle pay button click
     * @param {Event} event
     */
    #handlePayClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const formData = new FormData();
        formData.append("id", id);

        Swal.fire({
            title: "Konfirmasi Tagihan",
            html: "Konfirmasi order Radiologi menjadi tagihan pasien?",
            icon: "question",
            focusConfirm: true,
            showCancelButton: true,
            confirmButtonText: "Konfirmasi",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("/api/simrs/konfirmasi-tagihan-order-radiologi", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN":
                            document
                                .querySelector('meta[name="csrf-token"]')
                                ?.getAttribute("content") || "",
                    },
                })
                    .then((response) => {
                        if (response.status != 200) {
                            throw new Error("Error: " + response.statusText);
                        }
                        showSuccessAlert("Data berhasil disimpan");
                        setTimeout(() => window.location.reload(), 2000);
                    })
                    .catch((error) => {
                        console.log("Error:", error);
                        showErrorAlertNoRefresh(`Error: ${error}`);
                    });
            }
        });
    }

    /**
     * Handle edit button click
     * @param {Event} event
     */
    #handleEditClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const url = `/simrs/radiologi/edit-order/${id}`;

        window.open(
            url,
            "popupWindow_" + new Date().getTime(),
            "width=" +
                screen.width +
                ",height=" +
                screen.height +
                ",scrollbars=yes,resizable=yes"
        );
    }

    /**
     * Handle print nota button click
     * @param {Event} event
     */
    #handleNotaClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const url = `/simrs/radiologi/nota-order/${id}`;

        window.open(
            url,
            "popupWindow_" + new Date().getTime(),
            "width=" +
                screen.width +
                ",height=" +
                screen.height +
                ",scrollbars=yes,resizable=yes"
        );
    }

    /**
     * Handle print nota button click
     * @param {Event} event
     */
    #handleResultClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const url = `/simrs/radiologi/hasil-order/${id}`;

        window.open(
            url,
            "popupWindow_" + new Date().getTime(),
            "width=" +
                screen.width +
                ",height=" +
                screen.height +
                ",scrollbars=yes,resizable=yes"
        );
    }

    /**
     * Handle print nota button click
     * @param {Event} event
     */
    #handleLabelClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const url = `/simrs/radiologi/label-order/${id}`;

        window.open(
            url,
            "popupWindow_" + new Date().getTime(),
            "width=" +
                screen.width +
                ",height=" +
                screen.height +
                ",scrollbars=yes,resizable=yes"
        );
    }
}

const RadiologiOrderHandlerClass = new RadiologiOrderHandler();
