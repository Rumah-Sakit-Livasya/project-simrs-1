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

    @stack('plugin-order-lab')
    <script>
        $(document).ready(function() {
            // Inisialisasi Date Range Picker
            $('#datepicker-1').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
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
@endsection
