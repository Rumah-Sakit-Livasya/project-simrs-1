// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupAPOPharmacyHandler {

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$GudangId;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$AddModal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingIcon;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingPage;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Table;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$ModalTable;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Total;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Nominal;

    constructor() {
        this.#$GudangId = $("select[name='gudang_id']");
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$Total = $("#harga-display");
        this.#$Nominal = $("input[name='nominal']");

        this.#init();
    }

    #init() {
        this.#addEventListeners("#order-submit-approve", this.#handleApproveButtonClick);
        this.#addEventListeners("#order-submit-revision", this.#handleRevisionButtonClick);
        this.#addEventListeners("#order-submit-reject", this.#handleRejectButtonClick);
        this.#showLoading(false);
    }

    /**
     * Handle save order revision button click
     * @param {Event} event 
     */
    #handleRevisionButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        // insert hidden input
        // with name "status_app"
        // and value "revision"
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status_app";
        input.value = "revision";
        button.insertAdjacentElement("afterend", input);
    }

    /**
     * Handle save order approve button click
     * @param {Event} event 
     */
    #handleApproveButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        // insert hidden input
        // with name "status_app"
        // and value "approve"
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status_app";
        input.value = "approve";
        button.insertAdjacentElement("afterend", input);
    }

    /**
     * Handle save order reject button click
     * @param {Event} event 
     */
    #handleRejectButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        // insert hidden input
        // with name "status_app"
        // and value "reject"
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status_app";
        input.value = "reject";
        button.insertAdjacentElement("afterend", input);
    }

    refreshTotal() {
        let total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=approved_qty]");
            const hnaEl = $(tr).find("input[name^=hna]");
            if (!qtyEl || !hnaEl) return;

            const qty = parseInt(String(qtyEl.val()));
            const hna = parseInt(String(hnaEl.val()));
            if (isNaN(qty) || isNaN(hna)) return;

            total += qty * hna;
            $(tr).find("td.subtotal").text(this.#rp(qty * hna));
            this.#$Total.text(this.#rp(total));
        });
        this.#$Total.text(this.#rp(total));
        this.#$Nominal.val(total)
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
     * Show or hide the loading icon
     * @param {boolean} show 
     */
    #showLoading(show) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
    }

    /**
     * Format angka menjadi mata uang rupiah
     * @param {number} amount 
     * @returns 
     */
    #rp(amount) {
        const formattedAmount = 'Rp ' + amount.toLocaleString('id-ID');
        return formattedAmount;
    }

}

const PopupAPOPharmacyClass = new PopupAPOPharmacyHandler();