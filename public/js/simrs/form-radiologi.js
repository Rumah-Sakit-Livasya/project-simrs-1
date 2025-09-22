/**
 * Script for Embedded Radiology Order Form (Class-based approach - Submit Fix)
 * Author: Your Name
 * Version: 7.1.0
 * Dependencies: jQuery, SweetAlert2, Bootstrap Datepicker, Select2
 */

jQuery(function ($) {
    /**
     * @class RadiologiForm
     * Mengelola logika form order radiologi di halaman detail registrasi.
     */
    class RadiologiForm {
        _TarifRadiologi;
        _Registration;
        _GroupPenjaminId;
        _totalHarga = 0;
        _elementHarga;
        _elementForm;
        _CITO = false;

        constructor() {
            this._TarifRadiologi = window._tarifRadiologi || [];
            this._Registration = window._registration || {};
            this._GroupPenjaminId = window._groupPenjaminId || 1;
            this._init();
        }

        /**
         * Inisialisasi awal, menyeleksi elemen DOM dan memasang event listener.
         */
        _init() {
            this._elementHarga = $("#radiologi-total");
            this._elementForm = $("#form-radiologi");

            // Inisialisasi plugins
            this._initializePlugins();

            // === AWAL PERBAIKAN 1: EVENT LISTENER UNTUK TOMBOL SUBMIT ===
            // Ganti event listener dari 'submit' form menjadi 'click' pada tombol.
            $("#radiologi-submit").on("click", this._submit.bind(this));
            // === AKHIR PERBAIKAN 1 ===

            this._elementForm.on(
                "change",
                'input[name="order_type"]',
                this._orderTypeChange.bind(this)
            );
            $("#searchRadiology").on(
                "keyup",
                this._handleSearchBarChange.bind(this)
            );

            const $gridContainer = $("#radiology-grid-container");
            $gridContainer.on(
                "change",
                ".parameter_radiologi_checkbox",
                this._handleCheckboxChange.bind(this)
            );
            $gridContainer.on(
                "input change",
                ".parameter_radiologi_number",
                this._handleNumberChange.bind(this)
            );
            $gridContainer.on("click", ".btn-quantity-stepper", (e) => {
                const $button = $(e.currentTarget);
                const action = $button.data("action");
                const $input = $button.siblings(".quantity-input");
                if ($input.is(":disabled")) return;
                let currentValue = parseInt($input.val()) || 1;
                if (action === "increment") {
                    currentValue++;
                } else if (action === "decrement" && currentValue > 1) {
                    currentValue--;
                }
                $input.val(currentValue);
                this._calculateCost();
            });

            // Inisialisasi UI
            this._updateAllParameterPricesUI();
            this._disableAllQuantityInputs();
            this._calculateCost();
        }

        _initializePlugins() {
            if (
                $("#doctor_id").length &&
                !$("#doctor_id").hasClass("select2-hidden-accessible")
            ) {
                $("#doctor_id").select2({
                    placeholder: "Pilih Dokter Radiologi",
                    allowClear: true,
                    dropdownParent: $("#doctor_id").parent(),
                });
            }
            if ($(".datepicker").length) {
                $(".datepicker").datepicker({
                    todayHighlight: true,
                    orientation: "bottom left",
                    format: "dd-mm-yyyy",
                    autoclose: true,
                });
            }
        }

        _disableAllQuantityInputs() {
            $(".parameter_radiologi_number").prop("disabled", true);
            $(".btn-quantity-stepper").prop("disabled", true);
        }

        _handleSearchBarChange(event) {
            const searchQuery = $(event.target).val().toLowerCase().trim();
            $(".category-column").each(function () {
                const $categoryCard = $(this);
                let categoryVisible = false;
                $categoryCard.find(".parameter_radiologi").each(function () {
                    const $item = $(this);
                    const itemName = $item
                        .find(".custom-control-label")
                        .text()
                        .toLowerCase();
                    if (itemName.includes(searchQuery)) {
                        $item.show();
                        categoryVisible = true;
                    } else {
                        $item.hide();
                    }
                });
                $categoryCard.toggle(categoryVisible);
            });
        }

        _handleCheckboxChange(event) {
            const $checkbox = $(event.currentTarget);
            const $testItem = $checkbox.closest(".test-item");
            const $quantityInput = $testItem.find(".quantity-input");
            const $stepperButtons = $testItem.find(".btn-quantity-stepper");

            if ($checkbox.is(":checked")) {
                $quantityInput.prop("disabled", false);
                $stepperButtons.prop("disabled", false);
                if (
                    parseInt($quantityInput.val()) < 1 ||
                    $quantityInput.val() === ""
                ) {
                    $quantityInput.val(1);
                }
            } else {
                $quantityInput.prop("disabled", true).val(1);
                $stepperButtons.prop("disabled", true);
            }
            this._calculateCost();
        }

        _handleNumberChange(event) {
            const $input = $(event.target);
            let value = parseInt($input.val());
            if (isNaN(value) || value < 1) {
                $input.val(1);
            }
            this._calculateCost();
        }

        _orderTypeChange(event) {
            this._CITO = $(event.target).val() === "cito";
            this._calculateCost();
        }

        _updateAllParameterPricesUI() {
            const self = this;
            $(".parameter_radiologi").each(function () {
                const $item = $(this);
                const $checkbox = $item.find(".parameter_radiologi_checkbox");
                const parameterId = parseInt($checkbox.val());
                const $priceElement = $(
                    `#harga_parameter_radiologi_${parameterId}`
                );
                if (!parameterId || isNaN(parameterId)) return;
                const tarif = self._findApplicableTarif(parameterId);
                if (tarif) {
                    const formattedPrice = parseInt(tarif.total).toLocaleString(
                        "id-ID",
                        {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        }
                    );
                    $priceElement
                        .text(`(${formattedPrice})`)
                        .removeClass("text-danger");
                } else {
                    $priceElement.text("(N/A)").addClass("text-danger");
                }
            });
        }

        _calculateCost() {
            this._totalHarga = 0;
            let itemCount = 0;
            const self = this;
            $(".parameter_radiologi_checkbox:checked").each(function () {
                const parameterId = parseInt($(this).val());
                const $quantityInput = $(`#jumlah_${parameterId}`);
                const kuantitas = parseInt($quantityInput.val()) || 1;
                const tarif = self._findApplicableTarif(parameterId);
                if (tarif) {
                    let hargaSatuan = parseInt(tarif.total);
                    if (self._CITO) {
                        hargaSatuan = Math.round(hargaSatuan * 1.3);
                    }
                    self._totalHarga += hargaSatuan * kuantitas;
                    itemCount++;
                }
            });
            this._elementHarga.text(
                this._totalHarga.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                })
            );
            $("#radiologi-submit").prop("disabled", itemCount === 0);
        }

        _submit(event) {
            event.preventDefault();
            if (!$("#doctor_id").val()) {
                return this._showErrorAlert("Silakan pilih Dokter Radiologi.");
            }
            if (!$("#diagnosa_awal").val().trim()) {
                return this._showErrorAlert("Diagnosa klinis wajib diisi.");
            }
            const $checkedItems = $(".parameter_radiologi_checkbox:checked");
            if ($checkedItems.length === 0) {
                return this._showErrorAlert(
                    "Pilih minimal satu pemeriksaan radiologi."
                );
            }
            const formElement = this._elementForm[0];
            const formData = new FormData(formElement);
            const self = this;
            const parameters = [];

            if (this._Registration && this._Registration.id) {
                formData.append("registration_id", this._Registration.id);
            } else {
                return this._showErrorAlert(
                    "Data registrasi pasien tidak ditemukan."
                );
            }
            if (this._Registration && this._Registration.patient_id) {
                formData.append("patient_id", this._Registration.patient_id);
            } else {
                return this._showErrorAlert("Data pasien tidak ditemukan.");
            }

            $checkedItems.each(function () {
                const parameterId = parseInt($(this).val());
                const kuantitas =
                    parseInt($(`#jumlah_${parameterId}`).val()) || 1;
                const tarif = self._findApplicableTarif(parameterId);
                if (tarif) {
                    let hargaSatuan = parseInt(tarif.total);
                    if (self._CITO) {
                        hargaSatuan = Math.round(hargaSatuan * 1.3);
                    }
                    parameters.push({
                        id: parameterId,
                        qty: kuantitas,
                        price: hargaSatuan,
                    });
                }
            });

            formData.append("parameters", JSON.stringify(parameters));
            formData.append("total_biaya", this._totalHarga);

            Swal.fire({
                title: "Konfirmasi Order",
                html: `Anda akan menyimpan order radiologi dengan total biaya <b>${this._elementHarga.text()}</b>.<br>Apakah Anda yakin?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    this._sendData(formData);
                }
            });
        }

        _sendData(formData) {
            const $submitBtn = $("#radiologi-submit");
            const self = this; // === AWAL PERBAIKAN 2: Simpan konteks `this` ===

            $.ajax({
                url: "/api/simrs/order-radiologi",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                beforeSend: function () {
                    $submitBtn
                        .prop("disabled", true)
                        .html(
                            '<span class="spinner-border spinner-border-sm"></span> Menyimpan...'
                        );
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Sukses",
                            text: "Order radiologi berhasil disimpan!",
                            icon: "success",
                            confirmButtonText: "OK",
                        });
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        const errorMessages = response.errors
                            ? Object.values(response.errors).join("<br>")
                            : response.message || "Terjadi kesalahan";
                        self._showErrorAlert(errorMessages); // Gunakan `self`
                    }
                },
                error: function (jqXHR) {
                    console.error("AJAX Error:", jqXHR.responseText);
                    if (jqXHR.status === 419) {
                        self._showErrorAlert(
                            "Sesi Anda telah berakhir. Silakan muat ulang halaman."
                        ); // Gunakan `self`
                    } else if (
                        jqXHR.status === 422 &&
                        jqXHR.responseJSON.errors
                    ) {
                        const firstError = Object.values(
                            jqXHR.responseJSON.errors
                        )[0][0];
                        self._showErrorAlert(
                            `Kesalahan Validasi: ${firstError}`
                        ); // Gunakan `self`
                    } else {
                        self._showErrorAlert(
                            "Terjadi kesalahan teknis. Silakan hubungi administrator."
                        ); // Gunakan `self`
                    }
                },
                complete: function () {
                    $submitBtn
                        .prop("disabled", false)
                        .html('<i class="fal fa-save mr-1"></i> Simpan Order');
                },
            });
            // === AKHIR PERBAIKAN 2 ===
        }

        _findApplicableTarif(parameterId) {
            const kelasRawatId =
                this._Registration.registration_type === "rawat-inap"
                    ? this._Registration.kelas_rawat_id || 1
                    : 1;
            return this._TarifRadiologi.find((t) => {
                const isCorrectParameter =
                    parseInt(t.parameter_radiologi_id) === parameterId;
                const isCorrectGroupPenjamin =
                    parseInt(t.group_penjamin_id) === this._GroupPenjaminId;
                const isCorrectKelasRawat =
                    parseInt(t.kelas_rawat_id) === kelasRawatId;
                return (
                    isCorrectParameter &&
                    isCorrectGroupPenjamin &&
                    isCorrectKelasRawat
                );
            });
        }

        _showErrorAlert(message) {
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Error",
                    html: message,
                    icon: "error",
                    confirmButtonText: "OK",
                });
            } else {
                alert(message);
            }
        }
    }
    new RadiologiForm();
});
