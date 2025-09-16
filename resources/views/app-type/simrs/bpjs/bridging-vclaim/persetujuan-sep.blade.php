@extends('inc.layout')
@section('title', 'Persetujuan SEP')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel">
            <div class="panel-hdr">
                <h2><i class="fas fa-search mr-2"></i> Form Pencarian</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form id="form-search">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="tgl1" class="form-label">Periode Tanggal SEP</label>
                                    <div class="input-daterange input-group" id="datepicker-5">
                                        <input type="text" class="form-control bg-white" name="tgl1" id="tgl1"
                                            value="{{ date('d-m-Y') }}" readonly>
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text fs-xl"><i
                                                    class="fal fa-long-arrow-right"></i></span>
                                        </div>
                                        <input type="text" class="form-control bg-white" name="tgl2" id="tgl2"
                                            value="{{ date('d-m-Y') }}" readonly>
                                    </div>
                                    <span class="help-block">Filter data berdasarkan rentang tanggal SEP.</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label" for="layanan">Jenis Pelayanan</label>
                                    <select class="form-control select2" name="layanan" id="layanan">
                                        <option value="">SEMUA</option>
                                        <option value="f">RAWAT JALAN</option>
                                        <option value="t">RAWAT INAP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-block" id="btSearch">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-hdr">
                <h2><i class="fas fa-list-alt mr-2"></i> Daftar Persetujuan SEP</h2>
                <div class="panel-toolbar">
                    <button class="btn btn-primary btn-sm"
                        onclick="popupwindow('{{-- route('vclaim.pengajuan_add') --}}','Tambah Pengajuan', 800, 600, 'no')">
                        <i class="fas fa-plus mr-1"></i> Tambah Persetujuan SEP
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="persetujuan-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Kartu</th>
                                <th>Jenis Pelayanan</th>
                                <th>Jenis Pengajuan</th>
                                <th>Tanggal SEP</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Fungsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                width: '100%'
            });

            // Inisialisasi Datepicker
            $('#tgl1, #tgl2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = $('#persetujuan-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-persetujuan-sep') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl1 = $('#tgl1').val();
                        d.tgl2 = $('#tgl2').val();
                        d.layanan = $('#layanan').val();
                    }
                },
                columns: [{
                        data: 'nokartu',
                        name: 'nokartu'
                    },
                    {
                        data: 'jns_pelayanan',
                        name: 'jns_pelayanan'
                    },
                    {
                        data: 'jnspengajuan',
                        name: 'jnspengajuan'
                    },
                    {
                        data: 'tglsep',
                        name: 'tglsep'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            let badgeClass = 'badge-secondary';
                            if (data === 'Disetujui') badgeClass = 'badge-success';
                            if (data === 'Ditolak') badgeClass = 'badge-danger';
                            return `<span class="badge ${badgeClass} p-2">${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        name: 'fungsi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let deleteUrl =
                                "{{ route('bpjs.bridging-vclaim.destroy-persetujuan', ':id') }}"
                                .replace(':id', data);
                            // Tombol aksi lain bisa ditambahkan di sini
                            let buttons = `<div class="btn-group btn-group-sm">
                                <a href="javascript:void(0);" onclick="deleteApproval(${data})" class="btn btn-danger" data-toggle="tooltip" title="Hapus Pengajuan"><i class="fas fa-trash"></i></a>
                            </div>`;
                            return buttons;
                        }
                    }
                ],
                order: [
                    [3, 'desc']
                ], // Default order by Tanggal SEP desc
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#btSearch').on('click', function() {
                table.draw();
            });

            // Fungsi untuk menghapus data
            window.deleteApproval = function(id) {
                // Menggunakan fungsi SweetAlert global dari script_footer.blade.php
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: `{{ url('bpjs/bridging-vclaim/persetujuan-sep') }}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                table.draw(); // Muat ulang tabel
                            } else {
                                showErrorAlert(response.message);
                            }
                        },
                        error: function() {
                            showErrorAlert('Tidak dapat menghubungi server.');
                        }
                    });
                });
            }
        });

        // Fungsi helper untuk popup
        function popupwindow(url, title, w, h, scroll) {
            let left = (screen.width / 2) - (w / 2);
            let top = (screen.height / 2) - (h / 2);
            return window.open(url, title,
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=${scroll}, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`
            );
        }
    </script>
@endsection
