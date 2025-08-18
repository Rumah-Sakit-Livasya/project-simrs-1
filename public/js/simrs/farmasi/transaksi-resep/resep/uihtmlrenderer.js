// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

/**
 * A class responsible for generating HTML strings for the UI.
 * NOTE: This file should be included before UIElementUpdater.js
 */
class UIHTMLRenderer {
    /**
     * Creates the HTML for the restriction icon.
     * @private
     * @param {BarangFarmasi} item The pharmacy item.
     * @returns {string} The HTML string for the restriction icon.
     */
    _getRestriksiHTML(item) {
        if (item.restriksi !== null) {
            return /*html*/`
                <a class="mdi mdi-24px pointer mdi-alert text-warning"
                onclick="ResepClass.restriksiSwal('${item.nama}','${item.restriksi}')" title="Ada restriksi"></a>
            `;
        }
        return /*html*/`
            <a class="mdi mdi-24px pointer mdi-check-circle text-success"
             onclick="ResepClass.noRestriksiSwal('${item.nama}')" title="Tidak ada restriksi"></a>
        `;
    }

    /** @param {StoredItem} item */
    getObatBatchRowHTML(item) {
        return  /*html*/`
            <tr class="pointer batch-obat-select" onclick='ResepClass.tambahObat(${JSON.stringify(item)})'>
                <td class="batch">${item.pbi?.batch_no}</td>
                <td>${item.qty}</td>
                <td>${Utils.sqlDateToLocal(String(item.pbi?.tanggal_exp))}</td>
            </tr>
        `;
    }

    /**
     * Generates the HTML string for an "obat" (drug) select option.
     * @param {BarangFarmasi & {qty: number}} item 
     * @param {string} zats 
     * @returns  {string} The HTML string for the Obat Select option.
     */
    getObatSelectHTML(item, zats) {
        return /*html*/`
                <option value="${item.id}" data-item='${JSON.stringify(item)}' data-zat="${zats}" class="obat">
                    ${item.nama} (Stock: ${item.qty})
                </option>
            `;
    }

    /**
     * Generates the HTML string for a "racikan" (compounded drug) table row.
     * @param {number} key The unique key for the item.
     * @param {string} name The name of the racikan.
     * @param {number} embalaseValue The value for the embalase fee.
     * @returns {string} The HTML string for the table row.
     */
    getRacikanHTML(key, name, embalaseValue) {
        return /*html*/`
            <tr id="item${key}" class="racikan">
                <input type="hidden" name="signa[${key}]" value="">
                <input type="hidden" name="jam_pemberian[${key}]" value="[]">
                <input type="hidden" name="hna[${key}]" value="0">
                <input type="hidden" name="harga_embalase[${key}]" value="${embalaseValue}">
                <input type="hidden" name="subtotal[${key}]" value="${embalaseValue}">
                <input type="hidden" name="qty[${key}]" value="1">
                <input type="hidden" name="type[${key}]" value="racikan">
                <input type="hidden" name="nama_racikan[${key}]" value="${name}">

                <td class="kode_barang">RACIKAN</td>
                <td class="nama_barang"><u>${name}</u></td>
                <td><!-- Racikan --></td>
                <td><!-- Racikan --></td>
                <td><!-- Racikan --></td>
                <td><!-- Racikan --></td>
                <td><!-- Racikan --></td>
                <td class="signa">-</td>
                <td><select name="instruksi[${key}]" id="instruksi${key}">
                    <option value="Sesudah Makan">Sesudah Makan</option>
                    <option value="Sebelum Makan">Sebelum Makan</option>
                    <option value="Saat Makan">Saat Makan</option>
                </select></td>
                <td class="jam-pemberian">-</td>
                <td>${Utils.rp(0)}</td>
                <td class="embalase">${Utils.rp(embalaseValue)}</td>
                <td class="subtotal">${Utils.rp(embalaseValue)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                    title="Hapus" onclick="ResepClass.deleteRacikan(${key})"></a>
                    <a class="mdi mdi-clock-time-eight pointer mdi-24px text-secondary jam-pemberian-btn"
                    title="Ubah jam pemberian" onclick="ResepClass.jamPemberian(${key}, '${name}')"></a>
                    <a class="mdi mdi-medication pointer mdi-24px text-success signa-btn"
                    title="Ubah signa" onclick="ResepClass.signa(${key}, 'Racikan ${name}')"></a>
                    <a class="mdi mdi-plus pointer mdi-24px text-primary add-to-racikan-btn"
                    title="Tambah Obat" onclick="ResepClass.tambahObatRacikan(${key}, 'Racikan ${name}')"></a>
                </td>
            </tr>
        `;
    }

    /**
     * Generates the HTML string for an incomplete single drug item table row.\
     * Intended for drugs from electronic recipe.
     * @param {BarangFarmasi} item The drug item.
     * @param {number} key The unique key for the item.
     * @param {number} embalaseValue The value for the embalase fee.
     */
    getIncompleteObat(item, key, embalaseValue) {
        const Instruksi = /*html*/`
            <select name="instruksi[${key}]" id="instruksi${key}">
                <option value="Sesudah Makan">Sesudah Makan</option>
                <option value="Sebelum Makan">Sebelum Makan</option>
                <option value="Saat Makan">Saat Makan</option>
            </select>
        `;
        const TombolJamPemberian = /*html*/`
            <a class="mdi mdi-clock-time-eight pointer mdi-24px text-secondary jam-pemberian-btn"
                title="Ubah jam pemberian" onclick="ResepClass.jamPemberian(${key}, '${item.nama}')">
        `;
        const TombolSigna = /*html*/`
            <a class="mdi mdi-medication pointer mdi-24px text-success signa-btn"
                title="Ubah signa" onclick="ResepClass.signa(${key}, '${item.nama}')"></a>`;

        const TombolAlertIncomplete = /*html*/`
            <a class="mdi mdi-alert-rhombus pointer mdi-24px text-danger incomplete-btn"
                title="Obat Tidak Lengkap! Pilih Batch Obat!" onclick="ResepClass.pilihBatch(${key}, ${item.id}, '${item.nama}')"></a>
        `;

        const restriksiHTML = this._getRestriksiHTML(item);
        const subtotal = item.hna + embalaseValue;

        return /*html*/`
            <tr id="item${key}" class="obat singleton">
                <input type="hidden" name="signa[${key}]" value="">
                <input type="hidden" name="jam_pemberian[${key}]" value="[]">
                <input type="hidden" name="hna[${key}]" value="${item.hna}">
                <input type="hidden" name="harga_embalase[${key}]" value="${embalaseValue}">
                <input type="hidden" name="obat_id[${key}]" value="${item.id}">
                <input type="hidden" name="subtotal[${key}]" value="${subtotal}">
                <input type="hidden" name="type[${key}]" value="obat">
                <input type="hidden" name="si_id[${key}]" value="">

                <td class="kode_barang">${item.kode}</td>
                <td class="nama_barang">${TombolAlertIncomplete}${item.nama}</td>
                <td>${item.satuan?.nama}</td>
                <td>${restriksiHTML}</td>
                <td class="batch">SELECT A BATCH</td>
                <td class="ed">SELECT A BATCH</td>
                <td><input type="number" name="qty[${key}]" min="1" step="1" class="form-control" value="1" max="1"></td>
                <td class="signa">-</td>
                <td>${Instruksi}</td>
                <td class="jam-pemberian">-</td>
                <td>${Utils.rp(item.hna)}</td>
                <td class="embalase">${Utils.rp(embalaseValue)}</td>
                <td class="subtotal">${Utils.rp(subtotal)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                    title="Hapus" onclick="ResepClass.deleteItem(${key})"></a>
                    ${TombolJamPemberian}
                    ${TombolSigna}
                    ${TombolAlertIncomplete}
                    </a>
                </td>
            </tr>
        `.trim();
    }

    /**
     * Generates the HTML string for a single drug item table row.
     * @param {StoredItem} item The drug item.
     * @param {number} key The unique key for the item.
     * @param {number | null} racikan_key The key of the parent racikan, if any.
     * @param {number} embalaseValue The value for the embalase fee.
     * @returns {string} The HTML string for the table row.
     */
    getObatHTML(item, key, racikan_key, embalaseValue) {
        if (!item.pbi || !item.pbi.item) {
            showErrorAlertNoRefresh("StoredItem object is not complete");
            throw new Error("StoredItem object is not complete");
        }
        const Barang =  /** @type {BarangFarmasi} */(item.pbi.item);
        const classType = !racikan_key ? 'singleton' : 'detail-racikan';
        let detailRacikanInput = "";
        let detailRacikanLogo = "";

        if (racikan_key) {
            detailRacikanInput = /*html*/`
                <input type="hidden" name="detail_racikan[${key}]" value="${racikan_key}">
            `.trim();
            detailRacikanLogo = /*html*/`
                <span class="mdi mdi-subdirectory-arrow-right mdi-24px text-info"></span>
            `;
        }

        const Instruksi = /*html*/`
            <select name="instruksi[${key}]" id="instruksi${key}">
                <option value="Sesudah Makan">Sesudah Makan</option>
                <option value="Sebelum Makan">Sebelum Makan</option>
                <option value="Saat Makan">Saat Makan</option>
            </select>
        `;
        const TombolJamPemberian = /*html*/`
            <a class="mdi mdi-clock-time-eight pointer mdi-24px text-secondary jam-pemberian-btn"
                title="Ubah jam pemberian" onclick="ResepClass.jamPemberian(${key}, '${Barang.nama}')">
        `;
        const TombolSigna = /*html*/`
            <a class="mdi mdi-medication pointer mdi-24px text-success signa-btn"
                title="Ubah signa" onclick="ResepClass.signa(${key}, '${Barang.nama}')"></a>`;

        const restriksiHTML = this._getRestriksiHTML(Barang);
        const subtotal = Barang.hna + embalaseValue;

        return /*html*/`
            <tr id="item${key}" class="obat ${classType}">
                <input type="hidden" name="signa[${key}]" value="">
                <input type="hidden" name="jam_pemberian[${key}]" value="[]">
                <input type="hidden" name="hna[${key}]" value="${Barang.hna}">
                <input type="hidden" name="harga_embalase[${key}]" value="${embalaseValue}">
                <input type="hidden" name="obat_id[${key}]" value="${Barang.id}">
                <input type="hidden" name="subtotal[${key}]" value="${subtotal}">
                <input type="hidden" name="type[${key}]" value="obat">
                <input type="hidden" name="si_id[${key}]" value="${item.id}">
                ${detailRacikanInput}

                <td>${Barang.kode}</td>
                <td>${detailRacikanLogo}${Barang.nama}</td>
                <td>${Barang.satuan?.nama}</td>
                <td>${restriksiHTML}</td>
                <td class="batch">${item.pbi.batch_no}</td>
                <td class="ed">${Utils.sqlDateToLocal(item.pbi.tanggal_exp || '')}</td>
                <td><input type="number" name="qty[${key}]" min="1" step="1" class="form-control" value="1" max="${item.qty}"></td>
                <td class="signa">${!racikan_key ? '-' : ''}</td>
                <td>${!racikan_key ? Instruksi : ''}</td>
                <td class="jam-pemberian">${!racikan_key ? '-' : ''}</td>
                <td>${Utils.rp(Barang.hna)}</td>
                <td class="embalase">${Utils.rp(embalaseValue)}</td>
                <td class="subtotal">${Utils.rp(subtotal)}</td>
                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                    title="Hapus" onclick="ResepClass.deleteItem(${key})"></a>
                    ${!racikan_key ? TombolJamPemberian : ''}
                    ${!racikan_key ? TombolSigna : ''}
                    </a>
                </td>
            </tr>
        `.trim();
    }
}