@extends('inc.layout-no-side')
@section('title', 'Input Tindakan Operasi')

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
                            {{-- Patient Information Header --}}


                            <form action="{{ route('ok.prosedur.store') }}" method="POST" id="form-tindakan">
                                @csrf
                                <input type="hidden" name="order_operasi_id" value="{{ $order->id }}">

                                {{-- SEKSI: Data Jadwal Operasi --}}
                                <h5 class="frame-heading">Data Jadwal Operasi</h5>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Jadwal Operasi</label>
                                    <div class="col-md-4">
                                        <input type="datetime-local" class="form-control" name="tgl_operasi" required
                                            value="{{ \Carbon\Carbon::parse($order->tgl_operasi)->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Ruang Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="ruangan_id" required>
                                            <option value="">Pilih Ruang Operasi</option>
                                            @foreach ($ruangan_operasi as $ruangan)
                                                <option value="{{ $ruangan->id }}"
                                                    {{ $order->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                                    {{ $ruangan->nama_ruangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Kategori
                                        Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" id="kategori_operasi_id"
                                            name="kategori_operasi_id" required>
                                            <option value="">Pilih Kategori Operasi</option>
                                            @foreach ($kategori_operasi as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ $order->kategori_operasi_id == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Tipe Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="tipe_operasi_id" required>
                                            <option value="">Pilih Tipe Operasi</option>
                                            @foreach ($tipe_operasi as $tipe)
                                                <option value="{{ $tipe->id }}"
                                                    {{ $order->tipe_operasi_id == $tipe->id ? 'selected' : '' }}>
                                                    {{ $tipe->tipe }}
                                                </option>
                                            @endforeach
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
                                    <label class="col-md-2 col-form-label form-label-custom">Tipe Penggunaan</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="tipe_penggunaan" required>
                                            <option value="UMUM"
                                                {{ old('tipe_penggunaan', 'UMUM') == 'UMUM' ? 'selected' : '' }}>UMUM
                                            </option>
                                            <option value="ELEKTIF"
                                                {{ old('tipe_penggunaan') == 'ELEKTIF' ? 'selected' : '' }}>ELEKTIF
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                {{-- SEKSI: Tim Operasi --}}
                                <h5 class="frame-heading mt-4">Tim Operasi</h5>

                                {{-- Dokter Operator --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Dokter
                                        Operator</label>
                                    <div class="col-md-10">
                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2 dokter-operator" name="dokter_operator_id"
                                                required>
                                                <option value="">Pilih Dokter Operator</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->dokter_operator_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">Pilih dokter utama yang akan melakukan
                                            operasi</small>
                                    </div>
                                </div>

                                {{-- Asisten Dokter Operator --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom">Ass. Dokter Operator</label>
                                    <div class="col-md-10">
                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2" name="ass_dokter_operator_1_id">
                                                <option value="">Pilih Asisten Operator 1</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->ass_dokter_operator_1_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2" name="ass_dokter_operator_2_id">
                                                <option value="">Pilih Asisten Operator 2</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->ass_dokter_operator_2_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="doctor-select-container">
                                            <select class="form-control select2" name="ass_dokter_operator_3_id">
                                                <option value="">Pilih Asisten Operator 3</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->ass_dokter_operator_3_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">Opsional - Asisten dokter operator (maksimal
                                            3)</small>
                                    </div>
                                </div>

                                {{-- Dokter Anestesi dan Resusitator --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom">Dokter Anestesi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="dokter_anastesi_id">
                                            <option value="">Pilih Dokter Anestesi</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor['id'] }}"
                                                    {{ optional($order->prosedur)->dokter_anastesi_id == $doctor['id'] ? 'selected' : '' }}
                                                    data-kode="{{ $doctor['kode_dpjp'] }}"
                                                    data-departement="{{ $doctor['departement'] }}">
                                                    {{ $doctor['fullname'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Opsional - Dokter anestesi</small>
                                    </div>

                                    <label class="col-md-2 col-form-label form-label-custom">Ass. Dokter Anestesi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="ass_dokter_anastesi_id">
                                            <option value="">Pilih Asisten Anestesi</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor['id'] }}"
                                                    {{ optional($order->prosedur)->ass_dokter_anastesi_id == $doctor['id'] ? 'selected' : '' }}
                                                    data-kode="{{ $doctor['kode_dpjp'] }}"
                                                    data-departement="{{ $doctor['departement'] }}">
                                                    {{ $doctor['fullname'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Opsional - Asisten dokter anestesi</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom">Dokter Resusitator</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" name="dokter_resusitator_id">
                                            <option value="">Pilih Dokter Resusitator</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor['id'] }}"
                                                    {{ optional($order->prosedur)->dokter_resusitator_id == $doctor['id'] ? 'selected' : '' }}
                                                    data-kode="{{ $doctor['kode_dpjp'] }}"
                                                    data-departement="{{ $doctor['departement'] }}">
                                                    {{ $doctor['fullname'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Opsional - Dokter resusitator</small>
                                    </div>
                                </div>

                                {{-- Dokter Tambahan --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom">Dokter Tambahan</label>
                                    <div class="col-md-10">
                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2" name="dokter_tambahan_1_id">
                                                <option value="">Pilih Dokter Tambahan 1</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->dokter_tambahan_1_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2" name="dokter_tambahan_2_id">
                                                <option value="">Pilih Dokter Tambahan 2</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->dokter_tambahan_2_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2" name="dokter_tambahan_3_id">
                                                <option value="">Pilih Dokter Tambahan 3</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->dokter_tambahan_3_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="doctor-select-container mb-2">
                                            <select class="form-control select2" name="dokter_tambahan_4_id">
                                                <option value="">Pilih Dokter Tambahan 4</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->dokter_tambahan_4_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="doctor-select-container">
                                            <select class="form-control select2" name="dokter_tambahan_5_id">
                                                <option value="">Pilih Dokter Tambahan 5</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor['id'] }}"
                                                        {{ optional($order->prosedur)->dokter_tambahan_5_id == $doctor['id'] ? 'selected' : '' }}
                                                        data-kode="{{ $doctor['kode_dpjp'] }}"
                                                        data-departement="{{ $doctor['departement'] }}">
                                                        {{ $doctor['fullname'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">Opsional - Dokter tambahan (maksimal 5)</small>
                                    </div>
                                </div>

                                {{-- SEKSI: Tindakan Operasi --}}
                                <h5 class="frame-heading">Tindakan Operasi</h5>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label form-label-custom required">Jenis Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" id="jenis_operasi_id"
                                            name="jenis_operasi_id" required>
                                            <option value="">Pilih Jenis Operasi</option>
                                            @if ($order->kategori_operasi_id)
                                                @foreach ($jenis_operasi->where('kategori_operasi_id', $order->kategori_operasi_id) as $jenis)
                                                    <option value="{{ $jenis->id }}"
                                                        {{ $order->jenis_operasi_id == $jenis->id ? 'selected' : '' }}>
                                                        {{ $jenis->jenis }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <label class="col-md-2 col-form-label form-label-custom required">Tindakan
                                        Operasi</label>
                                    <div class="col-md-4">
                                        <select class="form-control select2" id="tindakan_operasi_id"
                                            name="tindakan_operasi_id" required>
                                            <option value="">Pilih Tindakan Operasi</option>
                                            @if ($order->kategori_operasi_id && $order->jenis_operasi_id)
                                                @foreach ($tindakan_operasi->where('kategori_operasi_id', $order->kategori_operasi_id)->where('jenis_operasi_id', $order->jenis_operasi_id) as $tindakan)
                                                    <option value="{{ $tindakan->id }}"
                                                        {{ optional($order->prosedur)->tindakan_operasi_id == $tindakan->id ? 'selected' : '' }}>
                                                        {{ $tindakan->nama_operasi }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-4">
                                    <button class="btn btn-secondary ml-auto" type="button" onclick="handleClose()">
                                        <i class="fal fa-times mr-1"></i> Tutup
                                    </button>
                                    <button class="btn btn-warning ml-2" type="button" id="btn-draft"
                                        data-status="draft">
                                        <i class="fal fa-save mr-1"></i> Save Draft
                                    </button>
                                    <button class="btn btn-primary ml-2" type="button" id="btn-final"
                                        data-status="final">
                                        <i class="fal fa-check mr-1"></i> Save Final
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/notifications/sweetalert2/sweetalert2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true,
                dropdownParent: $('#panel-form-tindakan'),
                width: '100%'
            });

            // Function untuk load jenis operasi berdasarkan kategori
            function loadJenisOperasi(kategoriId, selectedJenisId = null) {
                if (kategoriId) {
                    $.ajax({
                        url: `{{ url('simrs/ok/prosedur/get-jenis-by-kategori') }}/${kategoriId}`,
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#jenis_operasi_id').prop('disabled', true);
                            $('#jenis_operasi_id').empty().append(
                                '<option value="">Loading...</option>');
                        },
                        success: function(data) {
                            $('#jenis_operasi_id').empty();
                            $('#jenis_operasi_id').append(
                                '<option value="">Pilih Jenis Operasi</option>');
                            $.each(data, function(key, jenis) {
                                let selected = selectedJenisId == jenis.id ? 'selected' : '';
                                $('#jenis_operasi_id').append(
                                    `<option value="${jenis.id}" ${selected}>${jenis.jenis}</option>`
                                );
                            });
                            $('#jenis_operasi_id').prop('disabled', false);
                            $('#jenis_operasi_id').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading jenis operasi:', error);
                            $('#jenis_operasi_id').empty().append(
                                '<option value="">Error - Silakan refresh</option>');
                            $('#jenis_operasi_id').prop('disabled', false);
                        }
                    });
                } else {
                    $('#jenis_operasi_id').empty().append('<option value="">Pilih Jenis Operasi</option>');
                    $('#tindakan_operasi_id').empty().append('<option value="">Pilih Tindakan Operasi</option>');
                }
            }

            // Function untuk load tindakan operasi berdasarkan jenis
            function loadTindakanOperasi(jenisId, selectedTindakanId = null) {
                if (jenisId) {
                    $.ajax({
                        url: `{{ url('simrs/ok/prosedur/get-tindakan-by-jenis') }}/${jenisId}`,
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#tindakan_operasi_id').prop('disabled', true);
                            $('#tindakan_operasi_id').empty().append(
                                '<option value="">Loading...</option>');
                        },
                        success: function(data) {
                            $('#tindakan_operasi_id').empty();
                            $('#tindakan_operasi_id').append(
                                '<option value="">Pilih Tindakan Operasi</option>');
                            $.each(data, function(key, tindakan) {
                                let selected = selectedTindakanId == tindakan.id ? 'selected' :
                                    '';
                                $('#tindakan_operasi_id').append(
                                    `<option value="${tindakan.id}" ${selected}>${tindakan.nama_operasi}</option>`
                                );
                            });
                            $('#tindakan_operasi_id').prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading tindakan operasi:', error);
                            $('#tindakan_operasi_id').empty().append(
                                '<option value="">Error - Silakan refresh</option>');
                            $('#tindakan_operasi_id').prop('disabled', false);
                        }
                    });
                } else {
                    $('#tindakan_operasi_id').empty().append('<option value="">Pilih Tindakan Operasi</option>');
                }
            }

            // Event handlers untuk dropdown changes
            $('#kategori_operasi_id').on('change', function() {
                let kategoriId = $(this).val();
                loadJenisOperasi(kategoriId);
            });

            $('#jenis_operasi_id').on('change', function() {
                let jenisId = $(this).val();
                loadTindakanOperasi(jenisId);
            });

            // Load data awal
            let initialKategoriId = $('#kategori_operasi_id').val();
            if (initialKategoriId) {
                let selectedJenisId = '{{ $order->jenis_operasi_id ?? '' }}';
                let selectedTindakanId = '{{ optional($order->prosedur)->tindakan_operasi_id ?? '' }}';

                loadJenisOperasi(initialKategoriId, selectedJenisId);
                setTimeout(function() {
                    if (selectedJenisId) {
                        loadTindakanOperasi(selectedJenisId, selectedTindakanId);
                    }
                }, 500);
            }

            // HANDLE TOMBOL SAVE DRAFT
            $('#btn-draft').on('click', function() {
                submitFormWithStatus('draft', $(this));
            });

            // HANDLE TOMBOL SAVE FINAL  
            $('#btn-final').on('click', function() {
                submitFormWithStatus('final', $(this));
            });

            // Function untuk submit form
            function submitFormWithStatus(status, button) {
                // Simpan teks asli tombol
                button.data('original-text', button.html());

                // Disable buttons
                $('#btn-draft, #btn-final').prop('disabled', true);
                button.html('<i class="fal fa-spinner fa-spin mr-1"></i> Menyimpan...');

                // Ambil form data
                let form = $('#form-tindakan')[0];
                let formData = new FormData(form);
                formData.append('status', status);

                // Kumpulkan semua dokter tambahan
                let dokterTambahanIds = [];
                $('[name^="dokter_tambahan_"]').each(function() {
                    if ($(this).val()) {
                        dokterTambahanIds.push($(this).val());
                    }
                });
                formData.append('dokter_tambahan_ids', JSON.stringify(dokterTambahanIds));

                $.ajax({
                    url: '{{ route('ok.prosedur.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                handleClose();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan.'
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorText = '';

                            $.each(errors, function(field, messages) {
                                errorText += '• ' + messages.join(', ') + '\n';
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: errorText,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server. Silakan coba lagi.'
                            });
                        }
                    },
                    complete: function() {
                        // Re-enable buttons
                        $('#btn-draft, #btn-final').prop('disabled', false);
                        button.html(button.data('original-text'));
                    }
                });
            }
        });

        function handleClose() {
            if (window.opener) {
                window.opener.location.reload();
                window.close();
            } else {
                window.location.href = '{{ route('ok.daftar-pasien') }}';
            }
        }
    </script>
@endsection
