// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PONPharmacyHandler {
    constructor() {
        this.#addEventListeners(".delete-btn", this.#handleDeleteButtonClick);
        this.#addEventListeners(".print-btn", this.#handlePrintButtonClick);
        this.#addEventListeners(".edit-btn", this.#handleEditButtonClick);
        this.#addEventListeners("#tambah-btn", this.#handleTambahButtonClick);
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

        const url = "/simrs/procurement/purchase-order/non-pharmacy/edit/" + id;
        const width = screen.width;
        const height = screen.height;
        const left = width - width / 2;
        const top = height - height / 2;
        window.open(
            url,
            "popupWindow_editPONFarmasi" + id,
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

    /**
     * Handle print button click
     * @param {Event} event
     */
    #handlePrintButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        const url =
            "/simrs/procurement/purchase-order/non-pharmacy/print/" + id;
        const width = screen.width;
        const height = screen.height;
        const left = width - width / 2;
        const top = height - height / 2;
        window.open(
            url,
            "popupWindow_printPONFarmasi",
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

    /**
     * Add event listeners
     * @param {string} selector
     * @param {Function} handler
     * @param {string} event
     */
    #addEventListeners(selector, handler, event = "click") {
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
        const url = "/simrs/procurement/purchase-order/non-pharmacy/create";
        const width = screen.width;
        const height = screen.height;
        const left = width - width / 2;
        const top = height - height / 2;
        window.open(
            url,
            "popupWindow_addPONFarmasi",
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

    /**
     * Handle delete button click
     * @param {Event} event
     */
    #handleDeleteButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        Swal.fire({
            title: "Hapus PO?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                this.#deleteItem(id);
            }
        });
    }

    /**
     * Delete after confirmation
     * @param {number} id
     */
    #deleteItem(id) {
        const formData = new FormData();
        formData.append("id", String(id));
        formData.append(
            "csrf-token",
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") || ""
        );

        fetch(
            "/api/simrs/procurement/purchase-order/non-pharmacy/destroy/" + id,
            {
                method: "DELETE",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
            }
        )
            .then(async (response) => {
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message);
                }
                showSuccessAlert("Data berhasil dihapus!");
                setTimeout(() => window.location.reload(), 2000);
            })
            .catch((error) => {
                console.log("Error:", error);
                showErrorAlertNoRefresh(`Error: ${error}`);
            });
    }
}

const PONPharmacyClass = new PONPharmacyHandler();
