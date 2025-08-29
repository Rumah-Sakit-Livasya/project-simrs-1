// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

/**
 * Handles all user-facing events, from clicks to selections,
 * and delegates actions to the appropriate managers or UI handlers.
 */
class ResepEventHandler {
    /** @type {ResepStateManager} */
    #stateManager;
    /** @type {UIFormUpdater} */
    #uiFormHandler;
    /** @type {UITableUpdater} */
    #uiTableHandler;
    /** @type {UIMiscHandler} */
    #uiMiscHandler;

    /** @type {PatientType} */
    #patientType = "rajal";
    /** @type {number | undefined} */
    #ObatSelectToRacikanKey = undefined;
    /** @type {number | undefined} */
    #BatchSelectToObatKey = undefined;

    /**
     * @param {ResepStateManager} stateManager
     * @param {UIFormUpdater} uiFormHandler
     * @param {UITableUpdater} uiTableHandler
     * @param {UIMiscHandler} uiMiscHandler
     */
    constructor(stateManager, uiFormHandler, uiTableHandler, uiMiscHandler) {
        this.#stateManager = stateManager;
        this.#uiFormHandler = uiFormHandler;
        this.#uiTableHandler = uiTableHandler;
        this.#uiMiscHandler = uiMiscHandler;
    }

    /** Initializes all event listeners for the application. */
    initializeEventListeners() {
        $("#obat-select").select2({ matcher: this.#uiMiscHandler.obatMatcher });
        $("#gudang_id").on('select2:select', this.#handleGudangSelect.bind(this));
        $("#obat-select").on('select2:select', this.#handleObatSelect.bind(this));
        $("#form-resep").on("submit", this.#handleSubmitForm.bind(this));
        $(document).on("change input keyup", "#batch", this.#handleBatchSearchChange.bind(this));
        $(document).on("change input keyup", "input[type='number']", Utils.enforceNumberLimit);
        $(document).on("change input keyup", "input[type='number']", this.#uiTableHandler.refreshTotal.bind(this.#uiTableHandler));
        $(document).on("change", "#tipe_pasien", this.#handleTipePasienChange.bind(this));
        $(document).on("change", "#embalase_racikan, #embalase_item, #embalase_tidak", this.#handleEmbalaseChange.bind(this));
        $(document).on('click', '.signa-button', this.#handleSignaButtonClick.bind(this));

        this.#addEventListeners("#telaah-resep-btn", "click", this.#handleTelaahResepButtonClick);
        this.#addEventListeners("#pilih-pasien-btn", "click", this.#handlePilihPasienButtonClick);
        this.#addEventListeners("#pilih-dokter-btn", "click", this.#handlePilihDokterButtonClick);
        this.#addEventListeners("#resep-elektronik-btn", "click", this.#handleResepElektronikButtonClick);
        this.#addEventListeners("#resep-harian-btn", "click", this.#handleResepHarianButtonClick);
        this.#addEventListeners("#tambah-racikan-btn", "click", this.#handleTambahRacikanButtonClick);
        this.#addEventListeners("#add-to-racikan", "click", this.#handleCancelAddToRacikanClick);


        window.addEventListener("message", this.#handleWindowMessage.bind(this));

        this.#uiFormHandler.showLoading(false);
    }

    /** @param {Event} event */
    #handleTelaahResepButtonClick(event) {
        event.preventDefault();
        const JSON = this.#uiTableHandler.getTelaahResepRequiredData();
        window.open(`popup/telaah-resep-raw/${JSON}`, "popupTelaahResep", `width=${screen.width},height=${screen.height},top=0,left=0`);
    }

    /** @param {Event} event */
    #handleBatchSearchChange(event) {
        const SearchKeyword = /** @type {HTMLInputElement} */ (event.target).value;
        this.#uiFormHandler.filterBatchByKeyword(SearchKeyword);
    }

    /** @param {Event} event */
    #handleSubmitForm(event) {
        // check if #nama_pasien or #nama_dokter input is empty
        // show error alert for each input when empty
        if (!$("#nama_pasien").val()) {
            event.preventDefault();
            showErrorAlertNoRefresh("Nama pasien harus diisi!");
            return;
        }

        // check if there's any input with name "si_id" where the value is empty
        if ($("input[name^='si_id']").filter(function () {
            return String($(this).val()).length == 0;
        }).length > 0) {
            event.preventDefault();
            showErrorAlertNoRefresh("Ada obat yang belum lengkap! Pilih obat terlebih dahulu!");
            return;
        }

        // check if input with name "total" value is 0
        if ($("#total").val() == 0) {
            event.preventDefault();
            showErrorAlertNoRefresh("Harga 0! Tambahkan obat terlebih dahulu!");
            return;
        }
    }

    /**
     * @param {number} key 
     * @param {string} name
     */
    jamPemberian(key, name) {
        this.#uiFormHandler.showModalJamPemberian(key, name);
    }

    /**
     * @param {number} key 
     * @param {string} name
     */
    ubahSigna(key, name) {
        this.#uiFormHandler.showModalUbahSigna(key, name);
    }

    /** @param {Event} event */
    #handleEmbalaseChange(event) {
        const elementId = /** @type {HTMLElement} */ (event.target)?.id;
        switch (elementId) {
            case 'embalase_racikan': this.#uiTableHandler.embalaseRacikan(); break;
            case 'embalase_item': this.#uiTableHandler.embalaseItem(); break;
            case 'embalase_tidak': this.#uiTableHandler.embalaseFree(); break;
            default: throw new Error("Unknown embalase element id");
        }
    }

    /** @param {MessageEvent} event */
    #handleWindowMessage(event) {
        const { type, data } = event.data;
        if (type === "patient") {
            const Registration = /** @type {Registration} */ (data);
            this.#stateManager.changeRegistration(Registration);
            this.#handleCancelAddToRacikanClick();

            if (!Registration.penjamin?.is_bpjs) {
                // check the #embalase_item radio using jquery
                $("#embalase_item").prop("checked", true).trigger("change");
            }
        }
        else if (type === "re") {
            const ElectronicRecipe = /** @type {ResepElektronik} */ (data);
            this.#stateManager.changeElectronicRecipe(ElectronicRecipe);
            this.#handleCancelAddToRacikanClick();

            if (!ElectronicRecipe.registration?.penjamin?.is_bpjs) {
                // check the #embalase_item radio using jquery
                $("#embalase_item").prop("checked", true).trigger("change");
            }
        }
        else if (type == "rh") {
            const DailyRecipe = /** @type {ResepHarian} */ (data);
            this.#stateManager.changeDailyRecipe(DailyRecipe);
            this.#handleCancelAddToRacikanClick();

            if (!DailyRecipe.registration?.penjamin?.is_bpjs) {
                // check the #embalase_item radio using jquery
                $("#embalase_item").prop("checked", true).trigger("change");
            }
        }
        else if (type === "doctor") this.#stateManager.changeDoctor(data);
        else if (type == "telaah_resep") {
            this.#stateManager.changeTelaahResep(data);
            this.#uiFormHandler.enableSubmitButton();
        }
    }

    /** @param {Event} event */
    #handlePilihPasienButtonClick(event) {
        event.preventDefault();
        window.open(`popup/pilih-pasien/${this.#patientType}`, "popupPilihPasien", `width=${screen.width},height=${screen.height},top=0,left=0`);
    }

    /** @param {Event} event */
    #handlePilihDokterButtonClick(event) {
        event.preventDefault();
        window.open("popup/pilih-dokter", "popupPilihDokter", `width=${screen.width},height=${screen.height},top=0,left=0`);
    }

    /** @param {Event} event */
    #handleResepElektronikButtonClick(event) {
        event.preventDefault();
        this.#handleCancelAddToRacikanClick();
        window.open("popup/resep-elektronik", "popupResepElektronik", `width=${screen.width},height=${screen.height},top=0,left=0`);
    }

    /** @param {Event} event */
    #handleResepHarianButtonClick(event) {
        event.preventDefault();
        this.#handleCancelAddToRacikanClick();
        window.open("popup/resep-harian", "popupResepHarian", `width=${screen.width},height=${screen.height},top=0,left=0`);
    }

    /** @param {Event} event */
    #handleTambahRacikanButtonClick(event) {
        event.preventDefault();
        const namaRacikan = prompt("Masukkan nama racikan");
        if (namaRacikan?.trim()) {
            this.#uiTableHandler.insertRacikan(namaRacikan.trim());
        }
    }

    /** @param {Select2.Event<HTMLElement, Select2.DataParams>} event */
    #handleGudangSelect(event) {
        event.preventDefault();
        this.#handleCancelAddToRacikanClick();
        const selectedId = parseInt(event.params.data.id);
        this.#stateManager.updateObatSelect(selectedId);
    }

    /**
     * @param {number} key 
     * @param {number} id 
     * @param {string} nama 
     */
    handleBatchSelectForIncompleteItem(key, id, nama) {
        this.#BatchSelectToObatKey = key;

        this.#stateManager.updateObatBatch(id)
            .then(this.#uiFormHandler.showModalSelectObatBatch.bind(this.#uiFormHandler, nama))
            .catch(e => showErrorAlertNoRefresh(e));
    }

    /** @param {StoredItem} item */
    handleBatchSelect(item) {
        this.#uiFormHandler.closeModalSelectObatBatch();

        if (this.#BatchSelectToObatKey !== undefined) { // completing incomplete item
            this.#uiTableHandler.updateIncompleteObatBatch(item, this.#BatchSelectToObatKey);
            this.#BatchSelectToObatKey = undefined;
            return;
        }
        if (this.#ObatSelectToRacikanKey !== undefined) { // adding to racikan
            this.#uiTableHandler.insertObat(item, this.#ObatSelectToRacikanKey);
            return;
        }

        // const existingRow = $(`input[name^="obat_id"][value="${item.id}"]`).closest('tr.singleton');
        const existingRow = $(`input[name^="si_id"][value="${item.id}"]`).closest('tr.singleton');
        if (existingRow.length > 0) {
            const inputQty = existingRow.find('input[name^="qty"]');
            const currentQty = parseInt(String(inputQty.val()));
            if (currentQty < item.qty) {
                inputQty.val(currentQty + 1).trigger('change'); // Trigger change to refresh total
            }
        } else {
            this.#uiTableHandler.insertObat(item);
        }
    }

    /** @param {Select2.Event<HTMLElement, Select2.DataParams>} event */
    #handleObatSelect(event) {
        event.preventDefault();
        const selectedElement = event.params.data.element;
        const item = /** @type {ObatStock} */($(selectedElement).data('item'));
        this.#uiFormHandler.clearObatSelection();

        this.#stateManager.updateObatBatch(item.id)
            .then(this.#uiFormHandler.showModalSelectObatBatch.bind(this.#uiFormHandler, item.nama))
            .catch(e => showErrorAlertNoRefresh(e));
    }

    /** @param {Event} event */
    #handleTipePasienChange(event) {
        const select = /** @type {HTMLSelectElement | null} */ (event.target);
        if (!select) return;

        this.#patientType = /** @type {PatientType} */ (select.value);
        this.#stateManager.resetState();
        this.#uiFormHandler.handlePatientTypeChange(this.#patientType);
        this.#handleCancelAddToRacikanClick();
    }

    #handleCancelAddToRacikanClick = () => {
        this.#ObatSelectToRacikanKey = undefined;
        this.#uiFormHandler.$AddToRacikanSpan.hide();
    }

    // --- PUBLIC METHODS (Called from onclick attributes) ---

    tambahObatRacikan(key, name) {
        this.#ObatSelectToRacikanKey = key;
        this.#uiFormHandler.$AddToRacikanSpan.text(`Menambahkan ke Racikan: 「${name}」.`);
        this.#uiFormHandler.$AddToRacikanSpan.show();
    }

    deleteItem(key) {
        this.#uiTableHandler.deleteItem(key);
    }

    deleteRacikan(key) {
        this.#handleCancelAddToRacikanClick();
        this.#uiTableHandler.deleteRacikan(key);
    }

    noRestriksiSwal(nama) {
        Swal.fire(`「${nama}」 Tidak memiliki restriksi`, '', 'info');
    }

    restriksiSwal(nama, restriksi) {
        Swal.fire(`「${nama}」 Memiliki restriksi`, "Restriksi: " + restriksi, 'warning');
    }

    /**
     * Helper to attach event listeners.
     * @param {string} selector
     * @param {string} event
     * @param {Function} handler
     */
    #addEventListeners(selector, event, handler) {
        document.querySelectorAll(selector).forEach(button => {
            button.addEventListener(event, handler.bind(this));
        });
    }


    /** @param {Event} event */
    #handleSignaButtonClick(event) {
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const value = button.getAttribute('data-value');
        if (value) {
            const signaContent = document.getElementById('signa-content');
            if (signaContent) {
                // Cast to HTMLInputElement to access .value property
                const inputElement = /** @type {HTMLInputElement} */ (signaContent);
                // Append the value with a space separator
                if (inputElement.value.trim() === '') {
                    inputElement.value = value;
                } else {
                    inputElement.value += ' ' + value;
                }
                // Trigger input event to update any listeners
                inputElement.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }
}
