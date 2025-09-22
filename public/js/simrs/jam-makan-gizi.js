// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class JamMakanGiziHandler {
    constructor() {
        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
    }

    #init() {
        this.#addEventListeners("a.delete-btn", this.#handleDeleteButtonClick);
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
            title: "Hapus jam makan gizi?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                this.#deleteMenu(id);
            }
        });
    }

    /**
     * Delete jam makan after confirmation
     * @param {number} id
     */
    #deleteMenu(id) {
        const formData = new FormData();
        formData.append("id", String(id));
        formData.append(
            "csrf-token",
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content") || ""
        );

        fetch("/api/simrs/gizi/jam-makan/destroy/" + id, {
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

const JamMakanGiziClass = new JamMakanGiziHandler();
