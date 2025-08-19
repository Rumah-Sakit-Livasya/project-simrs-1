// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupSRPharmacyHandler {

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$AsalGudangId;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TujuanGudangId;

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
    #$ItemSource;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$ItemSearch;

    /**
    * @type {string[]}
    */
    // @ts-ignore
    #KeyCache = window._key_caches ?? [];

    #API_URL = "/api/simrs/warehouse/stock-request/pharmacy";

    constructor() {
        this.#$AsalGudangId = $("select[name='asal_gudang_id']");
        this.#$TujuanGudangId = $("select[name='tujuan_gudang_id']");
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$ItemSource = $("#itemSourceSelect");
        this.#$ItemSearch = $("#searchItemInput");

        this.#init();
    }

    #init() {
        this.#addEventListeners("#add-btn", this.#handleAddButtonClick);
        this.#addEventListeners("#searchItemInput", this.#handleItemSearchBar, "keyup");
        this.#addEventListeners("#order-submit-draft", this.#handleDraftButtonClick);
        this.#addEventListeners("#order-submit-final", this.#handleFinalButtonClick);
        this.#addEventListeners("#itemSourceSelect", this.#handleItemSearchBar.bind(this, null), "change");
        $("#asal-gudang").on("select2:select", this.#handleGudangChange.bind(this));
        $("#tujuan-gudang").on("select2:select", this.#handleGudangChange.bind(this));
        this.#showLoading(false);
    }

    #handleGudangChange() {
        this.#KeyCache = [];
        this.#$Table.empty();
    }

    /**
     * Handle save order final button click
     * @param {Event} event 
     */
    #handleFinalButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        // insert hidden input
        // with name "status"
        // and value "final"
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status";
        input.value = "final";
        button.insertAdjacentElement("afterend", input);
    }

    /**
     * Handle save order draft button click
     * @param {Event} event 
     */
    #handleDraftButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        // insert hidden input
        // with name "status"
        // and value "draft"
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status";
        input.value = "draft";
        button.insertAdjacentElement("afterend", input);
    }

    /**
     * Handle item search bar on key up
     * @param {Event?} event
     */
    #handleItemSearchBar(event) {
        let value = "";
        if (event) {
            const searchInput = /** @type {HTMLInputElement} */ (event.target);
            value = searchInput.value.toLowerCase();
        } else {
            value = (/** @type {string} */(this.#$ItemSearch.val()) || "").toLowerCase();
        }
        const items = document.querySelectorAll("tr.item");
        const source = /** @type {"stock" | "barang"} */ (this.#$ItemSource.val());


        items.forEach((item) => {
            if (!item) return;
            const itemNameElement = item.querySelector(".item-name");
            if (!itemNameElement) return;
            const itemName = itemNameElement.textContent;
            if (!itemName) return;

            // get attribute data-type
            const itemType = item.getAttribute("data-type");
            if (!itemType) return;
            if (itemType !== source) {
                // @ts-ignore
                item.style.display = "none";
                return;
            }

            // @ts-ignore
            item.style.display = itemName.toLowerCase().includes(value) ? "" : "none";
        });
    }

    addItem(tipe, id) {
        const row = this.#$ModalTable.find(`tr.${tipe}-based#${tipe}${id}`);

        const QtyString = row.find("input.qty").val();
        if (!QtyString) {
            this.#$AddModal.modal("hide");
            showErrorAlertNoRefresh("Quantitas tidak dapat ditemukan!");
            return;
        }
        const Qty = parseInt(String(QtyString));
        if (Qty <= 0) {
            this.#$AddModal.modal("hide");
            showErrorAlertNoRefresh("Jumlah tidak boleh 0!");
            return;
        }

        let stock = null;
        if (tipe == "stock") {
            // get td with class "stock"
            const stockCell = row.find("td.stock");
            const stockString = stockCell.text();
            stock = parseInt(String(stockString));
        }

        const Item = /** @type {BarangFarmasi} */ (row.data("item"));
        const selectedOption = row.find("select[name='satuan" + Item.id + "']").find("option:selected");
        const Satuan = /** @type {Satuan} */ (selectedOption.data("satuan"));

        const Key = `${Item.id}/${Satuan.id}`;
        if (this.#KeyCache.includes(Key)) return; // ignore duplicate key

        this.#KeyCache.push(Key); // add key to cache
        const HTML = this.#getItemTableCol(Item, Satuan, Key, Qty, stock);
        this.#$Table.append(HTML);
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {BarangFarmasi} item 
     * @param {Satuan} satuan
     * @param {string} key_cache 
     * @param {number} qty 
     * @param {number?} stock 
     */
    #getItemTableCol(item, satuan, key_cache, qty, stock = null) {
        const key = Math.round(Math.random() * 100000);

        return /*html*/`
            <tr id="item${key}">
                <input type="hidden" name="barang_id[${key}]" value="${item.id}">
                <input type="hidden" name="satuan_id[${key}]" value="${satuan.id}">

                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>${satuan.nama}</td>
                <td>${stock ? stock : '-'}</td>
                <td><input type="number" name="qty[${key}]" min="1" step="1" class="form-control" value="${qty}"></td>
                <td><input type="text" name="keterangan_item[${key}]" class="form-control"></td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupSRPharmacyClass.deleteItem(${key}, '${key_cache}')"></a></td>
            </tr>
        `;
    }

    /**
     * Delete item from table and variable
     * @param {string} key 
     * @param {string} key_cache
     */
    deleteItem(key, key_cache) {
        this.#$Table.find("#item" + key).remove();
        // remove from this.#KeyCache with value key_cache
        this.#KeyCache = this.#KeyCache.filter(item => item !== key_cache);
    }

    /**
    * Handle add button click
    * @param {Event} event 
    */
    async #handleAddButtonClick(event) {
        event.preventDefault();
        this.#$AddModal.modal("hide");
        const gudangAsalId = this.#$AsalGudangId.val();
        const gudangTujuanId = this.#$TujuanGudangId.val();
        if (!gudangAsalId || !gudangTujuanId) {
            showErrorAlertNoRefresh("Pilih gudang asal dan gudang tujuan terlebih dahulu!");
            return;
        }

        this.#showLoading(true);
        const url = `/get/item-gudang/${gudangAsalId}/${gudangTujuanId}`;
        const HTML = await (await this.#APIfetch(url, null, "GET", true)).text();
        this.#showLoading(false);

        this.#$ModalTable.html(HTML);
        this.#handleItemSearchBar(null);
        this.#$AddModal.modal("show");
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
     * Make a fetch call with API URL as base URL
     * @param {string} url 
     * @param {FormData | null} body 
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
                }
            })
                .then(async (response) => {
                    if (response.status != 200) {
                        throw new Error('Error: ' + response.statusText);
                    }
                    resolve(!raw ? await response.json() : response);
                })
                .catch(error => {
                    console.error('Error:', error);

                    // @ts-ignore
                    if (this.#showLoading)
                        this.#showLoading(false); // assert

                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
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

const PopupSRPharmacyClass = new PopupSRPharmacyHandler();