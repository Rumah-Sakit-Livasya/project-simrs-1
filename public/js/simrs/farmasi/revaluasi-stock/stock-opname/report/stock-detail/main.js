// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class MainClass {

    /**
      * @type {JQuery<HTMLElement>}
      */
    #$Loadings;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingsMessage;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Gudang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$NamaBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$JenisBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$KategoriBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$GolonganBarang;

    /**
    * @type {JQuery<HTMLElement>}
    */
    #$Tanggal;

    #API_URL = "/api/simrs/farmasi/laporan/stock-detail";
    #APIHandler = new APIHandler(this.#API_URL);

    #ContentShowing = false;

    #Table = new TableHandler();

    constructor() {
        this.#$Loadings = $(".loading");
        this.#$LoadingsMessage = $(".loading-message");
        this.#$Gudang = $("#gudang");
        this.#$JenisBarang = $("#jenis_barang");
        this.#$KategoriBarang = $("#kategori_barang");
        this.#$GolonganBarang = $("#golongan_barang");
        this.#$NamaBarang = $("#nama");
        this.#$Tanggal = $("#datepicker-1");
        this.#addEventListeners("#search-btn", this.#handleSearchButtonClick);
        this.#addEventListeners("#print-btn", this.#handlePrintButtonClick);
        this.showLoading(false);
    }

    /**
     * Handle print button click event.
     * @param {Event} event 
     */
    async #handlePrintButtonClick(event) {
        event.preventDefault();
        if (!this.#ContentShowing) return showErrorAlertNoRefresh("Tampilkan kontent terlebih dahulu dengan mencari data!");
        const template = /** @type {Response} */(await this.#APIHandler.getPrintTemplate());
        const tanggal = /** @type {string} */(this.#$Tanggal.val());  // Assuming Tanggal is a string type
        this.#Table.print(await template.text(), tanggal);
    }

    /**
     * Handle search button click event.
     * @param {Event} event 
     */
    #handleSearchButtonClick(event) {
        event.preventDefault();
        this.#refreshTable();  // Refresh the table based on the selected filters
    }

    async #refreshTable() {
        this.showLoading(true, "Loading contents...");
        const nama = /** @type {string} */ (this.#$NamaBarang.val());
        const kategori = /** @type {string} */(this.#$KategoriBarang.val());
        const golongan = /** @type {string} */(this.#$GolonganBarang.val());
        const jenis = /** @type {string} */(this.#$JenisBarang.val());  // Assuming JenisBarang is a string type
        const gudang = /** @type {string} */(this.#$Gudang.val());  // Assuming GudangBarang is a string type
        const tanggal = /** @type {string} */(this.#$Tanggal.val());  // Assuming Tanggal is a string type
        const Result = /** @type {StockDetails[]} */(await this.#APIHandler.fetchItems({ tanggal, nama, kategori, golongan, jenis, gudang }));
        console.log(Result);
        this.#Table.updateTable(Result);
        this.#ContentShowing = true;
        this.showLoading(false);
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
     * Show or hide the loading icon
     * @param {boolean} show 
     * @param {string?} message 
     */
    showLoading(show, message = null) {
        this.#$Loadings.toggle(show);

        if (message) {
            this.#$LoadingsMessage.text(message);
        } else {
            this.#$LoadingsMessage.text('Loading...');
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

const Main = new MainClass();