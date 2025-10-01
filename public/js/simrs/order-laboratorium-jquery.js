// Penyesuaian struktur dan pola dengan @order-radiologi.js
jQuery(function ($) {
    const OrderLaboratorium = {
        _KategoriLaboratorium: window._kategoriLaboratorium || [],
        _TarifLaboratorium: window._tarifLaboratorium || [],
        _Penjamins: window._penjamins || [],
        _KelasRawat: window._kelasRawats || [],
        _Registration: null,
        _totalHarga: 0,
        _groupTarif: 1,
        _kelasPerawatan: 1,
        _patienType: "rajal",
        _CITO: false,
        _elementHarga: null,
        _elementForm: null,
        _pilihPasienButton: null,

        init: function () {
            this._elementHarga = $("#laboratorium-total");
            this._elementForm = $("form[name='form-laboratorium']");
            this._pilihPasienButton = $("#pilih-pasien-btn");

            $("#searchLaboratorium").on(
                "keyup",
                this.handleSearchBarChange.bind(this)
            );
            $("input[type='radio'][name='order_type']").on(
                "change",
                this.handleOrderTypeChange.bind(this)
            );
            $("#tipe_pasien").on(
                "change",
                this.handleTipePasienChange.bind(this)
            );
            $("#pilih-pasien-btn").on(
                "click",
                this.handlePilihPasienButtonClick.bind(this)
            );
            $("button.submit-btn").on("click", this.submit.bind(this));

            window.addEventListener("message", (event) => {
                if (
                    event.data &&
                    event.data.type === "pasien_selected" &&
                    event.data.data
                ) {
                    this.changeRegistration(event.data.data);
                }
            });

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

            $("#laboratorium-grid-container").on(
                "change",
                ".parameter_laboratorium_checkbox",
                this.handleCheckboxChange.bind(this)
            );
            $("#laboratorium-grid-container").on(
                "input",
                ".parameter_laboratorium_number",
                this.handleNumberChange.bind(this)
            );

            this.updateAllParameterPricesUI();
        },

        handleSearchBarChange: function (event) {
            const searchQuery = $(event.target).val().toLowerCase().trim();
            if (searchQuery === "") {
                $(".category-column").show();
                $(".test-item").show();
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
                    if (itemName.includes(searchQuery)) {
                        $item.show();
                        categoryHasVisibleItems = true;
                    } else {
                        $item.hide();
                    }
                });
                $categoryColumn.toggle(categoryHasVisibleItems);
            });
        },

        handlePilihPasienButtonClick: function (event) {
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
        },

        changeRegistration: function (registration) {
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
            this.updateAllParameterPricesUI();
            this.calculateCost();
        },

        clearInputs: function () {
            this._Registration = null;
            this._groupTarif = 1;
            this._kelasPerawatan = 1;
            this._elementForm[0].reset();
            $("#order_date").val(new Date().toISOString().slice(0, 10));
            $(".select2").val(null).trigger("change");
            this.updateAllParameterPricesUI();
            this.calculateCost();
        },

        handleTipePasienChange: function (event) {
            // this.clearInputs();
            this._patienType = $(event.target).val();
            console.log(this._patienType);

            if (this._patienType === "otc") {
                $("#nama_pasien").prop("readonly", false);
                $("#date_of_birth").prop("readonly", false);
                $("#alamat").prop("disabled", false);
                $("#no_telp").prop("disabled", false);
                $("#diagnosa_awal").prop("disabled", false);
                $("input[name='jenis_kelamin']").prop("disabled", false);
                $("#mrn_registration_number").val("OTC");
                $("input[name='medical_record_number']").val("");
                $("input[name='registration_number']").val("");
                $("#poly_ruang").val("LABORATORIUM");
                $("#pilih-pasien-btn").prop("disabled", true);
            } else {
                $("#nama_pasien").prop("readonly", true);
                $("#date_of_birth").prop("readonly", true);
                $("#alamat").prop("disabled", true);
                $("#no_telp").prop("disabled", true);
                $("#diagnosa_awal").prop("disabled", true);
                $("input[name='jenis_kelamin']").prop("disabled", true);
                $("#pilih-pasien-btn").prop("disabled", false);
            }
        },

        handleNumberChange: function () {
            this.calculateCost();
        },

        handleOrderTypeChange: function (event) {
            this._CITO = $(event.target).val() === "cito";
            this.calculateCost();
        },

        updateAllParameterPricesUI: function () {
            for (const kategori of this._KategoriLaboratorium) {
                for (const parameter of kategori.parameter_laboratorium) {
                    const $hargaElement = $(
                        `#harga_parameter_laboratorium_${parameter.id}`
                    );
                    if (!$hargaElement.length) continue;
                    const tarif = this.findApplicableTarif(parameter);
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
        },

        calculateCost: function () {
            this._totalHarga = 0;
            const self = this;
            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    const parameterId = parseInt($(this).val());
                    const parameter = self._KategoriLaboratorium
                        .flatMap((k) => k.parameter_laboratorium)
                        .find((p) => p.id === parameterId);
                    if (parameter) {
                        const tarif = self.findApplicableTarif(parameter);
                        if (!tarif) {
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
        },

        handleCheckboxChange: function (event) {
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
            this.calculateCost();
        },

        findApplicableTarif: function (parameter) {
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
        },

        submit: function (event) {
            // Proses submit hanya jika tombol diklik (bukan submit form default)
            if (event) {
                event.preventDefault();
            }

            // Disable tombol submit agar tidak bisa diklik berkali-kali
            const $submitBtn = $(event && event.target).closest(
                "button.submit-btn"
            );
            $submitBtn.prop("disabled", true).addClass("btn-loading");

            const formData = new FormData(this._elementForm[0]);
            if (!this._Registration) {
                if (this._patienType !== "otc") {
                    showErrorAlertNoRefresh(
                        "Silakan pilih pasien terlebih dahulu!"
                    );
                    $submitBtn
                        .prop("disabled", false)
                        .removeClass("btn-loading");
                    return;
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
                        const tarif = self.findApplicableTarif(parameter);
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
                showErrorAlertNoRefresh(
                    "Pilih minimal satu pemeriksaan laboratorium."
                );
                $submitBtn.prop("disabled", false).removeClass("btn-loading");
                return;
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
                        showErrorAlertNoRefresh(
                            `Terjadi kesalahan: ${
                                response.errors || "Unknown error"
                            }`
                        );
                        $submitBtn
                            .prop("disabled", false)
                            .removeClass("btn-loading");
                        return;
                    }
                    showSuccessAlert("Order laboratorium berhasil disimpan!");
                    setTimeout(() => {
                        window.location.href = "/simrs/laboratorium/list-order";
                    }, 2000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    showErrorAlertNoRefresh(
                        `Gagal mengirim data: ${errorThrown}`
                    );
                    $submitBtn
                        .prop("disabled", false)
                        .removeClass("btn-loading");
                },
            });
        },
    };

    OrderLaboratorium.init();
});
