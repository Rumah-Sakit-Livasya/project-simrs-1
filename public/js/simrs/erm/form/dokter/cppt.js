// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

// @ts-ignore
const Swal = /** @type {import("sweetalert2").default} */ (window.Swal);

class CPPTDokterClass {
    #$Table = $("#table_re");
    #$GudangSelect = $("#cppt_gudang_id");
    #$BarangSelect = $("#cppt_barang_id");
    #$Loadings = $(".loading");
    #$LoadingsMessage = $(".loading-message");
    #$GrandTotalDisplay = $("#grand_total");
    #$GrandTotal = $("#total_harga_obat");
    #API_URL = "/api/simrs/poliklinik";

    constructor() {
        this.#showLoading(false);
        this.#$GudangSelect.on("select2:select", (e) =>
            this.#handleGudangSelect.bind(this, e)()
        );
        this.#$BarangSelect.on("select2:select", (e) =>
            this.#handleBarangSelect.bind(this, e)()
        );
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

        // <th style="width: 1%;">Aksi</th>
        // <th style="width: 25%;">Nama Obat</th>
        // <th style="width: 10%;">UOM</th>
        // <th style="width: 5%;">Stok</th>
        // <th style="width: 10%;">Qty</th>
        // <th style="width: 5%;">Harga</th>
        // <th style="width: 15%">Signa</th>
        // <th style="width: 15%">Instruksi</th>
        // <th style="width: 10%;">Subtotal Harga</th>
        //
        // insert to this.#$Table
        this.#$Table.append(/*html*/ `
                <tr id="item${key}" class="item-obat obat-${item.id}">
                    <input type="hidden" name="hna[${key}]" value="${item.hna}">
                    <input type="hidden" name="barang_id[${key}]" value="${
            item.id
        }">
                    <input type="hidden" name="subtotal[${key}]" value="${
            item.hna
        }">

                    <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                        title="Hapus" onclick="CPPTDokter.deleteItem(${key})"></a></td>
                    <td>${item.nama}</td>
                    <td>${item.satuan?.kode}</td>
                    <td>${item.qty}</td>
                    <td><input type="number" name="qty[${key}]" min="1" step="1" class="form-control" value="1" max="${
            item.qty
        }"
                    onkeyup="CPPTDokter.refreshTotal()" onchange="CPPTDokter.refreshTotal()"></td>
                    <td>${this.#rp(item.hna)}</td>
                    <td><input type="text" name="signa[${key}]" class="form-control"></td>
                    <td><input type="text" name="instruksi_obat[${key}]" class="form-control"></td>
                    <td class="subtotal">${this.#rp(item.hna)}</td>
                </tr>
            `);

        this.refreshTotal();
    }

    refreshTotal() {
        let total = 0;
        this.#$Table.find("tr.item-obat").each((i, tr) => {
            const qtyEl = $(tr).find("input[name^=qty]");
            const hnaEl = $(tr).find("input[name^=hna]");
            if (!qtyEl || !hnaEl) return;

            const qty = parseInt(String(qtyEl.val()));
            const hna = parseInt(String(hnaEl.val()));
            if (isNaN(qty) || isNaN(hna)) return;

            const subtotal = qty * hna;

            total += subtotal;
            $(tr).find("td.subtotal").text(this.#rp(subtotal));
            $(tr).find("input[name^=subtotal]").val(subtotal);
        });

        this.#$GrandTotalDisplay.text(this.#rp(total));
        this.#$GrandTotal.val(total);
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

const CPPTDokter = new CPPTDokterClass();
