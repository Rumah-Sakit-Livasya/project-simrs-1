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
                    // Lakukan AJAX call untuk konfirmasi di sini
                    // Contoh: $.post(`//apisimrs/laboratorium/confirm-payment/${orderId}`, function(data) { ... });
                    showSuccessAlert('Tagihan berhasil dikonfirmasi.');
                    // location.reload(); // Uncomment untuk muat ulang halaman
                }
            })
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
@endsection
