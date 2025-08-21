@extends('pages.simrs.erm.index') {{-- Sesuaikan dengan layout utama ERM Anda --}}
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pencatatan dan Pengawasan Infus</h5>
            <div>
                <button class="btn btn-primary" id="btn-add-infusion">
                    <i class="mdi mdi-plus-circle"></i> Tambah Form
                </button>
                {{-- Tombol Histori bisa dikembangkan nanti --}}
                <button class="btn btn-secondary"><i class="mdi mdi-history"></i> Histori</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="infusion-table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Hari/Tanggal</th>
                        <th>Jam</th>
                        <th>Kolf</th>
                        <th>Jenis Cairan & Kecepatan Tetesan</th>
                        <th>Keterangan</th>
                        <th>Masuk (cc)</th>
                        <th>Sisa (cc)</th>
                        <th>Nama Perawat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Include Modal --}}
    @include('pages.simrs.erm.form.perawat.partials.modal-infusion-monitor')
@endsection

@push('scripts')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Datatables
            var table = $('#infusion-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('api.infusion.index', $registration->id) }}",
                columns: [{
                        data: 'waktu_infus.tanggal',
                        name: 'waktu_infus'
                    },
                    {
                        data: 'waktu_infus.jam',
                        name: 'waktu_infus'
                    },
                    {
                        data: 'kolf_ke',
                        name: 'kolf_ke'
                    },
                    {
                        data: 'jenis_cairan',
                        name: 'jenis_cairan'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'cairan_masuk',
                        name: 'cairan_masuk'
                    },
                    {
                        data: 'cairan_sisa',
                        name: 'cairan_sisa'
                    },
                    {
                        data: 'nama_perawat',
                        name: 'nama_perawat'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Buka Modal untuk Tambah
            $('#btn-add-infusion').click(function() {
                $('#infusion_id').val('');
                $('#infusion-form').trigger("reset");
                $('#nama_perawat').val("{{ auth()->user()->name }}"); // Set nama perawat default
                $('#modal-title').html("Tambah Pencatatan Infus");
                $('#infusion-modal').modal('show');
            });

            // Buka Modal untuk Edit
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ url('api/simrs/erm/infusion-monitors') }}/" + id + '/edit', function(data) {
                    $('#modal-title').html("Edit Pencatatan Infus");
                    $('#infusion_id').val(data.id);
                    // Format datetime untuk input type="datetime-local"
                    const localDateTime = new Date(new Date(data.waktu_infus).getTime() - (
                        new Date().getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
                    $('#waktu_infus').val(localDateTime);
                    $('#kolf_ke').val(data.kolf_ke);
                    $('#jenis_cairan').val(data.jenis_cairan);
                    $('#cairan_masuk').val(data.cairan_masuk);
                    $('#cairan_sisa').val(data.cairan_sisa);
                    $('#keterangan').val(data.keterangan);
                    $('#nama_perawat').val(data.nama_perawat);
                    $('#infusion-modal').modal('show');
                });
            });

            // Simpan Data (Create & Update)
            $('#btn-save-infusion').click(function(e) {
                e.preventDefault();
                $(this).prop('disabled', true).html('Menyimpan...');

                var id = $('#infusion_id').val();
                var url = id ? "{{ url('api/simrs/erm/infusion-monitors') }}/" + id + '/update' :
                    "{{ route('api.infusion.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    data: $('#infusion-form').serialize(),
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(response) {
                        $('#infusion-modal').modal('hide');
                        table.draw();
                        Swal.fire('Sukses!', response.success, 'success');
                    },
                    error: function(jqXHR) {
                        alert('Terjadi kesalahan.');
                    },
                    complete: function() {
                        $('#btn-save-infusion').prop('disabled', false).html('Simpan');
                    }
                });
            });

            // Hapus Data
            $('body').on('click', '.delete-btn', function() {
                var id = $(this).data("id");
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('api/simrs/erm/infusion-monitors') }}/" + id,
                            success: function(response) {
                                table.draw();
                                Swal.fire('Dihapus!', response.success, 'success');
                            },
                            error: function() {
                                alert('Gagal menghapus data.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
