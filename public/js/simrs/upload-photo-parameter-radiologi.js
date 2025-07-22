// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class UploadPhotoParameterRadiologi {

    constructor() {
        this.#init();
    }

    #init() {
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
     * Handle upload button click
     * @param {Event} event 
     */
    handleUploadButtonClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const parameterId = target.getAttribute("data-parameter-id");
        if (!parameterId) return;

        const fileInput = /** @type {HTMLInputElement} */ (this.#querySelectorAbs('input[name="photo[]"].input-' + parameterId));
        const files = fileInput.files;
        console.log(files);

        if (!files) return;
        if (files.length === 0) {
            alert("Please select at least one file.");
            return;
        }

        const formData = new FormData();
        const CSRF = (this.#querySelectorAbs('meta[name="csrf-token"]').getAttribute('content')) || '';
        const userId = /** @type {HTMLInputElement} */(this.#querySelectorAbs('[name="user_id"].input-' + parameterId)).value;
        const employeeId =  /** @type {HTMLInputElement} */(this.#querySelectorAbs('[name="employee_id"].input-' + parameterId)).value;

        formData.append('_token', CSRF);
        formData.append('parameter_id', parameterId);
        formData.append('user_id', userId);
        formData.append('employee_id', employeeId);

        for (let i = 0; i < files.length; i++) {
            formData.append('photo[]', files[i]);
        }

        fetch('/api/simrs/upload-photo-parameter-radiologi', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''
            }
        })
            .then(async (response) => {
                if (response.status != 200) {
                    throw new Error('Error: ' + response.statusText);
                }
                showSuccessAlert('Data berhasil disimpan');
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }
}

const UploadPhotoParameterRadiologiClass = new UploadPhotoParameterRadiologi();