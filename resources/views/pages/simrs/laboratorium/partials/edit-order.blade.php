@extends('inc.layout')
@section('title', 'Edit Order Laboratorium')

@section('extended-css')
    <style>
        .table-info th,
        .table-info td {
            background-color: #d1ecf1 !important;
            border-color: #bee5eb !important;
        }

        .form-control-plaintext {
            padding-top: .375rem;
            padding-bottom: .375rem;
            margin-bottom: 0;
            line-height: 1.5;
            background-color: transparent;
            border: solid transparent;
            border-width: 1px 0;
        }

        /* CSS untuk Indikator Hasil */
        .hasil-abnormal {
            border-color: #fd3995 !important;
            color: #fd3995;
            font-weight: 700;
        }

        .hasil-normal {
            border-color: #1dc9b7;
        }

        .hasil-abnormal-radio {
            color: #fd3995;
            font-weight: 700;
        }

        .input-group-hasil {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-hasil .input-hasil {
            flex-grow: 1;
        }

        .hasil-indicator {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }

        .hasil-abnormal+.hasil-indicator {
            display: inline;
            color: #fd3995;
        }

        .btn-autofill {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Sesuaikan posisi indikator jika ada tombol autofill */
        .input-group-append+.hasil-indicator {
            right: 45px;
        }

        /* CSS untuk input di dalam tabel */
        .td-hasil {
            vertical-align: middle !important;
        }

        .td-hasil .custom-control {
            margin-bottom: 0.25rem;
        }

        .td-hasil .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: .875rem;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <form id="form-laboratorium" action="{{ route('order.laboratorium.edit-order') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            {{-- Panel Informasi Pasien & Order --}}
            <div class="panel" id="panel-info">
                <div class="panel-hdr">
                    <h2>
                        Informasi Pasien & <span class="fw-300"><i>Order [{{ $order->no_order }}]</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            {{-- Kolom Kiri: Info Order --}}
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Tanggal Order</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Penjamin</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ $order->registration->penjamin->nama_perusahaan ?? 'OTC' }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Poli / Ruang</label>
                                    <div class="col-sm-7">
                                        @php
                                            $poliRuang = '-';
                                            if ($order->registration_otc) {
                                                $poliRuang = $order->registration_otc->poly_ruang;
                                            } elseif ($order->registration) {
                                                $poliRuang =
                                                    $order->registration->poliklinik ??
                                                    ($order->registration->kelas_rawat->room->ruangan ?? '-');
                                            }
                                        @endphp
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ $poliRuang }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Dokter Perujuk</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ $order->registration->doctor->employee->fullname }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Tengah: Info Pasien --}}
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Nama Pasien</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ $order->registration->patient->name ?? $order->registration_otc->nama_pasien }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ $order->registration->patient->gender ?? $order->registration_otc->jenis_kelamin }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Tgl. Lahir / Umur</label>
                                    <div class="col-sm-7">
                                        @php
                                            $dob =
                                                $order->registration->patient->date_of_birth ??
                                                $order->registration_otc->date_of_birth;
                                        @endphp
                                        <input type="text" readonly class="form-control-plaintext"
                                            value="{{ \Carbon\Carbon::parse($dob)->format('d-m-Y') }} ({{ displayAge($dob) }})">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Alamat</label>
                                    <div class="col-sm-7">
                                        <textarea readonly class="form-control-plaintext" rows="1">{{ $order->registration->patient->address ?? $order->registration_otc->alamat }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Input Medis --}}
                            <div class="col-md-12 col-lg-4">
                                <div class="form-group">
                                    <label class="form-label" for="diagnosa_klinis">Diagnosa Klinis</label>
                                    <input type="text" class="form-control" id="diagnosa_klinis"
                                        value="{{ old('diagnosa_klinis', $order->diagnosa_klinis) }}"
                                        name="diagnosa_klinis">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="inspection_date">Tgl & Jam Sampel</label>
                                    <input type="datetime-local"
                                        class="form-control @error('inspection_date') is-invalid @enderror"
                                        id="inspection_date" name="inspection_date"
                                        value="{{ old('inspection_date', $order->inspection_date ? \Carbon\Carbon::parse($order->inspection_date)->format('Y-m-d\TH:i') : '') }}">
                                    @error('inspection_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="result_datetime">Tgl & Jam Hasil</label>
                                    <input type="datetime-local"
                                        class="form-control @error('result_datetime') is-invalid @enderror"
                                        id="result_datetime" name="result_datetime"
                                        value="{{ old('result_datetime', $order->result_datetime ? \Carbon\Carbon::parse($order->result_datetime)->format('Y-m-d\TH:i') : '') }}">
                                    @error('result_datetime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Input Hasil Pemeriksaan --}}
            <div class="panel" id="panel-hasil">
                <div class="panel-hdr">
                    <h2>
                        Input Hasil <span class="fw-300"><i>Pemeriksaan</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center" style="width: 40px;">#</th>
                                        <th>Pemeriksaan</th>
                                        <th style="width: 20%;">Hasil</th>
                                        <th style="width: 8%;">Satuan</th>
                                        <th style="width: 12%;">Nilai Normal</th>
                                        <th style="width: 15%;">Keterangan</th>
                                        <th style="width: 15%;">Verifikasi</th>
                                        <th class="text-center" style="width: 50px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalCount = 0; @endphp
                                    @foreach ($parametersInCategory as $categoryName => $parameters)
                                        <tr class="table-info">
                                            <td colspan="8" class="fw-500 text-center">{{ $categoryName }}</td>
                                        </tr>
                                        @foreach ($parameters as $parameter)
                                            @php
                                                $nilai_normal_parameter = null;
                                                $dob =
                                                    $order->registration->patient->date_of_birth ??
                                                    $order->registration_otc->date_of_birth;
                                                $jenis_kelamin =
                                                    $order->registration->patient->gender ??
                                                    $order->registration_otc->jenis_kelamin;
                                                foreach ($nilai_normals as $nilai_normal) {
                                                    if (
                                                        $nilai_normal->parameter_laboratorium_id ==
                                                            $parameter->parameter_laboratorium_id &&
                                                        isWithinAgeRange(
                                                            $dob,
                                                            $nilai_normal->dari_umur,
                                                            $nilai_normal->sampai_umur,
                                                        ) &&
                                                        ($nilai_normal->jenis_kelamin == $jenis_kelamin ||
                                                            $nilai_normal->jenis_kelamin == 'Semuanya')
                                                    ) {
                                                        $nilai_normal_parameter = $nilai_normal;
                                                        break;
                                                    }
                                                }
                                                $tipe_hasil = $parameter->parameter_laboratorium->tipe_hasil;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ ++$totalCount }}</td>
                                                <td>
                                                    <strong>{{ $parameter->parameter_laboratorium->parameter }}</strong>
                                                    @if ($order->tipe_order == 'cito')
                                                        <span class="badge badge-danger ml-2">CITO</span>
                                                    @endif
                                                </td>
                                                <td class="p-2 td-hasil" data-tipe-hasil="{{ $tipe_hasil }}"
                                                    data-nilai-normal="{{ $nilai_normal_parameter->nilai_normal ?? '' }}"
                                                    data-min="{{ $nilai_normal_parameter->min ?? '' }}"
                                                    data-max="{{ $nilai_normal_parameter->max ?? '' }}">

                                                    @if (!$parameter->parameter_laboratorium->is_hasil)
                                                        <span class="text-muted"> (Tidak ada hasil) </span>
                                                    @elseif ($tipe_hasil == 'Negatif/Positif')
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="hasil_{{ $parameter->id }}_neg"
                                                                name="hasil_{{ $parameter->id }}"
                                                                class="custom-control-input input-hasil" value="Negatif"
                                                                {{ old('hasil_' . $parameter->id, $parameter->hasil) == 'Negatif' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="hasil_{{ $parameter->id }}_neg">Negatif</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="hasil_{{ $parameter->id }}_pos"
                                                                name="hasil_{{ $parameter->id }}"
                                                                class="custom-control-input input-hasil" value="Positif"
                                                                {{ old('hasil_' . $parameter->id, $parameter->hasil) == 'Positif' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="hasil_{{ $parameter->id }}_pos">Positif</label>
                                                        </div>
                                                    @elseif ($tipe_hasil == 'Reaktif/NonReaktif')
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="hasil_{{ $parameter->id }}_non"
                                                                name="hasil_{{ $parameter->id }}"
                                                                class="custom-control-input input-hasil"
                                                                value="NonReaktif"
                                                                {{ old('hasil_' . $parameter->id, $parameter->hasil) == 'NonReaktif' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="hasil_{{ $parameter->id }}_non">Non Reaktif</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="hasil_{{ $parameter->id }}_rea"
                                                                name="hasil_{{ $parameter->id }}"
                                                                class="custom-control-input input-hasil" value="Reaktif"
                                                                {{ old('hasil_' . $parameter->id, $parameter->hasil) == 'Reaktif' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="hasil_{{ $parameter->id }}_rea">Reaktif</label>
                                                        </div>
                                                    @else
                                                        <div class="input-group input-group-sm input-group-hasil">
                                                            <input type="text"
                                                                class="form-control form-control-sm input-hasil"
                                                                name="hasil_{{ $parameter->id }}"
                                                                value="{{ old('hasil_' . $parameter->id, $parameter->hasil) }}"
                                                                autocomplete="off">

                                                            <i class="fal fa-exclamation-triangle hasil-indicator"
                                                                title="Hasil di luar rentang normal"></i>

                                                            @if ($tipe_hasil == 'Teks' && $nilai_normal_parameter && strpos($nilai_normal_parameter->nilai_normal, '/') === false)
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-primary btn-sm btn-autofill"
                                                                        type="button" title="Isi dengan nilai normal">
                                                                        <i class="fal fa-magic"></i>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $parameter->parameter_laboratorium->satuan }}</td>
                                                <td>
                                                    @if ($nilai_normal_parameter)
                                                        {{ $tipe_hasil == 'Angka' ? $nilai_normal_parameter->min . ' - ' . $nilai_normal_parameter->max : str_replace('/', ' / ', $nilai_normal_parameter->nilai_normal) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <textarea class="form-control form-control-sm" name="catatan_{{ $parameter->id }}"
                                                        id="catatan_{{ $parameter->id }}" rows="1">{{ old('catatan_' . $parameter->id, $parameter->catatan) }}</textarea>
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($parameter->verifikator_id))
                                                        <div class="text-success">
                                                            <i class="fas fa-check-circle fa-2x"></i>
                                                            <div class="fs-xs text-muted mt-1">
                                                                oleh
                                                                <strong>{{ $parameter->verifikator->fullname }}</strong><br>
                                                                {{ \Carbon\Carbon::parse($parameter->verifikasi_date)->format('d-m-Y H:i') }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <button type="button" data-id="{{ $parameter->id }}"
                                                            class="btn btn-sm btn-primary verify-btn">
                                                            Verifikasi
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($parameter->parameter_laboratorium->is_order && $order->order_parameter_laboratorium->count() > 1)
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-xs btn-icon btn-danger delete-btn"
                                                            title="Hapus Pemeriksaan" data-id="{{ $parameter->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Opsi & Tindakan --}}
            <div class="panel" id="panel-tindakan">
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('laboratorium.list-order') }}"
                                    class="btn btn-lg btn-secondary waves-effect waves-themed">
                                    <span class="fal fa-arrow-left mr-2"></span>
                                    Kembali ke Daftar Order
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" id="btn-open-popup"
                                    class="btn btn-lg btn-success waves-effect waves-themed">
                                    <span class="fal fa-plus-circle mr-2"></span>
                                    Tambah Tindakan
                                </button>
                                <button type="button" id="laboratorium-submit"
                                    class="btn btn-lg btn-primary waves-effect waves-themed">
                                    <span class="fal fa-save mr-2"></span>
                                    Simpan Final
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@section('plugin')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window._order = @json($order);
        });
    </script>
    <script>
        $(document).ready(function() {
            // --- LOGIKA POPUP WINDOW ---
            $('#btn-open-popup').on('click', function() {
                var orderId = window._order.id;
                var url = `/simrs/laboratorium/order/${orderId}/add-tindakan-popup`;
                var width = 1200;
                var height = 700;
                var left = (screen.width / 2) - (width / 2);
                var top = (screen.height / 2) - (height / 2);
                var popupWindow = window.open(url, 'TambahTindakan',
                    `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
                if (window.focus) popupWindow.focus();
                var timer = setInterval(function() {
                    if (popupWindow.closed) {
                        clearInterval(timer);
                        location.reload();
                    }
                }, 500);
            });

            // --- FUNGSI VALIDASI HASIL REAL-TIME ---
            function checkHasil(inputElement) {
                const $input = $(inputElement);
                const $parentTd = $input.closest('td');
                $parentTd.find('.input-hasil, .custom-control-label').removeClass(
                    'hasil-abnormal hasil-normal hasil-abnormal-radio');
                const tipeHasil = $parentTd.data('tipe-hasil');
                let nilaiInput;
                if ($input.is(':radio')) {
                    nilaiInput = $(`input[name="${$input.attr('name')}"]:checked`).val();
                } else {
                    nilaiInput = $input.val();
                }
                if (typeof nilaiInput === 'undefined' || nilaiInput.trim() === "") return;
                nilaiInput = nilaiInput.trim();
                let isAbnormal = false;
                if (tipeHasil === 'Angka') {
                    const min = parseFloat($parentTd.data('min'));
                    const max = parseFloat($parentTd.data('max'));
                    const nilaiFloat = parseFloat(nilaiInput);
                    if (!isNaN(min) && !isNaN(max) && !isNaN(nilaiFloat)) {
                        if (nilaiFloat < min || nilaiFloat > max) isAbnormal = true;
                    }
                } else { // Ini mencakup semua tipe Teks, termasuk radio button
                    const nilaiNormal = $parentTd.data('nilai-normal').toString().toLowerCase();
                    if (nilaiNormal && !nilaiNormal.includes('/')) {
                        if (nilaiInput.toLowerCase() !== nilaiNormal) isAbnormal = true;
                    }
                }
                let $targetElement = $input.is(':radio') ? $(`input[name="${$input.attr('name')}"]:checked`).next(
                    '.custom-control-label') : $input;
                if (isAbnormal) {
                    if ($input.is(':radio')) $targetElement.addClass('hasil-abnormal-radio');
                    else $targetElement.addClass('hasil-abnormal');
                } else {
                    $targetElement.addClass('hasil-normal');
                }
            }

            // --- EVENT LISTENERS ---
            $('.input-hasil').on('keyup change', function() {
                checkHasil(this);
            });
            $('.input-hasil').each(function() {
                if ($(this).is(':radio') && $(this).is(':checked')) checkHasil(this);
                else if (!$(this).is(':radio')) checkHasil(this);
            });
            $('.btn-autofill').on('click', function() {
                const $parentTd = $(this).closest('td');
                const $inputHasil = $parentTd.find('.input-hasil');
                const nilaiNormal = $parentTd.data('nilai-normal');
                if (nilaiNormal) {
                    $inputHasil.val(nilaiNormal);
                    checkHasil($inputHasil[0]);
                }
            });

            // --- Aksi Verifikasi & Hapus ---
            $('.verify-btn').on('click', function() {
                Swal.fire({
                        title: 'Verifikasi Hasil?',
                        text: "Pastikan hasil pemeriksaan ini sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Verifikasi!',
                        cancelButtonText: 'Batal'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            /* AJAX Call Here */
                            showSuccessAlert('Hasil berhasil diverifikasi.');
                        }
                    });
            });
            $('.delete-btn').on('click', function() {
                showDeleteConfirmation(function() {
                    /* AJAX Call Here */
                    showSuccessAlert('Pemeriksaan berhasil dihapus.');
                });
            });

            // --- SUBMIT FORM VIA JS DENGAN KONFIRMASI ---
            $('#form-laboratorium').on('submit', function(e) {
                // Prevent default submit, handled by JS
                e.preventDefault();
            });

            $('#laboratorium-submit').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Simpan Final?',
                    text: "Pastikan data sudah benar sebelum disimpan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form via AJAX
                        var $form = $('#form-laboratorium');
                        var formData = new FormData($form[0]);
                        var actionUrl = $form.attr('action');
                        var $btn = $(this);
                        $btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm mr-2"></span>Proses...'
                        );
                        $.ajax({
                            url: actionUrl,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Sukses, bisa redirect atau reload
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil disimpan.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Redirect ke daftar order atau reload
                                    window.location.href =
                                        "{{ route('laboratorium.list-order') }}";
                                });
                            },
                            error: function(xhr) {
                                $btn.prop('disabled', false).html(
                                    '<span class="fal fa-save mr-2"></span>Simpan Final'
                                );
                                // Tampilkan error
                                let msg = 'Terjadi kesalahan saat menyimpan data.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: msg,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                // Jika ada error validasi, tampilkan di form
                                if (xhr.status === 422 && xhr.responseJSON && xhr
                                    .responseJSON.errors) {
                                    let errors = xhr.responseJSON.errors;
                                    // Bersihkan error sebelumnya
                                    $form.find('.is-invalid').removeClass('is-invalid');
                                    $form.find('.invalid-feedback').remove();
                                    $.each(errors, function(field, messages) {
                                        let $input = $form.find('[name="' +
                                            field + '"]');
                                        $input.addClass('is-invalid');
                                        if ($input.next('.invalid-feedback')
                                            .length === 0) {
                                            $input.after(
                                                '<div class="invalid-feedback">' +
                                                messages[0] + '</div>');
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
