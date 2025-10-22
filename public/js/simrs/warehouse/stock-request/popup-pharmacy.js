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
    const $submitDraftBtn = $("#order-submit-draft");
    const $submitFinalBtn = $("#order-submit-final");

    let keyCache = [];
    let itemCounter = $ItemTableBody.find("tr").length;
    let isSubmitting = false; // Flag untuk mencegah double click
    let canEdit = true; // Default true untuk form baru

    // Ambil canEdit dari data attribute jika ada (untuk edit mode)
    const canEditAttr = $form.data('can-edit');
    if (canEditAttr !== undefined) {
        canEdit = canEditAttr;
    }

    initializeKeyCache();
    applyEditRestrictions();

    // === Event Bindings ===
    $("#add-btn").on("click", handleAddButtonClick);
    $ItemSourceSelect.on("change", applyModalFilter);
    $ItemSearchInput.on("keyup", applyModalFilter);
    $ItemTableBody.on("click", ".delete-btn", handleDeleteItemClick);
    $ModalItemTableBody.on("click", ".btn-pilih-item", handleSelectItemClick);

    $AsalGudangId.on("change", resetItems);
    $TujuanGudangId.on("change", resetItems);

    // [PERBAIKAN KUNCI] Ubah event handler tombol submit dengan proteksi double click
    $("#order-submit-draft, #order-submit-final").on("click", function (e) {
        e.preventDefault();

        // Cegah submit jika tidak bisa edit
        if (!canEdit) {
            showErrorAlertNoRefresh(
                "Stock Request ini tidak dapat diubah lagi."
            );
            return;
        }

        // Cegah submit jika sedang dalam proses submit
        if (isSubmitting) {
            return;
        }

        const $clickedBtn = $(this);
        const status = $clickedBtn.is("#order-submit-final") ? "final" : "draft";

        if (submitForm(status, $clickedBtn)) {
            // Set flag dan disable kedua tombol
            isSubmitting = true;
            $submitDraftBtn.prop("disabled", true).addClass("btn-disabled");
            $submitFinalBtn.prop("disabled", true).addClass("btn-disabled");

            // Toggle spinner UI
            $clickedBtn.find(".btn-text").addClass("d-none");
            $clickedBtn.find(".btn-spinner").removeClass("d-none");

            $form.submit();
        }
    });

    function applyEditRestrictions() {
        if (!canEdit) {
            // Disable semua input dan button
            $form.find("input, select, textarea").prop("disabled", true);
            $("#add-btn").prop("disabled", true).addClass("btn-disabled");
            $ItemTableBody.find(".delete-btn").prop("disabled", true).addClass("btn-disabled");
            $submitDraftBtn.prop("disabled", true).addClass("btn-disabled");
            $submitFinalBtn.prop("disabled", true).addClass("btn-disabled");
        }
    }

    function initializeKeyCache() {
        keyCache = [];
        $ItemTableBody.find(".delete-btn").each((_, el) => {
            keyCache.push($(el).data("key-cache"));
        });
    }

    async function handleAddButtonClick(event) {
        event.preventDefault();

        if (!canEdit) {
            showErrorAlertNoRefresh(
                "Stock Request ini tidak dapat diubah lagi."
            );
            return;
        }

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
        const deleteBtn = canEdit
            ? `<a class="btn btn-danger btn-xs delete-btn" data-key-cache="${key_cache}"><i class="fal fa-times"></i></a>`
            : `<a class="btn btn-danger btn-xs delete-btn btn-disabled" disabled><i class="fal fa-times"></i></a>`;

        const html = `
            <tr id="item${itemCounter}">
                <input type="hidden" name="barang_id[${itemCounter}]" value="${
            item.barang_id
        }">
                <input type="hidden" name="satuan_id[${itemCounter}]" value="${
            item.satuan_id
        }">
                <td class="text-center">
                    ${deleteBtn}
                </td>
                <td>${item.nama_barang}</td>
                <td>${item.nama_satuan}</td>
                <td>${item.stok ?? "-"}</td>
                <td><input type="number" name="qty[${itemCounter}]" class="form-control" value="${
            item.qty
        }" min="1" ${!canEdit ? 'disabled' : ''}></td>
                <td><input type="text" name="keterangan_item[${itemCounter}]" class="form-control" value="${
            item.keterangan
        }" ${!canEdit ? 'disabled' : ''}></td>
            </tr>
        `;
        $ItemTableBody.append(html);
    }

    function handleDeleteItemClick(event) {
        if (!canEdit) {
            showErrorAlertNoRefresh(
                "Stock Request ini tidak dapat diubah lagi."
            );
            return;
        }

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
        if (!canEdit) {
            showErrorAlertNoRefresh(
                "Stock Request ini tidak dapat diubah lagi."
            );
            return;
        }

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
     * @param {jQuery} $button - Tombol yang diklik
     */
    function submitForm(status, $button) {
        if ($ItemTableBody.find("tr").length === 0) {
            showErrorAlertNoRefresh(
                "Harap tambahkan minimal satu item barang."
            );
            // Reset tombol jika validasi gagal
            enableSubmitButtons();
            return false;
        }

        $("#status-input").val(status);

        // [PERBAIKAN KUNCI] Tampilkan overlay HANYA saat tombol simpan diklik.
        $loadingPage.show();

        // Return true agar form bisa lanjut di-submit secara native
        return true;
    }

    /**
     * Fungsi helper untuk mengaktifkan kembali tombol submit
     * (digunakan jika terjadi error sebelum submit)
     */
    function enableSubmitButtons() {
        isSubmitting = false;
        $submitDraftBtn.prop("disabled", false).removeClass("btn-disabled");
        $submitFinalBtn.prop("disabled", false).removeClass("btn-disabled");

        // Reset spinner UI
        $submitDraftBtn.find(".btn-text").removeClass("d-none");
        $submitDraftBtn.find(".btn-spinner").addClass("d-none");
        $submitFinalBtn.find(".btn-text").removeClass("d-none");
        $submitFinalBtn.find(".btn-spinner").addClass("d-none");

        $loadingPage.hide();
    }
});
