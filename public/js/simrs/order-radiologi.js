/**
 * Script for Radiology Order Page
 * Author: Your Name
 * Version: 1.0.0
 * Dependencies: jQuery, SweetAlert2
 */

// Pastikan script berjalan setelah DOM siap
$(function () {
    // =================================================================
    // VARIABEL GLOBAL & STATE APLIKASI
    // =================================================================
    let tarifRadiologi = window._tarifRadiologi || [];
    let penjamins = window._penjamins || [];

    let currentRegistration = null;
    let groupTarif = 1; // Default: Tarif Umum
    let kelasPerawatan = 1; // Default: Sesuai Rawat Jalan atau Kelas 3
    let patientType = "rajal"; // Tipe pasien default
    let isCito = false; // Status order CITO

    // Cache elemen jQuery yang sering digunakan untuk performa
    const $form = $('form[name="form-radiologi"]');
    const $totalHargaElement = $("#radiologi-total");
    const $pilihPasienBtn = $("#pilih-pasien-btn");
    const $radiologyGrid = $("#radiology-grid-container");
    const $submitBtn = $(".submit-btn");

    // =================================================================
    // FUNGSI INISIALISASI
    // =================================================================
    function init() {
        // Tampilkan harga satuan berdasarkan tarif default saat halaman dimuat
        updateIndividualPrices();
        // Hitung total biaya awal (akan menjadi Rp 0)
        calculateCost();
        // Atur semua event listener yang dibutuhkan
        setupEventListeners();
    }

    // =================================================================
    // SETUP EVENT LISTENERS (HANYA SEKALI SAAT INIT)
    // =================================================================
    function setupEventListeners() {
        // Event delegation untuk performa lebih baik pada item-item di dalam grid
        $radiologyGrid.on(
            "change",
            ".parameter_radiologi_checkbox",
            calculateCost
        );
        $radiologyGrid.on("click", ".btn-quantity-stepper", handleStepperClick);

        // Event listener untuk elemen lain di luar grid
        $("#searchRadiology").on("keyup", handleSearchBarChange);
        $("#tipe_pasien").on("change", selectTipePasienChange);
        $('input[name="order_type"]').on("change", orderTypeChange);
        $pilihPasienBtn.on("click", handlePilihPasienButtonClick);
        $submitBtn.on("click", (e) => {
            e.preventDefault(); // Mencegah form submit default
            submitForm();
        });

        // PERBAIKAN: Multiple event listener untuk menangkap pesan dari popup
        // Menggunakan addEventListener native untuk lebih reliable
        window.addEventListener(
            "message",
            function (event) {
                console.log("Pesan diterima dari popup:", event);

                // Validasi origin untuk keamanan (opsional tapi direkomendasikan)
                // if (event.origin !== window.location.origin) return;

                // Validasi pesan yang diterima untuk keamanan dan keandalan
                if (
                    event.data &&
                    event.data.type === "patientSelected" &&
                    event.data.data
                ) {
                    console.log("Data pasien yang diterima:", event.data.data);
                    changeRegistration(event.data.data);
                }
            },
            false
        );

        // Backup menggunakan jQuery event (untuk kompatibilitas)
        $(window).on("message", function (event) {
            const originalEvent = event.originalEvent;
            console.log("Pesan jQuery diterima:", originalEvent);

            // Validasi pesan yang diterima untuk keamanan dan keandalan
            if (
                originalEvent.data &&
                originalEvent.data.type === "patientSelected" &&
                originalEvent.data.data
            ) {
                console.log(
                    "Menerima data pasien dari popup (jQuery):",
                    originalEvent.data.data
                );
                changeRegistration(originalEvent.data.data);
            }
        });
    }

    // =================================================================
    // FUNGSI-FUNGSI UTAMA (CORE LOGIC)
    // =================================================================

    /**
     * Mengupdate tampilan harga satuan di samping setiap item tindakan.
     * Dipanggil saat init, ganti pasien, atau reset form.
     */
    function updateIndividualPrices() {
        $(".parameter_radiologi").each(function () {
            const $item = $(this);
            const parameterId = parseInt(
                $item.find(".parameter_radiologi_checkbox").val()
            );
            const $priceElement = $item.find(".test-price");

            const tarif = tarifRadiologi.find(
                (t) =>
                    t.parameter_radiologi_id == parameterId &&
                    t.group_penjamin_id == groupTarif &&
                    t.kelas_rawat_id == kelasPerawatan
            );

            if (tarif) {
                const formattedPrice = tarif.total.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                });
                $priceElement.text(formattedPrice).css("color", "#28a745");
            } else {
                // Menandakan jika tarif tidak ditemukan
                $priceElement.text("N/A").css("color", "red");
            }
        });
    }

    /**
     * Menangani klik pada tombol +/- kuantitas.
     */
    function handleStepperClick() {
        const $button = $(this);
        const action = $button.data("action");
        const $input = $button
            .closest(".quantity-stepper")
            .find(".quantity-input");
        let currentValue = parseInt($input.val());

        if (action === "increment") {
            currentValue++;
        } else if (action === "decrement" && currentValue > 1) {
            currentValue--;
        }
        $input.val(currentValue);
        // Hitung ulang total biaya setelah kuantitas berubah
        calculateCost();
    }

    /**
     * Menyaring daftar tindakan radiologi berdasarkan input pencarian.
     */
    function handleSearchBarChange() {
        const searchQuery = $(this).val().toLowerCase().trim();

        $(".category-column").each(function () {
            const $categoryCard = $(this);
            let categoryVisible = false;

            $categoryCard.find(".parameter_radiologi").each(function () {
                const $parameterItem = $(this);
                const parameterName = $parameterItem
                    .find(".custom-control-label")
                    .text()
                    .toLowerCase();

                if (parameterName.includes(searchQuery)) {
                    $parameterItem.css("display", "flex"); // Gunakan flex agar tetap rata
                    categoryVisible = true;
                } else {
                    $parameterItem.hide();
                }
            });
            // Sembunyikan seluruh kartu kategori jika tidak ada hasil yang cocok
            $categoryCard.toggle(categoryVisible);
        });
    }

    /**
     * Membuka jendela popup untuk memilih pasien.
     */
    function handlePilihPasienButtonClick(e) {
        e.preventDefault();
        if ($pilihPasienBtn.is(":disabled")) return;

        const popupUrl = `/simrs/radiologi/popup/pilih-pasien/${patientType}`;
        const popupName = "popupPilihPasien";
        const popupFeatures = `width=${screen.width * 0.9},height=${
            screen.height * 0.9
        },top=${screen.height * 0.05},left=${
            screen.width * 0.05
        },scrollbars=yes,resizable=yes`;

        // PERBAIKAN: Simpan referensi popup dan tambahkan error handling
        let popup = window.open(popupUrl, popupName, popupFeatures);
        if (popup) {
            popup.focus();

            // TAMBAHAN: Monitor popup untuk debugging
            console.log("Popup berhasil dibuka:", popup);

            // Optional: Periodic check apakah popup masih terbuka
            const checkClosed = setInterval(() => {
                if (popup.closed) {
                    console.log("Popup ditutup");
                    clearInterval(checkClosed);
                }
            }, 1000);
        } else {
            // Memberi tahu pengguna jika popup diblokir
            alert("Gagal membuka popup. Mohon izinkan popup untuk situs ini.");
        }
    }

    /**
     * Memproses data pasien yang diterima dari popup dan mengisi form.
     * @param {object} registration - Data lengkap registrasi pasien.
     */
    function changeRegistration(registration) {
        console.log("Memproses data registrasi:", registration);

        currentRegistration = registration;

        // Tentukan grup tarif dan kelas perawatan berdasarkan data pasien
        const penjamin = penjamins.find(
            (p) => p.id == registration.penjamin_id
        );
        groupTarif = penjamin ? penjamin.group_penjamin_id : 1;
        kelasPerawatan = registration.kelas_rawat_id
            ? parseInt(registration.kelas_rawat_id)
            : 1;

        console.log(
            "Group tarif:",
            groupTarif,
            "Kelas perawatan:",
            kelasPerawatan
        );

        // PERBAIKAN: Tambahkan null check dan fallback untuk data yang mungkin kosong
        $("#nama_pasien").val(registration.patient?.name || "");
        $("#date_of_birth").val(registration.patient?.date_of_birth || "");
        $("#poly_ruang").val(registration.departement?.name || "");
        $("#alamat").val(registration.patient?.address || "");
        $("#no_telp").val(registration.patient?.mobile_phone_number || "");
        $("#mrn_registration_number").val(
            `${registration.patient?.medical_record_number || ""} / ${
                registration.registration_number || ""
            }`
        );

        // PERBAIKAN: Handle gender dengan lebih robust
        if (registration.patient?.gender) {
            const gender =
                registration.patient.gender === "Laki-laki" ? "male" : "female";
            $(`#gender_${gender}`).prop("checked", true);
        }

        // Perbarui harga satuan dan total biaya setelah pasien dipilih
        updateIndividualPrices();
        calculateCost();

        // TAMBAHAN: Visual feedback bahwa data berhasil dimuat
        $("#nama_pasien")
            .css("background-color", "#d4edda")
            .delay(2000)
            .queue(function () {
                $(this).css("background-color", "").dequeue();
            });

        console.log("Data pasien berhasil dimuat ke form");
    }

    /**
     * Mengosongkan form dan mereset state saat tipe pasien diubah atau data dihapus.
     */
    function clearInputs() {
        currentRegistration = null;
        groupTarif = 1; // Reset ke tarif umum
        kelasPerawatan = 1; // Reset ke kelas default

        $(
            "#nama_pasien, #date_of_birth, #poly_ruang, #mrn_registration_number, #alamat, #no_telp, #diagnosa_awal"
        ).val("");
        $('input[name="jenis_kelamin"]').prop("checked", false);

        // Reset harga ke tarif default dan hitung ulang total
        updateIndividualPrices();
        calculateCost();
    }

    /**
     * Mengatur UI dan state berdasarkan pilihan tipe pasien (Rajal/Ranap/OTC).
     */
    function selectTipePasienChange() {
        clearInputs();
        patientType = $(this).val();

        if (patientType === "otc") {
            // Aktifkan input untuk pasien luar/OTC
            $("#nama_pasien, #date_of_birth").prop("readonly", false);
            $('input[name="jenis_kelamin"]').prop("disabled", false);
            $("#mrn_registration_number").val("OTC");
            $("#poly_ruang").val("RADIOLOGI");
            $pilihPasienBtn.prop("disabled", true);
        } else {
            // Kunci input untuk pasien terdaftar (data dari popup)
            $("#nama_pasien, #date_of_birth").prop("readonly", true);
            $('input[name="jenis_kelamin"]').prop("disabled", true);
            $pilihPasienBtn.prop("disabled", false);
        }
    }

    /**
     * Menangani perubahan tipe order (Normal / CITO) dan menghitung ulang biaya.
     */
    function orderTypeChange() {
        isCito = $(this).val() === "cito";
        calculateCost();
    }

    /**
     * Menghitung total biaya dari semua item yang dicentang.
     */
    function calculateCost() {
        let totalHarga = 0;
        // Hanya iterasi pada checkbox yang dicentang untuk efisiensi
        $(".parameter_radiologi_checkbox:checked").each(function () {
            const parameterId = parseInt($(this).val());
            const kuantitas = parseInt($(`#jumlah_${parameterId}`).val()) || 1;

            const tarif = tarifRadiologi.find(
                (t) =>
                    t.parameter_radiologi_id == parameterId &&
                    t.group_penjamin_id == groupTarif &&
                    t.kelas_rawat_id == kelasPerawatan
            );

            if (tarif) {
                let hargaSatuan = tarif.total;
                // Tambahkan biaya CITO jika dipilih
                if (isCito) {
                    hargaSatuan += hargaSatuan * 0.3;
                }
                totalHarga += hargaSatuan * kuantitas;
            }
        });

        // Tampilkan total biaya yang sudah diformat
        const formattedTotal = totalHarga.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
        $totalHargaElement.data("total", totalHarga).text(formattedTotal);
    }

    /**
     * Memvalidasi form, mengumpulkan data, dan menampilkan konfirmasi sebelum submit.
     */
    function submitForm() {
        const formData = new FormData($form[0]);

        // --- Validasi Sisi Klien ---
        if (!currentRegistration && patientType !== "otc") {
            return showErrorAlertNoRefresh(
                "Silahkan pilih pasien terlebih dahulu!"
            );
        }
        if (patientType === "otc" && !$("#nama_pasien").val().trim()) {
            return showErrorAlertNoRefresh(
                "Nama Pasien OTC tidak boleh kosong!"
            );
        }
        if (!$("#doctor_id").val()) {
            return showErrorAlertNoRefresh("Silahkan pilih Dokter Radiologi!");
        }
        const $checkedItems = $(".parameter_radiologi_checkbox:checked");
        if ($checkedItems.length === 0) {
            return showErrorAlertNoRefresh(
                "Pilih minimal satu pemeriksaan radiologi!"
            );
        }

        // --- Pengumpulan Data Tambahan ---
        let parameters = [];
        $checkedItems.each(function () {
            const parameterId = parseInt($(this).val());
            const kuantitas = parseInt($("#jumlah_" + parameterId).val());
            const tarif = tarifRadiologi.find(
                (t) =>
                    t.parameter_radiologi_id == parameterId &&
                    t.group_penjamin_id == groupTarif &&
                    t.kelas_rawat_id == kelasPerawatan
            );
            if (tarif) {
                let hargaSatuan = tarif.total;
                if (isCito) hargaSatuan += hargaSatuan * 0.3;
                parameters.push({
                    id: parameterId,
                    qty: kuantitas,
                    price: hargaSatuan,
                });
            }
        });

        formData.append("parameters", JSON.stringify(parameters));
        formData.append("total_biaya", $totalHargaElement.data("total"));
        if (currentRegistration) {
            formData.append("registration_id", currentRegistration.id);
            formData.append(
                "registration_type",
                currentRegistration.registration_type
            );
        } else {
            formData.append("is_otc", "1");
        }

        // --- Konfirmasi Pengguna ---
        Swal.fire({
            title: "Konfirmasi Order",
            html: `Anda akan menyimpan order radiologi dengan total biaya <b>${$totalHargaElement.text()}</b>.<br>Apakah Anda yakin?`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                sendData(formData);
            }
        });
    }

    /**
     * Mengirimkan data ke server menggunakan AJAX.
     * @param {FormData} formData - Data form yang akan dikirim.
     */
    function sendData(formData) {
        $.ajax({
            url: "/api/simrs/order-radiologi",
            method: "POST",
            data: formData,
            processData: false, // Wajib false untuk FormData
            contentType: false, // Wajib false untuk FormData
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            beforeSend: function () {
                // Beri feedback visual saat proses pengiriman
                $submitBtn
                    .prop("disabled", true)
                    .html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                    );
            },
            success: function (response) {
                if (response.success) {
                    showSuccessAlert("Order berhasil disimpan!");
                    setTimeout(
                        () =>
                            (window.location.href =
                                "/simrs/radiologi/list-order"),
                        1500
                    );
                } else {
                    const errorMessages = response.errors
                        ? Object.values(response.errors).join("<br>")
                        : response.message;
                    showErrorAlertNoRefresh(errorMessages);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(
                    "AJAX Error:",
                    textStatus,
                    errorThrown,
                    jqXHR.responseText
                );
                showErrorAlertNoRefresh(
                    "Terjadi kesalahan teknis. Silakan hubungi administrator."
                );
            },
            complete: function () {
                // Kembalikan tombol ke keadaan semula setelah selesai
                $submitBtn
                    .prop("disabled", false)
                    .html(
                        '<span class="fal fa-save mr-1"></span> Simpan Order'
                    );
            },
        });
    }

    // =================================================================
    // FUNGSI UTILITY UNTUK DEBUGGING
    // =================================================================

    // TAMBAHAN: Expose fungsi untuk debugging di console
    window.debugRadiologi = {
        currentRegistration: () => currentRegistration,
        tarifRadiologi: () => tarifRadiologi,
        penjamins: () => penjamins,
        groupTarif: () => groupTarif,
        kelasPerawatan: () => kelasPerawatan,
        testChangeRegistration: (data) => changeRegistration(data),
    };

    // =================================================================
    // JALANKAN INISIALISASI SAAT DOKUMEN SIAP
    // =================================================================
    init();
});
