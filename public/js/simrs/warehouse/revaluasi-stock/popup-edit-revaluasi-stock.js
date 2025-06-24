// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupEditRevaluasiStockHandler {

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$QtyTotal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$DeltaTotal;

    constructor() {
        this.#$QtyTotal = $("#qty-total");
        this.#$DeltaTotal = $("#delta-total");
        // vanilla js document on ready
        // call #init
        document.addEventListener("DOMContentLoaded", () => {
            this.#init();
        });
    }

    #init() {
        this.#addEventListeners("input[type='number']", this.enforceNumberLimit, "input");
        // this.#addEventListeners("input[type='number']", this.#refreshTotal, "change");
        this.#addEventListeners("input[type='number']", this.#refreshTotal, "input");
    }

    /**
     * Handle qty change
     * @param {Event} event 
     */
    #refreshTotal(event){
        const Input = /** @type {HTMLInputElement} */ (event.target);
        // get data-initial attribute value
        const Initial = parseInt(Input.getAttribute("data-initial") || "0");
        const Delta = parseInt(Input.value) - Initial;

        // update td with class delta-total
        // inside the same tr as the input td
        const DeltaTotal = Input.closest("tr")?.querySelector(".delta");
        let FinalDelta = 0;
        if (DeltaTotal) {
            FinalDelta = parseInt(DeltaTotal.textContent || "0") - Delta;
            DeltaTotal.textContent = String(Delta);
        }

        this.#$QtyTotal.text(parseInt(this.#$QtyTotal.text()) - FinalDelta);
        this.#$DeltaTotal.text(parseInt(this.#$DeltaTotal.text()) - FinalDelta);
    }

    /**
     * Enforce number input min max limit on manual input
     * @param {Event} event 
     */
    enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0));  // Default to 0 if not set
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER));  // Set default to a large number

        if (isNaN(value)) {
            inputField.value = '';  // Reset to empty on invalid input
            return;
        }

        if (value < min) {
            inputField.value = String(min);  // Clamp value at min
        } else if (value > max) {
            inputField.value = String(max);  // Clamp value at max
        }
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

const PopupEditRevaluasiStockClass = new PopupEditRevaluasiStockHandler();