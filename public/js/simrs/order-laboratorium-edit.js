// Penyesuaian struktur dan pola dengan @order-laboratorium-jquery.js
$(function () {
    var EditOrderLaboratorium = {
        _KategoriLaboratorium: window._kategoriLaboratorium || [],
        _TarifLaboratorium: window._tarifLaboratorium || [],
        _Penjamins: window._penjamins || [],
        _KelasRawat: window._kelasRawats || [],
        _Registration: window._registration || null,
        _totalHarga: 0,
        _groupTarif: window._groupTarif || 1,
        _kelasPerawatan: window._kelasPerawatan || 1,
        _patienType: window._patienType || "rajal",
        _CITO: window._cito || false,
        _elementHarga: null,
        _elementForm: null,

        init: function () {
            var self = this;
            self._elementHarga = $("#laboratorium-total");
            self._elementForm = $("form[name='form-laboratorium-edit']");

            $("#searchLaboratorium").on("keyup", function (e) {
                self.handleSearchBarChange(e);
            });
            $("input[type='radio'][name='order_type']").on(
                "change",
                function (e) {
                    self.handleOrderTypeChange(e);
                }
            );
            $("#tipe_pasien").on("change", function (e) {
                self.handleTipePasienChange(e);
            });

            $("#laboratorium-grid-container").on(
                "click",
                ".btn-quantity-stepper",
                function () {
                    var $button = $(this);
                    var action = $button.data("action");
                    var $input = $button
                        .closest(".quantity-stepper")
                        .find(".quantity-input");
                    if ($input.is(":disabled")) return;
                    var currentValue = parseInt($input.val());
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
                function (e) {
                    self.handleCheckboxChange(e);
                }
            );
            $("#laboratorium-grid-container").on(
                "input",
                ".parameter_laboratorium_number",
                function () {
                    self.handleNumberChange();
                }
            );

            $("button.submit-btn").on("click", function (e) {
                self.submit(e);
            });

            self.updateAllParameterPricesUI();
            self.calculateCost();
        },

        handleSearchBarChange: function (event) {
            var searchQuery = $(event.target).val().toLowerCase().trim();
            if (searchQuery === "") {
                $(".category-column").show();
                $(".test-item").show();
                return;
            }
            $(".category-column").each(function () {
                var $categoryColumn = $(this);
                var categoryHasVisibleItems = false;
                $categoryColumn.find(".test-item").each(function () {
                    var $item = $(this);
                    var itemName = $item
                        .find(".custom-control-label")
                        .text()
                        .toLowerCase();
                    if (itemName.indexOf(searchQuery) !== -1) {
                        $item.show();
                        categoryHasVisibleItems = true;
                    } else {
                        $item.hide();
                    }
                });
                $categoryColumn.toggle(categoryHasVisibleItems);
            });
        },

        handleTipePasienChange: function (event) {
            this._patienType = $(event.target).val();
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
            var self = this;
            $.each(self._KategoriLaboratorium, function (_, kategori) {
                $.each(
                    kategori.parameter_laboratorium,
                    function (_, parameter) {
                        var $hargaElement = $(
                            "#harga_parameter_laboratorium_" + parameter.id
                        );
                        if (!$hargaElement.length) return;
                        var tarif = self.findApplicableTarif(parameter);
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
                );
            });
        },

        calculateCost: function () {
            var self = this;
            self._totalHarga = 0;
            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    var parameterId = parseInt($(this).val());
                    var parameter = null;
                    $.each(self._KategoriLaboratorium, function (_, kategori) {
                        var found = $.grep(
                            kategori.parameter_laboratorium,
                            function (p) {
                                return p.id === parameterId;
                            }
                        );
                        if (found.length) {
                            parameter = found[0];
                            return false;
                        }
                    });
                    if (parameter) {
                        var tarif = self.findApplicableTarif(parameter);
                        if (!tarif) {
                            showErrorAlertNoRefresh(
                                "Tarif untuk '" +
                                    parameter.parameter +
                                    "' tidak ditemukan! Harap hubungi administrasi."
                            );
                        } else {
                            var $jumlahInput = $("#jumlah_" + parameter.id);
                            var jumlah = parseInt($jumlahInput.val()) || 1;
                            if (jumlah < 1) $jumlahInput.val(1);
                            var price = tarif.total * jumlah;
                            if (self._CITO) {
                                price *= 1.3;
                            }
                            self._totalHarga += price;
                        }
                    }
                }
            );
            self._elementHarga.text(
                self._totalHarga.toLocaleString("id-ID", {
                    style: "currency",
                    currency: "IDR",
                })
            );
        },

        handleCheckboxChange: function (event) {
            var $checkbox = $(event.target);
            var $testItem = $checkbox.closest(".test-item");
            var $quantityInput = $testItem.find(".quantity-input");
            var $stepperButtons = $testItem.find(".btn-quantity-stepper");

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
            var self = this;
            var result = null;
            $.each(self._TarifLaboratorium, function (_, t) {
                var isCorrectParameter =
                    t.parameter_laboratorium_id == parameter.id;
                var isCorrectKelasRawat =
                    t.kelas_rawat_id == self._kelasPerawatan;
                var isCorrectGroupPenjamin =
                    t.group_penjamin_id == self._groupTarif;
                if (self._Registration) {
                    if (
                        self._Registration.registration_type === "rawat-jalan"
                    ) {
                        var kelasRajal = null;
                        $.each(self._KelasRawat, function (_, k) {
                            if (
                                k.kelas &&
                                k.kelas.toLowerCase() === "rawat jalan"
                            ) {
                                kelasRajal = k;
                                return false;
                            }
                        });
                        if (kelasRajal) {
                            isCorrectKelasRawat =
                                t.kelas_rawat_id == kelasRajal.id;
                        }
                    } else {
                        isCorrectKelasRawat =
                            t.kelas_rawat_id ==
                            (self._Registration.kelas_rawat_id || -1);
                    }
                    var penjamin = null;
                    $.each(self._Penjamins, function (_, p) {
                        if (p.id == self._Registration.penjamin_id) {
                            penjamin = p;
                            return false;
                        }
                    });
                    if (penjamin) {
                        isCorrectGroupPenjamin =
                            t.group_penjamin_id == penjamin.group_penjamin_id;
                    }
                }
                if (
                    isCorrectParameter &&
                    isCorrectKelasRawat &&
                    isCorrectGroupPenjamin
                ) {
                    result = t;
                    return false;
                }
            });
            return result;
        },

        // --- MODIFIED submit: sub_parameter juga dikirim jika ada ---
        submit: function (event) {
            if (event) {
                event.preventDefault();
            }

            var self = this;
            var $submitBtn = $(event && event.target).closest(
                "button.submit-btn"
            );
            $submitBtn.prop("disabled", true).addClass("btn-loading");

            var formData = new FormData(self._elementForm[0]);
            if (!self._Registration) {
                if (self._patienType !== "otc") {
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
                    String(self._Registration.id)
                );
                formData.append(
                    "registration_type",
                    self._Registration.registration_type
                );
            }

            var parameters = [];
            $("input.parameter_laboratorium_checkbox:checked").each(
                function () {
                    var parameterId = parseInt($(this).val());
                    var parameter = null;
                    $.each(self._KategoriLaboratorium, function (_, kategori) {
                        var found = $.grep(
                            kategori.parameter_laboratorium,
                            function (p) {
                                return p.id === parameterId;
                            }
                        );
                        if (found.length) {
                            parameter = found[0];
                            return false;
                        }
                    });
                    if (parameter) {
                        var tarif = self.findApplicableTarif(parameter);
                        if (tarif) {
                            var jumlah =
                                parseInt($("#jumlah_" + parameter.id).val()) ||
                                1;
                            var price = tarif.total;
                            if (self._CITO) {
                                price *= 1.3;
                            }

                            // --- Handle sub_parameter jika ada ---
                            var subParameterArr = [];
                            if (parameter.sub_parameter) {
                                try {
                                    // sub_parameter di DB adalah string JSON array, misal: "[\"11\",\"12\",\"13\"]"
                                    var parsed = JSON.parse(
                                        parameter.sub_parameter
                                    );
                                    if (Array.isArray(parsed)) {
                                        // Convert all to int
                                        subParameterArr = parsed.map(function (
                                            x
                                        ) {
                                            var n = parseInt(x);
                                            return isNaN(n) ? x : n;
                                        });
                                    }
                                } catch (e) {
                                    // ignore parse error, treat as no sub_parameter
                                }
                            }

                            var paramObj = {
                                id: parameter.id,
                                qty: jumlah,
                                price: price,
                            };
                            if (subParameterArr.length > 0) {
                                paramObj.sub_parameter = subParameterArr;
                            }
                            parameters.push(paramObj);
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
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                url: "/api/simrs/laboratorium/order/" + window._orderId,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
                success: function (response) {
                    if (!response.success) {
                        showErrorAlertNoRefresh(
                            "Terjadi kesalahan: " +
                                (response.errors || "Unknown error")
                        );
                        $submitBtn
                            .prop("disabled", false)
                            .removeClass("btn-loading");
                        return;
                    }
                    showSuccessAlert("Order laboratorium berhasil diperbarui!");
                    setTimeout(function () {
                        window.location.href = "/simrs/laboratorium/list-order";
                    }, 2000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    showErrorAlertNoRefresh(
                        "Gagal mengirim data: " + errorThrown
                    );
                    $submitBtn
                        .prop("disabled", false)
                        .removeClass("btn-loading");
                },
            });
        },
    };

    EditOrderLaboratorium.init();

    // --- Highlight hasil abnormal/normal pada edit ---
    function checkHasil(input) {
        var $input = $(input);
        var $parentTd = $input.closest("td");
        $input.removeClass("hasil-abnormal hasil-normal hasil-abnormal-radio");
        var tipeHasil = $parentTd.data("tipe-hasil");
        var nilaiInput;
        if ($input.is(":radio")) {
            nilaiInput = $(
                "input[name='" + $input.attr("name") + "']:checked"
            ).val();
        } else {
            nilaiInput = $input.val();
        }
        if (typeof nilaiInput === "undefined" || $.trim(nilaiInput) === "")
            return;
        nilaiInput = $.trim(nilaiInput);
        var isAbnormal = false;
        if (tipeHasil === "Angka") {
            var min = parseFloat($parentTd.data("min"));
            var max = parseFloat($parentTd.data("max"));
            var nilaiFloat = parseFloat(nilaiInput);
            if (!isNaN(min) && !isNaN(max) && !isNaN(nilaiFloat)) {
                if (nilaiFloat < min || nilaiFloat > max) isAbnormal = true;
            }
        } else {
            var nilaiNormal = ($parentTd.data("nilai-normal") || "")
                .toString()
                .toLowerCase();
            if (nilaiNormal && nilaiNormal.indexOf("/") === -1) {
                if (nilaiInput.toLowerCase() !== nilaiNormal) isAbnormal = true;
            }
        }
        var $targetElement = $input.is(":radio")
            ? $("input[name='" + $input.attr("name") + "']:checked").next(
                  ".custom-control-label"
              )
            : $input;
        if (isAbnormal) {
            if ($input.is(":radio"))
                $targetElement.addClass("hasil-abnormal-radio");
            else $targetElement.addClass("hasil-abnormal");
        } else {
            $targetElement.addClass("hasil-normal");
        }
    }

    $(".input-hasil").on("keyup change", function () {
        checkHasil(this);
    });
    $(".input-hasil").each(function () {
        if ($(this).is(":radio") && $(this).is(":checked")) checkHasil(this);
        else if (!$(this).is(":radio")) checkHasil(this);
    });
    $(".btn-autofill").on("click", function () {
        var $parentTd = $(this).closest("td");
        var $inputHasil = $parentTd.find(".input-hasil");
        var nilaiNormal = $parentTd.data("nilai-normal");
        if (nilaiNormal) {
            $inputHasil.val(nilaiNormal);
            checkHasil($inputHasil[0]);
        }
    });
});
