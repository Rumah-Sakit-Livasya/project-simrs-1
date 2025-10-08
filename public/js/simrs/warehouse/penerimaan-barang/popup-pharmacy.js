// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupPBPharmacyHandler {
    /** @type {JQuery<HTMLElement>} */ #$AddModal;
    /** @type {JQuery<HTMLElement>} */ #$LoadingIcon;
    /** @type {JQuery<HTMLElement>} */ #$LoadingPage;
    /** @type {JQuery<HTMLElement>} */ #$Table;
    /** @type {JQuery<HTMLElement>} */ #$ModalTable;
    /** @type {JQuery<HTMLElement>} */ #$diskonTotal;
    /** @type {JQuery<HTMLElement>} */ #$Total;
    /** @type {JQuery<HTMLElement>} */ #$TotalFinalDisplay;
    /** @type {JQuery<HTMLElement>} */ #$TotalFinal;
    /** @type {JQuery<HTMLElement>} */ #$PPN;
    /** @type {JQuery<HTMLElement>} */ #$PPNNominal;
    /** @type {JQuery<HTMLElement>} */ #$Materai;
    /** @type {JQuery<HTMLElement>} */ #$DiskonFaktur;
    /** @type {JQuery<HTMLElement>} */ #$NoFaktur;
    /** @type {JQuery<HTMLElement>} */ #$TipeTerima;
    /** @type {JQuery<HTMLElement>} */ #$KodePO;
    /** @type {JQuery<HTMLElement>} */ #$POid;
    /** @type {JQuery<HTMLElement>} */ #$PICPenerima;
    /** @type {JQuery<HTMLElement>} */ #$TipeBayar;
    /** @type {JQuery<HTMLElement>} */ #$Supplier;
    /** @type {JQuery<HTMLElement>} */ #$SupplierId;
    /** @type {JQuery<HTMLElement>} */ #$Keterangan;
    /** @type {JQuery<HTMLElement>} */ #$Kas;
    /** @type {JQuery<HTMLElement>} */ #$TanggalFaktur;
    /** @type {JQuery<HTMLElement>} */ #$Gudang;
    /** @type {JQuery<HTMLElement>} */ #$SelectPOButton;

    #API_URL = "/api/simrs/procurement/penerimaan-barang/pharmacy";

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
            "#searchPOSupplierInput",
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
     * @param {JQuery.Event} event
     */
    #handleSupplierChange(event) {
        // @ts-ignore
        const id = event.params.data.id;
        this.#$SupplierId.val(id);
    }

    /**
     * @param {Event} event
     */
    #handleTipeBayarChange(event) {
        const Select = /**@type {HTMLSelectElement} */ (event.target);
        if (Select.value == "cash") {
            this.#$Kas.removeAttr("disabled");
        } else {
            this.#$Kas.attr("disabled", "disabled");
        }
    }

    /**
     * @param {Event} event
     */
    #handleTipeTerimaChange(event) {
        this.#reset();
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
     * @param {Event} event
     */
    enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0));
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER));
        if (isNaN(value)) {
            inputField.value = "";
            return this;
        }
        if (value < min) {
            inputField.value = String(min);
        } else if (value > max) {
            inputField.value = String(max);
        }
        return this;
    }

    /**
     * @param {PurchaseOrder} po
     */
    SelectPO(po) {
        this.#reset();
        this.#$POid.val(po.id);
        this.#$KodePO.val(po.kode_po);
        this.#$SupplierId.val(po.supplier_id);
        this.#$Supplier.val(po.supplier_id);
        this.#$Supplier.trigger("change");
        this.#$PPN.val(po.ppn);
        po.items?.forEach((item) => this.#addPOItem(item));
        this.refreshTotal();
    }

    #reset() {
        this.#$Table.empty();
        this.refreshTotal();
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
        this.#$Gudang.trigger("change");
        this.#$Supplier.trigger("change");
    }

    /**
     * @param {ItemPO} item
     */
    #addPOItem(item) {
        if (item.qty <= item.qty_received) return;
        const HTML = this.#getItemPOTableCol(item);
        this.#$Table.append(HTML);
    }

    /**
     * @param {Event} event
     */
    #handleFinalButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status";
        input.value = "final";
        button.insertAdjacentElement("afterend", input);
    }

    /**
     * @param {Event} event
     */
    #handleDraftButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "status";
        input.value = "draft";
        button.insertAdjacentElement("afterend", input);
    }

    /**
     * @param {Event} event
     */
    #handleItemSearchBar(event) {
        // Perbaikan: filter berdasarkan seluruh kolom (kode, nama, satuan, harga) sesuai modal-add-item-pharmacy.blade.php
        const searchInput = /** @type {HTMLInputElement} */ (event.target);
        const value = searchInput.value.toLowerCase();
        // Ambil semua baris item di modal
        const items = document.querySelectorAll("#itemTable tr.item");
        items.forEach((item) => {
            if (!item) return;
            // Ambil semua kolom (td) pada baris
            const tds = item.querySelectorAll("td");
            let found = false;
            tds.forEach((td) => {
                if (
                    td.textContent &&
                    td.textContent.toLowerCase().includes(value)
                ) {
                    found = true;
                }
            });
            // Tampilkan jika ada kolom yang cocok, sembunyikan jika tidak
            // @ts-ignore
            item.style.display = found ? "" : "none";
        });
    }

    /**
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
     * @param {Event} event
     */
    #handlePOSupplierSearchBar(event) {
        const searchInput = /** @type {HTMLInputElement} */ (event.target);
        const value = searchInput.value.toLowerCase();
        const items = document.querySelectorAll("tr.po-row");
        items.forEach((item) => {
            if (!item) return;
            const itemNameElement = item.querySelector(".supplier-po");
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
     * @param {BarangFarmasi} barang
     */
    addItem(barang) {
        const HTML = this.#getItemTableCol(barang);
        this.#$Table.append(HTML);
        this.refreshTotal();
    }

    /**
     * @param {BarangFarmasi} item
     */
    #getItemTableCol(item) {
        const key = Math.round(Math.random() * 100000);
        // Use Tailwind utility classes for table row and cell styling
        return /*html*/ `
            <tr id="item${key}" class="border-b border-gray-200 hover:bg-gray-50">
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

                <td class="text-center align-middle">
                    <input type="checkbox" class="form-control rounded border-gray-300" name="is_bonus[${key}]" onclick="PopupPBPharmacyClass.refreshTotal()">
                </td>
                <td class="px-2 py-1 align-middle">${item.kode}</td>
                <td class="px-2 py-1 align-middle">${item.nama}</td>
                <td class="px-2 py-1 align-middle">${item.satuan?.nama}</td>
                <td class="px-2 py-1 align-middle">
                    <input type="date" name="tanggal_exp[${key}]" class="form-control table-input" required>
                </td>
                <td class="px-2 py-1 align-middle">
                    <input type="text" name="batch_no[${key}]" class="form-control table-input" required>
                </td>
                <td class="px-2 py-1 align-middle text-center">-</td>
                <td class="px-2 py-1 align-middle text-center">-</td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="qty[${key}]" class="form-control qty table-input" min="0" step="1"
                        onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()"
                        onchange="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()" required>
                </td>
                <td class="px-2 py-1 align-middle text-right">${this.#rp(
                    item.hna || 0
                )}</td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="harga[${key}]" class="form-control table-input text-right" value="${
            item.hna || ""
        }"
                        onkeyup="PopupPBPharmacyClass.refreshTotal()"
                        onchange="PopupPBPharmacyClass.refreshTotal()" required>
                </td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="diskon_percent[${key}]" class="form-control table-input text-right" min="0" value="0" step="1" max="100"
                        onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)"
                        onchange="PopupPBPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)">
                </td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="diskon_nominal[${key}]" min="0" step="1" value="0" class="form-control table-input text-right"
                        onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)"
                        onchange="PopupPBPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)">
                </td>
                <td class="px-2 py-1 align-middle subtotal-display text-right">Rp 0</td>
                <td class="px-2 py-1 align-middle text-center">
                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupPBPharmacyClass.deleteItem(${key})"></a>
                </td>
            </tr>
        `;
    }

    /**
     * @param {ItemPO} item
     */
    #getItemPOTableCol(item) {
        const key = Math.round(Math.random() * 100000);
        // Use Tailwind utility classes for table row and cell styling
        return /*html*/ `
            <tr id="item${key}" class="border-b border-gray-200 hover:bg-gray-50">
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

                <td class="px-2 py-1 text-center align-middle">
                    <input type="checkbox" class="form-control rounded border-gray-300" name="is_bonus[${key}]" onclick="PopupPBPharmacyClass.refreshTotal()">
                </td>
                <td class="px-2 py-1 align-middle">${item.kode_barang}</td>
                <td class="px-2 py-1 align-middle">${item.nama_barang}</td>
                <td class="px-2 py-1 align-middle">${item.unit_barang}</td>
                <td class="px-2 py-1 align-middle">
                    <input type="date" name="tanggal_exp[${key}]" class="form-control table-input" required>
                </td>
                <td class="px-2 py-1 align-middle">
                    <input type="text" name="batch_no[${key}]" class="form-control table-input" required>
                </td>
                <td class="px-2 py-1 align-middle text-center">${item.qty}</td>
                <td class="px-2 py-1 align-middle text-center">${
                    item.qty - item.qty_received
                }</td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="qty[${key}]" class="form-control qty table-input" min="0" step="1" max="${
            item.qty - item.qty_received
        }"
                        onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()"
                        onchange="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()" required>
                </td>
                <td class="px-2 py-1 align-middle text-right">${this.#rp(
                    item.barang?.hna || 0
                )}</td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="harga[${key}]" class="form-control table-input text-right" value="${
            item.barang?.hna || ""
        }"
                        onkeyup="PopupPBPharmacyClass.refreshTotal()"
                        onchange="PopupPBPharmacyClass.refreshTotal()" required>
                </td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="diskon_percent[${key}]" class="form-control table-input text-right" min="0" value="0" step="1" max="100"
                        onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)"
                        onchange="PopupPBPharmacyClass.enforceNumberLimit(event).diskonPercentChange(event)">
                </td>
                <td class="px-2 py-1 align-middle">
                    <input type="number" name="diskon_nominal[${key}]" min="0" step="1" value="0" class="form-control table-input text-right"
                        onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)"
                        onchange="PopupPBPharmacyClass.enforceNumberLimit(event).diskonNominalChange(event)">
                </td>
                <td class="px-2 py-1 align-middle subtotal-display text-right">Rp 0</td>
                <td class="px-2 py-1 align-middle text-center">
                    <!-- <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupPBPharmacyClass.deleteItem(${key})"></a> -->
                </td>
            </tr>
        `;
    }

    /**
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
        if (!qty) return;
        const cost = /** @type {number | undefined} */ (
            $(tr).find("input[name^=harga]").val()
        );
        if (!cost) return;
        const harga = qty * cost;
        dscnEl.val((diskonPercent * harga) / 100);
        this.refreshTotal({ updatediskon: false });
        return this;
    }

    /**
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
        if (!qty) return;
        const cost = /** @type {number | undefined} */ (
            $(tr).find("input[name^=harga]").val()
        );
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
            // console.log(bonusCheckbox.is(":checked"));
            if (bonusCheckbox.is(":checked")) {
                $(tr).find("td.subtotal-display").text(this.#rp(0));
                $(tr).find("input[name^='subtotal']").val(0);
                return;
            }
            const qty = /** @type {number | undefined} */ (
                $(tr).find("input[name^=qty]").val()
            );
            if (!qty) return;
            const cost = /** @type {number | undefined} */ (
                $(tr).find("input[name^=harga]").val()
            );
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
     * @param {string} key
     */
    deleteItem(key) {
        this.#$Table.find("#item" + key).remove();
        this.refreshTotal();
    }

    /**
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
     * @param {boolean} show
     */
    #showLoading(show) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
    }

    /**
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
                    if (this.#showLoading) this.#showLoading(false);
                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }

    /**
     * @param {number} amount
     * @returns {string}
     */
    #rp(amount) {
        return "Rp " + amount.toLocaleString("id-ID");
    }
}

const PopupPBPharmacyClass = new PopupPBPharmacyHandler();
