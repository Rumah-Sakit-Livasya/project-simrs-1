// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class CPPTDokterClass {
    #$Table = $("#table_re");
    #$GudangSelect = $("#cppt_gudang_id");
    #$BarangSelect = $("#cppt_barang_id");
    #$Loadings = $(".loading");
    #$LoadingsMessage = $(".loading-message");
    #API_URL = "/api/simrs/poliklinik";

    constructor() {
        this.#showLoading(false);
        this.#$GudangSelect.on('select2:select', (e) => this.#handleGudangSelect.bind(this, e)())
    }

    /**
     * Handle gudang change
     * @param {Select2.Event<HTMLElement, Select2.DataParams>} event 
     */
    #handleGudangSelect(event) {
        event.preventDefault();
        // get selected id
        const selectedId = event.params.data.id;

        this.#showLoading(true, "Fetching Items...");
        const url = `/obat/${selectedId}`;
        this.#APIfetch(url)
            .then(response => {
                // add to select2 options
                this.#$BarangSelect.empty();
                this.#$BarangSelect.append(new Option("", ""));
                response.items.forEach(item => {
                    this.#$BarangSelect.append(new Option(`${item.nama} (Stock: ${item.qty})`, item.id));
                });
                this.#$BarangSelect.trigger('change'); // trigger change event to update select2
            })
            .catch(error => {
                showErrorAlertNoRefresh(error.message);
            })
            .finally(() => this.#showLoading(false));
    }

    /**
     * Show or hide the loading icon
     * @param {boolean} show 
     * @param {string?} message 
     */
    #showLoading(show, message = null) {
        this.#$Loadings.toggle(show);

        if (message) {
            this.#$LoadingsMessage.text(message);
        } else {
            this.#$LoadingsMessage.text('Loading...');
        }
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url 
     * @param {any | null} body 
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
                }
            })
                .then(async (response) => {
                    if (response.status != 200) {
                        throw new Error('Error: ' + response.statusText);
                    }
                    resolve(!raw ? await response.json() : response);
                })
                .catch(error => {
                    console.error('Error:', error);

                    // @ts-ignore
                    if (this.#showLoading) this.#showLoading(false); // assert

                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }
}

const CPPTDokter = new CPPTDokterClass();