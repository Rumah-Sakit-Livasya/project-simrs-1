// @ts-check
/// <reference types="jquery" />
/// <reference path="../../../../../../types.d.ts" />

class TableHandler {
    /**
     * @type {import("datatables.net").Api<any>}
     */
    #$Table;

    constructor() {
        this.#instantiateDatatable();
        this.#listenToDetailsButtonClick();
    }

    #instantiateDatatable() {
        this.#$Table = $("#datatable").DataTable({
            // @ts-ignore
            responsive: true,
            lengthChange: false,
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                extend: 'pdfHtml5',
                text: 'PDF',
                titleAttr: 'Generate PDF',
                className: 'btn-outline-danger btn-sm mr-1'
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                titleAttr: 'Generate Excel',
                className: 'btn-outline-success btn-sm mr-1'
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                titleAttr: 'Generate CSV',
                className: 'btn-outline-primary btn-sm mr-1'
            },
            {
                extend: 'copyHtml5',
                text: 'Copy',
                titleAttr: 'Copy to clipboard',
                className: 'btn-outline-primary btn-sm mr-1'
            },
            {
                extend: 'print',
                text: 'Print',
                titleAttr: 'Print Table',
                className: 'btn-outline-primary btn-sm'
            }]
        });
    }

    #listenToDetailsButtonClick() {
        const Table = this.#$Table;
        const getChildCol = this.#getChildCol;

        Table.on('click', '.details-control', function () {
            const tr = $(this).closest('tr');
            const row = Table.row(tr);
            const Stack = /** @type {StockDetails} */(tr.data("item"));
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

    // /**
    //  * Toggle show or hide child row based on applied filters
    //  * @param {"show" | "hide"} empty 
    //  * @param {"no" | "exp" | undefined} expired 
    //  */
    // toggleChildFilter(empty, expired) {
    //     // show all child
    //     // because if the child is hidden
    //     // then jquery won't be able to select it
    //     this.#$Table.rows().every(function () {
    //         const currentTr = $(this.node());
    //         if (!this.child.isShown()) {
    //             this.child.show();
    //             currentTr.addClass('shown');
    //         }
    //     });

    //     // start filtering
    //     const ChildTRs = $("tr.child"); // select with jquery
    //     ChildTRs.each(function () {
    //         const $TR = $(this);
    //         const Item = /** @type {StoredItemOpname} */ ($TR.data("item"));
    //         const Qty = parseInt($TR.find(".item-qty-final").text());

    //         if (!expired && empty == "show") {
    //             $TR.show();
    //         } else {
    //             // if empty == hide, hide items with 0 qty
    //             if (empty == "hide" && Qty == 0) $TR.hide();
    //             // if expired == "no", only show non-expired items or items without expired date
    //             else if (expired == "no" && (Item.pbi?.tanggal_exp ? new Date(Item.pbi?.tanggal_exp || 0) < new Date() : false)) $TR.hide();
    //             // if expired == "exp", only show expired items
    //             else if (expired == "exp" && (Item.pbi?.tanggal_exp ? new Date(Item.pbi?.tanggal_exp || 0) >= new Date() : false)) $TR.hide();
    //             else $TR.show();
    //         }
    //     });

    //     // finally, hide all child
    //     this.#$Table.rows().every(function () {
    //         const currentTr = $(this.node());
    //         if (this.child.isShown()) {
    //             this.child.hide();
    //             currentTr.removeClass('shown');
    //         }
    //     });
    // }

    // /**
    //  * Toggle show or hide row based on applied filters
    //  * @param {"f" | "nf" | undefined} jenis 
    //  * @param {number | undefined} kategori_id 
    //  * @param {number | undefined} satuan_id 
    //  */
    // toggleFilter(jenis, kategori_id, satuan_id) {
    //     // First, close all child rows
    //     this.#$Table.rows().every(function () {
    //         const currentTr = $(this.node());
    //         if (this.child.isShown()) {
    //             this.child.hide();
    //             currentTr.removeClass('shown');
    //         }
    //     });

    //     const HeadTRs = $("tr.head");
    //     HeadTRs.each(function () {
    //         const $TR = $(this);
    //         const Item = /** @type {StackedStoredItemOpname} */ ($TR.data("item"));
    //         if (!jenis && !kategori_id && !satuan_id) {
    //             $TR.show();
    //         } else {
    //             if (
    //                 (jenis ? (Item.type == jenis) : true) &&
    //                 (kategori_id ? (Item.barang.kategori_id == kategori_id) : true) &&
    //                 (satuan_id ? (Item.barang.satuan_id == satuan_id) : true)
    //             ) {
    //                 $TR.show();
    //             }
    //             else $TR.hide();
    //         }
    //     });
    // }

    /**
     * Update table with new items
     * @param {StockDetails[]} items 
     */
    async updateTable(items) {
        this.#$Table.clear();

        let iteration = 0;
        items.forEach((item) => {
            const HTML = this.#getHeadCol(item, ++iteration);
            this.#$Table.row.add($(HTML)).draw();

            const Table = this.#$Table;
            const getChildCol = this.#getChildCol;

            Table.rows().every(function () {
                const tr = $(this.node());
                const row = Table.row(tr);
                const Stack = /** @type {StockDetails} */(tr.data("item"));
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
     * @param {StockDetails} head 
     * @param {number} head_key
     */
    #getChildCol(head, head_key) {
        const key = Math.round(Math.random() * 100000);
        let qty = head.qty_finish;
        const Stacks = head.logs
            .map(item => {
                // const code is an attribute in item.source where the attribute name starts with 'kode'
                const code = item.source ? item.source[Object.keys(item.source).find(key => key.startsWith('kode')) || ''] : '';
                const adjustment = item.after_qty - (item.before_qty || 0);
                const sign = adjustment > 0 ? '+' : '';
                const start = qty - adjustment;
                const finish = qty;
                qty -= adjustment; // Update the quantity for the next iteration

                return /*html*/`
                    <tr id="item${key}" class="child-item child" data-head_key="${head_key}" data-si_id="${item.id}" data-item='${JSON.stringify(item)}'>
                        <td>${new Date(item.updated_at).toLocaleString("id-ID", { day: "2-digit", month: "long", year: "numeric" })}</td>
                        <td>${code}</td>
                        <td>${item.before_gudang?.nama || item.after_gudang?.nama}</td>
                        <td>${item.after_gudang?.nama}</td>
                        <td>${item.source?.keterangan || ''}</td>
                        <td>${start}</td>
                        <td>${sign}${adjustment}</td>
                        <td>${finish}</td>
                        <td>${item.user?.name}</td>
                    </tr>
            `}).join('\n');

        return /*html*/`
            <table class="table table-bordered table-hover table-striped w-100 child">
                <thead class="bg-info-600">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Gudang Awal</th>
                        <th>Gudang Akhir</th>
                        <th>Keterangan</th>
                        <th>Stock Awal</th>
                        <th>Adjustment</th>
                        <th>Stock Akhir</th>
                        <th>User</th>
                    </tr>
                </thead>

                <tbody>
                    ${Stacks}
                </tbody>
            </table>
        `;
    }

    /**
     * Get HTML for an item in string
     * @param {StockDetails} item 
     * @param {number} iteration 
     */
    #getHeadCol(item, iteration) {
        const key = Math.round(Math.random() * 100000);
        const sign = item.adjustment > 0 ? '+' : '';

        // <th>#</th>
        // <th>Detail</th>
        // <th>Kode Barang</th>
        // <th>Nama Barang</th>
        // <th>Satuan</th>
        // <th>Kategori</th>
        // <th>Golongan</th>
        // <th>HNA</th>
        // <th>Stock Awal</th>
        // <th>Masuk</th>
        // <th>Keluar</th>
        // <th>Adjustment</th>
        // <th>Stock Akhir</th>
        // <th>Nominal</th>
        // <th>Expired</th>

        return /*html*/`
            <tr id="head${key}" data-item='${JSON.stringify(item)}' data-key="${key}" class="head">
                <td>${iteration}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary details-control">
                        <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                    </button>
                </td>
                <td>${item.kode}</td>
                <td>${item.nama}</td>
                <td>${item.satuan?.kode}</td>
                <td>${item.kategori?.nama}</td>
                <td>${item.golongan?.nama}</td>
                <td>${this.#rp(item.hna)}</td>
                <td class="qty-start">${item.qty_start}</td>
                <td class="qty-in">+${item.qty_in}</td>
                <td class="qty-out">${item.qty_out}</td>
                <td class="adjustment">${sign}${item.adjustment}</td>
                <td class="qty-finish">${item.qty_finish}</td>
                <td>${this.#rp(item.qty_finish * item.hna)}</td>
                <td class="qty-expired">${item.qty_expired}</td>
            </tr>
        `;
    }

    /**
     * Print table
     * @param {string} print_template 
     * @param {string} periode 
     */
    print(print_template, periode) {
        const table = this.#$Table;
        let fullHtml = "";

        // 1. Save current page number (optional)
        const currentPage = table.page();

        // 2. Turn off pagination (show all rows)
        table.page.len(-1).draw();

        // 3. Show all child rows
        table.rows().every(function () {
            const currentTr = $(this.node());
            if (!this.child.isShown()) {
                this.child.show();
                currentTr.addClass('shown');
            }
        });

        // 4. Get table HTML including shown child rows
        fullHtml = $('#datatable').prop('outerHTML');

        // 5. Hide all child rows again
        table.rows().every(function () {
            const currentTr = $(this.node());
            if (this.child.isShown()) {
                this.child.hide();
                currentTr.removeClass('shown');
            }
        });

        // 6. Restore pagination
        table.page.len(10).draw();

        // 7. Restore current page (optional)
        table.page(currentPage).draw();

        // Convert HTML string to a DOM element using <template>
        const template = document.createElement('template');
        template.innerHTML = fullHtml.trim();
        const tableElement = template.content.querySelector('table');
        if (!tableElement) throw new Error('No table element found in the provided HTML');

        // Remove 2nd <th> from the header
        const ths = tableElement.querySelectorAll('thead th');
        if (ths[1]) ths[1].remove();

        // Remove 2nd <td> from every row in tbody
        const rows = tableElement.querySelectorAll('tbody tr');
        rows.forEach(tr => {
            const cells = tr.querySelectorAll('td');
            console.log();

            if (cells[1] && cells[1].getHTML().includes("details-control")) cells[1].remove();
        });

        // Optional: remove child row content columns if needed

        // Get the modified table HTML
        fullHtml = tableElement.outerHTML;

        // Now you can use `fullHtml` (e.g., for printing or exporting)

        // Print
        const Window = window.open();
        if (!Window) throw new Error('Failed to open new window');

        const Doc = Window.document;
        Doc.body.innerHTML = print_template;

        // using vanilla js, edit #title and #content
        const Title = Doc.getElementById('title')
        if (!Title) throw new Error('Failed to find #title element');  // using vanilla js, edit #title and #content
        Title.textContent = `Stock Detail Report (${periode})`;
        const Content = Doc.getElementById('content')
        if (!Content) throw new Error('Failed to find #content element');  // using vanilla js, edit #title and #content
        Content.innerHTML = fullHtml;

        setTimeout(() => {
            Window.print();
            Window.close();
        }, 500);
    }

    /**
     * Format angka menjadi mata uang rupiah
     * @param {number} amount 
     * @returns 
     */
    #rp(amount) {
        const formattedAmount = 'Rp ' + amount.toLocaleString('id-ID');
        return formattedAmount;
    }
}
