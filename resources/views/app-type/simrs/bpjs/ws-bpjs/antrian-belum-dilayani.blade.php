@extends('inc.layout')
@section('title', 'Antrean Belum Dilayani')
@section('content')

    <style>
        table {
            font-size: 7pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Antrean Belum Dilayani <span class="fw-300 fs-sm"><i>(DATA DARI SERVER BPJS)</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="reload-btn" data-toggle="tooltip"
                                title="Muat Ulang Data">
                                <i class="fal fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-belum-dilayani" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th style="width: 2%;">#</th>
                                        <th>Tanggal</th>
                                        <th>No. RM</th>
                                        <th>Peserta</th>
                                        <th>NIK</th>
                                        <th>No. Kartu</th>
                                        <th>No. HP</th>
                                        <th>Jenis Kunjungan</th>
                                        <th>No. Reff</th>
                                        <th>Kode Booking</th>
                                        <th>No Urut</th>
                                        <th>Poli</th>
                                        <th>Kode Dokter</th>
                                        <th>Jam Praktek</th>
                                        <th>Sumber Data</th>
                                        <th>Status</th>
                                        <th>Update All</th>
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var table = $('#dt-belum-dilayani').DataTable({
                responsive: true,
                scrollX: true, // Enable horizontal scrolling for wide table
                processing: true, // Show loading indicator
                pageLength: 200, // Set default entries to 200 as per screenshot
                lengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "All"]
                ],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [],
                // Customize language to match screenshot
                language: {
                    info: "Data _START_ - _END_ dari _TOTAL_",
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
                },
                // AJAX would be used in a real application
                // ajax: '/api/antrian/belum-dilayani',
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'norm'
                    },
                    {
                        data: 'peserta'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'nokartu'
                    },
                    {
                        data: 'nohp'
                    },
                    {
                        data: 'jeniskunjungan'
                    },
                    {
                        data: 'nomorreferensi'
                    },
                    {
                        data: 'kodebooking'
                    },
                    {
                        data: 'angkaantrean'
                    },
                    {
                        data: 'namapoli'
                    },
                    {
                        data: 'kodedokter'
                    },
                    {
                        data: 'jampraktek'
                    },
                    {
                        data: 'sumberdata'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'kodebooking',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0);" onclick="updateTask('${data}')">Update Task Id</a>`;
                        }
                    }
                ],
                // Add row counter
                "fnDrawCallback": function(oSettings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });

            // Function to load data (simulated)
            function loadData() {
                toastr.info('Memuat data antrean...', 'Info');
                table.processing(true); // Manually show processing indicator
                table.clear().draw();

                // --- AJAX Call Placeholder ---
                // In a real app, this would be an AJAX call
                setTimeout(function() {
                    var mockData = [{
                            tanggal: '2025-09-13',
                            norm: '070533',
                            peserta: 'Ya',
                            nik: '3204076608010002',
                            nokartu: '0000418023358',
                            nohp: '089685891166',
                            jeniskunjungan: 'KUNJUNGAN KONTROL',
                            nomorreferensi: '0126R0050925K000400',
                            kodebooking: '2509081858',
                            angkaantrean: '11',
                            namapoli: 'BED',
                            kodedokter: '512030',
                            jampraktek: '08:30-11:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '06-98-13',
                            peserta: 'Ya',
                            nik: '3210125704790001',
                            nokartu: '0002600225021',
                            nohp: '083842368950',
                            jeniskunjungan: 'KUNJUNGAN KONTROL',
                            nomorreferensi: '0126R0050925K000395',
                            kodebooking: '2509081941',
                            angkaantrean: '12',
                            namapoli: 'BED',
                            kodedokter: '512030',
                            jampraktek: '08:30-11:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '07-04-75',
                            peserta: 'Ya',
                            nik: '3210124505480001',
                            nokartu: '0001858351836',
                            nohp: '081997893259',
                            jeniskunjungan: 'KUNJUNGAN KONTROL',
                            nomorreferensi: '0126R0050925K000397',
                            kodebooking: '2509082080',
                            angkaantrean: '13',
                            namapoli: 'BED',
                            kodedokter: '512030',
                            jampraktek: '08:30-11:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '07-05-63',
                            peserta: 'Ya',
                            nik: '3210126806830042',
                            nokartu: '0003308766939',
                            nohp: '081280288958',
                            jeniskunjungan: 'KUNJUNGAN KONTROL',
                            nomorreferensi: '0126R0050925K000416',
                            kodebooking: '2509082359',
                            angkaantrean: '11',
                            namapoli: 'OBG',
                            kodedokter: '213676',
                            jampraktek: '13:00-15:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '53801',
                            peserta: 'Ya',
                            nik: '3210134902820001',
                            nokartu: '0002424126925',
                            nohp: '085242210208',
                            jeniskunjungan: 'KUNJUNGAN KONTROL',
                            nomorreferensi: '0126R0050925K000809',
                            kodebooking: '2509082497',
                            angkaantrean: '11',
                            namapoli: 'INT',
                            kodedokter: '691400',
                            jampraktek: '13:30-17:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '06-68-16',
                            peserta: 'Ya',
                            nik: '3175055005840011',
                            nokartu: '0001768621138',
                            nohp: '085324954464',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '0126B0090825P000614',
                            kodebooking: '2509082594',
                            angkaantrean: '14',
                            namapoli: 'BED',
                            kodedokter: '512030',
                            jampraktek: '08:30-11:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '06-46-29',
                            peserta: 'Ya',
                            nik: '3210116406910022',
                            nokartu: '0001519069511',
                            nohp: '082118137976',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '0126B0210925Y000499',
                            kodebooking: '2509082702',
                            angkaantrean: '12',
                            namapoli: 'OBG',
                            kodedokter: '7740',
                            jampraktek: '09:30-12:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '04-40-84',
                            peserta: 'Ya',
                            nik: '3216066207000028',
                            nokartu: '0001260792044',
                            nohp: '081316679972',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '0126B0250925Y000483',
                            kodebooking: '2509082703',
                            angkaantrean: '12',
                            namapoli: 'OBG',
                            kodedokter: '285431',
                            jampraktek: '16:00-18:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '04-42-39',
                            peserta: 'Ya',
                            nik: '3210126407950081',
                            nokartu: '0000461028205',
                            nohp: '089699984578',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '101715010925Y000578',
                            kodebooking: '2509082704',
                            angkaantrean: '15',
                            namapoli: 'OBG',
                            kodedokter: '213676',
                            jampraktek: '13:00-15:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '01-53-70',
                            peserta: 'Bukan',
                            nik: '3207135111880001',
                            nokartu: '00000000000',
                            nohp: '00000000000',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '',
                            kodebooking: '2509082690',
                            angkaantrean: '2',
                            namapoli: 'ANA',
                            kodedokter: '39441',
                            jampraktek: '07:00-09:00',
                            sumberdata: 'Bridging Antrean',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: '04-87-82',
                            peserta: 'Bukan',
                            nik: '3216182904230001',
                            nokartu: '00000000000',
                            nohp: '00000000000',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '',
                            kodebooking: '2509082641',
                            angkaantrean: '1',
                            namapoli: 'ANA',
                            kodedokter: '39441',
                            jampraktek: '07:00-09:00',
                            sumberdata: 'Bridging Antrean',
                            status: 'Belum dilayani'
                        },
                        {
                            tanggal: '2025-09-13',
                            norm: 'X-00000',
                            peserta: 'Ya',
                            nik: '3210025908950001',
                            nokartu: '0000172992701',
                            nohp: '085220860885',
                            jeniskunjungan: 'RUJUKAN FKTP',
                            nomorreferensi: '101716010925Y001009',
                            kodebooking: '2509082719',
                            angkaantrean: '13',
                            namapoli: 'OBG',
                            kodedokter: '7740',
                            jampraktek: '09:30-12:00',
                            sumberdata: 'Mobile JKN',
                            status: 'Belum dilayani'
                        }
                    ];
                    table.rows.add(mockData).draw();
                    toastr.success('Data berhasil dimuat.', 'Sukses');
                    table.processing(false); // Manually hide processing indicator
                }, 1500); // Simulate network delay
            }

            // Load data for the first time
            loadData();

            // Handle reload button click
            $('#reload-btn').on('click', function() {
                loadData();
            });
        });

        // Example function for the "Update Task Id" link
        function updateTask(kodeBooking) {
            console.log('Update Task for Kode Booking:', kodeBooking);
            toastr.info(`Proses update untuk kode booking ${kodeBooking}...`, 'Proses');
            // Add AJAX call to your update endpoint here
        }
    </script>
@endsection
