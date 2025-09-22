// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class MasterGudangHandler {
    constructor() {
        this.#addEventListeners(".delete-btn", this.#handleDeleteButtonClick);
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
    #handleDeleteButtonClick(event) {
        event.preventDefault();
        const button = /** @type {HTMLButtonElement} */ (event.target);
        const id = parseInt(button.getAttribute("data-id") || "0");
        if (!id) return;

        Swal.fire({
            title: "Hapus master gudang?",
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
     * Delete zat after confirmation
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

        fetch("/api/simrs/warehouse/master-data/master-gudang/destroy/" + id, {
            method: "DELETE",
            body: formData,
            headers: {
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
        })
            .then((response) => {
                if (response.status != 200) {
                    throw new Error("Error: " + response.statusText);
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

const MasterGudangClass = new MasterGudangHandler();
