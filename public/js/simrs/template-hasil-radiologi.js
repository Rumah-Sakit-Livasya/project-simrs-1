// @ts-check
/// <reference types="jquery" />
/// <reference types="bootstrap" />
/// <reference path="../types.d.ts" />

class TemplateHasilRadiologi {
    constructor() {
        this.#init();
    }

    #init() {
        this.#addEventListeners('#tambah-btn', this.#handleAddButtonClick);
    }

    /**
    * Absoulte query selector
    * @param {string} selectors 
    * @returns {Element}
    */
    #querySelectorAbs(selectors) {
        const element = document.querySelector(selectors);
        if (!element) throw new Error("No element with selector: " + selectors);
        return element;
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
     * Handle add template button click
     * @param {Event} event 
     */
    #handleAddButtonClick(event){
        event.preventDefault();
        // const target = /** @type {HTMLElement} */ (event.target);
    }
}

const TemplateHasilRadiologiClass = new TemplateHasilRadiologi();