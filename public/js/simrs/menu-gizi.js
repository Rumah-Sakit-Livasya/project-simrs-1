$(document).ready(function () {
    // =================================================================
    // 1. SETUP & INISIALISASI
    // =================================================================

    // Setup CSRF token untuk semua request AJAX
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Inisialisasi plugin Select2
    function initializeSelect2(context) {
        // Untuk pencarian makanan
        $(".select2-food-search", context).select2({
            placeholder: "Ketik untuk mencari makanan...",
            dropdownParent: $(context),
            allowClear: true,
        });

        // Untuk pilihan kategori
        $('select[name="kategori_id"]', context).select2({
            placeholder: "Pilih Kategori",
            dropdownParent: $(context),
        });
    }

    // Inisialisasi Select2 untuk semua modal saat halaman dimuat
    initializeSelect2($("#addMenuModal"));
    $(".edit-modal").each(function () {
        initializeSelect2($(this));
    });

    // =================================================================
    // 2. LOGIKA DATATABLES
    // =================================================================

    var table = $("#dt-menu").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/api/simrs/gizi/menu/datatable",
            data: function (d) {
                d.nama_menu = $("#nama_menu_filter").val();
            },
        },
        columns: [
            // Kolom untuk kontrol Child Row
            {
                className: "details-control",
                orderable: false,
                data: null,
                defaultContent: "",
            },
            // Kolom data seperti sebelumnya
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
            { data: "nama", name: "nama" },
            { data: "kategori", name: "kategori" },
            { data: "harga", name: "harga" },
            { data: "status", name: "status", className: "text-center" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
            // Kolom tersembunyi untuk menyimpan data child row
            { data: "detail_makanan", name: "detail_makanan", visible: false },
        ],
        // Atur kolom mana yang diurutkan pertama kali (misal kolom nomor)
        order: [[1, "asc"]],
        // Hapus drawCallback popover
        // "drawCallback": function( settings ) {
        //     $('[data-toggle="popover"]').popover();
        // }
    });

    // --- EVENT HANDLER UNTUK CHILD ROW ---
    $("#dt-menu tbody").on("click", "td.details-control", function () {
        var tr = $(this).closest("tr");
        var row = table.row(tr);

        if (row.child.isShown()) {
            // Jika child row sudah terbuka, tutup.
            row.child.hide();
            tr.removeClass("shown");
        } else {
            // Jika child row tertutup, buka dan isi dengan data dari kolom tersembunyi.
            // 'detail_makanan' adalah nama kolom virtual yang kita buat di controller
            row.child(row.data().detail_makanan).show();
            tr.addClass("shown");
        }
    });

    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        table.draw();
    });

    // =================================================================
    // 3. LOGIKA CRUD MENU (HAPUS)
    // =================================================================

    $("#dt-menu tbody").on("click", ".delete-btn", function () {
        var id = $(this).data("id");
        showDeleteConfirmation(function () {
            $.ajax({
                url: "/api/simrs/gizi/menu/" + id,
                type: "DELETE",
                success: function (response) {
                    if (response.success) {
                        showSuccessAlert(response.message);
                        table.ajax.reload();
                    } else {
                        showErrorAlert(
                            response.message || "Gagal menghapus data."
                        );
                    }
                },
                error: function (xhr) {
                    showErrorAlert(
                        "Terjadi kesalahan. Tidak dapat menghapus data."
                    );
                },
            });
        });
    });

    // =================================================================
    // 4. LOGIKA INTERAKSI MODAL DINAMIS
    // =================================================================

    // Fungsi untuk menghitung total harga di dalam modal
    function calculateTotal(modal) {
        let total = 0;
        $(modal)
            .find(".food-table-body tr")
            .each(function () {
                // Hanya hitung jika statusnya aktif (dicentang)
                if ($(this).find(".food-status-switch").is(":checked")) {
                    let priceText = $(this)
                        .find(".harga")
                        .text()
                        .replace(/[^0-9]/g, "");
                    total += parseInt(priceText) || 0;
                }
            });

        const formattedTotal = new Intl.NumberFormat("id-ID").format(total);
        $(modal)
            .find(".total-harga-display")
            .text("Rp " + formattedTotal);
        $(modal).find(".total-harga-input").val(total);
    }

    // Fungsi untuk membuat baris HTML makanan
    function createFoodRow(food, idPrefix) {
        const key = `${idPrefix}-${food.id}`; // Gunakan prefix unik
        return `
            <tr data-id="${food.id}" data-harga="${food.harga}">
                <td>
                    ${food.nama}
                    <input type="hidden" name="foods[${
                        food.id
                    }][id]" value="${food.id}">
                </td>
                <td class="text-right harga">${new Intl.NumberFormat(
                    "id-ID"
                ).format(food.harga)}</td>
                <td class="text-center">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input food-status-switch" id="food-status-${key}" name="foods[${food.id}][status]" value="1" checked>
                        <label class="custom-control-label" for="food-status-${key}"></label>
                        <input type="hidden" name="foods[${
                            food.id
                        }][status]" value="0">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-danger remove-food-btn"><i class="fal fa-times"></i></button>
                </td>
            </tr>
        `;
    }

    // Event handler saat memilih makanan dari Select2
    $(".select2-food-search").on("change", function () {
        const selectedFoodId = $(this).val();
        const modal = $(this).closest(".modal");
        const tableBody = modal.find(".food-table-body");

        if (selectedFoodId) {
            // Cek duplikat
            if (tableBody.find(`tr[data-id="${selectedFoodId}"]`).length > 0) {
                showErrorAlertNoRefresh("Makanan sudah ada di dalam menu.");
                $(this).val(null).trigger("change");
                return;
            }

            const food = window.allFoods[selectedFoodId];
            if (food) {
                const idPrefix = tableBody.data("id-prefix");
                const newRow = createFoodRow(food, idPrefix);
                tableBody.append(newRow);
                calculateTotal(modal);
            }
            // Reset select2 setelah memilih
            $(this).val(null).trigger("change");
        }
    });

    // Event handler untuk menghapus baris makanan dari tabel di modal
    $(".modal").on("click", ".remove-food-btn", function () {
        const modal = $(this).closest(".modal");
        $(this).closest("tr").remove();
        calculateTotal(modal);
    });

    // Event handler untuk switch status makanan (aktif/non-aktif)
    $(".modal").on("change", ".food-status-switch", function () {
        const modal = $(this).closest(".modal");
        // Handle hidden input untuk memastikan nilai 0 dikirim jika tidak dicentang
        const hiddenInput = $(this).siblings('input[type="hidden"]');
        hiddenInput.prop("disabled", $(this).is(":checked"));
        calculateTotal(modal);
    });

    // Inisialisasi status hidden input saat modal edit ditampilkan
    $(".edit-modal").on("shown.bs.modal", function () {
        $(this).find(".food-status-switch").trigger("change");
        calculateTotal(this);
    });
});
