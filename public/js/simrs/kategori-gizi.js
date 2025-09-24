$(document).ready(function () {
    // --- DATATABLES ---
    var table = $("#dt-kategori").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/api/simrs/gizi/kategori/datatable",
            data: function (d) {
                d.nama_kategori = $("#nama_kategori_filter").val();
            },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
            { data: "nama", name: "nama" },
            { data: "coa_pendapatan", name: "coa_pendapatan" },
            { data: "coa_biaya", name: "coa_biaya" },
            { data: "status", name: "status", className: "text-center" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
        ],
        dom:
            "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                extend: "pdfHtml5",
                text: "PDF",
                titleAttr: "Generate PDF",
                className: "btn-outline-danger btn-sm mr-1",
            },
            {
                extend: "excelHtml5",
                text: "Excel",
                titleAttr: "Generate Excel",
                className: "btn-outline-success btn-sm mr-1",
            },
            {
                extend: "print",
                text: "Print",
                titleAttr: "Print Table",
                className: "btn-outline-primary btn-sm",
            },
        ],
    });

    // Handle form filter
    $("#filter-form").on("submit", function (e) {
        e.preventDefault();
        table.draw();
    });

    // --- DELETE ACTION ---
    $("#dt-kategori tbody").on("click", ".delete-btn", function () {
        var id = $(this).data("id");
        showDeleteConfirmation(function () {
            $.ajax({
                url: "/api/simrs/gizi/kategori/" + id,
                type: "DELETE",
                // CSRF token sudah di-handle oleh $.ajaxSetup di layout utama
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
                    let errorMsg =
                        "Terjadi kesalahan. Tidak dapat menghapus data.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showErrorAlert(errorMsg);
                },
            });
        });
    });
});
