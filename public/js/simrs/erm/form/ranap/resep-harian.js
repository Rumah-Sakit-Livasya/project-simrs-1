// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class ResepHarianClass {
    #$Table = $("#table_re");
    #$GudangSelect = $("#cppt_gudang_id");
    #$BarangSelect = $("#cppt_barang_id");
    #$Loadings = $(".loading");
    #$LoadingsMessage = $(".loading-message");
    #API_URL = "/api/simrs/poliklinik";

    constructor() {
        this.#showLoading(false);
        $("#simpan-btn").on("click", this.#handleFormSubmit.bind(this));
        $(document).on(
            "change input keyup",
            "input[type='number']",
            ResepHarianClass.enforceNumberLimit
        );
        $(document).on(
            "change input keyup",
            "input[type='number']",
            this.refreshTotal.bind(this)
        );
        this.#$GudangSelect.on("select2:select", (e) =>
            this.#handleGudangSelect.bind(this, e)()
        );
        this.#$BarangSelect.on("select2:select", (e) =>
            this.#handleBarangSelect.bind(this, e)()
        );
    }

    #handleFormSubmit(event) {
        // check if textarea #resep_manual is empty
        // and if there's no tr.item-obat element
        if (
            $("textarea#resep_manual").val() === "" &&
            $("tr.item-obat").length === 0
        ) {
            event.preventDefault();
            showErrorAlertNoRefresh(
                "Mohon isi resep manual atau tambahkan obat resep elektronik!"
            );
        }
    }

    /**
     * Handle gudang change
     * @param {Select2.Event<HTMLElement, Select2.DataParams>} event
     */
    #handleBarangSelect(event) {
        event.preventDefault();
        // get selected id
        const selectedId = event.params.data.id;

        // unselect the select2
        // @ts-ignore
        this.#$BarangSelect.val(null).trigger("change");

        //get data-item
        const $option = $(`option[value='${selectedId}'].obat`);

        const item = /** @type {BarangFarmasi & {qty: number}} */ (
            $option.data("item")
        );

        // prevent duplicate
        const Obat = $("tr.obat-" + item.id);
        if (Obat.length > 0) {
            // add qty 1 to input with name^=qty in the row
            const QtyInput = Obat.find("input[name^=qty_perhari]");
            QtyInput.val(parseInt(String(QtyInput.val())) + 1);
            QtyInput.trigger("change");
            this.refreshTotal();
            return;
        }

        const key = Math.round(Math.random() * 100000);

        // <th style="width: 10%;">UOM</th>
        // <th style="width: 5%;">Stok</th>
        // <th style="width: 10%;">Qty Perhari</th>
        // <th style="width: 10%;">Jumlah Hari</th>
        // <th style="width: 10%;">Total Qty</th>
        // <th style="width: 15%">Signa</th>
        // <th style="width: 1%;">Aksi</th>
        //
        // insert to this.#$Table
        this.#$Table.append(/*html*/ `
                <tr id="item${key}" class="item-obat obat-${item.id}">
                    <input type="hidden" name="barang_id[${key}]" value="${item.id}">

                    <td>${item.nama}</td>
                    <td>${item.satuan?.kode}</td>
                    <td>${item.qty}</td>
                    <td><input type="number" name="qty_perhari[${key}]" min="1" step="1" class="form-control" value="1" max="${item.qty}"></td>
                    <td><input type="number" name="qty_hari[${key}]" min="1" step="1" class="form-control" value="1"></td>
                    <td class="total-qty">1</td>
                    <td><input type="text" name="signa[${key}]" class="form-control"></td>
                    <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="ResepHarian.deleteItem(${key})"></a></td>
                </tr>
            `);

        this.refreshTotal();
    }

    refreshTotal() {
        let total = 0;
        this.#$Table.find("tr.item-obat").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=qty_perhari]");
            const hariEl = $(tr).find("input[name^=qty_hari]");
            if (!qtyEl || !hariEl) return;

            const qty = parseInt(String(qtyEl.val()));
            const hari = parseInt(String(hariEl.val()));
            if (isNaN(qty) || isNaN(hari)) return;

            const qty_total = qty * hari;
            $(tr).find("td.total-qty").text(qty_total);
        });
    }

    deleteItem(key) {
        this.#$Table.find("#item" + key).remove();
        this.refreshTotal();
    }

    /**
     * Handle gudang change
     * @param {Select2.Event<HTMLElement, Select2.DataParams>} event
     */
    #handleGudangSelect(event) {
        event.preventDefault();
        // get selected id
        const selectedId = event.params.data.id;

        this.#showLoading(true, "Fetching Items...");
        const url = `/obat/${selectedId}`;
        this.#APIfetch(url)
            .then((response) => {
                // add to select2 options
                this.#$BarangSelect.empty();
                this.#$BarangSelect.append(new Option("", ""));
                response.items.forEach((item) => {
                    // this.#$BarangSelect.append(new Option(`${item.nama} (Stock: ${item.qty})`, item.id));
                    this.#$BarangSelect.append(
                        $(/*html*/ `
                            <option value="${
                                item.id
                            }" data-item='${JSON.stringify(item)}' class="obat">
                                ${item.nama} (Stock: ${item.qty})
                            </option>
                        `)
                    );
                });
                this.#$BarangSelect.trigger("change"); // trigger change event to update select2
            })
            .catch((error) => {
                showErrorAlertNoRefresh(error.message);
            })
            .finally(() => this.#showLoading(false));
    }

    /**
     * Show or hide the loading icon
     * @param {boolean} show
     * @param {string?} message
     */
    #showLoading(show, message = null) {
        this.#$Loadings.toggle(show);

        if (message) {
            this.#$LoadingsMessage.text(message);
        } else {
            this.#$LoadingsMessage.text("Loading...");
        }
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
     * Make a fetch call with API URL as base URL
     * @param {string} url
     * @param {any | null} body
     * @param {"GET" | "POST" | "PATCH" | "PUT" | "DELETE"} method
     */
    #APIfetch(url, body = null, method = "GET", raw = false) {
        return new Promise((resolve, reject) => {
            fetch(this.#API_URL + url, {
                method: method,
                body: body,
                headers: {
                    "Content-Type": "application/json",
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
}

const ResepHarian = new ResepHarianClass();
