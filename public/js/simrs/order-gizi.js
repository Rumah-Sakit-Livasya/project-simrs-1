$(document).ready(function () {
    // --- SETUP ---
    $(".select2").select2();

    // --- DATATABLES ---
    var table = $("#dt-order").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/api/simrs/gizi/order/datatable",
            type: "GET",
            data: function (d) {
                // Mengambil semua data dari form filter
                var formData = $("#filter-form").serializeArray();
                $.each(formData, function (i, field) {
                    d[field.name] = field.value;
                });
            },
        },
        columns: [
            {
                className: "details-control",
                orderable: false,
                data: null,
                defaultContent: "",
            },
            {
                data: "id", // Gunakan 'id' order sebagai sumber data
                name: "id",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    // Render sebagai input checkbox
                    // Tambahkan atribut 'disabled' jika status order sudah 'Delivered' (true)
                    const disabled = row.status_order ? "disabled" : "";
                    return `<input type="checkbox" class="row-checkbox" value="${data}" ${disabled}>`;
                },
            },
            { data: "nama_pemesan", name: "nama_pemesan" },
            { data: "untuk", name: "untuk" },
            { data: "pasien_info", name: "registration.patient.name" }, // Sertakan relasi untuk pencarian server-side
            { data: "no_reg_rm", name: "registration.registration_number" },
            { data: "waktu_makan", name: "waktu_makan" },
            { data: "harga_formatted", name: "total_harga" },
            { data: "ditagihkan_formatted", name: "ditagihkan" },
            {
                data: "status_payment_formatted",
                name: "status_payment",
                orderable: false,
                searchable: false,
            },
            { data: "status_order_formatted", name: "status_order" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
            { data: "detail_makanan", name: "detail_makanan", visible: false },
        ],
        order: [[5, "desc"]], // Urutkan berdasarkan no registrasi/rm
        columnDefs: [{ targets: [1, 11], className: "text-center" }],
    });

    // --- EVENT HANDLERS ---
    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        table.draw();
    });

    $("#reset-filter-btn").on("click", function () {
        $("#filter-form").trigger("reset");
        $(".select2").val(null).trigger("change");
        table.draw();
    });

    // Child row handler
    $("#dt-order tbody").on("click", "td.details-control", function () {
        var tr = $(this).closest("tr");
        var row = table.row(tr);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass("shown");
        } else {
            row.child(row.data().detail_makanan).show();
            tr.addClass("shown");
        }
    });

    // Aksi-aksi (delegation)
    $("#dt-order tbody").on("click", ".send-btn", function () {
        var id = $(this).data("id");
        Swal.fire({
            title: "Kirim Pesanan?",
            text: "Status pesanan akan diubah menjadi 'Terkirim'.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Kirim!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/simrs/gizi/order/status/" + id,
                    type: "PUT",
                    success: function (response) {
                        if (response.success) {
                            showSuccessAlert(response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function () {
                        showErrorAlert("Gagal mengubah status.");
                    },
                });
            }
        });
    });

    // Popup handlers
    function openPopup(url, title, width, height) {
        window.open(
            url,
            title,
            `width=${width},height=${height},scrollbars=yes,resizable=yes`
        );
    }

    $("#dt-order tbody").on("click", ".print-nota-btn", function () {
        openPopup(
            "/api/simrs/gizi/popup/print-nota/" + $(this).data("id"),
            "PrintNota",
            800,
            600
        );
    });
    $("#dt-order tbody").on("click", ".print-label-btn", function () {
        openPopup(
            "/api/simrs/gizi/popup/label/" + $(this).data("id"),
            "PrintLabel",
            400,
            400
        );
    });
    $("#dt-order tbody").on("click", ".edit-btn", function () {
        openPopup(
            "/api/simrs/gizi/popup/edit/" + $(this).data("id"),
            "EditSisaMakanan",
            600,
            500
        );
    });

    // Bulk actions
    $("#select-all-checkbox").on("click", function () {
        $(".row-checkbox").prop("checked", this.checked);
    });

    function getSelectedIds() {
        return $(".row-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
    }

    $("#bulk-send-btn").on("click", function () {
        var ids = getSelectedIds();
        if (ids.length === 0) {
            showErrorAlertNoRefresh(
                "Pilih minimal satu order yang akan dikirim."
            );
            return;
        }
        Swal.fire({
            title: "Kirim " + ids.length + " Pesanan?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Kirim!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/simrs/gizi/order/bulk-status",
                    type: "POST",
                    data: { ids: ids },
                    success: function (response) {
                        showSuccessAlert(response.message);
                        table.ajax.reload();
                    },
                    error: function () {
                        showErrorAlert("Gagal mengirim pesanan terpilih.");
                    },
                });
            }
        });
    });

    $("#bulk-print-btn").on("click", function () {
        var ids = getSelectedIds();
        if (ids.length === 0) {
            showErrorAlertNoRefresh(
                "Pilih minimal satu order untuk dicetak labelnya."
            );
            return;
        }
        var url = "/api/simrs/gizi/popup/bulk-label?ids=" + JSON.stringify(ids);
        openPopup(url, "BulkPrintLabel", 800, 600);
    });
});
