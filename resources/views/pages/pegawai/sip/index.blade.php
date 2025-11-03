@extends('inc.layout')
@section('title', 'Data SIP Pegawai')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- ... breadcrumb bisa disesuaikan ... --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tabel <span class="fw-300"><i>Data SIP</i></span></h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-form"
                                id="btn-add"><i class="fal fa-plus"></i> Tambah Manual</button>
                            <div class="btn-group ml-2">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i> Opsi
                                    Excel</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('sip.template') }}"><i
                                            class="fal fa-download"></i> Download Template</a>
                                    <button class="dropdown-item" type="button" data-toggle="modal"
                                        data-target="#modal-import"><i class="fal fa-upload"></i> Import dari Excel</button>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('sip.export') }}"><i
                                            class="fal fa-file-export"></i> Export Semua Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}<button type="button" class="close" data-dismiss="alert"
                                        aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {!! session('error') !!}<button type="button" class="close" data-dismiss="alert"
                                        aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            @endif
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Nomor SIP</th>
                                        <th>Tgl Kadaluarsa</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Modal Form -->
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Data SIP</h5><button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true"><i class="fal fa-times"></i></span></button>
                </div>
                <div class="modal-body">
                    <form id="form-data">
                        <input type="hidden" name="id" id="data-id">
                        <div class="form-group">
                            <label class="form-label" for="employee_id">Pegawai</label>
                            <select class="form-control" id="employee_id" name="employee_id" required>
                                <option value="" disabled selected>Pilih Pegawai...</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sip_number">Nomor SIP</label>
                            <input type="text" id="sip_number" name="sip_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sip_expiry_date">Tanggal Kadaluarsa</label>
                            <input type="date" id="sip_expiry_date" name="sip_expiry_date" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Tutup</button><button type="button" class="btn btn-primary"
                        id="btn-save">Simpan</button></div>
            </div>
        </div>
    </div>
    <!-- Modal Import -->
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data SIP</h5><button type="button" class="close"
                        data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                                class="fal fa-times"></i></span></button>
                </div>
                <form action="{{ route('sip.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info"><strong>Penting!</strong> Pastikan file sesuai template. <a
                                href="{{ route('sip.template') }}">Download template di sini</a>.</div>
                        <div class="custom-file"><input type="file" class="custom-file-input" id="importFile"
                                name="file" required accept=".xlsx, .xls"><label class="custom-file-label"
                                for="importFile">Pilih file...</label></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Tutup</button><button type="submit" class="btn btn-primary"><i
                                class="fal fa-upload"></i> Mulai Import</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#dt-basic-example').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sip.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        className: 'text-center',
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'employee.fullname',
                        name: 'employee.fullname'
                    },
                    {
                        data: 'sip_number',
                        name: 'sip_number'
                    },
                    {
                        data: 'sip_expiry_date',
                        name: 'sip_expiry_date'
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
            });

            $('#btn-add').on('click', function() {
                $('#form-data').trigger('reset');
                $('#data-id').val('');
                $('.modal-title').text('Tambah Data SIP');
            });

            $('#btn-save').on('click', function() {
                var id = $('#data-id').val();
                var url = id ?
                    "{{ route('sip.update', ['sip' => ':id']) }}".replace(':id', id) :
                    "{{ route('sip.store') }}";
                var method = id ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    type: method,
                    data: $('#form-data').serialize(),
                    success: (res) => {
                        $('#modal-form').modal('hide');
                        showSuccessAlert(res.success);
                        table.ajax.reload();
                    },
                    error: (xhr) => {
                        let err = xhr.responseJSON.errors;
                        let msg = '';
                        $.each(err, (key, val) => {
                            msg += val[0] + '\n'
                        });
                        showErrorAlertNoRefresh(msg);
                    }
                });
            });

            $('#dt-basic-example tbody').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $('.modal-title').text('Edit Data SIP');
                $.get("{{ route('sip.edit', ':id') }}".replace(':id', id), function(data) {
                    $('#modal-form').modal('show');
                    $('#data-id').val(data.id);
                    $('#employee_id').val(data.employee_id);
                    $('#sip_number').val(data.sip_number);
                    $('#sip_expiry_date').val(data.sip_expiry_date);
                }).fail(() => showErrorAlertNoRefresh('Gagal mengambil data.'));
            });

            $('#dt-basic-example tbody').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                showDeleteConfirmation(() => {
                    $.ajax({
                        url: "{{ route('sip.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        success: (res) => {
                            showSuccessAlert(res.success);
                            table.ajax.reload();
                        },
                        error: () => showErrorAlert('Gagal menghapus data.')
                    });
                });
            });
            $('.custom-file-input').on('change', function() {
                $(this).next('.custom-file-label').addClass("selected").html($(this).val().split('\\')
                    .pop());
            });
        });
    </script>
@endsection
