// public/js/simrs/simulasi-harga-radiologi.js

$(document).ready(function () {
    // =================================================================
    // DEKLARASI VARIABEL
    // =================================================================

    // Ambil data dari Blade, sediakan array kosong sebagai fallback
    const kategoriRadiologi = window._kategoriRadiologi || [];
    const tarifRadiologi = window._tarifRadiologi || [];

    // Variabel untuk menyimpan state filter dan total
    let groupTarif = 1;
    let kelasPerawatan = 1;
    let totalHarga = 0;
    const $elementHarga = $("#radiologi-total"); // Cache elemen jQuery
    let CITO = false;

    // =================================================================
    // FUNGSI-FUNGSI UTAMA
    // =================================================================

    /**
     * Menginisialisasi semua event listener dan status awal halaman.
     */
    function init() {
        // Event listener untuk semua checkbox parameter
        $(".parameter_radiologi_checkbox").on("change", calculateCost);

        // Event listener untuk semua input jumlah (number)
        $(".parameter_radiologi_number").on("input", calculateCost);

        // Event listener untuk search bar
        $("#searchRadiology").on("keyup", handleSearchBarChange);

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

        // Update harga satuan pada UI saat pertama kali halaman dimuat
        updateCostDisplay();
        // Hitung total biaya awal
        calculateCost();
    }

    /**
     * Menangani perubahan pada select kelas perawatan.
     */
    function handleKelasPerawatanSelectChange() {
        kelasPerawatan = parseInt($(this).val());
        updateCostDisplay();
        calculateCost();
    }

    /**
     * Menangani perubahan pada select group tarif.
     */
    function handleGroupTarifSelectChange() {
        groupTarif = parseInt($(this).val());
        updateCostDisplay();
        calculateCost();
    }

    /**
     * Menangani perubahan pada radio button tipe order (CITO/Normal).
     */
    function orderTypeChange() {
        CITO = $(this).val() === "cito";
        calculateCost();
    }

    /**
     * Memperbarui tampilan harga satuan untuk setiap parameter di UI.
     */
    function updateCostDisplay() {
        $.each(kategoriRadiologi, function (i, kategori) {
            $.each(kategori.parameter_radiologi, function (ii, parameter) {
                const $hargaSpan = $(
                    `#harga_parameter_radiologi_${parameter.id}`
                );
                if (!$hargaSpan.length) return; // Lanjut jika elemen span harga tidak ada

                // Cari tarif yang sesuai berdasarkan group dan kelas
                const tarif = tarifRadiologi.find(
                    (t) =>
                        t.parameter_radiologi_id == parameter.id &&
                        t.group_penjamin_id == groupTarif &&
                        t.kelas_rawat_id == kelasPerawatan
                );

                if (tarif && tarif.total !== undefined) {
                    const formattedPrice = parseFloat(
                        tarif.total
                    ).toLocaleString("id-ID", {
                        style: "currency",
                        currency: "IDR",
                    });
                    $hargaSpan.text(formattedPrice);
                } else {
                    $hargaSpan.text("Rp 0,00"); // Tampilkan harga default jika tarif tidak ditemukan
                }
            });
        });
    }

    /**
     * Menghitung total biaya dari semua parameter yang dipilih.
     * Ini adalah versi yang sudah diperbaiki logikanya.
     */
    function calculateCost() {
        let subTotal = 0;

        // Iterasi hanya pada checkbox yang dicentang untuk efisiensi
        $(".parameter_radiologi_checkbox:checked").each(function () {
            const parameterId = $(this).val();

            // Cari tarif yang sesuai
            const tarif = tarifRadiologi.find(
                (t) =>
                    t.parameter_radiologi_id == parameterId &&
                    t.group_penjamin_id == groupTarif &&
                    t.kelas_rawat_id == kelasPerawatan
            );

            if (tarif && tarif.total !== undefined) {
                const $jumlahInput = $(`#jumlah_${parameterId}`);
                let jumlah = parseInt($jumlahInput.val());

                // Validasi input jumlah, minimal 1
                if (isNaN(jumlah) || jumlah < 1) {
                    jumlah = 1;
                    $jumlahInput.val(1);
                }
                subTotal += parseFloat(tarif.total) * jumlah;
            }
        });

        totalHarga = subTotal;

        // Tambahkan biaya CITO 30% dari subtotal, HANYA SEKALI setelah loop selesai.
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
     * Menangani event ketik pada search bar untuk memfilter parameter.
     */
    function handleSearchBarChange() {
        const searchQuery = $(this).val().toLowerCase();

        // Iterasi per card untuk bisa menyembunyikan card jika kosong
        $(".card-tindakan").each(function () {
            const $card = $(this);
            let cardVisible = false;

            $card.find(".item.parameter_radiologi").each(function () {
                const $item = $(this);
                const parameterName = $item
                    .find(".custom-control-label")
                    .text()
                    .toLowerCase();

                if (parameterName.includes(searchQuery)) {
                    $item.show();
                    cardVisible = true;
                } else {
                    $item.hide();
                }
            });

            if (cardVisible) {
                $card.show();
            } else {
                $card.hide();
            }
        });
    }

    // =================================================================
    // INISIALISASI
    // =================================================================
    init();
});
