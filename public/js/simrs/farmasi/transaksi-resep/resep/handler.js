// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class ResepHandler {
    #uiHandler;
    #apiHandler;

    /** @type {Registration | undefined} */
    #Registration;

    /** @type {Doctor | undefined} */
    #Doctor;

    /** @type {PatientType} */
    #patientType = "rajal"

    constructor() {
        this.#uiHandler = new UIHandler();
        this.#apiHandler = new ApiHandler();
        $(document).ready(this.#init.bind(this));
    }

    #init() {
        $("#gudang_id").on('select2:select', this.#handleGudangSelect.bind(this));
        $("#obat-select").on('select2:select', this.#handleObatSelect.bind(this));
        $(document).on("change input keyup", "input[type='number']", Utils.enforceNumberLimit);
        $(document).on("change", "#tipe_pasien", this.#selectTipePasienChange.bind(this));
        
        this.#addEventListeners("#pilih-pasien-btn", this.#handlePilihPasienButtonClick);
        this.#addEventListeners("#pilih-dokter-btn", this.#handlePilihDokterButtonClick);
        this.#addEventListeners("#resep-elektronik-btn", this.#handleResepElektronikButtonClick);

        window.addEventListener("message", this.#handleWindowMessage.bind(this));
        
        this.#uiHandler.showLoading(false);
    }

    /**
     * @param {MessageEvent} event
     */
    #handleWindowMessage(event) {
        console.log("Receiving message from popup", event.data);
        if (event.data.type) {
            if (event.data.type == "patient") {
                this.#changeRegistration(event.data.data);
            } else if (event.data.type == "doctor") {
                this.#changeDoctor(event.data.data)
            }
        }
    }

    #handleResepElektronikButtonClick(event) {
        event.preventDefault();
        let popup = window.open("popup/resep-elektronik", "popupResepElektronik", `width=${screen.width},height=${screen.height},top=0,left=0`);
        if (!popup) return alert(new Error("Popup is null."));
    }

    #handlePilihPasienButtonClick(event) {
        event.preventDefault();
        let popup = window.open("popup/pilih-pasien/" + this.#patientType, "popupPilihPasien", `width=${screen.width},height=${screen.height},top=0,left=0`);
        if (!popup) return alert(new Error("Popup is null."));
    }

    #handlePilihDokterButtonClick(event) {
        event.preventDefault();
        let popup = window.open("popup/pilih-dokter", "popupPilihDokter", `width=${screen.width},height=${screen.height},top=0,left=0`);
        if (!popup) return alert(new Error("Popup is null."));
    }

    /** @param {Doctor} doctor */
    #changeDoctor(doctor) {
        this.#Doctor = doctor;
        this.#uiHandler.updateDoctorInfo(doctor);
    }

    /** @param {Registration} registration */
    #changeRegistration(registration) {
        if (registration.penjamin === undefined || registration.departement === undefined || registration.patient === undefined || registration.doctor === undefined) {
            showErrorAlertNoRefresh("Registration object is not complete");
            throw new Error("Registration object is not complete");
        }

        this.#Registration = registration;
        this.#Doctor = registration.doctor;

        const exactAge = Utils.calculateExactAge(registration.patient.date_of_birth);
        this.#uiHandler.updatePatientInfo(registration, exactAge);
        this.#uiHandler.updateDoctorInfo(registration.doctor);
    }

    /** @param {Select2.Event<HTMLElement, Select2.DataParams>} event */
    #handleGudangSelect(event) {
        event.preventDefault();
        const selectedId = event.params.data.id;
        this.#uiHandler.showLoading(true, "Fetching Items...");
        
        this.#apiHandler.fetch(`/obat/${selectedId}`)
            .then(response => this.#uiHandler.updateObatSelect(response.items))
            .catch(error => showErrorAlertNoRefresh(error.message))
            .finally(() => this.#uiHandler.showLoading(false));
    }

    /** @param {Select2.Event<HTMLElement, Select2.DataParams>} event */
    #handleObatSelect(event) {
        event.preventDefault();
        const selectedId = event.params.data.id;
        // Logic to add item to table would go here
        this.#uiHandler.clearObatSelection();
    }

    /** @param {Event} event */
    #selectTipePasienChange(event) {
        const select = /** @type {HTMLSelectElement | null} */ (event.target);
        if (!select) return;

        this.#Registration = undefined;
        this.#patientType = /** @type {PatientType} */ (select.value);
        this.#uiHandler.handlePatientTypeChange(this.#patientType);
    }

    /**
     * @param {string} selector 
     * @param {Function} handler 
     * @param {string} event
     */
    #addEventListeners(selector, handler, event = 'click') {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach((button) => {
            button.addEventListener(event, handler.bind(this));
        });
    }
}

// Instantiate the main handler to start the application
const ResepClass = new ResepHandler();