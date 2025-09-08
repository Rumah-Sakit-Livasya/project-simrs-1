@extends('inc.layout')
@section('title', 'Daftar Persalinan Pasien')
@section('content')
    <style>
        /* ========================================================== */
        /* ==                CSS LENGKAP & FINAL                   == */
        /* ========================================================== */

        /* CSS Utama */
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 600px;
        }

        .modal-md {
            max-width: 95% !important;
        }

        /* .select2-dropdown {
                                                                                                                                                                                z-index: 1060 !important;
                                                                                                                                                                            }

                                                                                                                                                                            .swal2-container {
                                                                                                                                                                                z-index: 99999 !important;
                                                                                                                                                                            }

                                                                                                                                                                            /*
                                                                                                                                                                                     * [TAMBAHKAN INI - SOLUSI UNTUK MASALAH POSISI]
                                                                                                                                                                                     * Membuat container Select2 di dalam modal menjadi titik referensi
                                                                                                                                                                                     * untuk posisi dropdown-nya.
                                                                                                                                                                                    */
        /* #modal-data-bayi .select2-container {
                                                                                                                                                                            position: relative;
                                                                                                                                                                        } */


        /* [PERBAIKAN] Mengatur z-index Select2 di filter panel agar tidak menembus modal */

        /* [PERBAIKAN] Mengatur z-index SweetAlert agar muncul di atas semua modal */
        .swal2-container {
            z-index: 99999 !important;
        }

        /* CSS Child Row (Details Control) */
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
        table.dataTable thead .sorting_desc:after {
            display: none !important;
        }

        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* CSS Modal & Form */
        .required-label::after {
            content: " *";
            color: red;
        }

        .form-section-header {
            color: white;
            padding: 8px 15px;
            margin-top: 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1rem;
        }

        #bayi-form-container .form-section-header:first-child {
            margin-top: 0;
        }

        /* CSS Grid Tindakan Persalinan */
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
            border-color: #007bff;
        }

        .tindakan-item input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .tindakan-item label {
            margin: 0;
            cursor: pointer;
            font-weight: normal;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Panel Filter -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter <span class="fw-300"><i>Persalinan</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('persalinan.index') }}" method="get">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tgl VK Awal</label>
                                        <div class="input-group"><input type="text" class="form-control datepicker"
                                                name="tgl_vk_awal" value="{{ request('tgl_vk_awal') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Tgl VK Akhir</label>
                                        <div class="input-group"><input type="text" class="form-control datepicker"
                                                name="tgl_vk_akhir" value="{{ request('tgl_vk_akhir') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>No. RM</label>
                                        <input type="text" class="form-control" name="no_rm"
                                            placeholder="Masukkan No. RM Pasien" value="{{ request('no_rm') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Nama Pasien</label>
                                        <input type="text" class="form-control" name="nama_pasien"
                                            placeholder="Masukkan Nama Pasien" value="{{ request('nama_pasien') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Status Registrasi</label>
                                        <select class="form-control select2" name="status_registrasi">
                                            <option value="">Semua Status</option>
                                            <option value="Aktif"
                                                {{ request('status_registrasi') == 'Aktif' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="Tutup Kunjungan"
                                                {{ request('status_registrasi') == 'Tutup Kunjungan' ? 'selected' : '' }}>
                                                Tutup Kunjungan
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row justify-content-end mt-2">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600"><span
                                                class="fal fa-search mr-1"></span> Cari</button>
                                        <a href="{{ route('persalinan.index') }}" class="btn bg-secondary-600"><span
                                                class="fal fa-undo mr-1"></span> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Tabel Utama -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar <span class="fw-300"><i>Persalinan</i></span></h2>
                        <div class="panel-toolbar">
                            @if (request()->hasAny(['tgl_vk_awal', 'tgl_vk_akhir', 'no_rm', 'nama_pasien', 'status_registrasi']))
                                <span class="badge bg-primary-600 badge-info p-2">
                                    Filter Aktif:
                                    @php $isFirst = true; @endphp
                                    @if (request('tgl_vk_awal') && request('tgl_vk_akhir'))
                                        Tgl VK: {{ request('tgl_vk_awal') }} s/d {{ request('tgl_vk_akhir') }}
                                        @php $isFirst = false; @endphp
                                    @endif
                                    @if (request('no_rm'))
                                        {{ !$isFirst ? ' | ' : '' }} No. RM: {{ request('no_rm') }} @php $isFirst = false; @endphp
                                    @endif
                                    @if (request('nama_pasien'))
                                        {{ !$isFirst ? ' | ' : '' }} Nama: {{ request('nama_pasien') }} @php $isFirst = false; @endphp
                                    @endif
                                    @if (request('status_registrasi'))
                                        {{ !$isFirst ? ' | ' : '' }} Status Reg: {{ request('status_registrasi') }}
                                        @php $isFirst = false; @endphp
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true"><i class="fal fa-times"></i></span></button>
                                    <strong>Sukses!</strong> {{ session('success') }}
                                </div>
                            @endif

                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 10px;"></th>
                                        <th>Tgl Order</th>
                                        <th>No Reg</th>
                                        <th>Pasien</th>
                                        <th>Tindakan Utama</th>
                                        <th>Kelas</th>
                                        <th>Dokter/Bidan</th>
                                        <th>User Input</th>
                                        <th>Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr data-id="{{ $order->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="details-control"><i class="fal fa-chevron-up"></i></td>
                                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                {{ optional($order->registration)->registration_number }} <br>
                                                <span
                                                    class="badge badge-info">{{ optional($order->registration)->status }}</span>
                                            </td>
                                            <td>
                                                {{ optional(optional($order->registration)->patient)->name }}<br>
                                                <small class="text-muted">RM:
                                                    {{ optional(optional($order->registration)->patient)->medical_record_number }}</small>
                                            </td>
                                            <td>{{ optional($order->persalinan)->nama_persalinan ?: '-' }}</td>
                                            <td>{{ optional($order->kelasRawat)->kelas ?: '-' }}</td>
                                            <td>{{ optional(optional($order->dokterBidan)->employee)->fullname ?: '-' }}
                                            </td>
                                            <td>{{ optional($order->user)->name ?: '-' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-primary btn-tambah-order"
                                                        data-registration-id="{{ $order->registration_id }}"
                                                        data-toggle="tooltip" title="Tambah Order Baru">
                                                        <i class="fal fa-plus"></i>
                                                    </button>
                                                    <a href="{{ route('bayi.popup', $order->id) }}" class="btn btn-info"
                                                        data-toggle="tooltip" title="Data Bayi"
                                                        onclick="openPopup(this.href); return false;">
                                                        <i class="fal fa-user-plus"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-delete"
                                                        data-url="{{ route('persalinan.destroy', $order->id) }}"
                                                        data-toggle="tooltip" title="Hapus Order">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div id="child-row-template" style="display: none;">
                                <div class="child-row-content">
                                    <h6 class="mb-3"><strong>Detail Order: <span
                                                class="order-placeholder"></span></strong></h6>
                                    <table class="child-table table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Informasi</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody class="detail-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- resources/views/pages/simrs/persalinan/partials/modal_data_bayi.blade.php -->



    <!-- Meng-include semua modal dari file partials -->
    @include('pages.simrs.persalinan.partials.modal_tambah_order')
    @include('pages.simrs.bayi.modal_data_bayi')
    @include('pages.simrs.bayi.modal_pilih_kamar')
    <!-- [BARU] MODAL UNT.bayiI -->

@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>

    <script>
        function openPopup(url) {
            const width = 1400;
            const height = 550;
            const left = (screen.width / 2) - (width / 2);
            const top = (screen.height / 2) - (height / 2);
            const windowFeatures = `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`;
            window.open(url, 'bayiPopup', windowFeatures);
        }
        $(document).ready(function() {
            // =====================================================================
            // BAGIAN 0: SETUP GLOBAL & INISIALISASI PLUGIN
            // =====================================================================

            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Inisialisasi Select2 untuk panel filter
            $('.select2').select2({
                dropdownCssClass: "move-up"
            });

            // Inisialisasi Select2 untuk modal tambah order
            $('.select2-modal').select2({
                dropdownParent: $('#modal-order-vk')
            });

            // Inisialisasi Tooltip
            $('[data-toggle="tooltip"]').tooltip();


            // =====================================================================
            // BAGIAN 1: FUNGSI HALAMAN UTAMA (DataTable, Child Row, Modal Order)
            // =====================================================================

            // Data detail untuk Child Row
            const allDetails = {!! json_encode(
                $orders->mapWithKeys(function ($order) {
                    return [
                        $order->id => [
                            'tgl_rencana' => $order->tgl_persalinan
                                ? \Carbon\Carbon::parse($order->tgl_persalinan)->translatedFormat('d F Y H:i')
                                : '-',
                            'dokter_bidan' => optional(optional($order->dokterBidan)->employee)->fullname ?: '-',
                            'asisten_operator' => optional(optional($order->asistenOperator)->employee)->fullname ?: '-',
                            'dokter_resusitator' => optional(optional($order->dokterResusitator)->employee)->fullname ?: '-',
                            'dokter_anestesi' => optional(optional($order->dokterAnestesi)->employee)->fullname ?: '-',
                            'asisten_anestesi' => optional(optional($order->asistenAnestesi)->employee)->fullname ?: '-',
                            'dokter_umum' => optional(optional($order->dokterUmum)->employee)->fullname ?: '-',
                            'kelas' => optional($order->kelasRawat)->kelas ?: '-',
                            'tipe' => optional($order->tipePersalinan)->tipe ?: '-',
                            'kategori' => optional($order->kategori)->nama ?: '-',
                            'melahirkan_bayi' => $order->melahirkan_bayi ? 'Ya' : 'Tidak',
                            'user_input' => optional($order->user)->name ?: '-',
                        ],
                    ];
                }),
            ) !!};

            // Inisialisasi DataTable Utama
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 20,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ /* Tombol export jika ada */ ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 9]
                }]
            });

            // Fungsi untuk format Child Row
            function formatChildRow(orderId, details) {
                var template = $('#child-row-template').clone();
                template.find('.order-placeholder').text('Order ID: ' + orderId);
                var tbody = template.find('.detail-tbody');
                tbody.empty();
                if (details) {
                    var rows = [
                        ['Tanggal Rencana', details.tgl_rencana],
                        ['Dokter/Bidan Operator', details.dokter_bidan],
                        ['Asisten Operator', details.asisten_operator],
                        ['Dokter Umum', details.dokter_umum],
                        ['Kelas Rawat', details.kelas],
                        ['Tipe', details.tipe],
                        ['Melahirkan Bayi', details.melahirkan_bayi],
                    ];
                    rows.forEach(row => tbody.append(`<tr><td>${row[0]}</td><td>${row[1]}</td></tr>`));
                } else {
                    tbody.append(
                        '<tr><td colspan="2" class="text-center text-muted">Tidak ada detail data.</td></tr>');
                }
                return template.html();
            }

            // Event handler untuk membuka/menutup Child Row
            $('#dt-basic-example tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('dt-hasChild');
                } else {
                    var orderId = tr.data('id');
                    var details = allDetails[orderId] || null;
                    row.child(formatChildRow(orderId, details)).show();
                    tr.addClass('dt-hasChild');
                }
            });

            // Logika Modal Tambah Order
            const populateDropdown = (selector, data, placeholder) => {
                const $select = $(selector);
                $select.empty().append(`<option value="">-- ${placeholder} --</option>`);
                if (Array.isArray(data)) data.forEach(item => $select.append(new Option(item.text, item.id)));
                $select.trigger('change.select2');
            };

            const loadMasterData = (registrationId) => $.get(`/simrs/persalinan/master-data/${registrationId}`);

            $('#dt-basic-example').on('click', '.btn-tambah-order', function() {
                const registrationId = $(this).data('registration-id');
                $('#form-order-vk')[0].reset();
                $('#order_vk_id').val('');
                $('#selected_registration_id').val(registrationId);
                $('.select2-modal').val(null).trigger('change');
                $('#modalOrderVKLabel').text('Input Order Persalinan Baru');
                $('#vk-step-1').removeClass('d-none');
                $('#vk-step-2').addClass('d-none');
                $('#btn-vk-lanjut').show();
                $('#btn-vk-kembali, #btn-simpan-order-vk').hide();
                loadMasterData(registrationId).done(data => {
                    $('#patient-info').html(
                        `${data.registration.patient_name} <br><small>No Reg: ${data.registration.registration_number}</small>`
                    );
                    populateDropdown('select[name="kelas_rawat_id"]', data.kelas_rawat,
                        'Pilih Kelas');
                    populateDropdown('select[name="dokter_bidan_operator_id"]', data.doctors,
                        'Pilih Dokter/Bidan');
                    populateDropdown('select[name="asisten_operator_id"]', data.doctors,
                        'Pilih Asisten');
                    populateDropdown('select[name="dokter_resusitator_id"]', data.doctors,
                        'Pilih Dokter');
                    populateDropdown('select[name="dokter_anestesi_id"]', data.doctors,
                        'Pilih Dokter');
                    populateDropdown('select[name="asisten_anestesi_id"]', data.doctors,
                        'Pilih Asisten');
                    populateDropdown('select[name="dokter_umum_id"]', data.doctors, 'Pilih Dokter');
                    populateDropdown('select[name="kategori_id"]', data.kategori, 'Pilih Kategori');
                    populateDropdown('select[name="tipe_penggunaan_id"]', data.tipe, 'Pilih Tipe');
                    let tindakanHtml = data.tindakan.map(item =>
                        `<div class="tindakan-item"><input type="radio" name="tindakan_id" id="tindakan-${item.id}" value="${item.id}" required><label for="tindakan-${item.id}">${item.text}</label></div>`
                    ).join('');
                    $('#tindakan-grid-container').html(tindakanHtml);
                    $('#modal-order-vk').modal('show');
                }).fail(() => Swal.fire('Error', 'Gagal memuat data master untuk pasien ini.', 'error'));
            });

            $('#btn-vk-lanjut').on('click', function() {
                let isValid = true;
                $('#vk-step-1 [required]').each(function() {
                    const $field = $(this);
                    let isFieldInvalid = ($field.is(':radio')) ? !$(
                        `input[name="${$field.attr('name')}"]:checked`).val() : !$field.val();
                    if (isFieldInvalid) {
                        isValid = false;
                        $field.closest('.form-group').find('label').addClass('text-danger');
                        if ($field.hasClass('select2-modal')) $field.next('.select2-container')
                            .find('.select2-selection').css('border-color', '#d9534f');
                    } else {
                        $field.closest('.form-group').find('label').removeClass('text-danger');
                        if ($field.hasClass('select2-modal')) $field.next('.select2-container')
                            .find('.select2-selection').css('border-color', '#ced4da');
                    }
                });
                if (!isValid) {
                    Swal.fire('Peringatan', 'Harap lengkapi semua field yang ditandai wajib (*)',
                        'warning');
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
                if ($('input[name="tindakan_id"]:checked').length === 0) {
                    Swal.fire('Peringatan', 'Anda harus memilih satu tindakan utama.', 'warning');
                    return;
                }
                const $button = $(this);
                $button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
                $.ajax({
                    url: "{{ route('persalinan.store') }}",
                    type: 'POST',
                    data: $('#form-order-vk').serialize(),
                    success: (response) => {
                        $('#modal-order-vk').modal('hide');
                        Swal.fire('Berhasil', response.message, 'success').then(() => window
                            .location.reload());
                    },
                    error: (xhr) => {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '<ul>';
                        $.each(errors, (key, value) => {
                            errorMsg += `<li>${value[0]}</li>`;
                        });
                        errorMsg += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Validasi',
                            html: errorMsg
                        });
                    },
                    complete: () => $button.prop('disabled', false).html(
                        '<i class="fas fa-save mr-1"></i>Simpan')
                });
            });

            $('#dt-basic-example').on('click', '.btn-delete', function() {
                const deleteUrl = $(this).data('url');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Yakin ingin menghapus order persalinan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            success: (response) => Swal.fire('Berhasil', response.message,
                                'success').then(() => window.location.reload()),
                            error: (xhr) => Swal.fire('Gagal', xhr.responseJSON?.message ||
                                'Terjadi kesalahan.', 'error')
                        });
                    }
                });
            });


            // =====================================================================
            // BAGIAN 2: MODAL DATA BAYI (LENGKAP DENGAN PERBAIKAN)
            // =====================================================================

            let dtBayi = null;

            // [PERBAIKAN] Inisialisasi Select2 untuk dokter di form bayi.
            // Dijalankan sekali saat halaman dimuat.
            $('#select-dokter-bayi').select2({
                placeholder: "Pilih atau ketik nama dokter",
                // [PENTING] Ini adalah solusi untuk masalah posisi dropdown.
                dropdownParent: $('#modal-data-bayi'),
                ajax: {
                    url: "{{ route('bayi.get_doctors') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: (data) => ({
                        results: data
                    }),
                    cache: true
                }
            });

            // Event handler saat tombol 'Data Bayi' di tabel utama diklik
            $('#dt-basic-example').on('click', '.btn-data-bayi', function() {
                const orderId = $(this).data('order-id');
                const modal = $('#modal-data-bayi');
                modal.data('order-id', orderId);

                // Reset Tampilan Modal ke kondisi awal
                $('#bayi-form-container').hide();
                $('#btn-tambah-bayi').show();
                $("#form-bayi")[0].reset();
                $('#bayi_id').val('');
                $('#select-dokter-bayi').val(null).trigger('change');

                loadDataBayi(orderId);
                modal.modal('show');
            });

            // Fungsi untuk memuat data bayi ke dalam DataTable di modal
            function loadDataBayi(orderId) {
                const table = $('#dt-bayi-table');
                if (dtBayi) {
                    dtBayi.ajax.url(`{{ url('simrs/vk/bayi/data') }}/${orderId}`).load();
                } else {
                    dtBayi = table.DataTable({
                        processing: true,
                        ajax: {
                            url: `{{ url('simrs/vk/bayi/data') }}/${orderId}`,
                            dataSrc: ''
                        },
                        columns: [{
                                data: 'no_rm',
                                defaultContent: '-'
                            },
                            {
                                data: 'nama_bayi'
                            },
                            {
                                data: 'tgl_lahir',
                                render: (data) => data ? moment(data).format('DD MMM YYYY HH:mm') : '-'
                            },
                            {
                                data: 'tgl_reg',
                                render: (data) => data ? moment(data).format('DD MMM YYYY HH:mm') : '-'
                            },
                            {
                                data: 'no_label',
                                defaultContent: '-'
                            },
                            {
                                data: 'id',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    // Membuat URL untuk print menggunakan helper url() Laravel
                                    const printUrl = `{{ url('simrs/vk/bayi') }}/${data}/print`;

                                    return `<div class="btn-group btn-group-sm">
                                                <button class="btn btn-secondary btn-print-bayi" data-url="${printUrl}" title="Cetak Akta"><i class="fal fa-print"></i></button>
                                                <button class="btn btn-warning btn-edit-bayi" data-id="${data}" title="Edit Data"><i class="fal fa-pencil"></i></button>
                                                <button class="btn btn-danger btn-delete-bayi" data-id="${data}" title="Hapus Data"><i class="fal fa-trash"></i></button>
                                            </div>`;
                                }
                            }
                        ],
                        searching: false,
                        lengthChange: false,
                        pageLength: 5,
                        language: {
                            emptyTable: "Belum ada data bayi untuk order ini."
                        }
                    });
                }
            }

            // UI Logika: Tampilkan & Sembunyikan Form Bayi
            $('#btn-tambah-bayi').on('click', function() {
                $('#form-bayi')[0].reset();
                $('#bayi_id').val('');
                $('#select-dokter-bayi').val(null).trigger('change');
                $('#bayi-form-container').slideDown();
                $(this).hide();
            });

            $('#btn-batal-bayi').on('click', function() {
                $('#bayi-form-container').slideUp(() => $('#btn-tambah-bayi').show());
            });

            // Proses Submit Form Tambah/Edit Bayi
            $('#form-bayi').on('submit', function(e) {
                e.preventDefault();
                const button = $('#btn-simpan-bayi');
                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

                const formData = $(this).serializeArray();
                formData.push({
                    name: "order_persalinan_id",
                    value: $('#modal-data-bayi').data('order-id')
                });

                $.ajax({
                    url: "{{ route('bayi.store') }}",
                    type: 'POST',
                    data: $.param(formData),
                    success: (response) => {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $('#btn-batal-bayi').click();
                        dtBayi.ajax.reload();
                    },
                    error: (xhr) => {
                        const errors = xhr.responseJSON.errors;
                        let errorMsg = '<ul>';
                        $.each(errors, (key, value) => {
                            errorMsg += `<li>${value[0]}</li>`;
                        });
                        errorMsg += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Validasi',
                            html: errorMsg
                        });
                    },
                    complete: () => button.prop('disabled', false).html('Simpan')
                });
            });

            // Event Handler untuk Tombol Edit & Hapus di Tabel Bayi
            // =====================================================================
            // ==      EVENT HANDLER UNTUK TOMBOL DI DALAM TABEL DATA BAYI        ==
            // =====================================================================
            $('#dt-bayi-table tbody')

                // Event handler untuk tombol PRINT
                .on('click', '.btn-print-bayi', function() {
                    const url = $(this).data('url');
                    const windowFeatures =
                        'width=800,height=700,menubar=no,toolbar=no,location=no,scrollbars=yes';
                    window.open(url, '_blank', windowFeatures);
                })

                // Event handler untuk tombol EDIT
                .on('click', '.btn-edit-bayi', function() {
                    const bayiId = $(this).data('id');
                    $.get(`{{ url('simrs/vk/bayi') }}/${bayiId}`, (data) => {
                        // Reset form sebelum mengisi
                        $('#form-bayi')[0].reset();
                        $('#select-dokter-bayi').val(null).trigger('change');

                        Object.keys(data).forEach(key => {
                            const field = $(`#form-bayi [name="${key}"]`);
                            if (field.is(':radio')) {
                                $(`input[name="${key}"][value="${data[key]}"]`).prop('checked',
                                    true);
                            } else if (key !== 'doctor_id') {
                                field.val(data[key]);
                            }
                        });

                        // Logika untuk menampilkan nama dokter di select2 saat edit
                        if (data.doctor_id && data.doctor && data.doctor.employee) {
                            var doctorOption = new Option(data.doctor.employee.fullname, data.doctor_id,
                                true, true);
                            $('#select-dokter-bayi').append(doctorOption).trigger('change');
                        }

                        $('#bayi_id').val(data.id);
                        $('#bayi-form-container').slideDown();
                        $('#btn-tambah-bayi').hide();
                    });
                })

                // Event handler untuk tombol DELETE
                .on('click', '.btn-delete-bayi', function() {
                    const bayiId = $(this).data('id');
                    Swal.fire({
                        title: 'Anda Yakin?',
                        text: "Data bayi akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `{{ url('simrs/vk/bayi') }}/${bayiId}`,
                                type: 'DELETE',
                                success: (response) => {
                                    Swal.fire('Dihapus!', response.message, 'success');
                                    // INI BAGIAN PENTING: Reload data tabel setelah sukses
                                    dtBayi.ajax.reload(null,
                                        false
                                    ); // false agar tidak kembali ke halaman pertama
                                },
                                error: (xhr) => {
                                    const message = xhr.responseJSON?.message ||
                                        'Gagal menghapus data.';
                                    Swal.fire('Gagal!', message, 'error');
                                }
                            });
                        }
                    });
                });


        });
    </script>
    <script>
        let dtKamarBayi = null;

        // Inisialisasi Select2 untuk filter kelas rawat di modal kamar bayi
        $('#kelas_rawat_id_bayi').select2({
            placeholder: "-- Pilih Kelas Rawat --",
            dropdownParent: $('#modal-pilih-kamar-bayi'), // Penting untuk modal bertumpuk
            ajax: {
                url: "{{ route('bayi.get_kelas_rawat') }}",
                dataType: 'json',
                processResults: (data) => ({
                    results: data
                })
            }
        });

        // Event listener saat modal kamar bayi akan ditampilkan
        $('#modal-pilih-kamar-bayi').on('show.bs.modal', function() {
            // Inisialisasi atau reload DataTable saat modal dibuka
            if (dtKamarBayi) {
                dtKamarBayi.ajax.reload();
            } else {
                dtKamarBayi = $('#dt-kamar-bayi-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('bayi.get_beds') }}',
                        data: function(d) {
                            d.kelas_rawat_id = $('#kelas_rawat_id_bayi').val();
                        }
                    },
                    columns: [{
                            data: 'ruangan',
                            name: 'room.ruangan'
                        },
                        {
                            data: 'kelas',
                            name: 'room.kelas_rawat.kelas'
                        },
                        {
                            data: 'nama_tt',
                            name: 'beds.nama_tt'
                        },
                        {
                            data: 'pasien',
                            name: 'pasien'
                        },
                        {
                            data: 'fungsi',
                            name: 'fungsi',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    pageLength: 5,
                    lengthChange: false,
                });
            }
        });

        // Event listener untuk filter kelas rawat
        $('#kelas_rawat_id_bayi').on('change', function() {
            if (dtKamarBayi) {
                dtKamarBayi.ajax.reload();
            }
        });

        // Event listener untuk tombol "Pilih" di dalam tabel kamar bayi
        $('#dt-kamar-bayi-table').on('click', '.pilih-bed-bayi', function() {
            var bedId = $(this).data('bed-id');
            var kelasId = $(this).data('kelas-id');
            var roomInfo = $(this).data('room-info');

            // Mengisi input di form bayi
            $('#bayi_kelas_kamar_input').val(roomInfo);
            $('#bayi_bed_id_input').val(bedId);
            $('#bayi_kelas_rawat_id_input').val(kelasId);

            // Menutup modal kamar
            $('#modal-pilih-kamar-bayi').modal('hide');
        });

        // [PENTING] Menangani masalah focus Select2 di dalam modal yang bertumpuk
        $('#modal-pilih-kamar-bayi').on('shown.bs.modal', function() {
            $(this).find('select').select2('open');
            $(this).find('select').select2('close');
        });

        // Di dalam file index.blade.php, di dalam <script>
    </script>
@endsection
