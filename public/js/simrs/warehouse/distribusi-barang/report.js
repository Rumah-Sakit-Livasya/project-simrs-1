// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class DBReportHandler {
    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TanggalDistribusi;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Jenis;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$KodePO;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$NamaBarang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$GudangAsal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$GudangTujuan;

    constructor() {
        // on document ready, call init
        $(document).ready(() => {
            this.#init();
        });
    }

    #init() {
        this.#$TanggalDistribusi = $("input[name='tanggal_db']");
        this.#$Jenis = $("select[name='jenis']");
        this.#$KodePO = $("input[name='kode_po']");
        this.#$NamaBarang = $("input[name='nama_barang']");
        this.#$GudangAsal = $("#asal_gudang_id");
        this.#$GudangTujuan = $("#tujuan_gudang_id");
        this.#addEventListeners("#reportBtn", this.#handleReportButtonClick);
    }

    #getJSONFromInputs() {
        const data = {
            tanggal_db: this.#$TanggalDistribusi.val(),
            jenis: this.#$Jenis.val(),
            kode_po: this.#$KodePO.val(),
            nama_barang: this.#$NamaBarang.val(),
            asal_gudang_id: this.#$GudangAsal.val(),
            tujuan_gudang_id: this.#$GudangTujuan.val(),
        };
        return JSON.stringify(data);
    }

    /**
    * Handle rekap button click
    * @param {MouseEvent} event 
    */
    #handleReportButtonClick(event) {
        event.preventDefault();
        const json = this.#getJSONFromInputs();
        this.#spawnReportPopup(json);
    }

    #spawnReportPopup(json) {
        const url = `/simrs/warehouse/distribusi-barang/report/show/${json}`;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_showReportDB",
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

const DBReportClass = new DBReportHandler();