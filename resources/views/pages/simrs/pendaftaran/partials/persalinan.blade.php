<style>
    table {
        font-size: 8pt !important;
    }

    .modal-lg {
        max-width: 800px;
    }

    .details-control {
        cursor: pointer;
        text-align: center;
        width: 30px;
        padding: 8px !important;
    }

    .details-control i {
        transition: transform 0.3s ease, color 0.3s ease;
        color: #3498db;
        font-size: 16px;
        transform: rotate(0deg);
    }

    .details-control:hover i {
        color: #2980b9;
    }

    tr.dt-hasChild td.details-control i {
        transform: rotate(180deg);
        color: #e74c3c;
    }

    td.details-control::before {
        display: none !important;
    }

    .child-row-content {
        padding: 15px;
        background-color: #f9f9f9;
    }

    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:after {
        display: none !important;
    }

    .child-table {
        width: 98% !important;
        margin: 10px auto !important;
        border-radius: 4px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .child-table thead th {
        background-color: #021d39;
        color: white;
        font-size: 12px;
        padding: 8px !important;
    }

    .child-table tbody td {
        padding: 8px !important;
        font-size: 12px;
        background-color: white;
    }

    #dt-order-persalinan tbody tr:hover {
        background-color: #f8f9fa;
    }

    .select2-container {
        z-index: 9999 !important;
    }

    /* Style untuk step wizard */
    .wizard-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .wizard-step {
        display: flex;
        align-items: center;
        color: #6c757d;
    }

    .wizard-step.active {
        color: #007bff;
        font-weight: bold;
    }

    .wizard-step.completed {
        color: #28a745;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        border: 2px solid currentColor;
        background: white;
    }

    .wizard-step.active .step-number,
    .wizard-step.completed .step-number {
        background: currentColor;
        color: white;
    }

    .wizard-connector {
        width: 50px;
        height: 2px;
        background: #dee2e6;
        margin: 0 15px;
    }

    .wizard-step.completed+.wizard-connector {
        background: #28a745;
    }

    .section-header {
        background: linear-gradient(135deg, #6f42c1, #8e44ad);
        color: white;
        padding: 10px 15px;
        margin: 20px -15px 15px -15px;
        border-radius: 5px;
        font-weight: 600;
    }

    .tindakan-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 10px;
    }

    .tindakan-item {
        display: flex;
        align-items: center;
        padding: 8px;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        transition: all 0.2s;
    }

    .tindakan-item:hover {
        background-color: #f8f9fa;
        border-color: #6f42c1;
    }

    .tindakan-item input[type="radio"] {
        margin-right: 8px;
        transform: scale(1.2);
    }

    .tindakan-item label {
        margin: 0;
        cursor: pointer;
        font-weight: normal;
        color: #555;
    }

    .btn-primary {
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
    }

    .required {
        color: #dc3545;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        line-height: 36px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="panel">
    <div class="panel-hdr">
        <h2>
            <i class="fas fa-baby mr-2 text-pink"></i>
            Order Persalinan (VK)
        </h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="dt-order-persalinan" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Tgl Persalinan</th>
                            <th>Pasien</th>
                            <th>Tindakan</th>
                            <th>Tipe Persalinan</th>
                            <th>Kategori</th>
                            <th>Dokter/Bidan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by AJAX -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-themed"
                                    id="btn-tambah-order-persalinan" data-toggle="modal" data-target="#modal-order-vk"
                                    data-registration-id="{{ $registration->id ?? 0 }}">
                                    <span class="fal fa-plus-circle mr-1"></span>
                                    Tambah Order
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary waves-effect waves-themed ml-2"
                                    id="btn-reload-persalinan">
                                    <span class="fal fa-sync mr-1"></span>
                                    Reload
                                </button>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Persalinan (VK) -->
<div class="modal fade" id="modal-order-vk" tabindex="-1" role="dialog" aria-labelledby="modalOrderVKLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-order-vk">
                <input type="hidden" id="order_vk_id" name="order_vk_id">
                <input type="hidden" id="vk_registration_id" name="registration_id"
                    value="{{ $registration->id ?? 0 }}">

                <div class="modal-header bg-primary-600 text-white">
                    <h5 class="modal-title" id="modalOrderVKLabel">Input Tindakan Persalinan (VK)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Step 1: Informasi Persalinan -->
                    <div id="vk-step-1">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tgl_rencana_persalinan">Tanggal Persalinan <span
                                        class="required">*</span></label>
                                <input type="datetime-local" class="form-control" id="tgl_rencana_persalinan"
                                    name="tgl_persalinan" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kelas_rawat">Kelas Rawat <span class="required">*</span></label>
                                <select class="form-control select2" id="kelas_rawat" name="kelas_rawat_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dokter_bidan_operator">Dokter/Bidan Operator <span
                                        class="required">*</span></label>
                                <select class="form-control select2" id="dokter_bidan_operator"
                                    name="dokter_bidan_operator_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="asisten_operator">Asisten Operator</label>
                                <select class="form-control select2" id="asisten_operator" name="asisten_operator_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dokter_resusitator">Dokter Resusitator</label>
                                <select class="form-control select2" id="dokter_resusitator"
                                    name="dokter_resusitator_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dokter_anestesi">Dokter Anestesi</label>
                                <select class="form-control select2" id="dokter_anestesi" name="dokter_anestesi_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="asisten_anestesi">Asisten Anestesi</label>
                                <select class="form-control select2" id="asisten_anestesi" name="asisten_anestesi_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dokter_umum">Dokter Umum</label>
                                <select class="form-control select2" id="dokter_umum" name="dokter_umum_id">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tipe_persalinan">Tipe Penggunaan <span class="required">*</span></label>
                                <select class="form-control select2" id="tipe_persalinan" name="tipe_penggunaan_id"
                                    required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kategori">Kategori <span class="required">*</span></label>
                                <select class="form-control select2" id="kategori" name="kategori_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Melahirkan Bayi ? <span class="required">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="melahirkan_bayi"
                                        id="melahirkan_ya" value="1" required>
                                    <label class="form-check-label" for="melahirkan_ya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="melahirkan_bayi"
                                        id="melahirkan_tidak" value="0">
                                    <label class="form-check-label" for="melahirkan_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Pilih Tindakan -->
                    <div id="vk-step-2" class="d-none">
                        <div id="tindakan-grid-container" class="tindakan-grid">
                            <!-- radio tindakan akan di-load via JS -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-vk-kembali"
                        style="display:none;">Kembali</button>
                    <button type="button" class="btn btn-primary" id="btn-vk-lanjut">Lanjut</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-order-vk" style="display:none;">
                        <i class="fas fa-save mr-1"></i>Simpan Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script src="/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let dtPersalinan = null;
        let registrationId = $('#btn-tambah-order-persalinan').data('registration-id') || 0;

        function initializeDataTable() {
            if (dtPersalinan && $.fn.DataTable.isDataTable('#dt-order-persalinan')) {
                dtPersalinan.ajax.reload();
                return;
            }
            if (!registrationId || registrationId === 0) {
                $('#dt-order-persalinan tbody').html(
                    '<tr><td colspan="7" class="text-center text-muted">Registration ID tidak valid.</td></tr>'
                );
                return;
            }
            dtPersalinan = $('#dt-order-persalinan').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                lengthChange: false,
                ajax: {
                    url: `/simrs/persalinan/order-data/${registrationId}`,
                    type: 'GET',
                    error: function(xhr) {
                        Swal.fire('Error', 'Gagal memuat data tabel: ' + (xhr.responseJSON
                            ?.message || xhr.statusText), 'error');
                        $('#dt-order-persalinan tbody').html(
                            '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>'
                        );
                    }
                },
                columns: [{
                        data: 'tgl_rencana',
                        name: 'tgl_persalinan'
                    },
                    {
                        data: 'pasien',
                        name: 'registration.patient.name'
                    },
                    {
                        data: 'tindakan',
                        name: 'persalinan.nama_persalinan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tipe_persalinan',
                        name: 'tipePersalinan.tipe'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori.nama'
                    },
                    {
                        data: 'dokter_bidan',
                        name: 'dokterBidan.employee.fullname'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                language: {
                    emptyTable: "Tidak ada order persalinan untuk pendaftaran ini.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    loadingRecords: "Memuat...",
                    processing: "Proses...",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        }

        function resetForm() {
            $('#form-order-vk')[0].reset();
            $('#order_vk_id').val('');
            $('#vk_registration_id').val(registrationId);
            $('#modal-order-vk .select2').val(null).trigger('change');
            $('#kelas_rawat').val(null).trigger('change');
            $('#kelas_rawat, #dokter_bidan_operator, #asisten_operator, #dokter_resusitator, #dokter_anestesi, #asisten_anestesi, #dokter_umum, #tipe_persalinan, #kategori')
                .html('<option value="">Loading...</option>');
            $('#modal-order-vk .modal-title').text('Input Tindakan Persalinan (VK)');
            $('#vk-step-1').removeClass('d-none');
            $('#vk-step-2').addClass('d-none');
            $('#btn-vk-lanjut').show();
            $('#btn-vk-kembali, #btn-simpan-order-vk').hide();
            $('.form-group label').removeClass('text-danger');
            $('.is-invalid').removeClass('is-invalid');
        }

        function loadAndPopulateDropdowns() {
            $.ajax({
                url: `/simrs/persalinan/master-data/${registrationId}`,
                method: 'GET',
                success: function(data) {
                    populateDropdown('#kelas_rawat', data.kelas_rawat || [], 'Pilih Kelas Rawat');
                    populateDropdown('#dokter_bidan_operator', data.doctors || [],
                        'Pilih Dokter/Bidan');
                    populateDropdown('#asisten_operator', data.doctors || [],
                        'Pilih Asisten Operator');
                    populateDropdown('#dokter_resusitator', data.doctors || [],
                        'Pilih Dokter Resusitator');
                    populateDropdown('#dokter_anestesi', data.doctors || [],
                        'Pilih Dokter Anestesi');
                    populateDropdown('#asisten_anestesi', data.doctors || [],
                        'Pilih Asisten Anestesi');
                    populateDropdown('#dokter_umum', data.doctors || [], 'Pilih Dokter Umum');
                    populateDropdown('#kategori', data.kategori || [], 'Pilih Kategori');
                    populateDropdown('#tipe_persalinan', data.tipe || [], 'Pilih Tipe Penggunaan');

                    let tindakanHtml = '';
                    if (data.tindakan && data.tindakan.length > 0) {
                        data.tindakan.forEach(item => {
                            tindakanHtml +=
                                `<div class="tindakan-item"><input type="radio" name="tindakan_id" id="tindakan-${item.id}" value="${item.id}" required><label for="tindakan-${item.id}">${item.text}</label></div>`;
                        });
                    } else {
                        tindakanHtml = '<p class="text-muted">Tidak ada tindakan tersedia.</p>';
                    }
                    $('#tindakan-grid-container').html(tindakanHtml);
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal memuat data master: ' + (xhr.responseJSON?.message ||
                        xhr.statusText), 'error');
                }
            });
        }

        function populateDropdown(selector, data, placeholder) {
            const $select = $(selector);
            $select.empty().append(`<option value="">${placeholder}</option>`);
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    $select.append(`<option value="${item.id}">${item.text}</option>`);
                });
            }
            if ($select.hasClass('select2')) {
                $select.select2({
                    dropdownParent: $select.closest('.modal'),
                    width: '100%'
                });
            }
        }

        $('#btn-tambah-order-persalinan').on('click', function() {
            resetForm();
            $('#modal-order-vk').modal('show');
        });

        $('#modal-order-vk').on('shown.bs.modal', function() {
            loadAndPopulateDropdowns();
        });

        $('#btn-reload-persalinan').on('click', function() {
            $(this).html('<i class="fal fa-spin fa-spinner mr-1"></i>Loading...');
            if (dtPersalinan) {
                dtPersalinan.ajax.reload(function() {
                    $('#btn-reload-persalinan').html(
                        '<span class="fal fa-sync mr-1"></span>Reload');
                });
            }
        });

        $('#btn-vk-lanjut').on('click', function() {
            let isValid = true;
            $('#vk-step-1 [required]').each(function() {
                const $field = $(this);
                let isFieldInvalid = false;

                if ($field.is(':radio')) {
                    if (!$(`input[name="${$field.attr('name')}"]:checked`).val()) {
                        isFieldInvalid = true;
                    }
                } else {
                    if (!$field.val()) {
                        isFieldInvalid = true;
                    }
                }

                if (isFieldInvalid) {
                    isValid = false;
                    $field.closest('.form-group').find('label').addClass('text-danger');
                } else {
                    $field.closest('.form-group').find('label').removeClass('text-danger');
                }
            });

            if (!isValid) {
                Swal.fire('Peringatan', 'Harap lengkapi semua field wajib (*)', 'warning');
                return;
            }

            $('#vk-step-1').addClass('d-none');
            $('#vk-step-2').removeClass('d-none');
            $(this).hide();
            $('#btn-vk-kembali, #btn-simpan-order-vk').show();
        });

        $('#btn-vk-kembali').on('click', function() {
            $('#vk-step-2').addClass('d-none');
            $('#vk-step-1').removeClass('d-none');
            $(this).hide();
            $('#btn-simpan-order-vk').hide();
            $('#btn-vk-lanjut').show();
        });

        $('#btn-simpan-order-vk').on('click', function() {
            const $button = $(this);
            if ($('input[name="tindakan_id"]:checked').length === 0) {
                Swal.fire('Peringatan', 'Anda harus memilih satu tindakan', 'warning');
                return;
            }

            $button.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
            $.ajax({
                url: "{{ route('persalinan.store') }}",
                type: 'POST',
                data: new FormData($('#form-order-vk')[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#modal-order-vk').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if (dtPersalinan) dtPersalinan.ajax.reload(null, false);
                },
                error: function(xhr) {
                    let errorMsg = 'Gagal menyimpan data.';
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        errorMsg = '<ul>';
                        $.each(errors, function(key, value) {
                            errorMsg += `<li>${value[0]}</li>`;
                        });
                        errorMsg += '</ul>';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMsg
                    });
                },
                complete: function() {
                    $button.prop('disabled', false).html(
                        '<i class="fas fa-save mr-1"></i>Simpan Order');
                }
            });
        });

        $('#dt-order-persalinan').on('click', '.btn-delete-persalinan', function() {
            const orderId = $(this).data('id');
            Swal.fire({
                title: 'Anda Yakin?',
                text: 'Data order persalinan akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/simrs/persalinan/destroy/${orderId}`,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire('Dihapus!', response.message, 'success');
                            if (dtPersalinan) dtPersalinan.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', xhr.responseJSON?.message ||
                                'Gagal menghapus data.', 'error');
                        }
                    });
                }
            });
        });

        $('#modal-order-vk').on('hidden.bs.modal', function() {
            resetForm();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        initializeDataTable();
    });
</script>
