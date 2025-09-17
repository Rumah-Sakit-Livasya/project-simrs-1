// Pastikan skrip dijalankan setelah DOM sepenuhnya dimuat
$(document).ready(function () {
    // Diasumsikan window.Swal adalah instance SweetAlert2 yang sudah ada secara global
    const Swal = window.Swal;

    // Ambil data order dari variabel global yang di-set oleh server
    const order = window._order || {};
    // Ambil token CSRF sekali untuk digunakan kembali
    const csrfToken = $('meta[name="csrf-token"]').attr("content") || "";

    /**
     * Menginisialisasi semua event listener
     */
    function init() {
        // Menggunakan delegasi event untuk menangani elemen yang mungkin ditambahkan secara dinamis
        // Namun, jika tombol sudah pasti ada saat halaman dimuat, .on() langsung juga bisa
        $(document).on("click", ".verify-btn", handleVerifyButton);
        $(document).on("click", ".delete-btn", handleDeleteButton);
    }

    /**
     * Menangani klik pada tombol hapus
     * @param {Event} event
     */
    function handleDeleteButton(event) {
        event.preventDefault();
        // $(this) merujuk pada elemen tombol yang diklik
        const id = $(this).data("id");
        if (!id) return;

        Swal.fire({
            title: "Hapus order parameter?",
            text: "Semua sub parameter ini akan ikut dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                deleteData(id);
            }
        });
    }

    /**
     * Mengirim permintaan untuk menghapus data
     * @param {string} id
     */
    function deleteData(id) {
        const formData = new FormData();
        formData.append("order_parameter_id", id);
        formData.append("order_id", order.id);

        $.ajax({
            url: "/api/simrs/laboratorium/parameter-delete",
            method: "POST",
            data: formData,
            processData: false, // Penting saat mengirim FormData
            contentType: false, // Penting saat mengirim FormData
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                // Diasumsikan showSuccessAlert adalah fungsi global
                showSuccessAlert("Data berhasil dihapus");
                setTimeout(() => window.location.reload(), 2000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error:", errorThrown);
                // Diasumsikan showErrorAlertNoRefresh adalah fungsi global
                showErrorAlertNoRefresh(`Error: ${errorThrown}`);
            },
        });
    }

    /**
     * Menangani klik pada tombol verifikasi
     * @param {Event} event
     */
    function handleVerifyButton(event) {
        event.preventDefault();
        const id = $(this).data("id");
        if (!id) return;

        const employeeId = $('input[name="employee_id"]').val();
        if (!employeeId) {
            showErrorAlertNoRefresh("Employee ID is required");
            return;
        }

        const now = new Date();
        // Membuat format YYYY-MM-DD HH:MM:SS
        const formattedDate = now.toISOString().slice(0, 19).replace("T", " ");

        Swal.fire({
            title: "Verifikasi",
            html: "Verifikasi expertise?",
            icon: "question",
            focusConfirm: true,
            showCancelButton: true,
            confirmButtonText: "Verifikasi",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append("id", id);
                formData.append("verifikator_id", employeeId);
                formData.append("verifikasi_date", formattedDate);

                $.ajax({
                    url: "/api/simrs/laboratorium/parameter-verify",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        showSuccessAlert("Data berhasil diverifikasi");
                        setTimeout(() => window.location.reload(), 2000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Error:", errorThrown);
                        showErrorAlertNoRefresh(`Error: ${errorThrown}`);
                    },
                });
            }
        });
    }

    // Panggil fungsi inisialisasi untuk memulai
    init();
});
