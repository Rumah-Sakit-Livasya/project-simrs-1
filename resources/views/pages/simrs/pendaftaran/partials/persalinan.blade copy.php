<style>
    table {
        font-size: 6pt !important;
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

    .tindakan-item input[type="checkbox"] {
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

    /* Style untuk select2 container */
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
                            <th>Tgl Order</th>
                            <th>Tgl Rencana</th>
                            <th>Pasien</th>
                            <th>Tindakan</th>
                            <th>Tipe Persalinan</th>
                            <th>Kategori</th>
                            <th>Dokter/Bidan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by AJAX -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">
                                <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-themed"
                                    id="btn-tambah-order-persalinan" data-toggle="modal" data-target="#modal-order-vk"
                                    data-registration-id="{{ $registration->id ?? 0 }}">
                                    <span class="fal fa-plus-circle mr-1"></span>
                                    Tambah Order
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary waves-effect waves-themed"
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
                    <!-- Debug info -->

                    <!-- Step 1: Informasi Persalinan -->
                    <div id="vk-step-1">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tgl_rencana_persalinan">Tanggal Persalinan *</label>
                                <input type="datetime-local" class="form-control" id="tgl_rencana_persalinan"
                                    name="tgl_persalinan" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kelas_rawat">Kelas Rawat *</label>
                                <select class="form-control" id="kelas_rawat" name="kelas_rawat_id" required>
                                    <option value="">Loading...</option>
                                </select>
                                <small class="text-muted">Total options: <span id="kelas_rawat-count">0</span></small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dokter_bidan_operator">Dokter/Bidan Operator *</label>
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
                                <label for="tipe_persalinan">Tipe Penggunaan *</label>
                                <select class="form-control select2" id="tipe_persalinan" name="tipe_penggunaan_id"
                                    required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kategori">Kategori *</label>
                                <select class="form-control select2" id="kategori" name="kategori_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Melahirkan Bayi ? *</label>
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
                            <!-- Checkbox tindakan akan di-load via JS -->
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

<!-- Modal Debug untuk Test Dropdown Kelas Rawat -->
<div class="modal fade" id="modal-debug-kelas_rawat" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Debug Kelas Rawat Dropdown</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="debug-kelas_rawat">Kelas Rawat (Native Dropdown)</label>
                    <select class="form-control" id="debug-kelas_rawat">
                        <option value="">Loading...</option>
                    </select>
                    <small class="text-muted">Total options: <span id="debug-kelas_rawat-count">0</span></small>
                </div>

                <div class="mt-3">
                    <h6>Data dari API:</h6>
                    <pre id="debug-kelas_rawat-json"
                        style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px; max-height: 200px; overflow-y: auto;"></pre>
                </div>

                <div class="mt-3">
                    <h6>HTML Dropdown:</h6>
                    <pre id="debug-kelas_rawat-html"
                        style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px; max-height: 150px; overflow-y: auto;"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-test-load-kelas_rawat">Test Load Data</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script src="/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {
        // Setup CSRF token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi variabel global
        let dtPersalinan = null;
        let masterDataCache = null;
        let registrationId = $('#btn-tambah-order-persalinan').data('registration-id') || 47;
        console.log('Using Registration ID:', registrationId);

        // Fungsi untuk memuat DataTable
        function initializeDataTable() {
            if (dtPersalinan && $.fn.DataTable.isDataTable('#dt-order-persalinan')) {
                dtPersalinan.ajax.reload();
                return;
            }

            if (!registrationId || registrationId === 0) {
                console.warn('Registration ID tidak valid, menggunakan fallback:', registrationId);
                $('#dt-order-persalinan tbody').html(
                    '<tr><td colspan="9" class="text-center text-muted">Registration ID tidak valid.</td></tr>'
                );
                return;
            }

            dtPersalinan = $('#dt-order-persalinan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `/simrs/persalinan/order-data/${registrationId}`,
                    type: 'GET',
                    error: function(xhr) {
                        console.error('Error loading DataTable:', xhr);
                        $('#dt-order-persalinan_processing').hide();
                        Swal.fire('Error', 'Gagal memuat data tabel: ' + (xhr.responseJSON
                            ?.message || xhr.statusText), 'error');
                        $('#dt-order-persalinan tbody').html(
                            '<tr><td colspan="9" class="text-center text-danger">Gagal memuat data.</td></tr>'
                        );
                    }
                },
                columns: [{
                        data: 'tgl_order',
                        name: 'created_at',
                        title: 'Tgl Order'
                    },
                    {
                        data: 'tgl_rencana',
                        name: 'tgl_persalinan',
                        title: 'Tgl Rencana'
                    },
                    {
                        data: 'pasien',
                        name: 'registration.patient.name',
                        title: 'Pasien'
                    },
                    {
                        data: 'tindakan',
                        name: 'tindakan',
                        title: 'Tindakan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tipe_persalinan',
                        name: 'tipePersalinan.tipe',
                        title: 'Tipe Persalinan'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori.nama',
                        title: 'Kategori'
                    },
                    {
                        data: 'dokter_bidan',
                        name: 'dokterBidan.name',
                        title: 'Dokter/Bidan'
                    },
                    {
                        data: 'status',
                        name: 'melahirkan_bayi',
                        title: 'Status'
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
                lengthMenu: [
                    [5, 10, 25, 50],
                    [5, 10, 25, 50]
                ],
                language: {
                    emptyTable: "Tidak ada order persalinan untuk registration ini.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    loadingRecords: "Memuat...",
                    processing: "Proses...",
                    search: "Cari:",
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

        // Fungsi untuk reset form
        function resetForm() {
            $('#form-order-vk')[0].reset();
            $('#order_vk_id').val('');
            $('#vk_registration_id').val(registrationId);

            $('#kelas_rawat, #dokter_bidan_operator, #asisten_operator, #dokter_resusitator, #dokter_anestesi, #asisten_anestesi, #dokter_umum, #tipe_persalinan, #kategori')
                .html('<option value="">Loading...</option>');

            $('#modal-order-vk .modal-title').text('Input Tindakan Persalinan (VK)');
            $('#vk-step-1').removeClass('d-none');
            $('#vk-step-2').addClass('d-none');
            $('#btn-vk-lanjut').show();
            $('#btn-vk-kembali, #btn-simpan-order-vk').hide();

            $('.form-group label').removeClass('text-danger');
            $('.is-invalid').removeClass('is-invalid');
            $('#debug-info').hide();
        }

        // Fungsi untuk memuat dan mengisi dropdown
        function loadAndPopulateDropdowns() {
            console.log('Loading master data for registration:', registrationId);

            $.ajax({
                url: `/simrs/persalinan/master-data/${registrationId}`,
                method: 'GET',
                success: function(data) {
                    console.log('API Response:', data);
                    masterDataCache = data;

                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                        return;
                    }

                    // Tampilkan debug info



                    // Populate dropdowns
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
                    populateDropdown('#tipe_persalinan', data.tipe || [], 'Pilih Tipe Persalinan');

                    // Populate tindakan
                    let tindakanHtml = '';
                    if (data.tindakan && Array.isArray(data.tindakan) && data.tindakan.length > 0) {
                        data.tindakan.forEach(item => {
                            tindakanHtml += `
                                <div class="tindakan-item">
                                    <input type="checkbox" name="tindakan[]" id="tindakan-${item.id}" value="${item.id}">
                                    <label for="tindakan-${item.id}">${item.text}</label>
                                </div>
                            `;
                        });
                    } else {
                        tindakanHtml = '<p class="text-muted">Tidak ada tindakan tersedia.</p>';
                    }
                    $('#tindakan-grid-container').html(tindakanHtml);

                    // Forced render untuk memastikan tampilan diperbarui
                    $('#modal-order-vk .modal-body').trigger('contentUpdated');
                },
                error: function(xhr) {
                    console.error('API Error:', xhr);
                    Swal.fire('Error', 'Gagal memuat data: ' + (xhr.responseJSON?.message || xhr
                        .statusText), 'error');
                }
            });
        }

        // Fungsi untuk mengisi dropdown dengan debug tambahan
        function populateDropdown(selector, data, placeholder) {
            const $select = $(selector);
            if (!$select.length) {
                console.error(`Elemen ${selector} tidak ditemukan di DOM`);
                Swal.fire('Error', `Elemen ${selector} tidak ditemukan`, 'error');
                return;
            }

            console.log(`Populating ${selector} with data:`, data);

            $select.empty();
            $select.append(`<option value="">${placeholder}</option>`);

            if (Array.isArray(data) && data.length > 0) {
                let validOptions = 0;
                data.forEach(item => {
                    if (item && item.id && item.text) {
                        $select.append(`<option value="${item.id}">${item.text}</option>`);
                        validOptions++;
                    } else {
                        console.warn('Invalid item skipped:', item);
                    }
                });
                console.log(`Added ${validOptions} valid options to ${selector}`);

                if (selector === '#kelas_rawat') {
                    $('#kelas_rawat-count').text(validOptions);
                    $('#debug-kelas_rawat-json').text(JSON.stringify(data, null, 2));
                    if (validOptions === 0) {
                        console.warn('No valid kelas rawat data');
                        Swal.fire('Peringatan', 'Tidak ada data Kelas Rawat yang valid', 'warning');
                    }
                }

                // Paksa render ulang setelah menambahkan option
                $select.trigger('change');
            } else {
                console.warn(`No data or invalid data for ${selector}:`, data);
                $select.append('<option value="" disabled>Tidak ada data tersedia</option>');
                if (selector === '#kelas_rawat') {
                    $('#kelas_rawat-count').text(0);
                    $('#debug-kelas_rawat-json').text('Data kosong atau tidak valid');
                    Swal.fire('Error', 'Data Kelas Rawat kosong atau tidak valid', 'error');
                }
            }

            console.log(`Final HTML for ${selector}:`, $select.html());
            // Tambahan debug untuk memeriksa perubahan DOM setelah populasi
            setTimeout(() => {
                console.log(`HTML after delay for ${selector}:`, $select.html());
            }, 100);
        }

        // Event handler untuk tombol Tambah
        $('#btn-tambah-order-persalinan').on('click', function() {
            console.log('Tambah order clicked');
            resetForm();

            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $('#modal-order-vk').one('shown.bs.modal', function() {
                loadAndPopulateDropdowns();
                Swal.close();
            }).modal('show');
        });

        // Event handler untuk tombol Reload DataTable
        $('#btn-reload-persalinan').on('click', function() {
            if (dtPersalinan) {
                dtPersalinan.ajax.reload(null, false);
            } else {
                initializeDataTable();
            }
        });

        // Event handler untuk tombol Lanjut
        $('#btn-vk-lanjut').on('click', function() {
            let isValid = true;
            $('#vk-step-1 [required]').each(function() {
                const $field = $(this);
                const value = $field.val();
                const $label = $field.closest('.form-group').find('label');

                if (!value || value.trim() === '') {
                    isValid = false;
                    $label.addClass('text-danger');
                    $field.addClass('is-invalid');
                } else {
                    $label.removeClass('text-danger');
                    $field.removeClass('is-invalid');
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

        // Event handler untuk tombol Kembali
        $('#btn-vk-kembali').on('click', function() {
            $('#vk-step-2').addClass('d-none');
            $('#vk-step-1').removeClass('d-none');
            $(this).hide();
            $('#btn-simpan-order-vk').hide();
            $('#btn-vk-lanjut').show();
        });

        // Event handler untuk tombol Simpan
        $('#btn-simpan-order-vk').on('click', function() {
            const $form = $('#form-order-vk');
            const formData = new FormData($form[0]);
            const $button = $(this);

            if ($('input[name="tindakan[]"]:checked').length === 0) {
                Swal.fire('Peringatan', 'Pilih minimal satu tindakan', 'warning');
                return;
            }

            $button.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('persalinan.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire('Berhasil', response.message || 'Order disimpan', 'success');
                    $('#modal-order-vk').modal('hide');
                    if (dtPersalinan) dtPersalinan.ajax.reload();
                },
                error: function(xhr) {
                    console.error('Save error:', xhr);
                    let errorMsg = 'Gagal menyimpan data';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                        else if (xhr.responseJSON.errors) errorMsg = Object.values(xhr
                            .responseJSON.errors).join('<br>');
                    }
                    Swal.fire('Gagal', errorMsg, 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).html(
                        '<i class="fas fa-save mr-1"></i>Simpan Order');
                }
            });
        });

        // Event handler untuk tombol Edit
        $('#dt-order-persalinan').on('click', '.btn-edit-persalinan', function() {
            const orderId = $(this).data('id');
            console.log('Edit order with ID:', orderId);
            resetForm();

            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.when(
                $.get(`/simrs/persalinan/master-data/${registrationId}`),
                $.get(`/simrs/persalinan/show/${orderId}`)
            ).done(function(masterData, orderData) {
                console.log('Master Data:', masterData[0], 'Order Data:', orderData[0]);
                const data = masterData[0];
                const order = orderData[0];

                $('#modal-order-vk .modal-title').text('Edit Order Persalinan (VK)');
                $('#order_vk_id').val(order.id);
                $('#vk_registration_id').val(order.registration_id);

                $('#tgl_rencana_persalinan').val(order.tgl_rencana_persalinan?.slice(0, 16) ||
                    '');
                $('#kelas_rawat').val(order.kelas_rawat_id || '');
                $('#dokter_bidan_operator').val(order.bidan_id || '');
                $('#asisten_operator').val(order.asisten_operator_id || '');
                $('#dokter_resusitator').val(order.dokter_resusitator_id || '');
                $('#dokter_anestesi').val(order.dokter_anestesi_id || '');
                $('#asisten_anestesi').val(order.asisten_anestesi_id || '');
                $('#dokter_umum').val(order.dokter_umum_id || '');
                $('#tipe_persalinan').val(order.tipe_persalinan_id || '');
                $('#kategori').val(order.kategori_persalinan_id || '');

                $(`#melahirkan_${order.melahirkan_bayi ? 'ya' : 'tidak'}`).prop('checked',
                    true);

                if (order.tindakan_ids) {
                    order.tindakan_ids.forEach(id => $(`#tindakan-${id}`).prop('checked',
                        true));
                }

                loadAndPopulateDropdowns();
                Swal.close();
                $('#modal-order-vk').modal('show');
            }).fail(function(xhr) {
                console.error('Load error:', xhr);
                Swal.fire('Gagal', 'Gagal memuat data order', 'error');
            });
        });

        // Event handler untuk tombol Hapus
        $('#dt-order-persalinan').on('click', '.btn-delete-persalinan', function() {
            const orderId = $(this).data('id');
            Swal.fire({
                title: 'Yakin?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/simrs/persalinan/destroy/${orderId}`,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire('Berhasil', response.message ||
                                'Data dihapus', 'success');
                            if (dtPersalinan) dtPersalinan.ajax.reload();
                        },
                        error: function(xhr) {
                            console.error('Delete error:', xhr);
                            Swal.fire('Gagal', xhr.responseJSON?.message ||
                                'Gagal menghapus', 'error');
                        }
                    });
                }
            });
        });

        // Event handler untuk modal close
        $('#modal-order-vk').on('hidden.bs.modal', function() {
            resetForm();
        });

        // Event handler untuk debugging dropdown kelas_rawat
        $('#kelas_rawat').on('change', function() {
            console.log('Kelas Rawat changed:', $(this).val(), $(this).find('option:selected').text());
        });

        // Inisialisasi DataTable
        initializeDataTable();
        console.log('Script initialized at', new Date().toLocaleString('id-ID', {
            timeZone: 'Asia/Jakarta'
        }));
    });
</script>

<!-- JavaScript untuk Modal Debug -->
<script>
    $(document).ready(function() {
        const registrationId = $('#btn-tambah-order-persalinan').data('registration-id') || 47;

        $('#btn-test-load-kelas_rawat').on('click', function() {
            console.log('Testing load with registration ID:', registrationId);
            $('#debug-kelas_rawat').html('<option value="">Loading...</option>');
            $('#debug-kelas_rawat-json').text('Loading...');
            $('#debug-kelas_rawat-html').text('Loading...');
            $('#debug-kelas_rawat-count').text('0');

            $.get(`/simrs/persalinan/master-data/${registrationId}`)
                .done(function(data) {
                    console.log('Debug data received:', data);
                    $('#debug-kelas_rawat-json').text(JSON.stringify(data, null, 2));

                    const $select = $('#debug-kelas_rawat');
                    $select.empty();
                    $select.append('<option value="">Pilih Kelas Rawat</option>');

                    let count = 0;
                    if (data.kelas_rawat && Array.isArray(data.kelas_rawat)) {
                        data.kelas_rawat.forEach(item => {
                            if (item && item.id && item.text) {
                                $select.append(
                                    `<option value="${item.id}">${item.text}</option>`);
                                count++;
                            }
                        });
                    }
                    $('#debug-kelas_rawat-count').text(count);
                    $('#debug-kelas_rawat-html').text($select.html());
                    console.log('Debug options added:', count);
                })
                .fail(function(xhr) {
                    console.error('Debug load error:', xhr);
                    $('#debug-kelas_rawat-json').text('Error: ' + (xhr.responseJSON?.message || xhr
                        .statusText));
                });
        });

        $('#modal-debug-kelas_rawat').on('shown.bs.modal', function() {
            $('#btn-test-load-kelas_rawat').click();
        });

        $('#debug-kelas_rawat').on('change', function() {
            console.log('Debug Kelas Rawat changed:', $(this).val(), $(this).find('option:selected')
                .text());
        });
    });
</script>