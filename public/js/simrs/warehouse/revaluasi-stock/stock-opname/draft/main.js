// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../../types.d.ts" />

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

    /**
     * @type {import("select2").OptionData[]}
     */
    #PreviousGudangSelected;

    /**
     * @type {number}
     */
    OpnameId;

    #API_URL = "/api/simrs/warehouse/revaluasi-stock/stock-opname/draft";
    #APIHandler = new APIHandler(this.#API_URL);

    #GudangSelectedOnce = false;

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
        this.showLoading(false);
    }

    /**
     * Handle Print Lembar SO button click
     * @param {Event} event 
     */
    #handlePrintSO(event) {
        event.preventDefault();
        const GudangOpnameID = this.#$Gudang.val();
        if (!GudangOpnameID) return showErrorAlertNoRefresh("Mohon pilih gudang terlebih dahulu");

        const url = `/simrs/warehouse/revaluasi-stock/stock-opname/draft/print-so/${GudangOpnameID}`;
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

        const url = `/simrs/warehouse/revaluasi-stock/stock-opname/draft/print-selisih/${GudangOpnameID}`;
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
        if (this.#GudangSelectedOnce) {
            // use sweealert2 to confirm changing gudang
            // because if gudang is changed, all unsaved progress will be lost
            const result = await Swal.fire({
                title: 'Perhatian!',
                text: 'Ganti gudang akan menghapus semua progress yang belum disimpan. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ganti gudang',
                cancelButtonText: 'Batal'
            });
            if (!result.isConfirmed) {
                // revert gudang selection
                let ids = this.#PreviousGudangSelected.map(item => item.id);
                this.#$Gudang.val(ids).trigger("change");
                return;
            }
        } else {
            this.#GudangSelectedOnce = true;
        }

        this.#PreviousGudangSelected = this.#$Gudang.select2("data");

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
     * Save draft of stored item opname
     * @param {String} key_head 
     */
    async saveDraft(key_head) {
        const SOGid = this.OpnameId;
        const HeadTR = /** @type {JQuery<HTMLTableRowElement>} */ ($("tr#head" + key_head));
        const Item = /** @type {StackedStoredItemOpname} */(HeadTR.data("item"));
        const Column = `si_${Item.type}_id`;
        const ChildTR = HeadTR.next("tr").next("tr");
        const ChildTable = ChildTR.find("table.child");
        const ChildTRs = ChildTable.find("tr.child-item").toArray();
        const Body = new FormData();
        const Drafts = /** @type {{si_id: number, qty: number, keterangan: string}[]} */([]);
        const UserID = $("input[name='user_id']").val();
        for (let i = 0; i < ChildTRs.length; i++) {
            const $TR = $(ChildTRs[i]);
            const Stock = parseInt(/** @type {string} */($TR.find("input.item-qty-actual").val()));
            const Keterangan = $TR.find("input.item-keterangan");
            const SIid = $TR.data("si_id");

            if (isNaN(Stock)) {
                return showErrorAlertNoRefresh("Ada stock yang belum di isi!");
            }
            Drafts.push({ si_id: SIid, qty: Stock, keterangan: String(Keterangan.val()) });
        }

        Body.append("user_id", String(UserID));
        Body.append("sog_id", String(SOGid));
        Body.append("column", Column);
        Body.append("drafts", JSON.stringify(Drafts));

        this.showLoading(true, "Storing draft...");
        const Result = await this.#APIHandler.storeDraft(Body)
            .catch(err => {
                showErrorAlertNoRefresh(err);
            });
        this.showLoading(false);

        if (!Result) return;
        showSuccessAlert(`Draft Stock Opname untuk item [${Item.barang.nama}] berhasil disimpan!`);
    }

    /**
     * Equalize actual stock to system stock on child table
     * @param {number} key_head 
     */
    stockEqual(key_head) {
        // find on $("#datatable") all tr with attribute data-head_key==key_head
        const updateChildCol = this.#Table.updateChildCol;
        const updateHeadCol = this.#Table.updateHeadCol;
        const TableRows = $("#datatable tr[data-head_key='" + key_head + "']");
        TableRows.each(function () {
            const TR = /** @type {JQuery<HTMLTableRowElement>} */($(this));
            const Stock = TR.find("input.item-qty-actual");
            const StockSystem = parseInt(TR.find(".item-qty-frozen").text());
            const Status = TR.find(".item-status");
            if (!Status.text().toLowerCase().includes("final")) { // don't update if data is already final
                Stock.val(StockSystem); // set value of stock to stock_sys
                updateChildCol(TR);
            }
        });

        const HeadTR = /** @type {JQuery<HTMLTableRowElement>} */ ($("tr#head" + key_head));
        updateHeadCol(HeadTR);
    }

    /**
     * Set actual stock to zero on child table
     * @param {number} key_head 
     */
    stockZero(key_head) {
        // find on $("#datatable") all tr with attribute data-head_key==key_head
        const updateChildCol = this.#Table.updateChildCol;
        const updateHeadCol = this.#Table.updateHeadCol;
        const TableRows = $("#datatable tr[data-head_key='" + key_head + "']");
        TableRows.each(function () {
            const TR = /** @type {JQuery<HTMLTableRowElement>} */($(this));
            const Stock = TR.find("input.item-qty-actual");
            const Status = TR.find(".item-status");
            if (!Status.text().toLowerCase().includes("final")) { // don't update if data is already final
                Stock.val(0); // set value of stock to 0
                updateChildCol(TR);
            }
        });

        const HeadTR = /** @type {JQuery<HTMLTableRowElement>} */ ($("tr#head" + key_head));
        updateHeadCol(HeadTR);
    }

    /**
     * Refresh the stock movement based on the type and key_head.
     * @param {number} key_head 
     */
    async movementRefresh(key_head) {
        const HeadTR = /** @type {JQuery<HTMLTableRowElement>} */ ($("tr#head" + key_head));
        const Item = /** @type {StackedStoredItemOpname} */(HeadTR.data("item"));
        this.showLoading(true, `Refreshing movement for item [${Item.barang.nama}]...`);

        const TableRows = $("#datatable tr[data-head_key='" + key_head + "']").toArray();
        for (let i = 0; i < TableRows.length; i++) {
            const TR = TableRows[i];
            const $TR = /** @type {JQuery<HTMLTableRowElement>} */ ($(TR));
            const SIid = /** @type {number} */ ($TR.data("si_id"));
            const Response = /** @type {{movement: number}} */
                (await this.#APIHandler.fetchItemMovement(Item.type, this.OpnameId, SIid));
            const Movement = $TR.find(".item-qty-movement");
            Movement.text(Response.movement);
            this.#Table.updateChildCol($TR);
        }
        this.#Table.updateHeadCol(HeadTR);
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