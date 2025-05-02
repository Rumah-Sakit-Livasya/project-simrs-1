// @ts-check
/// <reference types="jquery" />
/// <reference path="../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class PopupOrderGiziHandler {
    /**
     * @type {MakananGizi[]}
     */
    #Foods = [];

    /**
     * @type {JQuery<HTMLFormElement>}
     */
    #FormElement;

    constructor() {
        document.addEventListener("DOMContentLoaded", this.#init.bind(this));
        this.#FormElement = $("#form-order-gizi");

        // @ts-ignore
        this.#Foods = window._foods;
    }

    #init() {
        this.#addEventListeners("#searchMenuInput", this.#handleMenuSearchBar, "keyup");
        this.#addEventListeners("#search-food", this.#onFoodSelect, "change");
    }

    /**
     * Handle on select2 food select
     * @param {Event} event 
     */
    #onFoodSelect(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const id = parseInt(input.value);
        input.value = ""; // reset

        const food = this.#Foods.find(food => food.id === id);
        if (!food) return alert("Food not found");
        this.#FormElement
            .find('#table-food')
            .append($(this.#getFoodTableCol(food)));

        this.updateHarga();
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

    #handleMenuSearchBar(event) {
        const searchInput = /** @type {HTMLInputElement} */ (event.target);
        const menus = document.querySelectorAll(".pointer");

        const value = searchInput.value.toLowerCase();
        menus.forEach((menu) => {
            if (!menu) return;
            const menuNameElement = menu.querySelector(".menu-name");
            if (!menuNameElement) return;
            const menuName = menuNameElement.textContent;
            if (!menuName) return;

            // @ts-ignore
            menu.style.display = menuName.toLowerCase().includes(value) ? "" : "none";
        });
    }

    /**
     * Update price input and display
     */
    updateHarga() {
        let total = 0;

        // first, get foods id from input with name "foods_id[]"
        const foodsId = document.querySelectorAll('input[name^="foods_id"]');
        foodsId.forEach((element) => {
            const input = /** @type {HTMLInputElement} */ (element);
            const key = input.getAttribute("data-key");
            const id = parseInt(input.value);

            // find from #Foods with id equals to id we got from input.value
            const food = this.#Foods.find(food => food.id == id);
            if (!food) return showErrorAlertNoRefresh("Food not found! id: " + id);

            // get input with name "qty[${key}]"
            const qtyInput = /** @type {HTMLInputElement} */ (document.querySelector(`input[name="qty[${key}]"]`));
            const qty = parseInt(qtyInput.value);

            // add food price * qty to total
            total += food.harga * qty;
        });

        // update price
        this.#FormElement.find('input[name="total_harga"]').val(total);
        this.#FormElement.find('#harga-display').text(`Rp ${total.toLocaleString('id-ID')}`);
    }

    /**
     * Handle event when a menu is selected
     * @param {MakananGizi[]} foods 
     */
    menuSelect(foods) {
        const foodMap = new Map();

        foods.forEach(food => {
            if (foodMap.has(food.id)) {
                foodMap.get(food.id).quantity += 1;
            } else {
                foodMap.set(food.id, { ...food, quantity: 1 });
            }
        });

        foodMap.forEach(food => {
            this.#FormElement
                .find('#table-food')
                .append($(this.#getFoodTableCol(food, food.quantity)));
        });

        this.updateHarga();
    }

    /**
     * Generate HTML string for food table collumn
     * @param {MakananGizi} food 
     * @param {number} qty
     * @returns {string} HTML String
     */
    #getFoodTableCol(food, qty = 1) {
        const key = Math.round(Math.random() * 100000);

        return /*html*/`
                <tr id="food${key}">
                    <td>${food.nama}</td>
                    <td>
                        <input type="hidden" data-key="${key}" name="foods_id[${key}]" value="${food.id}">
                        <input type="number" data-key="${key}" name="qty[${key}]" value="${qty}" class="qty"
                            onchange="MainClass.quantityChange(event)">
                    </td>
                    <td id="harga${key}">Rp ${food.harga.toLocaleString('id-ID')}</td>
                    <td>
                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                            title="Hapus" onclick="MainClass.deleteFood(${key})"></a>
                    </td>
                </tr>
            `;
    }

    /**
     * Handle quantity change
     * @param {Event} event 
     */
    quantityChange(event) {
        const input = /** @type {HTMLInputElement} */ (event.target);
        const qty = parseInt(input.value);

        if (qty < 1) {
            input.value = "1";
        }

        this.updateHarga();
    }

    /**
     * Delete food collumn element
     * @param {number} key element key
     */
    deleteFood(key) {
        // find and delete element inside the form with name "food${index}"
        // use jquery
        this.#FormElement.find(`tr[id="food${key}"]`).remove();

        this.updateHarga();
    }

}

const PopupOrderGiziClass = new PopupOrderGiziHandler();