@extends('pages.simrs.pendaftaran.detail-registrasi-pasien')

@push('css-detail-regis')
    <style>
        .modal-content {
            position: relative;
        }

        .modal-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 99999999999999999999999;
            border-radius: .5rem;
            cursor: wait;
        }
    </style>
@endpush

@section('page-layanan')
    {{-- <div id="tindakan-medis"> --}}
    <div class="panel-hdr border-top">
        <h2 class="text-light">
            <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
            <span class="text-primary">Tindakan Medis</span>
        </h2>
    </div>
    <div class="row">
        <div class="col-md-12 px-4 pb-2 pt-4">
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <div class="table-responsive">
                        <table id="dt-tindakan-bidan" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Dokter</th>
                                    <th>Tindakan</th>
                                    <th>Kelas</th>
                                    <th>Qty</th>
                                    <th>Entry By</th>
                                    <th>F.O.C</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="9" class="text-center">
                                        <button type="button" class="btn btn-outline-primary waves-effect waves-themed"
                                            id="btn-tambah-tindakan" data-toggle="modal" data-id="{{ $registration->id }}"
                                            data-target="#modal-tambah-tindakan">
                                            <span class="fal fa-plus-circle"></span>
                                            Tambah Tindakan
                                        </button>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- datatable end -->
                    @if (str_contains(\Illuminate\Support\Facades\Route::currentRouteName(), 'daftar-registrasi-pasien') ||
                            str_contains(url()->current(), '/daftar-registrasi-pasien/'))
                        <div class="d-flex justify-content-start m-3">
                            <a href="{{ route('detail.registrasi.pasien', ['registrations' => $registration->id]) }}"
                                class="btn btn-outline-primary px-4 shadow-sm d-flex align-items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                <span>Kembali ke Menu</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
    @include('pages.simrs.pendaftaran.partials.modal-tindakan-medis')
@endsection


@push('script-detail-regis')
    <script>
        let dtTindakanBidan;
        $(document).ready(function() {
            // Sembunyikan elemen 'tindakan-medis' saat pertama kali dimuat
            $('#tindakan-medis').hide();

            // Inisialisasi DataTable
            dtTindakanBidan = $('#dt-tindakan-bidan').DataTable({
                processing: true,
                serverSide: false,
                searching: false,
                paging: false,
                info: false,
                ordering: false,
                language: {
                    emptyTable: "Belum ada tindakan medis."
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal_tindakan',
                        name: 'tanggal_tindakan'
                    },
                    {
                        data: 'doctor',
                        name: 'doctor'
                    },
                    {
                        data: 'tindakan',
                        name: 'tindakan'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'entry_by',
                        name: 'entry_by'
                    },
                    {
                        data: 'foc',
                        name: 'foc'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                data: []
            });

            // Event listener untuk menu item "Tindakan Medis"
            $('.menu-layanan[data-layanan="tindakan-medis"]').on('click', function() {
                const registrationId = $('#registration').val();

                $.ajax({
                    url: `/api/simrs/get-medical-actions/${registrationId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#modal-tambah-tindakan').modal('hide');
                        if (response.success) {
                            const data = response.data;
                            let rows = [];
                            let idx = 1;
                            data.forEach(action => {
                                rows.push({
                                    no: idx++,
                                    tanggal_tindakan: action.tanggal_tindakan ||
                                        'Tidak Diketahui',
                                    doctor: action.doctor?.employee?.fullname ||
                                        'Tidak Diketahui',
                                    tindakan: action.tindakan_medis
                                        ?.nama_tindakan || 'Tidak Diketahui',
                                    kelas: action.departement?.name ||
                                        'Tidak Diketahui',
                                    qty: action.qty || 0,
                                    entry_by: action.user?.employee?.fullname ||
                                        'Tidak Diketahui',
                                    foc: action.foc || 'Tidak Diketahui',
                                    aksi: `<button class="btn btn-danger btn-sm delete-action" data-id="${action.id}">Hapus</button>`
                                });
                            });
                            dtTindakanBidan.clear().rows.add(rows).draw();
                        } else {
                            $('#modal-tambah-tindakan').modal('hide');
                            showErrorAlertNoRefresh('Gagal memuat tindakan medis: ' + response
                                .message);
                        }
                    },
                    error: function(xhr) {
                        $('#modal-tambah-tindakan').modal('hide');
                        let errorMessage =
                            'Terjadi kesalahan yang tidak diketahui. Silakan coba lagi nanti.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 0) {
                            errorMessage =
                                'Tidak terhubung ke server. Silakan periksa koneksi internet Anda.';
                        } else if (xhr.status === 404) {
                            errorMessage = 'Tindakan medis tidak ditemukan.';
                        } else if (xhr.status === 500) {
                            errorMessage =
                                'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                        } else {
                            errorMessage =
                                `Gagal memuat tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                        }
                        // showErrorAlertNoRefresh(errorMessage);
                    }
                });
            });

            // Event listener untuk tombol hapus tindakan medis
            $(document).on('click', '.delete-action', function() {
                const actionId = $(this).data('id');
                const $row = $(this).closest('tr');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Tindakan medis ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/simrs/delete-medical-action/${actionId}`,
                            method: 'DELETE',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content'),
                                'Accept': 'application/json'
                            },
                            success: function(response) {
                                if (response == 1) {
                                    // Hapus baris dari DataTable
                                    dtTindakanBidan.row($row).remove().draw();
                                    $('#modal-tambah-tindakan').modal('hide');
                                    showSuccessAlert(
                                        'Tindakan medis berhasil dihapus.');
                                } else {
                                    $('#modal-tambah-tindakan').modal('hide');
                                    showErrorAlertNoRefresh(
                                        'Gagal menghapus tindakan medis: ' + (
                                            response.message || ''));
                                }
                            },
                            error: function(xhr) {
                                $('#modal-tambah-tindakan').modal('hide');
                                let errorMessage =
                                    'Terjadi kesalahan yang tidak diketahui. Silakan coba lagi nanti.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.status === 0) {
                                    errorMessage =
                                        'Tidak terhubung ke server. Silakan periksa koneksi internet Anda.';
                                } else if (xhr.status === 404) {
                                    errorMessage =
                                        'Tindakan medis yang ingin dihapus tidak ditemukan.';
                                } else if (xhr.status === 500) {
                                    errorMessage =
                                        'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                                } else {
                                    errorMessage =
                                        `Gagal menghapus tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                                }
                                showErrorAlertNoRefresh(errorMessage);
                            }
                        });
                    }
                });
            });

            // Set tanggal default untuk input
            let today = new Date();
            let day = String(today.getDate()).padStart(2, '0');
            let month = String(today.getMonth() + 1).padStart(2, '0');
            let year = today.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;
            $('#tglTindakan').val(formattedDate);

            // Inisialisasi datepicker
            $('#tglTindakan').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });

            // Pastikan kode ini ada di dalam file JS Anda
            $('#departement-tindakan-medis').on('change', function() {
                const tindakanMedisSelect = $('#tindakanMedis');
                const selectedOption = $(this).find('option:selected');
                const groupTindakanMedisData = selectedOption.data('groups');

                tindakanMedisSelect.empty().append('<option value="">Pilih Tindakan Medis</option>');

                if (groupTindakanMedisData && Array.isArray(groupTindakanMedisData)) {
                    $.each(groupTindakanMedisData, function(index, group) {
                        if (group.tindakan_medis) {
                            $.each(group.tindakan_medis, function(i, tindakan) {
                                tindakanMedisSelect.append(new Option(tindakan
                                    .nama_tindakan, tindakan.id));
                            });
                        }
                    });
                }
                tindakanMedisSelect.trigger('change');
            });

            // Fungsi untuk menambahkan tindakan medis baru ke DataTable
            function addMedicalAction(data) {
                // Ambil jumlah baris saat ini untuk penomoran
                let rowCount = dtTindakanBidan.data().count() + 1;
                dtTindakanBidan.row.add({
                    no: rowCount,
                    tanggal_tindakan: data.tanggal_tindakan || 'Tidak Diketahui',
                    doctor: data.doctor?.employee?.fullname || 'Tidak Diketahui',
                    tindakan: data.tindakan_medis?.nama_tindakan || 'Tidak Diketahui',
                    kelas: data.departement?.name || 'Tidak Diketahui',
                    qty: data.qty || 0,
                    entry_by: data.user?.employee?.fullname || 'Tidak Diketahui',
                    foc: data.foc || 'Tidak Diketahui',
                    aksi: `<button class="btn btn-danger btn-sm delete-action" data-id="${data.id}">Hapus</button>`
                }).draw();
            }

            // Event listener untuk pengiriman form untuk menambahkan tindakan medis baru
            $('#modal-tambah-tindakan #store-form').on('submit', function(event) {
                event.preventDefault();

                const modal = $('#modal-tambah-tindakan');
                const loadingOverlay = modal.find('.modal-loading-overlay');

                // Tampilkan loading overlay
                loadingOverlay.show();

                const formData = {
                    tanggal_tindakan: $('#tglTindakan').val(),
                    registration_id: $('#registration').val(),
                    doctor_id: $('#dokterPerawat').val(),
                    tindakan_medis_id: $('#tindakanMedis').val(),
                    kelas: $('#kelas-tindakan-medis').val(),
                    departement_id: $('#departement-tindakan-medis').val(),
                    qty: $('#qty').val(),
                    user_id: {{ auth()->user()->id }},
                    foc: $('#diskonDokter').is(':checked') ? 'Yes' : 'No',
                };

                $.ajax({
                    url: '/api/simrs/order-tindakan-medis',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            addMedicalAction(response.data);
                            modal.modal('hide');
                            showSuccessAlert('Tindakan medis berhasil ditambahkan!');
                        } else {
                            showErrorAlertNoRefresh('Gagal menambahkan tindakan medis: ' +
                                response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan yang tidak diketahui.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (xhr.status === 422) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        showErrorAlertNoRefresh(errorMessage);
                    },
                    complete: function() {
                        loadingOverlay.hide();
                    }
                });
            });

            $('#modal-tambah-tindakan').on('shown.bs.modal', function(event) {
                const modal = $(this);
                const loadingOverlay = modal.find('.modal-loading-overlay');
                const registrasiId = "{{ $registration->id }}";
                let today = new Date();
                let day = String(today.getDate()).padStart(2, '0');
                let month = String(today.getMonth() + 1).padStart(2, '0');
                let year = today.getFullYear();
                let formattedDate = `${day}-${month}-${year}`;

                loadingOverlay.show();

                $('#store-form')[0].reset();
                $('#tglTindakan').val(formattedDate);
                $('#store-form select').val(null).trigger('change');

                $('#store-form #dokterPerawat, #store-form #departement-tindakan-medis, #store-form #kelas-tindakan-medis, #store-form #tindakanMedis')
                    .select2({
                        dropdownParent: $('#modal-tambah-tindakan')
                    });

                if (registrasiId) {
                    $.ajax({
                        url: `/api/simrs/get-registrasi-data/${registrasiId}`,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                const data = response.data;
                                $('#dokterPerawat').val(data.dokter_id).trigger('change');
                                $('#kelas-tindakan-medis').val(data.kelas_id).trigger('change');
                                $('#departement-tindakan-medis').val(data.departement_id)
                                    .trigger('change');
                            } else {
                                showErrorAlertNoRefresh('Data registrasi tidak ditemukan: ' +
                                    response.message);
                            }
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh('Gagal memuat data registrasi.');
                        },
                        complete: function() {
                            loadingOverlay.hide();
                        }
                    });
                } else {
                    loadingOverlay.hide();
                }
            });
        });
    </script>
@endpush
