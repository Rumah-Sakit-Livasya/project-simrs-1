// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupPOPharmacyHandler {
    #$Form;
    #$AddModal;
    #$LoadingIcon;
    #$LoadingPage;
    #$Table;
    #$ModalTable;
    #$Total;
    #$DiscountTotal;
    #$GrandTotal;
    #$Nominal;
    #$PPN;
    #itemCounter = 0;

    #SumberItem = "pr";
    #TipePR = "all";
    #API_URL = "/api/simrs/procurement/purchase-order/pharmacy";

    constructor() {
        this.#$Form = $("#form-po");
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
        this.#itemCounter = this.#$Table.find("tr").length;

        // --- EVENT DELEGATION UNTUK SEMUA KALKULASI ---
        this.#$Table.on(
            "keyup change",
            "input[name^='qty'], input[name^='qty_bonus']",
            () => this.refreshTotal()
        );
        this.#$Table.on(
            "keyup change",
            "input[name^='discount_percent']",
            (e) => this.discountPercentChange(e)
        );
        this.#$Table.on(
            "keyup change",
            "input[name^='discount_nominal']",
            (e) => this.discountNominalChange(e)
        );
        this.#$Table.on("click", ".delete-btn", (e) =>
            this.deleteItem($(e.target).data("key"))
        );

        // Event listener lainnya
        $("#add-btn").on("click", () => this.#handleAddButtonClick());
        $("#searchItemInput").on("keyup", (e) => this.#handleItemSearchBar(e));
        $("#order-submit-draft").on("click", (e) =>
            this.#handleDraftButtonClick(e)
        );
        $("#order-submit-final").on("click", (e) =>
            this.#handleFinalButtonClick(e)
        );
        $("#tipe-pr-select").on("change", (e) => this.#handleTipePrChange(e));
        $("#sumber-item-select").on("change", (e) =>
            this.#handleSumberItemChange(e)
        );
        $("#ppn-input").on("keyup change", () => this.refreshTotal());

        // Memastikan semua input number mematuhi min/max
        $("input[type='number']").on("input", (e) =>
            this.enforceNumberLimit(e)
        );

        this.refreshTotal();
        this.#showLoading(false);
    }

    enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0));
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER));

        if (isNaN(value)) {
            inputField.value = "";
            return;
        }

        if (value < min) {
            inputField.value = String(min);
        } else if (value > max) {
            inputField.value = String(max);
        }
    }

    #handleTipePrChange(event) {
        const value = $(event.target).val();
        this.#TipePR = /** @type {TipePR} */ (value);
        this.#loadAddItemModal();
    }

    #handleSumberItemChange(event) {
        const value = $(event.target).val();
        this.#SumberItem = /** @type {SumberItem} */ (value);
        this.#loadAddItemModal();
    }

    #handleFinalButtonClick(event) {
        this.#$Form.find("input[name='status']").remove();
        this.#$Form.append('<input type="hidden" name="status" value="final">');
    }

    #handleDraftButtonClick(event) {
        this.#$Form.find("input[name='status']").remove();
        this.#$Form.append('<input type="hidden" name="status" value="draft">');
    }

    #handleItemSearchBar(event) {
        const value = $(event.target).val().toLowerCase();
        $("#itemTable tr.item").filter(function () {
            $(this).toggle(
                $(this).find(".item-name").text().toLowerCase().indexOf(value) >
                    -1
            );
        });
    }

    addItem(id) {
        const row = this.#$ModalTable.find("tr.item#" + id);
        const QtyString = row.find("input.qty").val();
        if (!QtyString || parseInt(String(QtyString)) <= 0) {
            showErrorAlertNoRefresh("Jumlah harus lebih dari 0!");
            return;
        }
        const Qty = parseInt(String(QtyString));
        const Item = /** @type {BarangFarmasi} */ (row.data("item"));
        const KodePR = row.data("kode_pr");
        const IdPR = row.data("id_pr");
        const MaxQty = parseInt(row.data("max_qty")) || 999999999;
        const Satuan = /** @type {Satuan} */ (
            row.find("select[name^='satuan'] option:selected").data("satuan")
        );

        const HTML = this.#getItemTableCol(
            Item,
            Satuan,
            Qty,
            MaxQty,
            KodePR,
            IdPR
        );
        this.#$Table.append(HTML);
        this.refreshTotal();
    }

    #getItemTableCol(item, satuan, qty, max_qty, kode_pr, IdPR) {
        this.#itemCounter++;
        const key = `new_${this.#itemCounter}`;

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
                <td><input type="number" name="qty[${key}]" min="0" step="1" class="form-control" value="${qty}" max="${max_qty}"></td>
                <td><input type="number" name="qty_bonus[${key}]" min="0" step="1" class="form-control" value="0"></td>
                <td class="harga_total">${this.#rp(item.hna * qty)}</td>
                <td class="discount_percent"><input type="number" name="discount_percent[${key}]" min="0" step="1" class="form-control" value="0"></td>
                <td class="discount_rp"><input type="number" name="discount_nominal[${key}]" min="0" step="1" class="form-control" value="0"></td>
                <td class="subtotal">${this.#rp(item.hna * qty)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn" title="Hapus" data-key="${key}"></a></td>
            </tr>
        `;
    }

    /**
     * Mengubah nilai dari input persen diskon.
     * @param {JQuery.Event} event - Event object dari jQuery.
     */
    discountPercentChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const $tr = $(input).closest("tr");

        // Ambil nilai HNA dan Qty langsung dari input, bukan dari teks. Ini lebih aman.
        const qty =
            parseFloat(String($tr.find("input[name^='qty']").val())) || 0;
        const hna =
            parseFloat(String($tr.find("input[name^='hna']").val())) || 0;
        const harga = qty * hna;

        const discountPercent = parseFloat(input.value) || 0;

        // Hitung diskon nominal dan bulatkan ke angka bulat terdekat.
        const discountNominal = Math.round((discountPercent / 100) * harga);

        // Set nilai input diskon nominal
        $tr.find("input[name^='discount_nominal']").val(discountNominal);

        // Panggil refreshTotal untuk menghitung ulang semua total.
        this.refreshTotal();
    }

    /**
     * Mengubah nilai dari input nominal diskon.
     * @param {JQuery.Event} event - Event object dari jQuery.
     */
    discountNominalChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const $tr = $(input).closest("tr");

        const qty =
            parseFloat(String($tr.find("input[name^='qty']").val())) || 0;
        const hna =
            parseFloat(String($tr.find("input[name^='hna']").val())) || 0;
        const harga = qty * hna;

        const discountNominal = parseFloat(input.value) || 0;

        // Hanya hitung persen jika harga lebih dari 0 untuk menghindari pembagian dengan nol.
        if (harga > 0) {
            const discountPercent = (discountNominal / harga) * 100;
            // Set nilai input diskon persen, batasi 2 angka desimal.
            $tr.find("input[name^='discount_percent']").val(
                discountPercent.toFixed(2)
            );
        } else {
            // Jika harga 0, persen juga 0.
            $tr.find("input[name^='discount_percent']").val(0);
        }

        this.refreshTotal();
    }

    /**
     * Fungsi utama untuk menghitung ulang semua total pada form.
     */
    refreshTotal() {
        let total = 0;
        let discount_total = 0;
        let grandtotal = 0;

        // Iterasi hanya pada baris yang terlihat (visible)
        this.#$Table.find("tr:visible").each((i, tr) => {
            const $tr = $(tr);

            // Gunakan parseFloat untuk semua nilai agar bisa menangani desimal
            const qty =
                parseFloat(String($tr.find("input[name^='qty']").val())) || 0;
            const hna =
                parseFloat(String($tr.find("input[name^='hna']").val())) || 0;
            const dscn =
                parseFloat(
                    String($tr.find("input[name^='discount_nominal']").val())
                ) || 0;

            // Hitung harga dan subtotal per baris
            const harga = qty * hna;
            const subtotal = harga - dscn;

            // Akumulasi total
            total += harga;
            discount_total += dscn;

            // Update tampilan per baris
            $tr.find(".harga_total").text(this.#rp(harga));
            $tr.find(".subtotal").text(this.#rp(subtotal));
        });

        // Hitung Grand Total sebelum PPN
        grandtotal = total - discount_total;

        // Ambil nilai PPN
        const PPN = parseFloat(String(this.#$PPN.val())) || 0;

        // Hitung nilai PPN dan tambahkan ke Grand Total
        const nilaiPPN = (grandtotal * PPN) / 100;
        grandtotal += nilaiPPN;

        // Update tampilan di tfoot dan input nominal
        // Bulatkan semua hasil akhir untuk menghindari masalah angka desimal panjang
        this.#$Total.text(this.#rp(Math.round(total)));
        this.#$DiscountTotal.text(this.#rp(Math.round(discount_total)));
        this.#$GrandTotal.text(this.#rp(Math.round(grandtotal)));
        this.#$Nominal.val(Math.round(grandtotal));
    }

    /**
     * Fungsi untuk menghapus item.
     * @param {string|number} key - Kunci unik dari baris item.
     */
    deleteItem(key) {
        const $row = this.#$Table.find("#item" + key);
        if (!$row.length) return;

        const $itemIdInput = $row.find("input[name^='item_id']");

        if ($itemIdInput.length > 0) {
            const itemId = $itemIdInput.val();
            this.#$Form.append(
                `<input type="hidden" name="deleted_items[]" value="${itemId}">`
            );
            $row.find("input, select").prop("disabled", true); // Nonaktifkan input
            $row.hide();
        } else {
            $row.remove();
        }

        // Panggil refreshTotal setelah menghapus/menyembunyikan baris
        this.refreshTotal();
    }

    #handleAddButtonClick() {
        this.#loadAddItemModal();
    }

    async #loadAddItemModal() {
        this.#$AddModal.modal("hide");
        this.#showLoading(true);
        try {
            const response = await fetch(
                `/api/simrs/procurement/purchase-order/pharmacy/get/items`,
                {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            $('meta[name="csrf-token"]').attr("content") || "",
                    },
                    body: JSON.stringify({
                        sumber_item: this.#SumberItem,
                        tipe_pr: this.#TipePR,
                    }),
                }
            );

            if (!response.ok) throw new Error("Network response was not ok.");
            const HTML = await response.text();
            this.#$ModalTable.html(HTML);
            this.#$AddModal.modal("show");
        } catch (error) {
            showErrorAlertNoRefresh(`Gagal memuat item: ${error.message}`);
        } finally {
            this.#showLoading(false);
        }
    }

    #showLoading(show) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
    }

    #rp(amount) {
        // Memastikan input adalah angka dan membulatkannya
        const num = Math.round(Number(amount));
        return "Rp " + num.toLocaleString("id-ID");
    }

    #parseCurrency(str) {
        return (
            parseFloat(
                String(str)
                    .replace(/Rp\s?|\./g, "")
                    .replace(",", ".")
            ) || 0
        );
    }
}

const PopupPOPharmacyClass = new PopupPOPharmacyHandler();
