// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../types.d.ts" />
/// <reference path="./api.js" />
/// <reference path="./uihtmlrenderer.js" />
/// <reference path="./uiformhandler.js" />
/// <reference path="./uitablehandler.js" />
/// <reference path="./uimischandler.js" />
/// <reference path="./resepstatehandler.js" />
/// <reference path="./resepeventhandler.js" />

/**
 * @typedef {BarangFarmasi&{
 *  qty: number
 * }} ObatStock
 */

/**
 * Main application class.
 * Its primary role is to instantiate and wire up all the handler and manager
 * classes to orchestrate the application.
 */
class ResepHandler {
    /** @type {ResepEventHandler} */
    #eventHandler;

    constructor() {
        $(document).ready(() => {
            // Instantiate all the pieces of the application
            const apiHandler = new ApiHandler();
            const uiHtmlRenderer = new UIHTMLRenderer();
            const uiFormUpdater = new UIFormUpdater(uiHtmlRenderer);
            const uiTableUpdater = new UITableUpdater(uiHtmlRenderer);
            const uiMiscHandler = new UIMiscHandler();

            const stateManager = new ResepStateManager(apiHandler, uiFormUpdater, uiTableUpdater);

            this.#eventHandler = new ResepEventHandler(stateManager, uiFormUpdater, uiTableUpdater, uiMiscHandler);

            // Start the application by initializing event listeners
            this.#eventHandler.initializeEventListeners();
        });
    }

    // --- PUBLIC API for onclick attributes in HTML ---
    // These methods delegate the call to the event handler instance.

    /**
     * @param {number} key 
     * @param {number} id 
     * @param {string} nama 
     */
    pilihBatch(key, id, nama) {
        this.#eventHandler.handleBatchSelectForIncompleteItem(key, id, nama)
    }

    /** @param {StoredItem} item */
    tambahObat(item) {
        this.#eventHandler.handleBatchSelect(item);
    }

    /** @param {string} key */
    deleteItem(key) {
        this.#eventHandler.deleteItem(key);
    }

    /** @param {string} key */
    deleteRacikan(key) {
        this.#eventHandler.deleteRacikan(key);
    }

    signa(key, name) {
        this.#eventHandler.ubahSigna(key, name);
    }

    /** 
     * @param {number} key 
     * @param {string} name 
     */
    tambahObatRacikan(key, name) {
        this.#eventHandler.tambahObatRacikan(key, name);
    }

    /** @param {string} nama */
    noRestriksiSwal(nama) {
        this.#eventHandler.noRestriksiSwal(nama);
    }

    /**
     * @param {string} nama 
     * @param {string} restriksi 
     */
    restriksiSwal(nama, restriksi) {
        this.#eventHandler.restriksiSwal(nama, restriksi);
    }

    /**
     * @param {number} key 
     * @param {string} name 
     */
    jamPemberian(key, name) {
        this.#eventHandler.jamPemberian(key, name);
    }
}

// Instantiate the main handler to start the application
const ResepClass = new ResepHandler();