// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const ParameterRadiologi = /** @type {ParameterRadiologi[]} */ (window._parameterRadiologi);

// @ts-ignore
const TarifRadiologi = /** @type {TarifRadiologi[]} */ (window._tarifRadiologi);

// @ts-ignore
const Registration = /** @type {Registration} */ (window._registration);

let totalHarga = 0;
/**
 * @type {HTMLElement | undefined}
 */
let elementHarga = undefined;

/**
 * @type {HTMLFormElement | undefined}
 */
let elementForm = undefined;

let CITO = false;

document.addEventListener("DOMContentLoaded", function () {

    // Select all checkboxes inside the Blade-generated form
    const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", handleCheckboxChange);
    });

    // Select all number input fields
    const numberInputs = document.querySelectorAll("input[type='number'].parameter_radiologi_number");
    numberInputs.forEach((input) => {
        input.addEventListener("input", handleNumberChange);
    });

    // Search bar
    const searchBar = document.getElementById("searchRadiology");
    if (!searchBar) return;
    searchBar.addEventListener("keyup", handleSearchBarChange);

    // Harga
    elementHarga = document.getElementById("radiologi-total");
    if (!elementHarga) return;

    // Order Type Radio
    const orderType = document.querySelectorAll("input[type='radio'][name='order_type']");
    if (orderType) {
        orderType.forEach((radio) => {
            radio.addEventListener("change", orderTypeChange);
        });
    }

    // Form
    const form = document.querySelector("form#form-radiologi");
    if (form) {
        elementForm = /** @type {HTMLFormElement} */ (form);
        form.addEventListener("submit", submit);
    }
});

/**
 * Handle radio order type changes
 * @param {Event} event 
 */
function orderTypeChange(event) {
    const _target = event.target;
    if (!_target) return;

    const radio = /** @type {HTMLInputElement} */ (_target);
    let type = radio.value;
    CITO = type == 'cito' ? true : false;
    calculateCost();
}

/**
 * Submit form
 * @param {Event} event 
 */
function submit(event) {
    event.preventDefault();
    const formData = new FormData(elementForm);

    // get parameters
    /**
     * @typedef {{ 
     * id: number
     * qty: number
     * price: number
     *  }} Parameter
     */
    let parameters = /** @type {Parameter[]} */ ([]);
    const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
    checkboxes.forEach((_checkbox) => {
        const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
        const isChecked = checkbox.checked;
        const parameterId = parseInt(checkbox.value);
        if (isChecked) {
            const QtyElement = /** @type {HTMLInputElement} */ (document.querySelector("input#jumlah_" + parameterId));
            const Qty = parseInt(QtyElement.value);
            const Tarif = TarifRadiologi.find((t) => t.parameter_radiologi_id == parameterId);
            let Price = Tarif.total * Qty;
            if (CITO) {
                Price += (Price * 30 / 100);
            }

            parameters.push({ id: parameterId, qty: Qty, price: Price });
        }
    })
    formData.append('parameters', JSON.stringify(parameters));
    formData.append('registration_id', String(Registration.id));

    fetch('/api/simrs/order-radiologi', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': String(formData.get("_token"))
        }
    })
        .then(data => {
            console.log('Success:', data);
            showSuccessAlert('Data berhasil disimpan');
            setTimeout(() => window.location.reload(), 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlertNoRefresh(`Error: ${error}`);
        });
}

function calculateCost() {
    totalHarga = 0;
    const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
    checkboxes.forEach((_checkbox) => {
        const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
        const isChecked = checkbox.checked;
        const parameterId = checkbox.value;
        const parameter = ParameterRadiologi.find((p) => p.id == parseInt(parameterId));

        if (isChecked && parameter) {
            const tarif = TarifRadiologi.find((t) => t.parameter_radiologi_id == parameter.id);
            if (tarif) {
                const jumlah = /** @type {HTMLInputElement} */ (document.querySelector(`input[id='jumlah_${parameter.id}']`));
                if (parseInt(jumlah.value) < 1) {
                    jumlah.value = String(1);
                }
                totalHarga += tarif.total * parseInt(jumlah.value);

                if (CITO) {
                    totalHarga += (totalHarga * 30 / 100);
                }
            }
        }
    });

    if (elementHarga) {
        elementHarga.textContent = totalHarga.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
    }
}

/**
 * Handle search bar changes
 * @param {Event} event 
 */
function handleSearchBarChange(event) {
    const _target = event.target;
    if (!_target) return;

    const searchBar = /** @type {HTMLInputElement} */ (_target);
    const searchQuery = searchBar.value.toLowerCase();
    if (searchQuery == "") {
        showAllParameters();
        return;
    }

    const parameters = document.querySelectorAll(".parameter_radiologi");
    parameters.forEach((parameter) => {
        const parameterName = parameter.querySelector(".form-check-label").textContent.toLowerCase();
        if (parameterName.includes(searchQuery)) {
            // @ts-ignore
            parameter.style.display = "block";
        } else {
            // @ts-ignore
            parameter.style.display = "none";
        }
    });
}

function showAllParameters() {
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
function handleCheckboxChange(event) {
    const _target = event.target;
    if (!_target) return;
    calculateCost();
}

/**
 * Handles number input changes
 * @param {Event} event
 */
function handleNumberChange(event) {
    const _target = event.target;
    if (!_target) return;
    calculateCost();
}
