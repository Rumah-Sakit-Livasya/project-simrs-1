@extends('inc.layout-no-side')
@section('title', 'Set Tarif Visite Dokter')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-pills'></i> Set Tarif Visite: <span
                    class="fw-300">{{ $doctor->employee->fullname }}</span>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div id="panel-form" class="panel">
                    <div class="panel-hdr">
                        <h2>Form Tarif</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-tarif" autocomplete="off">
                                @csrf
                                <input type="hidden" name="id" id="form-id">
                                <input type="hidden" name="doctor_id" id="doctor_id" value="{{ $doctor->id }}">

                                <div class="form-group">
                                    <label for="kelas_rawat_id">Kelas Rawat</label>
                                    <select class="form-control" id="kelas_rawat_id" name="kelas_rawat_id"
                                        required></select>
                                </div>
                                <div class="form-group">
                                    <label for="share_rs">Share RS</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="number" class="form-control" id="share_rs" name="share_rs" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="share_dr">Share Dokter</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="number" class="form-control" id="share_dr" name="share_dr" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="prasarana">Prasarana</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="number" class="form-control" id="prasarana" name="prasarana" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary mr-2" id="btn-batal">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Tarif {{ $doctor->employee->fullname }}</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-tarif-dokter" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas Rawat</th>
                                        <th>Share RS</th>
                                        <th>Share Dokter</th>
                                        <th>Prasarana</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2
            $('#kelas_rawat_id').select2({
                width: '100%',
                placeholder: 'Pilih Kelas Rawat',
                data: [{
                        id: '',
                        text: ''
                    }, // Placeholder option
                    @foreach ($kelasRawat as $kelas)
                        {
                            id: '{{ $kelas->id }}',
                            text: '{{ $kelas->kelas }}'
                        },
                    @endforeach
                ]
            });

            // Inisialisasi DataTables
            var table = $('#dt-tarif-dokter').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.tarif.by.doctor', $doctor->id) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kelas_rawat_name',
                        name: 'kelas_rawat.kelas'
                    },
                    {
                        data: 'share_rs',
                        name: 'share_rs'
                    },
                    {
                        data: 'share_dr',
                        name: 'share_dr'
                    },
                    {
                        data: 'prasarana',
                        name: 'prasarana'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            function resetForm() {
                $('#form-tarif').trigger("reset");
                $('#form-id').val('');
                $('#kelas_rawat_id').val(null).trigger('change');
                $('#panel-form .panel-hdr h2').html("Form Tarif");
                $('#btn-save').html("Simpan");
            }

            $('#btn-batal').click(resetForm);

            // Tombol Edit
            $('body').on('click', '#editBtn', function() {
                var id = $(this).data('id');
                $.get("{{ url('simrs/tarif-visite-dokter') }}/" + id + "/edit", function(data) {
                    $('#panel-form .panel-hdr h2').html("Edit Tarif");
                    $('#btn-save').html("Update");
                    $('#form-id').val(data.id);
                    $('#kelas_rawat_id').val(data.kelas_rawat_id).trigger('change');
                    $('#share_rs').val(data.share_rs);
                    $('#share_dr').val(data.share_dr);
                    $('#prasarana').val(data.prasarana);
                })
            });

            // Tombol Simpan/Update
            $('#form-tarif').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('tarif-visite-dokter.store') }}",
                    data: $(this).serialize(),
                    success: function(data) {
                        showSuccessAlert(data.message);
                        resetForm();
                        table.draw();
                    },
                    error: function(data) {
                        showErrorAlertNoRefresh('Terjadi kesalahan saat menyimpan data');
                    }
                });
            });

            // Tombol Hapus
            $('body').on('click', '#deleteBtn', function() {
                var id = $(this).data('id');
                showDeleteConfirmation(function() {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('master/tarif-visite-dokter') }}/" + id,
                        success: function(data) {
                            showSuccessAlert(data.message);
                            table.draw();
                        },
                        error: function(data) {
                            showErrorAlertNoRefresh(
                                'Terjadi kesalahan saat menghapus data');
                        }
                    });
                });
            });
        });
    </script>
@endsection
