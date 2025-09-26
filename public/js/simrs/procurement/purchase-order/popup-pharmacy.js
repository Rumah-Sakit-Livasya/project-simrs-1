// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupPOPharmacyHandler {
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
    #$DiscountTotal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$GrandTotal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Nominal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PPN;

    /**
     * @type {SumberItem}
     */
    #SumberItem = "pr";

    /**
     * @type {TipePR}
     */
    #TipePR = "all";

    #API_URL = "/api/simrs/procurement/purchase-order/pharmacy";

    constructor() {
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$Total = $("#harga-display");
        this.#$DiscountTotal = $("#discount-display");
        this.#$GrandTotal = $("#total-display");
        this.#$Nominal = $("input[name='nominal']");
        this.#$PPN = $("input[name='ppn']");

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
        this.#addEventListeners(
            "#tipe-pr-select",
            this.#handleTipePrChange,
            "change"
        );
        this.#addEventListeners(
            "#sumber-item-select",
            this.#handleSumberItemChange,
            "change"
        );
        this.#addEventListeners("#ppn-input", this.refreshTotal, "change");
        this.#addEventListeners("#ppn-input", this.refreshTotal, "keyup");
        this.#addEventListeners(
            "input[type='number']",
            this.enforceNumberLimit,
            "input"
        );
        this.#showLoading(false);
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

    /**
     * Handle modal tipe pr change
     * @param {Event} event
     */
    #handleTipePrChange(event) {
        // get value of the selected option
        const select = /** @type {HTMLSelectElement} */ (event.target);
        const value = select.value;

        this.#TipePR = /** @type {TipePR} */ (value);
        this.#loadAddItemModal();
    }

    /**
     * Handle modal sumber item change
     * @param {Event} event
     */
    #handleSumberItemChange(event) {
        // get value of the selected option
        const select = /** @type {HTMLSelectElement} */ (event.target);
        const value = select.value;

        this.#SumberItem = /** @type {SumberItem} */ (value);
        this.#loadAddItemModal();
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
        const trId = row.attr("id");
        const Item = /** @type {BarangFarmasi} */ (row.data("item"));
        const KodePR = row.data("kode_pr");
        const IdPR = row.data("id_pr");
        const MaxQTy = parseInt(row.data("max_qty")) || 999999999;
        const selectedOption = row
            .find("select[name='satuan" + trId + "']")
            .find("option:selected");
        const Satuan = /** @type {Satuan} */ (selectedOption.data("satuan"));
        const HTML = this.#getItemTableCol(
            Item,
            Satuan,
            Qty,
            MaxQTy,
            KodePR,
            IdPR
        );
        this.#$Table.append(HTML);
        this.refreshTotal();
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {BarangFarmasi} item
     * @param {Satuan} satuan
     * @param {number} qty
     * @param {number} max_qty
     * @param {string} kode_pr
     * @param {string} IdPR
     */
    #getItemTableCol(item, satuan, qty, max_qty, kode_pr, IdPR) {
        const key = Math.round(Math.random() * 100000);

        return /*html*/ `
            <tr id="item${key}">
                <input type="hidden" name="kode_barang[${key}]" value="${
            item.kode
        }">
                <input type="hidden" name="nama_barang[${key}]" value="${
            item.nama
        }">
                <input type="hidden" name="barang_id[${key}]" value="${
            item.id
        }">
                <input type="hidden" name="unit_barang[${key}]" value="${
            satuan.nama
        }">
                <input type="hidden" name="hna[${key}]" value="${item.hna}">
                <input type="hidden" name="pri_id[${key}]" value="${
            IdPR || ""
        }">

                <td>${kode_pr || ""}</td>
                <td>${item.nama}</td>
                <td>${satuan.nama}</td>
                <td><input type="number" name="qty[${key}]" min="0" step="1" class="form-control" value="${qty}" max="${max_qty}"
                    onkeyup="PopupPOPharmacyClass.refreshTotal()" onchange="PopupPOPharmacyClass.refreshTotal()"></td>
                <td><input type="number" name="qty_bonus[${key}]" min="0" step="1" class="form-control" value="0"
                    onkeyup="PopupPOPharmacyClass.refreshTotal()" onchange="PopupPOPharmacyClass.refreshTotal()"></td>
                <td class="harga_total">${this.#rp(item.hna * qty)}</td>
                <td class="discount_percent">
                    <input type="number" name="discount_percent[${key}]" min="0" step="1" class= "form-control" value="0"
                    onkeyup="PopupPOPharmacyClass.discountPercentChange(event)" onchange="PopupPOPharmacyClass.discountPercentChange(event)">
                <td class="discount_rp">
                    <input type="number" name="discount_nominal[${key}]" min="0" step="1" class= "form-control" value="0"
                    onkeyup="PopupPOPharmacyClass.discountNominalChange(event)" onchange="PopupPOPharmacyClass.discountNominalChange(event)"></td>
                <td class="subtotal">${this.#rp(item.hna * qty)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupPOPharmacyClass.deleteItem(${key})"></a></td>
            </tr>
        `;
    }

    /**
     * Handle discount percent input change
     * @param {Event} event
     */
    discountPercentChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const discountPercent = parseInt(input.value);
        if (isNaN(discountPercent)) return;

        const tr = input.closest("tr");
        if (!tr) return alert("TR Not found!");

        const dscnEl = $(tr).find("input[name^=discount_nominal]");
        if (!dscnEl) return alert("Input Not found!");

        const hargaEl = $(tr).find("td.harga_total");
        if (!hargaEl) return alert("Harga Not found!");
        const harga = parseInt(
            hargaEl.text().replaceAll("Rp", "").replaceAll(".", "")
        );

        dscnEl.val((discountPercent * harga) / 100);

        this.refreshTotal({ updateDiscount: false });
    }

    /**
     * Handle discount nominal input change
     * @param {Event} event
     */
    discountNominalChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const discountNominal = parseInt(input.value);
        if (isNaN(discountNominal)) return;

        const tr = input.closest("tr");
        if (!tr) return alert("TR Not found!");

        const dscpEl = $(tr).find("input[name^=discount_percent]");
        if (!dscpEl) return alert("Input Not found!");

        const hargaEl = $(tr).find("td.harga_total");
        if (!hargaEl) return alert("Harga Not found!");
        const harga = parseInt(
            hargaEl.text().replaceAll("Rp", "").replaceAll(".", "")
        );

        dscpEl.val((discountNominal / harga) * 100);

        this.refreshTotal({ updateDiscount: false });
    }

    refreshTotal(option = { updateDiscount: true }) {
        let total = 0;
        let grandtotal = 0;
        let discount_total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=qty]");
            const qtybEl = $(tr).find("input[name^=qty_bonus]");
            const hnaEl = $(tr).find("input[name^=hna]");
            const dscnEl = $(tr).find("input[name^=discount_nominal]");
            const dscnpEl = $(tr).find("input[name^=discount_percent]");
            if (!qtyEl || !hnaEl || !dscnEl || !dscnpEl || !qtybEl) return;

            const qty = parseInt(String(qtyEl.val()));
            const hna = parseInt(String(hnaEl.val()));
            const qtyb = parseInt(String(qtybEl.val()));
            const dscn = parseInt(String(dscnEl.val()));
            const dscnp = parseInt(String(dscnpEl.val()));
            if (
                isNaN(qty) ||
                isNaN(hna) ||
                isNaN(qtyb) ||
                isNaN(dscn) ||
                isNaN(dscnp)
            )
                return;

            const harga = qty * hna;

            if (option.updateDiscount) {
                dscnpEl.val((dscn / harga) * 100);
                dscnEl.val((dscnp * harga) / 100);
            }

            const subtotal = harga - dscn;
            total += harga;
            grandtotal += subtotal;
            discount_total += dscn;
            $(tr).find("td.harga_total").text(this.#rp(harga));
            $(tr).find("td.subtotal").text(this.#rp(subtotal));
        });
        const PPN = parseInt(String(this.#$PPN.val()));
        grandtotal += (grandtotal * PPN) / 100;

        this.#$Total.text(this.#rp(total));
        this.#$DiscountTotal.text(this.#rp(discount_total));
        this.#$GrandTotal.text(this.#rp(grandtotal));
        this.#$Nominal.val(grandtotal);
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
    #handleAddButtonClick(event) {
        event.preventDefault();
        this.#loadAddItemModal();
    }

    async #loadAddItemModal() {
        this.#$AddModal.modal("hide");
        this.#showLoading(true);
        const url = "/get/items";

        const body = JSON.stringify({
            sumber_item: this.#SumberItem,
            tipe_pr: this.#TipePR,
        });

        const response = await this.#APIfetch(url, body, "PATCH", true);
        const HTML = await response.text();
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
     * @param {any | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    "Content-Type": "application/json",
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

const PopupPOPharmacyClass = new PopupPOPharmacyHandler();
