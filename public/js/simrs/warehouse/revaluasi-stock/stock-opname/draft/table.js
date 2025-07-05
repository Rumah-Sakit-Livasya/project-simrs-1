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
        this.#listenToDetailsButtonClick();
        this.#listenToQtyChange();
    }

    #listenToQtyChange() {
        const updateChildCol = this.updateChildCol;
        const updateHeadCol = this.updateHeadCol;
        this.#$Table.on("keyup change input", ".item-qty-actual", function () {
            const childRow = $(this).closest('tr'); // child <tr>
            const HeadKey = childRow.data("head_key");
            const parentRow = /** @type {JQuery<HTMLTableRowElement>} */($("#head" + HeadKey)); // parent <tr>
            updateChildCol(childRow);
            updateHeadCol(parentRow);
        });
    }

    /**
     * Update head column
     * @param {JQuery<HTMLTableRowElement>} tr 
     */
    updateHeadCol(tr) {
        // update adjustment, actual, and final
        // by summing childs' adjustment and final
        let AdjustmentCount = 0;
        let ActualCount = 0;
        let FinalCount = 0;

        const ChildTR = tr.next("tr").next("tr");
        const ChildTable = ChildTR.find("table.child");
        const ChildsTRs = ChildTable.find("tr.child-item");

        ChildsTRs.each(function () {
            const AdjCel = $(this).find(".item-qty-adjustment");
            const FinalCel = $(this).find(".item-qty-final");
            const ActualCel = $(this).find(".item-qty-actual");

            AdjustmentCount += parseInt(AdjCel.text());
            FinalCount += parseInt(FinalCel.text());

            const ActualCountCel = parseInt(/** @type {string} */(ActualCel.val()));
            if (!isNaN(ActualCountCel)) {
                ActualCount += ActualCountCel;
            }
        });

        const Adjustment = tr.find(".qty-adjustment");
        const Final = tr.find(".qty-final");
        const Actual = tr.find(".qty-actual");

        Adjustment.text(AdjustmentCount);
        Final.text(FinalCount);
        Actual.text(ActualCount);
    }

    /**
     * Update child column
     * @param {JQuery<HTMLTableRowElement>} tr 
     */
    updateChildCol(tr) {
        // update adjustment and final
        const Frozen = tr.find(".item-qty-frozen");        // frozen value
        const Adjustment = tr.find(".item-qty-adjustment");
        const Movement = tr.find(".item-qty-movement");  // movement value
        const Final = tr.find(".item-qty-final");
        const Input = tr.find(".item-qty-actual");

        if (!Input.val()) {
            Adjustment.text(0);
            Final.text(parseInt(Frozen.text()) + parseInt(Movement.text()));
            return;
        }

        // adjustment = actual - frozen
        const AdjustmentValue = /** @type {number} */(Input.val()) - parseInt(Frozen.text());
        Adjustment.text(AdjustmentValue);

        // final = frozen + adjustment + movement
        const FinalValue = parseInt(Frozen.text()) + AdjustmentValue + parseInt(Movement.text());
        Final.text(FinalValue);
    }

    #listenToDetailsButtonClick() {
        const Table = this.#$Table;
        const getChildCol = this.#getChildCol;

        Table.on('click', '.details-control', function () {
            const tr = $(this).closest('tr');
            const row = Table.row(tr);
            const Stack = /** @type {StackedStoredItemOpname} */(tr.data("item"));
            const Key = /** @type {number} */ (tr.data("key"));

            const isAlreadyShown = row.child.isShown();

            // First, close all other rows
            Table.rows().every(function () {
                const currentTr = $(this.node());
                if (this.child.isShown()) {
                    this.child.hide();
                    currentTr.removeClass('shown');
                }
            });

            // Then toggle this row
            if (!isAlreadyShown) {
                console.log("Hidden, showing now...");

                if (row.child() === undefined) {
                    const HTML = getChildCol(Stack, Key);
                    row.child($(HTML));
                }

                row.child.show();
                tr.addClass('shown');
            } else {
                console.log("Showing, hiding now...");
                row.child.hide();
                tr.removeClass('shown');
            }
        });
    }

    /**
     * Toggle show or hide child row based on applied filters
     * @param {"show" | "hide"} empty 
     * @param {"no" | "exp" | undefined} expired 
     */
    toggleChildFilter(empty, expired) {
        // show all child
        // because if the child is hidden
        // then jquery won't be able to select it
        this.#$Table.rows().every(function () {
            const currentTr = $(this.node());
            if (!this.child.isShown()) {
                this.child.show();
                currentTr.addClass('shown');
            }
        });

        // start filtering
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

        // finally, hide all child
        this.#$Table.rows().every(function () {
            const currentTr = $(this.node());
            if (this.child.isShown()) {
                this.child.hide();
                currentTr.removeClass('shown');
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
        // First, close all child rows
        this.#$Table.rows().every(function () {
            const currentTr = $(this.node());
            if (this.child.isShown()) {
                this.child.hide();
                currentTr.removeClass('shown');
            }
        });

        const HeadTRs = $("tr.head");
        HeadTRs.each(function () {
            const $TR = $(this);
            const Item = /** @type {StackedStoredItemOpname} */ ($TR.data("item"));
            if (!jenis && !kategori_id && !satuan_id) {
                $TR.show();
            } else {
                if (
                    (jenis ? (Item.type == jenis) : true) &&
                    (kategori_id ? (Item.barang.kategori_id == kategori_id) : true) &&
                    (satuan_id ? (Item.barang.satuan_id == satuan_id) : true)
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
        const Stacks = /** @type {StackedStoredItemOpname[]} */ ([]);

        for (let i = 0; i < items.length; i++) {
            const Item = items[i];
            const PBI = /** @type {PenerimaanBarangItem} */ (Item.pbi);
            const Barang = /** @type {BarangFarmasi | BarangNonFarmasi} */ (PBI.item);
            const Satuan = /** @type {Satuan} */ (PBI.satuan);

            if (Item.pbi === undefined) continue;
            const Head = Stacks.find(s =>
                s.barang_id === PBI.barang_id &&
                s.satuan_id === PBI.satuan_id &&
                s.type === Item.type);

            if (Head) {
                Head.stack.push(Item);
                Head.qty += Item.qty;
                Head.movement += Item.movement;
                Head.frozen += Item.frozen;
                if (!Head.actual) {
                    Head.actual = Item.opname ? Item.opname.qty : undefined;
                } else if (Item.opname) {
                    Head.actual += Item.opname.qty;
                }

            } else {
                Stacks.push({
                    actual: Item.opname ? Item.opname.qty : undefined,
                    frozen: Item.frozen,
                    movement: Item.movement,
                    qty: Item.qty,
                    barang_id: PBI.barang_id,
                    satuan_id: PBI.satuan_id,
                    barang: Barang,
                    satuan: Satuan,
                    type: Item.type,
                    stack: [Item]
                });
            }
        }

        Stacks.forEach((item) => {
            const HTML = this.#getHeadCol(item);
            this.#$Table.row.add($(HTML)).draw();

            const Table = this.#$Table;
            const getChildCol = this.#getChildCol;

            Table.rows().every(function () {
                const tr = $(this.node());
                const row = Table.row(tr);
                const Stack = /** @type {StackedStoredItemOpname} */(tr.data("item"));
                const Key = /** @type {number} */ (tr.data("key"));

                if (row.child() === undefined) {
                    const HTML = getChildCol(Stack, Key);
                    row.child($(HTML));
                }
            });
        });
    }

    /**
     * Get HTML for childs of a stock opname item in string
     * @param {StackedStoredItemOpname} head 
     * @param {number} head_key
     */
    #getChildCol(head, head_key) {
        const key = Math.round(Math.random() * 100000);
        const StatusClass = {
            "draft": "text-info",
            "final": "text-success"
        };

        function ucfirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        const Stacks = head.stack
            .map(item => /*html*/`
                <tr id="item${key}" class="child-item child" data-head_key="${head_key}" data-si_id="${item.id}" data-item='${JSON.stringify(item)}'>
                    <td>${item.pbi?.pb?.kode_penerimaan}</td>
                    <td>${item.pbi?.batch_no}</td>
                    <td>${new Date(item.pbi?.pb?.tanggal_terima || 0).toLocaleString("id-ID", { day: "2-digit", month: "long", year: "numeric" })}</td>
                    <td>${new Date(item.pbi?.tanggal_exp || 0).toLocaleString("id-ID", { day: "2-digit", month: "long", year: "numeric" })}</td>
                    <td class="item-qty-frozen">${item.frozen}</td>
                    <td><input type="number" name="qty[${key}]" value="${item.opname ? item.opname.qty : ''}" min="0" ${item.opname && item.opname.status == "final" ? 'readonly' : ''}
                        class="form-control item-qty-actual" onkeyup="Main.enforceNumberLimit(event)" onchange="Main.enforceNumberLimit(event)"></td>
                    <td class="item-qty-adjustment">${item.opname ? item.opname.qty - item.frozen : 0}</td>
                    <td class="item-qty-movement">${item.movement}</td>
                    <td class="item-qty-final">${item.qty + (item.opname ? item.opname.qty - item.qty : 0) + item.movement}</td>
                    <td><input type="text" name="keterangan[${key}]" ${item.opname && item.opname.status == "final" ? 'readonly' : ''}
                        class="form-control item-keterangan" value="${item.opname ? item.opname.keterangan : ''}"></td>
                    <td class="${item.opname && StatusClass[item.opname.status]} item-status">${item.opname ? ucfirst(item.opname.status) : 'Uncounted'}</td>
                </tr>
            `).join('\n');

        return /*html*/`
            <table class="table table-bordered table-hover table-striped w-100 child">
                <div class="row">
                    <div class="col-xl-3 text-center">
                        <button onclick="Main.stockEqual(${head_key})" class="btn btn-secondary waves-effect waves-themed">
                            <span class="mdi mdi-equal pointer mdi-12px"></span>
                            Samakan Stock
                        </button>
                    </div>
                    <div class="col-xl-3 text-center">
                        <button onclick="Main.stockZero(${head_key})" class="btn btn-secondary waves-effect waves-themed">
                            <span class="mdi mdi-numeric-0-box pointer mdi-12px"></span>
                            Nolkan Stock
                        </button>
                    </div>
                    <div class="col-xl-3 text-center">
                        <button onclick="Main.movementRefresh(${head_key})" class="btn btn-info waves-effect waves-themed">
                            <span class="mdi mdi-refresh pointer mdi-12px"></span>
                            Refresh Pergerakan
                        </button>
                    </div>
                    <div class="col-xl-3 text-center">
                        <button onclick="Main.saveDraft(${head_key})" class="btn btn-primary waves-effect waves-themed">
                            <span class="mdi mdi-content-save pointer mdi-12px"></span>
                            Simpan Draft
                        </button>
                    </div>
                </div>
                <thead class="bg-info-600">
                    <tr>
                        <th>Kode PB</th>
                        <th>No Batch</th>
                        <th>Tanggal Terima</th>
                        <th>Tanggal Exp</th>
                        <th>Stock System</th>
                        <th>Stock Actual</th>
                        <th>Adjustment</th>
                        <th>Pergerakan</th>
                        <th>Final Stock</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    ${Stacks}
                </tbody>
            </table>
        `;
    }

    /**
     * Get HTML for a stock opname item in string
     * @param {StackedStoredItemOpname} item 
     */
    #getHeadCol(item) {
        const key = Math.round(Math.random() * 100000);

        // <th>Detail</th>
        // <th>Kode Barang</th>
        // <th>Nama Barang</th>
        // <th>Satuan</th>
        // <th>Stock System</th>
        // <th>Stock Actual</th>
        // <th>Adjustment</th>
        // <th>Pergerakan</th>
        // <th>Final Stock</th>

        return /*html*/`
            <tr id="head${key}" data-item='${JSON.stringify(item)}' data-key="${key}" class="head">
                <td>
                    <button type="button" class="btn btn-sm btn-primary details-control">
                        <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                    </button>
                </td>
                <td>${item.barang.kode}</td>
                <td>${item.barang.nama}</td>
                <td>${item.satuan.kode}</td>
                <td>${item.frozen}</td>
                <td class="qty-actual">${item.actual ? item.actual : ''}</td>
                <td class="qty-adjustment">${item.actual ? item.actual - item.frozen : 0}</td>
                <td>${item.movement}</td>
                <td class="qty-final">${item.qty + (item.actual ? item.actual - item.qty : 0) + item.movement}</td>
            </tr>
        `;
    }

}
