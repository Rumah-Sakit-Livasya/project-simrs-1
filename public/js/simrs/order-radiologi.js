// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

/**
 * @typedef {"rajal" | "ranap" | "otc"} PatienType
 */

class OrderRadiologi {

    /**
     * @type {ParameterRadiologi[]}
     */
    #ParameterRadiologi;

    /**
     * @type {Penjamin[]}
     */
    #Penjamins;

    /**
     * @type {TarifRadiologi[]}
     */
    #TarifRadiologi;

    /**
     * @type {Registration | undefined}
     */
    #Registration;

    #totalHarga = 0;
    /**
     * @type {HTMLElement | undefined}
     */
    #elementHarga = undefined;

    /**
     * @type {HTMLFormElement | undefined}
     */
    #elementForm = undefined;

    /**
     * @type {HTMLDivElement | undefined}
     */
    #activeParameterDiv = undefined;

    /**
     * @type {{ id: number, qty: number }[]}
     */
    #selectedParameters = [];

    /**
     * @type {HTMLInputElement[]}
     */
    #initialDisabledInputs = [];

    /**
     * @type {HTMLButtonElement | undefined}
     */
    #pilihPasienButton;

    /**
     * @type {PatienType}
     */
    #patienType = "rajal"

    #CITO = false;

    constructor() {
        // @ts-ignore
        this.#ParameterRadiologi = window._parameterRadiologi;
        // @ts-ignore
        this.#TarifRadiologi = window._tarifRadiologi;
        // @ts-ignore
        this.#Penjamins = window._penjamins;

        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
    }

    #init() {
        // Harga
        this.#elementHarga = document.getElementById("radiologi-total") || undefined;

        // Order Type Radio
        const orderType = document.querySelectorAll("input[type='radio'][name='order_type']");
        if (orderType) {
            orderType.forEach((radio) => {
                radio.addEventListener("change", this.#orderTypeChange.bind(this));
            });
        }

        // Form
        const form = document.querySelector("form[name='form-radiologi']");
        if (form) {
            this.#elementForm = /** @type {HTMLFormElement} */ (form);
            form.addEventListener("submit", this.#submit.bind(this));
        } else {
            alert("FORM NOT FOUND")
        }

        // submit button
        const submitButton = document.querySelector("button.submit-btn");
        if (submitButton) {
            submitButton.addEventListener("click", this.#submit.bind(this));
        }

        // Select parameter (for Select2)
        const selectParameter = $('#select_parameter');
        if (selectParameter.length) {
            selectParameter.on("select2:select", this.#selectParameterChange.bind(this));
        }

        // Active parameters div
        const activeParameterDiv = document.querySelector("div#active-parameters");
        if (form) {
            this.#activeParameterDiv = /** @type {HTMLDivElement} */ (activeParameterDiv);
        }

        // get all disabled inputs AND hidden inputs
        const disabledInputs = document.querySelectorAll("input:disabled");
        for (const input of disabledInputs) {
            this.#initialDisabledInputs.push(/** @type {HTMLInputElement} */(input))
        }

        // get tipe_pasien select
        const tipePasienSelect = document.querySelector("select#tipe_pasien");
        if (tipePasienSelect) {
            // listen to change
            tipePasienSelect.addEventListener("change", this.#selectTipePasienChange.bind(this));
        }

        // get pilih passien button
        const pilihPasienButton = document.querySelector("button#pilih-pasien-btn");
        if (pilihPasienButton) {
            // listen to click
            this.#pilihPasienButton = /** @type {HTMLButtonElement} */ (pilihPasienButton);

            // listen to click
            pilihPasienButton.addEventListener("click", this.#handlePilihPasienButtonClick.bind(this));
        }

        window.addEventListener("message", (event) => {
            console.log("Receiving message from popup", event.data);

            this.changeRegistration(event.data.data);
        });
    }

    /**
     * Handle pilih pasien button click
     * @param {Event} event 
     */
    #handlePilihPasienButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        if (!button) return;
        if (button.disabled) return;
        if (!this.#pilihPasienButton) return;

        // open popup
        let popup = window.open("popup/pilih-pasien/" + this.#patienType, "popupPilihPasien", `width=${screen.width},height=${screen.height},top=0,left=0`);
        if (!popup) return alert(new Error("Popup is null."));

        let interval = setInterval(() => {
            if (popup && popup.closed) {
                clearInterval(interval);
            }
        }, 500);
    }

    /**
     * Change registration. \
     * Should be called from pilih pasien popup.
     * @param {Registration} registration 
     */
    changeRegistration(registration) {
        this.#Registration = registration;
        console.log("Change registration called");


        // change input with name "nama_pasien"
        const namaPasienInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='nama_pasien']"));
        if (namaPasienInput) {
            namaPasienInput.value = /** @type {HTMLInputElement} */ registration.patient.name;
        }

        // change input with name "date_of_birth"
        const dateOfBirthInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='date_of_birth']"));
        if (dateOfBirthInput) {
            dateOfBirthInput.value = registration.patient.date_of_birth;
        }

        // change input with name "poly_ruang"
        const polyRuangInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='poly_ruang']"));
        if (polyRuangInput) {
            polyRuangInput.value = registration.departement.name;
        }

        // change input with name "alamat"
        const alamatInput =/** @type {HTMLInputElement} */  (document.querySelector("input[name='alamat']"));
        if (alamatInput) {
            alamatInput.value = registration.patient.address;
        }

        // change input with name "no_telp"
        const noTelpInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='no_telp']"));
        if (noTelpInput) {
            noTelpInput.value = registration.patient.mobile_phone_number;
        }

        // change input with name "medical_record_number"
        const medicalRecordNumberInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='medical_record_number']"));
        if (medicalRecordNumberInput) {
            medicalRecordNumberInput.value = registration.patient.medical_record_number;
        }

        // change input with name "registration_number"
        const registrationNumberInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='registration_number']"));
        if (registrationNumberInput) {
            registrationNumberInput.value = registration.registration_number;
        }

        // change input with name "mrn_registration_number"
        const mrnRegistrationNumberInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='mrn_registration_number']"));
        if (mrnRegistrationNumberInput) {
            mrnRegistrationNumberInput.value = `${registration.patient.medical_record_number} / ${registration.registration_number}`;
        }

        // change radio with name "jenis_kelamin"
        const jenisKelaminInput = /** @type {HTMLInputElement} */ (document.querySelector(`input[id='gender_${registration.patient.gender == "Laki-laki" ? "male" : "female"}']`));
        if (jenisKelaminInput) {
            // select the radio
            jenisKelaminInput.checked = true;
        }

        // recalculate cost
        this.#calculateCost();
    }

    #clearInputs() {
        this.#Registration = undefined;
        this.#initialDisabledInputs.forEach((input) => {
            if (input.id != "order_date") {
                if (input.type != "radio") {
                    input.value = "";
                } else {
                    input.checked = false;
                }
            }
        });
        // recalculate cost
        this.#calculateCost();
    }



    /**
     * Handle select tipe pasien changes
     * @param {Event} event 
     */
    #selectTipePasienChange(event) {
        const select = /** @type {HTMLSelectElement | null} */ (event.target);
        if (!select) return;

        // always clear inputs first
        this.#clearInputs();

        this.#patienType = /** @type {PatienType} */ (select.value);

        if (select.value == "otc") {
            // enable all disabled inputs except "order_date", "mrn_registration_number", and "poly_ruang"
            this.#initialDisabledInputs.forEach((input) => {
                if (input.name != "order_date" && input.name != "mrn_registration_number" && input.name != "poly_ruang") {
                    input.disabled = false;
                }
            });

            // set input with name "mrn_registration_number" value to "OTC"
            const mrnRegistrationNumberInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='mrn_registration_number']"));
            if (mrnRegistrationNumberInput) mrnRegistrationNumberInput.value = "OTC";

            // set input with name "poly_ruang" value to "RADIOLOGI"
            const polyRuangInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='poly_ruang']"));
            if (polyRuangInput) polyRuangInput.value = "RADIOLOGI";

            // disable pilih pasien button
            if (this.#pilihPasienButton)
                this.#pilihPasienButton.disabled = true;
        } else {
            // disable all disabled inputs
            this.#initialDisabledInputs.forEach((input) => {
                input.disabled = true;
            });

            // enable pilih pasien button
            if (this.#pilihPasienButton)
                this.#pilihPasienButton.disabled = false;
        }
    }

    /**
     * Handle select parameter changes
     * @param {Event} event 
     */
    #selectParameterChange(event) {
        const select = /** @type {HTMLSelectElement | null} */ (event.target);
        if (!select) return;

        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            this.#insertParameter(selectedOption);
        }
    }

    /**
     * Insert parameter to active parameter div
     * @param {HTMLOptionElement} option 
     */
    #insertParameter(option) {
        // push to array
        // get html object
        this.#selectedParameters.push({ id: parseInt(option.value), qty: 1 });
        const html = this.#parameterBox(option, this.#selectedParameters.length - 1);

        // insert to active parameter div
        if (this.#activeParameterDiv) {
            this.#activeParameterDiv.insertAdjacentHTML('beforeend', html);

            // listen to quantity number changes
            const numberInputs = document.querySelectorAll("input[type='number']#jumlah_" + option.value);
            numberInputs.forEach((input) => {
                input.addEventListener("input", this.#handleNumberChange.bind(this));
            });

            //  recalculate cost
            this.#calculateCost();
            $('#select_parameter').val('').trigger('change'); // Reset the Select2 dropdown
        } else {
            console.error(new Error('Active parameter div not found'));
        }
    }

    /**
     * Get parameter box html string
     * @param {HTMLOptionElement} option 
     * @param {number} index
     */
    #parameterBox(option, index) {
        return /*html*/`
            <h5 class="active_parameter" id="parameter_box_${option.value}">
                <a class="mdi mdi-close pointer mdi-24px text-danger" onclick="OrderRadiologiClass.removeParameter(${option.value}, ${index})" title="Hapus Parameter"></a>
                <input type="number" value="1" class="parameter_radiologi_number" id="jumlah_${option.value}" index="${index}">
                    ${option.text}
            </h5>
        `;
    }

    /**
     * Remove parameter
     * @param {number} id 
     * @param {number} index
     */
    removeParameter(id, index) {
        const box = document.getElementById(`parameter_box_${id}`);
        if (!box) return;

        // remove from array
        this.#selectedParameters.splice(index, 1);

        // remove html object
        // recalculate cost
        box.remove();
        this.#calculateCost();
    }

    /**
     * Handles number input changes
     * @param {Event} event
     */
    #handleNumberChange(event) {
        const _target = event.target;
        if (!_target) return;

        const input = /** @type {HTMLInputElement} */ (_target);
        let qty = parseInt(input.value);

        if (qty < 1) {
            input.value = "1";
            qty = 1;
        }

        // get attribute "index"
        const index = input.getAttribute("index");
        if (!index) return console.error("No index found");
        this.#selectedParameters[index].qty = qty;

        this.#calculateCost();
    }

    /**
     * Handle radio order type changes
     * @param {Event} event 
     */
    #orderTypeChange(event) {
        const _target = event.target;
        if (!_target) return;

        const radio = /** @type {HTMLInputElement} */ (_target);
        let type = radio.value;
        this.#CITO = type == 'cito' ? true : false;
        this.#calculateCost();
    }

    #calculateCost() {
        this.#totalHarga = 0;

        for (let i = 0; i < this.#selectedParameters.length; i++) {
            const parameter = this.#selectedParameters[i];
            const Tarif = this.#TarifRadiologi.find((t) => {
                const EqualParameterId = t.parameter_radiologi_id == parameter.id;
                let EqualKelasRawatId = true;
                let EqualGroupPenjaminId = true;

                if (this.#Registration) {
                    console.log("Calculating cost with registration")
                    EqualKelasRawatId = this.#Registration.registration_type == "rawat-jalan" ? true : (t.kelas_rawat_id == (this.#Registration.kelas_rawat_id ?? -1));

                    // get "group_penjamin_id" which is in Penjamin object
                    // with id equals to "penjamin_id" which is in Registration object
                    const Penjamin = this.#Penjamins.find((p) => p.id == this.#Registration?.penjamin_id);
                    if (Penjamin) {
                        EqualGroupPenjaminId = t.group_penjamin_id == Penjamin.group_penjamin_id;
                    }

                } else {
                    console.log("Calculating cost without registration")
                }
                if (EqualParameterId && EqualKelasRawatId && EqualGroupPenjaminId) return t;
            });
            if (!Tarif) {
                return showErrorAlertNoRefresh('Tarif tidak ditemukan! Mohon laporkan ke managemen. Parameter id: ' + parameter.id);
            }
            let Price = Tarif.total * parameter.qty;
            if (this.#CITO) {
                Price += (Price * 30 / 100);
            }
            this.#totalHarga += Price;
        }

        if (this.#elementHarga) {
            this.#elementHarga.textContent = this.#totalHarga.toLocaleString("id-ID", {
                style: "currency",
                currency: "IDR",
            });
        }

        return this.#totalHarga;
    }

    /**
     * Submit form
     * @param {Event} event 
     */
    #submit(event) {
        event.preventDefault();
        const formData = new FormData(this.#elementForm);
        if (!this.#Registration) {
            if (this.#patienType != "otc") {
                return showErrorAlertNoRefresh("Silahkan pilih pasien terlebih dahulu!");
            } else { // otc
                formData.append('is_otc', '1');
            }
        } else {
            formData.append('registration_id', String(this.#Registration.id));
            formData.append('registration_type', this.#Registration.registration_type);
        }

        // Append user_id and employee_id to formData
        // const userIdInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='user_id']"));
        // if (userIdInput) {
        //     formData.append('user_id', userIdInput.value);
        // }

        // const employeeIdInput = /** @type {HTMLInputElement} */ (document.querySelector("input[name='employee_id']"));
        // if (employeeIdInput) {
        //     formData.append('employee_id', employeeIdInput.value);
        // }


        // get parameters
        /**
         * @typedef {{ 
         * id: number
         * qty: number
         * price: number
         *  }} Parameter
         */
        let parameters = /** @type {Parameter[]} */ ([]);
        for (let i = 0; i < this.#selectedParameters.length; i++) {
            const parameter = this.#selectedParameters[i];
            const Tarif = this.#TarifRadiologi.find((t) => {
                const EqualParameterId = t.parameter_radiologi_id == parameter.id;
                let EqualKelasRawatId = true;
                let EqualGroupPenjaminId = true;

                if (this.#Registration) {
                    EqualKelasRawatId = this.#Registration.registration_type == "rawat-jalan" ? true : (t.kelas_rawat_id == (this.#Registration.kelas_rawat_id ?? -1));

                    // get "group_penjamin_id" which is in Penjamin object
                    // with id equals to "penjamin_id" which is in Registration object
                    const Penjamin = this.#Penjamins.find((p) => p.id == this.#Registration?.penjamin_id);
                    if (Penjamin) {
                        EqualGroupPenjaminId = t.group_penjamin_id == Penjamin.group_penjamin_id;
                    }

                }

                if (
                    EqualParameterId && EqualKelasRawatId && EqualGroupPenjaminId
                ) return t;
            });
            if (!Tarif) {
                return showErrorAlertNoRefresh('Tarif tidak ditemukan! Mohon laporkan ke managemen. Parameter id: ' + parameter.id);
            }
            let Price = Tarif.total;
            if (this.#CITO) {
                Price += (Price * 30 / 100);
            }
            parameters.push({ id: parameter.id, qty: parameter.qty, price: Price });
        }
        formData.append('parameters', JSON.stringify(parameters));

        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // @ts-ignore
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch('/api/simrs/order-radiologi', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        })
            .then(response => response.json())
            .then(async (data) => {
                console.log(data);
                if (!data.success) {
                    throw new Error(data.errors);
                }
                showSuccessAlert('Data berhasil disimpan');
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }
}

const OrderRadiologiClass = new OrderRadiologi();