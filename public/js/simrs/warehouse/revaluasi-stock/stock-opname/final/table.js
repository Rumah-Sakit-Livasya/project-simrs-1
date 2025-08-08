// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../../types.d.ts" />

class TableHandler {
    /**
     * @type {import("datatables.net").Api<any>}
     */
    #$Table;

    constructor() {
        this.#$Table = $("#datatable").DataTable();
    }

    /**
     * Builds the request body for final save
     * @param {number} sog_id Stock Opname Gudang id
     * @returns {FormData}
     */
    buildBodyForFinalSave(sog_id) {
        const Body = new FormData();
        const UserID = $("input[name='user_id']").val();

        this.#$Table.rows().every(function () {
            const $TR = $(this.node());
            const Item = /** @type {StoredItemOpname} */ ($TR.data("item"));
            Body.append(`sio_id[]`, String(Item.opname?.id));
        });

        Body.append("sog_id", String(sog_id));
        Body.append("user_id", String(UserID));
        console.log(Body.getAll('sio_id[]'));

        return Body;
    }

    /**
     * Toggle show or hide child row based on applied filters
     * @param {"show" | "hide"} empty 
     * @param {"no" | "exp" | undefined} expired 
     */
    toggleChildFilter(empty, expired) {
        const ChildTRs = $("tr.child"); // select with jquery
        ChildTRs.each(function () {
            const $TR = $(this);
            const Item = /** @type {StoredItemOpname} */ ($TR.data("item"));
            const Qty = parseInt($TR.find(".item-qty-final").text());

            if (!expired && empty == "show") {
                $TR.show();
            } else {
                // if empty == hide, hide items with 0 qty
                if (empty == "hide" && Qty == 0) $TR.hide();
                // if expired == "no", only show non-expired items or items without expired date
                else if (expired == "no" && (Item.pbi?.tanggal_exp ? new Date(Item.pbi?.tanggal_exp || 0) < new Date() : false)) $TR.hide();
                // if expired == "exp", only show expired items
                else if (expired == "exp" && (Item.pbi?.tanggal_exp ? new Date(Item.pbi?.tanggal_exp || 0) >= new Date() : false)) $TR.hide();
                else $TR.show();
            }
        });
    }

    /**
     * Toggle show or hide row based on applied filters
     * @param {"f" | "nf" | undefined} jenis 
     * @param {number | undefined} kategori_id 
     * @param {number | undefined} satuan_id 
     */
    toggleFilter(jenis, kategori_id, satuan_id) {
        const HeadTRs = $("tr.child"); // in final, there's no head. only child rows which becomes the head of its own
        HeadTRs.each(function () {
            const $TR = $(this);
            const Item = /** @type {StoredItemOpname} */ ($TR.data("item"));
            if (!jenis && !kategori_id && !satuan_id) {
                $TR.show();
            } else {
                if (
                    (jenis ? (Item.type == jenis) : true) &&
                    (kategori_id ? (Item.pbi?.item?.kategori_id == kategori_id) : true) &&
                    (satuan_id ? (Item.pbi?.item?.satuan_id == satuan_id) : true)
                ) {
                    $TR.show();
                }
                else $TR.hide();
            }
        });
    }

    /**
     * Update table with new items
     * @param {StoredItemOpname[]} items 
     */
    async updateTable(items) {
        this.#$Table.clear();

        if (!items.length) return this.#$Table.draw();
        // Add head items
        items.forEach(item => {
            const HTML = this.#getHeadCol(item);
            this.#$Table.row.add($(HTML)).draw();
        });

    }

    /**
     * Get HTML for a stock opname item in string
     * @param {StoredItemOpname} item 
     */
    #getHeadCol(item) {
        const key = Math.round(Math.random() * 100000);

        const StatusClass = {
            "draft": "text-info",
            "final": "text-success"
        };

        function ucfirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // <th>Kode PB</th>
        // <th>No Batch</th>
        // <th>Tanggal Terima</th>
        // <th>Tanggal Exp</th>
        // <th>System Stock</th>
        // <th>Actual Stock</th>
        // <th>Adjustment</th>
        // <th>Pergerakan</th>
        // <th>Final Stock</th>
        // <th>Keterangan</th>
        // <th>Status</th>

        return /*html*/`
                <tr id="item${key}" class="child-item child" data-si_id="${item.id}" data-item='${JSON.stringify(item)}'>
                    <td>${item.pbi?.pb?.kode_penerimaan}</td>
                    <td>${item.pbi?.batch_no}</td>
                    <td>${new Date(item.pbi?.pb?.tanggal_terima || 0).toLocaleString("id-ID", { day: "2-digit", month: "long", year: "numeric" })}</td>
                    <td>${new Date(item.pbi?.tanggal_exp || 0).toLocaleString("id-ID", { day: "2-digit", month: "long", year: "numeric" })}</td>
                    <td class="item-qty-frozen">${item.frozen}</td>
                    <td><input type="number" name="qty[${key}]" value="${item.opname ? item.opname.qty : ''}" min="0" readonly
                        class="form-control item-qty-actual" onkeyup="Main.enforceNumberLimit(event)" onchange="Main.enforceNumberLimit(event)"></td>
                    <td class="item-qty-adjustment">${item.opname ? item.opname.qty - item.frozen : 0}</td>
                    <td class="item-qty-movement">${item.movement}</td>
                    <td class="item-qty-final">${item.qty + (item.opname ? item.opname.qty - item.qty : 0) + item.movement}</td>
                    <td><input type="text" name="keterangan[${key}]" readonly
                        class="form-control item-keterangan" value="${item.opname ? item.opname.keterangan : ''}"></td>
                    <td class="${item.opname && StatusClass[item.opname.status]} item-status">${item.opname ? ucfirst(item.opname.status) : 'Uncounted. Should never appears here!'}</td>
                </tr>
        `;
    }

}
