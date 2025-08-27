@extends('inc.layout-no-side') {{-- Sesuaikan dengan layout Anda --}}
@section('content')
    <form id="create-new-form" method="POST">
        @csrf
        {{-- Variabel $formTemplate di sini berisi string HTML mentah dari controller --}}
        {!! $formTemplate !!}
    </form>

    <div class="mt-3">
        <div class="card">
            <div class="card-body d-flex justify-content-end">
                <div>
                    <button type="button" class="btn btn-warning waves-effect waves-light save-form text-white"
                        data-status="0">
                        <i class="fas fa-save"></i> Simpan (Draft)
                    </button>
                    <button type="button" class="btn btn-success waves-effect waves-light save-form" data-status="1">
                        <i class="fas fa-check-circle"></i> Simpan (Final)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    {{-- 1. MUAT LIBRARY SIGNATURE PAD DARI INTERNET (CDN) --}}
    {{-- Ganti link lama Anda dengan yang ini --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script type="text/javascript">
        // FUNGSI GLOBAL UNTUK DIPANGGIL OLEH POPUP
        window.updateSignature = function(inputTargetId, previewTargetId, dataUrl) {
            const input = document.getElementById(inputTargetId);
            const preview = document.getElementById(previewTargetId);
            const placeholder = preview.nextElementSibling; // Asumsi placeholder adalah elemen setelah gambar

            if (input && preview) {
                input.value = dataUrl;
                preview.src = dataUrl;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }
        };
    </script>

    <script script type="text/javascript">
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto"
            });

            // Ubah menjadi datepicker
            function calculateEndDate() {
                const durasi = parseInt($('#durasi_istirahat').val());

                // Ambil tanggal mulai dari datepicker
                const tglMulaiDate = $('#tgl_mulai').datepicker('getDate');

                if (!isNaN(durasi) && durasi > 0 && tglMulaiDate) {
                    // Hitung tanggal selesai berdasarkan durasi
                    const tglSelesai = new Date(tglMulaiDate);
                    tglSelesai.setDate(tglSelesai.getDate() + durasi - 1);

                    // Set tanggal selesai ke datepicker
                    $('#tgl_selesai').datepicker('setDate', tglSelesai);
                }
            }

            // Event listener-nya tetap sama
            $(document).on('change keyup', '#durasi_istirahat, #tgl_mulai', calculateEndDate);

            $('body').on('click', '.open-signature-popup', function() {
                const inputTarget = $(this).data('input-target');
                const previewTarget = $(this).data('preview-target');
                const url =
                    `{{ route('utility.signature.pad') }}?inputTarget=${inputTarget}&previewTarget=${previewTarget}`;
                window.open(url, 'SignatureWindow',
                    `width=${window.screen.width},height=${window.screen.height},scrollbars=yes,resizable=yes,fullscreen=yes`
                );
            });

            const signaturePads = {};

            // Helper function untuk resize canvas
            // Pastikan ini didefinisikan di luar loop
            function resizeCanvas(canvas) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                // Set ukuran CSS secara eksplisit jika belum ada, ini penting!
                if (!canvas.style.width) {
                    canvas.style.width = '100%';
                }
                if (!canvas.style.height) {
                    canvas.style.height = '150px';
                }

                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                const ctx = canvas.getContext("2d");
                ctx.scale(ratio, ratio);
                // Setelah resize, data yang ada hilang, jadi perlu clear
                if (signaturePads[canvas.id]) {
                    signaturePads[canvas.id].clear();
                }
            }

            @if (isset($signaturePadInitializers) && !empty($signaturePadInitializers))
                const initializers = @json($signaturePadInitializers);

                initializers.forEach(function(init) {
                    const canvas = document.getElementById(init.canvasId);
                    const clearButton = document.getElementById(init.clearButtonId);
                    const hiddenInput = document.getElementById(init.hiddenInputId);

                    // Jika elemen canvas tidak ditemukan, hentikan dan beri log
                    if (!canvas) {
                        console.error('Elemen Canvas dengan ID "' + init.canvasId + '" tidak ditemukan!');
                        return; // Lanjut ke initializer berikutnya
                    }

                    // Buat instance signature pad
                    const signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)'
                    });

                    // Simpan instance untuk referensi, gunakan ID canvas sebagai key
                    signaturePads[canvas.id] = signaturePad;

                    // Saat selesai menggambar, simpan data base64
                    signaturePad.onEnd = function() {
                        if (!signaturePad.isEmpty()) {
                            hiddenInput.value = signaturePad.toDataURL('image/png');
                        } else {
                            hiddenInput.value = '';
                        }
                    };

                    // Fungsi tombol clear
                    clearButton.addEventListener('click', function() {
                        signaturePad.clear();
                        hiddenInput.value = '';
                    });
                });

                // Panggil resize untuk semua canvas SETELAH loop inisialisasi selesai
                // Ini memastikan semua elemen sudah ada di DOM dan terukur
                Object.keys(signaturePads).forEach(function(canvasId) {
                    const canvasElement = document.getElementById(canvasId);
                    resizeCanvas(canvasElement);
                });

                // Tambahkan event listener untuk resize window
                window.addEventListener("resize", function() {
                    Object.keys(signaturePads).forEach(function(canvasId) {
                        const canvasElement = document.getElementById(canvasId);
                        resizeCanvas(canvasElement);
                    });
                });
            @endif


            // Logika Simpan (TIDAK BERUBAH)
            $('.save-form').on('click', function(e) {
                e.preventDefault();

                let isFinal = $(this).data('status') == 1;
                const form = $('#create-new-form');
                const formValues = {};
                const formDataArray = form.serializeArray();

                $.each(formDataArray, function(i, field) {
                    if (field.name.endsWith('[]')) {
                        let cleanName = field.name.slice(0, -2);
                        if (!formValues[cleanName]) {
                            formValues[cleanName] = [];
                        }
                        formValues[cleanName].push(field.value);
                    } else if (field.name !== '_token') {
                        formValues[field.name] = field.value;
                    }
                });

                const payload = {
                    registration_id: '{{ $registrationId }}',
                    form_template_id: '{{ $formTemplateId }}',
                    form_values: formValues,
                    is_final: isFinal
                };

                $.ajax({
                    url: "{{ route('poliklinik.pengkajian-lanjutan.store') }}",
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify(payload),
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            if (window.opener) window.opener.location.reload();
                            window.close();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan.';
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
@endsection
