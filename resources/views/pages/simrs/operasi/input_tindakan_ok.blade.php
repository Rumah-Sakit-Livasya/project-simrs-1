@extends('inc.layout-no-side') {{-- Ganti dengan layout yang sesuai, misal 'inc.layout-no-side' jika ada --}}
@section('title', 'Input Tindakan Operasi')

@section('extended-css')
    {{-- CSS khusus untuk halaman ini --}}
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        .form-label-custom {
            color: #868e96;
            text-align: right;
            padding-right: 1.5rem;
        }

        .form-control-plaintext {
            padding-top: 0;
            padding-bottom: 0;
        }

        .frame-heading {
            padding: 0.5rem 0;
            margin-bottom: 1rem;
            border-bottom: 1px solid #e5e5e5;
            font-weight: 500;
            color: #333;
        }

        label.required:after {
            content: " *";
            color: red;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-form-tindakan" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class="fal fa-heart-medical mr-2"></i> Input <span class="fw-300"><i>Tindakan
                                    Operasi</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Fullscreen"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('ok.prosedur.store') }}" method="POST" id="form-tindakan">
                                @csrf
                                <input type="hidden" name="order_operasi_id" value="{{ $order->id }}">

                                {{-- SEKSI: Data Jadwal Operasi --}}
                                <h5 class="frame-heading">Data Jadwal Operasi</h5>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Jadwal Operasi</label>
                                    <div class="col-md-4">
                                        <input type="datetime-local" class="form-control" name="tgl_operasi"
                                            value="{{ \Carbon\Carbon::parse($order->tgl_operasi)->format('Y-m-d\TH:i') }}"
                                            required>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Ruang Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="ruangan_id" required>
                                            <option value="">Pilih Ruang Operasi</option>
                                            @if (is_iterable($ruangan_operasi))
                                                @foreach ($ruangan_operasi as $ruangan)
                                                    <option value="{{ $ruangan->id }}"
                                                        {{ $order->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                                        {{ $ruangan->nama_ruangan }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option disabled>Data ruang operasi tidak tersedia</option>
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Tipe Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="tipe_operasi_id" required>
                                            <option value="">Pilih Tipe Operasi</option>
                                            @foreach ($tipe_operasi as $tipe)
                                                <option value="{{ $tipe->id }}"
                                                    {{ $order->tipe_operasi_id == $tipe->id ? 'selected' : '' }}>
                                                    {{ $tipe->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Tipe
                                        Penggunaan</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="tipe_penggunaan" required>
                                            <option value="">Pilih Tipe Penggunaan</option>
                                            <option value="UMUM"
                                                {{ $order->registration->penjamin_id == 1 || str_contains(strtoupper($order->registration->penjamin->nama_perusahaan ?? ''), 'UMUM') ? 'selected' : '' }}>
                                                UMUM
                                            </option>
                                            <option value="ELEKTIF"
                                                {{ !($order->registration->penjamin_id == 1 || str_contains(strtoupper($order->registration->penjamin->nama_perusahaan ?? ''), 'UMUM')) ? 'selected' : '' }}>
                                                ELEKTIF
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Kelas</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="kelas_rawat_id" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach ($kelas_rawat as $kelas)
                                                <option value="{{ $kelas->id }}"
                                                    {{ $order->kelas_rawat_id == $kelas->id ? 'selected' : '' }}>
                                                    {{ $kelas->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- SEKSI: Tim Operasi --}}
                                <h5 class="frame-heading mt-4">Tim Operasi</h5>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Dokter
                                        Operator</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="dokter_operator_id" required>
                                            <option value="" disabled selected>Pilih Dokter Operator</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom">Ass. Dokter Operator</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="ass_dokter_operator_id">
                                            <option value="">Pilih Asisten</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom">Dokter Anestesi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="dokter_anastesi_id">
                                            <option value="">Pilih Dokter Anestesi</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Ass. Dokter
                                        Anestesi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="ass_dokter_anastesi_id" required>
                                            <option value="" disabled selected>Pilih Asisten Anestesi</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Row tambahan untuk Dokter Resusitator dan Dokter Tambahan --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom">Dokter Resusitator</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="dokter_resusitator_id">
                                            <option value="">Pilih Dokter Resusitator</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom">Dokter Tambahan</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="dokter_tambahan_id">
                                            <option value="">Pilih Dokter Tambahan</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- SEKSI: Tindakan Operasi --}}
                                <h5 class="frame-heading mt-4">Tindakan Operasi</h5>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Kategori
                                        Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="kategori_operasi_id"
                                            id="kategori_operasi_id" required>
                                            <option value="">Pilih Kategori Operasi</option>
                                            @foreach ($kategori_operasi as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ $order->kategori_operasi_id == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Tindakan
                                        Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="tindakan_id" id="tindakan_operasi_id"
                                            required>
                                            <option value="" disabled selected>Pilih Tindakan</option>
                                            @foreach ($tindakan_operasi as $tindakan)
                                                <option value="{{ $tindakan->id }}"
                                                    data-kategori="{{ $tindakan->kategori_operasi_id }}">
                                                    {{ $tindakan->nama_tindakan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="laporan_operasi" class="required">Laporan Operasi (Singkat)</label>
                                    <textarea class="form-control" name="laporan_operasi" rows="3" required
                                        placeholder="Jelaskan secara singkat prosedur yang dilakukan..."></textarea>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-4">
                                    <button class="btn btn-secondary ml-auto" type="button"
                                        onclick="window.close()">Tutup</button>
                                    <button class="btn btn-warning ml-2" type="submit" name="status" value="rencana">
                                        <i class="fal fa-save mr-1"></i> Save Draft
                                    </button>
                                    <button class="btn btn-primary ml-2" type="submit" name="status" value="selesai">
                                        <i class="fal fa-check mr-1"></i> Save Final
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
    {{-- Script untuk halaman ini --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true,
                // Jika halaman ini dibuka di pop-up, seringkali dropdownnya tersembunyi
                // di belakang panel. Gunakan dropdownParent untuk mengatasinya.
                // Cari parent terdekat yang sesuai, misal panel atau form itu sendiri.
                dropdownParent: $('#panel-form-tindakan')
            });

            // Filter tindakan operasi berdasarkan kategori operasi
            $('#kategori_operasi_id').on('change', function() {
                var kategoriId = $(this).val();
                var tindakanSelect = $('#tindakan_operasi_id');

                // Reset dan kosongkan dropdown tindakan
                tindakanSelect.val('').trigger('change');
                tindakanSelect.find('option:not(:first)').hide();

                if (kategoriId) {
                    // Tampilkan hanya tindakan yang sesuai dengan kategori
                    tindakanSelect.find('option[data-kategori="' + kategoriId + '"]').show();
                } else {
                    // Tampilkan semua tindakan jika tidak ada kategori dipilih
                    tindakanSelect.find('option').show();
                }

                // Refresh Select2
                tindakanSelect.select2({
                    placeholder: "Pilih Tindakan...",
                    allowClear: true,
                    dropdownParent: $('#panel-form-tindakan')
                });
            });

            // Trigger change saat halaman load untuk set initial state
            $('#kategori_operasi_id').trigger('change');

            // AJAX submit form
            $('#form-tindakan').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                var submitButton = $(document.activeElement);

                // Disable buttons untuk mencegah double-click
                $('button[type="submit"]').prop('disabled', true).addClass('disabled');

                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: $(form).serialize() + '&' + submitButton.attr('name') + '=' + submitButton
                        .val(),
                    success: function(response) {
                        if (response.success) {
                            // Cek jika halaman ini dibuka dari window lain (pop-up)
                            if (window.opener && !window.opener.closed) {
                                // Refresh halaman parent
                                window.opener.location.reload();
                            }
                            // Tutup jendela pop-up saat ini
                            window.close();
                        }
                    },
                    error: function(xhr) {
                        // Aktifkan kembali tombol jika error
                        $('button[type="submit"]').prop('disabled', false).removeClass(
                            'disabled');

                        var errors = xhr.responseJSON.errors;
                        var errorMessage = "Terjadi kesalahan:\n";
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorMessage += "- " + value[0] + "\n";
                            });
                        } else {
                            errorMessage = xhr.responseJSON.message ||
                                "Gagal menyimpan data. Periksa kembali isian Anda.";
                        }
                        // Tampilkan pesan error
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
