@extends('inc.layout')
@section('title', 'List Order Laboratorium')
@section('extended-css')
    {{-- CSS Kustom untuk halaman ini --}}
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 600px;
            /* Lebarkan popover untuk detail */
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        @include('pages.simrs.laboratorium.partials.list-order-form')

        @include('pages.simrs.laboratorium.partials.list-order-datatable')

    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Date Range Picker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Inisialisasi Datatables
            $('#dt-basic-example').dataTable({
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
                    }
                ],
                initComplete: function() {
                    // Inisialisasi Popovers setelah tabel selesai dimuat
                    $('[data-toggle="popover"]').each(function() {
                        var contentId = $(this).data('content-id');
                        var contentHtml = $('#' + contentId).html();
                        $(this).popover({
                            html: true,
                            content: contentHtml,
                            sanitize: false // Penting jika kontennya HTML
                        });
                    });
                }
            });
        });

        // Fungsi helper untuk format input No. RM
        function formatAngka(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 6) value = value.substr(0, 6);
            if (value.length > 0) {
                value = value.match(/.{1,2}/g).join('-');
            }
            input.value = value;
        }

        // Event delegation untuk tombol aksi
        $(document).on('click', '.nota-btn', function() {
            let orderId = $(this).data('id');
            window.open(`/simrs/laboratorium/nota-order/${orderId}`, '_blank');
        });

        $(document).on('click', '.pay-btn', function() {
            let orderId = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi Tagihan?',
                text: "Anda yakin ingin mengkonfirmasi tagihan untuk order ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    formData.append('id', orderId);

                    fetch("/api/simrs/laboratorium/pay", {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute("content") || "",
                            },
                        })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error("Error: " + response.statusText);
                            }
                            return response.json();
                        })
                        .then((data) => {
                            if (data.success) {
                                showSuccessAlert("Data berhasil disimpan");
                                setTimeout(() => window.location.reload(), 2000);
                            } else {
                                showErrorAlertNoRefresh(data.message || "Terjadi kesalahan.");
                            }
                        })
                        .catch((error) => {
                            console.log("Error:", error);
                            showErrorAlertNoRefresh(`Error: ${error.message}`);
                        });
                }
            });
        });

        $(document).on('click', '.edit-btn', function() {
            let orderId = $(this).data('id');
            window.location.href = `/simrs/laboratorium/edit-order/${orderId}`;
        });

        $(document).on('click', '.label-btn', function() {
            let orderId = $(this).data('id');
            window.open(`/simrs/laboratorium/label-order/${orderId}`, '_blank');
        });

        $(document).on('click', '.result-btn', function() {
            let orderId = $(this).data('id');
            window.open(`/simrs/laboratorium/hasil-order/${orderId}`, '_blank');
        });
    </script>

    <script>
        $(document).ready(function() {
            /* Fungsi untuk memformat child row, termasuk total biaya */
            function format(details) {
                if (!details || details.length === 0) {
                    return '<div class="p-3 text-center">Tidak ada detail parameter untuk order ini.</div>';
                }
                let totalPrice = 0;
                let table = `<table class="table table-sm table-striped table-bordered child-table">
                            <thead class="bg-info-50">
                                <tr>
                                    <th style="width: 30px;">#</th>
                                    <th>Parameter</th>
                                    <th>Harga</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>`;
                details.forEach((item, index) => {
                    totalPrice += (parseFloat(item.nominal_rupiah) || 0);
                    const formattedPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(item.nominal_rupiah || 0);
                    const parameterName = item.parameter_laboratorium ? item.parameter_laboratorium
                        .parameter : '<i class="text-muted">N/A</i>';
                    table += `<tr>
                            <td>${index + 1}</td>
                            <td>${parameterName}</td>
                            <td>${formattedPrice}</td>
                            <td>${item.catatan || ''}</td>
                          </tr>`;
                });
                table += '</tbody>';
                const formattedTotal = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2
                }).format(totalPrice);
                table += `<tfoot>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Total Biaya</td>
                            <td class="font-weight-bold">${formattedTotal}</td>
                            <td></td>
                        </tr>
                      </tfoot>`;
                table += '</table>';
                return table;
            }

            // Inisialisasi DataTable
            var table = $('#dt-lab-orders').DataTable({
                responsive: true,
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }]
            });

            // Event listener untuk membuka dan menutup detail
            $('#dt-lab-orders tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var icon = $(this).find('i');
                var detailData = JSON.parse(tr.attr('data-details'));
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('details-shown');
                    icon.removeClass('fa-minus-circle text-danger').addClass('fa-plus-circle text-success');
                } else {
                    row.child(format(detailData)).show();
                    tr.addClass('details-shown');
                    icon.removeClass('fa-plus-circle text-success').addClass('fa-minus-circle text-danger');
                }
            });
        });
    </script>
@endsection
