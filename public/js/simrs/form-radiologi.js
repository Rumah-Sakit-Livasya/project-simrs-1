// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class RadiologiForm {

    /**
    * @type {number}
    */
    #GroupPenjaminId;

    /**
     * @type {TarifRadiologi[]}
     */
    #TarifRadiologi;

    /**
     * @type {Registration}
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

    #CITO = false;

    constructor() {
        // @ts-ignore
        this.#ParameterRadiologi = window._parameterRadiologi;
        // @ts-ignore
        this.#TarifRadiologi = window._tarifRadiologi;
        // @ts-ignore
        this.#Registration = window._registration;
        // @ts-ignore
        this.#GroupPenjaminId = window._groupPenjaminId;

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
        const form = document.querySelector("form#form-radiologi");
        if (form) {
            this.#elementForm = /** @type {HTMLFormElement} */ (form);
            form.addEventListener("submit", this.#submit.bind(this));
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
            <h5 class="form-control active_parameter" id="parameter_box_${option.value}">
                <a class="mdi mdi-close pointer mdi-24px text-danger" onclick="RadiologiFormClass.removeParameter(${option.value}, ${index})" title="Hapus Parameter"></a>
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

    /**
     * Submit form
     * @param {Event} event 
     */
    #submit(event) {
        event.preventDefault();
        const formData = new FormData(this.#elementForm);

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
                const EqualKelasRawatId = this.#Registration.registration_type == "rawat-jalan" ? true : (t.kelas_rawat_id == (this.#Registration.kelas_rawat_id ?? -1));
                const EqualGroupPenjaminId = t.group_penjamin_id == (this.#GroupPenjaminId ?? -1);
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
        formData.append('registration_id', String(this.#Registration.id));

        fetch('/api/simrs/order-radiologi', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': String(formData.get("_token"))
            }
        })
            .then(async (data) => {
                console.log(data.url);
                console.log(await data.text())
                if (data.status != 200) {
                    throw new Error('Error: ' + data.statusText);
                }
                showSuccessAlert('Data berhasil disimpan');
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }

    #calculateCost() {
        this.#totalHarga = 0;

        for (let i = 0; i < this.#selectedParameters.length; i++) {
            const parameter = this.#selectedParameters[i];
            const Tarif = this.#TarifRadiologi.find((t) => {
                const EqualParameterId = t.parameter_radiologi_id == parameter.id;
                const EqualKelasRawatId = this.#Registration.registration_type == "rawat-jalan" ? true : (t.kelas_rawat_id == (this.#Registration.kelas_rawat_id ?? -1));
                const EqualGroupPenjaminId = t.group_penjamin_id == (this.#GroupPenjaminId ?? -1);
                if (
                    EqualParameterId && EqualKelasRawatId && EqualGroupPenjaminId
                ) return t;
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
        if(!index) return console.error("No index found");
        this.#selectedParameters[index].qty = qty;

        this.#calculateCost();
    }
}

const RadiologiFormClass = new RadiologiForm();