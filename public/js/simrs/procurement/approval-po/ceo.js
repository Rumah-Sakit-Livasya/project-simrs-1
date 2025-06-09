// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class APOCEOHandler {
    constructor() {
        this.#addEventListeners('.edit-btn', this.#handleEditButtonClick);
        this.#addEventListeners('#tambah-btn', this.#handleTambahButtonClick);
    }

    /**
     * Handle edit button click
     * @param {Event} event 
     */
    #handleEditButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        const type = button.getAttribute("data-type") || "";
        const url = `/simrs/procurement/approval-po/ceo/edit/${type}/${id}`;
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_editAPOCEO" + type + id,
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
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
     * Handle tambah button click
     * @param {Event} event 
     */
    #handleTambahButtonClick(event) {
        event.preventDefault();
        const url = "/simrs/procurement/approval-po/ceo/create";
        const width = screen.width;
        const height = screen.height;
        const left = width - (width / 2);
        const top = height - (height / 2);
        window.open(
            url,
            "popupWindow_addAPOCEO",
            "width=" + width + ",height=" + height +
            ",scrollbars=yes,resizable=yes,left=" + left + ",top=" + top
        );
    }
}

const APOCEOClass = new APOCEOHandler();