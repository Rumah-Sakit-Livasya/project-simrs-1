// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class RadiologiRegistrasiForm {
    /**
     * @type {KategoriRadiologi[]}
     */
    #KategoriRadiologi;

    /**
     * @type {number}
     */
    #GroupPenjaminId;

    /**
     * @type {TarifRadiologi[]}
     */
    #TarifRadiologi;

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
        this.#KategoriRadiologi = window._kategoriRadiologi;
        // @ts-ignore
        this.#TarifRadiologi = window._tarifRadiologi;
        // @ts-ignore
        this.#GroupPenjaminId = window._groupPenjaminId;

        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
    }

    #init() {
        // Harga
        this.#elementHarga =
            document.getElementById("radiologi-total") || undefined;

        // Order Type Radio
        const orderType = document.querySelectorAll(
            "input[type='radio'][name='order_type']"
        );
        if (orderType) {
            orderType.forEach((radio) => {
                radio.addEventListener(
                    "change",
                    this.#orderTypeChange.bind(this)
                );
            });
        }

        // Form
        const form = document.querySelector("form#form-radiologi");
        if (form) {
            this.#elementForm = /** @type {HTMLFormElement} */ (form);
            form.addEventListener("submit", this.#submit.bind(this));
        }

        // Select all checkboxes inside the Blade-generated form
        const checkboxes = document.querySelectorAll(
            "input[type='checkbox'].parameter_radiologi_checkbox"
        );
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener(
                "change",
                this.#handleCheckboxChange.bind(this)
            );
        });

        // Select all number input fields
        const numberInputs = document.querySelectorAll(
            "input[type='number'].parameter_radiologi_number"
        );
        numberInputs.forEach((input) => {
            input.addEventListener(
                "input",
                this.#handleNumberChange.bind(this)
            );
        });

        // Search bar
        const searchBar = document.getElementById("searchRadiology");
        if (searchBar) {
            searchBar.addEventListener(
                "keyup",
                this.#handleSearchBarChange.bind(this)
            );
        }

        this.#updateCost();
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
            const parameterNameElement =
                parameter.querySelector(".form-check-label");
            if (!parameterNameElement) return;
            const parameterName = parameterNameElement.textContent;
            if (!parameterName) return;

            if (parameterName.toLowerCase().includes(searchQuery)) {
                // @ts-ignore
                parameter.style.display = "inherit";
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
            parameter.style.display = "inherit";
        });
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
        this.#CITO = type == "cito" ? true : false;
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
        const checkboxes = document.querySelectorAll(
            "input[type='checkbox'].parameter_radiologi_checkbox"
        );
        checkboxes.forEach((_checkbox) => {
            const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
            const isChecked = checkbox.checked;
            const parameterId = checkbox.value;
            /** @type {ParameterRadiologi | undefined} */
            let parameter;

            for (const nama_kategori in this.#KategoriRadiologi) {
                const parameters =
                    this.#KategoriRadiologi[nama_kategori].parameter_radiologi;
                parameter = parameters.find(
                    (p) => p.id == parseInt(parameterId)
                );
            }

            if (isChecked && parameter) {
                const Tarif = this.#TarifRadiologi.find(
                    (t) => t.parameter_radiologi_id == parameter.id
                );
                if (!Tarif) {
                    return showErrorAlertNoRefresh(
                        "Tarif tidak ditemukan! Mohon laporkan ke managemen. Parameter id: " +
                            parameter.id
                    );
                }
                let Price = Tarif.total;
                if (this.#CITO) {
                    Price += (Price * 30) / 100;
                }

                const jumlah = /** @type {HTMLInputElement} */ (
                    document.querySelector(`input[id='jumlah_${parameter.id}']`)
                );
                if (parseInt(jumlah.value) < 1) {
                    jumlah.value = String(1);
                }
                parameters.push({
                    id: parameter.id,
                    qty: parseInt(jumlah.value),
                    price: Price,
                });
            }
        });
        formData.append("parameters", JSON.stringify(parameters));

        // log all formdata attributes to console
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        fetch("/patients/simpan/registrasi", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": String(formData.get("_token")),
            },
        })
            .then(async (data) => {
                console.log(data.url);
                console.log(await data.text());
                if (data.status != 200) {
                    throw new Error("Error: " + data.statusText);
                }

                // the last path of data.url is the newly created registration id
                const id = parseInt(data.url.split("/").pop() || "0");

                this.#submit2(formData, id);
            })
            .catch((error) => {
                console.log("Error:", error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }

    /**
     * 2nd submit stage
     * @param {FormData} formData
     * @param {number} registrationId
     */
    #submit2(formData, registrationId) {
        if (!registrationId) {
            return showErrorAlertNoRefresh(
                "Error: Registration ID is not defined or 0"
            );
        }

        formData.append("registration_id", String(registrationId));

        fetch("/api/simrs/order-radiologi", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": String(formData.get("_token")),
            },
        })
            .then(async (data) => {
                console.log(data.url);
                console.log(await data.text());
                if (data.status != 200) {
                    throw new Error("Error: " + data.statusText);
                }

                showSuccessAlert("Data berhasil disimpan");
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch((error) => {
                console.log("Error:", error);
                showErrorAlertNoRefresh(`Error: ${error}`);
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

    #updateCost() {
        for (let i = 0; i < this.#KategoriRadiologi.length; i++) {
            const KategoriRadiologi = this.#KategoriRadiologi[i];
            for (
                let ii = 0;
                ii < KategoriRadiologi.parameter_radiologi.length;
                ii++
            ) {
                const ParameterRadiologi =
                    KategoriRadiologi.parameter_radiologi[ii];

                // get span with id "harga_parameter_radiologi_${ParameterRadiologi.id}"
                const hargaParameterRadiologi = document.getElementById(
                    `harga_parameter_radiologi_${ParameterRadiologi.id}`
                );
                if (hargaParameterRadiologi == null) continue;

                // get tarif from #TarifRadiologi with equal parameter_radiologi_id, group_penjamin_id and kelas_rawat_id
                const tarif = this.#TarifRadiologi.find(
                    (t) => t.parameter_radiologi_id == ParameterRadiologi.id
                );

                if (tarif) {
                    hargaParameterRadiologi.textContent =
                        tarif.total.toLocaleString("id-ID", {
                            style: "currency",
                            currency: "IDR",
                        });
                } else {
                    console.log(
                        "Tarif belum di set atau tidak ditemukan! ID Parameter: " +
                            ParameterRadiologi.id
                    );
                    // showErrorAlertNoRefresh("Tarif tidak ditemukan atau belum di set! Mohon laporkan ke management. Cek log console!");
                }
            }
        }
    }

    #calculateCost() {
        this.#totalHarga = 0;

        const checkboxes = document.querySelectorAll(
            "input[type='checkbox'].parameter_radiologi_checkbox"
        );
        checkboxes.forEach((_checkbox) => {
            const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
            const isChecked = checkbox.checked;
            const parameterId = checkbox.value;
            /** @type {ParameterRadiologi | undefined} */
            let parameter;

            for (const nama_kategori in this.#KategoriRadiologi) {
                const parameters =
                    this.#KategoriRadiologi[nama_kategori].parameter_radiologi;
                parameter = parameters.find(
                    (p) => p.id == parseInt(parameterId)
                );
            }

            if (isChecked && parameter) {
                const Tarif = this.#TarifRadiologi.find(
                    (t) => t.parameter_radiologi_id == parameter.id
                );
                if (!Tarif) {
                    return showErrorAlertNoRefresh(
                        "Tarif tidak ditemukan! Mohon laporkan ke managemen. Parameter id: " +
                            parameter.id
                    );
                }
                const jumlah = /** @type {HTMLInputElement} */ (
                    document.querySelector(`input[id='jumlah_${parameter.id}']`)
                );
                if (parseInt(jumlah.value) < 1) {
                    jumlah.value = String(1);
                }

                let Price = Tarif.total * parseInt(jumlah.value);
                if (this.#CITO) {
                    Price += (Price * 30) / 100;
                }
                this.#totalHarga += Price;
            }
        });

        if (this.#elementHarga) {
            this.#elementHarga.textContent = this.#totalHarga.toLocaleString(
                "id-ID",
                {
                    style: "currency",
                    currency: "IDR",
                }
            );
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
        this.#calculateCost();
    }
}

const RadiologiRegistrasiFormClass = new RadiologiRegistrasiForm();
