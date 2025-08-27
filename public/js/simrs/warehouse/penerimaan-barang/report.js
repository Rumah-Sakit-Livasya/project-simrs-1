// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PBReportHandler {
    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TanggalTerima;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TanggalFaktur;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$SupplierId;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$KategoriId;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TipeTerima;

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

    constructor() {
        // on document ready, call init
        $(document).ready(() => {
            this.#init();
        });
    }

    #init() {
        this.#$TanggalTerima = $("input[name='tanggal_terima']");
        this.#$TanggalFaktur = $("input[name='tanggal_faktur']");
        this.#$SupplierId = $("select[name='supplier_id']");
        this.#$KategoriId = $("select[name='kategori_id']");
        this.#$TipeTerima = $("select[name='tipe_terima']");
        this.#$Jenis = $("select[name='jenis']");
        this.#$KodePO = $("input[name='kode_po']");
        this.#$NamaBarang = $("input[name='nama_barang']");
        this.#addEventListeners("#rekapBtn", this.#handleRekapButtonClick);
        this.#addEventListeners("#detailItemBtn", this.#handleDetailItemButtonClick);
        this.#addEventListeners("#detailPBBtn", this.#handleDetailPBButtonClick);
    }

    #getJSONFromInputs() {
        const data = {
            tanggal_terima: this.#$TanggalTerima.val(),
            tanggal_faktur: this.#$TanggalFaktur.val(),
            supplier_id: this.#$SupplierId.val(),
            kategori_id: this.#$KategoriId.val(),
            tipe_terima: this.#$TipeTerima.val(),
            jenis: this.#$Jenis.val(),
            kode_po: this.#$KodePO.val(),
            nama_barang: this.#$NamaBarang.val(),
        };
        return JSON.stringify(data);
    }

    /**
    * Handle rekap button click
    * @param {MouseEvent} event 
    */
    #handleRekapButtonClick(event) {
        event.preventDefault();
        const json = this.#getJSONFromInputs();
        this.#spawnReportPopup("rekap", json);
    }

    /**
    * Handle detail item button click
    * @param {MouseEvent} event 
    */
    #handleDetailItemButtonClick(event) {
        event.preventDefault();
        const json = this.#getJSONFromInputs();
        this.#spawnReportPopup("item", json);
    }

    /**
    * Handle detail PB button click
    * @param {MouseEvent} event 
    */
    #handleDetailPBButtonClick(event) {
        event.preventDefault();
        const json = this.#getJSONFromInputs();
        this.#spawnReportPopup("pb", json);
    }

    #spawnReportPopup(type, json) {
        const url = `/simrs/warehouse/penerimaan-barang/report/show/${type}/${json}`;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_showReportPB" + type,
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

const PBReportClass = new PBReportHandler();