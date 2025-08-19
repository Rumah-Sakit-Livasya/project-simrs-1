// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../../types.d.ts" />

class APIHandler {

    /**
     * @type {string}
     */
    #API_URL;

    constructor(url) {
        this.#API_URL = url;
    }

    /**
     * Fetch opname items from API
     * @param {number} StockOpnameGudangId 
     * @returns {Promise<StoredItemOpname[]>} 
     */
    async fetchItems(StockOpnameGudangId) {
        const url = `/get/opname-items/${StockOpnameGudangId}`;
        const result = await this.#APIfetch(url);
        return result;
    }

    /**
     * Fetch latest opname item movement
     * @param {"f" | "nf"} type 
     * @param {number} opname_id 
     * @param {number} si_id 
     * @returns 
     */
    async fetchItemMovement(type, opname_id, si_id) {
        const url = `/get/opname-item-movement/${type}/${opname_id}/${si_id}`;
        const result = await this.#APIfetch(url);
        return result;
    }

    /**
     * Store stock opname draft of an item
     * @param {FormData} body 
     */
    async storeFinal(body) {
        const url = `/store`;
        return await this.#APIfetch(url, body, "POST");
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url 
     * @param {FormData | null} body 
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '',
                    // 'Content-Type': 'application/json',
                    'Accept': 'application/json'
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
}