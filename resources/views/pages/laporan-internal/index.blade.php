@extends('inc.layout')
@section('title', 'Laporan Internal IT')

@section('extended-css')
    <style>
        /* Add to your extended-css section */
        .modal-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .timeline-inputs .input-group-text {
            min-width: 80px;
            justify-content: center;
        }

        .timeline-inputs .form-control {
            border-left: 0;
        }

        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            transition: all 0.5s ease;
        }

        .notification-toast.hide {
            transform: translateX(150%);
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            @can('filter laporan internal')
                <div class="row mb-3">
                    <div class="col-12">
                        <div id="panel-1" class="panel">
                            <div class="panel-hdr">
                                <h2>Filter Laporan</h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <form id="filter-form" method="POST" action="{{ route('laporan-internal.filter') }}">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-label mb-1 font-weight-normal"
                                                        for="datepicker-modal-2">Tanggal<i class="text-danger">*</i></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text fs-xl"><i
                                                                    class="fal fa-calendar"></i></span>
                                                        </div>
                                                        <input type="text" id="datepicker-modal-2"
                                                            class="form-control datepicker @error('tanggal') is-invalid @enderror"
                                                            placeholder="Select a date" name="tanggal">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="filter-jenis">Jenis</label>
                                                    <!-- Mengubah input menjadi select3 -->
                                                    <select class="select3 form-control @error('jenis') is-invalid @enderror"
                                                        name="jenis" id="filter-jenis">
                                                        <option value="">Pilih Jenis</option>
                                                        <!-- Placeholder option -->
                                                        <option value="kendala"
                                                            {{ (old('jenis') ?? request('jenis')) == 'kendala' ? 'selected' : '' }}>
                                                            Kendala</option>
                                                        <option value="kegiatan"
                                                            {{ (old('jenis') ?? request('jenis')) == 'kegiatan' ? 'selected' : '' }}>
                                                            Kegiatan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="user">User</label>
                                                    <select class="select3 form-control @error('user') is-invalid @enderror"
                                                        name="user[]" id="user" multiple>
                                                        @foreach ($umum as $employee)
                                                            <option value="{{ $employee->id }}">
                                                                {{ old('user', $employee->fullname) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <label for="filter-status">Status</label>
                                                    <!-- Mengubah input menjadi select3 -->
                                                    <select class="select3 form-control @error('status') is-invalid @enderror"
                                                        name="status" id="filter-status">
                                                        <option value="">Pilih Status</option>
                                                        <!-- Placeholder option -->
                                                        <option value="selesai"
                                                            {{ (old('status') ?? request('status')) == 'selesai' ? 'selected' : '' }}>
                                                            Selesai</option>
                                                        <option value="diproses"
                                                            {{ (old('status') ?? request('status')) == 'diproses' ? 'selected' : '' }}>
                                                            Diproses</option>
                                                        <option value="ditunda"
                                                            {{ (old('status') ?? request('status')) == 'ditunda' ? 'selected' : '' }}>
                                                            Ditunda</option>
                                                        <option value="ditolak"
                                                            {{ (old('status') ?? request('status')) == 'ditolak' ? 'selected' : '' }}>
                                                            Ditolak</option>
                                                    </select>

                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label class="form-label" for="">&nbsp</label>
                                                <button type="submit" class="btn btn-primary btn-block w-100">
                                                    <div class="ikon-tambah">
                                                        <span class="fal fa-search mr-1"></span>Cari
                                                    </div>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-2" class="panel">
                        <div class="panel-hdr">
                            <h2>Laporan Internal IT</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row mb-3">
                                    <div class="col">
                                        @can('create laporan internal')
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#tambah-data">
                                                <i class="fas fa-plus me-1"></i> Tambah Laporan
                                            </button>
                                        @endcan
                                        @can('export excel laporan internal')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#exportModal">
                                                <i class="fas fa-file-excel mr-2"></i>Download Harian
                                            </button>
                                        @endcan

                                        <!-- Tombol Export Word Harian -->
                                        @can('export word laporan internal')
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                                data-target="#exportWordModal">
                                                <i class="fas fa-file-word mr-2"></i> Download Word Harian
                                            </button>
                                        @endcan

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped w-100" id="laporanTable">
                                        <thead class="bg-primary-50">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Uraian</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                {{-- <th>Dokumentasi</th> --}}
                                                @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)')
                                                    <th>Jam Masuk</th>
                                                    <th>Jam Diproses</th>
                                                    <th>Respon Time</th>
                                                @endif
                                                <th>User</th>
                                                <th>Aksi</th>
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

        <!-- Tambah Laporan Modal -->
        <div class="modal fade" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Laporan Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-content">
                    <form id="form-laporan" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="organization_id"
                                value="{{ auth()->user()->employee->organization_id }}">

                            <div class="row mb-3">
                                <!-- Input Tanggal Laporan -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label mb-1 font-weight-normal" for="datepicker-modal-2">Tanggal
                                            Laproan<i class="text-danger">*</i></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text fs-xl"><i
                                                        class="fal fa-calendar"></i></span>
                                            </div>
                                            <input type="text" id="datepicker-modal-2"
                                                class="form-control datepicker @error('tanggal') is-invalid @enderror"
                                                placeholder="Select a date" name="tanggal">
                                        </div>
                                    </div>
                                </div>

                                <!-- Pilih Jenis Laporan -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis" class="font-weight-bold">Jenis Laporan</label>
                                        <select class="form-control select2" id="jenis" name="jenis" required>
                                            <option value="" selected disabled>Pilih Jenis Laporan</option>
                                            <option value="kegiatan">Kegiatan</option>
                                            <option value="kendala">Kendala</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Pilih Unit (Organisasi) -->
                                <div class="col-md-4" id="organization-field" style="display: none">
                                    <div class="form-group">
                                        <label for="unit_terkait" class="font-weight-bold">Unit</label>
                                        <select class="form-control select2" id="unit_terkait" name="unit_terkait">
                                            <option value="" selected disabled>Pilih Organisasi</option>
                                            @foreach (\App\Models\Organization::all() as $org)
                                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hanya untuk laporan kegiatan</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Uraian -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="kegiatan" class="font-weight-bold">Uraian Lengkap</label>
                                        <textarea class="form-control" id="kegiatan" name="kegiatan" rows="4" required
                                            placeholder="Deskripsikan laporan secara detail"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="font-weight-bold">Status</label>
                                        <select class="form-control select2" id="status" name="status" required>
                                            <option value="" disabled selected>Pilih Status</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="ditunda">Ditunda</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </div>
                                    <!-- Keterangan -->

                                    <div class="form-group" id="keterangan-field" style="display: none;">
                                        <label for="keterangan" class="font-weight-bold">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                                            placeholder="Deskripsikan keterangan status secara detail"></textarea>
                                    </div>
                                </div>

                                @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Timeline</label>
                                            <div class="timeline-inputs">
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light">Masuk</span>
                                                    </div>
                                                    <input type="time" class="form-control" name="jam_masuk">
                                                </div>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light">Diproses</span>
                                                    </div>
                                                    <input type="time" class="form-control" name="jam_diproses">
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light">Selesai</span>
                                                    </div>
                                                    <input type="time" class="form-control" name="jam_selesai">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    {{-- <div class="form-group mb-0">
                                        <label class="form-label">File (Browser)</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div> --}}

                                    <div class="form-group">
                                        <label for="dokumentasi" class="form-label">Dokumentasi (Opsional)</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="dokumentasi"
                                                name="dokumentasi" accept=".jpg,.jpeg,.png,.pdf">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                        <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for showing documentation -->
        <div class="modal fade" id="dokumentasiModal" tabindex="-1" role="dialog"
            aria-labelledby="dokumentasiModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dokumentasiModalLabel">Dokumentasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="noDocument" class="py-5" style="display: none;">
                            <i class="fas fa-file-excel fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada dokumentasi tersedia</p>
                        </div>
                        <div id="unsupportedFormat" class="py-5" style="display: none;">
                            <i class="fas fa-file-excel fa-3x text-danger mb-3"></i>
                            <p class="text-danger">Format file tidak didukung. Hanya JPG, PNG, dan PDF yang bisa
                                ditampilkan.</p>
                        </div>
                        <img id="dokumentasiImage" src="" class="img-fluid"
                            style="max-height: 70vh; display: none;" alt="Dokumentasi">
                        <div id="dokumentasiPdf" class="w-100" style="height: 70vh; display: none;">
                            <iframe id="pdfViewer" src=""
                                style="width: 100%; height: 100%; border: none;"></iframe>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <a id="downloadDokumentasi" href="#" class="btn btn-primary" download
                            style="display: none;">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Word Modal -->
        <div class="modal fade" id="exportWordModal" tabindex="-1" role="dialog"
            aria-labelledby="exportWordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportWordModalLabel">Download Laporan Word Harian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="exportWordForm" action="{{ route('laporan.internal.export.word') }}" method="GET">
                        <input type="hidden" name="organization_id"
                            value="{{ auth()->user()->employee->organization_id }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exportWordTanggal">Tanggal Laporan</label>
                                <input type="date" class="form-control" id="exportWordTanggal" name="tanggal"
                                    required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label for="exportWordJenis">Jenis Laporan</label>
                                <select class="form-control" id="exportWordJenis" name="jenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="kendala">Kendala</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="pic">PIC (Person In Charge) <i class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip"
                                        title="Orang yang bertanggungjawab atas target ini"></i></label>
                                <!-- Mengubah input menjadi select2 -->
                                <select class="select2 form-control @error('pic') is-invalid @enderror" name="pic[]"
                                    id="pic" multiple>
                                    @foreach ($employeeUnit as $employee)
                                        <option value="{{ $employee->id }}">{{ old('pic', $employee->fullname) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-download mr-2"></i>Download Word
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Download Laporan Harian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="exportForm" action="{{ route('laporan.internal.export.harian') }}" method="GET">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exportTanggal">Tanggal Laporan</label>
                                <input type="date" class="form-control" id="exportTanggal" name="tanggal" required
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label for="exportJenis">Jenis Laporan</label>
                                <select class="form-control" id="exportJenis" name="jenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="kendala">Kendala</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-download mr-2"></i>Download
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function showToast(message, icon = 'success') {
            Toast.fire({
                icon: icon,
                title: message
            });
        }

        $(document).ready(function() {
            // Initialize select2
            $('#form-laporan .select2').select2({
                dropdownParent: $('#tambah-data')
            });

            $(function() {
                $('.select3').select2();
                $('#pic').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#exportWordModal'),
                    allowClear: true
                });
            });

            // Show/hide organization field based on jenis selection
            $('#jenis').change(function() {
                if ($(this).val() === 'kendala') {
                    $('#organization-field').show();
                    $('#organization').prop('required', true);
                    $('input[name="unit_terkait"]').remove();
                } else {
                    $('#organization-field').hide();
                    $('#organization').prop('required', false);
                    if (!$('input[name="unit_terkait"]').length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'unit_terkait',
                            value: '{{ auth()->user()->employee->organization_id }}'
                        }).appendTo('form');
                    }
                }
            });

            // Show/hide keterangan field based on status selection
            $('#status').change(function() {
                if ($(this).val() === 'ditunda' || $(this).val() === 'ditolak') {
                    $('#keterangan-field').show();
                } else {
                    $('#keterangan-field').hide();
                }
            });

            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Set today's date as default
            $('.datepicker').datepicker('setDate', new Date());

            // Initialize DataTable with local language config
            var table = $('#laporanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/laporan-internal-list',
                    type: 'GET',
                    data: function(d) {
                        // Tambahkan data filter ke request
                        d.jenis = $('#jenis').val();
                        d.status = $('#status').val();
                        d.tanggal = $('#datepicker-modal-2').val();
                        d.organization = {{ auth()->user()->employee->organization->id }};
                    },
                    error: function(xhr) {
                        console.error('DataTables error:', xhr.responseText);
                        showToast('Gagal memuat data laporan', 'error');
                    }
                },
                columns: [{
                        data: null,
                        name: 'no',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
                        render: function(data) {
                            const badgeClass = data === 'kegiatan' ? 'bg-success' : 'bg-warning';
                            const displayText = data === 'kegiatan' ? 'Kegiatan' : 'Kendala';
                            return `<span class="badge ${badgeClass}">${displayText}</span>`;
                        }
                    },
                    {
                        data: 'kegiatan',
                        name: 'kegiatan'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let badgeClass = 'bg-secondary';
                            if (data == 'selesai') badgeClass = 'bg-success';
                            if (data == 'diproses') badgeClass = 'bg-primary';
                            if (data == 'baru') badgeClass = 'bg-info';
                            if (data == 'ditolak') badgeClass = 'bg-danger';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        render: function(data) {
                            return data ?? '-';
                        }
                    },
                    @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)')
                        {
                            data: 'jam_masuk',
                            name: 'jam_masuk',
                            render: function(data) {
                                if (!data) return '-';
                                try {
                                    // Jika format waktu sudah HH:MM:SS
                                    if (typeof data === 'string' && data.match(
                                            /^\d{2}:\d{2}:\d{2}$/)) {
                                        return data;
                                    }
                                    // Jika format timestamp
                                    return new Date(data).toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        hour12: false
                                    });
                                } catch (e) {
                                    return '<span class="text-warning">Invalid</span>';
                                }
                            }
                        }, {
                            data: 'jam_diproses',
                            name: 'jam_diproses',
                            render: function(data) {
                                if (!data) return '-';
                                try {
                                    if (typeof data === 'string' && data.match(
                                            /^\d{2}:\d{2}:\d{2}$/)) {
                                        return data;
                                    }
                                    return new Date(data).toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        hour12: false
                                    });
                                } catch (e) {
                                    return '<span class="text-warning">Invalid</span>';
                                }
                            }
                        }, {
                            data: null,
                            name: 'respon_time',
                            render: function(data, type, row) {
                                // Hitung waktu respon dari jam_masuk ke jam_diproses
                                if (row.jam_masuk && row.jam_diproses) {
                                    try {
                                        const start = this.parseTime(row.jam_masuk);
                                        const respon = this.parseTime(row.jam_diproses);

                                        if (respon < start) {
                                            return '<span class="text-danger">Invalid</span>';
                                        }

                                        const diff = Math.abs(respon - start);
                                        return this.formatDuration(diff);
                                    } catch (e) {
                                        console.error('Error calculating response time:', e);
                                        return '<span class="text-warning">Error</span>';
                                    }
                                }
                                return '-';
                            }
                        },
                    @endif {
                        data: 'fullname',
                        name: 'fullname',
                        render: function(data) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Tombol default (edit dan delete)
                            let buttons = `
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-icon btn-primary" onclick="editLaporan(${data})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @can('delete laporan internal')
                                                <button class="btn btn-sm btn-icon btn-danger" onclick="deleteLaporan(${data})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        `;

                            // Tambahkan tombol dokumentasi jika ada
                            if (row.dokumentasi && !isNumeric(row.dokumentasi)) {
                                buttons += `
                                            <button class="btn btn-sm btn-icon btn-info btn-show-dokumentasi" 
                                                    data-file="${row.dokumentasi.startsWith('http') ? row.dokumentasi : assetUrl(row.dokumentasi)}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        `;
                            }

                            // Tambahkan tombol checklist jika status Diproses atau Ditolak
                            if (row.status === 'diproses') {
                                buttons += `
                                                <button class="btn btn-sm btn-icon btn-success" onclick="completeLaporan(${data})" title="Tandai Selesai">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            `;
                            }

                            buttons += `</div>`;
                            return buttons;
                        }
                    }
                ],
                // Tambahkan fungsi helper di luar columns
                createdRow: function(row, data, dataIndex) {
                    // Helper functions untuk digunakan dalam render
                    $.fn.dataTable.render.formatDuration = function(diff) {
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        let durationText = '';
                        if (hours > 0) durationText += `${hours} jam `;
                        if (minutes > 0) durationText += `${minutes} menit `;
                        if (seconds > 0 || (hours === 0 && minutes === 0)) durationText +=
                            `${seconds} detik`;

                        return durationText.trim();
                    };

                    $.fn.dataTable.render.parseTime = function(timeStr) {
                        // Handle both timestamp and HH:MM:SS format
                        if (typeof timeStr === 'string' && timeStr.match(/^\d{2}:\d{2}:\d{2}$/)) {
                            const [hours, minutes, seconds] = timeStr.split(':');
                            const date = new Date();
                            date.setHours(hours, minutes, seconds);
                            return date;
                        }
                        return new Date(timeStr);
                    };
                },
                language: {
                    "decimal": "",
                    "emptyTable": "Tidak ada data yang tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                responsive: true
            });

            // Helper function to check if value is numeric
            function isNumeric(n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            }

            // Helper function to get asset URL
            function assetUrl(path) {
                return path.startsWith('/') ? path : '/' + path;
            }

            $('#filter-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah pengiriman form default

                // Reload DataTable dengan filter
                table.ajax
                    .reload(); // Ini akan memanggil URL yang ditentukan di ajax.url dan mengirimkan data filter
            });


            // Form submission with file upload support
            $('#form-laporan').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                // Create FormData object to handle file upload
                const formData = new FormData(this);

                // Get CSRF token from meta tag
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/laporan-internal',
                    method: 'POST',
                    data: formData,
                    processData: false, // Important for file upload
                    contentType: false, // Important for file upload
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        showToast(response.message);
                        $('#form-laporan')[0].reset();
                        $('#tambah-data').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            'Gagal menyimpan data';
                        showToast(errorMessage, 'error');

                        // If there are validation errors, display them
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                const errorMessage = errors[field][0];
                                $(`#${field}-error`).text(errorMessage).show();
                            }
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Laporan');
                    }
                });
            });

            // Reset form when modal is closed
            $('#tambah-data').on('hidden.bs.modal', function() {
                $('#form-laporan')[0].reset();
                $('.select2').val(null).trigger('change');
                $('.datepicker').datepicker('setDate', new Date());
                $('#organization-field').hide();
            });
        });

        // Show Dokumentasi
        $(document).on('click', '.btn-show-dokumentasi', function() {
            const rawFileUrl = $(this).data('file');

            // Reset modal state
            $('#noDocument').hide();
            $('#dokumentasiImage').hide().attr('src', '');
            $('#dokumentasiPdf').hide();
            $('#pdfViewer').attr('src', '');
            $('#downloadDokumentasi').hide().attr('href', '#');
            $('#unsupportedFormat').hide();
            $('#invalidUrl').hide();

            // Validasi dasar URL
            if (!rawFileUrl || typeof rawFileUrl !== 'string' || rawFileUrl.trim() === '') {
                $('#noDocument').show();
                $('#dokumentasiModal').modal('show');
                return;
            }

            try {
                let fileUrl;
                let extension;

                // Coba parse sebagai URL lengkap
                try {
                    const url = new URL(rawFileUrl);
                    fileUrl = url.toString();
                    const pathname = url.pathname;
                    extension = pathname.split('.').pop().toLowerCase().split(/[#?]/)[0];
                }
                // Jika gagal, anggap sebagai path relatif
                catch (e) {
                    // Handle relative paths
                    if (rawFileUrl.startsWith('storage/') || rawFileUrl.startsWith('/storage/')) {
                        fileUrl = assetUrl(rawFileUrl);
                    } else {
                        fileUrl = assetUrl('storage/' + rawFileUrl);
                    }

                    // Extract extension from relative path
                    const pathParts = rawFileUrl.split('.');
                    extension = pathParts.length > 1 ? pathParts.pop().toLowerCase().split(/[#?]/)[0] : '';
                }

                // Validasi ekstensi file
                const supportedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                if (!supportedExtensions.includes(extension)) {
                    $('#unsupportedFormat').show();
                    $('#dokumentasiModal').modal('show');
                    return;
                }

                // Tampilkan viewer sesuai tipe file
                if (extension === 'pdf') {
                    $('#pdfViewer').attr('src', fileUrl);
                    $('#dokumentasiPdf').show();
                } else {
                    $('#dokumentasiImage').attr('src', fileUrl).show();
                }

                $('#downloadDokumentasi').attr('href', fileUrl).show();
                $('#dokumentasiModal').modal('show');
            } catch (e) {
                console.error('Error showing documentation:', e);
                $('#invalidUrl').show();
                $('#dokumentasiModal').modal('show');
            }
        });

        // Export Word form submission
        $('#exportWordForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action') + '?' + form.serialize();

            window.open(url, '_blank');
            $('#exportWordModal').modal('hide');
        });


        // Export form submission
        $('#exportForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action') + '?' + form.serialize();

            // Open download in new tab
            window.open(url, '_blank');

            // Close modal
            $('#exportModal').modal('hide');
        });

        // Helper function untuk path relatif
        function assetUrl(path) {
            // Hapus slash di awal jika ada
            const cleanPath = path.startsWith('/') ? path.substring(1) : path;
            return window.location.origin + '/' + cleanPath;
        }

        function editLaporan(id) {
            Swal.fire({
                title: 'Edit Laporan',
                text: 'Fitur edit akan segera tersedia!',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }

        function completeLaporan(id) {
            Swal.fire({
                title: 'Ubah Status Laporan?',
                text: "Laporan akan di ubah statusnya menjadi selesai!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/laporan-internal/complete/' + id,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: 'Selesai'
                        },
                        success: function() {
                            showToast('Data laporan berhasil dihapus');
                            $('#laporanTable').DataTable().ajax.reload();
                        },
                        error: function() {
                            showToast('Gagal menghapus data laporan', 'error');
                        }
                    });
                }
            });
        }

        function deleteLaporan(id) {
            Swal.fire({
                title: 'Hapus Laporan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/laporan-internal/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            showToast('Data laporan berhasil dihapus');
                            $('#laporanTable').DataTable().ajax.reload();
                        },
                        error: function() {
                            showToast('Gagal menghapus data laporan', 'error');
                        }
                    });
                }
            });
        }

        // Definisikan fungsi helper di luar DataTables
        function parseTime(timeStr) {
            // Handle both timestamp and HH:MM:SS format
            if (typeof timeStr === 'string' && timeStr.match(/^\d{2}:\d{2}:\d{2}$/)) {
                const [hours, minutes, seconds] = timeStr.split(':');
                const date = new Date();
                date.setHours(hours, minutes, seconds);
                return date;
            }
            return new Date(timeStr);
        }

        function formatDuration(diff) {
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            let durationText = '';
            if (hours > 0) durationText += `${hours} jam `;
            if (minutes > 0) durationText += `${minutes} menit `;
            if (seconds > 0 || (hours === 0 && minutes === 0)) durationText += `${seconds} detik`;

            return durationText.trim();
        }
    </script>
@endsection
