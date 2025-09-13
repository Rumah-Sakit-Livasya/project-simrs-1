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
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create-cob">
                                <i class="fal fa-plus-circle mr-1"></i>
                                Tambah COB
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-daftar-cob" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 3%;">#</th>
                                        <th style="width: 7%;">Kode COB</th>
                                        <th style="width: 15%;">Nama COB</th>
                                        <th>Perusahaan</th>
                                        <th>Alamat</th>
                                        <th>uri_endpoint</th>
                                        <th class="text-center" style="width: 7%;">Status</th>
                                        <th class="text-center" style="width: 10%;">Fungsi</th>
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
    {{-- ======================== MODAL CREATE COB ======================= --}}
    {{-- ================================================================= --}}
    <div class="modal fade" id="modal-create-cob" tabindex="-1" role="dialog" aria-labelledby="modal-create-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-create-label">Tambah COB Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="form-create-cob">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_cob">Kode COB</label>
                                    <input type="text" class="form-control" id="kode_cob" name="kode_cob" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_cob">Nama COB</label>
                                    <input type="text" class="form-control" id="nama_cob" name="nama_cob" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perusahaan">Perusahaan</label>
                            <input type="text" class="form-control" id="perusahaan" name="perusahaan" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="uri_endpoint">URI Endpoint</label>
                            <input type="url" class="form-control" id="uri_endpoint" name="uri_endpoint"
                                placeholder="http://contoh.com/api">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            {{-- TAMBAHKAN CLASS 'select2' DI SINI --}}
                            <select class="form-control select2" id="status" name="status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    {{-- TAMBAHKAN SCRIPT PLUGIN SELECT2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        var mockData = [{
            id: 1,
            kode_cob: '0001',
            nama_cob: 'MANDIRI INHEALTH',
            perusahaan: 'PT ASURANSI JIWA INHEALTH INDONESIA',
            alamat: 'MENARA PALMA LANTAI 20, JL. HR. RASUNA SAID',
            uri_endpoint: 'http://app.inhealth.co.id/wsinhealthclaim/api/ir',
            status: 'Aktif'
        }];

        $(document).ready(function() {
            // INISIALISASI SELECT2 UNTUK MODAL CREATE
            $('#status.select2').select2({
                // Opsi ini SANGAT PENTING agar Select2 berfungsi di dalam modal Bootstrap
                dropdownParent: $('#modal-create-cob')
            });

            var table = $('#dt-daftar-cob').DataTable({
                responsive: true,
                scrollX: true,
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                pageLength: 50,
                data: mockData,
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode_cob'
                    },
                    {
                        data: 'nama_cob'
                    },
                    {
                        data: 'perusahaan'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'uri_endpoint'
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
                        data: 'id',
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var editUrl = `/cob/${data}/edit`;
                            var deleteFunc = `deleteCob(${data})`;
                            return `
                                <a href="${editUrl}" class="btn btn-xs btn-icon btn-outline-primary" data-toggle="tooltip" title="Edit COB">
                                    <i class="fal fa-pencil"></i>
                                </a>
                                <button onclick="${deleteFunc}" class="btn btn-xs btn-icon btn-outline-danger" data-toggle="tooltip" title="Hapus COB">
                                    <i class="fal fa-trash"></i>
                                </button>
                            `;
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

            $('#form-create-cob').on('submit', function(e) {
                e.preventDefault();
                var newData = {
                    id: mockData.length + 2,
                    kode_cob: $('#kode_cob').val(),
                    nama_cob: $('#nama_cob').val(),
                    perusahaan: $('#perusahaan').val(),
                    alamat: $('#alamat').val(),
                    uri_endpoint: $('#uri_endpoint').val(),
                    status: $('#status').val(),
                };

                mockData.push(newData);
                table.clear().rows.add(mockData).draw();

                $('#modal-create-cob').modal('hide');
                $(this)[0].reset();
                // RESET SELECT2 SECARA MANUAL SETELAH FORM DI-RESET
                $('#status').val('Aktif').trigger('change');

                toastr.success('Data COB baru berhasil ditambahkan!', 'Sukses');
            });
        });

        function deleteCob(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Data COB ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    mockData = mockData.filter(item => item.id !== id);
                    $('#dt-daftar-cob').DataTable().clear().rows.add(mockData).draw();
                    Swal.fire('Terhapus!', 'Data COB telah dihapus.', 'success');
                }
            });
        }
    </script>
@endsection
