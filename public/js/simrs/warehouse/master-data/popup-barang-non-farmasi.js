// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupBarangNonFarmasiHandler {

    /**
     * @type {Satuan[]}
     */
    #Satuan;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #SatuanTambahanSelect;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #PPNPrev;    

    /**
     * @type {number[]}
     */
    SelectedSatuanTambahanIds = [];

    SelectedSatuanId = -1;
    HNA = 0;
    PPN = 0;

    constructor() {
        // @ts-ignore
        this.#Satuan = window._satuans;
        this.#SatuanTambahanSelect = $('#satuan-tambahan-select');
        this.#PPNPrev = $('#ppn_prev');
        this.#init();
    }

    #init() {
        $('#satuan_id').on('select2:select', this.#onSatuanChange.bind(this));
        this.#SatuanTambahanSelect.on("select2:select", this.#onSatuanTambahanChange.bind(this));
        this.#addEventListeners("#hna", this.#onHNAChange, "keyup");
        this.#addEventListeners("#ppn", this.#onPPNChange, "keyup");
    }

    /**
     * On Harga Beli input change
     * @param {Event} event 
     */
    #onHNAChange(event){
        const input = /** @type {HTMLInputElement} */ (event.target);
        this.HNA = parseInt(input.value);
        this.calculatePPNPrev();
    }

    /**
     * On PPN input change
     * @param {Event} event 
     */
    #onPPNChange(event){
        const input = /** @type {HTMLInputElement} */ (event.target);
        this.PPN = parseInt(input.value);
        this.calculatePPNPrev();
    }

    calculatePPNPrev(){
        const Prev = this.HNA + (this.HNA * this.PPN / 100);
        this.#PPNPrev.val(Prev);
    }

    /**
    * On Select2 satuan tambahan change
    * @param {JQuery.ChangeEvent} event 
    */
    #onSatuanTambahanChange(event) {
        // if SelectedSatuanId === -1
        // return alert user must select primary satuan first
        if (this.SelectedSatuanId === -1) {
            // set selection to the first option
            this.setSatuanTambahanSelectToFirstOption();
            // and then fire alert
            return alert("Silahkan pilih satuan utama terlebih dahulu!");
        }

        // @ts-ignore
        const id = parseInt(event.params.data.id)

        // Add the selected satuan ID to the list of selected satuan tambahan IDs
        this.SelectedSatuanTambahanIds.push(id);

        // Find the satuan object that matches the selected satuan ID
        const satuan = this.#Satuan.find(satuan => satuan.id === id);

        // If the satuan is not found, alert the user and exit the function
        if (!satuan) return alert("Satuan not found!");

        // Generate the HTML element for the satuan table column using the satuan object
        const element = this.#getSatuanTableCol(satuan);

        // Reset the satuan tambahan select to the first option
        this.setSatuanTambahanSelectToFirstOption();

        // Append the generated satuan table column element to the table with ID "table-satuan"
        $("#table-satuan").append(element);

        // Refresh the options
        this.refreshSatuanTambahanSelect();
    }

    setSatuanTambahanSelectToFirstOption() {
        const first = this.#SatuanTambahanSelect.find('option').first().val();
        this.#SatuanTambahanSelect.val(first ?? 0).trigger('change');
    }

    /**
     * Get HTML element of table col with satuan as data constructor
     * @param {Satuan} satuan 
     */
    #getSatuanTableCol(satuan) {
        const key = new Date().getTime();
        return /*html*/`
            <tr id="satuan${key}" data-index="${this.SelectedSatuanTambahanIds.length - 1}">
                <td>${satuan.nama}</td>
                <td>
                    <input type="hidden" name="satuans_id[${key}]" value="${satuan.id}">
                    <input type="number" name="satuans_jumlah[${key}]" value="1" class="form-control" min="1">
                </td>
                <td>
                    <input type="checkbox" name="satuans_status[${key}]" value="1" title="Aktif?" checked>
                </td>
                <td>
                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="PopupBarangNonFarmasiClass.deleteSatuanTambahan(${key})"></a>
                </td>
            </tr>
        `.trim();
    }

    /**
     * Delete satuan tambahan collumn element
     * @param {number} key element key
     */
    deleteSatuanTambahan(key) {
        const satuanTambahanElement = document.getElementById(`satuan${key}`);
        const index = satuanTambahanElement?.getAttribute("data-index");

        if (index) this.SelectedSatuanTambahanIds.splice(parseInt(index), 1);
        if (satuanTambahanElement) satuanTambahanElement.remove();
        else alert('Element not found');

        this.refreshSatuanTambahanSelect();
    }

    /**
    * On Select2 satuan_id change
    * @param {JQuery.ChangeEvent} event 
    */
    #onSatuanChange(event) {
        // @ts-ignore
        this.SelectedSatuanId = parseInt(event.params.data.id);
        this.refreshSatuanTambahanSelect();
    }

    /**
     * Appends options on satuan tambahan select
     * @param {{value: number, text: string}[]} options 
     */
    #appendOptionsToSatuanTambahanSelect(options){
        this.#SatuanTambahanSelect.find('option:not(:first)').remove();
        const SelectElement = this.#SatuanTambahanSelect;
        $.each(options, function (index, option) {
            SelectElement.append($('<option>', option));
        });
    }

    /**
     * Empties and appends options on satuan tambahan select
     */
    refreshSatuanTambahanSelect() {
        const newOptions = this.getNewSatuanTambahanOptions();
        this.#appendOptionsToSatuanTambahanSelect(newOptions);
    }

    /**
    * Get new satuan tambahan options
    */
    getNewSatuanTambahanOptions() {
        const filteredOptions1 = this.#Satuan.filter(satuan => satuan.id !== this.SelectedSatuanId);
        const filteredOptions2 = filteredOptions1.filter(satuan => !this.SelectedSatuanTambahanIds.includes(satuan.id));
        const newOptions = /** @type {{value: number, text: string}[]} */ ([]);

        filteredOptions2.forEach(satuan => {
            newOptions.push({ value: satuan.id, text: satuan.nama });
        });

        return newOptions;
    }

    /**
     * Add event listeners
     * @param {string} selector 
     * @param {Function} handler 
     * @param {string} event
     */
    #addEventListeners(selector, handler, event = 'click') {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach((button) => {
            button.addEventListener(event, handler.bind(this));
        });
    }


}

const PopupBarangNonFarmasiClass = new PopupBarangNonFarmasiHandler();