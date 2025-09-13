@extends('inc.layout')
@section('title', 'Monitoring Antrian')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10 ">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Pencarian</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="get" id="form-pencarian">
                                @csrf
                                <div class="row">
                                    <!-- Periode Awal -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="tanggal_awal">Awal Periode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                id="tanggal_awal" value="{{ date('d-m-Y') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Periode Akhir -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="tanggal_akhir">Akhir Periode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                id="tanggal_akhir" value="{{ date('d-m-Y') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Department / Poli -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="department_id">Department</label>
                                        <select class="form-control select2" id="department_id" name="department_id">
                                            <option value="">Semua Department</option>
                                            {{-- Options can be loaded from database --}}
                                            <option value="INT">Poli Penyakit Dalam</option>
                                            <option value="JAN">Poli Jantung</option>
                                            <option value="BED">Poli Bedah</option>
                                        </select>
                                    </div>

                                    <!-- Jenis -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="jenis_pasien">Jenis</label>
                                        <select class="form-control select2" id="jenis_pasien" name="jenis_pasien">
                                            <option value="ALL">ALL</option>
                                            <option value="JKN">JKN</option>
                                            <option value="NON-JKN">NON-JKN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fal fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Monitoring Antrean Online <span class="fw-300 fs-sm"><i>(* data dapat berubah ketika antrian
                                    task id di update)</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            {{-- Optional: Add refresh button or other tools here --}}
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-monitoring-antrian" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Periksa</th>
                                        <th>NO RM</th>
                                        <th>Nama Dokter</th>
                                        <th>Kode Booking</th>
                                        <th>Jenis</th>
                                        <th>Keterangan</th>
                                        <th>TASK 1</th>
                                        <th>TASK 2</th>
                                        <th>TASK 3</th>
                                        <th>TASK 4</th>
                                        <th>TASK 5</th>
                                        <th>TASK 6</th>
                                        <th>TASK 7</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data will be populated by DataTables --}}
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Initialize Plugins
            $('.datepicker').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('.select2').select2({
                width: '100%'
            });

            // 2. Initialize DataTable
            var table = $('#dt-monitoring-antrian').DataTable({
                responsive: true,
                processing: true, // Show a "processing" indicator
                serverSide: false, // Change to true for server-side processing
                scrollX: true, // Enable horizontal scrolling
                lengthChange: false,
                pageLength: 20,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ // Optional: Add export buttons
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }
                ],
                // AJAX placeholder for loading data
                // ajax: {
                //     url: '/api/v1/antrian/monitoring',
                //     type: 'POST',
                //     data: function(d) {
                //         d.tanggal_awal = $('#tanggal_awal').val();
                //         d.tanggal_akhir = $('#tanggal_akhir').val();
                //         d.department_id = $('#department_id').val();
                //         d.jenis_pasien = $('#jenis_pasien').val();
                //     }
                // },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tanggalperiksa'
                    },
                    {
                        data: 'norm'
                    },
                    {
                        data: 'namadokter'
                    },
                    {
                        data: 'kodebooking'
                    },
                    {
                        data: 'jenispasien'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'task1'
                    },
                    {
                        data: 'task2'
                    },
                    {
                        data: 'task3'
                    },
                    {
                        data: 'task4'
                    },
                    {
                        data: 'task5'
                    },
                    {
                        data: 'task6'
                    },
                    {
                        data: 'task7'
                    },
                    {
                        data: 'kodebooking', // Use a unique identifier for actions
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<button class="btn btn-xs btn-danger" onclick="batalAntrian('${data}')">Batal</button>`;
                        }
                    }
                ],
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

            // 3. Handle Form Submission
            $('#form-pencarian').on('submit', function(e) {
                e.preventDefault();
                // table.ajax.reload(); // Use this for server-side processing

                // DEMO: For demonstration, we'll clear and add mock data
                toastr.info('Mencari data...', 'Info');
                table.clear().draw();
                setTimeout(function() {
                    var mockData = [{
                            tanggalperiksa: '13-09-2025',
                            norm: '123456',
                            namadokter: 'DR. BUDI SANTOSO, SP.A',
                            kodebooking: 'JKN001',
                            jenispasien: 'JKN',
                            keterangan: 'Pasien Rujukan',
                            task1: '10:00',
                            task2: '10:01',
                            task3: '10:05',
                            task4: '',
                            task5: '',
                            task6: '',
                            task7: ''
                        },
                        {
                            tanggalperiksa: '13-09-2025',
                            norm: '654321',
                            namadokter: 'DR. CITRA DEWI, SP.OG',
                            kodebooking: 'UMM002',
                            jenispasien: 'NON-JKN',
                            keterangan: 'Pasien Umum',
                            task1: '10:02',
                            task2: '10:03',
                            task3: '10:08',
                            task4: '10:20',
                            task5: '',
                            task6: '',
                            task7: ''
                        },
                        {
                            tanggalperiksa: '13-09-2025',
                            norm: '789012',
                            namadokter: 'DR. BUDI SANTOSO, SP.A',
                            kodebooking: 'JKN003',
                            jenispasien: 'JKN',
                            keterangan: 'Kontrol Rutin',
                            task1: '10:05',
                            task2: '',
                            task3: '',
                            task4: '',
                            task5: '',
                            task6: '',
                            task7: ''
                        }
                    ];
                    table.rows.add(mockData).draw();
                    toastr.success('Data berhasil dimuat.', 'Sukses');
                }, 1000);
            });

            // Trigger search on page load to show initial data
            $('#form-pencarian').submit();
        });

        // 4. Example function for the action button
        function batalAntrian(kodeBooking) {
            Swal.fire({
                title: 'Anda yakin?',
                text: `Anda akan membatalkan antrian dengan kode booking ${kodeBooking}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Place your AJAX call to the cancellation API here
                    Swal.fire('Dibatalkan!', 'Antrian telah berhasil dibatalkan.', 'success');
                    // Reload table after action
                    // $('#dt-monitoring-antrian').DataTable().ajax.reload();
                }
            });
        }
    </script>
@endsection
