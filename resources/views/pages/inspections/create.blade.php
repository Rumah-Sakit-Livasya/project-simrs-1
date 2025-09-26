@extends('inc.layout')
@section('title', 'Form Inspeksi Kendaraan Harian')

@section('style')
    <style>
        /* Hilangkan underline pada button-link saat hover */
        .no-underline-hover,
        .no-underline-hover:hover,
        .no-underline-hover:focus {
            text-decoration: none !important;
        }

        .select2-container--open {
            z-index: 9999999 !important;
        }

        .inspection-card {
            border-radius: 0;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .inspection-card:first-child {
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
        }

        .inspection-card:last-child {
            margin-bottom: 1rem;
            border-bottom: 0;
            border-bottom-left-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }

        .inspection-header .btn {
            text-decoration: none !important;
            width: 100%;
            text-align: left;
            color: #333;
        }

        .inspection-header .btn:hover {
            background-color: #f8f9fa;
            text-decoration: none !important;
            /* Pastikan tidak ada garis bawah saat hover */
        }

        .status-icon {
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .checklist-item {
            transition: border-color 0.3s ease;
        }

        .checklist-item.status-rusak {
            border-color: #fd7e14 !important;
            background-color: #fff9f5;
        }

        .checklist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-name {
            font-weight: 500;
            font-size: 1rem;
        }

        .optional-inputs {
            display: none;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid #eee;
        }

        .monthly-status-icon {
            transition: color 0.3s ease;
        }

        .accordion-disabled {
            background-color: #f8f9fa !important;
            /* Warna abu-abu terang */
            cursor: not-allowed;
            /* Tampilkan ikon "dilarang" saat hover */
            opacity: 0.7;
            /* Buat sedikit transparan */
        }

        .accordion-disabled:hover {
            color: #333 !important;
            /* Cegah perubahan warna teks saat hover */
            text-decoration: none !important;
            /* Pastikan tidak ada garis bawah saat hover pada disabled */
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="{{ route('vehicles.inspections') }}">Riwayat Inspeksi</a></li>
            <li class="breadcrumb-item active">Form Inspeksi Baru</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <form id="inspectionForm" onsubmit="submitInspectionForm(event)">
                    {{-- Panel Header Sesi Inspeksi (Tidak ada perubahan di sini) --}}
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>Detail Sesi Inspeksi</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inspection_date">Tanggal Inspeksi</label>
                                        <input type="date" class="form-control" id="inspection_date"
                                            name="inspection_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inspector_id">Petugas Pemeriksa</label>
                                        <select class="form-control select2" id="inspector_id" name="inspector_id" required
                                            style="width: 100%;">
                                            <option></option>
                                            @foreach ($inspectors as $inspector)
                                                <option value="{{ $inspector->id }}">{{ $inspector->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Accordion untuk setiap Kendaraan --}}
                    <div class="accordion" id="vehicleAccordion">
                        @foreach ($vehicles as $vehicle)
                            <div class="card inspection-card" data-vehicle-id="{{ $vehicle->id }}">
                                <div class="card-header inspection-header p-0" id="heading-{{ $vehicle->id }}">
                                    <h2 class="mb-0">
                                        <button
                                            class="btn btn-link btn-block d-flex justify-content-between align-items-center p-3 @if ($vehicle->inspection_status == 'checked') accordion-disabled @endif no-underline-hover"
                                            type="button"
                                            @if ($vehicle->inspection_status != 'checked') data-toggle="collapse"
                                                data-target="#collapse-{{ $vehicle->id }}" @endif
                                            style="text-decoration: none !important;">
                                            <span>
                                                @if ($vehicle->inspection_status == 'checked')
                                                    <i class="fas fa-check-circle text-success monthly-status-icon"
                                                        title="Sudah diinspeksi bulan ini. Tidak bisa diubah."></i>
                                                @else
                                                    <i class="fas fa-hourglass-half text-warning monthly-status-icon"
                                                        title="Belum diinspeksi bulan ini"></i>
                                                @endif
                                                <span class="ms-2">{{ $vehicle->name }}</span>
                                                <small class="text-muted">({{ $vehicle->license_plate }})</small>
                                            </span>
                                            <i class="fal fa-check-circle status-icon text-muted"
                                                title="Status pengisian form hari ini"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse-{{ $vehicle->id }}" class="collapse" data-parent="#vehicleAccordion">
                                    {{-- LOGIKA BARU: Bungkus isi card dengan fieldset yang bisa di-disable --}}
                                    <fieldset @if ($vehicle->inspection_status == 'checked') disabled @endif>
                                        <div class="card-body">
                                            @foreach ($inspectionItems as $item)
                                                <div class="checklist-item mb-3 p-3 border rounded">
                                                    <div class="checklist-header">
                                                        <span class="item-name d-block mb-3">{{ $item->name }}</span>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-outline-success btn-sm">
                                                                <input type="radio"
                                                                    name="results[{{ $vehicle->id }}][{{ $item->id }}][status]"
                                                                    value="Baik"> Baik
                                                            </label>
                                                            <label class="btn btn-outline-danger btn-sm">
                                                                <input type="radio"
                                                                    name="results[{{ $vehicle->id }}][{{ $item->id }}][status]"
                                                                    value="Rusak"> Rusak
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="optional-inputs" style="display: none;">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label small">Dokumentasi Kerusakan (Wajib
                                                                jika 'Rusak')</label>
                                                            <input type="file" class="form-control-file form-control-sm"
                                                                name="results[{{ $vehicle->id }}][{{ $item->id }}][photo]">
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <label class="form-label small">Keterangan detail...</label>
                                                            <textarea class="form-control form-control-sm" name="results[{{ $vehicle->id }}][{{ $item->id }}][notes]"
                                                                rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Submit (Tidak ada perubahan di sini) --}}
                    <div class="panel">
                        <div class="panel-content d-flex flex-row align-items-center">
                            <button class="btn btn-primary ml-auto" type="submit" id="submitButton">Simpan Hasil
                                Inspeksi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Tidak ada perubahan yang diperlukan pada JavaScript --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script>
        // FUNGSI INI TIDAK PERLU DIUBAH
        function checkVehicleCompletion(vehicleCard) {
            /* ... */
        }
        $(document).ready(function() {
            /* ... */
        });
        async function submitInspectionForm(event) {
            /* ... */
        }
        // Copy-paste semua JavaScript dari jawaban Anda sebelumnya ke sini.
        // FUNGSI INI TIDAK PERLU DIUBAH
        function checkVehicleCompletion(vehicleCard) {
            const totalItems = $(vehicleCard).find('.checklist-item').length;
            const checkedItems = $(vehicleCard).find('input[type=radio]:checked').length;
            const statusIcon = $(vehicleCard).find('.status-icon');
            if (totalItems > 0 && totalItems === checkedItems) {
                statusIcon.removeClass('text-muted').addClass('text-success');
            } else {
                statusIcon.removeClass('text-success').addClass('text-muted');
            }
        }

        $(document).ready(function() {
            $('#inspector_id').select2({
                placeholder: "-- Pilih Petugas --"
            });

            $('.inspection-card').each(function() {
                checkVehicleCompletion(this);
            });

            $('input[type=radio]').on('change', function() {
                const wrapper = $(this).closest('.checklist-item');
                const optionalInputs = wrapper.find('.optional-inputs');
                const photoInput = optionalInputs.find('input[type=file]');

                if ($(this).val() === 'Rusak') {
                    optionalInputs.slideDown();
                    wrapper.addClass('status-rusak');
                    photoInput.prop('required', true);
                } else {
                    optionalInputs.slideUp();
                    wrapper.removeClass('status-rusak');
                    photoInput.prop('required', false);
                    photoInput.val('');
                    optionalInputs.find('textarea').val('');
                }

                checkVehicleCompletion($(this).closest('.inspection-card'));
            });
        });

        // FUNGSI SUBMIT INI JUGA TIDAK PERLU DIUBAH
        async function submitInspectionForm(event) {
            event.preventDefault();

            let filledVehicles = 0;
            $('.inspection-card').each(function() {
                const totalItems = $(this).find('.checklist-item').length;
                const checkedItems = $(this).find('input[type=radio]:checked').length;
                if (checkedItems > 0 && checkedItems < totalItems) {
                    Swal.fire('Validasi Gagal',
                        `Checklist untuk kendaraan "${$(this).find('span.ms-2').text()}" belum lengkap. Harap isi semua item atau kosongkan.`,
                        'warning');
                    filledVehicles = -1; // Set flag error
                    return false; // Hentikan loop .each
                }
                if (checkedItems === totalItems) {
                    filledVehicles++;
                }
            });

            if (filledVehicles === -1) return; // Hentikan jika ada yg terisi sebagian
            if (filledVehicles === 0) {
                Swal.fire('Tidak Ada Data', 'Anda harus mengisi checklist setidaknya untuk satu kendaraan.', 'info');
                return;
            }

            const form = document.getElementById('inspectionForm');
            const formData = new FormData(form);
            const submitButton = $('#submitButton');
            submitButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
            );

            try {
                const response = await fetch('/api/internal/inspections', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) throw result;
                Swal.fire({
                    title: 'Sukses!',
                    text: result.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('vehicles.inspections') }}";
                });
            } catch (error) {
                console.log(error);
                let errorHtml = '<ul>';
                if (error.errors) {
                    for (const key in error.errors) {
                        errorHtml += `<li>${error.errors[key][0]}</li>`;
                    }
                } else {
                    errorHtml += `<li>${error.message || 'Terjadi kesalahan.'}</li>`;
                }
                errorHtml += '</ul>';
                Swal.fire('Error', 'Gagal menyimpan, periksa kembali isian Anda.' + errorHtml, 'error');
            } finally {
                submitButton.prop('disabled', false).html('Simpan Hasil Inspeksi');
            }
        }
    </script>
@endsection
