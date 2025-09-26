// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupDBPharmacyHandler {
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
    #$LoadingMessage;

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
    #$ItemSearch;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$KodeSR;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$SRId;

    /**
     * @type {string[]}
     */
    // @ts-ignore
    #KeyCache = window._key_caches ?? [];

    #API_URL = "/api/simrs/warehouse/distribusi-barang/pharmacy";

    constructor() {
        this.#$AsalGudangId = $("select[name='asal_gudang_id']");
        this.#$TujuanGudangId = $("select[name='tujuan_gudang_id']");
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$LoadingMessage = $("#loading-message");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$ItemSearch = $("#searchItemInput");
        this.#$KodeSR = $("input[name='kode_sr']");
        this.#$SRId = $("input[name='sr_id']");

        this.#init();
    }

    #init() {
        this.#addEventListeners("#add-btn", this.#handleAddButtonClick);
        this.#addEventListeners(
            "#searchItemInput",
            this.#handleItemSearchBar,
            "keyup"
        );
        this.#addEventListeners(
            "#searchSRInput",
            this.#handleSRSearch,
            "keyup"
        );
        this.#addEventListeners(
            "#searchSRAsalInput",
            this.#handleSRSearch,
            "keyup"
        );
        this.#addEventListeners(
            "#searchSRTujuanInput",
            this.#handleSRSearch,
            "keyup"
        );
        this.#addEventListeners(
            "#order-submit-draft",
            this.#handleDraftButtonClick
        );
        this.#addEventListeners(
            "#order-submit-final",
            this.#handleFinalButtonClick
        );
        this.#addEventListeners(
            "input[type='number']",
            this.enforceNumberLimit,
            "input"
        );
        $("#asal-gudang").on(
            "select2:select",
            this.#handleGudangChange.bind(this)
        );
        $("#tujuan-gudang").on(
            "select2:select",
            this.#handleGudangChange.bind(this)
        );
        this.#showLoading(false);
    }

    #reset() {
        this.#$Table.empty();
        this.#KeyCache = [];
        this.#$AsalGudangId.val("");
        this.#$TujuanGudangId.val("");
        this.#$KodeSR.val("");
        this.#$SRId.val("");
        // trigger change for both gudang
        this.#$AsalGudangId.trigger("change");
        this.#$TujuanGudangId.trigger("change");
    }

    /**
     * Handle Stock Request search bars change
     */
    #handleSRSearch() {
        let kode_sr = String($("#searchSRInput").val()).toLowerCase();
        let gudang_asal = String($("#searchSRAsalInput").val()).toLowerCase();
        let gudang_tujuan = String(
            $("#searchSRTujuanInput").val()
        ).toLowerCase();

        const items = document.querySelectorAll("tr.sr-row");
        items.forEach((item) => {
            const kodeSRElement = item.querySelector(".kode-sr");
            if (!kodeSRElement) return;
            const KodeSR = kodeSRElement.textContent;
            if (!KodeSR) return;

            const gudangAsalElement = item.querySelector(".gudang-asal-sr");
            if (!gudangAsalElement) return;
            const GudangAsal = gudangAsalElement.textContent;
            if (!GudangAsal) return;

            const gudangTujuanElement = item.querySelector(".gudang-tujuan-sr");
            if (!gudangTujuanElement) return;
            const GudangTujuan = gudangTujuanElement.textContent;
            if (!GudangTujuan) return;

            // @ts-ignore
            item.style.display =
                KodeSR.toLowerCase().includes(kode_sr) &&
                GudangAsal.toLowerCase().includes(gudang_asal) &&
                GudangTujuan.toLowerCase().includes(gudang_tujuan)
                    ? ""
                    : "none";
        });
    }

    /**
     *
     * @param {string} selector
     * @param {any} value
     * @returns
     */
    #select2HasOptionWithValue(selector, value) {
        // Check if the option with the specific value exists
        return $(selector).find(`option[value="${value}"]`).length > 0;
    }

    /**
     * Handle stock request select from modal selection
     * @param {StockRequest} sr
     */
    async SelectSR(sr) {
        // ensure the warehouse option exists in the current element
        if (
            !this.#select2HasOptionWithValue("#asal-gudang", sr.asal_gudang_id)
        ) {
            return showErrorAlertNoRefresh(
                "Maaf, gudang asal tidak ada di pilihan saat ini."
            );
        }
        // do the same with sr.tujuan_gudang_id
        if (
            !this.#select2HasOptionWithValue(
                "#tujuan-gudang",
                sr.tujuan_gudang_id
            )
        ) {
            return showErrorAlertNoRefresh(
                "Maaf, gudang tujuan tidak ada di pilihan saat ini."
            );
        }

        this.#reset();
        if (!sr.items) throw alert("Items not found!"); // dev error

        this.#showLoading(true, "Fetching item stocks...");
        for (let i = 0; i < sr.items.length; i++) {
            const sri = sr.items[i];
            const Item = /** @type {BarangFarmasi | undefined} */ (sri.barang);
            if (!Item) throw alert("Barang not found!"); // dev error
            const Satuan = sri.satuan;
            if (!Satuan) throw alert("Satuan not found!"); // dev error

            const Key = `${Item.id}/${Satuan.id}`;
            if (this.#KeyCache.includes(Key)) return; // ignore duplicate key

            // fetch stock with url api_base + /get/stock/{gudang_id}/{barang_id}/{satuan_id}
            const url = `/get/stock/${sr.asal_gudang_id}/${Item.id}/${Satuan.id}`;
            const stock = /** @type {{sis: StoredItem[], qty: number}} */ (
                await this.#APIfetch(url)
            ); // fetch stock from server

            let qty = sri.qty;
            if (stock.qty < sri.qty) {
                qty = stock.qty;
            }

            this.#KeyCache.push(Key); // add key to cache
            const HTML = this.#getItemTableCol(
                Item,
                Satuan,
                Key,
                qty,
                stock.qty,
                sri
            );
            this.#$Table.append(HTML);
        }
        this.#showLoading(false);

        this.#$AsalGudangId.val(sr.asal_gudang_id);
        this.#$TujuanGudangId.val(sr.tujuan_gudang_id);
        this.#$KodeSR.val(sr.kode_sr);
        this.#$SRId.val(sr.id);
        // trigger change jquery
        this.#$AsalGudangId.trigger("change");
        this.#$TujuanGudangId.trigger("change");
    }

    /**
     * Enforce number input min max limit on manual input
     * @param {Event} event
     */
    enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0)); // Default to 0 if not set
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER)); // Set default to a large number

        if (isNaN(value)) {
            inputField.value = ""; // Reset to empty on invalid input
            return;
        }

        if (value < min) {
            inputField.value = String(min); // Clamp value at min
        } else if (value > max) {
            inputField.value = String(max); // Clamp value at max
        }
    }

    #handleGudangChange() {
        this.#KeyCache = [];
        this.#$Table.empty();
        this.#$KodeSR.val("");
        this.#$SRId.val("");
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
            value = /** @type {string} */ (
                (this.#$ItemSearch.val()) || ""
            ).toLowerCase();
        }
        const items = document.querySelectorAll("tr.item");

        items.forEach((item) => {
            if (!item) return;
            const itemNameElement = item.querySelector(".item-name");
            if (!itemNameElement) return;
            const itemName = itemNameElement.textContent;
            if (!itemName) return;

            // @ts-ignore
            item.style.display = itemName.toLowerCase().includes(value)
                ? ""
                : "none";
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
        const selectedOption = row
            .find("select[name='satuan" + Item.id + "']")
            .find("option:selected");
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
     * @param {StockRequestItem?} sri
     */
    #getItemTableCol(item, satuan, key_cache, qty, stock = null, sri = null) {
        const key = Math.round(Math.random() * 100000);

        let SR_HTML_HIDDEN = "";
        if (sri) {
            SR_HTML_HIDDEN = /*html*/ `
                <input type="hidden" name="sri_id[${key}]" value="${sri.id}">
            `;
        }

        return /*html*/ `
            <tr id="item${key}">
                <input type="hidden" name="barang_id[${key}]" value="${
            item.id
        }">
                <input type="hidden" name="satuan_id[${key}]" value="${
            satuan.id
        }">
                ${SR_HTML_HIDDEN}

                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>${satuan.nama}</td>
                <td>${stock ? stock : "-"}</td>
                <td><input type="number" name="qty[${key}]" min="1" ${
            stock ? `max="${stock}"` : ""
        } step="1" class="form-control" value="${qty}"
                    onkeyup="PopupDBPharmacyClass.enforceNumberLimit(event)" onchange="PopupDBPharmacyClass.enforceNumberLimit(event)"></td>
                <td>${sri ? sri.keterangan || "" : ""}</td>
                <td><input type="text" name="keterangan_item[${key}]" class="form-control"></td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupDBPharmacyClass.deleteItem(${key}, '${key_cache}')"></a></td>
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
        this.#KeyCache = this.#KeyCache.filter((item) => item !== key_cache);
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
            showErrorAlertNoRefresh(
                "Pilih gudang asal dan gudang tujuan terlebih dahulu!"
            );
            return;
        }

        this.#showLoading(true, "Fetching warehouse stock...");
        const url = `/get/item-gudang/${gudangAsalId}/${gudangTujuanId}`;
        const HTML = await (
            await this.#APIfetch(url, null, "GET", true)
        ).text();
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
    #addEventListeners(selector, handler, event = "click") {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach((button) => {
            button.addEventListener(event, handler.bind(this));
        });
    }

    /**
     * Show or hide the loading icon
     * @param {boolean} show
     * @param {string?} message
     */
    #showLoading(show, message = null) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
        this.#$LoadingMessage.toggle(show);

        if (message) {
            this.#$LoadingMessage.text(message);
        } else {
            this.#$LoadingMessage.text("Loading...");
        }
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
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
            })
                .then(async (response) => {
                    if (response.status != 200) {
                        throw new Error("Error: " + response.statusText);
                    }
                    resolve(!raw ? await response.json() : response);
                })
                .catch((error) => {
                    console.log("Error:", error);

                    // @ts-ignore
                    if (this.#showLoading) this.#showLoading(false); // assert

                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }
}

const PopupDBPharmacyClass = new PopupDBPharmacyHandler();
