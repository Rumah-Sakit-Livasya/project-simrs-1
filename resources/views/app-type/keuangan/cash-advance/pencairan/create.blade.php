@extends('inc.layout')
@section('title', 'buat pencairan')
@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .badge-waiting {
            background-color: #f39c12;
            color: white;
        }

        .badge-approved {
            background-color: #00a65a;
            color: white;
        }

        .badge-rejected {
            background-color: #dd4b39;
            color: white;
        }

        .modal-lg {
            max-width: 800px;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        /* PENTING: Tambahkan CSS ini jika belum ada untuk memastikan toggle berfungsi */
        .child-row {
            display: none;
            /* Sembunyikan secara default */
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        /* Styling tambahan untuk memperjelas batas row */
        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        /* Pastikan table di dalam child row memiliki margin dan padding yang tepat */
        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        /* Pastikan parent dan child row terhubung secara visual */
        tr.parent-row.active {
            border-bottom: none !important;
        }

        /* Tambahkan di bagian style */
        .control-details {
            cursor: pointer;
            text-align: center;
            width: 30px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
            /* Warna biru */
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
            /* Warna merah saat terbuka */
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
            /* Warna biru lebih gelap saat hover */
        }

        /* Sembunyikan ikon sort bawaan DataTables */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        /* Styling untuk child row */
        /* Pastikan content di child row tidak overflow */
        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

        /* Styling untuk tabel di dalam child row */
        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            background-color: #021d39;
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12px;
            background-color: white;
        }

        /* Animasi untuk transisi smooth */
        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Warna berbeda untuk child row */
        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencairan Dana</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <form action="{{ route('keuangan.cash-advance.pencairan.store') }}" method="POST">
                            @csrf
                            <div class="panel-content">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_pencairan">Tanggal Pencairan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_pencairan"
                                                value="{{ old('tanggal_pencairan', date('Y-m-d')) }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    {{-- =============================================== --}}
                                    {{-- PERUBAHAN POSISI & FUNGSI DI SINI --}}
                                    {{-- =============================================== --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_pengaju">Nama Pengaju <span class="text-danger">*</span></label>
                                        {{-- Input group sekarang ada di field Nama Pengaju --}}
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nama_pengaju"
                                                name="nama_pengaju_text" placeholder="Klik ikon search untuk memilih..."
                                                value="{{ old('nama_pengaju_text') }}" required>
                                            <input type="hidden" id="pengajuan_id" name="pengajuan_id"
                                                value="{{ old('pengajuan_id') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text btn-search-popup"><i
                                                        class="fal fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Kode Pengajuan</label>
                                        {{-- Field Kode Pengajuan sekarang menjadi readonly biasa --}}
                                        <input type="text" class="form-control" id="kode_pengajuan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Jumlah Disetujui</label>
                                        <input type="text" class="form-control money-display" id="jumlah_pengajuan">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Telah Dicairkan</label>
                                        <input type="text" class="form-control money-display" id="telah_dicairkan">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Sisa Pencairan</label>
                                        <input type="text" class="form-control money-display" id="belum_dicairkan">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nominal">Nominal Dicairkan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control money-input" id="nominal" name="nominal"
                                            value="{{ old('nominal') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bank_id">Sumber Kas/Bank <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="bank_id" name="bank_id" required>
                                            <option value="">Pilih Kas/Bank</option>
                                            @foreach ($banks as $b)
                                                <option value="{{ $b->id }}"
                                                    {{ old('bank_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                <a href="{{ route('keuangan.cash-advance.pencairan') }}"
                                    class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success ml-auto">
                                    <i class="fal fa-save mr-1"></i> Simpan Pencairan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- ... script plugin Anda ... --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi plugin
            $('.select2').select2({
                placeholder: 'Pilih Opsi'
            });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.money-input').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                radixPoint: ',', // Tidak digunakan tapi baik untuk ada
                autoGroup: true,
                digits: 0,
                prefix: 'Rp ',
                rightAlign: false,
                removeMaskOnSubmit: true // PENTING: agar yang dikirim ke server adalah angka mentah
            });

            // Konfigurasi InputMask untuk display nominal yang readonly
            $('.money-display').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                radixPoint: ',',
                autoGroup: true,
                digits: 0,
                prefix: 'Rp ',
                rightAlign: false
            });

            // Event listener untuk membuka pop-up
            $('.btn-search-popup').on('click', function() {
                var url = "{{ route('keuangan.cash-advance.pencairan.dataPengajuanPopup') }}";
                window.open(url, 'PilihPengajuan', 'width=1000,height=600,scrollbars=yes');
            });

            // Fungsi ini akan dipanggil dari jendela pop-up
            window.selectPengajuan = function(id, kode, nama, disetujui, dicairkan, sisa) {
                function formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                }

                $('#pengajuan_id').val(id); // ID pengajuan (hidden)
                $('#nama_pengaju').val(nama); // Nama Pengaju (terlihat)
                $('#kode_pengajuan').val(kode); // Kode Pengajuan (terlihat, otomatis)
                $('#jumlah_pengajuan').val(formatRupiah(disetujui));
                $('#telah_dicairkan').val(formatRupiah(dicairkan));
                $('#belum_dicairkan').val(formatRupiah(sisa));
                $('#nominal').val(sisa); // Otomatis isi nominal dengan sisa
            }
        });
    </script>
@endsection
