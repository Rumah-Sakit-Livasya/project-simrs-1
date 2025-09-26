// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class ModalMenuGiziHandler {
    /**
     * @type {JQuery<HTMLFormElement>}
     */
    #FormElement;

    /**
     * @type {string}
     */
    #FormSelector;

    /**
     * @param {string} form_selector
     */
    constructor(form_selector) {
        if (!document.querySelector(form_selector))
            throw alert("Form not found");

        this.#FormElement = $(form_selector);
        this.#FormSelector = form_selector;

        this.#FormElement.closest(".modal").on("hidden.bs.modal", () => {
            location.reload();
        });

        this.#FormElement.find("select#search-food").on("change", (e) => {
            const select = /** @type {HTMLSelectElement} */ (e.target);
            const id = parseInt(select.value);
            if (!isNaN(id)) {
                this.#onFoodSelect(id);

                this.#FormElement
                    .find("select#search-food")
                    .val("")
                    .trigger("change");
            }
        });
    }

    /**
     * Handle on select2 food select
     * @param {number} id
     */
    #onFoodSelect(id) {
        const food = MenuGiziHandlerClass.Foods.find((food) => food.id === id);
        if (!food) return alert("Food not found");
        this.#FormElement
            .find("#table-food")
            .append($(this.#getFoodTableCol(food)));
        this.#updateHarga(food.harga);
    }

    /**
     * Update price input and display
     * @param {number} change
     */
    #updateHarga(change) {
        const harga = this.#FormElement.find('input[name="harga"]').val();
        if (!harga) return alert("Input harga not found.");
        // @ts-ignore
        const hargaTotal = parseInt(harga) + change;
        this.#FormElement.find('input[name="harga"]').val(hargaTotal);
        this.#FormElement
            .find("#harga-display")
            .text(`Rp ${hargaTotal.toLocaleString("id-ID")}`);
    }

    /**
     * Generate HTML string for food table collumn
     * @param {MakananGizi} food
     * @returns {string} HTML String
     */
    #getFoodTableCol(food) {
        const key = new Date().getTime();

        return /*html*/ `
            <tr id="food${key}">
                <td>${food.nama}</td>
                <td>Rp ${food.harga.toLocaleString("id-ID")}</td>
                <td>
                    <input type="hidden" name="foods_id[${key}]" value="${
            food.id
        }">
                    <input type="checkbox" name="foods_status[${key}]" value="1" checked
                        onchange="window['handler_${
                            this.#FormSelector
                        }'].updateFoodStatus(this.checked, ${food.harga})">
                </td>
                <td>
                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="window['handler_${
                            this.#FormSelector
                        }'].deleteFood(${key}, ${food.harga})"></a>
                </td>
            </tr>
        `;
    }

    /**
     * Update food collumn element
     * @param {boolean} checked food status
     * @param {number} change price change
     */
    updateFoodStatus(checked, change) {
        if (checked) {
            this.#updateHarga(change);
        } else {
            this.#updateHarga(-change);
        }
    }

    /**
     * Delete food collumn element
     * @param {number} key element key
     * @param {number} change price change
     */
    deleteFood(key, change) {
        const isChecked = this.#FormElement
            .find(`input[name="foods_status[${key}]"]`)
            .is(":checked");
        if (isChecked) {
            this.#updateHarga(-change);
        }

        // find and delete element inside the form with name "food${index}"
        // use jquery
        this.#FormElement.find(`tr[id="food${key}"]`).remove();
    }
}

class MenuGiziHandler {
    /**
     * @type {MakananGizi[]}
     */
    Foods = [];

    constructor() {
        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
        // @ts-ignore
        this.Foods = window._foods;
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
            title: "Hapus menu gizi?",
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
     * Delete menu after confirmation
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

        fetch("/api/simrs/gizi/menu/destroy/" + id, {
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

const MenuGiziHandlerClass = new MenuGiziHandler();
