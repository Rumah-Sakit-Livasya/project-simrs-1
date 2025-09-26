@extends('pages.simrs.erm.index')
@section('erm')
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
            z-index: 10;
            border-radius: .5rem;
            cursor: wait;
        }
    </style>

    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian"></div>
                    <h2 class="font-weight-bold">TINDAKAN MEDIS</h2>
                </header>
                <hr style="border-color: #868686; margin-top: 30px; margin-bottom: 30px;">
                <div class="row">
                    <div class="col-md-12 px-4 pb-2 pt-4">
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table id="dt-tindakan-bidan"
                                        class="table table-bordered table-hover table-striped w-100">
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
                                                    <button type="button"
                                                        class="btn btn-outline-primary waves-effect waves-themed"
                                                        id="btn-tambah-tindakan" data-toggle="modal"
                                                        data-id="{{ $registration->id }}"
                                                        data-target="#modal-tambah-tindakan">
                                                        <span class="fal fa-plus-circle"></span>
                                                        Tambah Tindakan
                                                    </button>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('pages.simrs.pendaftaran.partials.modal-tindakan-medis')
            </div>
        </div>
    @endif
@endsection

@section('plugin-erm')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    @include('pages.simrs.poliklinik.partials.action-js.tindakan-medis')
    <script>
        let dtTindakanBidan;
        $(document).ready(function() {
            $('body').addClass('layout-composed');

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
                ]
            });

            function addMedicalAction(data) {
                const doctorName = data.doctor?.employee?.fullname || 'Tidak Diketahui';
                const actionName = data.tindakan_medis?.nama_tindakan || 'Tidak Diketahui';
                const className = data.departement?.name || 'Tidak Diketahui';
                const qty = data.qty || 0;
                const userName = data.user?.employee?.fullname || 'Tidak Diketahui';
                const foc = data.foc || 'Tidak Diketahui';

                dtTindakanBidan.row.add({
                    no: dtTindakanBidan.rows().count() + 1,
                    tanggal_tindakan: data.tanggal_tindakan || 'Tidak Diketahui',
                    doctor: doctorName,
                    tindakan: actionName,
                    kelas: className,
                    qty: qty,
                    entry_by: userName,
                    foc: foc,
                    aksi: `<button class="btn btn-danger btn-sm delete-action" data-id="${data.id}">Hapus</button>`
                }).draw();
            }

            function loadMedicalActions() {
                const registrationId = "{{ $registration->id }}";
                $.ajax({
                    url: `/api/simrs/get-medical-actions/${registrationId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            dtTindakanBidan.clear();
                            let index = 1;
                            response.data.forEach(function(action) {
                                const doctorName = action.doctor?.employee?.fullname ||
                                    'Tidak Diketahui';
                                const actionName = action.tindakan_medis?.nama_tindakan ||
                                    'Tidak Diketahui';
                                const className = action.departement?.name || 'Tidak Diketahui';
                                const qty = action.qty || 0;
                                const userName = action.user?.employee?.fullname ||
                                    'Tidak Diketahui';
                                const foc = action.foc || 'Tidak Diketahui';

                                dtTindakanBidan.row.add({
                                    no: index++,
                                    tanggal_tindakan: action.tanggal_tindakan ||
                                        'Tidak Diketahui',
                                    doctor: doctorName,
                                    tindakan: actionName,
                                    kelas: className,
                                    qty: qty,
                                    entry_by: userName,
                                    foc: foc,
                                    aksi: `<button class="btn btn-danger btn-sm delete-action" data-id="${action.id}">Hapus</button>`
                                });
                            });
                            dtTindakanBidan.draw();
                        } else {
                            $('#modal-tambah-tindakan').modal('hide');
                            showErrorAlertNoRefresh('Gagal memuat tindakan medis: ' + response.message);
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
                            errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                        } else {
                            errorMessage =
                                `Gagal memuat tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                        }
                        // showErrorAlertNoRefresh(errorMessage);
                    }
                });
            }

            loadMedicalActions();

            $('#btn-tambah-tindakan').click(function() {
                $('#modal-tambah-tindakan').modal('show');
            });

            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                $('#dt-tindakan-bidan thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                $('#dt-tindakan-bidan').removeClassPrefix('bg-').addClass(theadColor);
            });

            // Event listener untuk pengiriman form untuk menambahkan tindakan medis baru
            $('#modal-tambah-tindakan #store-form').on('submit', function(event) {
                event.preventDefault();

                const modal = $('#modal-tambah-tindakan');
                const loadingOverlay = modal.find('.modal-loading-overlay');

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
@endsection
