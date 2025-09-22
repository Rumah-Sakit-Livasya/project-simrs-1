// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class ResepResponseHandler {
    #Username = /** @type {string} */ ($("#username").val());
    #UserID = /** @type {number} */ ($("#user_id").val());
    #API_URL = "/api/simrs/farmasi/response-time";

    constructor() {
        // Initialize any event listeners or functionality needed for the resep response page
        this.#init();
    }

    /**
     * Initialize page functionality
     */
    #init() {
        this.#addEventListeners(".process-btn", this.#handleProcessButtonClick);
        this.#addEventListeners(".telaah-btn", this.#handleTelaahButtonClick);
        this.#addEventListeners(
            ".keterangan-btn",
            this.#handleKeteranganButtonClick
        );
        this.#addEventListeners("#print-btn", this.#handlePrintButtonClick);
    }

    /** @param {Event} event */
    #handlePrintButtonClick(event) {
        event.preventDefault();
        const Form = /** @type {HTMLFormElement | undefined} */ (
            $("#form-response-time").get(0)
        );

        if (!Form) return alert("Form not found!");
        const Body = new FormData(Form);

        // convert Body to JSON
        const BodyJSON = JSON.stringify(Object.fromEntries(Body.entries()));

        const url = "/simrs/farmasi/response-time/popup/report/" + BodyJSON;
        const width = screen.width;
        const height = screen.height;
        const left = width - width / 2;
        const top = height - height / 2;
        window.open(
            url,
            "popupWindow_printResepResponseTimeReport",
            "width=" +
                width +
                ",height=" +
                height +
                ",scrollbars=yes,resizable=yes,left=" +
                left +
                ",top=" +
                top
        );
    }

    async #handleKeteranganButtonClick(event) {
        event.preventDefault();
        const Element = /** @type {HTMLElement} */ (event.target);

        // get data-id and data-keterangan
        const id = Element.getAttribute("data-id");
        const keterangan = Element.getAttribute("data-keterangan");

        const KeteranganBaru = prompt("Edit keterangan: ", keterangan ?? "");
        if (KeteranganBaru?.trim()) {
            const Base64Encoded = btoa(
                JSON.stringify({
                    id,
                    keterangan: KeteranganBaru,
                })
            );
            const URL = `/update-keterangan/${id}/${Base64Encoded}`;

            const Response = await this.#APIfetch(URL, null, "PUT").catch((e) =>
                showErrorAlertNoRefresh(e.message ?? e)
            );
            if (!Response) return;

            showSuccessAlert("Data berhasil di simpan!");
            // refresh page
            setTimeout(() => location.reload(), 1000 * 2); // 2s
        }
    }

    /** @param {Event} event */
    #handleTelaahButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        const url = "/simrs/farmasi/response-time/popup/telaah-resep/" + id;
        const width = screen.width;
        const height = screen.height;
        const left = width - width / 2;
        const top = height - height / 2;
        window.open(
            url,
            "popupWindow_printFarmasiTelaah" + id,
            "width=" +
                width +
                ",height=" +
                height +
                ",scrollbars=yes,resizable=yes,left=" +
                left +
                ",top=" +
                top
        );
    }

    /** @param {Event} event */
    #handleProcessButtonClick(event) {
        event.preventDefault();
        const Element = /** @type {HTMLElement} */ (event.target);

        // get data-id and data-type attributes
        const id = Element.getAttribute("data-id");
        const type = Element.getAttribute("data-type");
        const stamp = Date.now();

        // get stamp display to show time in format like this:
        // 22 Aug 2025 09:39
        const TimeDisplay = new Date(stamp).toLocaleString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });

        let typeDisplay = "UNSET";
        switch (type) {
            case "input_resep":
                typeDisplay = "Input Resep";
                break;
            case "penyiapan":
                typeDisplay = "Penyiapan Obat";
                break;
            case "racik":
                typeDisplay = "Racik Resep";
                break;
            case "verifikasi":
                typeDisplay = "Verifikasi";
                break;
            case "penyerahan":
                typeDisplay = "Penyerahan Obat";
                break;
        }

        // using Swal (sweetalert2), show confirmation in Bahasa Indonesia with the following variables:
        // this.#Username, TimeDisplay, typeDisplay
        Swal.fire({
            title: "Input Respon?",
            html: /*html*/ `
                Proses: <b>${typeDisplay}</b>  <br>
                Petugas: <b> ${this.#Username}</b> <br>
                Waktu: <b>${TimeDisplay}</b>
            `.trim(),
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Konfirmasi",
            cancelButtonText: "Batal",
        }).then(async (result) => {
            if (result.isConfirmed) {
                const Base64Encoded = btoa(
                    JSON.stringify({
                        type: type,
                        user_id: this.#UserID,
                        timestamp: stamp,
                    })
                );
                const URL = `/update/${id}/${Base64Encoded}`;

                const Response = await this.#APIfetch(URL, null, "PUT").catch(
                    (e) => showErrorAlertNoRefresh(e.message ?? e)
                );
                if (!Response) return;

                showSuccessAlert("Data berhasil di simpan!");
                // refresh page
                setTimeout(() => location.reload(), 1000 * 2); // 2s
            }
        });
    }

    /**
     * Add event listeners
     * @param {string} selector
     * @param {Function} handler
     * @param {string} event
     */
    #addEventListeners(selector, handler, event = "click") {
        const elements = document.querySelectorAll(selector);
        elements.forEach((element) => {
            element.addEventListener(event, handler.bind(this));
        });
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url
     * @param {FormData | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            console.log(body);

            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
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

const resepResponseHandler = new ResepResponseHandler();
