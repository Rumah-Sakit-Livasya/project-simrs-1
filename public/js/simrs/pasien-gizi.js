$(document).ready(function () {
    // --- SETUP ---
    $(".select2").select2({
        placeholder: "Semua",
        allowClear: true,
    });

    // --- DATATABLES ---
    var table = $("#dt-pasien").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/api/simrs/gizi/pasien/datatable",
            type: "GET",
            data: function (d) {
                var formData = $("#filter-form").serializeArray();
                $.each(formData, function (i, field) {
                    d[field.name] = field.value;
                });
            },
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "kelas", name: "kelas_rawat.kelas" },
            { data: "ruang", name: "patient.bed.room.ruangan" },
            { data: "tempat_tidur", name: "patient.bed.nama_tt" },
            { data: "registration_number", name: "registration_number" },
            { data: "pasien_info", name: "patient.name" },
            { data: "dokter", name: "doctor.employee.fullname" },
            { data: "diagnosa_awal", name: "diagnosa_awal" },
            { data: "kategori_diet", name: "diet_gizi.category.nama" },
            { data: "asuransi", name: "penjamin.penjamin" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
        order: [[4, "desc"]], // Urutkan berdasarkan no registrasi
        columnDefs: [{ targets: [0, 10], className: "text-center" }],
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

    // --- POPUP ACTIONS ---
    function openPopup(url, title, width, height) {
        // Logika untuk membuka popup (bisa disesuaikan)
        const left = (screen.width - width) / 2;
        const top = (screen.height - height) / 2;
        const popup = window.open(
            url,
            title,
            `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
        );

        // Tambahkan listener untuk refresh tabel setelah popup ditutup
        if (popup) {
            const timer = setInterval(function () {
                if (popup.closed) {
                    clearInterval(timer);
                    table.ajax.reload(null, false); // Reload tabel tanpa reset paging
                }
            }, 500);
        }
    }

    $("#dt-pasien tbody").on("click", ".action-btn", function () {
        var action = $(this).data("action");
        var id = $(this).data("id");
        var url, title, width, height;

        switch (action) {
            case "pilih-diet":
                url = "/simrs/gizi/popup/pilih-diet/" + id;
                title = "PilihDiet_" + id;
                width = 800;
                height = 600;
                break;
            case "order-pasien":
                url = "/simrs/gizi/popup/order/pasien/" + id;
                title = "OrderPasien_" + id;
                width = screen.width * 0.7;
                height = screen.height * 0.7;
                break;
            case "order-keluarga":
                url = "/simrs/gizi/popup/order/keluarga/" + id;
                title = "OrderKeluarga_" + id;
                width = screen.width * 0.7;
                height = screen.height * 0.7;
                break;
            default:
                return;
        }
        openPopup(url, title, width, height);
    });
});
