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
            z-index: 99999999999999999999999;
            border-radius: .5rem;
            cursor: wait;
        }

        /* Style for table loading overlay */
        .table-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.65);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: all;
        }

        .table-loading-overlay .spinner-border {
            color: #1976d2;
            width: 3rem;
            height: 3rem;
        }

        .table-container-relative {
            position: relative;
        }
    </style>

    <div class="tab-content p-3">
        <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
            @include('pages.simrs.erm.partials.detail-pasien')
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
                            <div class="table-responsive table-container-relative" id="table-order-tindakan-wrapper"
                                style="overflow:visible;">
                                <!-- Table loading overlay -->
                                <div class="table-loading-overlay" id="table-tindakan-loading" style="display:none;">
                                    <div>
                                        <div class="spinner-border" role="status"></div>
                                    </div>
                                </div>
                                <table id="dt-tindakan-bidan" class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Dokter/Perawat</th>
                                            <th>Tindakan</th>
                                            {{-- FIX: Changed header from "Kelas" to "Poliklinik" for clarity --}}
                                            <th>Poliklinik</th>
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
                                                    data-id="{{ $registration->id }}" data-target="#modal-tambah-tindakan">
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
        </div>
    </div>
    {{-- </div> --}}
    <div class="modal fade" id="modal-tambah-tindakan" tabindex="-1" role="dialog" aria-hidden="true"
        data-id="{{ $registration->id }}">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-loading-overlay" style="display: none;">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <h4 class="ml-3">Memproses data...</h4>
                    </div>
                </div>

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Tambah Tindakan Medis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <form id="store-form" method="post" action="javascript:void(0)" autocomplete="off" novalidate>
                    @csrf
                    @method('post')
                    <input type="hidden" id="registration" value="{{ $registration->id }}">

                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="tglTindakan" class="col-sm-3 col-form-label">Tgl Tindakan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="tglTindakan" name="tgl_tindakan"
                                    placeholder="Pilih tanggal">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dokterPerawat" class="col-sm-3 col-form-label">Dokter/Perawat/Bidan</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="dokterPerawat" name="employee_id" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach ($groupedPersonnel as $department => $items)
                                        <optgroup label="{{ $department }}">
                                            @foreach ($items as $item)
                                                <option value="{{ $item['employee_id'] }}">
                                                    {{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="departement-tindakan-medis" class="col-sm-3 col-form-label">Poliklinik</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="departement-tindakan-medis" name="departement_id"
                                    style="width: 100%;">
                                    @foreach ($dTindakan as $departement)
                                        <option value="{{ $departement->id }}"
                                            data-groups="{{ $departement->grup_tindakan_medis ? json_encode($departement->grup_tindakan_medis->toArray()) : '[]' }}">
                                            {{ $departement->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kelas-tindakan-medis" class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="kelas-tindakan-medis" name="kelas"
                                    style="width: 100%;">
                                    @foreach ($kelas_rawats as $kelas)
                                        <option value="{{ $kelas->id }}">
                                            {{ $kelas->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tindakanMedis" class="col-sm-3 col-form-label">Tindakan Medis</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="tindakanMedis" name="tindakan_medis_id"
                                    style="width: 100%;">
                                    {{-- Dibuat kosong, hanya ada placeholder awal --}}
                                    <option value="" selected>Pilih Poliklinik terlebih dahulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="qty" name="qty" value="1"
                                    min="1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="diskonDokter" class="col-sm-3 col-form-label">Diskon Dokter</label>
                            <div class="col-sm-9">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="diskonDokter" name="foc">
                                    <label class="form-check-label" for="diskonDokter">Ya</label>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Tindakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin-erm')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        let dtTindakanBidan;

        // Helper to show/hide loading overlay for table
        function showTableLoading(show = true) {
            $('#table-tindakan-loading').css('display', show ? 'flex' : 'none');
        }

        // Fungsi untuk memuat dan menampilkan data tindakan medis
        function loadMedicalActions(registrationId) {
            if (!registrationId) return; // Keluar jika tidak ada ID registrasi

            showTableLoading(true);

            $.ajax({
                url: `/api/simrs/get-medical-actions/${registrationId}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        let rows = [];
                        let idx = 1;
                        data.forEach(action => {
                            rows.push({
                                no: idx++,
                                tanggal_tindakan: action.tanggal_tindakan || 'Tidak Diketahui',
                                employee: action.employee.fullname || 'Tidak Diketahui',
                                tindakan: action.tindakan_medis?.nama_tindakan ||
                                    'Tidak Diketahui',
                                // This now correctly corresponds to the "Poliklinik" header
                                kelas: action.departement?.name || 'Tidak Diketahui',
                                qty: action.qty || 0,
                                entry_by: action.user?.employee?.fullname || 'Tidak Diketahui',
                                foc: action.foc || 'Tidak Diketahui',
                                aksi: `<button class="btn btn-danger btn-sm delete-action" data-id="${action.id}">Hapus</button>`
                            });
                        });
                        // Pastikan DataTable sudah diinisialisasi sebelum digunakan
                        if (dtTindakanBidan) {
                            dtTindakanBidan.clear().rows.add(rows).draw();
                        }
                    } else {
                        // This case handles a 404 response gracefully
                        if (dtTindakanBidan) {
                            dtTindakanBidan.clear().draw();
                        }
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan yang tidak diketahui. Silakan coba lagi nanti.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                    } else if (xhr.status !== 0 && xhr.status !== 404) {
                        errorMessage =
                            `Gagal memuat tindakan medis. Status: ${xhr.status}, Pesan: ${xhr.statusText}`;
                    }
                    if (xhr.status !== 404) { // Don't show an error if no records were found
                        showErrorAlertNoRefresh(errorMessage);
                    }
                },
                complete: function() {
                    showTableLoading(false);
                }
            });
        }

        // Fungsi untuk menambahkan baris tindakan medis baru ke DataTable
        function addMedicalAction(data) {
            let rowCount = dtTindakanBidan.data().count() + 1;
            dtTindakanBidan.row.add({
                no: rowCount,
                tanggal_tindakan: data.tanggal_tindakan || 'Tidak Diketahui',
                employee: data.employee?.fullname || 'Tidak Diketahui',
                tindakan: data.tindakan_medis?.nama_tindakan || 'Tidak Diketahui',
                kelas: data.departement?.name || 'Tidak Diketahui',
                qty: data.qty || 0,
                entry_by: data.user?.employee?.fullname || 'Tidak Diketahui',
                foc: data.foc || 'Tidak Diketahui',
                aksi: `<button class="btn btn-danger btn-sm delete-action" data-id="${data.id}">Hapus</button>`
            }).draw();
        }

        $(document).ready(function() {
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
                        name: 'no'
                    },
                    {
                        data: 'tanggal_tindakan',
                        name: 'tanggal_tindakan'
                    },
                    {
                        data: 'employee',
                        name: 'employee'
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
                        name: 'aksi'
                    }
                ],
                data: []
            });

            const registrationId = "{{ $registration->id }}";
            loadMedicalActions(registrationId);

            $('.menu-layanan[data-layanan="tindakan-medis"]').on('click', function() {
                loadMedicalActions(registrationId);
            });

            $('#dt-tindakan-bidan tbody').on('click', '.delete-action', function() {
                const actionId = $(this).data('id');
                const $row = $(this).closest('tr');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tindakan medis dan tagihan terkait akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $row.css('opacity', 0.5);

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
                                if (response && response.success) {
                                    dtTindakanBidan.row($row).remove().draw(false);
                                    showSuccessAlert(response.message ||
                                        'Tindakan medis berhasil dihapus.');
                                } else {
                                    showErrorAlertNoRefresh(response.message ||
                                        'Gagal menghapus tindakan medis dari server.'
                                    );
                                    $row.css('opacity', 1);
                                }
                            },
                            error: function(xhr) {
                                const errorMessage = xhr.responseJSON?.message ||
                                    'Terjadi kesalahan pada server. Silakan coba lagi.';
                                showErrorAlertNoRefresh(errorMessage);
                                $row.css('opacity', 1);
                            }
                        });
                    }
                });
            });

            let today = new Date();
            let day = String(today.getDate()).padStart(2, '0');
            let month = String(today.getMonth() + 1).padStart(2, '0');
            let year = today.getFullYear();
            let formattedDate = `${day}-${month}-${year}`;
            $('#tglTindakan').val(formattedDate).datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });

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

            $('#modal-tambah-tindakan #store-form').on('submit', function(event) {
                event.preventDefault();
                const modal = $('#modal-tambah-tindakan');
                const loadingOverlay = modal.find('.modal-loading-overlay');
                loadingOverlay.show();

                const formData = {
                    tanggal_tindakan: $('#tglTindakan').val(),
                    registration_id: registrationId,
                    employee_id: $('#dokterPerawat').val(),
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
                        if (xhr.status === 422) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
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
                loadingOverlay.show();

                $('#store-form')[0].reset();
                $('#tglTindakan').val(formattedDate);
                $('#store-form select').val(null).trigger('change');

                $('#store-form #dokterPerawat, #store-form #departement-tindakan-medis, #store-form #kelas-tindakan-medis, #store-form #tindakanMedis')
                    .select2({
                        dropdownParent: $('#modal-tambah-tindakan')
                    });

                $.ajax({
                    url: `/api/simrs/get-registrasi-data/${registrationId}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            if (data.doctor_employee_id) {
                                $('#dokterPerawat').val(data.doctor_employee_id).trigger(
                                    'change');
                            }
                            $('#kelas-tindakan-medis').val(data.kelas_id).trigger('change');
                            $('#departement-tindakan-medis').val(data.departement_id).trigger(
                                'change');
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
            });
        });
    </script>
@endsection
