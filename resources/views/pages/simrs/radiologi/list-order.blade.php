@extends('inc.layout')
@section('title', 'List Order Radiologi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        @include('pages.simrs.radiologi.partials.list-order-form')

        @include('pages.simrs.radiologi.partials.list-order-datatable')

    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- Datepicker Range --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        var runDatePicker = function() {

            // minimum setup
            $('#date_of_birth').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls
            });

        }

        $(document).ready(function() {

            // Datepciker
            runDatePicker();

            // Select 2
            $(function() {
                $('.select2').select2({
                    dropdownCssClass: "move-up"
                });
                $(".select2").on("select2:open", function() {
                    // Mengambil elemen kotak pencarian
                    var searchField = $(".select2-search__field");

                    // Mengubah urutan elemen untuk memindahkannya ke atas
                    searchField.insertBefore(searchField.prev());
                });
            });

            // --- PERUBAHAN UNTUK DATERANGE PICKER ---
            // Tentukan tanggal awal dan akhir.
            // Jika ada tanggal dari request (hasil pencarian), gunakan itu.
            // Jika tidak, gunakan tanggal hari ini.
            @php
                $dateRange = request('registration_date');
                if ($dateRange) {
                    $dates = explode(' - ', $dateRange);
                    // Pastikan formatnya YYYY-MM-DD sesuai dengan yang dikirim daterangepicker
                    $startDate = \Carbon\Carbon::parse($dates[0])->format('Y-m-d');
                    $endDate = \Carbon\Carbon::parse($dates[1])->format('Y-m-d');
                } else {
                    $startDate = \Carbon\Carbon::today()->format('Y-m-d');
                    $endDate = \Carbon\Carbon::today()->format('Y-m-d');
                }
            @endphp

            $('#datepicker-1').daterangepicker({
                opens: 'left',
                startDate: moment('{{ $startDate }}'), // Gunakan variabel dari PHP
                endDate: moment('{{ $endDate }}'), // Gunakan variabel dari PHP
                locale: {
                    format: 'YYYY-MM-DD' // Format yang dikirim ke server
                }
            });


            $('#loading-spinner').show();
            // initialize datatable
            let table = $('#dt-basic-example').DataTable({
                "drawCallback": function(settings) {
                    $('#loading-spinner').hide();
                },
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
                ]
            });

            // ===================== TAMBAHKAN BLOK KODE INI =====================
            // Event listener untuk tombol delete
            $('#dt-basic-example tbody').on('click', '.delete-btn', function() {
                var orderId = $(this).data('id');
                var row = $(this).closest('tr'); // Ambil elemen <tr> dari baris yang akan dihapus

                // Tampilkan konfirmasi menggunakan SweetAlert
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Order ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user mengonfirmasi, kirim request AJAX
                        $.ajax({
                            url: `/simrs/radiologi/order/${orderId}`, // Sesuaikan dengan URL route Anda
                            type: 'DELETE',
                            headers: {
                                // Kirim CSRF token untuk keamanan
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Hapus baris dari DataTable
                                    table.row(row).remove().draw();

                                    // Tampilkan notifikasi sukses
                                    Swal.fire(
                                        'Dihapus!',
                                        response.message,
                                        'success'
                                    );
                                } else {
                                    // Tampilkan notifikasi error dari server
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                // Tangani error AJAX (misal: server down, 404, dll)
                                Swal.fire(
                                    'Error!',
                                    'Tidak dapat menghubungi server. Coba lagi nanti.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

        });


        // Input RM
        function formatAngka(input) {
            var value = input.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 6) {
                value = value.substr(0, 6);
            }

            if (value.length > 0) {
                formattedValue = value.match(/.{1,2}/g).join('-');
            }

            input.value = formattedValue;
        }
    </script>

    <script src="{{ asset('js/simrs/list-order-radiologi.js') }}?v={{ time() }}"></script>
@endsection
