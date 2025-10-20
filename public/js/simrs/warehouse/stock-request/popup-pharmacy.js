// @ts-check
/// <reference types="jquery" />

$(document).ready(function () {
    // === Cache DOM Elements ===
    const $form = $("#form-sr");
    const $loadingPage = $("#loading-page");
    const $AddModal = $("#pilihItemModal");
    const $ModalItemTableBody = $("#itemTable");
    const $ItemTableBody = $("#tableItems");
    const $AsalGudangId = $("select[name='asal_gudang_id']");
    const $TujuanGudangId = $("select[name='tujuan_gudang_id']");
    const $ItemSourceSelect = $("#itemSourceSelect");
    const $ItemSearchInput = $("#searchItemInput");

    let keyCache = [];
    let itemCounter = $ItemTableBody.find("tr").length;

    initializeKeyCache();

    // === Event Bindings ===
    $("#add-btn").on("click", handleAddButtonClick);
    $ItemSourceSelect.on("change", applyModalFilter);
    $ItemSearchInput.on("keyup", applyModalFilter);
    $ItemTableBody.on("click", ".delete-btn", handleDeleteItemClick);
    $ModalItemTableBody.on("click", ".btn-pilih-item", handleSelectItemClick);

    $AsalGudangId.on("change", resetItems);
    $TujuanGudangId.on("change", resetItems);

    // [PERBAIKAN KUNCI] Ubah event handler tombol submit
    $("#order-submit-draft, #order-submit-final").on("click", function (e) {
        e.preventDefault();
        const status = $(this).is("#order-submit-final") ? "final" : "draft";
        if (submitForm(status)) {
            $form.submit();
        }
    });

    function initializeKeyCache() {
        keyCache = [];
        $ItemTableBody.find(".delete-btn").each((_, el) => {
            keyCache.push($(el).data("key-cache"));
        });
    }

    async function handleAddButtonClick(event) {
        event.preventDefault();

        const gudangAsalId = $AsalGudangId.val();
        const gudangTujuanId = $TujuanGudangId.val();
        if (!gudangAsalId || !gudangTujuanId) {
            showErrorAlertNoRefresh(
                "Pilih gudang asal dan gudang tujuan terlebih dahulu!"
            );
            return;
        }

        $ModalItemTableBody.html(
            '<tr><td colspan="9" class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>'
        );
        $AddModal.modal("show");

        try {
            const url = `/simrs/warehouse/stock-request/pharmacy/get/item-gudang/${gudangAsalId}/${gudangTujuanId}`;
            const response = await fetch(url);
            const data = await response.json();

            if (!response.ok)
                throw new Error(data.error || "Gagal mengambil data");
            renderModalItems(data, gudangAsalId, gudangTujuanId);
        } catch (error) {
            $ModalItemTableBody.html(
                `<tr><td colspan="9" class="text-center text-danger p-3">Gagal memuat data: ${error.message}</td></tr>`
            );
        }
    }

    /**
     * [REFACTOR] Merender data item (dari AJAX JSON) ke dalam tabel modal.
     * Disesuaikan dengan struktur JSON yang baru.
     * @param {Array<Object>} items
     * @param {string|number} asalGudangId
     * @param {string|number} tujuanGudangId
     */
    function renderModalItems(items, asalGudangId, tujuanGudangId) {
        if (!items || items.length === 0) {
            $ModalItemTableBody.html(
                '<tr><td colspan="9" class="text-center p-3">Tidak ada item ditemukan.</td></tr>'
            );
            return;
        }

        let html = "";
        items.forEach((itemData) => {
            // [FIX] Semua data sekarang ada di dalam properti `barang`
            const barang = itemData.barang;

            // [FIX] Satuan diambil dari dalam objek barang
            const satuan = barang.satuan;

            if (!barang || !satuan) return; // Lewati jika data tidak lengkap

            const key = `${barang.id}/${satuan.id}`;
            const isAdded = keyCache.includes(key);

            // [FIX] Stok diambil dari dalam objek barang, lalu dicari yang cocok
            // Gunakan asalGudangId dan tujuanGudangId dari parameter
            const stokAsalData = barang.stok_gudang.find(
                (s) => s.gudang_id == asalGudangId
            );
            const stokTujuanData = barang.stok_gudang.find(
                (s) => s.gudang_id == tujuanGudangId
            );

            const stokAsal = stokAsalData ? stokAsalData.total_qty : 0;
            const stokTujuan = stokTujuanData ? stokTujuanData.total_qty : 0;

            const source = stokAsal > 0 ? "stock" : "barang";

            html += `
                <tr class="item-row"
                    data-source="${source}"
                    data-item-name="${barang.nama.toLowerCase()}"
                    data-key="${key}"
                    data-item='${JSON.stringify(
                        barang
                    )}'>  {{-- Simpan objek barang lengkap --}}

                    <td>${barang.kode}</td>
                    <td class="item-name">${barang.nama}</td>
                    <td>${satuan.nama}</td>
                    <td>${stokAsal}</td>
                    <td>${stokTujuan}</td>
                    <td>${barang.min_stok || 0}</td>
                    <td>${barang.max_stok || 0}</td>
                    <td><input type="number" class="form-control form-control-sm item-qty" min="1" value="1"></td>
                    <td>
                        <button class="btn btn-primary btn-xs btn-pilih-item" ${
                            isAdded ? "disabled" : ""
                        }>
                            ${
                                isAdded
                                    ? '<i class="fal fa-check"></i>'
                                    : "Pilih"
                            }
                        </button>
                    </td>
                </tr>
            `;
        });
        $ModalItemTableBody.html(html);
        applyModalFilter();
    }

    /**
     * [REFACTOR] Menangani klik tombol "Pilih" di dalam modal.
     * Disesuaikan dengan struktur data yang baru.
     */
    function handleSelectItemClick(event) {
        const $button = $(event.currentTarget);
        const $row = $button.closest("tr");
        const key = $row.data("key");

        if (keyCache.includes(key)) return;

        // [FIX] Ambil objek 'barang' lengkap dari atribut data
        const barang = $row.data("item");
        const satuan = barang.satuan; // Ambil satuan dari dalam objek barang
        const stokAsal = $row.find("td").eq(3).text(); // Ambil dari teks di kolom tabel

        const qty = parseInt($row.find("input.item-qty").val().toString(), 10);
        if (isNaN(qty) || qty <= 0) {
            showErrorAlertNoRefresh("Qty harus diisi dan lebih dari 0.");
            return;
        }

        // Panggil fungsi untuk menambah baris ke tabel utama
        addItemToTable({
            barang_id: barang.id,
            satuan_id: satuan.id,
            nama_barang: barang.nama,
            nama_satuan: satuan.nama,
            stok: stokAsal,
            qty: qty,
            keterangan: "",
        });

        $button.prop("disabled", true).html('<i class="fal fa-check"></i>');
        keyCache.push(key);
    }

    /**
     * Menerapkan filter pada tabel modal berdasarkan input pencarian dan dropdown source.
     * Pastikan penamaan selector konsisten ($ItemSearchInput, $ItemSourceSelect, $ModalItemTableBody).
     */
    function applyModalFilter() {
        const searchTerm = ($ItemSearchInput.val() || "")
            .toString()
            .toLowerCase();
        const sourceFilter = $ItemSourceSelect.val();

        $ModalItemTableBody.find("tr.item-row").each(function () {
            const $row = $(this);

            // [PERBAIKAN] Gunakan data-item-name, bukan data-name
            const itemName = ($row.data("item-name") || "")
                .toString()
                .toLowerCase();
            const itemSource = $row.data("source");

            const nameMatch = itemName.includes(searchTerm);
            // Hanya filter source jika dropdown tidak kosong, jika tidak, tampilkan semua
            const sourceMatch = !sourceFilter || itemSource === sourceFilter;

            $row.toggle(nameMatch && sourceMatch);
        });
    }

    function addItemToTable(item) {
        itemCounter++;
        const key_cache = `${item.barang_id}/${item.satuan_id}`;
        const html = `
            <tr id="item${itemCounter}">
                <input type="hidden" name="barang_id[${itemCounter}]" value="${
            item.barang_id
        }">
                <input type="hidden" name="satuan_id[${itemCounter}]" value="${
            item.satuan_id
        }">
                <td class="text-center">
                    <a class="btn btn-danger btn-xs delete-btn" data-key-cache="${key_cache}"><i class="fal fa-times"></i></a>
                </td>
                <td>${item.nama_barang}</td>
                <td>${item.nama_satuan}</td>
                <td>${item.stok ?? "-"}</td>
                <td><input type="number" name="qty[${itemCounter}]" class="form-control" value="${
            item.qty
        }" min="1"></td>
                <td><input type="text" name="keterangan_item[${itemCounter}]" class="form-control" value="${
            item.keterangan
        }"></td>
            </tr>
        `;
        $ItemTableBody.append(html);
    }

    function handleDeleteItemClick(event) {
        const $button = $(event.currentTarget);
        const key_cache = $button.data("key-cache");

        keyCache = keyCache.filter((k) => k !== key_cache);

        const $modalButton = $ModalItemTableBody.find(
            `tr[data-key="${key_cache}"] .btn-pilih-item`
        );
        $modalButton.prop("disabled", false).text("Pilih");

        $button.closest("tr").remove();
    }

    function resetItems() {
        if ($ItemTableBody.find("tr").length > 0) {
            Swal.fire({
                title: "Ganti Gudang?",
                text: "Item yang sudah ditambahkan akan dihapus. Lanjutkan?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, lanjutkan!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $ItemTableBody.empty();
                    initializeKeyCache();
                }
            });
        }
    }

    /**
     * Men-submit form dengan status yang ditentukan.
     * @param {'draft' | 'final'} status
     */
    function submitForm(status) {
        if ($ItemTableBody.find("tr").length === 0) {
            showErrorAlertNoRefresh(
                "Harap tambahkan minimal satu item barang."
            );
            return false;
        }

        $("#status-input").val(status);

        // [PERBAIKAN KUNCI] Tampilkan overlay HANYA saat tombol simpan diklik.
        $loadingPage.show();

        // Return true agar form bisa lanjut di-submit secara native
        return true;
    }
});
