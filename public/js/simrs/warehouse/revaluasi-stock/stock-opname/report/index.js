// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class SOReportHandler {
    constructor() {
        this.#addEventListeners('.print-selisih-btn', this.#handlePrintSelisihButtonClick);
        this.#addEventListeners('.print-detail-btn', this.#handlePrintSOButtonClick);
    }

    /**
     * Handle print button click
     * @param {Event} event 
     */
    #handlePrintSelisihButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        const url = "/simrs/warehouse/revaluasi-stock/stock-opname/report/print-selisih/" + id;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_printSOReportSelisih",
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    /**
     * Handle print button click
     * @param {Event} event 
     */
    #handlePrintSOButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        const url = "/simrs/warehouse/revaluasi-stock/stock-opname/report/print-so/" + id;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_printSOReportDetail",
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
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
}

const SOReportClass = new SOReportHandler();