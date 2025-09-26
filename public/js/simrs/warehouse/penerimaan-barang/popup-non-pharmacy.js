// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupPBNPharmacyHandler {
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
    #$diskonTotal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Total;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TotalFinalDisplay;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TotalFinal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PPN;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PPNNominal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Materai;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$DiskonFaktur;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$NoFaktur;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TipeTerima;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$KodePO;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$POid;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PICPenerima;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TipeBayar;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Supplier;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$SupplierId;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Keterangan;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Kas;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$TanggalFaktur;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Gudang;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$SelectPOButton;

    #API_URL = "/api/simrs/procurement/penerimaan-barang/non-pharmacy";

    constructor() {
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$diskonTotal = $("#diskon-display");
        this.#$TotalFinalDisplay = $("#total-display");
        this.#$TotalFinal = $("input[name='total_final']");
        this.#$Total = $("input[name='total']");
        this.#$Materai = $("input[name='materai']");
        this.#$DiskonFaktur = $("input[name='diskon_faktur']");
        this.#$PPN = $("input[name='ppn']");
        this.#$PPNNominal = $("input[name='ppn_nominal']");
        this.#$NoFaktur = $("input[name='no_faktur']");
        this.#$TipeTerima = $("#tipe_terima");
        this.#$KodePO = $("input[name='kode_po']");
        this.#$POid = $("input[name='po_id']");
        this.#$PICPenerima = $("input[name='pic_penerima']");
        this.#$TipeBayar = $("select[name='tipe_bayar']");
        this.#$Supplier = $("#supplier");
        this.#$SupplierId = $("input[name='supplier_id']");
        this.#$Keterangan = $("input[name='keterangan']");
        this.#$Kas = $("input[name='kas']");
        this.#$TanggalFaktur = $("input[name='tanggal_faktur']");
        this.#$Gudang = $("#gudang");
        this.#$SelectPOButton = $("#select-po-btn");

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
            "#searchPOInput",
            this.#handlePOSearchBar,
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
            "#tipe_terima",
            this.#handleTipeTerimaChange,
            "change"
        );
        this.#addEventListeners(
            "select[name='tipe_bayar']",
            this.#handleTipeBayarChange,
            "change"
        );
        this.#addEventListeners(
            "input[type='number']",
            this.refreshTotal,
            "input"
        );
        this.#addEventListeners(
            "input[type='number']",
            this.enforceNumberLimit,
            "input"
        );
        this.#addEventListeners(
            "#supplier",
            this.#handleSupplierChange,
            "select2:select"
        );
        this.#$Supplier.on(
            "select2:select",
            this.#handleSupplierChange.bind(this)
        );
        this.#showLoading(false);
    }

    /**
     * Handle supplier select change
     * @param {JQuery.Event} event
     */
    #handleSupplierChange(event) {
        // @ts-ignore
        const id = event.params.data.id;
        this.#$SupplierId.val(id);
    }

    /**
     * Handle tipe bayar select change
     * @param {Event} event
     */
    #handleTipeBayarChange(event) {
        const Select = /**@type {HTMLSelectElement} */ (event.target);
        if (Select.value == "cash") {
            // enable #$Kas
            this.#$Kas.removeAttr("disabled");
        } else {
            this.#$Kas.attr("disabled", "disabled");
        }
    }

    /**
     * Handle tipe terima select change
     * @param {Event} event
     */
    #handleTipeTerimaChange(event) {
        this.#reset();
        // if selected option value == po
        // disable #$Kas, hide #$SelectPOButton, and enable #$Supplier
        // all of them are JQuery selector variable
        // and vice versa
        const Select = /**@type {HTMLSelectElement} */ (event.target);
        if (Select.value === "npo") {
            this.#$Kas.prop("disabled", true);
            this.#$SelectPOButton.hide();
            this.#$Supplier.prop("disabled", false);
        } else {
            this.#$Kas.prop("disabled", false);
            this.#$SelectPOButton.show();
            this.#$Supplier.prop("disabled", true);
        }
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
            return this;
        }

        if (value < min) {
            inputField.value = String(min); // Clamp value at min
        } else if (value > max) {
            inputField.value = String(max); // Clamp value at max
        }

        return this;
    }

    /**
     * Handle modal select PO event
     * @param {PurchaseOrder} po
     */
    SelectPO(po) {
        this.#reset(); // reset to initial state

        // first, update value of #$KodePO and #$POid in the class
        this.#$POid.val(po.id);
        this.#$KodePO.val(po.kode_po);

        // next, change the selected #$Supplier option
        this.#$SupplierId.val(po.supplier_id);
        this.#$Supplier.val(po.supplier_id);
        // trigger Select2 change
        this.#$Supplier.trigger("change");

        // update ppn too
        this.#$PPN.val(po.ppn);

        // finally, for each item, give it to addPOItem
        po.items?.forEach((item) => this.#addPOItem(item)); // addPOItem is a method that will be defined later
        this.refreshTotal();
    }

    #reset() {
        // empty the table
        this.#$Table.empty(); // #$Table is a reference to the table element in the class
        // refresh total
        this.refreshTotal();
        // also empty NoFaktur, TanggalFaktur, PICPenerima, Keterangan, Gudang, Supplier, KodePO, POid
        this.#$NoFaktur.val("");
        this.#$TanggalFaktur.val("");
        this.#$PICPenerima.val("");
        this.#$Keterangan.val("");
        this.#$Gudang.val("");
        this.#$Supplier.val("");
        this.#$SupplierId.val("");
        this.#$KodePO.val("");
        this.#$POid.val("");
        this.#$DiskonFaktur.val(0);
        this.#$PPN.val(0);
        this.#$PPNNominal.val(0);
        this.#$Total.val(0);
        this.#$TotalFinal.val(0);
        this.#$TotalFinalDisplay.val(0);
        this.#$diskonTotal.val(0);

        // trigger change on Gudang and Supplier
        this.#$Gudang.trigger("change");
        this.#$Supplier.trigger("change");
    }

    /**
     * Add item from PO select
     * @param {ItemPO} item
     */
    #addPOItem(item) {
        // if qty equals or more than received qty, return
        if (item.qty <= item.qty_received) return;
        const HTML = this.#getItemPOTableCol(item);
        this.#$Table.append(HTML);
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

    /**
     * Handle PO search bar on key up
     * @param {Event} event
     */
    #handlePOSearchBar(event) {
        const searchInput = /** @type {HTMLInputElement} */ (event.target);
        const value = searchInput.value.toLowerCase();
        const items = document.querySelectorAll("tr.po-row");

        items.forEach((item) => {
            if (!item) return;

            let kode_po = "";
            let supplier_po = "";

            const kodePOElement = item.querySelector(".kode-po");
            if (!kodePOElement) return;
            kode_po = kodePOElement.textContent || "";

            const supplierPOElement = item.querySelector(".supplier-po");
            if (!supplierPOElement) return;
            supplier_po = supplierPOElement.textContent || "";

            // @ts-ignore
            if (
                kode_po.toLowerCase().includes(value) ||
                supplier_po.toLowerCase().includes(value)
            ) {
                // @ts-ignore
                item.style.display = "";
            } else {
                // @ts-ignore
                item.style.display = "none";
            }
        });
    }

    /**
     * Add item from item select
     * @param {BarangNonFarmasi} barang
     */
    addItem(barang) {
        const HTML = this.#getItemTableCol(barang);
        this.#$Table.append(HTML);
        this.refreshTotal();
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {BarangNonFarmasi} item
     */
    #getItemTableCol(item) {
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
            item.satuan?.nama
        }">
                <input type="hidden" name="satuan_id[${key}]" value="${
            item.satuan_id
        }">
                <input type="hidden" name="subtotal[${key}]" value="0">

                <td><input type="checkbox" class="form-control" name="is_bonus[${key}]" onclick="PopupPBNPharmacyClass.refreshTotal()"></td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>${item.satuan?.nama}</td>
                <td><input type="date" name="tanggal_exp[${key}]" class="form-control"></td>
                <td><input type="text" name="batch_no[${key}]" class="form-control" required></td>
                <td> - </td>
                <td> - </td>
                <td><input type="number" name="qty[${key}]" class="form-control qty" min="0" step="1"
                 onkeyup="PopupPBNPharmacyClass.enforceNumberLimit(event).refreshTotal()" onchange="PopupPBNPharmacyClass.enforceNumberLimit(event).refreshTotal()" required></td>
                <td>${this.#rp(item.hna || 0)}</td>
                <td><input type="number" name="harga[${key}]" class="form-control" value="${
            item.hna || ""
        }"
                 onkeyup="PopupPBNPharmacyClass.refreshTotal()" onchange="PopupPBNPharmacyClass.refreshTotal()" required></td>
                <td><input type="number" name="diskon_percent[${key}]" class="form-control" min="0" value="0" step="1" max="100"
                 onkeyup="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)" onchange="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)"></td>
                <td><input type="number" name="diskon_nominal[${key}]" min="0" step="1" value="0" class="form-control"
                 onkeyup="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)" onchange="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)"></td>
                <td class="subtotal-display">Rp 0</td>
                <td>
                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupPBNPharmacyClass.deleteItem(${key})"></a>
                </td>
            </tr>
        `;
    }

    /**
     * Generate HTML string for PO Item table collumn
     * @param {ItemPO} item
     */
    #getItemPOTableCol(item) {
        const key = Math.round(Math.random() * 100000);

        return /*html*/ `
            <tr id="item${key}">
                <input type="hidden" name="kode_barang[${key}]" value="${
            item.kode_barang
        }">
                <input type="hidden" name="nama_barang[${key}]" value="${
            item.nama_barang
        }">
                <input type="hidden" name="barang_id[${key}]" value="${
            item.barang_id
        }">
                <input type="hidden" name="unit_barang[${key}]" value="${
            item.unit_barang
        }">
                <input type="hidden" name="satuan_id[${key}]" value="${
            item.barang?.satuan_id
        }">
                <input type="hidden" name="poi_id[${key}]" value="${item.id}">
                <input type="hidden" name="subtotal[${key}]" value="0">

                <td><input type="checkbox" class="form-control" name="is_bonus[${key}]" onclick="PopupPBNPharmacyClass.refreshTotal()"></td>
                <td>${item.kode_barang}</td>
                <td>${item.nama_barang}</td>
                <td>${item.unit_barang}</td>
                <td><input type="date" name="tanggal_exp[${key}]" class="form-control"></td>
                <td><input type="text" name="batch_no[${key}]" class="form-control" required></td>
                <td>${item.qty}</td>
                <td>${item.qty - item.qty_received}</td>
                <td><input type="number" name="qty[${key}]" class="form-control qty" min="0" step="1" max="${
            item.qty - item.qty_received
        }"
                 onkeyup="PopupPBNPharmacyClass.enforceNumberLimit(event).refreshTotal()" onchange="PopupPBNPharmacyClass.enforceNumberLimit(event).refreshTotal()" required></td>
                <td>${this.#rp(item.barang?.hna || 0)}</td>
                <td><input type="number" name="harga[${key}]" class="form-control" value="${
            item.barang?.hna || ""
        }"
                 onkeyup="PopupPBNPharmacyClass.refreshTotal()" onchange="PopupPBNPharmacyClass.refreshTotal()" required></td>
                <td><input type="number" name="diskon_percent[${key}]" class="form-control" min="0" value="0" step="1" max="100"
                 onkeyup="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)" onchange="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)"></td>
                <td><input type="number" name="diskon_nominal[${key}]" min="0" step="1" value="0" class="form-control"
                 onkeyup="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)" onchange="PopupPBNPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)"></td>
                <td class="subtotal-display">Rp 0</td>
                <td>
                    <!-- <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupPBNPharmacyClass.deleteItem(${key})"></a> -->
                </td>
            </tr>
        `;
    }

    /**
     * Handle diskon percent input change
     * @param {Event} event
     */
    diskonPercentChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const diskonPercent = parseInt(input.value);
        if (isNaN(diskonPercent)) return this;

        const tr = input.closest("tr");
        if (!tr) {
            alert("TR Not found!");
            return this;
        }

        const dscnEl = $(tr).find("input[name^=diskon_nominal]");
        if (!dscnEl) {
            alert("Input Not found!");
            return this;
        }

        const qty = /** @type {number | undefined} */ (
            $(tr).find("input[name^=qty]").val()
        );
        // if quantity is not set, skip calculating this row
        if (!qty) return;

        const cost = /** @type {number | undefined} */ (
            $(tr).find("input[name^=harga]").val()
        );
        // if cost is not set, skip calculating this row
        if (!cost) return;

        const harga = qty * cost;

        dscnEl.val((diskonPercent * harga) / 100);

        this.refreshTotal({ updatediskon: false });
        return this;
    }

    /**
     * Handle diskon nominal input change
     * @param {Event} event
     */
    diskonNominalChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const diskonNominal = parseInt(input.value);
        if (isNaN(diskonNominal)) return;

        const tr = input.closest("tr");
        if (!tr) {
            alert("TR Not found!");
            return this;
        }

        const dscpEl = $(tr).find("input[name^=diskon_percent]");
        if (!dscpEl) {
            alert("Input Not found!");
            return this;
        }

        const qty = /** @type {number | undefined} */ (
            $(tr).find("input[name^=qty]").val()
        );
        // if quantity is not set, skip calculating this row
        if (!qty) return;

        const cost = /** @type {number | undefined} */ (
            $(tr).find("input[name^=harga]").val()
        );
        // if cost is not set, skip calculating this row
        if (!cost) return;

        const harga = qty * cost;

        dscpEl.val((diskonNominal / harga) * 100);

        this.refreshTotal({ updatediskon: false });
        return this;
    }

    refreshTotal(option = { updatediskon: true }) {
        let total = 0;
        let diskon_total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const bonusCheckbox = $(tr).find("input[name^=is_bonus]");
            console.log(bonusCheckbox.is(":checked"));

            // if bonus is checked, skip calculating this row
            if (bonusCheckbox.is(":checked")) {
                $(tr).find("td.subtotal-display").text(this.#rp(0));
                $(tr).find("input[name^='subtotal']").val(0);
                return;
            } // skip if bonus checkbox is checked

            const qty = /** @type {number | undefined} */ (
                $(tr).find("input[name^=qty]").val()
            );
            // if quantity is not set, skip calculating this row
            if (!qty) return;

            const cost = /** @type {number | undefined} */ (
                $(tr).find("input[name^=harga]").val()
            );
            // if cost is not set, skip calculating this row
            if (!cost) return;

            const harga = qty * cost;

            const discountNominalElement = $(tr).find(
                "input[name^=diskon_nominal]"
            );
            const discountPercentElement = $(tr).find(
                "input[name^=diskon_percent]"
            );
            const discountNominal = parseInt(
                /** @type {string} */ (discountNominalElement.val()) || "0"
            );
            const discountPercent = parseInt(
                /** @type {string} */ (discountPercentElement.val()) || "0"
            );

            if (option.updatediskon) {
                discountPercentElement.val((discountNominal / harga) * 100);
                discountNominalElement.val((discountPercent * harga) / 100);
            }

            const subtotal = harga - discountNominal;
            total += harga;
            diskon_total += discountNominal;

            $(tr).find("td.subtotal-display").text(this.#rp(subtotal));
            $(tr).find("input[name^='subtotal']").val(subtotal);
        });
        const Materai = parseInt(
            /** @type {string} */ (this.#$Materai.val()) || "0"
        );
        const DiskonFaktur = parseInt(
            /** @type {string} */ (this.#$DiskonFaktur.val()) || "0"
        );
        const PPN = /** @type {number} */ (this.#$PPN.val());
        const PPN_Nominal = (total * PPN) / 100;
        const grandtotal =
            total + PPN_Nominal - diskon_total - DiskonFaktur + Materai;

        this.#$PPNNominal.val(PPN_Nominal);
        this.#$diskonTotal.text(this.#rp(diskon_total));
        this.#$TotalFinalDisplay.text(this.#rp(grandtotal));
        this.#$TotalFinal.val(grandtotal);
        this.#$Total.val(total);
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

    #loadAddItemModal() {
        if (this.#$TipeTerima.val() === "po") {
            showErrorAlertNoRefresh(
                "Tipe terima harus non PO untuk dapat menambahkan item secara manual!"
            );
            return;
        }

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

const PopupPBNPharmacyClass = new PopupPBNPharmacyHandler();
