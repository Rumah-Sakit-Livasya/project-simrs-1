// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

class EditOrderRadiologi {
    constructor() {
        this.#init();
    }

    #init() {
        this.#addEventListeners('.verify-btn', this.#handleVerifyButton);
        this.#addEventListeners(".edit-btn", this.#handleEditClick);
    }

    /**
     * Add event listeners
     * @param {string} selector 
     * @param {Function} handler 
     */
    #addEventListeners(selector, handler) {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach((button) => {
            button.addEventListener("click", handler.bind(this));
        });
    }

    /**
     * Handle edit button click
     * @param {Event} event 
     */
    #handleEditClick(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const url = `/simrs/radiologi/edit-hasil-parameter/${id}`;
        const popupWidth = 900;
        const popupHeight = 600;
        const left = (screen.width - popupWidth) / 2;
        const top = (screen.height - popupHeight) / 2;

        window.open(
            url,
            "popupWindow_" + new Date().getTime(),
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }

    /**
     * Handle verify button click
     * @param {Event} event 
     */
    #handleVerifyButton(event) {
        event.preventDefault();
        const target = /** @type {HTMLElement} */ (event.target);
        const id = target.getAttribute("data-id");
        if (!id) return;

        const now = new Date();
        const formattedDate = now.toISOString().slice(0, 19).replace("T", " ");

        const employeeIdInput = /** @type {HTMLInputElement} */ (document.querySelector('input[name="employee_id"]'));
        const employeeId = employeeIdInput ? employeeIdInput.value : null;
        if (!employeeId) {
            showErrorAlertNoRefresh('Employee ID is required');
            return;
        }

        const formData = new FormData();
        formData.append("id", id);
        formData.append("verifikator_id", employeeId);
        formData.append("verifikasi_date", formattedDate);

        Swal.fire({
            title: "Verifikasi",
            html: "Verifikasi expertise?",
            icon: "question",
            focusConfirm: true,
            showCancelButton: true,
            confirmButtonText: "Verifikasi",
            cancelButtonText: "Batal"
        }).then(result => {
            if (result.isConfirmed) {
                fetch('/api/simrs/verifikasi-order-parameter-radiologi', {
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
                        showSuccessAlert('Data berhasil disimpan');
                        setTimeout(() => window.location.reload(), 2000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorAlertNoRefresh(`Error: ${error}`);
                    });
            }
        });
    }
}

const EditOrderRadiologiClass = new EditOrderRadiologi();