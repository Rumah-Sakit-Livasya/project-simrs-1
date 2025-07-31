// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupReturBarangHandler {

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$SupplierId;

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

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PPN;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PPN_Nominal;

    /**
     * @type {string[]}
     */
    #SelectedItems = [];

    #API_URL = "/api/simrs/warehouse/penerimaan-barang/retur-barang";

    constructor() {
        this.#$SupplierId = $("select[name='supplier_id']");
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$Total = $("#total-display");
        this.#$Nominal = $("input[name='nominal']");
        this.#$PPN = $("input[name='ppn']");
        this.#$PPN_Nominal = $("input[name='ppn_nominal']");

        this.#init();
    }

    #reset() {
        this.#SelectedItems = [];
        this.#$Table.empty();
        this.#$Total.text("0");
        this.#$Nominal.val("0");
        this.#$PPN.val("0");
        this.#$PPN_Nominal.val("0");
        this.refreshTotal();
    }

    #init() {
        this.#addEventListeners("#add-btn", this.#handleAddButtonClick);
        this.#addEventListeners("#searchItemInput", this.#handleItemSearchBar, "keyup");
        this.#addEventListeners("#searchPBInput", this.#handleItemSearchBar, "keyup");
        this.#addEventListeners("#searchNoFakturInput", this.#handleItemSearchBar, "keyup");
        this.#addEventListeners("input[name='ppn']", this.refreshTotal, "keyup");
        this.#$SupplierId.on('select2:select', this.#reset.bind(this));
        this.#showLoading(false);
    }

    /**
     * Handle item search bar on key up
     * @param {Event} event 
     */
    #handleItemSearchBar(event) {
        const searchInput = /** @type {HTMLInputElement} */ (event.target);
        const value = searchInput.value.toLowerCase();
        const items = document.querySelectorAll("tr.item");

        items.forEach((item) => {
            if (!item) return;

            let kode_pb = "";
            let no_faktur = "";
            let item_name = "";

            const itemNameElement = item.querySelector(".item-name");
            if (!itemNameElement) return;
            item_name = itemNameElement.textContent || "";

            const kodePBElement = item.querySelector(".kode-pb");
            if (!kodePBElement) return;
            kode_pb = kodePBElement.textContent || "";

            const noFakturElement = item.querySelector(".no-faktur");
            if (!noFakturElement) return;
            no_faktur = noFakturElement.textContent || "";

            if (kode_pb.toLowerCase().includes(value) ||
                no_faktur.toLowerCase().includes(value) ||
                item_name.toLowerCase().includes(value)) {
                // @ts-ignore
                item.style.display = "";
            } else {
                // @ts-ignore
                item.style.display = "none";
            }
        });
    }

    /**
     * Add selected item from modal to table
     * @param {StoredItem} Item 
     * @returns void
     */
    addItem(Item) {
        if (!Item.pbi || !Item.pbi.pb)
            return alert("PBI or PB not found!");

        const PBCode = Item.pbi.pb.kode_penerimaan + Item.pbi.batch_no;
        if (!PBCode)
            return alert("PB Code not found!");

        if (this.#SelectedItems.includes(PBCode)) {
            this.#$AddModal.modal("hide");
            return showErrorAlertNoRefresh("Barang sudah ada didalam daftar!");
        }

        let tipe = /** @type {"farmasi" | "non_farmasi"} */ ("farmasi");
        if (PBCode.includes("/UNGR/") || PBCode.includes("/URGR/"))
            tipe = "non_farmasi";
        console.log(tipe, PBCode);


        const HTML = this.#getItemTableCol(Item, tipe, PBCode);
        if (!HTML) return; // error occured and alerted

        this.#$Table.append(HTML);
        this.#SelectedItems.push(PBCode);
        this.refreshTotal();
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {StoredItem} item 
     * @param {"farmasi" | "non_farmasi"} tipe 
     * @param {string} code 
     */
    #getItemTableCol(item, tipe, code) {
        const key = Math.round(Math.random() * 100000);

        let type_column = "si_f_id";
        if (tipe == "non_farmasi")
            type_column = "si_nf_id";

        if (!item.pbi) return alert("PBI Not Found!");

        let harga = 0;
        if (item.pbi.diskon_nominal == 0) {
            harga = item.pbi.harga;
        } else {
            // we only know the total of the discount in nominal
            // we need to know what's the discount percentage
            let full_price = item.pbi.subtotal + item.pbi.diskon_nominal;
            let discount_percentage = (item.pbi.diskon_nominal / full_price) * 100;
            harga = item.pbi.harga - harga * (discount_percentage / 100);
        }

        // <th>Kode Barang</th>
        // <th>Nama Barang</th>
        // <th>No Faktur</th>
        // <th>No Batch</th>
        // <th>Satuan</th>
        // <th>Qty Terima</th>
        // <th>Telah Diretur</th>
        // <th>Stok</th>
        // <th>Qty Retur</th>
        // <th>Harga</th>
        // <th>Subtotal</th>
        // <th>Aksi</th>

        return /*html*/`
            <tr id="item${key}">
                <input type="hidden" name="item_type[${key}]" value="${type_column}">
                <input type="hidden" name="item_si_id[${key}]" value="${item.id}">
                <input type="hidden" name="item_harga[${key}]" value="${harga}">
                <input type="hidden" name="item_subtotal[${key}]" value="${item.pbi.qty * harga}">

                <td>${item.pbi.kode_barang}</td>
                <td>${item.pbi.nama_barang}</td>
                <td>${item.pbi.pb?.no_faktur}</td>
                <td>${item.pbi.batch_no}</td>
                <td>${item.pbi.unit_barang}</td>
                <td>${item.pbi.qty}</td>
                <td>Coming Soon!</td>
                <td>${item.qty}</td>
                <td><input type="number" name="item_qty[${key}]" min="0" step="1" max="${item.qty}" class="form-control" value="${item.qty}"
                    onkeyup="PopupReturBarangClass.enforceNumberLimit(event).refreshTotal()" onchange="PopupReturBarangClass.enforceNumberLimit(event).refreshTotal()"></td>
                <td>${this.#rp(harga)}</td>
                <td class="subtotal">${this.#rp(item.pbi.qty * harga)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupReturBarangClass.deleteItem(${key}, ${code})"></a></td>
            </tr>
        `;
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
            return this;
        }

        if (value < min) {
            inputField.value = String(min);  // Clamp value at min
        } else if (value > max) {
            inputField.value = String(max);  // Clamp value at max
        }

        return this;
    }

    refreshTotal() {
        console.log("refreshing total...");

        let total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=item_qty]");
            const hnaEl = $(tr).find("input[name^=item_harga]");
            if (!qtyEl || !hnaEl) return alert("Element not found!");

            const qty = parseInt(String(qtyEl.val()));
            const hna = parseInt(String(hnaEl.val()));
            if (isNaN(qty) || isNaN(hna)) return alert("Qty or HNA is not a number!");

            let subtotal = qty * hna;
            console.log(qty, hna, subtotal);

            total += subtotal;
            $(tr).find("td.subtotal").text(this.#rp(subtotal));
            $(tr).find("input[name^=item_subtotal]").val(subtotal);
        });
        const PPN = /** @type {number} */ (this.#$PPN.val() || 0);
        const PPN_Nominal = total * (PPN / 100);
        total += PPN_Nominal;

        this.#$Total.text(this.#rp(total));
        this.#$Nominal.val(total);
        this.#$PPN_Nominal.val(PPN_Nominal);
    }

    /**
     * Delete item from table and variable
     * @param {string} key 
     * @param {string} code 
     */
    deleteItem(key, code) {
        this.#$Table.find("#item" + key).remove();
        // remove an item from this.#SelectedItems
        // where value is code
        this.#SelectedItems = this.#SelectedItems.filter(item => item !== code);

        this.refreshTotal();
    }

    /**
    * Handle add button click
    * @param {Event} event 
    */
    async #handleAddButtonClick(event) {
        event.preventDefault();
        const supplierId = /** @type {string} */ (this.#$SupplierId.val());
        if (!supplierId) {
            showErrorAlertNoRefresh("Pilih supplier terlebih dahulu!");
            return;
        }

        this.#showLoading(true);
        const url = "/get/item-supplier/" + supplierId;

        const HTML = await (await this.#APIfetch(url, null, "GET", true)).text();
        this.#showLoading(false);

        this.#$ModalTable.html(HTML);
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

const PopupReturBarangClass = new PopupReturBarangHandler();