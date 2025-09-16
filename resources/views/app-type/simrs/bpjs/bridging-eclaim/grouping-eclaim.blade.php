@extends('inc.layout')
@section('title', 'Grouping E-Claim')
@section('content')

    <style>
        table {
            font-size: 8pt;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
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
                                    {{-- Kolom Kiri --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="tipe_periode">Tipe Periode</label>
                                            <select class="form-control select2" id="tipe_periode" name="tipe_periode">
                                                <option value="TANGGAL_MASUK">Tanggal Masuk</option>
                                                <option value="TANGGAL_PULANG">Tanggal Pulang</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="layanan">Layanan</label>
                                            <select class="form-control select2" id="layanan" name="layanan">
                                                <option value="ALL">ALL</option>
                                                <option value="RAJAL">Rawat Jalan</option>
                                                <option value="RANAP">Rawat Inap</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="no_rm">No RM</label>
                                            <input type="text" class="form-control" id="no_rm" name="no_rm"
                                                placeholder="Masukkan No Rekam Medis">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="status_klaim">Status Klaim</label>
                                            <select class="form-control select2" id="status_klaim" name="status_klaim">
                                                <option value="SEMUA">Semua</option>
                                                <option value="BELUM_DIKLAIM">Belum Diklaim</option>
                                                <option value="SUDAH_DIKLAIM">Sudah Diklaim</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Kolom Tengah --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="awal_periode">Awal Periode Registrasi</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="awal_periode"
                                                    name="awal_periode" value="{{ date('Y-m-d') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="nama_pasien">Nama Pasien</label>
                                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                                placeholder="Masukkan Nama Pasien">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="no_sep">No SEP</label>
                                            <input type="text" class="form-control" id="no_sep" name="no_sep"
                                                placeholder="Masukkan No SEP">
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="akhir_periode">Akhir Periode Registrasi</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" id="akhir_periode"
                                                    name="akhir_periode" value="{{ date('Y-m-d') }}">
                                                <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                            class="fal fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="poliklinik">Poliklinik</label>
                                            <select class="form-control select2" id="poliklinik" name="poliklinik">
                                                <option value="">Semua Poliklinik</option>
                                                <option value="INT">Poli Penyakit Dalam</option>
                                                <option value="BED">Poli Bedah</option>
                                                <option value="OBG">Poli Obstetri dan Ginekologi</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="icd10">ICD 10</label>
                                            <select class="form-control select2" id="icd10" name="icd10">
                                                <option value="SEMUA">Semua</option>
                                                {{-- Opsi ICD 10 bisa di-load via AJAX search --}}
                                            </select>
                                        </div>
                                        <div class="form-group d-flex justify-content-end align-items-end">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fal fa-search mr-1"></i> Cari
                                            </button>
                                            <button type="button" class="btn btn-success" id="btn-export-xls">
                                                <i class="fal fa-file-excel mr-1"></i> Xls
                                            </button>
                                        </div>
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
                            Daftar Pasien BPJS Kesehatan
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-grouping-eclaim" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>No Reg</th>
                                        <th>Nama Pasien</th>
                                        <th>Periode</th>
                                        <th>Department</th>
                                        <th>No Kartu</th>
                                        <th>No SEP</th>
                                        <th>DPJP</th>
                                        <th>Status Claim</th>
                                        <th>Status DC</th>
                                        <th>LPK</th>
                                        <th>Resume Medis</th>
                                        <th>Fungsi</th>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Initialize Plugins
            $('.datepicker').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('.select2').select2({
                width: '100%'
            });

            // 2. Initialize DataTable
            var table = $('#dt-grouping-eclaim').DataTable({
                responsive: true,
                scrollX: true, // IMPORTANT: Enable horizontal scrolling
                processing: true,
                pageLength: 50,
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                language: {
                    emptyTable: "Tidak ada data untuk ditampilkan"
                },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'no_reg'
                    },
                    {
                        data: 'nama_pasien'
                    },
                    {
                        data: 'periode'
                    },
                    {
                        data: 'department'
                    },
                    {
                        data: 'no_kartu'
                    },
                    {
                        data: 'no_sep'
                    },
                    {
                        data: 'dpjp'
                    },
                    {
                        data: 'status_claim'
                    },
                    {
                        data: 'status_dc'
                    },
                    {
                        data: 'lpk'
                    },
                    {
                        data: 'resume_medis'
                    },
                    {
                        data: 'no_reg', // Use a unique ID for actions
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-xs btn-primary" onclick="prosesGrouping('${data}')">Grouping</button>`;
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
                        cell.innerHTML = i + 1 + oSettings._iDisplayStart;
                    });
                }
            });

            // 3. Handle Form Submission
            $('#form-pencarian').on('submit', function(e) {
                e.preventDefault();
                // For real app, use: table.ajax.reload();

                // Demo logic
                toastr.info('Mencari data pasien...', 'Proses');
                table.clear().draw();
                setTimeout(function() {
                    var mockData = [{
                            no_reg: 'REG001',
                            nama_pasien: 'JANE DOE',
                            periode: '2025-09-10 s/d 2025-09-13',
                            department: 'RAWAT INAP BEDAH',
                            no_kartu: '0001234567890',
                            no_sep: '0123R00109250000001',
                            dpjp: 'DR. FAJAR NUGROHO, SP.B',
                            status_claim: 'Belum',
                            status_dc: 'Belum',
                            lpk: 'Belum',
                            resume_medis: 'Sudah'
                        },
                        {
                            no_reg: 'REG002',
                            nama_pasien: 'JOHN SMITH',
                            periode: '2025-09-13 s/d 2025-09-13',
                            department: 'RAWAT JALAN DALAM',
                            no_kartu: '0009876543210',
                            no_sep: '0123R00109250000002',
                            dpjp: 'DR. ADI KURNIAWAN, SP.PD',
                            status_claim: 'Belum',
                            status_dc: 'Belum',
                            lpk: 'Belum',
                            resume_medis: 'Sudah'
                        }
                    ];
                    table.rows.add(mockData).draw();
                    toastr.success('Data ditemukan.', 'Sukses');
                }, 1000);
            });

            // 4. Handle Export Button
            $('#btn-export-xls').on('click', function() {
                toastr.info('Fungsi ekspor ke Excel akan dijalankan.', 'Info');
                // Add your export logic here
            });
        });

        // 5. Example function for action button
        function prosesGrouping(noReg) {
            toastr.info(`Memproses grouping untuk No Registrasi: ${noReg}`, 'Proses');
            // Add your AJAX call for grouping process here
        }
    </script>
@endsection
