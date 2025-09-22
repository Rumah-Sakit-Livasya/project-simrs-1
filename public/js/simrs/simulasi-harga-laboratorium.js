// Pastikan skrip dijalankan setelah DOM sepenuhnya dimuat
$(document).ready(function () {
    // Variabel yang setara dengan properti kelas
    // Diasumsikan window._kategoriLaboratorium dan window._tarifLaboratorium di-set dari sisi server
    const kategoriLaboratorium = window._kategoriLaboratorium || [];
    const tarifLaboratorium = window._tarifLaboratorium || [];

    let groupTarif = 1;
    let kelasPerawatan = 1;
    let totalHarga = 0;
    const $elementHarga = $("#laboratorium-total"); // Cache elemen total harga
    let CITO = false;

    /**
     * Menginisialisasi semua event listener dan status awal
     */
    function init() {
        // Event listener untuk semua checkbox parameter
        $(".parameter_laboratorium_checkbox").on("change", calculateCost);

        // Event listener untuk semua input jumlah (number)
        $(".parameter_laboratorium_number").on("input", calculateCost);

        // Event listener untuk search bar
        $("#searchLaboratorium").on("keyup", handleSearchBarChange);

        // Event listener untuk radio button tipe order
        $("input[type='radio'][name='order_type']").on(
            "change",
            orderTypeChange
        );

        // Event listener untuk select group tarif
        const $groupSelect = $("#group_tarif");
        if ($groupSelect.length) {
            groupTarif = parseInt($groupSelect.val()) || 1;
            $groupSelect.on("change", handleGroupTarifSelectChange);
        }

        // Event listener untuk select kelas perawatan
        const $kelasSelect = $("#kelas_perawatan");
        if ($kelasSelect.length) {
            kelasPerawatan = parseInt($kelasSelect.val()) || 1;
            $kelasSelect.on("change", handleKelasPerawatanSelectChange);
        }

        // Memperbarui harga satuan pada UI saat pertama kali halaman dimuat
        updateCostDisplay();
        // Menghitung total biaya awal
        calculateCost();
    }

    /**
     * Menangani perubahan pada select kelas perawatan
     */
    function handleKelasPerawatanSelectChange() {
        kelasPerawatan = parseInt($(this).val());
        updateCostDisplay();
        calculateCost();
    }

    /**
     * Menangani perubahan pada select group tarif
     */
    function handleGroupTarifSelectChange() {
        groupTarif = parseInt($(this).val());
        updateCostDisplay();
        calculateCost();
    }

    /**
     * Menangani perubahan pada radio button tipe order (CITO/Biasa)
     */
    function orderTypeChange() {
        CITO = $(this).val() === "cito";
        calculateCost();
    }

    /**
     * Memperbarui tampilan harga satuan untuk setiap parameter di UI
     */
    function updateCostDisplay() {
        // Iterasi melalui setiap kategori dan parameternya
        $.each(kategoriLaboratorium, function (i, kategori) {
            $.each(kategori.parameter_laboratorium, function (ii, parameter) {
                const $hargaSpan = $(
                    `#harga_parameter_laboratorium_${parameter.id}`
                );
                if (!$hargaSpan.length) return; // Lanjut jika elemen tidak ditemukan

                // Cari tarif yang sesuai berdasarkan group dan kelas
                const tarif = tarifLaboratorium.find(
                    (t) =>
                        t.parameter_laboratorium_id == parameter.id &&
                        t.group_penjamin_id == groupTarif &&
                        t.kelas_rawat_id == kelasPerawatan
                );

                if (tarif) {
                    // Format harga ke format mata uang Rupiah
                    const formattedPrice = tarif.total.toLocaleString("id-ID", {
                        style: "currency",
                        currency: "IDR",
                    });
                    $hargaSpan.text(formattedPrice);
                } else {
                    console.log(
                        `Tarif belum di set atau tidak ditemukan! ID Parameter: ${parameter.id}`
                    );
                    // Diasumsikan showErrorAlertNoRefresh adalah fungsi global yang sudah ada
                    if (typeof showErrorAlertNoRefresh === "function") {
                        // showErrorAlertNoRefresh(
                        //     "Tarif tidak ditemukan atau belum di set! Mohon laporkan ke management. Cek log console!"
                        // );
                    }
                }
            });
        });
    }

    /**
     * Menghitung total biaya dari semua parameter yang dipilih
     */
    function calculateCost() {
        totalHarga = 0;
        let subTotal = 0;

        // Iterasi hanya pada checkbox yang dicentang
        $(".parameter_laboratorium_checkbox:checked").each(function () {
            const parameterId = $(this).val();

            // Cari tarif yang sesuai
            const tarif = tarifLaboratorium.find(
                (t) =>
                    t.parameter_laboratorium_id == parameterId &&
                    t.group_penjamin_id == groupTarif &&
                    t.kelas_rawat_id == kelasPerawatan
            );

            if (tarif) {
                const $jumlahInput = $(`#jumlah_${parameterId}`);
                let jumlah = parseInt($jumlahInput.val());

                // Validasi input jumlah, minimal 1
                if (isNaN(jumlah) || jumlah < 1) {
                    jumlah = 1;
                    $jumlahInput.val(1);
                }
                subTotal += tarif.total * jumlah;
            }
        });

        totalHarga = subTotal;

        // Jika CITO, tambahkan biaya tambahan 30% dari subtotal
        if (CITO) {
            totalHarga += (subTotal * 30) / 100;
        }

        // Tampilkan total harga yang sudah diformat
        if ($elementHarga.length) {
            $elementHarga.text(
                totalHarga.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                })
            );
        }
    }

    /**
     * Menangani event ketik pada search bar untuk memfilter parameter
     */
    function handleSearchBarChange() {
        const searchQuery = $(this).val().toLowerCase();

        if (searchQuery === "") {
            $(".parameter_laboratorium").show();
            return;
        }

        $(".parameter_laboratorium").each(function () {
            const $parameter = $(this);
            const parameterName = $parameter
                .find(".form-check-label")
                .text()
                .toLowerCase();

            // Tampilkan atau sembunyikan parameter berdasarkan input pencarian
            if (parameterName.includes(searchQuery)) {
                $parameter.show();
            } else {
                $parameter.hide();
            }
        });
    }

    // Jalankan fungsi inisialisasi
    init();
});
