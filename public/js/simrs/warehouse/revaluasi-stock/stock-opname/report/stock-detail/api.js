// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../../../types.d.ts" />

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
     * @param {{tanggal: string, nama: string, kategori: string, golongan: string, jenis: string, gudang: string}} filters  Filters to apply to the API call
     * @returns {Promise<StockDetails[]>}
     */
    async fetchItems(filters) {
        const url = `/get-items`;
        const body = new FormData();
        for (const [key, value] of Object.entries(filters)) {
            if (value) {
                body.append(key, value);
            }
        }
        const result = await this.#APIfetch(url, body, "POST");
        return result;
    }

    async getPrintTemplate() {
        const url = "/get-print-template";
        const result = await this.#APIfetch(url, null, "POST", true);
        return result;
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
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    Accept: "application/json",
                },
            })
                .then(async (response) => {
                    if (response.status != 200) {
                        throw new Error("Error: " + response.statusText);
                    }
                    resolve(!raw ? await response.json() : response);
                })
                .catch((error) => {
                    console.log("Error:", error);

                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }
}
