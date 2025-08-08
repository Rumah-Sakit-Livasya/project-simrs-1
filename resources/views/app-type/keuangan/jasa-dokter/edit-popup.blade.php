{{-- TIDAK ADA @extends DI SINI --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit AP Dokter - {{ config('app.name', 'SIMRS') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="msapplication-tap-highlight" content="no">

    <!-- BASE CSS - SmartAdmin (atau framework Anda) -->
    {{-- Pilih salah satu: vendors.bundle.css biasanya sudah termasuk Bootstrap --}}
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/vendors.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/app.bundle.css') }}"> {{-- Tema utama SmartAdmin --}}

    <!-- PLUGINS CSS -->
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/formplugins/select2/select2.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/notifications/toastr/toastr.css') }}">

    <!-- FAVICONS (Contoh dari SmartAdmin) -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="mask-icon" href="{{ asset('img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">

    <style>
        /* Styling Global untuk Popup Mandiri */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-size: 80%;
            /* Reset jika ada style aneh */
        }

        body {
            background-color: #f3f4f6;
            /* Latar belakang lembut untuk area di luar panel */
            padding: 20px;
            /* Memberi ruang di sekeliling panel */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            /* Font default browser yang bersih */
            font-size: 13px;
            /* Ukuran font dasar yang umum untuk form */
            line-height: 1.5;
            color: #374151;
            /* Warna teks utama */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* Panel mulai dari atas, sisakan ruang untuk shadow */
            box-sizing: border-box;
            overflow-y: auto;
            /* Scroll jika konten lebih panjang dari window */
        }

        /* Wrapper untuk panel agar bisa diatur max-width dan margin */
        .popup-panel-wrapper {
            width: 100%;

            /* LEBAR MAKSIMUM PANEL, SESUAIKAN */
        }

        /* Menggunakan kelas .panel standar SmartAdmin (jika vendors.bundle.css di-load) */
        /* Jika tidak, kita buat styling panel minimal */
        .panel {
            background-color: #fff;
            border: 1px solid #e5e7eb;
            /* Border lebih lembut */
            border-radius: 0.375rem;
            /* Rounded corners */
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 0 !important;
            /* Override jika ada */
        }

        .panel-hdr {
            background-color: #fff;
            /* Header putih */
            color: #374151;
            /* Teks header gelap */
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-hdr h2 {
            font-size: 15px;
            /* Ukuran font header */
            font-weight: 500;
            margin: 0;
        }

        .panel-hdr h2 small {
            font-size: 0.85em;
            color: #6b7280;
            margin-left: 6px;
            font-weight: 400;
        }

        .panel-container .panel-content {
            padding: 16px;
            /* Padding konten */
        }

        /* Form Elements - Layout Dua Kolom */
        .form-group-grid {
            display: grid;
            grid-template-columns: 130px 1fr;
            /* Label : Nilai/Input (Sesuaikan lebar label) */
            align-items: center;
            /* Vertically align items */
            gap: 0 12px;
            /* Jarak antar kolom */
            margin-bottom: 10px;
            /* Jarak antar baris */
        }

        .form-label-grid {
            font-weight: 500;
            color: #374151;
            font-size: 13px;
            text-align: left;
            padding-right: 5px;
            /* Jarak dari label ke titik dua (jika ada) */
        }

        /* Nilai Readonly dengan Garis Bawah */
        .form-value-grid .form-control-plaintext-underline {
            display: block;
            /* Atau flex jika ada ikon di dalamnya */
            width: 100%;
            padding: 5px 0;
            /* Padding atas-bawah */
            font-size: 13px;
            color: #1f2937;
            /* Warna teks nilai */
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #d1d5db;
            /* Garis bawah */
            line-height: 1.4;
            min-height: 32px;
            /* Tinggi minimal agar sejajar dengan Select2 */
            display: flex;
            align-items: center;
        }

        .form-value-grid .form-control-plaintext-underline small.text-muted {
            display: block;
            font-size: 11px;
            color: #6b7280 !important;
            margin-top: 3px;
        }

        /* Select2 Styling agar konsisten */
        .form-value-grid .select2-container--bootstrap4 .select2-selection--single {
            height: 34px !important;
            /* Tinggi Select2 disamakan */
            border: 1px solid #d1d5db !important;
            /* Border Select2 */
            font-size: 13px !important;
            border-radius: 0.25rem !important;
        }

        .form-value-grid .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            /* Vertically center text */
            padding-left: 10px !important;
            color: #374151 !important;
        }

        .form-value-grid .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 32px !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            z-index: 10060 !important;
            /* Di atas elemen lain */
        }


        /* Tombol Aksi */
        .action-buttons {
            margin-top: 18px !important;
            padding-top: 12px !important;
            border-top: 1px solid #e5e7eb;
            display: flex !important;
            justify-content: flex-start !important;
            /* Tombol di kiri */
            gap: 8px !important;
        }

        .action-buttons .btn {
            font-size: 13px !important;
            padding: 6px 12px !important;
            border-radius: 0.25rem !important;
            font-weight: 500;
        }

        .btn-primary {
            /* Tombol simpan biru */
            background-color: #2563eb !important;
            /* Warna biru yang lebih modern */
            border-color: #2563eb !important;
            color: white !important;
        }

        .btn-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1e40af !important;
        }

        .btn-secondary {
            background-color: #6b7280 !important;
            border-color: #6b7280 !important;
            color: white !important;
        }

        .btn-secondary:hover {
            background-color: #4b5563 !important;
            border-color: #4b5563 !important;
        }

        /* Alert dan Pesan Error Validasi */
        .alert {
            font-size: 13px !important;
            padding: 10px 15px !important;
            margin-bottom: 12px !important;
            border-radius: 0.25rem !important;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 16px;
        }

        .invalid-feedback {
            font-size: 12px !important;
            color: #dc3545 !important;
            /* Warna error standar Bootstrap */
            display: block !important;
            margin-top: 3px !important;
        }

        .dokter-asli {
            font-size: 11px !important;
            color: #6b7280 !important;
            margin-left: 5px !important;
        }
    </style>
</head>

<body class="mod-bg-1"> {{-- Kelas SmartAdmin jika `app.bundle.css` memanfaatkannya, jika tidak, bisa dihapus --}}
    <div class="popup-panel-wrapper">
        <div id="panel-edit-ap" class="panel">
            <div class="panel-hdr">
                <h2>
                    Edit Tindakan AP
                    @if ($jasaDokter->ap_number)
                        <small>(No. AP: {{ $jasaDokter->ap_number }})</small>
                    @endif
                </h2>
            </div>
            <div class="panel-container show"> {{-- Kelas dari SmartAdmin untuk struktur panel --}}
                <div class="panel-content">
                    {{-- Pesan Alert --}}
                    @if (session('success_popup'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_popup') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        </div>
                    @endif
                    @if (session('error_popup'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error_popup') }}
                            @if ($errors->any() && !session('success_popup'))
                                <ul class="mt-2 mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        </div>
                    @elseif ($errors->any() && !session('error_popup') && !session('success_popup'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validasi Gagal:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        </div>
                    @endif

                    {{-- FORM --}}
                    <form action="{{ route('update-popup', ['jasaDokter' => $jasaDokter->id]) }}" method="POST"
                        id="editApDokterFormInPopup">
                        @csrf
                        @method('PUT')

                        <div class="form-group-grid">
                            <label class="form-label-grid" for="dokter_id_ap_popup_standalone">Dokter <span
                                    class="text-danger">*</span></label>
                            <div class="form-value-grid">
                                <select class="form-control select2 @error('dokter_id_ap') is-invalid @enderror"
                                    id="dokter_id_ap_popup_standalone" name="dokter_id_ap" required
                                    data-placeholder="Pilih Dokter...">
                                    <option value=""></option>
                                    @foreach ($allDoctors as $doc)
                                        <option value="{{ $doc['id'] }}"
                                            {{ old('dokter_id_ap', $jasaDokter->dokter_id) == $doc['id'] ? 'selected' : '' }}>
                                            {{ $doc['fullname'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dokter_id_ap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group-grid">
                            <label class="form-label-grid">Tagihan</label>
                            <div class="form-value-grid">
                                <div class="form-control-plaintext-underline"
                                    title="{{ $jasaDokter->nama_tindakan ?? ($jasaDokter->tagihanPasien?->tindakan_medis?->nama_tindakan ?? ($jasaDokter->tagihanPasien?->tagihan ?? '-')) }} @if ($jasaDokter->tagihanPasien?->registration?->doctor) (Dokter Asli: {{ $jasaDokter->tagihanPasien->registration->doctor->employee?->fullname ?? ($jasaDokter->tagihanPasien->registration->doctor->nama_dokter ?? 'N/A') }}) @endif">
                                    {{ $jasaDokter->nama_tindakan ?? ($jasaDokter->tagihanPasien?->tindakan_medis?->nama_tindakan ?? ($jasaDokter->tagihanPasien?->tagihan ?? '-')) }}

                                    @if ($jasaDokter->tagihanPasien?->registration?->doctor)
                                        <small class="text-muted dokter-asli">(
                                            {{ $jasaDokter->tagihanPasien->registration->doctor->employee?->fullname ?? ($jasaDokter->tagihanPasien->registration->doctor->nama_dokter ?? 'N/A') }})</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group-grid">
                            <label class="form-label-grid">Nominal</label>
                            <div class="form-value-grid">
                                <div class="form-control-plaintext-underline">Rp
                                    {{ number_format($jasaDokter->nominal ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="form-group-grid">
                            <label class="form-label-grid">Diskon</label>
                            <div class="form-value-grid">
                                <div class="form-control-plaintext-underline">Rp
                                    {{ number_format($jasaDokter->diskon ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="form-group-grid">
                            <label class="form-label-grid">Share Dokter</label>
                            <div class="form-value-grid">
                                <div class="form-control-plaintext-underline">Rp
                                    {{ number_format($jasaDokter->share_dokter ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">
                                <i class="fal fa-save mr-1"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-themed"
                                onclick="window.close();">
                                <i class="fal fa-times mr-1"></i> Tutup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
    <script src="{{ asset('js/formplugins/select2/select2.bundle.js') }}"></script>
    <script src="{{ asset('js/notifications/toastr/toastr.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#dokter_id_ap_popup_standalone').select2({
                placeholder: "Pilih Dokter...",
                allowClear: true,
                theme: 'bootstrap4', // Pastikan tema ini didukung oleh CSS Anda
                width: '100%',
                // dropdownParent: $('#panel-edit-ap .panel-content') // Aktifkan jika dropdown terpotong
            });

            // Konfigurasi Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "4000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Logika Toastr untuk pesan session (sukses/error) dan validasi
            @if (session('success_popup'))
                toastr.success("{{ session('success_popup') }}");
                @if (session('close_popup_and_refresh_opener'))
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                    setTimeout(function() {
                        window.close();
                    }, 1500);
                @endif
            @endif
            @if (session('error_popup'))
                toastr.error("{{ session('error_popup') }}");
            @endif
            @if ($errors->any() && !session('error_popup') && !session('success_popup'))
                var errorMessages = "<ul>";
                @foreach ($errors->all() as $error)
                    errorMessages += "<li>{{ $error }}</li>";
                @endforeach
                errorMessages += "</ul>";
                toastr.error(errorMessages, "Kesalahan Validasi", {
                    timeOut: 0,
                    extendedTimeOut: 0,
                    closeButton: true,
                    tapToDismiss: false
                });
            @endif
        });
    </script>
</body>

</html>
