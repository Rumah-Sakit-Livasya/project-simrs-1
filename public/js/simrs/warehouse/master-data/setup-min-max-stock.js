// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class SMMSHandler {

    /**
     * @type {MasterGudang[]}
     */
    #Gudang;

    /**
    * @type {Satuan[]}
    */
    #Satuan;

    /**
    * @type {MinMaxStock[]}
    */
    #SMMS;

    /**
     * @type {BarangFarmasi[]}
     */
    #BarangFarmasi;

    /**
     * @type {BarangNonFarmasi[]}
     */
    #BarangNonFarmasi;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Table;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingIcon;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingPage;

    /**
    * @type {JQuery<HTMLElement>}
    */
    #$GudangSelect;

    /**
    * @type {JQuery<HTMLElement>}
    */
    #$BarangSelect;

    #API_URL = "/api/simrs/warehouse/master-data/setup-min-max-stock";

    constructor() {
        // @ts-ignore
        this.#Gudang = window._gudangs;
        // @ts-ignore
        this.#Satuan = window._satuans;
        // @ts-ignore
        this.#BarangFarmasi = window._barang_farmasi;
        // @ts-ignore
        this.#BarangNonFarmasi = window._barang_non_farmasi;

        this.#$Table = $("#table-body");
        this.#$GudangSelect = $("#gudang_id");
        this.#$BarangSelect = $("#select-barang");
        this.#$LoadingIcon = $("#loading-spinner")
        this.#$LoadingPage = $("#loading-page");

        this.#init();
    }

    #init() {
        this.#$GudangSelect.on('select2:select', this.handleGudangChange.bind(this));
        this.#$BarangSelect.on('select2:select', this.#handleBarangChange.bind(this));
        this.#refreshTable();
        this.#showLoading(false);
    }

    /**
     * Show or hide the loading icon
     * @param {boolean} show 
     */
    #showLoading(show) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
    }

    async handleGudangChange() {
        this.#showLoading(true);
        const gudangId = this.#$GudangSelect.val();
        if (!gudangId) {
            this.#showLoading(false);
            return showErrorAlertNoRefresh("Pilih gudang terlebih dahulu!");
        }

        const url = "/get/gudang/" + gudangId;
        this.#SMMS = (await this.#APIfetch(url));
        this.#refreshTable();
        this.#showLoading(false);
    }

    #handleBarangChange() {
        const gudangId = parseInt(String(this.#$GudangSelect.val()));
        if (!gudangId) {
            this.#setBarangSelectToFirstOption();
            return showErrorAlertNoRefresh("Pilih gudang terlebih dahulu!");
        }

        const barangId = parseInt(String(this.#$BarangSelect.val()));
        const barangType = this.#$BarangSelect.find(':selected').data('type');
        if (!barangId) return;

        if (barangType == "Farmasi") {
            this.#SMMS.push({
                barang_f_id: barangId,
                // @ts-ignore
                barang_nf_id: null,
                gudang_id: gudangId,
                min: 1,
                max: 1
            });
        } else {
            this.#SMMS.push({
                // @ts-ignore
                barang_f_id: null,
                barang_nf_id: barangId,
                gudang_id: gudangId,
                min: 1,
                max: 1
            });
        }
        this.#refreshTable();
        this.#setBarangSelectToFirstOption();
    }

    #setBarangSelectToFirstOption() {
        const first = this.#$BarangSelect.find('option').first().val();
        this.#$BarangSelect.val(first ?? 0).trigger('change');
    }

    #refreshTable() {
        this.#$Table.empty();
        if (this.#SMMS && this.#SMMS.length) {
            this.#SMMS.forEach((s, i) => {
                const HTML = this.#getMMSTableCol(s, i);
                if (!HTML) return; // error occured
                const $row = $(HTML);
                this.#$Table.append($row);
            });
        }
        else {
            const HTML = this.#getIsEmptyTableCol();
            const $row = $(HTML);
            this.#$Table.append($row);
        }
    }

    #getIsEmptyTableCol() {
        return /*html*/`
            <tr>
                <td colspan="7" style="text-align: center;">Pilih gudang lain atau masukkan barang terlebih dahulu!</td>
            </tr>
        `;
    }

    /**
     * Generate HTML string for MMS table collumn
     * @param {MinMaxStock} mms 
     * @param {number} index
     */
    #getMMSTableCol(mms, index) {
        const Gudang = this.#Gudang.find(g => g.id == mms.gudang_id);
        const Barang = mms.barang_f_id
            ? this.#BarangFarmasi.find(b => b.id == mms.barang_f_id)
            : this.#BarangNonFarmasi.find(b => b.id == mms.barang_nf_id);
        const BarangType = mms.barang_f_id
            ? "Farmasi"
            : "NonFarmasi";

        // make sure both variable is not undefined
        if (!Gudang || !Barang) {
            return showErrorAlertNoRefresh("Gudang atau Barang tidak ditemukan!");
        };

        const Satuan = this.#Satuan.find(s => s.id == Barang.satuan_id);
        // make sure Satuan is not undefined
        if (!Satuan) {
            return showErrorAlertNoRefresh("Satuan tidak ditemukan!");
        };

        const key = Math.round(Math.random() * 100000);

        let id = "";
        if (mms.id) id = /*html*/`
            <input type="hidden" name="mms_id[${key}]" value="${mms.id}">
        `;

        return /*html*/`
            <tr id="mms${key}">
                <td>${index + 1}</td>
                <th>${Barang.kode}
                    <input type="hidden" name="barang_type[${key}]" value="${BarangType}">
                    <input type="hidden" name="barang_id[${key}]" value="${Barang.id}">
                    ${id}
                </th>
                <th>${Barang.nama}</th>
                <th>${Satuan.kode}</th>
                <th>
                    <input type="number" name="min[${key}]" value="${mms.min}" min="0" step="1" class="form-control" 
                            onkeyup="SMMSClass.minChange(event, ${key}, ${index})" onchange="SMMSClass.minChange(event, ${key}, ${index})" required>
                </th>
                <th>
                    <input type="number" name="max[${key}]" value="${mms.max}" min="1" step="1" class="form-control"
                            onkeyup="SMMSClass.maxChange(event, ${key}, ${index})" onchange="SMMSClass.maxChange(event, ${key}, ${index})" required>
                </th>
                <th>
                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="SMMSClass.deleteItem(${key}, ${index})"></a>
                </th>
            </tr>
        `;
    }

    /**
     * Handle minimum MMS change
     * @param {Event} event 
     * @param {string} key 
     * @param {number} index 
     */
    minChange(event, key, index) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        this.#SMMS[index].min = parseInt(input.value);
    }

    /**
     * Handle maximum MMS change
     * @param {Event} event 
     * @param {string} key 
     * @param {number} index 
     */
    maxChange(event, key, index) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        this.#SMMS[index].max = parseInt(input.value);
    }

    /**
     * Delete item from table and variable
     * @param {string} key 
     * @param {number} index 
     */
    deleteItem(key, index) {
        this.#SMMS.splice(index, 1);
        this.#$Table.find("#mms" + key).remove();
        this.#refreshTable();
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url 
     * @param {FormData | null} body 
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET") {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
                }
            })
                .then(async (response) => {
                    if (response.status != 200) {
                        throw new Error('Error: ' + response.statusText);
                    }
                    resolve(await response.json());
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }

}

const SMMSClass = new SMMSHandler();