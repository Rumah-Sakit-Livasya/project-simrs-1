@extends('inc.layout')
@section('title', 'Bridging Applicare - Ketersediaan Kamar')

@section('extended-css')
    {{-- CSS untuk grouping di tabel --}}
    <style>
        .group-start td {
            border-top: 2px solid #c0c0c0 !important;
        }

        td.empty-cell {
            border-bottom-width: 0 !important;
            border-top-width: 0 !important;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- ... (subheader, panel-header, dll.) ... --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-table"></i> Tabel Ketersediaan Kamar</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#mappingModal">
                                <i class="fas fa-cog mr-2"></i> Setting Mapping Kelas
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab_internal_rs" role="tab">
                                        <i class="fal fa-hospital mr-1"></i> Data Internal RS (SIMRS)
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab_bpjs" role="tab">
                                        <i class="fal fa-server mr-1"></i> Data di BPJS (Applicare)
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3">

                                {{-- TAB KONTEN INTERNAL RS --}}
                                <div class="tab-pane fade show active" id="tab_internal_rs" role="tabpanel">
                                    <div class="table-responsive">
                                        {{-- Struktur Thead disesuaikan dengan gambar --}}
                                        <table id="dt-internal-rs"
                                            class="table table-bordered table-hover table-striped w-100">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Aplicare</th>
                                                    <th>Nama Kelas</th>
                                                    <th>Kode Ruangan</th>
                                                    <th>Nama Ruangan</th>
                                                    <th>Total Bed</th>
                                                    <th>Bed Terpakai</th>
                                                    <th>Sisa Bed</th>
                                                    <th>Mapping Ruangan</th>
                                                    <th>Fungsi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- TAB KONTEN BPJS --}}
                                <div class="tab-pane fade" id="tab_bpjs" role="tabpanel">
                                    <table id="dt-bpjs" class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-success-600">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Ruang</th>
                                                <th>Kode Ruang</th>
                                                <th>Nama Kelas</th>
                                                <th>Kode Kelas</th>
                                                <th>Kapasitas</th>
                                                <th>Tersedia</th>
                                                <th>Tersedia (Pria)</th>
                                                <th>Tersedia (Wanita)</th>
                                                <th>Update Terakhir</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL (Tambahkan input untuk urutan) --}}
        <div class="modal fade" id="mappingModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Setting Mapping Kelas BPJS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Pilih kelas BPJS yang sesuai untuk setiap kelas rawat internal Anda.
                            Perubahan akan berlaku untuk semua ruangan dengan kelas rawat yang sama.
                        </div>
                        <form id="mappingForm">
                            <div class="form-group">
                                <label for="kelas_rawat_id">Pilih Kelas Rawat Internal (SIMRS)</label>
                                <select class="form-control" id="kelas_rawat_id" name="kelas_rawat_id" required>
                                    <option value="" disabled selected>-- Pilih Kelas Internal --</option>
                                    @foreach ($kelasRawatInternal as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            data-aplicare-code="{{ $kelas->aplicare_code }}">
                                            {{ $kelas->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="aplicare_code">Set ke Kelas BPJS (Applicare)</label>
                                <select class="form-control" id="aplicare_code" name="aplicare_code" required>
                                    <option value="" disabled selected>-- Pilih Kelas BPJS --</option>
                                    @foreach ($kelasBpjs as $kelas)
                                        <option value="{{ $kelas['kodekelas'] }}">{{ $kelas['namakelas'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="aplicare_urutan">Nomor Urut Tampilan</label>
                                <input type="number" class="form-control" id="aplicare_urutan" name="aplicare_urutan"
                                    placeholder="Contoh: 1">
                                <small class="form-text text-muted">Gunakan untuk mengurutkan grup kelas di tabel.</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="saveMappingBtn">Simpan Mapping</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
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

            // DataTable Internal RS dengan Grouping Kelas dan sorting aplicare_urutan
            const dtInternal = $('#dt-internal-rs').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('aplicares.data') }}",
                // Urutkan default berdasarkan kolom urutan (hidden)
                order: [
                    [10, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'aplicare_code',
                        name: 'kelas_rawat.aplicare_code',
                        orderable: false
                    },
                    {
                        data: 'class_name',
                        name: 'kelas_rawat.kelas',
                        orderable: false
                    },
                    {
                        data: 'kode_ruang',
                        name: 'no_ruang',
                        orderable: false
                    },
                    {
                        data: 'ruangan',
                        name: 'ruangan',
                        orderable: false
                    },
                    {
                        data: 'beds_count',
                        name: 'beds_count',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'beds_terpakai_count',
                        name: 'beds_terpakai_count',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'sisa_bed',
                        name: 'sisa_bed',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'mapping_status',
                        name: 'mapping_status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    // Kolom tersembunyi untuk sorting urutan
                    {
                        data: 'aplicare_urutan',
                        name: 'kelas_rawat.aplicare_urutan',
                        visible: false
                    },
                ],
                paging: false,
                info: false,
                drawCallback: function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    // Indeks kolom untuk grouping
                    var aplicareCodeIndex = 1;
                    var classNameIndex = 2;

                    api.column(classNameIndex, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            // Baris grup baru, tambahkan class group-start
                            $(rows).eq(i).addClass('group-start');
                            last = group;
                        } else {
                            // Baris dalam grup, kosongkan kolom agrupasi
                            $(rows).eq(i).find('td').eq(aplicareCodeIndex).html('').addClass(
                                'empty-cell');
                            $(rows).eq(i).find('td').eq(classNameIndex).html('').addClass(
                                'empty-cell');
                        }
                    });
                }
            });

            // DataTable BPJS
            const dtBpjs = $('#dt-bpjs').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                paging: false,
                info: false,
                ajax: "{{ route('aplicares.bpjs-data') }}",
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'namaruang',
                        name: 'namaruang'
                    },
                    {
                        data: 'koderuang',
                        name: 'koderuang'
                    },
                    {
                        data: 'namakelas',
                        name: 'namakelas'
                    },
                    {
                        data: 'kodekelas',
                        name: 'kodekelas'
                    },
                    {
                        data: 'kapasitas',
                        name: 'kapasitas'
                    },
                    {
                        data: 'tersedia',
                        name: 'tersedia'
                    },
                    {
                        data: 'tersediapria',
                        name: 'tersediapria'
                    },
                    {
                        data: 'tersediawanita',
                        name: 'tersediawanita'
                    },
                    {
                        data: 'lastupdate',
                        name: 'lastupdate'
                    }
                ]
            });

            // -- Modal Handling Mapping Kelas BPJS
            $('#kelas_rawat_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const aplicareCode = selectedOption.data('aplicare-code');
                $('#aplicare_code').val(aplicareCode || '');
            });

            $('#saveMappingBtn').on('click', function() {
                const kelasRawatId = $('#kelas_rawat_id').val();
                const aplicareCode = $('#aplicare_code').val();
                const aplicareName = $('#aplicare_code option:selected').text();
                const aplicareUrutan = $('#aplicare_urutan').val();

                if (!kelasRawatId || !aplicareCode) {
                    showErrorAlertNoRefresh('Harap lengkapi semua pilihan.');
                    return;
                }

                $.ajax({
                    url: "{{ route('aplicares.save-kelas-mapping') }}",
                    type: 'POST',
                    data: {
                        kelas_rawat_id: kelasRawatId,
                        aplicare_code: aplicareCode,
                        aplicare_name: aplicareName,
                        aplicare_urutan: aplicareUrutan
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        $('#mappingModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseJSON.message || 'Terjadi kesalahan.');
                    }
                });
            });

            // Toggle mapping
            window.toggleMapping = function(roomId, activate) {
                const actionText = activate ? 'mengaktifkan' : 'menonaktifkan';
                const url = `{{ url('bpjs/aplicares/toggle-mapping') }}/${roomId}`;

                Swal.fire({
                    title: `Anda yakin ingin ${actionText} ruangan ini?`,
                    text: activate ? "Data ruangan akan dikirimkan ke server BPJS." :
                        "Ruangan tidak akan terkirim lagi ke BPJS.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Ya, ${actionText}!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                activate: activate
                            },
                            success: function(response) {
                                showSuccessAlert(response.message);
                                dtInternal.ajax.reload();
                                dtBpjs.ajax.reload();
                            },
                            error: function(xhr) {
                                showErrorAlertNoRefresh(xhr.responseJSON.message ||
                                    'Terjadi kesalahan.');
                            }
                        });
                    }
                });
            }

            // Update room
            window.updateRoom = function(id) {
                const url = `{{ url('bpjs/aplicares/update') }}/${id}`;
                Swal.fire({
                    title: 'Perbarui Ketersediaan?',
                    text: 'Data ketersediaan ruangan akan diperbarui di BPJS.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, perbarui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            success: function(response) {
                                showSuccessAlert(response.message);
                                dtInternal.ajax.reload();
                                dtBpjs.ajax.reload();
                            },
                            error: function(xhr) {
                                showErrorAlertNoRefresh(xhr.responseJSON.message ||
                                    'Terjadi kesalahan.');
                            }
                        });
                    }
                });
            }

            // Delete room
            window.deleteRoom = function(id) {
                const url = `{{ url('bpjs/aplicares/delete') }}/${id}`;
                Swal.fire({
                    title: 'Hapus Ruangan dari Aplicare?',
                    text: "Ruangan ini akan dihapus dari sistem BPJS.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            success: function(response) {
                                showSuccessAlert(response.message);
                                dtInternal.ajax.reload();
                                dtBpjs.ajax.reload();
                            },
                            error: function(xhr) {
                                showErrorAlertNoRefresh(xhr.responseJSON.message ||
                                    'Terjadi kesalahan.');
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection
