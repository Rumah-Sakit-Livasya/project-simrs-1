// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupReturBarangHandler {
    /** @type {JQuery<HTMLElement>} */ #$SupplierId;
    /** @type {JQuery<HTMLElement>} */ #$GudangId; // <-- Tambahkan ini
    /** @type {JQuery<HTMLElement>} */ #$LoadingPage;
    /** @type {JQuery<HTMLElement>} */ #$Table;
    /** @type {JQuery<HTMLElement>} */ #$Total;
    /** @type {JQuery<HTMLElement>} */ #$Nominal;
    /** @type {JQuery<HTMLElement>} */ #$PPN;
    /** @type {JQuery<HTMLElement>} */ #$PPN_Nominal;
    /** @type {JQuery<HTMLElement>} */ #$AddBtn;

    /** @type {string[]} */ #SelectedItems = [];

    constructor() {
        this.#$SupplierId = $("select[name='supplier_id']");
        this.#$GudangId = $("select[name='gudang_id_filter']"); // <-- Tambahkan ini
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$Total = $("#total-display");
        this.#$Nominal = $("input[name='nominal']");
        this.#$PPN = $("input[name='ppn']");
        this.#$PPN_Nominal = $("input[name='ppn_nominal']");
        this.#$AddBtn = $("#add-btn");

        this.#init();
    }

    #init() {
        // Inisialisasi Select2 untuk kedua dropdown
        this.#$SupplierId.select2({ width: "100%" });
        this.#$GudangId.select2({ width: "100%" }); // <-- Tambahkan ini

        // Event Listeners
        this.#$AddBtn.on("click", this.#handleAddButtonClick.bind(this));
        this.#$PPN.on("keyup change", this.refreshTotal.bind(this));

        // Event listener saat supplier atau gudang berubah
        this.#$SupplierId.on(
            "select2:select",
            this.#handleFilterChange.bind(this)
        );
        this.#$GudangId.on("change", this.#handleFilterChange.bind(this)); // Gunakan 'change' untuk gudang

        this.#$Table.on("keyup change", 'input[name^="item_qty"]', (event) => {
            this.enforceNumberLimit(event);
            this.refreshTotal();
        });

        this.#$Table.on("click", ".delete-btn", (event) => {
            const button = $(event.currentTarget);
            const key = button.data("key");
            const code = button.data("code");
            this.deleteItem(key, code);
        });
    }

    // Fungsi baru untuk menangani perubahan pada filter
    #handleFilterChange() {
        // Reset tabel setiap kali filter utama (supplier/gudang) berubah
        this.#SelectedItems = [];
        this.#$Table.empty();
        this.refreshTotal();

        // Cek jika kedua filter sudah terisi, maka aktifkan tombol "Tambah Item"
        const supplierId = this.#$SupplierId.val();
        const gudangId = this.#$GudangId.val();

        if (supplierId && gudangId) {
            this.#$AddBtn.prop("disabled", false);
        } else {
            this.#$AddBtn.prop("disabled", true);
        }
    }

    // =========================================================================
    // FUNGSI INI AKAN DIPANGGIL DARI POPUP WINDOW
    // =========================================================================
    addItemFromPopup(Item) {
        if (!Item.pbi || !Item.pbi.pb) {
            return showErrorAlertNoRefresh(
                "Data penerimaan barang tidak lengkap!"
            );
        }
        const itemIdentifier = `${Item.pbi.pb.id}-${Item.id}`;
        if (this.#SelectedItems.includes(itemIdentifier)) {
            return Swal.fire({
                icon: "warning",
                title: "Item Sudah Ada",
                text: "Barang dari faktur ini sudah ada dalam daftar retur.",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
        }

        const tipe = Item.pbi.pb.kode_penerimaan.includes("/UNGR/")
            ? "non_farmasi"
            : "farmasi";
        const HTML = this.#getItemTableCol(Item, tipe, itemIdentifier);
        if (!HTML) return;

        this.#$Table.append(HTML);
        this.#SelectedItems.push(itemIdentifier);
        this.refreshTotal();
        // Tidak menutup popup di sini, sehingga popup tetap terbuka setelah memilih item
    }

    // =========================================================================
    // MENGUBAH FUNGSI INI UNTUK MEMBUKA POPUP WINDOW
    // =========================================================================
    #handleAddButtonClick(event) {
        event.preventDefault();
        const supplierId = this.#$SupplierId.val();
        const gudangId = this.#$GudangId.val(); // <-- AMBIL GUDANG ID

        if (!supplierId || !gudangId) {
            // <-- CEK KEDUANYA
            return showErrorAlertNoRefresh(
                "Pilih supplier dan gudang terlebih dahulu!"
            );
        }

        // <-- MASUKKAN GUDANG ID KE URL
        const url = `/simrs/warehouse/penerimaan-barang/retur-barang/popup-items/${supplierId}/${gudangId}`;
        const windowName = "pilihItemPopup";
        // Membuka popup setengah layar dan di tengah
        const width = Math.floor(screen.availWidth / 2);
        const height = Math.floor(screen.availHeight / 2);
        const left = Math.floor((screen.availWidth - width) / 2);
        const top = Math.floor((screen.availHeight - height) / 2);
        const windowFeatures = `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`;

        window.open(url, windowName, windowFeatures);
    }

    #getItemTableCol(item, tipe, itemIdentifier) {
        const key = Math.floor(Math.random() * 100000);
        const type_column = tipe === "non_farmasi" ? "si_nf_id" : "si_f_id";
        if (!item.pbi)
            return showErrorAlertNoRefresh("Data PBI tidak ditemukan!");
        const subtotalBeli = item.pbi.subtotal;
        const qtyBeli = item.pbi.qty;
        const hargaRetur =
            qtyBeli > 0 ? subtotalBeli / qtyBeli : item.pbi.harga;
        return `
            <tr id="item${key}">
                <input type="hidden" name="item_type[${key}]" value="${type_column}">
                <input type="hidden" name="item_si_id[${key}]" value="${
            item.id
        }">
                <input type="hidden" name="item_harga[${key}]" value="${hargaRetur}">
                <input type="hidden" name="item_subtotal[${key}]" value="0">
                <td>${item.pbi.kode_barang}</td>
                <td>${item.pbi.nama_barang}</td>
                <td>${item.pbi.pb?.no_faktur || "-"}</td>
                <td>${
                    item.pbi.tanggal_exp
                        ? new Date(item.pbi.tanggal_exp).toLocaleDateString(
                              "id-ID"
                          )
                        : "-"
                }</td>
                <td>${item.pbi.batch_no || "-"}</td>
                <td class="text-center">${item.qty}</td>
                <td><input type="number" name="item_qty[${key}]" min="1" step="1" max="${
            item.qty
        }" class="form-control qty table-input" value="1" required></td>
                <td class="text-right harga-display">${this.#rp(
                    hargaRetur
                )}</td>
                <td class="text-right subtotal-display">Rp 0</td>
                <td class="text-center"><a href="javascript:void(0);" class="btn btn-xs btn-danger delete-btn" data-key="${key}" data-code="${itemIdentifier}" title="Hapus"><i class="fal fa-trash"></i></a></td>
            </tr>`;
    }

    enforceNumberLimit(event) {
        const input = event.target;
        let value = parseInt(input.value, 10);
        const min = parseInt(input.min, 10);
        const max = parseInt(input.max, 10);
        if (isNaN(value) || value < min) {
            input.value = min;
        } else if (value > max) {
            input.value = max;
        }
        return this;
    }

    refreshTotal() {
        let total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const row = $(tr);
            const qty =
                parseInt(row.find('input[name^="item_qty"]').val(), 10) || 0;
            const harga =
                parseFloat(row.find('input[name^="item_harga"]').val()) || 0;
            const subtotal = qty * harga;
            total += subtotal;
            row.find(".subtotal-display").text(this.#rp(subtotal));
            row.find('input[name^="item_subtotal"]').val(subtotal);
        });
        const ppnPercent = parseInt(this.#$PPN.val(), 10) || 0;
        const ppnNominal = total * (ppnPercent / 100);
        const grandTotal = total + ppnNominal;
        this.#$Total.text(this.#rp(grandTotal));
        this.#$Nominal.val(grandTotal);
        this.#$PPN_Nominal.val(ppnNominal);
    }

    deleteItem(key, code) {
        $(`#item${key}`).remove();
        this.#SelectedItems = this.#SelectedItems.filter(
            (item) => item !== code
        );
        this.refreshTotal();
    }

    #rp(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(amount);
    }
}

// Inisialisasi class dan ekspos fungsi ke object window global
$(document).ready(function () {
    const returHandler = new PopupReturBarangHandler();
    // Membuat fungsi global yang bisa diakses oleh popup
    window.addItemFromPopup = returHandler.addItemFromPopup.bind(returHandler);
});
