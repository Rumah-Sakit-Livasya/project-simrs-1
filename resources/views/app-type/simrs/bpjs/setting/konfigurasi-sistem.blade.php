@extends('inc.layout')
@section('title', 'Konfigurasi Sistem')
@section('content')

    <style>
        table {
            font-size: 8pt;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Search Form</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-search-config">
                                <div class="row align-items-end">
                                    <div class="col-md-9">
                                        <label class="form-label" for="nama_search">Nama</label>
                                        <input type="text" id="nama_search" class="form-control"
                                            placeholder="Cari berdasarkan keterangan...">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-konfigurasi" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Konfigurasi Sistem
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-konfigurasi" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 3%;">#</th>
                                        <th style="width: 15%;">Group</th>
                                        <th>Keterangan</th>
                                        <th>Data</th>
                                        <th style="width: 15%;">Type Data</th>
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

    {{-- ============================================================= --}}
    {{-- ======================= MODAL EDIT ========================== --}}
    {{-- ============================================================= --}}
    <div class="modal fade" id="modal-edit-config" tabindex="-1" role="dialog" aria-labelledby="modal-edit-config-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-edit-config-label">Edit Konfigurasi Sistem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="form-edit-config">
                    @csrf
                    @method('PUT') {{-- Method spoofing for update --}}
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_group">Group</label>
                            <input type="text" class="form-control" id="edit_group" name="group" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="edit_keterangan" name="keterangan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_data">Data</label>
                            <textarea class="form-control" id="edit_data" name="data" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_type_data">Type Data</label>
                            <input type="text" class="form-control" id="edit_type_data" name="type_data">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-update-config">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Simpan Perubahan
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

    <script>
        // Store mock data in a global scope to be accessible for updates
        var mockData = [{
                id: 1,
                group: 'BPJS KESEHATAN',
                keterangan: 'Antrian Online Vclaim',
                data: 'https://apijkn.bpjs-kesehatan.go.id/antreanrs/',
                type_data: 'ANTRIAN ONLINE VCLAIM'
            },
            {
                id: 2,
                group: 'BPJS KESEHATAN',
                keterangan: 'Config insid BPJS',
                data: '224',
                type_data: 'INSURANCE ID BPJS'
            },
            {
                id: 3,
                group: 'BPJS KESEHATAN',
                keterangan: 'Cons ID BPJS',
                data: '11461',
                type_data: 'CONS ID BPJS'
            },
            {
                id: 4,
                group: 'BPJS KESEHATAN',
                keterangan: 'Encryption Key Eclaim',
                data: '3eeb9b7eab966f2fc42f23204a2b6359c98ec45862f37803d',
                type_data: ''
            },
            {
                id: 5,
                group: 'BPJS KESEHATAN',
                keterangan: 'Kode RS Eclaim',
                data: '3210038',
                type_data: ''
            },
            {
                id: 6,
                group: 'BPJS KESEHATAN',
                keterangan: 'Link Aplicare',
                data: 'https://apijkn.bpjs-kesehatan.go.id/aplicaresws/',
                type_data: ''
            },
            {
                id: 7,
                group: 'BPJS KESEHATAN',
                keterangan: 'Link BPJS Server',
                data: 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/',
                type_data: ''
            },
            {
                id: 8,
                group: 'BPJS KESEHATAN',
                keterangan: 'Link Eclaim',
                data: 'http://192.168.1.129/E-Klaim/ws.php',
                type_data: ''
            }
        ];

        $(document).ready(function() {
            var table = $('#dt-konfigurasi').DataTable({
                responsive: true,
                dom: "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                pageLength: 50,
                data: mockData, // Load data directly
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'group'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'data'
                    },
                    {
                        data: 'type_data'
                    },
                    {
                        data: null, // Pass the full row object
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            // Use data-* attributes to pass data to the modal
                            return `<button class="btn btn-xs btn-outline-primary" 
                                        data-toggle="modal" 
                                        data-target="#modal-edit-config"
                                        data-id="${row.id}"
                                        data-group="${row.group}"
                                        data-keterangan="${row.keterangan}"
                                        data-data="${row.data}"
                                        data-type_data="${row.type_data}">Edit</button>`;
                        }
                    }
                ],
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

            // Handle search form
            $('#form-search-config').on('submit', function(e) {
                e.preventDefault();
                table.column(2).search($('#nama_search').val()).draw();
            });

            // SCRIPT UNTUK MODAL EDIT
            $('#modal-edit-config').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var modal = $(this);

                // Extract data from data-* attributes
                var id = button.data('id');
                var group = button.data('group');
                var keterangan = button.data('keterangan');
                var data = button.data('data');
                var type_data = button.data('type_data');

                // Populate the form fields in the modal
                modal.find('#edit_id').val(id);
                modal.find('#edit_group').val(group);
                modal.find('#edit_keterangan').val(keterangan);
                modal.find('#edit_data').val(data);
                modal.find('#edit_type_data').val(type_data);
            });

            // Handle Edit Form Submission with AJAX
            $('#form-edit-config').on('submit', function(e) {
                e.preventDefault();
                var btn = $('#btn-update-config');
                btn.prop('disabled', true).find('.spinner-border').removeClass('d-none');

                var formData = $(this).serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});

                // AJAX Call Placeholder
                setTimeout(function() {
                    // Update the data in the source array (mockData)
                    var index = mockData.findIndex(item => item.id == formData.id);
                    if (index !== -1) {
                        mockData[index].data = formData.data;
                        mockData[index].type_data = formData.type_data;
                    }

                    // Redraw the table with updated data
                    table.clear().rows.add(mockData).draw();

                    btn.prop('disabled', false).find('.spinner-border').addClass('d-none');
                    $('#modal-edit-config').modal('hide');
                    toastr.success('Konfigurasi berhasil diperbarui!', 'Sukses');
                }, 1000); // Simulate network delay
            });
        });
    </script>
@endsection
