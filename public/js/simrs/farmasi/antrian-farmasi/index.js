// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

/**
 * Main application class for Antrian Farmasi.
 * Handles queue management and table updates for pharmacy queue system.
 */
class AntrianFarmasiHandler {
    /** @type {string} */
    static BaseAPI = "/api/simrs/farmasi/antrian-farmasi";

    static PlasmaWindowID = "popupWindow_antrianFarmasiPlasma";

    /** @type {Object.<string, string>} */
    #hash;

    /** @type {string[]} */
    #letters;

    /** @type {number | NodeJS.Timeout | null} */
    #intervalId;

    /** @type {Window | null} */
    #popupWindow = null;

    constructor() {
        this.#hash = {
            "a": "",
            "b": "",
            "c": "",
            "d": ""
        };
        this.#letters = ['a', 'b', 'c', 'd'];

        $(document).ready(() => {
            this.#initialize();
            this.#addEventListeners("#plasma-btn", this.#spawnPlasma);
        });
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

    /** @param {Event} event */
    #spawnPlasma(event) {
        event?.preventDefault();

        const url = "/simrs/farmasi/antrian-farmasi/plasma";
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            AntrianFarmasiHandler.PlasmaWindowID,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    /**
     * Initialize the queue system
     */
    async #initialize() {
        // Initial load
        for (const letter of this.#letters) {
            $(`#table-body-${letter}`).empty();
            await this.#refreshTable(letter);
        }

        // Update every 10 seconds
        const aSecond = 1000;
        this.#intervalId = setInterval(async () => {
            for (const letter of this.#letters) {
                await this.#refreshTable(letter);
            }
        }, aSecond * 10);
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url
     * @param {any | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     * @param {boolean} raw
     * @returns {Promise<any>}
     */
    async #fetchAPI(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(url, {
                method: method,
                body: body,
                headers: {
                    // 'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content')) || ''
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
                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }

    /**
     * Generate HTML for queue row
     * @param {FarmasiAntrian} queue
     * @param {number} no
     * @returns {string}
     */
    #getQueueHTML(queue, no) {
        // <tr>
        //     <th>#</th>
        //     <th>Antrian</th>
        //     <th>No RM</th>
        //     <th>Nama Pasien</th>
        //     <th>Poliklinik</th>
        //     <th>Terpanggil?</th>
        //     <th>Aksi</th>
        // </tr>
        return  /*html*/`
            <tr>
                <td>${no}</td>
                <td>${queue.antrian}</td>
                <td>${queue.re?.registration?.patient?.medical_record_number}</td>
                <td>${queue.re?.registration?.patient?.name}</td>
                <td>${queue.re?.registration?.departement?.name}</td>
                <td>${queue.dipanggil ? 'Sudah' : 'Belum'}</td>
                <td><a class="mdi mdi-microphone pointer mdi-24px text-primary call-btn"
                    title="Panggil Antrian" onclick="AntrianFarmasiClass.call(${queue.id}, '${queue.antrian}')">
                    <a class="mdi mdi-hand-extended-outline pointer mdi-24px text-success give-btn"
                    title="Pemberian Obat" onclick="AntrianFarmasiClass.give(${queue.id}, '${queue.antrian}')">
                </td>
            </tr>
        `;
    }

    /**
     * @param {number} id 
     * @param {string} queue 
     */
    give(id, queue) {
        // confirm with Swal
        Swal.fire({
            title: 'Pemberian Obat',
            text: `Akan melakukan pemberian obat untuk antrian ${queue}, lanjutkan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
        }).then(async (result) => {
            if (result.isConfirmed) {
                this.#updateGiveStatus(id, queue);
            }
        });
    }

    /** 
     * @param {number} id
     * @param {string} queue 
     */ 
    #updateGiveStatus(id, queue) {
        const URL = AntrianFarmasiHandler.BaseAPI + `/update-give-status/${id}`;
        showSuccessAlert(`Telah memberi obat kepada antrian ${queue}.`);
        return this.#fetchAPI(URL, null, "PUT");
    }

    /**
     * @param {number} id 
     * @param {string} queue 
     */
    call(id, queue) {
        // confirm with Swal
        Swal.fire({
            title: 'Panggil Antrian',
            text: `Akan memanggil antrian ${queue}, lanjutkan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Panggil',
            cancelButtonText: 'Batal',
        }).then(async (result) => {
            if (result.isConfirmed) {
                this.#callOnPlasma(id, queue);
            }
        });
    }

    /** @param {number} ms */
    sleep(ms) {
        return new Promise(r => setTimeout(() => r(true), ms));
    }

    /**
     * @param {number} id 
     * @param {string} queue 
     */
    async  #callOnPlasma(id, queue) {
        let Popup = (this.#popupWindow);
        if (!Popup) {
            Popup = window.open('', AntrianFarmasiHandler.PlasmaWindowID);
            if (!Popup) {
                this.#spawnPlasma(new Event('change'));
                await this.sleep(3000)
            }
        }

        Popup?.postMessage({ type: "call", data: { id, queue } }, '*');
        showSuccessAlert(`Antrian ${queue} akan segera dipanggil.`);
    }

    /**
     * Refresh table for specific letter
     * @param {string} letter
     */
    async #refreshTable(letter) {
        const URL = AntrianFarmasiHandler.BaseAPI + `/get-antrian/${letter}`;
        const NewHash = /** @type {string} */ (await (await this.#fetchAPI(URL, null, "GET", true)).text());

        if (this.#hash[letter] == NewHash) return /* console.log("No Update on table " + letter) */; // no update
        this.#hash[letter] = NewHash;

        const Content = /** @type {FarmasiAntrian[]} */ (JSON.parse(atob(NewHash)));

        $(`#table-body-${letter}`).empty();
        let no = 0;
        Content.forEach(Queue => {
            $(`#table-body-${letter}`).append(this.#getQueueHTML(Queue, ++no));
        });
    }

    /**
     * Public method to manually refresh a specific table
     * @param {string} letter
     */
    async refreshTable(letter) {
        await this.#refreshTable(letter);
    }

    /**
     * Stop the automatic refresh interval
     */
    stopAutoRefresh() {
        if (this.#intervalId) {
            clearInterval(this.#intervalId);
            this.#intervalId = null;
        }
    }
}

// Instantiate the main handler to start the application
const AntrianFarmasiClass = new AntrianFarmasiHandler();
