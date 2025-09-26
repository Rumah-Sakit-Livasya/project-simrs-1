// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class ReturResepHandler {
    /**
     * @type {JQuery<HTMLElement>}
     */
    #$AddModal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingIcon;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$LoadingPage;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Table;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$ModalTable;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Total;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Nominal;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Patient;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$PatientName;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$Registration;

    /**
     * @type {JQuery<HTMLElement>}
     */
    #$RMReg;

    /**
     * @type {string[]}
     */
    #SelectedItems = [];

    #API_URL = "/api/simrs/farmasi/retur-resep";

    constructor() {
        this.#$AddModal = $("#pilihItemModal");
        this.#$LoadingIcon = $("#loading-spinner");
        this.#$LoadingPage = $("#loading-page");
        this.#$Table = $("#tableItems");
        this.#$ModalTable = $("#itemTable");
        this.#$Total = $("#total-display");
        this.#$Nominal = $("input[name='nominal']");
        this.#$Patient = $("#patient_id");
        this.#$PatientName = $("#nama_pasien");
        this.#$Registration = $("#registration_id");
        this.#$RMReg = $("#rm_reg");

        this.#init();
    }

    #init() {
        this.#addEventListeners("#add-btn", this.#handleAddButtonClick);
        this.#addEventListeners(
            "#searchItemInput",
            this.#handleItemSearchBar,
            "keyup"
        );
        this.#addEventListeners(
            "#searchNoResepInput",
            this.#handleItemSearchBar,
            "keyup"
        );
        $(document).on(
            "change input keyup",
            "input[type='number']",
            ReturResepHandler.enforceNumberLimit
        );
        $(document).on(
            "change input keyup",
            "input[type='number']",
            this.refreshTotal.bind(this)
        );
        $("#patient_id").on("select2:select", () => {
            this.#reset.bind(this)();
            this.#updateRegistrationList.bind(this)();
        });
        this.#showLoading(false);
    }

    /**
     * Generates the HTML string for an "obat" (drug) select option.
     * @param {Registration} registration
     * @returns  {string} The HTML string for the Obat Select option.
     */
    getRegistrationSelectHTML(registration) {
        let Tipe = "Rawat Jalan";
        if (registration.registration_type == "rawat-inap") {
            Tipe = `Rawat inap [${registration.kelas_rawat?.kelas} / ${registration.patient?.bed?.room?.ruangan} / ${registration.patient?.bed?.nama_tt}]`;
        }
        return /*html*/ `
                <option value="${registration.id}" class="registrasi">
                    ${registration.registration_number} (${this.#tgl(
            registration.registration_date
        )} - ${Tipe})
                </option>
            `;
    }

    #tgl(tanggal) {
        // format sql date to local date that outputs like this:
        // 08 Jan 2025
        return new Date(tanggal).toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    }

    async #updateRegistrationList() {
        this.#showLoading(true);
        const Registrations = await this.#APIfetch(
            `/get/registrations/${this.#$Patient.val()}`,
            null,
            "GET"
        )
            .catch((error) => showErrorAlertNoRefresh(error.message))
            .finally(() => this.#showLoading(false));

        this.#$Registration.empty().append(new Option("Pilih Registrasi", ""));
        Registrations.forEach((registration) => {
            const option = $(this.getRegistrationSelectHTML(registration));
            this.#$Registration.append(option);
        });

        this.#$Registration.find("option:first").prop("disabled", true);
        this.#$Registration.trigger("change");
    }

    #reset() {
        this.#SelectedItems = [];
        this.#$Table.empty();
        // this.#$Patient.val('');
        this.#$PatientName.val("").trigger("change");
        this.#$Registration.val("").trigger("change");
        this.#$RMReg.val("");
        this.#$Total.text("0");
        this.#$Nominal.val("0");
        this.refreshTotal();
    }

    /**
     * Handle item search bar on key up
     * @param {Event} event
     */
    #handleItemSearchBar(event) {
        const ItemName = String($("#searchItemInput").val());
        const RecipeNo = String($("#searchNoResepInput").val());
        const items = document.querySelectorAll("tr.item-pilih-obat");

        items.forEach((item) => {
            if (!item) return;

            let nama_barang = "";
            let kode_barang = "";
            let no_resep = "";
            let no_batch = "";

            const itemNameElement = item.querySelector(".item-name");
            if (!itemNameElement) return;
            nama_barang = itemNameElement.textContent || "";

            const itemCodeElement = item.querySelector(".item-code");
            if (!itemCodeElement) return;
            kode_barang = itemCodeElement.textContent || "";

            const batchNoElement = item.querySelector(".batch-no");
            if (!batchNoElement) return;
            no_batch = batchNoElement.textContent || "";

            const recipeNoElement = item.querySelector(".recipe-no");
            if (!recipeNoElement) return;
            no_resep = recipeNoElement.textContent || "";

            if (
                kode_barang.toLowerCase().includes(ItemName) ||
                no_batch.toLowerCase().includes(ItemName) ||
                no_resep.toLowerCase().includes(RecipeNo) ||
                nama_barang.toLowerCase().includes(ItemName)
            ) {
                // @ts-ignore
                item.style.display = "";
            } else {
                // @ts-ignore
                item.style.display = "none";
            }
        });
    }

    /**
     * Add selected item from modal to table
     * @param {FarmasiResepItems} item
     * @returns void
     */
    addItem(item) {
        if (!item.stored || !item.stored.pbi) {
            return alert("Item tidak lengkap!");
        }

        const FRI = $("tr.fri" + item.id);
        if (FRI.length > 0) {
            // add qty 1 to input with name^=qty in the row
            const QtyInput = FRI.find("input[name^=qty]");
            QtyInput.val(parseInt(String(QtyInput.val())) + 1);
            QtyInput.trigger("change");
        } else {
            const HTML = this.#getItemTableCol(item);
            if (!HTML) return; // error occured and alerted
            this.#$Table.append(HTML);
        }
        this.refreshTotal();
    }

    /**
     * Generate HTML string for Item table collumn
     * @param {FarmasiResepItems} item
     */
    #getItemTableCol(item) {
        const key = Math.round(Math.random() * 100000);

        // <th>Kode Barang</th>
        // <th>Nama Barang</th>
        // <th>Satuan</th>
        // <th>Qty</th>
        // <th>Harga</th>
        // <th>Subtotal</th>
        // <th>Aksi</th>

        return /*html*/ `
            <tr id="item${key}" class="fri${item.id}">
                <input type="hidden" name="item_id[${key}]" value="${item.id}">
                <input type="hidden" name="hna[${key}]" value="${item.harga}">
                <input type="hidden" name="subtotal[${key}]" value="${
            item.harga
        }">

                <td>${item.stored?.pbi?.kode_barang}</td>
                <td>${item.stored?.pbi?.nama_barang}</td>
                <td>${item.stored?.pbi?.unit_barang}</td>
                <td>${item.stored?.pbi?.batch_no}</td>
                <td><input type="number" name="qty[${key}]" class="form-control" value="1" min="1" max="${
            item.qty
        }"></td>
                <td>${this.#rp(item.harga)}</td>
                <td class='subtotal'>${this.#rp(item.harga)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                    title="Hapus" onclick="ReturResepClass.deleteItem(${key})"></a></td>
            </tr>
        `;
    }

    /**
     * Enforce number input min max limit on manual input
     * @param {Event} event
     */
    static enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0));
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER));

        if (isNaN(value)) {
            inputField.value = "";
            return;
        }

        if (value < min) {
            inputField.value = String(min);
        } else if (value > max) {
            inputField.value = String(max);
        }
    }

    /**
     * Enforce number input min max limit on manual input
     * @param {Event} event
     */
    enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0)); // Default to 0 if not set
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER)); // Set default to a large number

        if (isNaN(value)) {
            inputField.value = ""; // Reset to empty on invalid input
            return this;
        }

        if (value < min) {
            inputField.value = String(min); // Clamp value at min
        } else if (value > max) {
            inputField.value = String(max); // Clamp value at max
        }

        return this;
    }

    refreshTotal() {
        console.log("refreshing total...");

        let total = 0;
        this.#$Table.find("tr").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=qty]");
            const hnaEl = $(tr).find("input[name^=hna]");
            if (!qtyEl || !hnaEl) return alert("Element not found!");

            const qty = parseInt(String(qtyEl.val()));
            const hna = parseInt(String(hnaEl.val()));
            if (isNaN(qty) || isNaN(hna))
                return alert("Qty or HNA is not a number!");

            let subtotal = qty * hna;
            console.log(qty, hna, subtotal);

            total += subtotal;
            $(tr).find("td.subtotal").text(this.#rp(subtotal));
            $(tr).find("input[name^=subtotal]").val(subtotal);
        });

        this.#$Total.text(this.#rp(total));
        this.#$Nominal.val(total);
    }

    /**
     * Delete item from table and variable
     * @param {string} key
     * @param {string} code
     */
    deleteItem(key, code) {
        this.#$Table.find("#item" + key).remove();
        // remove an item from this.#SelectedItems
        // where value is code
        this.#SelectedItems = this.#SelectedItems.filter(
            (item) => item !== code
        );

        this.refreshTotal();
    }

    /**
     * Handle add button click
     * @param {Event} event
     */
    async #handleAddButtonClick(event) {
        event.preventDefault();
        const patientId = /** @type {string} */ (this.#$Patient.val());
        const registrationId = /** @type {string} */ (
            this.#$Registration.val()
        );
        if (!patientId || !registrationId) {
            showErrorAlertNoRefresh(
                "Pilih pasien dan registrasi terlebih dahulu!"
            );
            return;
        }

        this.#showLoading(true);
        const url = "/get/item-registration/" + registrationId;

        const HTML = await (
            await this.#APIfetch(url, null, "GET", true)
        ).text();
        this.#showLoading(false);

        this.#$ModalTable.html(HTML);
        this.#$AddModal.modal("show");
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
     * Show or hide the loading icon
     * @param {boolean} show
     */
    #showLoading(show) {
        this.#$LoadingIcon.toggle(show);
        this.#$LoadingPage.toggle(show);
    }

    /**
     * Make a fetch call with API URL as base URL
     * @param {string} url
     * @param {FormData | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
            })
                .then(async (response) => {
                    if (response.status != 200) {
                        throw new Error("Error: " + response.statusText);
                    }
                    resolve(!raw ? await response.json() : response);
                })
                .catch((error) => {
                    console.log("Error:", error);

                    // @ts-ignore
                    if (this.#showLoading) this.#showLoading(false); // assert

                    showErrorAlertNoRefresh(`Error: ${error}`);
                    return reject(error);
                });
        });
    }

    /**
     * Format angka menjadi mata uang rupiah
     * @param {number} amount
     * @returns
     */
    #rp(amount) {
        const formattedAmount = "Rp " + amount.toLocaleString("id-ID");
        return formattedAmount;
    }
}

const ReturResepClass = new ReturResepHandler();
