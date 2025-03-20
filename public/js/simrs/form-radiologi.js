// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class RadiologiForm {
    /**
     * @type {ParameterRadiologi[]}
     */
    #ParameterRadiologi;

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

        // Form
        const form = document.querySelector("form#form-radiologi");
        if (form) {
            this.#elementForm = /** @type {HTMLFormElement} */ (form);
            form.addEventListener("submit", this.#submit.bind(this));
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
        const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
        checkboxes.forEach((_checkbox) => {
            const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
            const isChecked = checkbox.checked;
            const parameterId = parseInt(checkbox.value);
            if (isChecked) {
                const QtyElement = /** @type {HTMLInputElement} */ (document.querySelector("input#jumlah_" + parameterId));
                const Qty = parseInt(QtyElement.value);
                const Tarif = this.#TarifRadiologi.find((t) => t.parameter_radiologi_id == parameterId);
                if (!Tarif) {
                    return showErrorAlertNoRefresh('Tarif tidak ditemukan! Mohon laporkan ke managemen. Parameter id: ' + parameterId);
                }
                let Price = Tarif.total;
                if (this.#CITO) {
                    Price += (Price * 30 / 100);
                }

                parameters.push({ id: parameterId, qty: Qty, price: Price });
            }
        })
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
        const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
        checkboxes.forEach((_checkbox) => {
            const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
            const isChecked = checkbox.checked;
            const parameterId = checkbox.value;
            const parameter = this.#ParameterRadiologi.find((p) => p.id == parseInt(parameterId));

            if (isChecked && parameter) {
                const tarif = this.#TarifRadiologi.find((t) => {
                    if (
                        t.parameter_radiologi_id == parameter.id
                        &&
                        t.kelas_rawat_id == (this.#Registration.kelas_rawat_id ?? -1)
                        &&
                        t.group_penjamin_id == (this.#GroupPenjaminId ?? -1)
                    ) return t;
                });

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
                else {
                    showErrorAlertNoRefresh("Tarif untuk penjamin dan kelas rawat ini tidak ditemukan!");
                    return;
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

const RadiologiFormClass = new RadiologiForm();

// // @ts-check
// /// <reference types="jquery" />
// /// <reference path="../types.d.ts" />

// // @ts-ignore
// const ParameterRadiologi = /** @type {ParameterRadiologi[]} */ (window._parameterRadiologi);

// // @ts-ignore
// const TarifRadiologi = /** @type {TarifRadiologi[]} */ (window._tarifRadiologi);

// // @ts-ignore
// const Registration = /** @type {Registration} */ (window._registration);

// let totalHarga = 0;
// /**
//  * @type {HTMLElement | undefined}
//  */
// let elementHarga = undefined;

// /**
//  * @type {HTMLFormElement | undefined}
//  */
// let elementForm = undefined;

// let CITO = false;

// document.addEventListener("DOMContentLoaded", function () {

//     // Select all checkboxes inside the Blade-generated form
//     const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");

//     checkboxes.forEach((checkbox) => {
//         checkbox.addEventListener("change", handleCheckboxChange);
//     });

//     // Select all number input fields
//     const numberInputs = document.querySelectorAll("input[type='number'].parameter_radiologi_number");
//     numberInputs.forEach((input) => {
//         input.addEventListener("input", handleNumberChange);
//     });

//     // Search bar
//     const searchBar = document.getElementById("searchRadiology");
//     if (!searchBar) return;
//     searchBar.addEventListener("keyup", handleSearchBarChange);

//     // Harga
//     elementHarga = document.getElementById("radiologi-total") || undefined;

//     // Order Type Radio
//     const orderType = document.querySelectorAll("input[type='radio'][name='order_type']");
//     if (orderType) {
//         orderType.forEach((radio) => {
//             radio.addEventListener("change", orderTypeChange);
//         });
//     }

//     // Form
//     const form = document.querySelector("form#form-radiologi");
//     if (form) {
//         elementForm = /** @type {HTMLFormElement} */ (form);
//         form.addEventListener("submit", submit);
//     }
// });

// /**
//  * Handle radio order type changes
//  * @param {Event} event 
//  */
// function orderTypeChange(event) {
//     const _target = event.target;
//     if (!_target) return;

//     const radio = /** @type {HTMLInputElement} */ (_target);
//     let type = radio.value;
//     CITO = type == 'cito' ? true : false;
//     calculateCost();
// }

// /**
//  * Submit form
//  * @param {Event} event 
//  */
// function submit(event) {
//     event.preventDefault();
//     const formData = new FormData(elementForm);

//     // get parameters
//     /**
//      * @typedef {{ 
//      * id: number
//      * qty: number
//      * price: number
//      *  }} Parameter
//      */
//     let parameters = /** @type {Parameter[]} */ ([]);
//     const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
//     checkboxes.forEach((_checkbox) => {
//         const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
//         const isChecked = checkbox.checked;
//         const parameterId = parseInt(checkbox.value);
//         if (isChecked) {
//             const QtyElement = /** @type {HTMLInputElement} */ (document.querySelector("input#jumlah_" + parameterId));
//             const Qty = parseInt(QtyElement.value);
//             const Tarif = TarifRadiologi.find((t) => t.parameter_radiologi_id == parameterId);
//             if (!Tarif) {
//                 return showErrorAlertNoRefresh('Tarif tidak ditemukan! Mohon laporkan ke managemen. Parameter id: ' + parameterId);
//             }
//             let Price = Tarif.total;
//             if (CITO) {
//                 Price += (Price * 30 / 100);
//             }

//             parameters.push({ id: parameterId, qty: Qty, price: Price });
//         }
//     })
//     formData.append('parameters', JSON.stringify(parameters));
//     formData.append('registration_id', String(Registration.id));

//     fetch('/api/simrs/order-radiologi', {
//         method: 'POST',
//         body: formData,
//         headers: {
//             'X-CSRF-TOKEN': String(formData.get("_token"))
//         }
//     })
//         .then(async (data) => {
//             console.log(data.url);
//             console.log(await data.text())
//             if (data.status != 200) {
//                 throw new Error('Error: ' + data.statusText);
//             }
//             showSuccessAlert('Data berhasil disimpan');
//             setTimeout(() => window.location.reload(), 2000);
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             showErrorAlertNoRefresh(`Error: ${error}`);
//         });
// }

// function calculateCost() {
//     totalHarga = 0;
//     const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
//     checkboxes.forEach((_checkbox) => {
//         const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
//         const isChecked = checkbox.checked;
//         const parameterId = checkbox.value;
//         const parameter = ParameterRadiologi.find((p) => p.id == parseInt(parameterId));

//         if (isChecked && parameter) {
//             const tarif = TarifRadiologi.find((t) => t.parameter_radiologi_id == parameter.id);
//             if (tarif) {
//                 const jumlah = /** @type {HTMLInputElement} */ (document.querySelector(`input[id='jumlah_${parameter.id}']`));
//                 if (parseInt(jumlah.value) < 1) {
//                     jumlah.value = String(1);
//                 }
//                 totalHarga += tarif.total * parseInt(jumlah.value);

//                 if (CITO) {
//                     totalHarga += (totalHarga * 30 / 100);
//                 }
//             }
//         }
//     });

//     if (elementHarga) {
//         elementHarga.textContent = totalHarga.toLocaleString("id-ID", {
//             style: "currency",
//             currency: "IDR",
//         });
//     }
// }

// /**
//  * Handle search bar changes
//  * @param {Event} event 
//  */
// function handleSearchBarChange(event) {
//     const _target = event.target;
//     if (!_target) return;

//     const searchBar = /** @type {HTMLInputElement} */ (_target);
//     const searchQuery = searchBar.value.toLowerCase();
//     if (searchQuery == "") {
//         showAllParameters();
//         return;
//     }

//     const parameters = document.querySelectorAll(".parameter_radiologi");
//     parameters.forEach((parameter) => {
//         const parameterNameElement = parameter.querySelector(".form-check-label");
//         if (!parameterNameElement) return;
//         const parameterName = parameterNameElement.textContent;
//         if (!parameterName) return;

//         if (parameterName.toLowerCase().includes(searchQuery)) {
//             // @ts-ignore
//             parameter.style.display = "block";
//         } else {
//             // @ts-ignore
//             parameter.style.display = "none";
//         }
//     });
// }

// function showAllParameters() {
//     const parameters = document.querySelectorAll(".parameter_radiologi");
//     parameters.forEach((parameter) => {
//         // @ts-ignore
//         parameter.style.display = "block";
//     });
// }

// /**
//  * Handles checkbox state changes
//  * @param {Event} event
//  */
// function handleCheckboxChange(event) {
//     const _target = event.target;
//     if (!_target) return;
//     calculateCost();
// }

// /**
//  * Handles number input changes
//  * @param {Event} event
//  */
// function handleNumberChange(event) {
//     const _target = event.target;
//     if (!_target) return;
//     calculateCost();
// }
