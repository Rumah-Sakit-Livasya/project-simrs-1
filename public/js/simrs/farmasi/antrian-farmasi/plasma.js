// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

/**
 * Main application class for Antrian Farmasi.
 * Handles queue management and table updates for pharmacy queue system.
 */
class PlasmaFarmasiHandler {
    /** @type {string} */
    static BaseAPI = "/api/simrs/farmasi/antrian-farmasi";

    /** @type {Object.<string, string>} */
    #hash;

    /** @type {string[]} */
    #letters;

    /** @type {number | NodeJS.Timeout | null} */
    #intervalId;

    #$Toaster = $("#toast");
    #$ToasterContent = $("#toast-content");
    #$CurrentCall = $("#current-call");
    #$CountNonRacikan = $("#count-non-racikan");
    #$CountRacikan = $("#count-racikan");

    #isAnnouncing = false;

    /** @type {{id: number, queue: string}[]} */
    #announceList = [];

    #prosessNonRacikan = 0;
    #prosessRacikan = 0;
    #prosessNonRacikanBPJS = 0;
    #prosessRacikanBPJS = 0;

    constructor() {
        this.#$Toaster.hide();
        this.#hash = {
            "a": "",
            "b": "",
            "c": "",
            "d": ""
        };
        this.#letters = ['a', 'b', 'c', 'd'];

        $(document).ready(() => {
            this.#initialize();
        });

        window.addEventListener('message', (event) => {
            switch (event.data.type) {
                case "call":
                    this.#makeCallAnnouncement(event.data.data.id, event.data.data.queue)
                    break;
            }
        });
    }

    /**
     * Initialize the queue system
     */
    async #initialize() {
        // Initial load
        for (const letter of this.#letters) {
            $(`#list-${letter}`).empty();
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
                    'X-CSRF-TOKEN': String($("input[name='_token']").val()),
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
                    alert(`Error: ${error}`);
                    return reject(error);
                });
        });
    }

    /** @param {string} queue */
    #updateCurrentCall(queue, time_ms = 10 * 1000) {
        this.#$ToasterContent.text(queue);
        this.#$CurrentCall.text(queue);

        this.#$Toaster.show();
        setTimeout(() => {
            this.#$Toaster.hide();
        }, time_ms);
    }

    /** @param {number} ms */
    sleep(ms) {
        return new Promise(r => setTimeout(() => r(true), ms));
    }

    /**
     * @param {number} id 
     * @param {string} queue 
     */
    async #makeCallAnnouncement(id, queue) {
        this.#announceList.push({ id, queue });
        if (this.#isAnnouncing) return;
        this.#isAnnouncing = true;

        // queue format: {{letter}}{{number}}
        // split letter from number
        const Split = queue.split(/(\d+)/).filter(Boolean);
        const Letter = Split[0];
        const Number = Split[1];
        const Announcement = `Nomor Antrian, ${Letter}, ${Number}, Menuju Ke Loket, Farmasi`;

        this.#updateCurrentCall(queue);
        this.#playTTS(Announcement);
        this.#updateCallStatus(id);
        await this.sleep(11 * 1000); // 11s delay

        this.#isAnnouncing = false;
        this.#announceList.shift();
        if (this.#announceList.length > 0) {
            this.#makeCallAnnouncement(this.#announceList[0].id, this.#announceList[0].queue);
        }
    }

    async #updateCallStatus(id) {
        const URL = PlasmaFarmasiHandler.BaseAPI + `/update-call-status/${id}`;
        return this.#fetchAPI(URL, null, "PUT");
    }

    async #playTTS(text) {
        try {
            const response = await fetch(`http://liva_simrs_laravel11.test/api/tts?text=${encodeURIComponent(text)}`);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const audioBlob = await response.blob();
            const audioUrl = URL.createObjectURL(audioBlob);

            // Create an audio element
            const audio = new Audio(audioUrl);

            // Try to autoplay (muted if necessary)
            audio.muted = true;
            let playPromise = audio.play();

            if (playPromise !== undefined) {
                playPromise.then(() => {
                    // Autoplay worked -> unmute
                    audio.muted = false;
                }).catch(() => {
                    // Autoplay failed -> wait for user gesture
                    console.warn("Autoplay blocked. Waiting for user action to play TTS.");

                    const resume = () => {
                        audio.muted = false;
                        audio.play().catch(err => console.error("Manual play failed:", err));
                        document.removeEventListener("click", resume);
                    };

                    // Resume on next click anywhere
                    document.addEventListener("click", resume);
                });
            }

            return audioUrl;
        } catch (error) {
            console.error('Error fetching TTS:', error);
            throw error;
        }
    }

    /**
     * Generate HTML for queue row
     * @param {FarmasiAntrian} queue
     * @param {number} no
     * @returns {string}
     */
    #getQueueHTML(queue, no) {
        // <div class="item-row">
        //     <div class="item-left">
        //         <div class="item-id">MRN</div>
        //         <div class="item-name">PATIENT NAME</div>
        //         <div class="item-doc">DOCTOR NAME</div>
        //     </div>
        //     <div class="item-code">QUEUE CODE / NO</div>
        // </div>
        return  /*html*/`
         <div class="item-row">
             <div class="item-left">
                 <div class="item-id">${queue.re?.registration?.patient?.medical_record_number}</div>
                 <div class="item-name">${queue.re?.registration?.patient?.name}</div>
                 <div class="item-doc">${queue.re?.registration?.doctor?.employee?.fullname}</div>
             </div>
             <div class="item-code">${queue.antrian}</div>
         </div>
        `;
    }

    /**
     * @param {string} letter 
     * @param {number} count 
     */
    #updateCounter(letter, count) {
        switch (letter.toUpperCase()) {
            case 'A':
                // Umum / Asuransi Non Racikan
                this.#prosessNonRacikan = count;
                this.#$CountNonRacikan.text(this.#prosessNonRacikan + this.#prosessNonRacikanBPJS);
                break;

            case 'B':
                // Umum / Asuransi Racikan
                this.#prosessRacikan = count;
                this.#$CountRacikan.text(this.#prosessRacikan + this.#prosessRacikanBPJS);
                break;

            case 'C':
                // BPJS Non Racikan
                this.#prosessNonRacikanBPJS = count;
                this.#$CountNonRacikan.text(this.#prosessNonRacikan + this.#prosessNonRacikanBPJS);
                break;

            case 'D':
                // BPJS Racikan
                this.#prosessRacikanBPJS = count;
                this.#$CountRacikan.text(this.#prosessRacikan + this.#prosessRacikanBPJS);
                break;
        }
    }

    /**
     * Refresh table for specific letter
     * @param {string} letter
     */
    async #refreshTable(letter) {
        const URL = PlasmaFarmasiHandler.BaseAPI + `/get-antrian/${letter}`;
        const NewHash = /** @type {string} */ (await (await this.#fetchAPI(URL, null, "GET", true)).text());

        if (this.#hash[letter] == NewHash) return /* console.log("No Update on table " + letter) */; // no update
        this.#hash[letter] = NewHash;

        const Content = /** @type {FarmasiAntrian[]} */ (JSON.parse(atob(NewHash)));
        this.#updateCounter(letter, Content.length);
        $(`#list-${letter}`).empty();

        let no = 0;
        Content.forEach(Queue => {
            $(`#list-${letter}`).append(this.#getQueueHTML(Queue, ++no));
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
const PlasmaFarmasiClass = new PlasmaFarmasiHandler();
