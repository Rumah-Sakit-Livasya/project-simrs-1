// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class EditOrderLaboratorium {
    /**
     * @type {OrderLaboratorium}
     */
    #Order;

    constructor() {
        document.addEventListener("DOMContentLoaded", () => {
            this.#init();
        })
    }

    #init() {
        // @ts-ignore
        this.#Order = window._order;

        this.#addEventListeners('.verify-btn', this.#handleVerifyButton);
        this.#addEventListeners('.delete-btn', this.#handleDeleteButton);
    }

    /**
    * Handle delete button click
    * @param {Event} event 
    */
    #handleDeleteButton(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = button.getAttribute("data-id");
        if (!id) return;

        Swal.fire({
            title: 'Hapus order parameter?',
            text: "Semua sub parameter ini akan ikut dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.#deleteData(id);
            }
        });
    }

    /**
    * Delete data
    * @param {string} id 
    */
    #deleteData(id) {
        const formData = new FormData();
        formData.append('order_parameter_id', id);
        formData.append('order_id', String(this.#Order.id));
        formData.append('csrf-token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');

        fetch('/api/simrs/laboratorium/parameter-delete', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || '',
                'Cache-Control': 'no-cache'
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
                fetch('/api/simrs/laboratorium/parameter-verify', {
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

const EditOrderLaboratoriumClass = new EditOrderLaboratorium();