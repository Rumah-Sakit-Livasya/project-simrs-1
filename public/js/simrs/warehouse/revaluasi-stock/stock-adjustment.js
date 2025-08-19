// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class StockAdjustmentHandler {
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
    #$LoadingMessage;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Email;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Password;
    
    /**
     * @type {JQuery<HTMLElement>}
     */
    #$AuthModal;

    #API_URL = "/api/simrs/warehouse/revaluasi-stock/stock-adjustment";

    constructor() {
        this.#$LoadingIcon = $("#loading-spinner-head");
        this.#$LoadingPage = $("#loading-page");
        this.#$LoadingMessage = $("#loading-message");
        this.#$Email = $("#email");
        this.#$Password = $("#password");
        this.#$AuthModal = $("#authModal");
        this.#addEventListeners('#authBtn', this.#handleLoginButtonClick);
        this.#showLoading(false);
        this.#init();
    }

    #init() {
    }

    /**
     * Show or hide the loading icon
     * @param {boolean} show 
     * @param {string?} message 
     */
    #showLoading(show, message = null) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
        this.#$LoadingMessage.toggle(show);

        if (message) {
            this.#$LoadingMessage.text(message);
        } else {
            this.#$LoadingMessage.text('Loading...');
        }
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

    /**
     * Handle login button click
     * @param {Event} event 
     */
    async #handleLoginButtonClick(event) {
        event.preventDefault();
        this.#$AuthModal.modal("hide");
        this.#showLoading(true, "Logging in...");
        const body = new FormData();
        body.append('email', /** @type {string} */(this.#$Email.val()));
        body.append('password', /** @type {string} */(this.#$Password.val()));
        const endpoint = "/login";
        const response = await this.#APIfetch(endpoint, body, "POST");

        if (!response.success) {
            return showErrorAlertNoRefresh(response.message);
        }
        this.#showLoading(false);

        const token = response.token;

        const url = "/simrs/warehouse/revaluasi-stock/stock-adjustment/create/" + token;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_addStockAdjustment",
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url 
     * @param {FormData | null} body 
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        console.log(this.#API_URL + url);
        
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

const StockAdjustmentClass = new StockAdjustmentHandler();