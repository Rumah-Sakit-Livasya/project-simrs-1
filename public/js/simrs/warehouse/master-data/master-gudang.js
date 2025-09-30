$(document).ready(function () {
    // Setup token CSRF untuk semua request AJAX
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const modal = $("#gudangModal");
    const form = $("#gudangForm");
    const modalTitle = $("#gudangModalLabel");
    const saveBtn = $("#saveBtn");

    // --- FUNGSI HELPER ---
    function resetForm() {
        form[0].reset(); // Reset form
        form.find("input, select").removeClass("is-invalid"); // Hapus kelas error
        form.find(".invalid-feedback").text(""); // Hapus pesan error
        $("#cost_center").val(null).trigger("change"); // Reset select2
        toggleDefaultApotekCheckboxes(form); // Jalankan logika checkbox
    }

    function clearErrors() {
        form.find(".is-invalid").removeClass("is-invalid");
        form.find(".invalid-feedback").empty();
    }

    function showErrors(errors) {
        clearErrors();
        $.each(errors, function (key, value) {
            $("#" + key).addClass("is-invalid");
            $("#" + key + "_error").text(value[0]);
        });
    }

    function toggleDefaultApotekCheckboxes(container) {
        const isApotekChecked = container
            .find(".apotek-checkbox")
            .is(":checked");
        container
            .find(".default-apotek-checkbox")
            .prop("disabled", !isApotekChecked);
        if (!isApotekChecked) {
            container.find(".default-apotek-checkbox").prop("checked", false);
        }
    }

    // --- EVENT HANDLER ---

    // 1. Tombol "Tambah" diklik
    $("#btn-add").on("click", function () {
        resetForm();
        modalTitle.text("Tambah Master Gudang");
        $("#formMethod").val("POST"); // Method untuk store
        form.attr("action", "/simrs/warehouse/master-data/master-gudang"); // URL untuk store
        modal.modal("show");
    });

    // 2. Tombol "Edit" di dalam tabel diklik
    $("#dt-master-gudang").on("click", ".edit-btn", function () {
        const id = $(this).data("id");
        const url = `/simrs/warehouse/master-data/master-gudang/${id}`;

        resetForm();

        // Ambil data dari server via AJAX
        $.get(url, function (data) {
            // Pastikan data yang diterima sesuai dengan struktur tabel warehouse_master_gudang
            modalTitle.text("Edit Master Gudang: " + (data.nama ?? ""));
            $("#formMethod").val("PUT");
            form.attr("action", url);

            // Isi form dengan data yang diterima
            $("#gudangId").val(data.id ?? "");
            $("#nama").val(data.nama ?? "");
            $("#cost_center")
                .val(data.cost_center ?? "")
                .trigger("change");

            // Handle Checkbox & Radio
            $("#apotek").prop("checked", data.apotek == 1);
            $("#warehouse").prop("checked", data.warehouse == 1);
            $("#rajal_default").prop("checked", data.rajal_default == 1);
            $("#ranap_default").prop("checked", data.ranap_default == 1);

            if (data.aktif == 1) {
                $("#status_aktif").prop("checked", true);
            } else {
                $("#status_nonaktif").prop("checked", true);
            }

            toggleDefaultApotekCheckboxes(form); // Cek status checkbox apotek
            modal.modal("show");
        });
    });

    // 3. Form di dalam modal disubmit
    form.on("submit", function (e) {
        e.preventDefault();
        saveBtn.prop("disabled", true).text("Menyimpan...");

        var url = $(this).attr("action");
        var method = $("#formMethod").val() === "PUT" ? "POST" : "POST"; // Laravel handle PUT via POST + _method
        var formData = new FormData(this);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                modal.modal("hide");
                showSuccessAlert(response.success);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    showErrors(xhr.responseJSON.errors);
                } else {
                    showErrorAlertNoRefresh("Terjadi kesalahan pada server.");
                }
            },
            complete: function () {
                saveBtn.prop("disabled", false).text("Simpan");
            },
        });
    });

    // 4. Logika checkbox apotek
    form.on("change", ".apotek-checkbox", function () {
        toggleDefaultApotekCheckboxes(form);
    });

    // Event listener untuk tombol hapus
    // Menggunakan event delegation untuk menangani elemen yang mungkin dimuat secara dinamis
    $("#dt-basic-example").on("click", ".delete-btn", function () {
        var gudangId = $(this).data("id");

        // URL yang sesuai dengan Route::resource
        var url = "/simrs/warehouse/master-data/master-gudang/" + gudangId;

        // Panggil fungsi konfirmasi SweetAlert dari script_footer
        showDeleteConfirmation(function () {
            $.ajax({
                url: url,
                type: "DELETE",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Tampilkan notifikasi sukses
                        showSuccessAlert(response.message);
                        // Reload halaman setelah 2 detik untuk melihat perubahan
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        // Tampilkan notifikasi error dari server
                        showErrorAlertNoRefresh(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    // Tampilkan notifikasi error umum jika AJAX gagal
                    showErrorAlertNoRefresh(
                        "Terjadi kesalahan saat menghapus data."
                    );
                    console.error("AJAX Error:", status, error);
                },
            });
        });
    });
});
