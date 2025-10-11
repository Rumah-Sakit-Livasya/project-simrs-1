// Global scope untuk bisa dipanggil dari popup lain
function addItemFromPopup(barang) {
    if (window.prFormHandler) {
        window.prFormHandler.addItem(barang);
    }
}

class PrFormHandler {
    #selectedItems = new Set();
    #submittingButton = null;

    constructor(editData = []) {
        this.initializePlugins();
        this.attachEventListeners();

        if (editData.length > 0) {
            this.loadEditData(editData);
        } else {
            this.calculateTotal();
        }
    }

    initializePlugins() {
        $(".select2").select2({ width: "100%" });
    }

    attachEventListeners() {
        $("#add-item-popup-btn").on("click", this.openItemPopup.bind(this));
        $("#item-container").on(
            "click",
            ".remove-item-row",
            this.removeItemRow.bind(this)
        );
        $("#item-container").on(
            "change keyup",
            ".item-qty, .item-hna",
            this.calculateTotal.bind(this)
        );

        // Track which submit button is clicked
        $('#pr-form button[type="submit"]').on("click", (e) => {
            this.#submittingButton = e.currentTarget;
        });

        $("#pr-form").on("submit", this.submitForm.bind(this));
    }

    loadEditData(items) {
        items.forEach((item) => {
            if (item.barang) {
                this.addItem(item.barang, item);
            }
        });
        this.calculateTotal();
    }

    openItemPopup() {
        const url = "/simrs/procurement/purchase-request/pharmacy/popup-items";
        window.open(
            url,
            "itemPopup",
            (() => {
                const width = Math.floor(screen.availWidth / 2);
                const height = Math.floor(screen.availHeight / 2);
                const left = Math.floor((screen.availWidth - width) / 2);
                const top = Math.floor((screen.availHeight - height) / 2);
                return `width=${width},height=${height},left=${left},top=${top},scrollbars=yes`;
            })()
        );
    }

    addItem(barang, itemData = null) {
        if (this.#selectedItems.has(barang.id)) {
            Swal.fire({
                icon: "warning",
                title: "Item Sudah Ada",
                text: "Barang ini sudah ada dalam daftar.",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
            return;
        }

        const key = Date.now();
        const qty = itemData ? itemData.qty : 1;
        const hna = itemData ? itemData.harga_barang : barang.hna;
        const keterangan =
            itemData && itemData.keterangan != null ? itemData.keterangan : "";
        const itemId = itemData ? itemData.id : "";

        const rowHtml = `
            <tr class="item-row" data-barang-id="${barang.id}">
                <input type="hidden" name="item_id[${key}]" value="${itemId}">
                <input type="hidden" name="barang_id[${key}]" value="${
            barang.id
        }">
                <input type="hidden" name="satuan_id[${key}]" value="${
            barang.satuan_id
        }">
                <td>${barang.kode}</td>
                <td>${barang.nama}</td>
                <td><input type="number" name="qty[${key}]" class="form-control table-input qty-input item-qty" value="${qty}" min="1" required></td>
                <td>${barang.satuan ? barang.satuan.nama : ""}</td>
                <td><input type="number" name="hna[${key}]" class="form-control table-input item-hna" value="${hna}" min="0" required></td>
                <td><input type="text" name="keterangan_item[${key}]" class="form-control table-input" value="${keterangan}"></td>
                <td class="text-center"><button type="button" class="btn btn-xs btn-danger remove-item-row"><i class="fal fa-trash"></i></button></td>
            </tr>
        `;
        $("#item-container").append(rowHtml);
        this.#selectedItems.add(barang.id);
        this.calculateTotal();
    }

    removeItemRow(event) {
        const row = $(event.target).closest(".item-row");
        const barangId = row.data("barang-id");
        this.#selectedItems.delete(barangId);
        row.remove();
        this.calculateTotal();
    }

    calculateTotal() {
        let total = 0;
        $(".item-row").each(function () {
            const qty = parseFloat($(this).find(".item-qty").val()) || 0;
            const hna = parseFloat($(this).find(".item-hna").val()) || 0;
            total += qty * hna;
        });
        $("#total-nominal").text(this.rp(total));
        $('input[name="nominal"]').val(total);
    }

    submitForm(e) {
        e.preventDefault();
        const form = $(e.target);
        let formData = form.serialize();

        // Tambahkan data dari tombol submit yang diklik (status)
        if (this.#submittingButton) {
            formData += `&${this.#submittingButton.name}=${
                this.#submittingButton.value
            }`;
        }

        // Validasi: minimal satu item
        if ($("#item-container").find(".item-row").length === 0) {
            alert("Harap tambahkan minimal satu item permintaan.");
            return;
        }

        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: formData,
            success: function (response) {
                if (response.success) {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    window.close();
                }
            },
            error: function (xhr) {
                const message =
                    xhr.responseJSON.message || "Terjadi kesalahan.";
                const errors = xhr.responseJSON.errors;
                let errorString = message + "\n\n";
                if (errors) {
                    $.each(errors, function (key, value) {
                        errorString += `* ${value}\n`;
                    });
                }
                alert(errorString);
            },
        });
    }

    rp(number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(number);
    }
}

$(document).ready(function () {
    // 'editData' didefinisikan di view 'form-popup.blade.php'
    window.prFormHandler = new PrFormHandler(
        typeof editData !== "undefined" ? editData : []
    );
});
