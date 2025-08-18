// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

/**
 * A class responsible for updating general form elements and information fields.
 */
class UIFormUpdater {
    $Loadings = $(".loading");
    $OTCChanges = $(".otc-change");
    $LoadingMessage = $("#loading-message");
    $ObatSelect = $("#obat-select");
    $GudangSelect = $("#gudang_id");
    $TipePasienSelect = $("#tipe_pasien");
    $TableObatBatch = $("#tableObats");
    $PilihPasienButton = $("#pilih-pasien-btn");
    $AddToRacikanSpan = $("#add-to-racikan");
    $PilihObat = $("#modal-pilih-obat");
    $JamPemberian = $("#modal-jam-pemberian");
    $Signa = $("#modal-signa");
    $SubmitButton = $("#submit-btn");
    $TelaahResepButton = $("#telaah-resep-btn");
    $ModalJamPemberian = new bootstrap.Modal(this.$JamPemberian[0]);
    $ModalSigna = new bootstrap.Modal(this.$Signa[0]);
    $ModalPilihObat = new bootstrap.Modal(this.$PilihObat[0]);

    /** @type {UIHTMLRenderer} */
    htmlRenderer;

    /**
     * @param {UIHTMLRenderer} htmlRendererInstance
     */
    constructor(htmlRendererInstance) {
        if (!htmlRendererInstance) {
            throw new Error("UIFormUpdater requires an instance of UIHTMLRenderer.");
        }
        this.htmlRenderer = htmlRendererInstance;
    }


    /** @param {string} keyword */
    filterBatchByKeyword(keyword) {
        this.$PilihObat.find(".batch-obat-select").each(function () {
            const $this = $(this);
            const text = $this.text().toLowerCase();
            if (text.includes(keyword.toLowerCase())) {
                $this.show();
            } else {
                $this.hide();
            }
        });
    }

    /**
     * @param {number} key 
     * @param {string} name 
     */
    showModalUbahSigna(key, name) {
        this.$Signa.find("#nama-obat").text(name);
        const Item = $("#item" + key);

        // get signa from hidden input with name^=signa
        const Signa = Item.find(`input[name^=signa]`).val();
        this.$Signa.find("#signa-content").val(String(Signa));

        this.$ModalSigna.show()

        // Attach an event listener for when the modal hides
        this.$Signa.one('hidden.bs.modal', (e) => {
            console.log('Modal has been closed.');

            // Check if any checkboxes are checked to determine if there was a result
            const NewSigna = String(this.$Signa.find('#signa-content').val());
            console.log(NewSigna);
            Item.find("input[name^=signa]").val(NewSigna);
            Item.find('.signa').text(NewSigna || '-');
        });
    }

    /**
     * @param {number} key 
     * @param {string} name 
     */
    showModalJamPemberian(key, name) {
        this.$JamPemberian.find("#nama-obat").text(name);

        // first, uncheck all checkbox with class .jam-pemberian-checks
        this.$JamPemberian.find(".jam-pemberian-checks").prop("checked", false);

        const Item = $("#item" + key);

        // get jams from hidden input with name^=jam_pemberian
        // since the value is in string, parse it first
        const jams = JSON.parse(String(Item.find(`input[name^=jam_pemberian]`).val()) || "[]"); // default to empty array if value is null or undefined

        for (let i = 0; i < jams.length; i++) {
            const Jam = jams[i];
            const Id = "jam" + Jam;
            // check the checkbox with id jam + Jam
            this.$JamPemberian.find(`#${Id}`).prop("checked", true);
        }

        this.$ModalJamPemberian.show()
        // Attach an event listener for when the modal hides
        this.$JamPemberian.one('hidden.bs.modal', (e) => {
            console.log('Modal has been closed.');

            // Check if any checkboxes are checked to determine if there was a result
            const isChecked = this.$JamPemberian.find('.jam-pemberian-checks:checked');
            const Hours = Array.from(isChecked).map(checkbox => /** @type {HTMLInputElement} */(checkbox).value);
            console.log(isChecked, Hours);

            Item.find("input[name^=jam_pemberian]").val(JSON.stringify(Hours));
            Item.find('.jam-pemberian').text(Hours.map(hour => hour.padStart(2, '0') + ':00').join(", ") || "-");
        });
    }

    /** @param {"rajal" | "ranap" | "otc"} type */
    changeTipePasien(type) {
        this.$TipePasienSelect.val(type).trigger("change");
    }

    /**
     * Show or hide the loading icon.
     * @param {boolean} show
     * @param {string?} message
     */
    showLoading(show, message = null) {
        this.$Loadings.toggle(show);
        this.$LoadingMessage.text(message || 'Loading...');
    }

    /** @param {string} tr */
    updateTelaahResepInput(tr) {
        $("input#telaah-resep").val(tr);
    }

    /** 
     * @param {string} name 
     */
    tambahObatRacikan(name) {
        this.$AddToRacikanSpan.text(`Menambahkan ke racikan "${name}"`);
    }

    clearTambahObatRacikan() {
        this.$AddToRacikanSpan.text('');
    }

    /**
     * Update patient information in the UI.
     * @param {Registration} registration
     * @param {{years: number, months: number, days: number}} exactAge
     */
    updatePatientInfo(registration, exactAge) {
        const Age = `${exactAge.years} thn ${exactAge.months} bln ${exactAge.days} hr`;

        if (!registration.penjamin || !registration.departement || !registration.patient || !registration.doctor?.employee) {
            showErrorAlertNoRefresh("Registration object is not complete");
            throw new Error("Registration object is not complete");
        }

        $("#registration-id").val(registration.id);
        $("#nama_pasien").val(registration.patient.name);
        $("#mrn_registration_number").val(`${registration.patient.medical_record_number} / ${registration.registration_number}`);
        $("#penjamin").val(registration.penjamin.nama_perusahaan);
        $("#umur_jk").val(`${Age} / ${registration.patient.gender}`);
        $("#alamat").val(registration.patient.address);
        $("#poly_ruang").val(registration.departement.name);
        $("#no_telp").val(registration.patient.mobile_phone_number);
    }

    /**
     * Update doctor name in the UI.
     * @param {Doctor} doctor
     */
    updateDoctorInfo(doctor) {
        if (!doctor.employee) {
            showErrorAlertNoRefresh("Doctor object is not complete");
            throw new Error("Doctor object is not complete");
        }
        $("#dokter-id").val(doctor.id);
        $("#nama_dokter").val(doctor.employee.fullname);
    }

    /**
     * Update the drug selection dropdown.
     * @param {(BarangFarmasi & {qty: number})[]} items
     */
    updateObatSelect(items) {
        this.$ObatSelect.empty().append(new Option("Pilih Obat", ""));
        items.forEach(item => {
            if (!item.zat_aktif) {
                showErrorAlertNoRefresh("BarangFarmasi object is not complete");
                throw new Error("BarangFarmasi object is not complete");
            }
            const zats = item.zat_aktif.map(z => z.zat?.nama).join('');
            const option = $(this.htmlRenderer.getObatSelectHTML(item, zats));
            this.$ObatSelect.append(option);
        });
        this.$ObatSelect.find('option:first').prop('disabled', true);
        this.$ObatSelect.trigger('change');
    }

    disableTelaahResepButton() {
        this.$TelaahResepButton.prop("disabled", true);
    }

    enableTelaahResepButton() {
        this.$TelaahResepButton.prop("disabled", false);
    }

    disableSubmitButton() {
        this.$SubmitButton.prop("disabled", true);
    }

    enableSubmitButton() {
        this.$SubmitButton.prop("disabled", false);
    }

    /** @param {StoredItem[]} items */
    updateObatBatch(items) {
        this.$TableObatBatch.empty();

        // ensure the top item is the one with nearest expired date
        // sort items by item.pbi.tanggal_exp
        // which is a string of date in SQL
        items.sort((a, b) => {
            if (!a.pbi || !a.pbi.item || !b.pbi || !b.pbi.item) {
                showErrorAlertNoRefresh("Stored object is not complete");
                throw new Error("Stored object is not complete");
            }

            // Convert date strings to Date objects for proper comparison
            const dateA = new Date(a.pbi.tanggal_exp || '0001-01-01');
            const dateB = new Date(b.pbi.tanggal_exp || '0001-01-01');

            // Return the difference in milliseconds between dates
            return dateA.getTime() - dateB.getTime();
        });

        items.forEach(item => {
            if (!item.pbi || !item.pbi.item || !item.pbi.item.satuan) {
                showErrorAlertNoRefresh("Stored object is not complete");
                throw new Error("Stored object is not complete");
            }
            const row = this.htmlRenderer.getObatBatchRowHTML(item);
            this.$TableObatBatch.append(row);
        });
    }

    /** @param {string} name Nama obat */
    showModalSelectObatBatch(name) {
        this.$PilihObat.find("#nama-obat").text(name);
        this.$PilihObat.find("#batch").val("").trigger("change");
        this.$ModalPilihObat.show();
    }

    closeModalSelectObatBatch() {
        this.$ModalPilihObat.hide();
    }

    /**
     * Reset the form to its initial state.
     */
    reset() {
        $("main input:not([type='hidden']):not(.unclearable)").val("");
        $("#doctor-id").val("");
        $("#registration-id").val("");
        $("#dokter-id").val("");
        $("#re-id").val("");
        $("#embalase_tidak").prop("checked", true);
        $("#bmhp").prop("checked", false);
        $("#kronis").prop("checked", false);
        $("#dispensing").prop("checked", false);
        $("#tableItems").empty(); // Clear the items table
        $("#resep-manual").val("");
        $("#telaah-resep").val("");
        this.$SubmitButton.prop("disabled", true); // Disable submit button
        this.$PilihPasienButton.prop("disabled", false);
    }

    /**
     * Handles UI changes when patient type is switched.
     * @param {PatientType} patientType
     */
    handlePatientTypeChange(patientType) {
        this.reset();
        const isOtc = patientType === "otc";
        this.$OTCChanges.each(function () {
            const name = $(this).attr("name") || ''; // Ensure we have a name attribute to avoid errors
            const isReadOnly = !isOtc || ["order_date", "mrn_registration_number", "poly_ruang"].includes(name);
            $(this).prop("readonly", isReadOnly);
        });

        if (isOtc) {
            this.disableTelaahResepButton();
            this.enableSubmitButton();
            $("input[name='mrn_registration_number']").val("OTC");
        } else {
            this.enableTelaahResepButton();
            this.disableSubmitButton();
        }
        this.$PilihPasienButton.prop("disabled", isOtc);
    }

    /**
     * Unselects the drug selection dropdown.
     */
    clearObatSelection() {
        // @ts-ignore
        this.$ObatSelect.val(null).trigger('change');
    }
}