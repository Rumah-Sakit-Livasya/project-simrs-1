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
        this.#addEventListeners('.delete-btn', this.#handleDeleteButtonClick);
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
    #handleAddButtonClick(event) {
        event.preventDefault();
        // const target = /** @type {HTMLElement} */ (event.target);
    }

    /**
     * Handle add template button click
     * @param {Event} event 
     */
    handleAddButtonClick(event, id) {
        event.preventDefault();
        const target = /** @type {HTMLInputElement} */ (event.target);
        const form = /** @type {HTMLFormElement} */ (target.form);
        const formData = new FormData(form);

        fetch(`/api/simrs/simpan-template-radiologi/${id ? id : '0'}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
            }
        })
            .then(async(response) => {
                if (response.status != 200) {
                    throw new Error('Error: ' + response.statusText);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`Error: ${error}`);
            });
    }

    /**
     * Handle delete template button click
     * @param {Event} event 
     */
    #handleDeleteButtonClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const templateId = target.getAttribute('data-id');

        if (!templateId) {
            alert('Template ID not found.');
            return;
        }

        if (!confirm('Konfirmasi hapus template?')) {
            return;
        }

        fetch(`/api/simrs/delete-template-radiologi/${templateId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
            }
        })
            .then(response => {
                if (response.status != 200) {
                    throw new Error('Error: ' + response.statusText);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`Error: ${error}`);
            });
    }

    useTemplate(orderParameterId, template) {
        const userId = (/** @type {HTMLInputElement} */(this.#querySelectorAbs('input[name="user_id"]'))).value;
        const employeeId = (/** @type {HTMLInputElement} */(this.#querySelectorAbs('input[name="employee_id"]'))).value;
        const formData = new FormData();

        formData.append('parameter_id', orderParameterId);
        formData.append('catatan', template);
        formData.append('user_id', userId);
        formData.append('employee_id', employeeId);

        fetch('/api/simrs/update-pemeriksaan-parameter-radiologi', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
            }
        })
            .then(response => {
                if (response.status != 200) {
                    throw new Error('Error: ' + response.statusText);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`Error: ${error}`);
            });
    }
}

const TemplateHasilRadiologiClass = new TemplateHasilRadiologi();