{{-- This partial will now contain two tables for a better workflow --}}

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
        /* Default: Panah ke atas (chevron-up), siap untuk diexpand ke bawah */
        transform: rotate(0deg);
    }

    .details-control:hover i {
        color: #2980b9;
    }

    /* Saat baris memiliki class 'dt-hasChild' (child row terbuka), putar ikon 180 derajat */
    tr.dt-hasChild td.details-control i {
        transform: rotate(180deg);
        color: #e74c3c;
    }

    td.details-control::before {
        display: none !important;
    }

    /* Styling untuk child row content */
    .child-row-content {
        padding: 15px;
        background-color: #f9f9f9;
    }

    /* Sembunyikan ikon sort bawaan DataTables */
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:after {
        display: none !important;
    }

    /* Styling untuk tabel di dalam child row */
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

    /* Efek hover untuk row */
    #dt-basic-example tbody tr:hover {
        background-color: #f8f9fa;
    }

    .select2-container {
        z-index: 9999 !important;
    }
</style>
<div class="panel">
    <div class="panel-hdr">
        <h2>
            <i class="far fa-calendar-alt mr-2 text-purple"></i>
            Order Operasi
        </h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="dt-order-operasi" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Tgl Order</th>
                            <th>Kelas</th>
                            <th>Ruangan</th>
                            <th>Tipe Operasi</th>
                            <th>Tipe Penggunaan</th>
                            <th>Diagnosa</th>
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
                                    id="btn-tambah-order-operasi" data-toggle="modal"
                                    data-target="#modal-order-operasi">
                                    <span class="fal fa-plus-circle mr-1"></span>
                                    Tambah Order
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary waves-effect waves-themed"
                                    id="btn-reload-order">
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

<!-- Section: Tindakan Operasi (Execution) -->
<div class="panel mt-4">
    <div class="panel-hdr">
        <h2>
            <i class="fas fa-heart-pulse mr-2 text-danger"></i>
            Tindakan Operasi
        </h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="dt-tindakan-operasi" class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Tindakan</th>
                            <th>Tipe Operasi</th>
                            <th>Tipe Penggunaan</th>
                            <th>Dokter</th>
                            <th>Tgl. Tindakan</th>
                            <th>User Create</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (str_contains(\Illuminate\Support\Facades\Route::currentRouteName(), 'daftar-registrasi-pasien') ||
            str_contains(url()->current(), '/daftar-registrasi-pasien/'))
        <div class="d-flex justify-content-start m-3">
            <button type="button" data-target-menu="operasi"
                class="btn btn-outline-primary px-4 shadow-sm d-flex align-items-center btn-back-to-layanan">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Menu</span>
            </button>
        </div>
    @endif
</div>
<!-- This will include the modal you create in the next step -->
@include('pages.simrs.pendaftaran.partials.modal-operasi')
<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script src="/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/painterro-1.2.3.min.js"></script>
<script>
    $(document).ready(function() {
        // =====================================================================
        // INISIALISASI SELECT2 DAN DATEPICKER
        // =====================================================================

        // Inisialisasi Select2 untuk modal
        $('#modal-order-operasi .select2').select2({
            dropdownParent: $('#modal-order-operasi'),
            width: '100%'
        });

        // Inisialisasi DatePicker untuk tanggal operasi
        $('#tgl_operasi').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: new Date()
        });

        // =====================================================================
        // INISIALISASI DATATABLES
        // =====================================================================

        // DataTable untuk Order Operasi
        var orderOperasiTable = $('#dt-order-operasi').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('operasi.order.data', $registration->id) }}",
                type: 'GET',
                dataSrc: 'data',
                error: function(xhr, error, code) {
                    console.error('Error loading order operasi data:', error);
                }
            },
            columns: [{
                    data: 'tgl_order_formatted',
                    name: 'tgl_order'
                },
                {
                    data: 'kelas_name',
                    name: 'kelas'
                },
                {
                    data: 'ruangan_name',
                    name: 'ruangan'
                },
                {
                    data: 'kategori_operasi_name',
                    name: 'kategori_operasi'
                },
                {
                    data: 'jenis_operasi_name',
                    name: 'tipe_operasi'
                },
                {
                    data: 'diagnosa',
                    name: 'diagnosa',
                    render: function(data, type, row) {
                        return data ? (data.length > 50 ? data.substring(0, 50) + '...' :
                            data) : '-';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-xs btn-outline-danger waves-effect waves-themed btn-delete-order" data-id="${row.id}">
                                <i class="fal fa-trash"></i> Hapus
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            pageLength: 10,
            lengthChange: false,
            ordering: true,
            searching: true,
            language: {
                processing: "Memuat data...",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data order operasi",
                zeroRecords: "Tidak ditemukan data yang sesuai"
            }
        });

        // DataTable untuk Tindakan Operasi
        var tindakanOperasiTable = $('#dt-tindakan-operasi').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('operasi.prosedur.data', $registration->id) }}",
                type: 'GET',
                dataSrc: 'data',
                error: function(xhr, error, code) {
                    console.error('Error loading tindakan operasi data:', error);
                }
            },
            columns: [{
                    data: 'tindakan_nama',
                    name: 'tindakan'
                },
                {
                    data: 'tipe_operasi',
                    name: 'tipe_operasi'
                },
                {
                    data: 'kategori_operasi',
                    name: 'kategori_operasi'
                },
                {
                    data: 'dokter_operator',
                    name: 'dokter_operator'
                },
                {
                    data: 'tgl_tindakan',
                    name: 'tgl_tindakan'
                },
                {
                    data: 'user_create',
                    name: 'user_create'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        let badgeClass = data === 'Draft' ? 'badge-warning' : 'badge-success';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                }
            ],
            pageLength: 10,
            lengthChange: false,
            language: {
                processing: "Memuat data...",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data tindakan operasi"
            }
        });

        // =====================================================================
        // EVENT HANDLERS
        // =====================================================================

        // Event handler untuk tombol reload
        $('#btn-reload-order').on('click', function() {
            $(this).html('<i class="fal fa-spin fa-spinner mr-1"></i>Loading...');

            // Reload kedua tabel
            orderOperasiTable.ajax.reload(function() {
                tindakanOperasiTable.ajax.reload(function() {
                    $('#btn-reload-order').html(
                        '<span class="fal fa-sync mr-1"></span>Reload');
                });
            });
        });

        // Event handler untuk reset form ketika modal dibuka
        $('#modal-order-operasi').on('show.bs.modal', function() {
            // Reset form
            $('#form-order-operasi')[0].reset();

            // Reset Select2
            $('#form-order-operasi .select2').val(null).trigger('change');

            // Set default tanggal ke hari ini
            $('#tgl_operasi').val('{{ date('d-m-Y H:i') }}');

            // Reset button
            $('#btn-simpan-order-operasi').prop('disabled', false).html('Simpan Order');
        });

        // Event handler untuk reset form ketika modal ditutup
        $('#modal-order-operasi').on('hidden.bs.modal', function() {
            // Reset form
            $('#form-order-operasi')[0].reset();

            // Reset Select2
            $('#form-order-operasi .select2').val(null).trigger('change');

            // Reset button state
            $('#btn-simpan-order-operasi').prop('disabled', false).html('Simpan Order');
        });

        // =====================================================================
        // EVENT HANDLER UNTUK SIMPAN ORDER OPERASI (PERBAIKAN UTAMA)
        // =====================================================================
        $('#btn-simpan-order-operasi').on('click', function(e) {
            e.preventDefault();

            var button = $(this);
            var form = $('#form-order-operasi');
            // Validasi form sebelum submit
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return false;
            }

            var formData = form.serialize();

            // Disable button dan ubah text
            button.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
            );

            // AJAX Request
            $.ajax({
                url: "{{ route('operasi.order.store') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // ======================================================
                    // === PERUBAHAN UTAMA DI SINI ===
                    // ======================================================

                    // 1. Langsung tutup modal
                    $('#modal-order-operasi').modal('hide');

                    // 2. Tampilkan notifikasi sukses SETELAH modal tertutup
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });

                    // 3. Reload tabel DataTables
                    orderOperasiTable.ajax.reload(null, false);
                },
                error: function(xhr) {
                    console.error('Error response:', xhr);

                    // Re-enable button
                    button.prop('disabled', false).html('Simpan Order');

                    var errors = xhr.responseJSON?.errors;
                    var errorMsg = '';

                    if (errors) {
                        errorMsg = 'Terjadi kesalahan validasi:\n';
                        $.each(errors, function(key, value) {
                            errorMsg += '• ' + value[0] + '\n';
                        });
                    } else {
                        errorMsg = xhr.responseJSON?.message ||
                            'Gagal menyimpan data. Silakan coba lagi.';
                    }

                    // Tampilkan error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {

                    // Tombol akan di-reset oleh event 'hidden.bs.modal'
                    // jadi tidak perlu di-enable secara eksplisit di sini
                    // kecuali jika terjadi error
                    if (!this.success) { // Hanya re-enable jika ajax GAGAL
                        button.prop('disabled', false).html('Simpan Order');
                    }
                }
            });
        });


        // Event handler untuk reset form ketika modal ditutup (TETAP DIPERTAHANKAN)
        // Ini akan dijalankan secara otomatis setiap kali modal ditutup, baik manual maupun via script
        $('#modal-order-operasi').on('hidden.bs.modal', function() {
            $('#form-order-operasi')[0].reset();
            $('#form-order-operasi .select2').val(null).trigger('change');
            $('#btn-simpan-order-operasi').prop('disabled', false).html('Simpan Order');
        });

        // Event handler untuk delete order (Sudah benar dari jawaban sebelumnya)
        $('#dt-order-operasi').on('click', '.btn-delete-order', function() {
            var orderId = $(this).data('id');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus order operasi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading pada tombol
                    var deleteBtn = row.find('.btn-delete-order');
                    deleteBtn.html('<i class="fal fa-spin fa-spinner"></i>').prop('disabled',
                        true);

                    let urlTemplate = "{{ route('operasi.order.delete', ['order' => ':id']) }}";
                    let deleteUrl = urlTemplate.replace(':id', orderId);

                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Order berhasil dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Reload tabel
                            orderOperasiTable.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            let errorMessage = xhr.responseJSON ? xhr.responseJSON
                                .message : 'Terjadi kesalahan saat menghapus data.';
                            Swal.fire('Gagal!', errorMessage, 'error');
                        }
                    });
                }
            });
        });

        // =====================================================================
        // EVENT HANDLER UNTUK EDIT ORDER (BONUS)
        // =====================================================================
        $('#dt-order-operasi').on('click', '.btn-edit-order', function() {
            var orderId = $(this).data('id');

            // Implementasi edit bisa ditambahkan di sini
            Swal.fire({
                icon: 'info',
                title: 'Fitur Edit',
                text: 'Fitur edit order akan segera tersedia.',
                showConfirmButton: true
            });
        });

        // =====================================================================
        // UTILITY FUNCTIONS
        // =====================================================================

        // Function untuk reload semua tabel
        window.reloadOrderTables = function() {
            orderOperasiTable.ajax.reload();
            tindakanOperasiTable.ajax.reload();
        };

        // Function untuk format tanggal (jika diperlukan)
        window.formatDate = function(dateString) {
            if (!dateString) return '-';

            var date = new Date(dateString);
            var options = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            return date.toLocaleDateString('id-ID', options);
        };

        console.log('Order Operasi JavaScript initialized successfully');
    });
</script>
