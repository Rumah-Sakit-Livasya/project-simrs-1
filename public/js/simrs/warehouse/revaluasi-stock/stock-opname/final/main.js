// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../../types.d.ts" />

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
    #$JenisBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$KategoriBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$SatuanBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$BatchKosong;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$BatchExpired;

    // /**
    //  * @type {import("select2").OptionData[]}
    //  */
    // #PreviousGudangSelected;

    /**
     * @type {number}
     */
    OpnameId;

    #API_URL = "/api/simrs/warehouse/revaluasi-stock/stock-opname/final";
    #APIHandler = new APIHandler(this.#API_URL);

    // #GudangSelectedOnce = false;

    #Table = new TableHandler();

    constructor() {
        this.#$Loadings = $(".loading");
        this.#$LoadingsMessage = $(".loading-message");
        this.#$Gudang = $("#gudang");
        this.#$JenisBarang = $("#jenis_barang");
        this.#$KategoriBarang = $("#kategori_barang");
        this.#$SatuanBarang = $("#satuan_barang");
        this.#$BatchKosong = $("#batch_kosong");
        this.#$BatchExpired = $("#batch_expired");

        this.#$Gudang.on("select2:select", this.#handleGudangChange.bind(this));

        this.#$JenisBarang.on("change", this.#handleFilterChange.bind(this));
        this.#$KategoriBarang.on("select2:select", this.#handleFilterChange.bind(this));
        this.#$SatuanBarang.on("select2:select", this.#handleFilterChange.bind(this));

        this.#$BatchKosong.on("change", this.#handleChildFilterChange.bind(this));
        this.#$BatchExpired.on("change", this.#handleChildFilterChange.bind(this));

        this.#addEventListeners("#print-selisih-stock-btn", this.#handlePrintSelisihButtonClick);
        this.#addEventListeners("#print-lembar-so-btn", this.#handlePrintSO);
        this.#addEventListeners("#refresh-so-btn", this.#refresh);
        this.#addEventListeners("#save-final-btn", this.#handleSaveFinalClick);
        this.showLoading(false);
    }

    /**
     * Handle save final button click
     * @param {Event} event 
     */
    async #handleSaveFinalClick(event) {
        event.preventDefault();
        const GudangOpnameID = this.#$Gudang.val();
        if (!GudangOpnameID) return showErrorAlertNoRefresh("Mohon pilih gudang terlebih dahulu");

        this.showLoading(true, "Saving final...");
        const Body = this.#Table.buildBodyForFinalSave(this.OpnameId);
        const Response = await this.#APIHandler.storeFinal(Body).catch(err => {
            this.showLoading(false);
            showErrorAlertNoRefresh(err.message);
        });
        this.showLoading(false);

        if (Response.success) {
            showSuccessAlert("Data berhasil disimpan. Perubahan telah diterapkan pada stock sistem.");
        } else {
            showErrorAlert(Response.message);
        }
    }

    /**
     * Handle Print Lembar SO button click
     * @param {Event} event 
     */
    #handlePrintSO(event) {
        event.preventDefault();
        const GudangOpnameID = this.#$Gudang.val();
        if (!GudangOpnameID) return showErrorAlertNoRefresh("Mohon pilih gudang terlebih dahulu");

        const url = `/simrs/warehouse/revaluasi-stock/stock-opname/final/print-so/${GudangOpnameID}`;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_printSO" + GudangOpnameID,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    /**
     * Handle Print Selisih Stock Opname button click
     * @param {Event} event 
     */
    #handlePrintSelisihButtonClick(event) {
        event.preventDefault();
        const GudangOpnameID = this.#$Gudang.val();
        if (!GudangOpnameID) return showErrorAlertNoRefresh("Mohon pilih gudang terlebih dahulu");

        const url = `/simrs/warehouse/revaluasi-stock/stock-opname/final/print-selisih/${GudangOpnameID}`;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_printSelisihStock" + GudangOpnameID,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    #handleChildFilterChange() {
        const BatchKosong = /** @type {"show" | "hide"} */(this.#$BatchKosong.val());
        const BatchExpired = /** @type {"no" | "exp" | undefined} */(this.#$BatchExpired.val());
        this.#Table.toggleChildFilter(BatchKosong, BatchExpired);
    }

    #handleFilterChange() {
        const JenisBarang = /** @type {"f" | "nf" |undefined} */(this.#$JenisBarang.val());
        const KategoriBarang = /** @type {number | undefined} */(this.#$KategoriBarang.val());
        const SatuanBarang = /** @type {number | undefined} */(this.#$SatuanBarang.val());
        this.#Table.toggleFilter(JenisBarang, KategoriBarang, SatuanBarang);
    }

    async #handleGudangChange() {
        // if (this.#GudangSelectedOnce) {
        //     // use sweealert2 to confirm changing gudang
        //     // because if gudang is changed, all unsaved progress will be lost
        //     const result = await Swal.fire({
        //         title: 'Perhatian!',
        //         text: 'Ganti gudang akan menghapus semua progress yang belum disimpan. Lanjutkan?',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Ya, ganti gudang',
        //         cancelButtonText: 'Batal'
        //     });
        //     if (!result.isConfirmed) {
        //         // revert gudang selection
        //         let ids = this.#PreviousGudangSelected.map(item => item.id);
        //         this.#$Gudang.val(ids).trigger("change");
        //         return;
        //     }
        // } else {
        //     this.#GudangSelectedOnce = true;
        // }

        // this.#PreviousGudangSelected = this.#$Gudang.select2("data");

        this.showLoading(true, "Loading data...");
        const SOGid = /** @type {number} */(this.#$Gudang.val());
        this.OpnameId = SOGid;
        const items = await this.#APIHandler.fetchItems(SOGid);
        await this.#Table.updateTable(items);
        this.#handleFilterChange();
        this.#handleChildFilterChange();
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
     * Refresh the stock movement based on the type and key_head.
     * @param {Event} event 
     */
    async #refresh(event) {
        event.preventDefault();
        const GudangOpnameID = this.#$Gudang.val();
        if (!GudangOpnameID) return showErrorAlertNoRefresh("Mohon pilih gudang terlebih dahulu");

        this.showLoading(true, `Refreshing...`);

        const SOGid = /** @type {number} */(this.#$Gudang.val());
        this.OpnameId = SOGid;
        const items = await this.#APIHandler.fetchItems(SOGid);
        await this.#Table.updateTable(items);
        this.#handleFilterChange();
        this.#handleChildFilterChange();

        this.showLoading(false);
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