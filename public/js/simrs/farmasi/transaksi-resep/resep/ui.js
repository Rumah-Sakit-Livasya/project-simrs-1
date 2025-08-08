// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

class UIHandler {
    #$Loadings = $(".loading");
    #$OTCChanges = $(".otc-change");
    #$LoadingMessage = $("#loading-message");
    #$ObatSelect = $("#obat-select");
    #$GudangSelect = $("#gudang_id");
    #$Table = $("#tableItems");
    #$PilihPasienButton = $("#pilih-pasien-btn");

    /**
     * Show or hide the loading icon
     * @param {boolean} show 
     * @param {string?} message 
     */
    showLoading(show, message = null) {
        this.#$Loadings.toggle(show);

        if (message) {
            this.#$LoadingMessage.text(message);
        } else {
            this.#$LoadingMessage.text('Loading...');
        }
    }

    /**
     * Update patient information in the UI
     * @param {Registration} registration
     * @param {{years: number, months: number, days: number}} exactAge
     */
    updatePatientInfo(registration, exactAge) {
        const Age = `${exactAge.years} thn ${exactAge.months} bln ${exactAge.days} hr`; // format age to string

        if (registration.penjamin === undefined ||
            registration.departement === undefined ||
            registration.patient === undefined ||
            registration.doctor === undefined ||
            registration.doctor.employee === undefined
        ) {
            showErrorAlertNoRefresh("Registration object is not complete"); // show error alert
            throw new Error("Registration object is not complete");
        }

        $("#nama_pasien").val(registration.patient.name);
        $("#mrn_registration_number").val(`${registration.patient.medical_record_number} / ${registration.registration_number}`);
        $("#penjamin").val(registration.penjamin.nama_perusahaan);
        $("#umur_jk").val(`${Age} / ${registration.patient.gender}`);
        $("#alamat").val(registration.patient.address);
        $("#poly_ruang").val(registration.departement.name);
        $("#no_telp").val(registration.patient.mobile_phone_number);
    }

    /**
     * Update doctor name in the UI
     * @param {Doctor} doctor 
     */
    updateDoctorInfo(doctor) {
        if (doctor.employee === undefined) {
            showErrorAlertNoRefresh("Doctor object is not complete"); // show error alert
            throw new Error("Doctor object is not complete");
        }
        $("#nama_dokter").val(doctor.employee.fullname);
    }

    /**
     * Update the drug selection dropdown
     * @param {Array<any>} items 
     */
    updateObatSelect(items) {
        this.#$ObatSelect.empty();
        this.#$ObatSelect.append(new Option("Pilih Obat", ""));
        items.forEach(item => {
            this.#$ObatSelect.append($(/*html*/`
                <option value="${item.id}" data-item='${JSON.stringify(item)}' class="obat">
                    ${item.nama} (Stock: ${item.qty})
                </option>
            `));
        });
        this.#$ObatSelect.find('option:first').prop('disabled', true);
        this.#$ObatSelect.trigger('change');
    }

    /**
     * Reset the form to its initial state
     */
    reset() {
        $("input:not([type='hidden']):not(.unclearable)").val("");
        $("#embalase_tidak").prop("checked", true);
        $("#bmhp").prop("checked", false);
        $("#kronis").prop("checked", false);
        $("#dispensing").prop("checked", false);
        this.#$PilihPasienButton.prop("disabled", false);
    }

    /**
     * Handles UI changes when patient type is switched
     * @param {PatientType} patientType
     */
    handlePatientTypeChange(patientType) {
        this.reset();
        if (patientType == "otc") {
            this.#$OTCChanges.each(function () {
                if ($(this).attr("name") != "order_date" && $(this).attr("name") != "mrn_registration_number" && $(this).attr("name") != "poly_ruang") {
                    $(this).prop("readonly", false);
                }
            });
            $("input[name='mrn_registration_number']").val("OTC");
            this.#$PilihPasienButton.prop("disabled", true);
        } else {
            this.#$OTCChanges.each(function () {
                $(this).prop("readonly", true);
            });
            this.#$PilihPasienButton.prop("disabled", false);
        }
    }

    /**
     * Unselects the drug selection dropdown
     */
    clearObatSelection() {
        // @ts-ignore
        this.#$ObatSelect.val(null).trigger('change');
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {BarangFarmasi} item 
     */
    #getItemTableCol(item) {
        // todo
    }

    refreshTotal(option = { updatediskon: true }) {
        // todo
    }

    /**
     * Delete item from table and variable
     * @param {string} key 
     */
    deleteItem(key) {
        this.#$Table.find("#item" + key).remove();
        this.refreshTotal();
    }
}