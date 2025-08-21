// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />
/// <reference path="./uihtmlrenderer.js" />

/**
 * A class for manipulating the prescription items table.
 * It depends on UIHTMLRenderer for creating HTML content.
 * NOTE: This file should be included after UIHTMLRenderer.js
 */
class UITableUpdater {
    $Table = $("#tableItems");

    /** @type {UIHTMLRenderer} */
    htmlRenderer;

    /**
     * @param {UIHTMLRenderer} htmlRendererInstance
     */
    constructor(htmlRendererInstance) {
        if (!htmlRendererInstance) {
            throw new Error("UITableUpdater requires an instance of UIHTMLRenderer.");
        }
        this.htmlRenderer = htmlRendererInstance;
    }

    /**
     * @typedef {Object} PrescribedItem
     * @property {string} kode - The item code
     * @property {string} nama - The item name
     * @property {number} qty - The quantity of the item
     */

    /**
     * @returns {string}
     */
    getTelaahResepRequiredData() {
        /** @type {PrescribedItem[]} */
        const Items = [];
        this.$Table.find("tr:not(.detail-racikan)").each(function () {
            const $row = $(this);
            Items.push({
                kode: $row.find(".kode_barang").first().text(),
                nama: $row.find(".nama_barang").first().text(),
                qty: /** @type {number} */ ($row.find("input[name^=qty]").first().val())
            });
        });

        return btoa(JSON.stringify(Items));
    }

    /**
     * @param {StoredItem} item 
     * @param {number} key 
     */
    updateIncompleteObatBatch(item, key) {
        if (!item.pbi || !item.pbi.item || !item.pbi.item.satuan) {
            showErrorAlertNoRefresh("Stored object is not complete");
            throw new Error("Stored object is not complete");
        }

        const Item = $("#item" + key);
        if (!Item) {
            showErrorAlertNoRefresh("Item element not found.");
            throw new Error("Item element not found.");
        }

        // remove all button with class "incomplete-btn" in the Item
        Item.find(".incomplete-btn").remove();

        // update the input with name^=si_id
        Item.find("input[name^='si_id']").val(item.id);

        // update the max of the input with type number and name^=qty
        Item.find("input[type='number'][name^='qty']").attr("max", item.qty);

        const RequiredQty = parseInt(String(Item.find("input[type='number'][name^='qty']").val()));
        if (item.qty < RequiredQty) {
            // not enough stock, display alert
            showErrorAlertNoRefresh(`Stock tidak cukup untuk barang ${item.pbi.item.nama}! 
                Jumlah obat akan disesuaikan dengan stock batch yang dipilih! 
                (Dibutuhkan: ${RequiredQty} | Stock Batch Dipilih: ${item.qty}).`.trim());

            // update the input with name^=si_qty and add class "incomplete-btn"
            Item.find("input[type='number'][name^='qty']").val(item.qty);
        }



        // update the text of td with class .batch
        Item.find("td.batch").text(item.pbi?.batch_no);

        // update the text of td with class .ed
        Item.find("td.ed").text(Utils.sqlDateToLocal(item.pbi?.tanggal_exp || ""));

    }

    /**
     * Set embalase fee for compounded drugs.
     */
    embalaseRacikan() {
        $(".singleton [name^='harga_embalase']").val(0);
        $(".singleton .embalase").text(Utils.rp(0));
        $(".racikan [name^='harga_embalase']").val(6000);
        $(".racikan .embalase").text(Utils.rp(6000));
        this.refreshTotal();
    }

    /**
     * Set embalase fee for single items.
     */
    embalaseItem() {
        $(".racikan [name^='harga_embalase']").val(0);
        $(".racikan .embalase").text(Utils.rp(0));
        $(".singleton [name^='harga_embalase']").val(2000);
        $(".singleton .embalase").text(Utils.rp(2000));
        this.refreshTotal();
    }

    /**
     * Set embalase fee to zero for all items.
     */
    embalaseFree() {
        $("[name^='harga_embalase']").val(0);
        $(".embalase").text(Utils.rp(0));
        this.refreshTotal();
    }

    /**
     * Inserts a racikan group row into the table.
     * @param {string} name
     */
    insertRacikan(name) {
        const key = Math.round(Math.random() * 100000);
        const embalaseCheck = $('#embalase_racikan');
        const embalaseValue = embalaseCheck.is(':checked') ? 6000 : 0;
        const html = this.htmlRenderer.getRacikanHTML(key, name, embalaseValue);

        this.$Table.append(html);
        $(`#instruksi${key}`).select2({ tags: true });
        this.refreshTotal();
    }

    /**
     * Inserts a drug item row into the table.
     * @param {StoredItem} item
     * @param {number | null} racikan_key
     */
    insertObat(item, racikan_key = null) {
        const key = Math.round(Math.random() * 100000);
        const embalaseCheck = $('#embalase_item');
        const embalaseValue = (embalaseCheck.is(':checked') && !racikan_key) ? 2000 : 0;
        const html = this.htmlRenderer.getObatHTML(item, key, racikan_key, embalaseValue);

        if (racikan_key === null) {
            this.$Table.append(html);
            $(`#instruksi${key}`).select2({ tags: true });
        } else {
            this.$Table.find(`#item${racikan_key}`).after(html);
        }

        this.refreshTotal();
    }

    /** @param {BarangFarmasi} item */
    insertIncompleteObat(item, qty, signa, instruksi) {
        const key = Math.round(Math.random() * 100000);
        const embalaseCheck = $('#embalase_item');
        const embalaseValue = embalaseCheck.is(':checked') ? 2000 : 0;
        const html = this.htmlRenderer.getIncompleteObat(item, key, embalaseValue, qty, signa, instruksi);

        this.$Table.append(html);
        $(`#instruksi${key}`).select2({ tags: true });

        this.refreshTotal();
    }

    /**
     * Updates the recipe table based on existing recipe data.
     * @param {ResepElektronik} re
     */
    updateRecipeInfo(re) {
        $("#re-id").val(re.id);
        $("#resep-manual").val(re.resep_manual);
        if (re.items) {
            for (const item of re.items) {
                const obat = /** @type {BarangFarmasi & {qty:number}} */ (item.barang);
                if (!obat) {
                    showErrorAlertNoRefresh("ResepElektronikItem object is not complete");
                    throw new Error("ResepElektronikItem object is not complete");
                }
                this.insertIncompleteObat(obat, item.qty, item.signa, item.instruksi);
            }
        }
    }

    /**
     * Recalculates and updates all totals in the table.
     */
    refreshTotal() {
        let total = 0;
        this.$Table.find("tr.obat").each((i, tr) => {
            const $tr = $(tr);
            const qty = parseInt(String($tr.find("input[name^=qty]").val()));
            const hna = parseInt(String($tr.find("input[name^=hna]").val()));
            const embalase = parseInt(String($tr.find("input[name^=harga_embalase]").val()));
            const subtotal = (qty * hna) + embalase;

            $tr.find(".subtotal").text(Utils.rp(subtotal));
            $tr.find("input[name^=subtotal]").val(subtotal);
            total += subtotal;
        });

        this.$Table.find("tr.racikan").each((i, tr) => {
            const $tr = $(tr);
            const embalase = parseInt(String($tr.find("input[name^=harga_embalase]").val()));
            $tr.find(".subtotal").text(Utils.rp(embalase));
            $tr.find("input[name^=subtotal]").val(embalase);
            total += embalase;
        });

        $("input[name=total]").val(total);
        $("#total-display").text(Utils.rp(total));
    }

    /**
     * Delete an item row from the table.
     * @param {string} key
     */
    deleteItem(key) {
        this.$Table.find("#item" + key).remove();
        this.refreshTotal();
    }

    /**
     * Delete a racikan and its associated item rows from the table.
     * @param {string} key
     */
    deleteRacikan(key) {
        this.$Table.find("#item" + key).remove();
        this.$Table.find("tr.detail-racikan").each((i, tr) => {
            const $tr = $(tr);
            if ($tr.find("input[name^=detail_racikan]").val() == key) {
                $tr.remove();
            }
        });
        this.refreshTotal();
    }
}