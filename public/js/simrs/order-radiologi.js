// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class OrderRadiologi {
    /**
     * @type {KategoriRadiologi[]}
     */
    #KategoriRadiologi;

    /**
     * @type {KelasRawat[]}
     */
    #KelasRawat;

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

    #groupTarif = 1;

    #kelasPerawatan = 1;

    /**
     * @type {HTMLElement | undefined}
     */
    #elementHarga = undefined;

    /**
     * @type {HTMLFormElement | undefined}
     */
    #elementForm = undefined;

    /**
     * @type {HTMLInputElement[]}
     */
    #initialDisabledInputs = [];

    /**
     * @type {HTMLButtonElement | undefined}
     */
    #pilihPasienButton;

    /**
     * @type {PatientType}
     */
    #patienType = "rajal";

    #CITO = false;

    constructor() {
        // @ts-ignore
        this.#KategoriRadiologi = window._kategoriRadiologi;
        // @ts-ignore
        this.#TarifRadiologi = window._tarifRadiologi;
        // @ts-ignore
        this.#Penjamins = window._penjamins;
        // @ts-ignore
        this.#KelasRawat = window._kelasRawats;

        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
    }

    #init() {
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
        const form = document.querySelector("form[name='form-radiologi']");
        if (form) {
            this.#elementForm = /** @type {HTMLFormElement} */ (form);
            form.addEventListener("submit", this.#submit.bind(this));
        } else {
            alert("FORM NOT FOUND");
        }

        // submit button
        const submitButton = document.querySelector("button.submit-btn");
        if (submitButton) {
            submitButton.addEventListener("click", this.#submit.bind(this));
        }

        // get all disabled inputs
        const disabledInputs = document.querySelectorAll("input:disabled");
        for (const input of disabledInputs) {
            this.#initialDisabledInputs.push(
                /** @type {HTMLInputElement} */ (input)
            );
        }

        // get tipe_pasien select
        const tipePasienSelect = document.querySelector("select#tipe_pasien");
        if (tipePasienSelect) {
            // listen to change
            tipePasienSelect.addEventListener(
                "change",
                this.#selectTipePasienChange.bind(this)
            );
        }

        // get pilih passien button
        const pilihPasienButton = document.querySelector(
            "button#pilih-pasien-btn"
        );
        if (pilihPasienButton) {
            // listen to click
            this.#pilihPasienButton = /** @type {HTMLButtonElement} */ (
                pilihPasienButton
            );

            // listen to click
            pilihPasienButton.addEventListener(
                "click",
                this.#handlePilihPasienButtonClick.bind(this)
            );
        }

        window.addEventListener("message", (event) => {
            console.log("Receiving message from popup", event.data);

            this.changeRegistration(event.data.data);
        });

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
        let popup = window.open(
            "popup/pilih-pasien/" + this.#patienType,
            "popupPilihPasien",
            `width=${screen.width},height=${screen.height},top=0,left=0`
        );
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

        // get Penjamin object from Penjamins with id equals to registration.penjamin_id
        const Penjamin = this.#Penjamins.find(
            (p) => p.id == registration.penjamin_id
        );
        if (Penjamin) {
            this.#groupTarif = Penjamin.group_penjamin_id;
        }

        this.#kelasPerawatan = registration.kelas_rawat_id
            ? parseInt(registration.kelas_rawat_id)
            : 1;

        // change input with name "nama_pasien"
        const namaPasienInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='nama_pasien']")
        );
        if (namaPasienInput) {
            namaPasienInput.value =
                /** @type {HTMLInputElement} */ registration.patient.name;
        }

        // change input with name "date_of_birth"
        const dateOfBirthInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='date_of_birth']")
        );
        if (dateOfBirthInput) {
            dateOfBirthInput.value = registration.patient.date_of_birth;
        }

        // change input with name "poly_ruang"
        const polyRuangInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='poly_ruang']")
        );
        if (polyRuangInput) {
            polyRuangInput.value = registration.departement.name;
        }

        // change input with name "alamat"
        const alamatInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='alamat']")
        );
        if (alamatInput) {
            alamatInput.value = registration.patient.address;
        }

        // change input with name "no_telp"
        const noTelpInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='no_telp']")
        );
        if (noTelpInput) {
            noTelpInput.value = registration.patient.mobile_phone_number;
        }

        // change input with name "medical_record_number"
        const medicalRecordNumberInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='medical_record_number']")
        );
        if (medicalRecordNumberInput) {
            medicalRecordNumberInput.value =
                registration.patient.medical_record_number;
        }

        // change input with name "registration_number"
        const registrationNumberInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='registration_number']")
        );
        if (registrationNumberInput) {
            registrationNumberInput.value = registration.registration_number;
        }

        // change input with name "mrn_registration_number"
        const mrnRegistrationNumberInput = /** @type {HTMLInputElement} */ (
            document.querySelector("input[name='mrn_registration_number']")
        );
        if (mrnRegistrationNumberInput) {
            mrnRegistrationNumberInput.value = `${registration.patient.medical_record_number} / ${registration.registration_number}`;
        }

        // change radio with name "jenis_kelamin"
        const jenisKelaminInput = /** @type {HTMLInputElement} */ (
            document.querySelector(
                `input[id='gender_${
                    registration.patient.gender == "Laki-laki"
                        ? "male"
                        : "female"
                }']`
            )
        );
        if (jenisKelaminInput) {
            // select the radio
            jenisKelaminInput.checked = true;
        }

        // recalculate cost
        this.#calculateCost();
    }

    #clearInputs() {
        this.#Registration = undefined;
        this.#groupTarif = 1;
        this.#kelasPerawatan = 1;
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

        this.#patienType = /** @type {PatientType} */ (select.value);

        if (select.value == "otc") {
            // enable all disabled inputs except "order_date", "mrn_registration_number", and "poly_ruang"
            this.#initialDisabledInputs.forEach((input) => {
                if (
                    input.name != "order_date" &&
                    input.name != "mrn_registration_number" &&
                    input.name != "poly_ruang"
                ) {
                    input.disabled = false;
                }
            });

            // set input with name "mrn_registration_number" value to "OTC"
            const mrnRegistrationNumberInput = /** @type {HTMLInputElement} */ (
                document.querySelector("input[name='mrn_registration_number']")
            );
            if (mrnRegistrationNumberInput)
                mrnRegistrationNumberInput.value = "OTC";

            // set input with name "poly_ruang" value to "RADIOLOGI"
            const polyRuangInput = /** @type {HTMLInputElement} */ (
                document.querySelector("input[name='poly_ruang']")
            );
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
     * Handles number input changes
     * @param {Event} event
     */
    #handleNumberChange(event) {
        const _target = event.target;
        if (!_target) return;
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
        this.#CITO = type == "cito" ? true : false;
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
                const tarif = this.#TarifRadiologi.find((t) => {
                    if (
                        t.parameter_radiologi_id == ParameterRadiologi.id &&
                        t.group_penjamin_id == this.#groupTarif &&
                        t.kelas_rawat_id == this.#kelasPerawatan
                    )
                        return t;
                });

                if (tarif) {
                    hargaParameterRadiologi.textContent =
                        tarif.total.toLocaleString("id-ID", {
                            style: "currency",
                            currency: "IDR",
                        });
                } else {
                    console.error(
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
                const Tarif = this.#TarifRadiologi.find((t) => {
                    const EqualParameterId =
                        t.parameter_radiologi_id == parameter.id;
                    let EqualKelasRawatId = true;
                    let EqualGroupPenjaminId = true;
                    const kelasRajal = this.#KelasRawat.find(
                        (k) => k.kelas.toLowerCase() == "rawat jalan"
                    );
                    if (!kelasRajal)
                        return showErrorAlertNoRefresh(
                            "Kelas rawat jalan tidak ditemukan!"
                        );

                    if (this.#Registration) {
                        console.log("Calculating cost with registration");
                        EqualKelasRawatId =
                            this.#Registration.registration_type ==
                            "rawat-jalan"
                                ? t.kelas_rawat_id == kelasRajal.id
                                : t.kelas_rawat_id ==
                                  (this.#Registration.kelas_rawat_id ?? -1);

                        // get "group_penjamin_id" which is in Penjamin object
                        // with id equals to "penjamin_id" which is in Registration object
                        const Penjamin = this.#Penjamins.find(
                            (p) => p.id == this.#Registration?.penjamin_id
                        );
                        if (Penjamin) {
                            EqualGroupPenjaminId =
                                t.group_penjamin_id ==
                                Penjamin.group_penjamin_id;
                        }
                    }
                    if (
                        EqualParameterId &&
                        EqualKelasRawatId &&
                        EqualGroupPenjaminId
                    )
                        return t;
                });

                if (!Tarif) {
                    console.error(
                        "Tarif belum di set atau tidak ditemukan! ID Parameter: " +
                            parameter.id
                    );
                    // showErrorAlertNoRefresh("Tarif tidak ditemukan atau belum di set! Mohon laporkan ke management. Cek log console!");
                } else {
                    const jumlah = /** @type {HTMLInputElement} */ (
                        document.querySelector(
                            `input[id='jumlah_${parameter.id}']`
                        )
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
     * Handles checkbox state changes
     * @param {Event} event
     */
    #handleCheckboxChange(event) {
        const _target = event.target;
        if (!_target) return;
        this.#calculateCost();
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
                return showErrorAlertNoRefresh(
                    "Silahkan pilih pasien terlebih dahulu!"
                );
            } else {
                // otc
                formData.append("is_otc", "1");
            }
        } else {
            formData.append("registration_id", String(this.#Registration.id));
            formData.append(
                "registration_type",
                this.#Registration.registration_type
            );
        }

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
                const Tarif = this.#TarifRadiologi.find((t) => {
                    const EqualParameterId =
                        t.parameter_radiologi_id == parameter.id;
                    let EqualKelasRawatId = true;
                    let EqualGroupPenjaminId = true;
                    const kelasRajal = this.#KelasRawat.find(
                        (k) => k.kelas.toLowerCase() == "rawat jalan"
                    );
                    if (!kelasRajal)
                        return showErrorAlertNoRefresh(
                            "Kelas rawat jalan tidak ditemukan!"
                        );

                    if (this.#Registration) {
                        console.log("Calculating cost with registration");
                        EqualKelasRawatId =
                            this.#Registration.registration_type ==
                            "rawat-jalan"
                                ? t.kelas_rawat_id == kelasRajal.id
                                : t.kelas_rawat_id ==
                                  (this.#Registration.kelas_rawat_id ?? -1);

                        // get "group_penjamin_id" which is in Penjamin object
                        // with id equals to "penjamin_id" which is in Registration object
                        const Penjamin = this.#Penjamins.find(
                            (p) => p.id == this.#Registration?.penjamin_id
                        );
                        if (Penjamin) {
                            EqualGroupPenjaminId =
                                t.group_penjamin_id ==
                                Penjamin.group_penjamin_id;
                        }
                    }
                    if (
                        EqualParameterId &&
                        EqualKelasRawatId &&
                        EqualGroupPenjaminId
                    )
                        return t;
                });

                if (!Tarif) {
                    console.error(
                        "Tarif belum di set atau tidak ditemukan! ID Parameter: " +
                            parameter.id
                    );
                    // showErrorAlertNoRefresh("Tarif tidak ditemukan atau belum di set! Mohon laporkan ke management. Cek log console!");
                } else {
                    const jumlah = /** @type {HTMLInputElement} */ (
                        document.querySelector(
                            `input[id='jumlah_${parameter.id}']`
                        )
                    );
                    if (parseInt(jumlah.value) < 1) {
                        jumlah.value = String(1);
                    }

                    let Price = Tarif.total;
                    if (this.#CITO) {
                        Price += (Price * 30) / 100;
                    }

                    parameters.push({
                        id: parameter.id,
                        qty: parseInt(jumlah.value),
                        price: Price,
                    });
                }
            }
        });

        formData.append("parameters", JSON.stringify(parameters));

        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // @ts-ignore
        const CSRF_TOKEN = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content;

        fetch("/api/simrs/order-radiologi", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN,
            },
        })
            .then((response) => response.json())
            .then(async (data) => {
                console.log(data);
                if (!data.success) {
                    throw new Error(data.errors);
                }
                showSuccessAlert("Data berhasil disimpan");
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch((error) => {
                console.error("Error:", error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }
}

const OrderRadiologiClass = new OrderRadiologi();
