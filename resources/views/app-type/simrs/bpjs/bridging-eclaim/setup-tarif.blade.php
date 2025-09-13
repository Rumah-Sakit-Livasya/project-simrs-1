@extends('inc.layout')
@section('title', 'Daftar COB')
@section('content')

    <style>
        table {
            font-size: 8pt;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>COB</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            {{-- TOMBOL INI DIUBAH UNTUK MEMBUKA MODAL --}}
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create-cob">
                                <i class="fal fa-plus-circle mr-1"></i>
                                Tambah Jenis Tarif
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-daftar-cob" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th style="width: 15%;">Kode Tarif</th>
                                        <th>Nama Tarif</th>
                                        <th class="text-center" style="width: 15%;">Status</th>
                                        <th class="text-center" style="width: 15%;">Fungsi</th>
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

    {{-- ================================================================= --}}
    {{-- ======================= MODAL CREATE BARU ======================= --}}
    {{-- ================================================================= --}}
    <div class="modal fade" id="modal-create-cob" tabindex="-1" role="dialog" aria-labelledby="modal-create-cob-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-create-cob-label">Tambah Jenis Tarif Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="form-create-cob">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_tarif">Kode Tarif</label>
                            <input type="text" class="form-control" id="kode_tarif" name="kode_tarif"
                                placeholder="Contoh: AP, CS, dll." required>
                        </div>
                        <div class="form-group">
                            <label for="nama_tarif">Nama Tarif</label>
                            <input type="text" class="form-control" id="nama_tarif" name="nama_tarif"
                                placeholder="Contoh: TARIF RS KELAS A PEMERINTAH" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Awal</label>
                            <select class="form-control select2" id="status" name="status" required>
                                <option value="Non Aktif" selected>Non Aktif</option>
                                <option value="Aktif">Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-save-cob">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    {{-- PASTIKAN PLUGIN SELECT2 SUDAH DI-INCLUDE --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    code
    Code
    <script>
        $(document).ready(function() {
            // =================================================================
            // ======================= INISIALISASI PLUGIN =====================
            // =================================================================

            // Inisialisasi Select2 di dalam modal
            // Opsi 'dropdownParent' sangat penting agar dropdown Select2 muncul di atas modal
            $('#status.select2').select2({
                dropdownParent: $('#modal-create-cob')
            });

            // Initialize DataTables (kode dari sebelumnya)
            var table = $('#dt-daftar-cob').DataTable({
                responsive: true,
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                pageLength: 50,
                lengthChange: false,
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode_tarif'
                    },
                    {
                        data: 'nama_tarif'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data === 'Aktif' ?
                                '<span class="badge badge-success">Aktif</span>' :
                                '<span class="badge badge-secondary">Non Aktif</span>';
                        }
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            let btnClass = row.status === 'Aktif' ? 'btn-danger' : 'btn-success';
                            let btnIcon = row.status === 'Aktif' ? 'fa-toggle-off' : 'fa-toggle-on';
                            let btnText = row.status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan';
                            let action = `toggleStatus('${row.kode_tarif}', '${row.status}')`;
                            return `<button onclick="${action}" class="btn btn-xs ${btnClass}"><i class="fal ${btnIcon} mr-1"></i> ${btnText}</button>`;
                        }
                    }
                ],
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

            // Mock Data (kode dari sebelumnya)
            var mockData = [{
                    id: 1,
                    kode_tarif: 'AP',
                    nama_tarif: 'TARIF RS KELAS A PEMERINTAH',
                    status: 'Non Aktif'
                },
                {
                    id: 2,
                    kode_tarif: 'AS',
                    nama_tarif: 'TARIF RS KELAS A SWASTA',
                    status: 'Non Aktif'
                },
                {
                    id: 3,
                    kode_tarif: 'BP',
                    nama_tarif: 'TARIF RS KELAS B PEMERINTAH',
                    status: 'Non Aktif'
                },
                {
                    id: 4,
                    kode_tarif: 'BS',
                    nama_tarif: 'TARIF RS KELAS B SWASTA',
                    status: 'Non Aktif'
                },
                {
                    id: 5,
                    kode_tarif: 'CP',
                    nama_tarif: 'TARIF RS KELAS C PEMERINTAH',
                    status: 'Non Aktif'
                },
                {
                    id: 6,
                    kode_tarif: 'CS',
                    nama_tarif: 'TARIF RS KELAS C SWASTA',
                    status: 'Aktif'
                },
                {
                    id: 7,
                    kode_tarif: 'DP',
                    nama_tarif: 'TARIF RS KELAS D PEMERINTAH',
                    status: 'Non Aktif'
                },
                {
                    id: 8,
                    kode_tarif: 'DS',
                    nama_tarif: 'TARIF RS KELAS D SWASTA',
                    status: 'Non Aktif'
                },
                {
                    id: 9,
                    kode_tarif: 'RSCM',
                    nama_tarif: 'TARIF RSUPN CIPTO MANGUNKUSUMO',
                    status: 'Non Aktif'
                }
            ];
            table.clear().rows.add(mockData).draw();

            // Handle Form Submission with AJAX
            $('#form-create-cob').on('submit', function(e) {
                e.preventDefault();

                var btn = $('#btn-save-cob');
                btn.prop('disabled', true);
                btn.find('.spinner-border').removeClass('d-none');

                var formData = $(this).serialize();

                // Simulasi AJAX
                setTimeout(function() {
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').addClass('d-none');

                    $('#modal-create-cob').modal('hide');

                    toastr.success('Data tarif berhasil ditambahkan! (Demo)', 'Sukses');

                    var newData = {
                        id: Math.floor(Math.random() * 100),
                        kode_tarif: $('#kode_tarif').val(),
                        nama_tarif: $('#nama_tarif').val(),
                        status: $('#status').val()
                    };
                    table.row.add(newData).draw(false);

                    $('#form-create-cob')[0].reset();
                    // Reset Select2 ke nilai default setelah form di-reset
                    $('#status').val('Non Aktif').trigger('change');
                }, 1000);
            });
        });

        // Function to toggle status
        function toggleStatus(kodeTarif, currentStatus) {
            let nextStatus = currentStatus === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan';
            let nextStatusText = currentStatus === 'Aktif' ? 'Non Aktif' : 'Aktif';

            Swal.fire({
                title: `Anda yakin ingin ${nextStatus} tarif ini?`,
                text: `Status tarif dengan kode "${kodeTarif}" akan diubah menjadi ${nextStatusText}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Ya, ${nextStatus}!`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.success(`Status untuk tarif ${kodeTarif} berhasil diubah.`, 'Sukses!');
                }
            });
        }
    </script>
@endsection
