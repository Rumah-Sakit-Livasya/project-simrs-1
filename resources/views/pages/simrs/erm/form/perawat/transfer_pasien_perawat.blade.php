@extends('pages.simrs.erm.index')

@section('erm')
    @php
        $data = $pengkajian ?? ($transfer ?? null);
    @endphp

    <div class="tab-content p-3">
        <div class="tab-pane fade show active" id="tab_transfer_pasien" role="tabpanel">
            <form id="transferPasienForm">
                @csrf
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr class="mt-4 mb-4" style="border-color: #868686;">

                <header class="text-primary text-center mb-4">
                    <h2 class="font-weight-bold">TRANSFER PASIEN ANTAR RUANGAN</h2>
                </header>

                {{-- CARD INFORMASI UMUM --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary-100">
                        <h4 class="card-title">Informasi Umum & Asal Pasien</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tanggal & Jam Transfer</label>
                                <div class="input-group">
                                    <input type="date" name="tgl" class="form-control"
                                        value="{{ old('tgl', optional($data)->tgl ?? date('Y-m-d')) }}">
                                    <input type="time" name="jam" class="form-control"
                                        value="{{ old('jam', isset($data->jam) ? \Carbon\Carbon::parse($data->jam)->format('H:i') : date('H:i')) }}">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Asal Ruangan & Kelas</label>
                                <div class="input-group">
                                    <input name="ruangan_asal" placeholder="Ruangan Asal" class="form-control"
                                        type="text" value="{{ old('ruangan_asal', $data->ruangan_asal ?? '') }}">
                                    <input name="kelas_asal" placeholder="Kelas Asal" class="form-control" type="text"
                                        value="{{ old('kelas_asal', $data->kelas_asal ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pindah ke Ruangan & Kelas</label>
                                <div class="input-group">
                                    <input name="ruangan_pindah" placeholder="Ruangan Pindah" class="form-control"
                                        type="text" value="{{ old('ruangan_pindah', $data->ruangan_pindah ?? '') }}">
                                    <input name="kelas_pindah" placeholder="Kelas Pindah" class="form-control"
                                        type="text" value="{{ old('kelas_pindah', $data->kelas_pindah ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Dokter yang Merawat</label>
                                <input name="dokter" class="form-control" type="text"
                                    value="{{ old('dokter', $data->dokter ?? ($registration->doctor->employee->fullname ?? '')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD SERAH TERIMA --}}
                <div class="card mb-4">
                    <div class="card-header bg-info-100">
                        <h4 class="card-title text-center">Serah Terima Pasien</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-5 text-center">@include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang Menyerahkan',
                                'name_prefix' => 'data_ttd1',
                                'pic' => auth()->user()->name,
                                'index' => 1,
                                'signature_model' => $data?->signature_pengirim,
                            ])</div>
                            <div class="col-md-5 text-center">@include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang Menerima',
                                'name_prefix' => 'data_ttd2',
                                'pic' => '',
                                'index' => 2,
                                'signature_model' => $data?->signature_penerima,
                            ])</div>
                        </div>
                    </div>
                </div>

                {{-- CARD PASIEN KEMBALI --}}
                <div class="card">
                    <div class="card-header bg-danger-100">
                        <h4 class="card-title text-center">Diisi untuk Pasien yang Kembali ke Ruang Semula</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group"><label>Catatan Pasien Kembali</label>
                            <textarea name="pasien_kelmbali" class="form-control" rows="2">{{ old('pasien_kelmbali', $data->pasien_kelmbali ?? '') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group"><label>Keadaan Umum</label><input type="text"
                                    name="keadaan_umum_after" class="form-control"
                                    value="{{ old('keadaan_umum_after', $data->keadaan_umum_after ?? '') }}"></div>
                            <div class="col-md-2 form-group"><label>TD (mmHg)</label><input type="text" name="td_after"
                                    class="form-control" value="{{ old('td_after', $data->td_after ?? '') }}"></div>
                            <div class="col-md-2 form-group"><label>ND (x/m)</label><input type="text" name="nd_after"
                                    class="form-control" value="{{ old('nd_after', $data->nd_after ?? '') }}"></div>
                            <div class="col-md-2 form-group"><label>RR (x/m)</label><input type="text" name="rr_after"
                                    class="form-control" value="{{ old('rr_after', $data->rr_after ?? '') }}"></div>
                            <div class="col-md-3 form-group"><label>Suhu (°C)</label><input type="text" name="sb_after"
                                    class="form-control" value="{{ old('sb_after', $data->sb_after ?? '') }}"></div>
                        </div>

                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-5 text-center">@include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang Menyerahkan',
                                'name_prefix' => 'data_ttd3',
                                'pic' => auth()->user()->name,
                                'index' => 3,
                                'signature_model' => $data?->signature_pengirim_balik,
                            ])</div>
                            <div class="col-md-5 text-center">@include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang Menerima',
                                'name_prefix' => 'data_ttd4',
                                'pic' => '',
                                'index' => 4,
                                'signature_model' => $data?->signature_penerima_balik,
                            ])</div>
                        </div>
                    </div>
                </div>

                <div
                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-5">
                    <button class="btn btn-primary ml-auto" type="submit" id="save-transfer-button">
                        <span class="mdi mdi-content-save mr-2"></span> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('plugin-erm')
    <script>
        // =====================================================================
        // INISIALISASI TANDA TANGAN (DENGAN PENYIMPANAN STATE EKSTERNAL)
        // =====================================================================
        (function() {
            const canvasManyElement = document.getElementById('canvas-many');
            console.log(canvasManyElement);

            if (!canvasManyElement || window.signaturePadManyInitialized) return;

            const ctxMany = canvasManyElement.getContext('2d', {
                willReadFrequently: true
            });
            let signatureState = {};
            let currentSession = {
                painting: false,
                history: [],
                hasDrawn: false,
                index: null
            };

            function startNewSession(index) {
                currentSession = {
                    painting: false,
                    history: [],
                    hasDrawn: false,
                    index: index
                };
                ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
            }

            function startPositionMany(e) {
                e.preventDefault();
                currentSession.painting = true;
                drawMany(e);
            }

            function endPositionMany(e) {
                e.preventDefault();
                if (!currentSession.painting) return;
                currentSession.painting = false;
                ctxMany.beginPath();
                currentSession.history.push(ctxMany.getImageData(0, 0, canvasManyElement.width, canvasManyElement
                    .height));
            }

            function drawMany(e) {
                if (!currentSession.painting) return;
                const rect = canvasManyElement.getBoundingClientRect();
                const x = (e.clientX || e.touches?.[0]?.clientX) - rect.left;
                const y = (e.clientY || e.touches?.[0]?.clientY) - rect.top;
                ctxMany.lineWidth = 3;
                ctxMany.lineCap = 'round';
                ctxMany.strokeStyle = '#000';
                ctxMany.lineTo(x, y);
                ctxMany.stroke();
                ctxMany.beginPath();
                ctxMany.moveTo(x, y);
                currentSession.hasDrawn = true;
            }

            function undoMany() {
                if (currentSession.history.length > 0) {
                    currentSession.history.pop();
                    if (currentSession.history.length > 0) {
                        ctxMany.putImageData(currentSession.history[currentSession.history.length - 1], 0, 0);
                    } else {
                        ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                        currentSession.hasDrawn = false;
                    }
                }
            }

            window.openSignaturePadMany = function(index) {
                startNewSession(index);
                $('#signatureModalMany').modal('show');
            }

            window.saveSignatureMany = function() {
                if (!currentSession.hasDrawn) {
                    alert("Silakan buat tanda tangan terlebih dahulu.");
                    return;
                }
                const dataURL = canvasManyElement.toDataURL('image/png');
                const currentIndex = currentSession.index;
                const preview = document.getElementById(`signature_preview_${currentIndex}`);
                const input = document.getElementById(`signature_image_${currentIndex}`);

                signatureState[currentIndex] = dataURL;
                if (preview) {
                    preview.src = dataURL;
                    preview.style.display = 'block';
                }
                if (input) {
                    input.value = dataURL;
                }

                console.log(`Signature for index ${currentIndex} saved to state.`);
                $('#signatureModalMany').modal('hide');
                const triggerButton = document.getElementById(`ttd_pegawai_${currentIndex}`);
                if (triggerButton) {
                    triggerButton.focus();
                }
            }

            window.syncSignatureStateToForm = function() {
                console.log('Syncing signature state to form inputs...');
                for (const index in signatureState) {
                    if (signatureState.hasOwnProperty(index)) {
                        const input = document.getElementById(`signature_image_${index}`);
                        if (input && signatureState[index]) {
                            input.value = signatureState[index];
                            console.log(`Input ${index} synced.`);
                        }
                    }
                }
            }

            $('#signatureModalMany .btn-outline-danger').on('click', function() {
                ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                currentSession.history = [];
                currentSession.hasDrawn = false;
            });
            $('#signatureModalMany .btn-outline-secondary').on('click', undoMany);
            $('#signatureModalMany .btn-success').on('click', window.saveSignatureMany);

            canvasManyElement.addEventListener('mousedown', startPositionMany);
            canvasManyElement.addEventListener('mouseup', endPositionMany);
            canvasManyElement.addEventListener('mousemove', drawMany);
            canvasManyElement.addEventListener('touchstart', startPositionMany, {
                passive: false
            });
            canvasManyElement.addEventListener('touchend', endPositionMany, {
                passive: false
            });
            canvasManyElement.addEventListener('touchmove', drawMany, {
                passive: false
            });

            window.signaturePadManyInitialized = true;
            console.log('Signature Pad Initialized with EXTERNAL state management.');
        })();

        // =====================================================================
        // AJAX FORM SUBMISSION (DENGAN SINKRONISASI)
        // =====================================================================
        $(document).ready(function() {
            $('#transferPasienForm').on('submit', function(e) {
                e.preventDefault();

                window.syncSignatureStateToForm();

                console.log('Form submission intercepted. Starting AJAX...');
                const form = $(this);
                const url = "{{ route('transfer-pasien-antar-ruangan.store') }}";
                const formData = new FormData(this);
                formData.append('registration_id', '{{ $registration->id }}');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#save-transfer-button').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                        );
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.reload();
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        var errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (response) {
                            errorMessage = response.error || Object.values(response.errors)
                                .join('\n');
                        }
                        alert('Error: \n' + errorMessage);
                    },
                    complete: function() {
                        $('#save-transfer-button').prop('disabled', false).html(
                            '<span class="mdi mdi-content-save mr-2"></span> Simpan Data');
                    }
                });
            });

            // ==========================================================
            // LOGIKA BARU UNTUK POPUP TANDA TANGAN
            // ==========================================================

            // Fungsi ini dipanggil dari window popup untuk mengupdate halaman utama
            window.updateSignature = function(targetInputId, targetPreviewId, dataURL) {
                // Cari elemen di halaman utama dan isi nilainya
                const inputField = document.getElementById(targetInputId);
                const previewImage = document.getElementById(targetPreviewId);

                if (inputField) {
                    inputField.value = dataURL;
                }
                if (previewImage) {
                    previewImage.src = dataURL;
                    previewImage.style.display = 'block';
                }
            };

            // Fungsi ini dipanggil oleh tombol "Tanda Tangan" untuk membuka popup
            window.openSignaturePopup = function(targetInputId, targetPreviewId) {
                const windowWidth = screen.availWidth;
                const windowHeight = screen.availHeight;
                const left = 0;
                const top = 0;

                // Bangun URL dengan query string untuk memberitahu popup elemen mana yang harus diupdate
                const url =
                    `{{ route('signature.pad') }}?targetInput=${targetInputId}&targetPreview=${targetPreviewId}`;

                // Buka popup window
                window.open(
                    url,
                    'SignatureWindow',
                    `width=${windowWidth},height=${windowHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
                );
            };

        });
    </script>
@endsection
