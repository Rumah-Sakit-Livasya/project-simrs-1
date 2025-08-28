/**
 * CPPT Dokter Form Handler
 * Handles form submission, UI interactions, and AJAX operations for CPPT Doctor form
 * Matches the CPPTController.php requirements exactly
 */

// Swal is already declared in cppt.js, so we don't need to declare it again

class CPPTDokterFormHandler {
    #$Form = $("#cppt-dokter-rajal-form");
    #$SubmitBtn = $("#submit-cppt-dokter");
    #$BsSOAPBtn = $("#bsSOAP");

    constructor() {
        this.#initializeEventListeners();
        this.#initializeSelect2();
        this.#initializeDatepickers();
    }

    /**
     * Initialize all event listeners
     */
    #initializeEventListeners() {
        // Form submission handler
        this.#$Form.on("submit", (e) => this.#handleFormSubmit(e));

        // Button click handlers
        this.#$SubmitBtn.on("click", (e) => this.#handleSubmitClick(e));
        this.#$BsSOAPBtn.on("click", (e) => this.#handleBsSOAPClick(e));

        // Panel toggle handlers - let Bootstrap handle the collapse, just add custom logic
        $("#btnAdd").on("click", (e) => this.#handleBtnAddClick(e));
        $("#tutup").on("click", (e) => this.#handleTutupClick(e));

        // Gudang change handler
        $("#cppt_gudang_id").on("select2:select", (e) =>
            this.#handleGudangChange(e)
        );
    }

    /**
     * Initialize Select2 elements
     */
    #initializeSelect2() {
        $(".select2").select2({
            placeholder: "Pilih Item",
        });

        $("#cppt_barang_id").select2({
            placeholder: "Pilih Obat",
        });

        $("#cppt_gudang_id").select2({
            placeholder: "Pilih Gudang",
        });

        $("#cppt_doctor_id").select2({
            placeholder: "Pilih Dokter",
        });

        $("#konsulkan_ke").select2({
            placeholder: "Pilih Dokter",
        });
    }

    /**
     * Initialize datepickers
     */
    #initializeDatepickers() {
        $(".input-daterange").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom",
        });
    }

    /**
     * Handle form submission
     * @param {JQuery.SubmitEvent} e
     */
    #handleFormSubmit(e) {
        e.preventDefault();
        this.#submitCPPT("final");
    }

    /**
     * Handle submit button click
     * @param {JQuery.ClickEvent} e
     */
    #handleSubmitClick(e) {
        e.preventDefault();
        this.#submitCPPT("final");
    }

    /**
     * Handle BS SOAP button click
     * @param {JQuery.ClickEvent} e
     */
    #handleBsSOAPClick(e) {
        e.preventDefault();
        this.#submitCPPT("final");
    }

    /**
     * Handle btnAdd click - let Bootstrap handle collapse, just ensure proper state
     * @param {JQuery.ClickEvent} e
     */
    #handleBtnAddClick(e) {
        // Don't prevent default - let Bootstrap handle the collapse
        // Add any additional logic here if needed
    }

    /**
     * Handle tutup button click
     * @param {JQuery.ClickEvent} e
     */
    #handleTutupClick(e) {
        // Don't prevent default - let Bootstrap handle the collapse
        // Add any additional logic here if needed
    }

    /**
     * Toggle SOAP panel visibility
     * @param {boolean} show
     */
    #toggleSOAPPanel(show) {
        if (show) {
            $("#add_soap").collapse("show");
        } else {
            $("#add_soap").collapse("hide");
            $(".btnAdd").attr("aria-expanded", "false").addClass("collapsed");
        }
    }

    /**
     * Handle gudang selection change
     * @param {Select2.Event<HTMLElement, Select2.DataParams>} e
     */
    #handleGudangChange(e) {
        const selectedId = e.params.data.id;
        this.#loadBarangOptions(selectedId);
    }

    /**
     * Load barang options based on gudang selection
     * @param {string} gudangId
     */
    async #loadBarangOptions(gudangId) {
        try {
            this.#showLoading(true, "Memuat data obat...");

            const response = await fetch(`/obat/${gudangId}`);
            const data = await response.json();

            const $barangSelect = $("#cppt_barang_id");
            $barangSelect.empty();
            $barangSelect.append(new Option("Pilih Obat", ""));

            data.items.forEach((item) => {
                const option = new Option(
                    `${item.nama} (Stock: ${item.qty})`,
                    item.id
                );
                $(option).addClass("obat").data("item", item);
                $barangSelect.append(option);
            });

            $barangSelect.trigger("change");
        } catch (error) {
            console.error("Error loading barang options:", error);
            this.#showErrorAlert("Gagal memuat data obat");
        } finally {
            this.#showLoading(false);
        }
    }

    /**
     * Submit CPPT form - matches CPPTController.php requirements exactly
     * @param {'draft' | 'final'} actionType
     */
    async #submitCPPT(actionType) {
        try {
            this.#setSubmitButtonState(true);

            // Get registration number from hidden input field
            const registrationNumber = $("#regNum").val() || "default_reg_num";

            // Construct the URL based on the registration type and number
            // Matches the route: Route::post('/cppt/{type}/{registration_number}/store', [CPPTController::class, 'store'])->name('cppt.store');
            const url = `/api/simrs/erm/cppt/rawat-jalan/${registrationNumber}/store`;

            const formData = new FormData(this.#$Form[0]);
            formData.append("action_type", actionType);

            // Ensure signature data is sent in the correct format expected by controller
            // Controller expects: 'signature_data' => 'nullable|array', 'signature_data.pic', 'signature_data.signature_image'
            const signatureImage = formData.get("signature_image");
            const signaturePic = formData.get("pic");

            if (signatureImage) {
                formData.set("signature_data[signature_image]", signatureImage);
                formData.delete("signature_image");
            }
            if (signaturePic) {
                formData.set("signature_data[pic]", signaturePic);
                formData.delete("pic");
            }

            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN":
                        $('meta[name="csrf-token"]').attr("content") || "",
                },
            });

            const result = await response.json();

            if (response.ok) {
                this.#showSuccessAlert(
                    actionType === "draft"
                        ? "Data berhasil disimpan sebagai draft!"
                        : "Data berhasil disimpan!"
                );
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.#handleValidationErrors(result.errors);
            }
        } catch (error) {
            console.error("Error submitting CPPT:", error);
            this.#showErrorAlert("Terjadi kesalahan saat menyimpan data");
        } finally {
            this.#setSubmitButtonState(false);
        }
    }

    /**
     * Set submit button loading state
     * @param {boolean} loading
     */
    #setSubmitButtonState(loading) {
        const button = this.#$SubmitBtn.add(this.#$BsSOAPBtn);
        button.prop("disabled", loading);

        if (loading) {
            button.html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...'
            );
        } else {
            button.html('<i class="mdi mdi-content-save mr-2"></i>Simpan CPPT');
        }
    }

    /**
     * Show loading indicator
     * @param {boolean} show
     * @param {string} message
     */
    #showLoading(show, message = "Loading...") {
        const $loading = $(".loading");
        const $loadingMessage = $(".loading-message");

        $loading.toggle(show);
        if (show && message) {
            $loadingMessage.text(message);
        }
    }

    /**
     * Handle validation errors
     * @param {Object} errors
     */
    #handleValidationErrors(errors) {
        let errorMessage = "Error Validasi:\n";
        Object.values(errors).forEach((errorArray) => {
            errorArray.forEach((error) => {
                errorMessage += `- ${error}\n`;
            });
        });
        alert(errorMessage);
    }

    /**
     * Show success alert
     * @param {string} message
     */
    #showSuccessAlert(message) {
        if (typeof Swal !== "undefined") {
            Swal.fire("Sukses!", message, "success");
        } else {
            alert(message);
        }
    }

    /**
     * Show error alert
     * @param {string} message
     */
    #showErrorAlert(message) {
        if (typeof Swal !== "undefined") {
            Swal.fire("Error", message, "error");
        } else {
            alert(message);
        }
    }
}

// Initialize when DOM is ready
$(document).ready(() => {
    // Initialize form handler
    window.cpptDokterForm = new CPPTDokterFormHandler();

    // Other initialization code that was inline
    $("body").addClass("layout-composed");

    // Toggle panel functionality
    $("#toggle-pasien").on("click", function () {
        const target = $("#js-slide-left");
        const backdrop = $(".slide-backdrop");

        target.toggleClass("hide");
        backdrop.toggleClass("show");
    });

    // Close panel when backdrop is clicked
    $(".slide-backdrop").on("click", function () {
        $("#js-slide-left").removeClass("slide-on-mobile-left-show");
        $(this).removeClass("show");
    });
});
