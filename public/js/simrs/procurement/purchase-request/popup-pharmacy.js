// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupPRPharmacyHandler {
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

    #API_URL = "/api/simrs/procurement/purchase-request/pharmacy";

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
        this.#addEventListeners("#add-btn", this.#handleAddButtonClick);
        this.#addEventListeners(
            "#searchItemInput",
            this.#handleItemSearchBar,
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
        this.#showLoading(false);
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
     * @param {Event} event
     */
    #handleItemSearchBar(event) {
        const searchInput = /** @type {HTMLInputElement} */ (event.target);
        const value = searchInput.value.toLowerCase();
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

    addItem(id) {
        const row = this.#$ModalTable.find("tr.item#" + id);

        const QtyString = row.find("input.qty").val();
        if (!QtyString) {
            showErrorAlertNoRefresh("Quantitas tidak dapat ditemukan!");
            return;
        }
        const Qty = parseInt(String(QtyString));
        if (Qty <= 0) {
            showErrorAlertNoRefresh("Jumlah tidak boleh 0!");
            return;
        }

        const Item = /** @type {BarangFarmasi} */ (row.data("item"));
        const selectedOption = row
            .find("select[name='satuan" + Item.id + "']")
            .find("option:selected");
        const Satuan = /** @type {Satuan} */ (selectedOption.data("satuan"));
        const HTML = this.#getItemTableCol(Item, Satuan, Qty);
        this.#$Table.append(HTML);
        this.refreshTotal();
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {BarangFarmasi} item
     * @param {Satuan} satuan
     * @param {number} qty
     */
    #getItemTableCol(item, satuan, qty) {
        const key = Math.round(Math.random() * 100000);

        return /*html*/ `
            <tr id="item${key}">
                <td>${item.kode}
                    <input type="hidden" name="kode_barang[${key}]" value="${
            item.kode
        }">
                </td>
                <td>${item.nama}
                    <input type="hidden" name="nama_barang[${key}]" value="${
            item.nama
        }">
                    <input type="hidden" name="barang_id[${key}]" value="${
            item.id
        }">
                </td>
                <td>${satuan.nama}
                    <input type="hidden" name="unit_barang[${key}]" value="${
            satuan.nama
        }">
                    <input type="hidden" name="satuan_id[${key}]" value="${
            satuan.id
        }">
                </td>
                <td><input type="text" name="keterangan_item[${key}]" class="form-control"></td>
                <td><input type="number" name="qty[${key}]" min="0" step="1" class="form-control" value="${qty}"
                    onkeyup="PopupPRPharmacyClass.refreshTotal()" onchange="PopupPRPharmacyClass.refreshTotal()"></td>
                <td>${this.#rp(item.hna)}
                    <input type="hidden" name="hna[${key}]" value="${item.hna}">
                </td>
                <td class="subtotal">${this.#rp(item.hna * qty)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupPRPharmacyClass.deleteItem(${key})"></a></td>
            </tr>
        `;
    }

    refreshTotal() {
        let total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=qty]");
            const hnaEl = $(tr).find("input[name^=hna]");
            if (!qtyEl || !hnaEl) return;

            const qty = parseInt(String(qtyEl.val()));
            const hna = parseInt(String(hnaEl.val()));
            if (isNaN(qty) || isNaN(hna)) return;

            total += qty * hna;
            $(tr)
                .find("td.subtotal")
                .text(this.#rp(qty * hna));
            this.#$Total.text(this.#rp(total));
        });
        this.#$Total.text(this.#rp(total));
        this.#$Nominal.val(total);
    }

    /**
     * Delete item from table and variable
     * @param {string} key
     */
    deleteItem(key) {
        this.#$Table.find("#item" + key).remove();
        this.refreshTotal();
    }

    /**
     * Handle add button click
     * @param {Event} event
     */
    async #handleAddButtonClick(event) {
        event.preventDefault();
        const gudangId = this.#$GudangId.val();
        if (!gudangId) {
            showErrorAlertNoRefresh("Pilih gudang terlebih dahulu!");
            return;
        }

        this.#showLoading(true);
        const url = "/get/item-gudang/" + gudangId;
        const HTML = await (
            await this.#APIfetch(url, null, "GET", true)
        ).text();
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
    #addEventListeners(selector, handler, event = "click") {
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

    /**
     * Format angka menjadi mata uang rupiah
     * @param {number} amount
     * @returns
     */
    #rp(amount) {
        const formattedAmount = "Rp " + amount.toLocaleString("id-ID");
        return formattedAmount;
    }
}

const PopupPRPharmacyClass = new PopupPRPharmacyHandler();
