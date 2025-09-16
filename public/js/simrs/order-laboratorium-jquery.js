// Membungkus semua kode di dalam jQuery's document ready function.
jQuery(function ($) {
    /**
     * @class OrderLaboratorium
     * Mengelola semua logika untuk halaman order laboratorium dengan layout Grid.
     */
    class OrderLaboratorium {
        _KategoriLaboratorium;
        _KelasRawat;
        _Penjamins;
        _TarifLaboratorium;
        _Registration;
        _totalHarga = 0;
        _groupTarif = 1;
        _kelasPerawatan = 1;
        _elementHarga;
        _elementForm;
        _pilihPasienButton;
        _patienType = "rajal";
        _CITO = false;

        constructor() {
            // Mengambil data global yang di-pass dari Blade view.
            this._KategoriLaboratorium = window._kategoriLaboratorium;
            this._TarifLaboratorium = window._tarifLaboratorium;
            this._Penjamins = window._penjamins;
            this._KelasRawat = window._kelasRawats;

            this._init();
        }

        /**
         * Inisialisasi awal, menyeleksi elemen DOM dan memasang event listener.
         */
        _init() {
            // Event listener untuk fungsionalitas utama form
            $("#searchLaboratorium").on(
                "keyup",
                this._handleSearchBarChange.bind(this)
            );
            $("input[type='radio'][name='order_type']").on(
                "change",
                this._orderTypeChange.bind(this)
            );
            $("#tipe_pasien").on(
                "change",
                this._selectTipePasienChange.bind(this)
            );
            $("#pilih-pasien-btn").on(
                "click",
                this._handlePilihPasienButtonClick.bind(this)
            );
            $("button.submit-btn").on("click", this._submit.bind(this));

            // Menyimpan referensi elemen jQuery
            this._elementHarga = $("#laboratorium-total");
            this._elementForm = $("form[name='form-laboratorium']");
            this._pilihPasienButton = $("#pilih-pasien-btn");

            // Listener global untuk menerima data pasien dari jendela popup
            window.addEventListener("message", (event) => {
                if (
                    event.data &&
                    event.data.type === "pasien_selected" &&
                    event.data.data
                ) {
                    this.changeRegistration(event.data.data);
                }
            });

            // === PERBAIKAN 1: Update selector untuk event delegation ===
            // Targetkan kontainer grid yang baru.
            $("#laboratorium-grid-container").on(
                "click",
                ".btn-quantity-stepper",
                function () {
                    const $button = $(this);
                    const action = $button.data("action");
                    const $input = $button
                        .closest(".quantity-stepper")
                        .find(".quantity-input");

                    if ($input.is(":disabled")) return;

                    let currentValue = parseInt($input.val());
                    if (action === "increment") {
                        currentValue++;
                    } else if (action === "decrement" && currentValue > 1) {
                        currentValue--;
                    }
                    $input.val(currentValue).trigger("input");
                }
            );

            // Event delegation untuk checkbox dan input number agar lebih efisien
            $("#laboratorium-grid-container").on(
                "change",
                ".parameter_laboratorium_checkbox",
                this._handleCheckboxChange.bind(this)
            );
            $("#laboratorium-grid-container").on(
                "input",
                ".parameter_laboratorium_number",
                this._handleNumberChange.bind(this)
            );

            // Inisialisasi tampilan harga awal
            this._updateAllParameterPricesUI();
        }

        /**
         * === PERBAIKAN 2: Logika pencarian baru untuk layout GRID ===
         */
        _handleSearchBarChange(event) {
            const searchQuery = $(event.target).val().toLowerCase().trim();

            // Jika search bar dikosongkan, tampilkan semua kolom dan item.
            if (searchQuery === "") {
                $(".category-column").show();
                $(".test-item").show();
                return;
            }

            // Iterasi pada setiap kolom kategori
            $(".category-column").each(function () {
                const $categoryColumn = $(this);
                let categoryHasVisibleItems = false;

                // Iterasi pada setiap item di dalam kolom ini
                $categoryColumn.find(".test-item").each(function () {
                    const $item = $(this);
                    const itemName = $item
                        .find(".custom-control-label")
                        .text()
                        .toLowerCase();

                    // Jika item cocok dengan pencarian
                    if (itemName.includes(searchQuery)) {
                        $item.show();
                        categoryHasVisibleItems = true; // Tandai bahwa kolom ini punya hasil
                    } else {
                        $item.hide();
                    }
                });

                // Tampilkan atau sembunyikan seluruh kolom (card kategori)
                $categoryColumn.toggle(categoryHasVisibleItems);
            });
        }

        // ... (Sisa method di bawah ini TIDAK PERLU diubah, karena logikanya sudah benar) ...

        _handlePilihPasienButtonClick(event) {
            event.preventDefault();
            if (this._pilihPasienButton.prop("disabled")) return;
            const popup = window.open(
                `popup/pilih-pasien/${this._patienType}`,
                "popupPilihPasien",
                `width=${screen.width},height=${screen.height},top=0,left=0`
            );
            if (!popup) {
                return alert(
                    "Gagal membuka popup. Mohon izinkan popup untuk situs ini."
                );
            }
        }

        changeRegistration(registration) {
            this._Registration = registration;
            const penjamin = this._Penjamins.find(
                (p) => p.id == registration.penjamin_id
            );
            if (penjamin) {
                this._groupTarif = penjamin.group_penjamin_id;
            }
            this._kelasPerawatan = registration.kelas_rawat_id
                ? parseInt(registration.kelas_rawat_id)
                : 1;
            $("#nama_pasien").val(registration.patient.name);
            $("#date_of_birth").val(registration.patient.date_of_birth);
            $("#poly_ruang").val(registration.departement.name);
            $("#alamat").val(registration.patient.address);
            $("#no_telp").val(registration.patient.mobile_phone_number);
            $("input[name='medical_record_number']").val(
                registration.patient.medical_record_number
            );
            $("input[name='registration_number']").val(
                registration.registration_number
            );
            $("#mrn_registration_number").val(
                `${registration.patient.medical_record_number} / ${registration.registration_number}`
            );
            const genderId =
                registration.patient.gender === "Laki-laki"
                    ? "#gender_male"
                    : "#gender_female";
            $(genderId).prop("checked", true);
            this._updateAllParameterPricesUI();
            this._calculateCost();
        }

        _clearInputs() {
            this._Registration = undefined;
            this._groupTarif = 1;
            this._kelasPerawatan = 1;
            this._elementForm[0].reset();
            $("#order_date").val(new Date().toISOString().slice(0, 10));
            $(".select2").val(null).trigger("change");
            this._updateAllParameterPricesUI();
            this._calculateCost();
        }

        _selectTipePasienChange(event) {
            this._clearInputs();
            this._patienType = $(event.target).val();
            if (this._patienType === "otc") {
                $(
                    "#nama_pasien, #date_of_birth, #alamat, #no_telp, #diagnosa_awal, input[name='jenis_kelamin']"
                ).prop("disabled", false);
                $("#pilih-pasien-btn").prop("disabled", true);
                $("#mrn_registration_number").val("OTC");
                $("#poly_ruang").val("LABORATORIUM");
            } else {
                $(
                    "#nama_pasien, #date_of_birth, #alamat, #no_telp, #diagnosa_awal, input[name='jenis_kelamin']"
                ).prop("disabled", true);
                $("#pilih-pasien-btn").prop("disabled", false);
            }
        }

        _handleNumberChange() {
            this._calculateCost();
        }

        _orderTypeChange(event) {
            this._CITO = $(event.target).val() === "cito";
            this._calculateCost();
        }

        _updateAllParameterPricesUI() {
            for (const kategori of this._KategoriLaboratorium) {
                for (const parameter of kategori.parameter_laboratorium) {
                    const $hargaElement = $(
                        `#harga_parameter_laboratorium_${parameter.id}`
                    );
                    if (!$hargaElement.length) continue;
                    const tarif = this._findApplicableTarif(parameter);
                    if (tarif) {
                        $hargaElement.text(
                            tarif.total.toLocaleString("id-ID", {
                                style: "currency",
                                currency: "IDR",
                            })
                        );
                    } else {
                        $hargaElement.text("N/A");
                    }
                }
            }
        }

        _calculateCost() {
            this._totalHarga = 0;
            const self = this;
            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    const parameterId = parseInt($(this).val());
                    const parameter = self._KategoriLaboratorium
                        .flatMap((k) => k.parameter_laboratorium)
                        .find((p) => p.id === parameterId);
                    if (parameter) {
                        const tarif = self._findApplicableTarif(parameter);
                        if (!tarif) {
                            console.error(
                                `Tarif tidak ditemukan untuk Parameter ID: ${parameter.id}`
                            );
                            showErrorAlertNoRefresh(
                                `Tarif untuk '${parameter.parameter}' tidak ditemukan! Harap hubungi administrasi.`
                            );
                        } else {
                            const $jumlahInput = $(`#jumlah_${parameter.id}`);
                            const jumlah = parseInt($jumlahInput.val()) || 1;
                            if (jumlah < 1) $jumlahInput.val(1);
                            let price = tarif.total * jumlah;
                            if (self._CITO) {
                                price *= 1.3;
                            }
                            self._totalHarga += price;
                        }
                    }
                }
            );
            this._elementHarga.text(
                this._totalHarga.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                })
            );
        }

        _handleCheckboxChange(event) {
            const $checkbox = $(event.target);
            const $testItem = $checkbox.closest(".test-item");
            const $quantityInput = $testItem.find(".quantity-input");
            const $stepperButtons = $testItem.find(".btn-quantity-stepper");

            if ($checkbox.is(":checked")) {
                $quantityInput.prop("disabled", false);
                $stepperButtons.prop("disabled", false);
            } else {
                $quantityInput.prop("disabled", true).val(1);
                $stepperButtons.prop("disabled", true);
            }
            this._calculateCost();
        }

        _findApplicableTarif(parameter) {
            return this._TarifLaboratorium.find((t) => {
                const isCorrectParameter =
                    t.parameter_laboratorium_id == parameter.id;
                let isCorrectKelasRawat =
                    t.kelas_rawat_id == this._kelasPerawatan;
                let isCorrectGroupPenjamin =
                    t.group_penjamin_id == this._groupTarif;
                if (this._Registration) {
                    if (
                        this._Registration.registration_type === "rawat-jalan"
                    ) {
                        const kelasRajal = this._KelasRawat.find(
                            (k) => k.kelas.toLowerCase() === "rawat jalan"
                        );
                        if (kelasRajal)
                            isCorrectKelasRawat =
                                t.kelas_rawat_id == kelasRajal.id;
                    } else {
                        isCorrectKelasRawat =
                            t.kelas_rawat_id ==
                            (this._Registration.kelas_rawat_id ?? -1);
                    }
                    const penjamin = this._Penjamins.find(
                        (p) => p.id == this._Registration.penjamin_id
                    );
                    if (penjamin)
                        isCorrectGroupPenjamin =
                            t.group_penjamin_id == penjamin.group_penjamin_id;
                }
                return (
                    isCorrectParameter &&
                    isCorrectKelasRawat &&
                    isCorrectGroupPenjamin
                );
            });
        }

        _submit(event) {
            event.preventDefault();
            const formData = new FormData(this._elementForm[0]);
            if (!this._Registration) {
                if (this._patienType !== "otc") {
                    return showErrorAlertNoRefresh(
                        "Silakan pilih pasien terlebih dahulu!"
                    );
                }
                formData.append("is_otc", "1");
            } else {
                formData.append(
                    "registration_id",
                    String(this._Registration.id)
                );
                formData.append(
                    "registration_type",
                    this._Registration.registration_type
                );
            }
            const parameters = [];
            const self = this;
            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    const parameterId = parseInt($(this).val());
                    const parameter = self._KategoriLaboratorium
                        .flatMap((k) => k.parameter_laboratorium)
                        .find((p) => p.id === parameterId);
                    if (parameter) {
                        const tarif = self._findApplicableTarif(parameter);
                        if (tarif) {
                            const jumlah =
                                parseInt($(`#jumlah_${parameter.id}`).val()) ||
                                1;
                            let price = tarif.total;
                            if (self._CITO) {
                                price *= 1.3;
                            }
                            parameters.push({
                                id: parameter.id,
                                qty: jumlah,
                                price: price,
                            });
                        }
                    }
                }
            );
            if (parameters.length === 0) {
                return showErrorAlertNoRefresh(
                    "Pilih minimal satu pemeriksaan laboratorium."
                );
            }
            formData.append("parameters", JSON.stringify(parameters));
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                url: "/api/simrs/laboratorium/order",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
                success: function (response) {
                    if (!response.success) {
                        return showErrorAlertNoRefresh(
                            `Terjadi kesalahan: ${
                                response.errors || "Unknown error"
                            }`
                        );
                    }
                    showSuccessAlert("Order laboratorium berhasil disimpan!");
                    setTimeout(
                        () =>
                            (window.location.href =
                                "/simrs/laboratorium/list-order"),
                        2000
                    ); // Ganti dengan URL redirect yang sesuai
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    showErrorAlertNoRefresh(
                        `Gagal mengirim data: ${errorThrown}`
                    );
                },
            });
        }
    }

    // Inisialisasi kelas untuk menjalankan skrip.
    new OrderLaboratorium();
});
