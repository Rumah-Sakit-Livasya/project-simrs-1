<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('inc.header')
    <style>
        body {
            background: #f3f3f3;
        }

        .page-content {
            padding: 1.5rem;
        }
    </style>
</head>

<body>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Ubah Penjamin Pasien
                            <span class="fw-300"><i>{{ $registration->patient->name ?? 'Nama Pasien' }}
                                    ({{ $registration->registration_number }})</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('error'))
                                <div class="alert alert-danger border-0">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger border-0">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('registration.ubah-penjamin.action', $registration->id) }}"
                                method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="current_penjamin">Penjamin Saat Ini</label>
                                    <input type="text" id="current_penjamin" class="form-control"
                                        value="{{ strtolower($registration->penjamin->nama_perusahaan ?? 'N/A') == strtolower($standarPenjaminNama ?? 'standar') ? 'UMUM' : $registration->penjamin->nama_perusahaan ?? 'N/A' }}"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="penjamin_id">Ubah Ke Penjamin</label>
                                    <select class="form-control select2" id="penjamin_id" name="penjamin_id" required
                                        data-standar-id="{{ $standarPenjaminId }}">
                                        <option value="">Pilih Penjamin Baru...</option>
                                        @foreach ($penjamins as $penjamin)
                                            <option value="{{ $penjamin->id }}"
                                                {{ old('penjamin_id', $registration->penjamin_id) == $penjamin->id ? 'selected' : '' }}>
                                                {{ strtolower($penjamin->nama_perusahaan) == strtolower($standarPenjaminNama ?? 'standar') ? 'UMUM' : $penjamin->nama_perusahaan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- DATA PENDUKUNG (DIAMBIL DARI PATIENT) --}}
                                <div id="data-pendukung-wrapper" style="display: none;">
                                    <hr>
                                    <h5 class="frame-heading">Data Pendukung Penjamin (dari Master Pasien)</h5>
                                    <div class="form-group">
                                        <label class="form-label" for="nama_perusahaan_pegawai">Perusahaan</label>
                                        <input class="form-control" type="text" id="nama_perusahaan_pegawai"
                                            name="nama_perusahaan_pegawai"
                                            value="{{ old('nama_perusahaan_pegawai', $registration->patient->nama_perusahaan_pegawai ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="nomor_kepegawian">No Pegawai</label>
                                        <input class="form-control" type="text" id="nomor_kepegawian"
                                            name="nomor_kepegawian"
                                            value="{{ old('nomor_kepegawian', $registration->patient->nomor_kepegawian ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="bagian_pegawai">Bagian</label>
                                        <input class="form-control" type="text" id="bagian_pegawai"
                                            name="bagian_pegawai"
                                            value="{{ old('bagian_pegawai', $registration->patient->bagian_pegawai ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="grup_perusahaan">Group</label>
                                        <input class="form-control" type="text" id="grup_perusahaan"
                                            name="grup_perusahaan"
                                            value="{{ old('grup_perusahaan', $registration->patient->grup_perusahaan ?? '') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="hubungan_pegawai">Hubungan</label>
                                        <input class="form-control" type="text" id="hubungan_pegawai"
                                            name="hubungan_pegawai"
                                            value="{{ old('hubungan_pegawai', $registration->patient->hubungan_pegawai ?? '') }}">
                                    </div>
                                </div>
                                {{-- END DATA PENDUKUNG --}}

                                <hr>
                                <h5 class="frame-heading">Verifikasi Perubahan</h5>
                                <div class="form-group">
                                    <label class="form-label" for="user_id">User Verifikator</label>
                                    <select class="form-control select2" id="user_id" name="user_id" required>
                                        <option value="">Pilih User...</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="alasan">Alasan Perubahan</label>
                                    <textarea name="alasan" id="alasan" rows="3" class="form-control" required>{{ old('alasan') }}</textarea>
                                </div>
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                    <button class="btn btn-primary ml-auto" type="submit">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('inc.script_footer')

    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi plugin Select2
            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true,
                dropdownParent: $('body')
            });

            // Simpan elemen-elemen penting ke dalam variabel
            const $penjaminSelect = $('#penjamin_id');
            const $dataPendukungWrapper = $('#data-pendukung-wrapper');

            // Ambil ID penjamin standar dari atribut data
            const standarId = $penjaminSelect.data('standar-id') ? $penjaminSelect.data('standar-id').toString() :
                null;

            /**
             * Fungsi ini akan menampilkan Data Pendukung HANYA JIKA
             * penjamin yang dipilih BUKAN penjamin standar.
             */
            function toggleDataPendukung() {
                const selectedId = $penjaminSelect.val();

                // Kondisi utama: Tampilkan jika ada yang dipilih DAN itu BUKAN standar.
                if (selectedId && selectedId !== standarId) {
                    $dataPendukungWrapper.slideDown();
                } else {
                    // Untuk kasus lainnya (belum dipilih ATAU yang dipilih adalah standar),
                    // sembunyikan.
                    $dataPendukungWrapper.slideUp();
                }
            }

            // Jalankan fungsi saat pilihan di dropdown berubah
            $penjaminSelect.on('change', toggleDataPendukung);

            // Jalankan fungsi saat halaman dimuat untuk mengatur state awal
            toggleDataPendukung();
        });
    </script>
</body>

</html>
