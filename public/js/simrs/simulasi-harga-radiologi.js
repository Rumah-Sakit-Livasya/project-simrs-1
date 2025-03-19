// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class SimulasiHargaRadiologi {
    /**
     * @type {ParameterRadiologi[]}
     */
    #ParameterRadiologi;

    /**
     * @type {TarifRadiologi[]}
     */
    #TarifRadiologi;

    #totalHarga = 0;
    /**
     * @type {HTMLElement | undefined}
     */
    #elementHarga = undefined;

    #CITO = false;

    constructor() {
        // @ts-ignore
        this.#ParameterRadiologi = window._parameterRadiologi;
        // @ts-ignore
        this.#TarifRadiologi = window._tarifRadiologi;

        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
    }

    #init() {
        // Select all checkboxes inside the Blade-generated form
        const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", this.#handleCheckboxChange.bind(this));
        });

        // Select all number input fields
        const numberInputs = document.querySelectorAll("input[type='number'].parameter_radiologi_number");
        numberInputs.forEach((input) => {
            input.addEventListener("input", this.#handleNumberChange.bind(this));
        });

        // Search bar
        const searchBar = document.getElementById("searchRadiology");
        if (searchBar) {
            searchBar.addEventListener("keyup", this.#handleSearchBarChange.bind(this));
        }

        // Harga
        this.#elementHarga = document.getElementById("radiologi-total") || undefined;

        // Order Type Radio
        const orderType = document.querySelectorAll("input[type='radio'][name='order_type']");
        if (orderType) {
            orderType.forEach((radio) => {
                radio.addEventListener("change", this.#orderTypeChange.bind(this));
            });
        }
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
        const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
        checkboxes.forEach((_checkbox) => {
            const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
            const isChecked = checkbox.checked;
            const parameterId = checkbox.value;
            const parameter = this.#ParameterRadiologi.find((p) => p.id == parseInt(parameterId));

            if (isChecked && parameter) {
                const tarif = this.#TarifRadiologi.find((t) => t.parameter_radiologi_id == parameter.id);
                if (tarif) {
                    const jumlah = /** @type {HTMLInputElement} */ (document.querySelector(`input[id='jumlah_${parameter.id}']`));
                    if (parseInt(jumlah.value) < 1) {
                        jumlah.value = String(1);
                    }
                    this.#totalHarga += tarif.total * parseInt(jumlah.value);

                    if (this.#CITO) {
                        this.#totalHarga += (this.#totalHarga * 30 / 100);
                    }
                }
            }
        });

        if (this.#elementHarga) {
            this.#elementHarga.textContent = this.#totalHarga.toLocaleString("id-ID", {
                style: "currency",
                currency: "IDR",
            });
        }
    }

    /**
     * Handle search bar changes
     * @param {Event} event 
     */
    #handleSearchBarChange(event) {
        const _target = event.target;
        if (!_target) return;

        const searchBar = /** @type {HTMLInputElement} */ (_target);
        const searchQuery = searchBar.value.toLowerCase();
        if (searchQuery == "") {
            this.#showAllParameters();
            return;
        }

        const parameters = document.querySelectorAll(".parameter_radiologi");
        parameters.forEach((parameter) => {
            const parameterNameElement = parameter.querySelector(".form-check-label");
            if (!parameterNameElement) return;
            const parameterName = parameterNameElement.textContent;
            if (!parameterName) return;

            if (parameterName.toLowerCase().includes(searchQuery)) {
                // @ts-ignore
                parameter.style.display = "block";
            } else {
                // @ts-ignore
                parameter.style.display = "none";
            }
        });
    }

    #showAllParameters() {
        const parameters = document.querySelectorAll(".parameter_radiologi");
        parameters.forEach((parameter) => {
            // @ts-ignore
            parameter.style.display = "block";
        });
    }

    /**
     * Handles checkbox state changes
     * @param {Event} event
     */
    #handleCheckboxChange(event) {
        const _target = event.target;
        if (!_target) return;
        this.#calculateCost();
    }

    /**
     * Handles number input changes
     * @param {Event} event
     */
    #handleNumberChange(event) {
        const _target = event.target;
        if (!_target) return;
        this.#calculateCost();
    }
}

const SimulasiHargaRadiologiClass = new SimulasiHargaRadiologi();