// @ts-check
class PopupDBPharmacyHandler {
    #config;
    #$form;
    #$asalGudangSelect;
    #$tujuanGudangSelect;
    #$itemTableBody;
    #$pilihItemModal;
    #$pilihItemTableBody;
    #itemCache = new Set(); // Menggunakan Set untuk mencegah duplikat dengan lebih efisien

    constructor(config) {
        this.#config = config;
        this.#$form = $("#form-distribusi-barang");
        this.#$asalGudangSelect = this.#$form.find("#asal-gudang");
        this.#$tujuanGudangSelect = this.#$form.find("#tujuan-gudang");
        this.#$itemTableBody = this.#$form.find("#tableItems");
        this.#$pilihItemModal = $("#pilihItemModal");
        this.#$pilihItemTableBody = this.#$pilihItemModal.find("#itemTable");

        this.#init();
    }

    #init() {
        // Event listeners untuk tombol utama di form
        $("#add-item-btn").on("click", this.#handleAddButtonClick.bind(this));
        $("#btn-save-draft").on("click", () => this.#submitForm("draft"));
        $("#btn-save-final").on("click", () => this.#submitForm("final"));

        // Event listener untuk modal pilih item (delegasi event)
        this.#$pilihItemTableBody.on(
            "click",
            ".btn-add-item",
            this.#handleSelectItem.bind(this)
        );
        $("#searchItemInput").on("keyup", this.#filterItemsInModal.bind(this));

        // Hapus item dari tabel utama
        this.#$itemTableBody.on(
            "click",
            ".delete-btn",
            this.#handleDeleteItem.bind(this)
        );

        // Reset jika gudang diubah
        this.#$asalGudangSelect.on("change", this.#resetFormItems.bind(this));
        this.#$tujuanGudangSelect.on("change", this.#resetFormItems.bind(this));
    }

    #resetFormItems() {
        this.#itemCache.clear();
        this.#$itemTableBody
            .empty()
            .append(
                '<tr id="no-items-row"><td colspan="6" class="text-center">Belum ada item yang ditambahkan.</td></tr>'
            );
        this.#$form.find('input[name="sr_id"], input[name="kode_sr"]').val("");
    }

    async #handleAddButtonClick() {
        const asalGudangId = this.#$asalGudangSelect.val();
        const tujuanGudangId = this.#$tujuanGudangSelect.val();

        if (!asalGudangId || !tujuanGudangId) {
            showErrorAlertNoRefresh(
                "Pilih gudang asal dan gudang tujuan terlebih dahulu!"
            );
            return;
        }

        this.#$pilihItemTableBody.html(
            '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>'
        );
        this.#$pilihItemModal.modal("show");

        try {
            // Ganti URL dengan rute API Anda
            const url = `/api/simrs/warehouse/distribusi-barang/pharmacy/get/item-gudang/${asalGudangId}/${tujuanGudangId}`;
            const response = await fetch(url);
            if (!response.ok) throw new Error("Gagal mengambil data barang.");

            const items = await response.json(); // Asumsi API mengembalikan array of items

            let html = "";
            if (items.length === 0) {
                html =
                    '<tr><td colspan="5" class="text-center">Tidak ada barang yang tersedia di gudang asal.</td></tr>';
            } else {
                items.forEach((itemData) => {
                    const item = itemData.barang; // Sesuaikan dengan struktur data Anda
                    const satuan = itemData.satuan; // Sesuaikan dengan struktur data Anda
                    const stok = itemData.stok_asal; // Sesuaikan dengan struktur data Anda

                    const key = `${item.id}/${satuan.id}`;
                    // Buat baris HTML untuk modal pilih item
                    const row = $(`
                        <tr data-key="${key}" data-item-name="${item.nama.toLowerCase()}">
                            <td>${item.nama}</td>
                            <td>${satuan.nama}</td>
                            <td>${stok}</td>
                            <td><input type="number" class="form-control form-control-sm item-qty" min="1" max="${stok}" value="1"></td>
                            <td>
                                <button type="button" class="btn btn-xs btn-primary btn-add-item" ${
                                    this.#itemCache.has(key) ? "disabled" : ""
                                }>
                                    ${
                                        this.#itemCache.has(key)
                                            ? '<i class="fal fa-check"></i>'
                                            : "Pilih"
                                    }
                                </button>
                            </td>
                        </tr>
                    `);
                    // Simpan data lengkap di elemen jQuery untuk akses mudah
                    row.data("itemData", { item, satuan, stok });
                    html += row.prop("outerHTML");
                });
            }
            this.#$pilihItemTableBody.html(html);
        } catch (error) {
            this.#$pilihItemTableBody.html(
                `<tr><td colspan="5" class="text-center text-danger">${error.message}</td></tr>`
            );
        }
    }

    #handleSelectItem(event) {
        const $button = $(event.currentTarget);
        const $row = $button.closest("tr");
        const itemData = $row.data("itemData");
        const key = $row.data("key");
        const qty = $row.find(".item-qty").val();

        if (this.#itemCache.has(key)) {
            return; // Sudah ditambahkan
        }

        if (!qty || parseInt(qty) <= 0) {
            showErrorAlertNoRefresh("Jumlah harus lebih dari 0.");
            return;
        }

        this.#itemCache.add(key);
        $("#no-items-row").hide(); // Sembunyikan pesan "belum ada item"

        // Tambahkan baris ke tabel utama
        const newRowHtml = `
            <tr data-key="${key}">
                <input type="hidden" name="items[${key}][barang_id]" value="${itemData.item.id}">
                <input type="hidden" name="items[${key}][satuan_id]" value="${itemData.satuan.id}">
                <td>${itemData.item.nama}</td>
                <td>${itemData.satuan.nama}</td>
                <td>${itemData.stok}</td>
                <td><input type="number" name="items[${key}][qty]" class="form-control" value="${qty}" min="1" max="${itemData.stok}"></td>
                <td><input type="text" name="items[${key}][keterangan]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-icon btn-danger btn-xs rounded-circle delete-btn">
                        <i class="fal fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        this.#$itemTableBody.append(newRowHtml);

        // Update tombol di modal
        $button.prop("disabled", true).html('<i class="fal fa-check"></i>');
    }

    #handleDeleteItem(event) {
        const $row = $(event.currentTarget).closest("tr");
        const key = $row.data("key");

        this.#itemCache.delete(key);
        $row.remove();

        if (this.#itemCache.size === 0) {
            $("#no-items-row").show();
        }

        // Aktifkan kembali tombol di modal jika modal sedang terbuka
        this.#$pilihItemModal
            .find(`tr[data-key="${key}"] .btn-add-item`)
            .prop("disabled", false)
            .text("Pilih");
    }

    #filterItemsInModal() {
        const searchTerm = $("#searchItemInput").val().toLowerCase();
        this.#$pilihItemTableBody.find("tr").each(function () {
            const itemName = $(this).data("itemName") || "";
            if (itemName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    #submitForm(status) {
        // Validasi sederhana
        if (this.#itemCache.size === 0) {
            showErrorAlertNoRefresh("Harap tambahkan setidaknya satu item.");
            return;
        }

        this.#$form.find("#form-status").val(status);
        this.#$form.submit();
    }
}
