$(document).ready(function () {
    // --- Inisialisasi Plugin ---
    $(".select2").select2({ width: "100%" });
    $("#tanggal_pr_filter")
        .daterangepicker({
            opens: "left",
            autoUpdateInput: false,
            locale: {
                format: "YYYY-MM-DD",
                cancelLabel: "Clear",
                separator: " to ",
            },
        })
        .on("apply.daterangepicker", function (ev, picker) {
            $(this).val(
                picker.startDate.format("YYYY-MM-DD") +
                    " to " +
                    picker.endDate.format("YYYY-MM-DD")
            );
        })
        .on("cancel.daterangepicker", function (ev, picker) {
            $(this).val("");
        });

    // --- Inisialisasi DataTable ---
    var table = $("#pr-table").DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, className: "details-control", targets: 0 },
        ],
        order: [[1, "desc"]],
    });

    // --- Event Listeners ---
    // Child Row
    $("#pr-table tbody").on("click", "td.details-control", function () {
        var tr = $(this).closest("tr");
        var row = table.row(tr);
        var prId = tr.data("id");
        var url = `/simrs/procurement/purchase-request/pharmacy/${prId}/details`;

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass("shown");
        } else {
            row.child(
                '<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat...</td></tr>'
            ).show();
            tr.addClass("shown");
            $.get(url, function (response) {
                row.child(formatChildRow(response.data)).show();
            }).fail(function () {
                row.child(
                    '<tr><td colspan="8" class="text-center text-danger">Gagal memuat data.</td></tr>'
                ).show();
            });
        }
    });

    // Mengambil URL dari data-url
    $("#add-pr-btn").on("click", function () {
        openPopup($(this).data("url"));
    });

    $("#pr-table").on("click", ".edit-btn", function () {
        openPopup($(this).data("url"));
    });

    $("#pr-table").on("click", ".print-btn", function () {
        window.open($(this).data("url"), "_blank");
    });

    $("#pr-table").on("click", ".delete-btn", function () {
        var url = $(this).data("url");
        showDeleteConfirmation(function () {
            $.ajax({
                url: url,
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        showSuccessAlert(response.message);
                        window.location.reload();
                    }
                },
                error: function (xhr) {
                    showErrorAlert(xhr.responseJSON.message);
                },
            });
        });
    });

    // --- Helper Functions ---
    function openPopup(url) {
        window.open(url, "prPopup", `width=1200,height=800,scrollbars=yes`);
    }

    function formatChildRow(data) {
        if (!data || data.length === 0)
            return '<tr><td colspan="7" class="text-center">Tidak ada item dalam permintaan ini.</td></tr>';
        let rows = "";
        data.forEach((d, i) => {
            rows += `<tr>
                <td class="text-center">${i + 1}</td>
                <td>${d.nama_barang} (${d.kode_barang})</td>
                <td class="text-center">${d.qty}</td>
                <td>${d.unit_barang}</td>
                <td class="text-right">${rp(d.harga_barang)}</td>
                <td class="text-right">${rp(d.subtotal)}</td>
                <td>${d.keterangan || ""}</td>
            </tr>`;
        });
        return `<div class="p-2 bg-white"><table class="child-table">
            <thead><tr><th>#</th><th>Barang</th><th>Qty</th><th>Satuan</th><th>HNA</th><th>Subtotal</th><th>Keterangan</th></tr></thead>
            <tbody>${rows}</tbody>
        </table></div>`;
    }

    function rp(number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(number);
    }
});
