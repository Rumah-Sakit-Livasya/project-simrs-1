@extends('inc.layout-no-side')
@section('title', 'Set Tarif Visite Dokter')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-pills'></i> Set Tarif Visite: <span
                    class="fw-300">{{ $doctor->employee->fullname }}</span>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <form id="form-tarif-matrix">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <div class="panel-hdr d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">Matriks Tarif per Kelas dan Grup Penjamin</h2>
                            <div class="panel-toolbar">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mr-2 mb-0" for="copy-from-doctor">Salin Tarif Dari:</label>
                                    <select id="copy-from-doctor" class="form-control form-control-sm"
                                        style="width: 250px;">
                                        <option></option>
                                        @foreach ($sourceDoctors as $sourceDoctor)
                                            <option value="{{ $sourceDoctor->id }}">{{ $sourceDoctor->employee->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table id="tariff-matrix-table" class="table table-bordered table-hover w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th rowspan="2" class="align-middle text-center">Kelas Perawatan</th>
                                                @foreach ($groupPenjamins as $group)
                                                    <th colspan="3" class="text-center">{{ $group->name }}</th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach ($groupPenjamins as $group)
                                                    <th class="text-center">Share RS (Rp)</th>
                                                    <th class="text-center">Share DR (Rp)</th>
                                                    <th class="text-center">Prasarana (Rp)</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kelasRawat as $kelas)
                                                <tr>
                                                    <td class="fw-500">{{ $kelas->kelas }}</td>
                                                    @foreach ($groupPenjamins as $group)
                                                        @php
                                                            $key = $kelas->id . '_' . $group->id;
                                                            $tariff = $existingTariffs->get($key);
                                                        @endphp
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm"
                                                                name="tariffs[{{ $key }}][share_rs]"
                                                                value="{{ $tariff->share_rs ?? '' }}" placeholder="0">
                                                            <input type="hidden"
                                                                name="tariffs[{{ $key }}][kelas_rawat_id]"
                                                                value="{{ $kelas->id }}">
                                                            <input type="hidden"
                                                                name="tariffs[{{ $key }}][group_penjamin_id]"
                                                                value="{{ $group->id }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm"
                                                                name="tariffs[{{ $key }}][share_dr]"
                                                                value="{{ $tariff->share_dr ?? '' }}" placeholder="0">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm"
                                                                name="tariffs[{{ $key }}][prasarana]"
                                                                value="{{ $tariff->prasarana ?? '' }}" placeholder="0">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div
                                class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="btn-save-matrix">Simpan Semua
                                    Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2
            $('#copy-from-doctor').select2({
                placeholder: 'Pilih dokter untuk menyalin tarif...',
                width: 'style',
                allowClear: true
            });

            // Handler copy tarif dari dokter lain
            $('#copy-from-doctor').on('change', function() {
                const sourceDoctorId = $(this).val();

                if (!sourceDoctorId) {
                    return;
                }

                Swal.fire({
                    title: 'Salin Tarif?',
                    text: "Ini akan menimpa semua tarif yang ada di form dengan tarif dari dokter yang dipilih. Anda masih harus menyimpannya secara manual.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Salin!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('simrs/tarif-visite-dokter/get-tariffs-json') }}/" +
                                sourceDoctorId,
                            type: "GET",
                            dataType: 'json',
                            success: function(tariffs) {
                                // Bersihkan input
                                $('#tariff-matrix-table tbody input[type="number"]')
                                    .val('');
                                tariffs.forEach(function(tariff) {
                                    const key = tariff.kelas_rawat_id + '_' +
                                        tariff.group_penjamin_id;
                                    $(`input[name="tariffs[${key}][share_rs]"]`)
                                        .val(tariff.share_rs);
                                    $(`input[name="tariffs[${key}][share_dr]"]`)
                                        .val(tariff.share_dr);
                                    $(`input[name="tariffs[${key}][prasarana]"]`)
                                        .val(tariff.prasarana);
                                });
                                showSuccessAlert(
                                    'Tarif berhasil disalin. Jangan lupa untuk menyimpan perubahan.'
                                    );
                                $('#copy-from-doctor').val(null).trigger('change');
                            },
                            error: function() {
                                showErrorAlert(
                                    'Gagal mengambil data tarif dari dokter yang dipilih.'
                                    );
                                $('#copy-from-doctor').val(null).trigger('change');
                            }
                        });
                    } else {
                        $('#copy-from-doctor').val(null).trigger('change');
                    }
                });
            });

            $('#form-tarif-matrix').submit(function(e) {
                e.preventDefault();
                $('#btn-save-matrix').html('Menyimpan...').prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('tarif-visite-dokter.store') }}",
                    data: $(this).serialize(),
                    success: function(data) {
                        showSuccessAlert(data.message);
                        if (window.opener && !window.opener.closed) {
                            try {
                                window.opener.onTariffUpdated();
                            } catch (e) {}
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menyimpan data';
                        showErrorAlertNoRefresh(errorMsg);
                    },
                    complete: function() {
                        $('#btn-save-matrix').html('Simpan Semua Perubahan').prop('disabled',
                            false);
                    }
                });
            });
        });
    </script>
@endsection
