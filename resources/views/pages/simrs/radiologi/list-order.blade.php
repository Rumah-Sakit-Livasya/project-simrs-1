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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('radiologi.list-order') }}",
                    data: function(d) {
                        d.registration_date = $('#datepicker-1').val();
                        d.medical_record_number = $('#medical_record_number').val();
                        d.name = $('#name').val();
                        d.registration_number = $('#registration_number').val();
                        d.no_order = $('#no_order').val();
                    },
                    complete: function() {
                        $('#loading-spinner').hide();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'detail',
                        name: 'detail',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_date',
                        name: 'order_date'
                    },
                    {
                        data: 'medical_record_number',
                        name: 'registration.patient.medical_record_number'
                    },
                    {
                        data: 'registration_number',
                        name: 'registration.registration_number'
                    },
                    {
                        data: 'no_order',
                        name: 'no_order'
                    },
                    {
                        data: 'patient_name',
                        name: 'registration.patient.name'
                    },
                    {
                        data: 'poly_ruang',
                        name: 'registration.poliklinik'
                    },
                    {
                        data: 'penjamin',
                        name: 'registration.patient.penjamin.name'
                    },
                    {
                        data: 'doctor',
                        name: 'doctor.employee.fullname'
                    },
                    {
                        data: 'status_isi_hasil',
                        name: 'status_isi_hasil'
                    },
                    {
                        data: 'status_billed',
                        name: 'status_billed'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
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

            // Handle form submission for filtering
            $('form[action="{{ route('radiologi.list-order') }}"]').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Event listener untuk membuka dan menutup child row detail
            $('#dt-basic-example tbody').on('click', '.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var icon = $(this);
                var detailData = JSON.parse(tr.find('[data-details]').attr('data-details'));

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

            // Fungsi untuk format child row
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
                    const parameterName = item.parameter_radiologi ? item.parameter_radiologi
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
