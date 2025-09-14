@extends('inc.layout')
@section('title', 'Data SEP Internal')

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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="nosep">Nomor SEP</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nosep" name="nosep"
                                            placeholder="Masukkan Nomor SEP Induk..." required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="btSearch">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-hdr">
                <h2><i class="fas fa-list-alt mr-2"></i> Data Rujukan Internal SEP</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="sep-internal-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>Data SEP</th>
                                <th>Poli Tujuan</th>
                                <th>Poli Asal</th>
                                <th>Tgl. Rujukan Internal</th>
                                <th>Penunjang</th>
                                <th>Diagnosa</th>
                                <th>Dokter</th>
                                <th>User</th>
                                <th>Tgl. Entry</th>
                                <th>Aksi</th>
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
    <script>
        $(document).ready(function() {
            var table = $('#sep-internal-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-data-sep-internal') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.nosep = $('#nosep').val();
                    }
                },
                columns: [{
                        data: 'tglsep',
                        name: 'tglsep'
                    },
                    {
                        data: 'nmtujuanrujuk',
                        name: 'nmtujuanrujuk'
                    },
                    {
                        data: 'nmpoliasal',
                        name: 'nmpoliasal'
                    },
                    {
                        data: 'tglrujukinternal',
                        name: 'tglrujukinternal'
                    },
                    {
                        data: 'nmpenunjang',
                        name: 'nmpenunjang'
                    },
                    {
                        data: 'nmdiag',
                        name: 'nmdiag'
                    },
                    {
                        data: 'nmdokter',
                        name: 'nmdokter'
                    },
                    {
                        data: 'fuser',
                        name: 'fuser'
                    },
                    {
                        data: 'fdate',
                        name: 'fdate'
                    },
                    {
                        data: 'idrujuk_internal',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0);" onclick="deleteInternal('${row.nosep}', '${data}', '${row.tglrujukinternal}')" class="btn btn-sm btn-icon btn-danger" data-toggle="tooltip" title="Hapus Rujukan Internal"><i class="fas fa-trash"></i></a>`;
                        }
                    }
                ],
                order: [], // Menonaktifkan sorting default
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#btSearch').on('click', function() {
                if ($('#nosep').val()) {
                    table.draw();
                } else {
                    showErrorAlertNoRefresh('Nomor SEP harus diisi.');
                }
            });

            // Fungsi untuk menghapus data
            window.deleteInternal = function(nosep, idrujuk_internal, tglrujukinternal) {
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: `{{ route('bpjs.bridging-vclaim.delete-sep-internal') }}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                            nosep: nosep,
                            idrujuk_internal: idrujuk_internal,
                            tglrujukinternal: tglrujukinternal,
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                table.draw();
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON;
                            showErrorAlert(error.message || 'Gagal menghapus data.');
                        }
                    });
                });
            }
        });
    </script>
@endsection
