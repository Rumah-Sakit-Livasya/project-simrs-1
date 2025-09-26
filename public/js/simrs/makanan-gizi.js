/**
 * Script untuk halaman Manajemen Makanan Gizi
 * Menggunakan jQuery, DataTables, dan SweetAlert2
 */
$(document).ready(function () {
    // 1. Inisialisasi DataTables
    // ----------------------------------------------------------------
    var table = $("#dt-makanan").DataTable({
        processing: true, // Menampilkan indikator loading
        serverSide: true, // Memproses data di sisi server
        responsive: true, // Membuat tabel responsif
        ajax: {
            url: "/api/simrs/gizi/makanan/datatable",
            // Mengirim data filter tambahan saat request AJAX
            data: function (d) {
                d.nama_makanan = $("#nama_makanan_filter").val();
            },
        },
        columns: [
            // Kolom Nomor (bukan dari database)
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
            // Kolom dari database
            { data: "nama", name: "nama" },
            { data: "harga", name: "harga" },
            { data: "status", name: "status", className: "text-center" },
            // Kolom Aksi (tombol edit & hapus)
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                className: "text-center",
            },
        ],
        // Konfigurasi tombol ekspor (PDF, Excel, dll)
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

    // 2. Logika untuk Filter
    // ----------------------------------------------------------------
    $("#filter-form").on("submit", function (e) {
        e.preventDefault(); // Mencegah form submit default
        table.draw(); // Menggambar ulang tabel dengan filter baru
    });

    // 3. Logika untuk Tombol Hapus (Event Delegation)
    // ----------------------------------------------------------------
    // Kita menggunakan event delegation pada '#dt-makanan tbody'
    // karena tombol '.delete-btn' dibuat secara dinamis oleh DataTables.
    $("#dt-makanan tbody").on("click", ".delete-btn", function () {
        var id = $(this).data("id"); // Ambil ID dari atribut data-id

        // Ambil CSRF token dari meta tag (pastikan ada <meta name="csrf-token" content="..."> di layout)
        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        showDeleteConfirmation(function () {
            $.ajax({
                url: "/api/simrs/gizi/makanan/" + id,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    if (response.success) {
                        showSuccessAlert(response.message);
                        table.ajax.reload();
                    } else {
                        showErrorAlert(response.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 419) {
                        showErrorAlert(
                            "Session expired. Silakan refresh halaman dan coba lagi."
                        );
                    } else {
                        showErrorAlert(
                            "Terjadi kesalahan. Tidak dapat menghapus data."
                        );
                    }
                    console.error("AJAX Error:", xhr.responseText);
                },
            });
        });
    });
});
