{{-- This partial will now contain two tables for a better workflow --}}

<style>
    table {
        font-size: 8pt !important;
    }

    .modal-lg {
        max-width: 800px;
    }

    /*
                                ====================================================================
                                CSS BARU UNTUK DETAILS CONTROL (Disamakan dengan Pertanggung Jawaban)
                                ====================================================================
                            */
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
                            <th colspan="7">
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
</div>
<!-- This will include the modal you create in the next step -->
@include('pages.simrs.pendaftaran.partials.modal-operasi')
<script src="/js/formplugins/select2/select2.bundle.js"></script>
<script src="/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/painterro-1.2.3.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#modal-order-operasi .select2').select2({
            dropdownParent: $('#modal-order-operasi')
        });

        // Inisialisasi DataTable untuk Order Operasi
        var orderOperasiTable = $('#dt-order-operasi').DataTable({
            processing: true,
            serverSide: false, // Ubah ke true jika data banyak
            ajax: {
                url: "{{ route('operasi.order.data', $registration->id) }}", // Route untuk get data
                type: 'GET',
                dataSrc: 'data'
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
                    name: 'diagnosa'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
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
            language: {
                processing: "Memuat data...",
                // search: "Pencarian:",
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
                emptyTable: "Tidak ada data order operasi"
            }
        });

        // Inisialisasi DataTable untuk Tindakan Operasi
        var tindakanOperasiTable = $('#dt-tindakan-operasi').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('operasi.prosedur.data', $registration->id) }}", // Route untuk get data tindakan
                type: 'GET',
                dataSrc: 'data'
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
                        let badgeClass = '';
                        let statusText = '';

                        switch (data) {
                            case 'Draft':
                                badgeClass = 'badge-warning';
                                statusText = 'Draft';
                                break;
                            case 'Final':
                                badgeClass = 'badge-success';
                                statusText = 'Final';
                                break;
                        }

                        return `<span class="badge ${badgeClass}">${statusText}</span>`;
                    }
                }
            ],
            language: {
                processing: "Memuat data...",
                // search: "Pencarian:",
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

        // Event handler untuk tombol reload
        $('#btn-reload-order').on('click', function() {
            orderOperasiTable.ajax.reload();
            tindakanOperasiTable.ajax.reload();
        });

        // Event handler untuk simpan order operasi
        $('#btn-simpan-order-operasi').on('click', function(e) {
            e.preventDefault();

            var button = $(this);
            var formData = $('#form-order-operasi').serialize();

            // Disable tombol dan tampilkan loading
            button.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
            );

            $.ajax({
                url: "{{ route('operasi.order.store') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Jangan tutup modal otomatis, biarkan user melihat pesan sukses dulu

                    // Tampilkan notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tutup modal setelah user klik OK
                            $('#modal-order-operasi').modal('hide');

                            // Reset form
                            $('#form-order-operasi')[0].reset();
                            $('#form-order-operasi .select2').val(null).trigger(
                                'change');
                        }
                    });

                    // Reload tabel DataTables
                    orderOperasiTable.ajax.reload(null,
                        false); // false = tetap di halaman yang sama
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON?.errors;
                    var errorMsg = 'Terjadi kesalahan:\n';

                    if (errors) {
                        $.each(errors, function(key, value) {
                            errorMsg += '- ' + value[0] + '\n';
                        });
                    } else {
                        errorMsg = xhr.responseJSON?.message ||
                            'Gagal menyimpan data. Silakan coba lagi.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg,
                        showConfirmButton: true
                    });
                },
                complete: function() {
                    // Aktifkan kembali tombol
                    button.prop('disabled', false).html('Simpan Order');
                }
            });
        });

        // Event handler untuk reset form ketika modal ditutup
        $('#modal-order-operasi').on('hidden.bs.modal', function() {
            $('#form-order-operasi')[0].reset();
            $('#form-order-operasi .select2').val(null).trigger('change');

            // Reset tombol jika masih dalam keadaan loading
            $('#btn-simpan-order-operasi').prop('disabled', false).html('Simpan Order');
        });

        // Event handler untuk edit order (opsional)
        $('#dt-order-operasi').on('click', '.btn-edit-order', function() {
            var orderId = $(this).data('id');
            // Implementasi edit order
            console.log('Edit order ID:', orderId);
        });

        // Event handler untuk delete order (opsional)
        $('#dt-order-operasi').on('click', '.btn-delete-order', function() {
            var orderId = $(this).data('id');

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus order ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implementasi delete order
                    $.ajax({
                        url: "{{ route('operasi.order.delete') }}",
                        type: 'DELETE',
                        data: {
                            id: orderId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            orderOperasiTable.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error');
                        }
                    });
                }
            });
        });
    });
</script>
