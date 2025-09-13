@extends('inc.layout')
@section('title', 'Referensi Dokter')
@section('content')

    <style>
        table {
            font-size: 8pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Referensi <span class="fw-300"><i>Dokter</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            {{-- This button triggers the data fetch from the HFIS API --}}
                            <a href="javascript:void(0);" id="get-data-btn" class="btn btn-primary">
                                <i class="fal fa-download mr-1"></i>
                                Get Data Referensi Dokter
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Panel tag to display function description and API response status --}}
                            <div class="panel-tag">
                                <p class="mb-0"><strong>Fungsi :</strong> Melihat referensi dokter yang ada pada Aplikasi
                                    HFIS</p>
                                <p class="mb-0"><strong>Response WS :</strong> <span id="response-status">-</span></p>
                            </div>

                            {{-- Data Table --}}
                            <table id="dt-referensi-dokter" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No.</th>
                                        <th style="width: 25%;">Kode Dokter</th>
                                        <th>Nama Dokter</th>
                                        <th class="text-center" style="width: 15%;">Update Jadwal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data will be loaded here via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Include necessary plugins from your template --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Initialize the DataTable
            var table = $('#dt-referensi-dokter').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 15,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                    className: 'btn-outline-success btn-sm mr-1'
                }, {
                    extend: 'print',
                    text: '<i class="fal fa-print mr-1"></i> Print',
                    className: 'btn-outline-primary btn-sm'
                }],
                // Define columns. `data` points to the key in the JSON response.
                columns: [{
                    data: null, // For the counter column
                    searchable: false,
                    orderable: false,
                    className: 'text-center'
                }, {
                    data: 'kodeDokter'
                }, {
                    data: 'namaDokter'
                }, {
                    data: null, // For the action button
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        // Action button to update the doctor's schedule
                        return `<a href="/jadwal/update/${row.kodeDokter}" class="btn btn-xs btn-success">Update Jadwal</a>`;
                    }
                }],
                // Add row counter
                "fnDrawCallback": function(oSettings) {
                    table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });

            // 2. Handle button click to fetch data
            $('#get-data-btn').on('click', function() {
                var btn = $(this);
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                // --- AJAX Call Placeholder ---
                // In a real application, you would make an AJAX call here to your backend.
                /*
                $.ajax({
                    url: '/api/v1/hfis/referensi/dokter', // Your backend endpoint
                    type: 'GET',
                    success: function(response) {
                        if (response.metadata.code == 200) {
                            table.clear().rows.add(response.response.list).draw();
                            $('#response-status').text(response.metadata.message);
                            toastr.success('Data referensi dokter berhasil dimuat.', 'Sukses');
                        } else {
                            $('#response-status').text(response.metadata.message);
                            Swal.fire('Gagal', 'Gagal memuat data: ' + response.metadata.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
                        $('#response-status').text('Error: Gagal terhubung.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="fal fa-download mr-1"></i> Get Data Referensi Dokter');
                    }
                });
                */

                // For demonstration, we will use mock data after a short delay.
                setTimeout(function() {
                    var mockData = {
                        "response": {
                            "list": [{
                                "kodeDokter": 231465,
                                "namaDokter": "DR. ADI KURNIAWAN, SP.PD"
                            }, {
                                "kodeDokter": 239454,
                                "namaDokter": "DR. BUDI SANTOSO, SP.A"
                            }, {
                                "kodeDokter": 221783,
                                "namaDokter": "DR. CITRA DEWI, SP.OG"
                            }, {
                                "kodeDokter": 231454,
                                "namaDokter": "DR. FAJAR NUGROHO, SP.B"
                            }, {
                                "kodeDokter": 239458,
                                "namaDokter": "DR. HERLINA WATI, SP.JP"
                            }]
                        },
                        "metadata": {
                            "code": 200,
                            "message": "OK"
                        }
                    };

                    // Load data into the table
                    table.clear().rows.add(mockData.response.list).draw();
                    $('#response-status').text(mockData.metadata.message + ' (Data Demo)');

                    // Show success notification
                    toastr.success('Data referensi dokter berhasil dimuat.', 'Sukses');

                    // Re-enable button
                    btn.prop('disabled', false).html(
                        '<i class="fal fa-download mr-1"></i> Get Data Referensi Dokter');

                }, 1000); // Simulate network delay

            });
        });
    </script>
@endsection
