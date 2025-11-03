@extends('inc.layout')
@section('title', 'Background Pendidikan Pegawai')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">HRIS</a></li>
            <li class="breadcrumb-item">Data Master</li>
            <li class="breadcrumb-item active">Background Pendidikan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel <span class="fw-300"><i>Background Pendidikan</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-form"
                                id="btn-add">
                                <i class="fal fa-plus"></i>
                                Tambah Data
                            </button>
                            <div class="btn-group ml-2">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fal fa-file-excel"></i> Excel
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('educational-background.template') }}">
                                        <i class="fal fa-download"></i> Download Template
                                    </a>
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                        data-target="#modal-import">
                                        <i class="fal fa-upload"></i> Import Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Pendidikan Terakhir</th>
                                        <th>Tahun Lulus</th>
                                        <th>No. Ijazah</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Form -->
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Background Pendidikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-data">
                        <input type="hidden" name="id" id="data-id">
                        <div class="form-group">
                            <label class="form-label" for="employee_id">Pegawai</label>
                            <select class="form-control" id="employee_id" name="employee_id" required style="width:100%">
                                <option value="" disabled selected>Pilih Pegawai...</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="last_education">Pendidikan Terakhir</label>
                                    <input type="text" id="last_education" name="last_education" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="graduation_year">Tahun Lulus</label>
                                    <input type="number" id="graduation_year" name="graduation_year" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label" for="diploma_number">Nomor Ijazah</label>
                            <input type="text" id="diploma_number" name="diploma_number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="basic_qualifications">Kualifikasi Dasar</label>
                            <textarea id="basic_qualifications" name="basic_qualifications" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="initial_competency">Kompetensi Awal</label>
                            <textarea id="initial_competency" name="initial_competency" class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data dari Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form action="{{ route('educational-background.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">File Excel</label>
                            <div class="alert alert-info">
                                <strong>Penting!</strong> Pastikan file yang diupload sesuai dengan template yang telah
                                disediakan.
                                <a href="{{ route('educational-background.template') }}">Download template di sini</a>.
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="importFile" name="file" required
                                    accept=".xlsx, .xls">
                                <label class="custom-file-label" for="importFile">Pilih file...</label>
                            </div>
                            <span class="help-block">Hanya file dengan format .xlsx atau .xls yang diizinkan.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fal fa-upload"></i>
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi select2 untuk employee_id
            $('#employee_id').select2({
                dropdownParent: $('#modal-form'),
                width: '100%',
                placeholder: "Pilih Pegawai...",
                allowClear: true
            });

            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi DataTables
            var table = $('#dt-basic-example').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('educational-background.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'last_education',
                        name: 'last_education'
                    },
                    {
                        data: 'graduation_year',
                        name: 'graduation_year'
                    },
                    {
                        data: 'diploma_number',
                        name: 'diploma_number'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                responsive: true,
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
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // Reset form saat tombol tambah di klik
            $('#btn-add').on('click', function() {
                $('#form-data').trigger('reset');
                $('#data-id').val('');
                $('#employee_id').val('').trigger('change');
                $('.modal-title').text('Tambah Data Background Pendidikan');
            });

            // Logic Simpan dan Update
            $('#btn-save').on('click', function() {
                var id = $('#data-id').val();
                var url = id ? "{{ url('educational-background') }}/" + id :
                    "{{ route('educational-background.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#form-data').serialize(),
                    success: function(response) {
                        $('#modal-form').modal('hide');
                        showSuccessAlert(response.success);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        showErrorAlertNoRefresh(errorMessage);
                    }
                });
            });

            // Logic Edit
            $('#dt-basic-example tbody').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                var url = "{{ route('educational-background.edit', ':id') }}".replace(':id', id);
                $('.modal-title').text('Edit Data Background Pendidikan');

                $.get(url, function(response) {
                    var data = response.data;
                    var educational = data.educational_background ?? {};

                    $('#modal-form').modal('show');

                    // Set field values based on flat Employee + nested educational_background
                    $('#data-id').val(educational.id ?? '');
                    $('#employee_id').val(data.id ?? '').trigger('change');
                    $('#last_education').val(educational.last_education ?? '');
                    $('#graduation_year').val(educational.graduation_year ?? '');
                    $('#diploma_number').val(educational.diploma_number ?? '');
                    $('#basic_qualifications').val(educational.basic_qualifications ?? '');
                    $('#initial_competency').val(educational.initial_competency ?? '');
                });
            });

            // Logic Hapus
            $('#dt-basic-example tbody').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                var url = "{{ route('educational-background.destroy', ':id') }}".replace(':id', id);

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            showSuccessAlert(response.success);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            showErrorAlert('Gagal menghapus data.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
