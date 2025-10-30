@extends('inc.layout-no-side') {{-- Sesuaikan dengan layout Anda --}}
@section('extended-css')
    <style>
        @media print {

            /* --- Pengaturan Dasar Halaman Cetak --- */
            body {
                background-color: #fff !important;
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                color: #000;
            }

            /* --- Sembunyikan Elemen yang Tidak Perlu --- */
            .card-header,
            .btn,
            .bi,
            .form-label.text-muted,
            .d-print-none {
                display: none !important;
            }

            /* --- Atur Ulang Tampilan Card Menjadi Dokumen Biasa --- */
            .card,
            .card-body {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* --- Tampilkan Elemen Khusus Cetak --- */
            .d-print-block {
                display: block !important;
            }

            .d-print-inline {
                display: inline !important;
            }

            /* --- Atur Ulang Tampilan Elemen Form Menjadi Teks Statis --- */
            .form-control-plaintext {
                padding-left: 0;
                padding-right: 0;
                border-bottom: 1px dotted #888;
                /* Garis bawah untuk data */
            }

            /* --- Penataan Layout Khusus Cetak --- */
            #printableArea {
                width: 100%;
            }

            /* Memastikan tidak ada page break di dalam konten yang tidak diinginkan */
            p,
            h4,
            h5 {
                orphans: 3;
                widows: 3;
            }

            h4,
            h5 {
                page-break-after: avoid;
            }

            /* --- Penataan Area Tanda Tangan --- */
            .row {
                /* Bootstrap grid tetap bekerja, tidak perlu banyak diubah */
            }

            .text-center {
                text-align: center !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
                {{ $pengkajian->form_template->name }}
                @if ($isEditMode)
                    <span class="badge bg-warning">Mode Edit</span>
                @else
                    <span class="badge bg-info">Mode Lihat</span>
                @endif
            </h4>
            <div>
                <small>Diisi oleh: {{ $pengkajian->creator->name ?? 'N/A' }} pada
                    {{ $pengkajian->created_at->format('d M Y, H:i') }}</small>
            </div>
        </div>
        <div class="card-body">
            {{-- Form hanya dibutuhkan dalam mode edit untuk submit --}}
            @if ($isEditMode)
                <form id="edit-form" method="POST"
                    action="{{ route('poliklinik.pengkajian-lanjutan.update', $pengkajian->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="registration_id" value="{{ $pengkajian->registration_id }}">
                    <input type="hidden" name="form_template_id" value="{{ $pengkajian->form_template_id }}">
                    {!! $processedFormHtml !!}
                </form>
            @else
                {{-- Tampilkan HTML yang sudah diproses sebagai read-only --}}
                {!! $processedFormHtml !!}
            @endif
        </div>
    </div>

    {{-- Tampilkan tombol hanya jika relevan --}}
    <div class="mt-3">
        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" onclick="window.close()">
                    <i class="fas fa-times"></i> Tutup
                </button>
                @if ($isEditMode)
                    <div>
                        <button type="button" class="btn btn-warning waves-effect waves-light save-form text-white"
                            data-status="0">
                            <i class="fas fa-save"></i> Simpan (Draft)
                        </button>
                        <button type="button" class="btn btn-success waves-effect waves-light save-form" data-status="1">
                            <i class="fas fa-check-circle"></i> Simpan (Final)
                        </button>
                    </div>
                @else
                    {{-- Tombol untuk Cetak atau Edit (jika belum final) --}}
                    <div>
                        <button type="button" class="btn btn-primary" onclick="window.print();">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        @if (!$pengkajian->is_final)
                            <a href="{{ route('poliklinik.pengkajian-lanjutan.edit', $pengkajian->id) }}"
                                class="btn btn-info">
                                <i class="fas fa-edit"></i> Edit Form
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    {{-- Plugin hanya dibutuhkan dalam mode edit --}}
    @if ($isEditMode)
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/painterro@1.2.78/build/painterro.min.js"></script>
        <script type="text/javascript">
            // FUNGSI GLOBAL UNTUK DIPANGGIL OLEH POPUP
            window.updateSignature = function(inputTargetId, previewTargetId, dataUrl) {
                const input = document.getElementById(inputTargetId);
                const preview = document.getElementById(previewTargetId);
                const placeholder = $(preview).siblings('.placeholder-text')[0];

                if (input && preview) {
                    input.value = dataUrl;
                    preview.src = dataUrl;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                }
            };

            $(document).ready(function() {
                // Event listener untuk membuka popup tanda tangan
                $('body').on('click', '.open-signature-popup', function() {
                    const inputTarget = $(this).data('input-target');
                    const previewTarget = $(this).data('preview-target');
                    const url =
                        `{{ route('utility.signature.pad') }}?inputTarget=${inputTarget}&previewTarget=${previewTarget}`;
                    window.open(url, 'SignatureWindow', 'width=600,height=400,scrollbars=no,resizable=no');
                });

                // ==============================
                // Inisialisasi Signature Pad
                // ==============================
                const signaturePads = {};
                @if (!empty($signaturePadInitializers))
                    const initializers = @json($signaturePadInitializers);
                    initializers.forEach(function(init) {
                        const canvas = document.getElementById(init.canvasId);
                        if (!canvas) return;

                        const signaturePad = new SignaturePad(canvas, {
                            backgroundColor: 'rgb(255, 255, 255)'
                        });
                        signaturePads[init.canvasId] = signaturePad;

                        // Muat data tanda tangan yang sudah ada jika ada
                        if (init.initialData) {
                            setTimeout(() => {
                                signaturePad.fromDataURL(init.initialData, {
                                    ratio: 1,
                                    width: canvas.offsetWidth,
                                    height: canvas.offsetHeight
                                });
                            }, 200);
                        }

                        const hiddenInput = document.getElementById(init.hiddenInputId);
                        signaturePad.onEnd = function() {
                            if (!signaturePad.isEmpty()) {
                                hiddenInput.value = signaturePad.toDataURL('image/png');
                            }
                        };

                        document.getElementById(init.clearButtonId).addEventListener('click', function() {
                            signaturePad.clear();
                            hiddenInput.value = '';
                        });
                    });
                @endif

                // ==============================
                // Logika Simpan (Update)
                // ==============================
                $('.save-form').on('click', function(e) {
                    e.preventDefault();
                    let isFinal = $(this).data('status') == 1;
                    const form = $('#edit-form');
                    const formActionUrl = form.attr('action'); // [FIX #1] Ambil URL di sini
                    const formValues = {};
                    const formDataArray = form.serializeArray();

                    // Kumpulkan semua data dari form
                    $.each(formDataArray, function(i, field) {
                        if (field.name.endsWith('[]')) {
                            let cleanName = field.name.slice(0, -2);
                            if (!formValues[cleanName]) {
                                formValues[cleanName] = [];
                            }
                            formValues[cleanName].push(field.value);
                        } else if (field.name !== '_token' && field.name !== '_method') {
                            formValues[field.name] = field.value;
                        }
                    });

                    // Konfirmasi jika akan difinalisasi
                    if (isFinal) {
                        Swal.fire({
                            title: 'Anda yakin?',
                            text: "Form yang sudah difinalisasi tidak dapat diubah lagi!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, finalisasi!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                sendUpdateRequest(formValues, isFinal,
                                    formActionUrl); // [FIX #1] Kirim URL sebagai argumen
                            }
                        });
                    } else {
                        sendUpdateRequest(formValues, isFinal,
                            formActionUrl); // [FIX #1] Kirim URL sebagai argumen
                    }
                });

                // Fungsi untuk mengirim data update via AJAX
                function sendUpdateRequest(formValues, isFinal, formActionUrl) {
                    // [FIX #2] Ekstrak ID yang dibutuhkan dari objek formValues
                    const registrationId = formValues.registration_id;
                    const formTemplateId = formValues.form_template_id;

                    // [FIX #2] Hapus ID dari objek formValues agar tidak terkirim ganda
                    delete formValues.registration_id;
                    delete formValues.form_template_id;

                    // [FIX #2] Susun payload dengan struktur yang benar untuk Controller
                    const payload = {
                        registration_id: registrationId,
                        form_template_id: formTemplateId,
                        form_values: formValues,
                        is_final: isFinal,
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT'
                    };

                    $.ajax({
                        url: formActionUrl, // Menggunakan URL yang sudah diteruskan
                        method: 'POST', // Method tetap POST karena kita override dengan _method di payload
                        contentType: 'application/json',
                        data: JSON.stringify(payload),
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                // Arahkan ke halaman lihat (show) setelah berhasil
                                window.location.href =
                                    "{{ route('poliklinik.pengkajian-lanjutan.show', $pengkajian->id) }}";
                            });
                        },
                        error: function(xhr) {
                            // Penanganan error yang lebih baik
                            if (xhr.status === 422) { // Error validasi
                                let errorHtml = '<ul class="text-start">';
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorHtml += '<li>' + value[0] + '</li>';
                                });
                                errorHtml += '</ul>';
                                Swal.fire('Error Validasi!', errorHtml, 'error');
                            } else { // Error lainnya
                                let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                                    'Terjadi kesalahan saat menyimpan data.';
                                Swal.fire('Error!', errorMessage, 'error');
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@endsection
