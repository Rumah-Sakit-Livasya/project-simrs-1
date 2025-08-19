@section('script-operasi')
    <script>
        $(document).ready(function() {
            // --- INITIALIZATION ---
            $('#operasi').hide();
            let orderOperasiDataTable;
            let tindakanOperasiDataTable;

            // ++ PENGATURAN BAHASA UNTUK DATATABLES ++
            const commonLanguageSettings = {
                processing: '<i class="fa fa-spinner fa-spin"></i> Sedang memuat data...',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(difilter dari _MAX_ total entri)",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
                emptyTable: "Tidak ada data tersedia di dalam tabel",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Mundur"
                }
            };

            // ++ INISIALISASI PLUGIN ++

            // Inisialisasi Datepicker untuk input tanggal operasi di dalam modal
            $('#tgl_operasi').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                autoclose: true,
                orientation: 'bottom left',
                container: '#modal-order-operasi'
            });

            // Inisialisasi Select2 untuk semua elemen dengan class 'select2-modal' di dalam modal
            $('.select2-modal').select2({
                dropdownParent: $('#modal-order-operasi'),
                placeholder: 'Pilih...',
                allowClear: true,
                width: '100%'
            });

            // --- EVENT LISTENERS ---

            // 1. Show Operasi Section on Menu Click
            $('.menu-layanan[data-layanan="operasi"]').on('click', function() {
                $('#menu-layanan').fadeOut(300);
                $('.tab-pane.fade.show.active, #pengkajian-nurse-rajal, #tindakan-medis, #radiologi, #laboratorium')
                    .hide();

                $('#operasi').delay(300).fadeIn(300, function() {
                    initializeDataTables();
                    loadOrderOperasiData();
                    loadTindakanOperasiData();
                });
            });

            // 2. Open "Tambah Order" Modal
            $('#btn-tambah-order-operasi').on('click', function() {
                $('#form-order-operasi')[0].reset();
                $('#order_operasi_id').val('');
                $('.select2-modal').val(null).trigger('change');
                loadMasterDataForModal();
                $('#modal-order-operasi').modal('show');
            });

            // 3. Reload Button for Order Table
            $('#btn-reload-order').on('click', function() {
                var button = $(this);
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

                loadOrderOperasiData();
                loadTindakanOperasiData();

                setTimeout(function() {
                    button.prop('disabled', false).html(
                        '<span class="fal fa-sync mr-1"></span> Reload');
                }, 1000);
            });

            // 4. Delete Order Operasi
            $('#dt-order-operasi tbody').on('click', '.btn-hapus-order-operasi', function() {
                const orderId = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Order operasi ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/simrs/order-operasi/delete/${orderId}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message ||
                                            'Order berhasil dihapus.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    loadOrderOperasiData();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message ||
                                            'Gagal menghapus order.'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan server saat menghapus.'
                                });
                            }
                        });
                    }
                });
            });

            // --- HELPER FUNCTIONS ---

            function initializeDataTables() {
                // Initialize Order Operasi DataTable
                if (!$.fn.DataTable.isDataTable('#dt-order-operasi')) {
                    orderOperasiDataTable = $('#dt-order-operasi').DataTable({
                        responsive: true,
                        searching: true,
                        paging: true,
                        info: true,
                        lengthChange: true,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "Semua"]
                        ],
                        order: [
                            [0, 'desc']
                        ],
                        language: commonLanguageSettings
                    });
                }

                // Initialize Tindakan Operasi DataTable
                if (!$.fn.DataTable.isDataTable('#dt-tindakan-operasi')) {
                    tindakanOperasiDataTable = $('#dt-tindakan-operasi').DataTable({
                        responsive: true,
                        searching: true,
                        paging: true,
                        info: true,
                        lengthChange: true,
                        pageLength: 10,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "Semua"]
                        ],
                        language: commonLanguageSettings
                    });
                }
            }

            function loadOrderOperasiData() {
                if (!orderOperasiDataTable) return;

                const table = orderOperasiDataTable;
                table.processing(true);
                const registrationId = $('#operasi_registration_id').val();

                $.get(`/api/simrs/get-order-operasi/${registrationId}`, function(response) {
                    table.clear();
                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        response.data.forEach(item => {
                            table.row.add([
                                item.tgl_order_formatted || 'N/A',
                                item.kelas_name || 'N/A',
                                item.ruangan_name || 'N/A',
                                item.jenis_operasi_name || 'N/A',
                                item.kategori_operasi_name || 'N/A',
                                item.diagnosa || 'N/A',
                                `<button class="btn btn-xs btn-danger btn-hapus-order-operasi" data-id="${item.id}" title="Hapus Order"><i class="fal fa-trash"></i></button>`
                            ]).draw(false);
                        });
                    }
                    table.draw();
                    table.processing(false);
                }).fail(() => {
                    table.clear().draw();
                    table.processing(false);
                });
            }

            function loadTindakanOperasiData() {
                if (!tindakanOperasiDataTable) return;

                const table = tindakanOperasiDataTable;
                table.processing(true);
                const registrationId = $('#operasi_registration_id').val();

                $.get(`/api/simrs/get-tindakan-operasi/${registrationId}`, function(response) {
                    table.clear();
                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        response.data.forEach(item => {
                            table.row.add([
                                item.tindakan_name || 'N/A',
                                item.tipe_operasi_name || 'N/A',
                                item.tipe_penggunaan_name || 'N/A',
                                item.dokter_name || 'N/A',
                                item.tgl_tindakan_formatted || 'N/A',
                                item.user_create_name || 'N/A',
                                `<span class="badge badge-success p-2">${item.status || 'Selesai'}</span>`
                            ]).draw(false);
                        });
                    }
                    table.draw();
                    table.processing(false);
                }).fail(() => {
                    table.clear().draw();
                    table.processing(false);
                });
            }

            function loadMasterDataForModal() {
                const endpoints = {
                    '#jenis_operasi': '/api/simrs/master/jenis-operasi',
                    '#kategori_operasi': '/api/simrs/master/kategori-operasi',
                    '#tindakan_operasi_master': '/api/simrs/master/tindakan-operasi',
                    '#kelas_operasi': '/api/simrs/master/kelas-rawat',
                };

                for (const selector in endpoints) {
                    const url = endpoints[selector];
                    const $select = $(selector);

                    $.get(url, function(data) {
                        $select.empty().append('<option value=""></option>');
                        data.forEach(item => {
                            const itemName = item.name || item.nama_jenis || item.nama_kategori ||
                                item.nama_operasi || item.kelas;
                            $select.append(`<option value="${item.id}">${itemName}</option>`);
                        });
                        $select.trigger('change.select2');
                    }).fail(function() {
                        console.log(`Failed to load data for ${selector}`);
                    });
                }
            }

            // Export functions to global scope untuk digunakan di file lain
            window.operasiHelpers = {
                loadOrderOperasiData: loadOrderOperasiData,
                loadTindakanOperasiData: loadTindakanOperasiData,
                loadMasterDataForModal: loadMasterDataForModal,
                orderOperasiDataTable: () => orderOperasiDataTable,
                tindakanOperasiDataTable: () => tindakanOperasiDataTable
            };
        });
    </script>
@endsection
