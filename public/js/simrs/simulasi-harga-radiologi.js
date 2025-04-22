// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class SimulasiHargaRadiologi {
    /**
     * @type {KategoriRadiologi[]}
     */
    #KategoriRadiologi;

    /**
     * @type {TarifRadiologi[]}
     */
    #TarifRadiologi;

    #groupTarif = 1;

    #kelasPerawatan = 1;

    #totalHarga = 0;
    /**
     * @type {HTMLElement | undefined}
     */
    #elementHarga = undefined;

    #CITO = false;

    constructor() {
        // @ts-ignore
        this.#KategoriRadiologi = window._kategoriRadiologi;
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

        // Group Tarif Select
        const groupSelect = document.getElementById("group_tarif");
        if (groupSelect) {
            groupSelect.addEventListener("change", this.#handleGroupTarifSelectChange.bind(this));
        }

        // Kelas Perawatan Select
        const kelasSelect = document.getElementById("kelas_perawatan");
        if (kelasSelect) {
            kelasSelect.addEventListener("change", this.#handleKelasPerawatanSelectChange.bind(this));
        }

        this.#updateCost();
    }

    /**
    * Handle kelas perawatan select changes
    * @param {Event} event 
    */
    #handleKelasPerawatanSelectChange(event) {
        const _target = event.target;
        if (!_target) return;
        const select = /** @type {HTMLSelectElement} */ (_target);
        const selectedValue = select.value;
        this.#kelasPerawatan = parseInt(selectedValue);
        this.#updateCost();
        this.#calculateCost();
    }

    /**
    * Handle group select changes
    * @param {Event} event 
    */
    #handleGroupTarifSelectChange(event) {
        const _target = event.target;
        if (!_target) return;
        const select = /** @type {HTMLSelectElement} */ (_target);
        const selectedValue = select.value;
        this.#groupTarif = parseInt(selectedValue);
        this.#updateCost();
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

    #updateCost() {
        for (let i = 0; i < this.#KategoriRadiologi.length; i++) {
            const KategoriRadiologi = this.#KategoriRadiologi[i];
            for (let ii = 0; ii < KategoriRadiologi.parameter_radiologi.length; ii++) {
                const ParameterRadiologi = KategoriRadiologi.parameter_radiologi[ii];

                // get span with id "harga_parameter_radiologi_${ParameterRadiologi.id}"
                const hargaParameterRadiologi = document.getElementById(`harga_parameter_radiologi_${ParameterRadiologi.id}`);
                if (hargaParameterRadiologi == null) continue;

                // get tarif from #TarifRadiologi with equal parameter_radiologi_id, group_penjamin_id and kelas_rawat_id
                const tarif = this.#TarifRadiologi
                    .find((t) => {
                        if (t.parameter_radiologi_id == ParameterRadiologi.id &&
                            t.group_penjamin_id == this.#groupTarif &&
                            t.kelas_rawat_id == this.#kelasPerawatan)
                            return t;
                    });

                if (tarif) {
                    hargaParameterRadiologi.textContent = tarif.total.toLocaleString("id-ID", {
                        style: "currency",
                        currency: "IDR",
                    });
                } else {
                    console.error("Tarif belum di set atau tidak ditemukan! ID Parameter: " + ParameterRadiologi.id);
                    showErrorAlertNoRefresh("Tarif tidak ditemukan atau belum di set! Mohon laporkan ke management. Cek log console!");
                }

            }
        }
    }

    #calculateCost() {
        this.#totalHarga = 0;
        const checkboxes = document.querySelectorAll("input[type='checkbox'].parameter_radiologi_checkbox");
        checkboxes.forEach((_checkbox) => {
            const checkbox = /** @type {HTMLInputElement} */ (_checkbox);
            const isChecked = checkbox.checked;
            const parameterId = checkbox.value;
            /** @type {ParameterRadiologi | undefined} */
            let parameter;

            for (const nama_kategori in this.#KategoriRadiologi) {
                const parameters = this.#KategoriRadiologi[nama_kategori].parameter_radiologi;
                parameter = parameters.find((p) => p.id == parseInt(parameterId));
            }


            if (isChecked && parameter) {
                const tarif = this.#TarifRadiologi
                    .find((t) => {
                        if (t.parameter_radiologi_id == parameter.id &&
                            t.group_penjamin_id == this.#groupTarif &&
                            t.kelas_rawat_id == this.#kelasPerawatan)
                            return t;
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