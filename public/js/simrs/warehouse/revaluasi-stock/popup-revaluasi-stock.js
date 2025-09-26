// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupRevaluasiStockHandler {
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
    #$Gudang;

    /**
     * @type {import("datatables.net").Api<any>}
     */
    #$Datatable;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TableBody;

    /**
     * @type {string}
     */
    #Token;

    /**
     * @type {{pharmacy: StoredItem[], non_pharmacy: StoredItem[]}}
     */
    #StoredItems = { pharmacy: [], non_pharmacy: [] };

    #API_URL = "/api/simrs/warehouse/revaluasi-stock/stock-adjustment";

    constructor() {
        this.#$LoadingIcon = $("#loading-spinner-head");
        this.#$LoadingPage = $("#loading-page");
        this.#$LoadingMessage = $("#loading-message");
        this.#$Gudang = $("#gudang");
        this.#$Datatable = $("#dt-basic-example").DataTable();
        this.#$TableBody = $("#table-body");
        this.#Token = /** @type {string} */ ($("[name='_token']").val());

        this.#$Gudang.on("select2:select", this.#handleGudangChange.bind(this));

        // vanilla js document on ready
        // call #init
        document.addEventListener("DOMContentLoaded", () => {
            this.#init();
        });
    }

    #init() {
        this.#showLoading(false);
    }

    async #handleGudangChange() {
        this.#showLoading(true, "Loading data...");
        const url = `/get/item-gudang/${this.#Token}/${this.#$Gudang.val()}`;
        this.#StoredItems = await this.#APIfetch(url);
        console.log(this.#StoredItems);
        this.#showLoading(false);

        this.#refreshTable();
    }

    #refreshTable() {
        this.#showLoading(true, "Refreshing table...");
        this.#$Datatable.clear();
        const StoredItemsSortedPharmacy = /** @type {StoredItem[]} */ ([]);
        this.#StoredItems.pharmacy.forEach((StoredItem) => {
            const Barang = StoredItem.pbi?.barang_id;
            const Satuan = StoredItem.pbi?.satuan_id;

            let push = true;
            StoredItemsSortedPharmacy.filter(
                (item) =>
                    item.pbi?.barang_id === Barang &&
                    item.pbi?.satuan_id === Satuan
            ).forEach((item) => {
                push = false;
                item.qty += StoredItem.qty;
            });

            if (push) StoredItemsSortedPharmacy.push(StoredItem);
        });

        const StoredItemsSortedNonPharmacy = /** @type {StoredItem[]} */ ([]);
        this.#StoredItems.non_pharmacy.forEach((StoredItem) => {
            const Barang = StoredItem.pbi?.barang_id;
            const Satuan = StoredItem.pbi?.satuan_id;

            let push = true;
            StoredItemsSortedNonPharmacy.filter(
                (item) =>
                    item.pbi?.barang_id === Barang &&
                    item.pbi?.satuan_id === Satuan
            ).forEach((item) => {
                push = false;
                item.qty += StoredItem.qty;
            });

            if (push) StoredItemsSortedNonPharmacy.push(StoredItem);
        });

        let index = 0;
        StoredItemsSortedPharmacy.forEach((item) => {
            const HTML = this.#getStoredItemCol(item, "f", index++);
            this.#$Datatable.row.add($(HTML)).draw();
        });
        StoredItemsSortedNonPharmacy.forEach((item) => {
            const HTML = this.#getStoredItemCol(item, "nf", index++);
            this.#$Datatable.row.add($(HTML)).draw();
        });
        this.#showLoading(false);
    }

    /**
     * Get HTML for a stored item in string
     * @param {StoredItem} item
     * @param {"f" | "nf"} type
     * @param {number} index
     */
    #getStoredItemCol(item, type, index) {
        const key = Math.round(Math.random() * 100000);

        // <th>#</th>
        // <th>Tipe Barang</th>
        // <th>Kode Barang</th>
        // <th>Nama Barang</th>
        // <th>Satuan</th>
        // <th>Golongan</th>
        // <th>Kategori</th>
        // <th>Stok</th>
        // <th>Aksi</th>

        return /*html*/ `
            <tr id="item${key}">
                <td>${index + 1}</td>
                <td>${type == "f" ? "Farmasi" : "Non Farmasi"}</td>
                <td>${item.pbi?.item?.kode}</td>
                <td>${item.pbi?.item?.nama}</td>
                <td>${item.pbi?.satuan?.nama}</td>
                <td>${item.pbi?.item?.golongan?.nama}</td>
                <td>${item.pbi?.item?.kategori?.nama}</td>
                <td>${item.qty}</td>
                <td><a class="mdi mdi-pencil pointer mdi-24px text-primary delete-btn"
                        title="Edit Stock" onclick="PopupRevaluasiStockClass.editStock(${
                            item.pbi?.barang_id
                        }, ${item.pbi?.satuan_id}, '${type}')"></a></td>
            </tr>
        `;
    }

    /**
     * Create new popup to edit stock
     * @param {number} barang_id
     * @param {number} satuan_id
     * @param {"f" | "nf"} type
     */
    editStock(barang_id, satuan_id, type) {
        console.log(arguments);
        const url = `/simrs/warehouse/revaluasi-stock/stock-adjustment/edit/${this.#$Gudang.val()}/${barang_id}/${satuan_id}/${type}/${
            this.#Token
        }`;
        const width = screen.width;
        const height = screen.height;
        const left = width - width / 2;
        const top = height - height / 2;
        window.open(
            url,
            "popupWindow_editStockAdjustment",
            "width=" +
                width +
                ",height=" +
                height +
                ",scrollbars=yes,resizable=yes,left=" +
                left +
                ",top=" +
                top
        );
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
     * Make a fetch call with API URL as base URL
     * @param {string} url
     * @param {FormData | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        console.log(this.#API_URL + url);

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

const PopupRevaluasiStockClass = new PopupRevaluasiStockHandler();
