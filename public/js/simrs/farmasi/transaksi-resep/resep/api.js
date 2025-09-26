// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../types.d.ts" />

class ApiHandler {
    #API_URL = "/api/simrs/farmasi/transaksi-resep";

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url
     * @param {any | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     * @param {boolean} raw
     * @returns {Promise<any>}
     */
    fetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
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
