// Membungkus semua kode di dalam jQuery's document ready function.
jQuery(function ($) {
    /**
     * @class LaboratoriumForm
     * Mengelola logika form order laboratorium di halaman detail registrasi.
     */
    class LaboratoriumForm {
        _KategoriLaboratorium;
        _KelasRawat;
        _GroupPenjaminId;
        _TarifLaboratorium;
        _Registration;
        _totalHarga = 0;
        _elementHarga;
        _elementForm;
        _CITO = false;

        constructor() {
            this._KategoriLaboratorium = window._kategoriLaboratorium;
            this._TarifLaboratorium = window._tarifLaboratorium;
            this._Registration = window._registration;
            this._GroupPenjaminId = window._groupPenjaminId;
            this._KelasRawat = window._kelasRawats;

            this._init();
        }

        /**
         * Inisialisasi awal, menyeleksi elemen DOM dan memasang event listener.
         */
        _init() {
            this._elementHarga = $("#laboratorium-total");
            this._elementForm = $("form#form-laboratorium"); // Ganti ID ke yang lebih simpel jika perlu

            // Event listener untuk form, radio, dan search bar
            this._elementForm.on(
                "change",
                "input[name='order_type']",
                this._orderTypeChange.bind(this)
            );
            this._elementForm.on("submit", this._submit.bind(this));
            $("#searchLaboratorium").on(
                "keyup",
                this._handleSearchBarChange.bind(this)
            );

            // Gunakan event delegation untuk semua interaksi di dalam grid
            const $gridContainer = $("#laboratorium-grid-container");

            // Event untuk checkbox
            $gridContainer.on(
                "change",
                ".parameter_laboratorium_checkbox",
                this._handleCheckboxChange.bind(this)
            );

            // Event untuk input number (jika user mengetik manual)
            $gridContainer.on(
                "input",
                ".parameter_laboratorium_number",
                this._handleNumberChange.bind(this)
            );

            // === START PENAMBAHAN: LOGIKA QUANTITY STEPPER ===
            $gridContainer.on("click", ".btn-quantity-stepper", function (e) {
                const $button = $(e.currentTarget);
                const action = $button.data("action");
                const $input = $button
                    .closest(".quantity-stepper")
                    .find(".quantity-input");

                if ($input.is(":disabled")) {
                    return;
                }

                let currentValue = parseInt($input.val()) || 1;

                if (action === "increment") {
                    currentValue++;
                } else if (action === "decrement" && currentValue > 1) {
                    currentValue--;
                }

                $input.val(currentValue).trigger("input"); // Trigger 'input' untuk memanggil _handleNumberChange
            });
            // === END PENAMBAHAN ===

            this._updateAllParameterPricesUI();
        }

        _handleSearchBarChange(event) {
            const searchQuery = $(event.target).val().toLowerCase().trim();
            if (searchQuery === "") {
                $(".category-column, .test-item").show();
                return;
            }
            $(".category-column").each(function () {
                const $categoryColumn = $(this);
                let categoryHasVisibleItems = false;
                $categoryColumn.find(".test-item").each(function () {
                    const $item = $(this);
                    const itemName = $item
                        .find(".custom-control-label")
                        .text()
                        .toLowerCase();
                    const isVisible = itemName.includes(searchQuery);
                    $item.toggle(isVisible);
                    if (isVisible) categoryHasVisibleItems = true;
                });
                $categoryColumn.toggle(categoryHasVisibleItems);
            });
        }

        /**
         * === START PENYEMPURNAAN: Handler untuk Checkbox ===
         * Sekarang juga mengaktifkan/menonaktifkan tombol stepper.
         */
        _handleCheckboxChange(event) {
            const $checkbox = $(event.currentTarget);
            const $testItem = $checkbox.closest(".test-item");
            const $quantityInput = $testItem.find(".quantity-input");
            const $stepperButtons = $testItem.find(".btn-quantity-stepper");

            if ($checkbox.is(":checked")) {
                $quantityInput.prop("disabled", false);
                $stepperButtons.prop("disabled", false);
            } else {
                $quantityInput.prop("disabled", true).val(1); // Reset nilai ke 1 saat di-uncheck
                $stepperButtons.prop("disabled", true);
            }

            this._calculateCost();
        }
        // === END PENYEMPURNAAN ===

        _handleNumberChange() {
            this._calculateCost();
        }

        _orderTypeChange(event) {
            this._CITO = $(event.target).val() === "cito";
            this._calculateCost();
        }

        _updateAllParameterPricesUI() {
            const self = this;
            this._KategoriLaboratorium.forEach((category) => {
                category.parameter_laboratorium.forEach((parameter) => {
                    const $hargaElement = $(
                        `#harga_parameter_laboratorium_${parameter.id}`
                    );
                    if ($hargaElement.length) {
                        const tarif = self._findApplicableTarif(parameter);
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
                });
            });
        }

        _calculateCost() {
            this._totalHarga = 0;
            const self = this;
            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    const parameterId = parseInt($(this).val());
                    const parameter = self._findParameterById(parameterId);
                    if (parameter) {
                        const tarif = self._findApplicableTarif(parameter);
                        if (tarif) {
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

        _submit(event) {
            event.preventDefault();

            // Gunakan ID form yang lebih spesifik jika ada beberapa form di halaman
            const formElement = this._elementForm[0];
            if (!formElement) {
                return showErrorAlertNoRefresh("Elemen form tidak ditemukan!");
            }
            const formData = new FormData(formElement);
            const self = this;
            const parameters = [];

            // === START PERBAIKAN: TAMBAHKAN REGISTRATION_ID KE FORMDATA ===
            // Pastikan _Registration tidak null atau undefined
            if (self._Registration && self._Registration.id) {
                formData.append("registration_id", self._Registration.id);
            } else {
                // Ini adalah pengaman, seharusnya tidak terjadi untuk pasien terdaftar
                return showErrorAlertNoRefresh(
                    "Data registrasi pasien tidak ditemukan. Mohon muat ulang halaman."
                );
            }
            // === END PERBAIKAN ===

            // === TAMBAHKAN PATIENT_ID KE FORMDATA ===
            // Pastikan _Registration.patient_id ada
            if (self._Registration && self._Registration.patient_id) {
                formData.append("patient_id", self._Registration.patient_id);
            } else {
                // Pengaman jika patient_id tidak ada
                return showErrorAlertNoRefresh(
                    "Data pasien tidak ditemukan. Mohon muat ulang halaman."
                );
            }
            // === END TAMBAHKAN PATIENT_ID ===

            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    const parameterId = parseInt($(this).val());
                    const parameter = self._findParameterById(parameterId);
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

            // Ambil CSRF token dari meta tag (pastikan ada di <head> HTML: <meta name="csrf-token" content="...">)
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");
            if (!csrfToken) {
                return showErrorAlertNoRefresh(
                    "CSRF token tidak ditemukan. Silakan muat ulang halaman."
                );
            }
            // Tambahkan CSRF token ke FormData
            formData.append("_token", csrfToken);

            $.ajax({
                url: "/api/simrs/laboratorium/order",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (!response.success) {
                        // Tampilkan pesan error yang lebih spesifik dari server jika ada
                        let errorMsg = "Unknown error";
                        if (response.errors) {
                            // Ambil pesan error pertama dari objek errors
                            errorMsg = Object.values(response.errors)[0][0];
                        } else if (response.message) {
                            errorMsg = response.message;
                        }
                        return showErrorAlertNoRefresh(
                            `Terjadi kesalahan: ${errorMsg}`
                        );
                    }
                    showSuccessAlert("Order laboratorium berhasil disimpan!");
                    setTimeout(() => window.location.reload(), 2000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(
                        "AJAX Error:",
                        jqXHR.responseJSON || textStatus
                    );
                    // Tangani error CSRF token mismatch secara spesifik
                    if (jqXHR.status === 419) {
                        showErrorAlertNoRefresh(
                            "Sesi Anda telah berakhir (CSRF token mismatch). Silakan muat ulang halaman dan coba lagi."
                        );
                    } else if (
                        jqXHR.status === 422 &&
                        jqXHR.responseJSON.errors
                    ) {
                        const firstError = Object.values(
                            jqXHR.responseJSON.errors
                        )[0][0];
                        showErrorAlertNoRefresh(
                            `Kesalahan Validasi: ${firstError}`
                        );
                    } else {
                        showErrorAlertNoRefresh(
                            `Gagal mengirim data: ${errorThrown}`
                        );
                    }
                },
            });
        }

        _findParameterById(parameterId) {
            for (const category of this._KategoriLaboratorium) {
                const found = category.parameter_laboratorium.find(
                    (p) => p.id === parameterId
                );
                if (found) return found;
            }
            return undefined;
        }

        _findApplicableTarif(parameter) {
            const kelasRajal = this._KelasRawat.find(
                (k) => k.kelas.toLowerCase() === "rawat jalan"
            );
            if (!kelasRajal) {
                showErrorAlertNoRefresh(
                    "Konfigurasi 'Kelas Rawat Jalan' tidak ditemukan!"
                );
                return null;
            }
            return this._TarifLaboratorium.find((t) => {
                const isCorrectParameter =
                    t.parameter_laboratorium_id == parameter.id;
                const isCorrectGroupPenjamin =
                    t.group_penjamin_id == (this._GroupPenjaminId ?? -1);
                let isCorrectKelasRawat;
                if (this._Registration.registration_type === "rawat-jalan") {
                    isCorrectKelasRawat = t.kelas_rawat_id == kelasRajal.id;
                } else {
                    isCorrectKelasRawat =
                        t.kelas_rawat_id ==
                        (this._Registration.kelas_rawat_id ?? -1);
                }
                return (
                    isCorrectParameter &&
                    isCorrectKelasRawat &&
                    isCorrectGroupPenjamin
                );
            });
        }
    }

    // Inisialisasi kelas untuk menjalankan skrip.
    new LaboratoriumForm();
});
