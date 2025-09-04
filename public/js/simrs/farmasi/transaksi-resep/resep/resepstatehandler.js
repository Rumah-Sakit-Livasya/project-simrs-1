// @ts-check
/// <reference path="../../../../types.d.ts" />

/**
 * Manages the application's core data state (Registration, Doctor, Recipe)
 * and orchestrates data fetching and state updates.
 */
class ResepStateManager {
    /** @type {Registration | undefined} */
    #Registration;
    /** @type {Doctor | undefined} */
    #Doctor;
    /** @type {ResepHarian | undefined} */
    #DailyRecipe;
    /** @type {ResepElektronik | undefined} */
    #ElectronicRecipe;
    /** @type {TelaahResep | undefined} */
    #TelaahResep;

    /** @type {ApiHandler} */
    #apiHandler;
    /** @type {UIFormUpdater} */
    #uiFormHandler;
    /** @type {UITableUpdater} */
    #uiTableHandler;

    /**
     * @param {ApiHandler} apiHandler
     * @param {UIFormUpdater} uiFormHandler
     ** @param {UITableUpdater} uiTableHandler
     */
    constructor(apiHandler, uiFormHandler, uiTableHandler) {
        this.#apiHandler = apiHandler;
        this.#uiFormHandler = uiFormHandler;
        this.#uiTableHandler = uiTableHandler;
    }

    /** Resets the internal state and the UI. */
    resetState() {
        this.#Registration = undefined;
        this.#Doctor = undefined;
        this.#ElectronicRecipe = undefined;
        this.#TelaahResep = undefined;
        this.#uiFormHandler.reset();
        this.#uiTableHandler.refreshTotal();
    }

    /**
     * Updates the current Telaah Resep.
     * @param {string} tr
     */
    changeTelaahResep(tr) {
        /** @type {any[]} */
        const Data = JSON.parse(tr);

        // @ts-ignore
        this.#TelaahResep = {};

        for (let i = 0; i < Data.length; i++)
            if (this.#TelaahResep && !Data[i].name.startsWith('_')) {
                this.#TelaahResep[Data[i].name] = Data[i].value;
            }

        this.#uiFormHandler.updateTelaahResepInput(JSON.stringify(this.#TelaahResep));
    }

    /**
     * Updates the current daily recipe, which in turn updates the patient and doctor.
     * @param {ResepHarian} rh 
     */
    async changeDailyRecipe(rh) {
        if (!rh.registration || !rh.registration.doctor || !rh.registration.patient) {
            showErrorAlertNoRefresh("ResepHarian object is not complete");
            throw new Error("ResepHarian object is not complete");
        }

        const currentGudangId = $("#gudang_id").val();
        if (rh.gudang_id !== null && parseInt(String(currentGudangId)) !== rh.gudang_id) {
            await this.updateObatSelect(rh.gudang_id);
        }

        this.resetState();

        this.#uiFormHandler.changeTipePasien("ranap");
        await this.switchToGudangDefaultRanap();

        this.#DailyRecipe = rh;
        this.#Registration = rh.registration;
        this.#Doctor = rh.registration.doctor;

        const exactAge = Utils.calculateExactAge(rh.registration.patient.date_of_birth);
        this.#uiFormHandler.updatePatientInfo(rh.registration, exactAge);
        this.#uiFormHandler.updateDoctorInfo(rh.registration.doctor);
        this.#uiTableHandler.updateDailyRecipeInfo(rh);
    }

    /**
     * Updates the current electronic recipe, which in turn updates the patient and doctor.
     * @param {ResepElektronik} re
     */
    async changeElectronicRecipe(re) {
        if (!re.registration || !re.registration.doctor || !re.registration.patient || !re.cppt) {
            showErrorAlertNoRefresh("ResepElektronik object is not complete");
            throw new Error("ResepElektronik object is not complete");
        }

        const currentGudangId = $("#gudang_id").val();
        if (re.gudang_id !== null && parseInt(String(currentGudangId)) !== re.gudang_id) {
            await this.updateObatSelect(re.gudang_id);
        }

        this.resetState();
        if (re.registration.registration_type == "rawat-inap") {
            this.#uiFormHandler.changeTipePasien("ranap");
            await this.switchToGudangDefaultRanap();
        } else if (re.registration.registration_type == "rawat-jalan") {
            this.#uiFormHandler.changeTipePasien("rajal");
            await this.switchToGudangDefaultRajal();
        }

        this.#ElectronicRecipe = re;
        this.#Registration = re.registration;
        this.#Doctor = re.registration.doctor;

        const exactAge = Utils.calculateExactAge(re.registration.patient.date_of_birth);
        this.#uiFormHandler.updatePatientInfo(re.registration, exactAge);
        this.#uiFormHandler.updateDoctorInfo(re.registration.doctor);
        this.#uiTableHandler.updateElectronicRecipeInfo(re);
    }

    /**
     * Updates the current doctor.
     * @param {Doctor} doctor
     */
    changeDoctor(doctor) {
        this.#Doctor = doctor;
        this.#uiFormHandler.updateDoctorInfo(doctor);
    }

    /**
     * Updates the current patient registration.
     * @param {Registration} registration
     */
    async changeRegistration(registration) {
        if (!registration.penjamin || !registration.departement || !registration.patient || !registration.doctor) {
            showErrorAlertNoRefresh("Registration object is not complete");
            throw new Error("Registration object is not complete");
        }

        this.#Registration = registration;
        this.#Doctor = registration.doctor;


        if (registration.registration_type == "rawat-inap") {
            await this.switchToGudangDefaultRanap();
        } else {
            await this.switchToGudangDefaultRajal();
        }

        const exactAge = Utils.calculateExactAge(registration.patient.date_of_birth);
        this.#uiFormHandler.updatePatientInfo(registration, exactAge);
        this.#uiFormHandler.updateDoctorInfo(registration.doctor);
    }

    async switchToGudangDefaultRanap() {
        this.#uiFormHandler.showLoading(true, "Switching to default warehouse for inpatient...");
        const ID = await this.#apiHandler.fetch("/gudang-default-ranap")
            .catch(err => { return showErrorAlertNoRefresh(err.message); })
            .finally(() => { this.#uiFormHandler.showLoading(false); });
        if (!ID || ID == -1) return;
        if (this.#uiFormHandler.$GudangSelect.val() == ID) return;

        this.#uiFormHandler.$GudangSelect.val(ID).trigger("change");
        this.updateObatSelect(ID);
    }

    async switchToGudangDefaultRajal() {
        this.#uiFormHandler.showLoading(true, "Switching to default warehouse for outpatient...");
        const ID = await this.#apiHandler.fetch("/gudang-default-rajal")
            .catch(err => { return showErrorAlertNoRefresh(err.message); })
            .finally(() => { this.#uiFormHandler.showLoading(false); });
        if (!ID || ID == -1) return;
        if (this.#uiFormHandler.$GudangSelect.val() == ID) return;

        this.#uiFormHandler.$GudangSelect.val(ID).trigger("change");
        this.updateObatSelect(ID);
    }

    /**
     * Fetches drug items for a given warehouse and updates the UI select element.
     * @param {number} gudang_id
     */
    updateObatSelect(gudang_id) {
        this.#uiFormHandler.showLoading(true, "Fetching Items...");
        this.#uiTableHandler.$Table.empty(); // Also clear the table when warehouse changes
        this.#uiTableHandler.refreshTotal();

        return this.#apiHandler.fetch(`/obat/${gudang_id}`)
            .then(response => this.#uiFormHandler.updateObatSelect(response.items))
            .catch(error => showErrorAlertNoRefresh(error.message))
            .finally(() => this.#uiFormHandler.showLoading(false));
    }

    /** @param {number} id */
    updateObatBatch(id) {
        this.#uiFormHandler.showLoading(true, "Fetching Batch...");
        const GudangID = String(this.#uiFormHandler.$GudangSelect.val());
        return this.#apiHandler.fetch(`/batch/${GudangID}/${id}`)
            .then(response => this.#uiFormHandler.updateObatBatch(response.items))
            .catch(error => showErrorAlertNoRefresh(error.message))
            .finally(() => this.#uiFormHandler.showLoading(false));
    }
}