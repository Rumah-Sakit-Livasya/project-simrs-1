@section('script-vk')
    <script>
        $(document).ready(function() {
            // --- INITIALIZATION ---
            $('#persalinan').hide();
            let orderVkDataTable;
            let tindakanVkDataTable;

            const commonLanguageSettings = {
                processing: '<i class="fa fa-spinner fa-spin"></i> Sedang memuat data...',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                infoEmpty: "Menampilkan 0 dari 0 entri",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
                emptyTable: "Tidak ada data tersedia di dalam tabel",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Mundur"
                }
            };

            // ++ INISIALISASI PLUGIN UNTUK MODAL VK ++
            $('#tgl_persalinan').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                autoclose: true,
                orientation: 'bottom left',
                container: '#modal-order-vk'
            });

            $('.select2-modal-vk').select2({
                dropdownParent: $('#modal-order-vk'),
                placeholder: 'Pilih...',
                allowClear: true,
                width: '100%'
            });

            // --- EVENT LISTENERS ---
            // 1. Show Persalinan Section on Menu Click
            $('.menu-layanan[data-layanan="persalinan/index"]').on('click', function() {
                $('#menu-layanan').fadeOut(300);
                $('.tab-pane.fade.show.active, #pengkajian-nurse-rajal, #tindakan-medis, #radiologi, #laboratorium, #operasi')
                    .hide();

                $('#persalinan').delay(300).fadeIn(300, function() {
                    if (!$.fn.DataTable.isDataTable('#dt-order-vk')) {
                        orderVkDataTable = $('#dt-order-vk').DataTable({
                            responsive: true,
                            language: commonLanguageSettings
                        });
                    }
                    if (!$.fn.DataTable.isDataTable('#dt-tindakan-vk')) {
                        tindakanVkDataTable = $('#dt-tindakan-vk').DataTable({
                            responsive: true,
                            language: commonLanguageSettings
                        });
                    }
                    loadOrderVkData();
                    loadTindakanVkData();
                });
            });

            // 2. Open "Tambah Order VK" Modal
            $('#btn-tambah-order-vk').on('click', function() {
                $('#form-order-vk')[0].reset();
                $('#order_vk_id').val('');
                $('.select2-modal-vk').val(null).trigger('change');
                loadMasterDataForVkModal();
                $('#modal-order-vk').modal('show');
            });

            // 3. Reload Button
            $('#btn-reload-order-vk').on('click', function() {
                loadOrderVkData();
            });

            // 4. Save/Update Order VK
            $('#btn-simpan-order-vk').on('click', function() {
                $(this).prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
                const formData = $('#form-order-vk').serialize();
                const orderId = $('#order_vk_id').val();
                const url = orderId ? `/api/simrs/order-vk/update/${orderId}` : '/api/simrs/order-vk/store';
                const method = orderId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: (response) => {
                        if (response.success) {
                            $('#modal-order-vk').modal('hide');
                            showSuccessAlert(response.message ||
                                'Order persalinan berhasil disimpan!');
                            loadOrderVkData();
                        } else {
                            showErrorAlertNoRefresh(response.message ||
                                'Gagal menyimpan order.');
                        }
                    },
                    error: (xhr) => {
                        const errors = xhr.responseJSON.errors;
                        let errorMsg = 'Kesalahan validasi:<br>';
                        if (errors) {
                            $.each(errors, (key, value) => {
                                errorMsg += `• ${value[0]}<br>`;
                            });
                        } else {
                            errorMsg = xhr.responseJSON.message || 'Kesalahan server.';
                        }
                        showErrorAlertNoRefresh(errorMsg);
                    },
                    complete: () => $('#btn-simpan-order-vk').prop('disabled', false).text(
                        'Simpan Order')
                });
            });

            // 5. Delete Order VK
            $('#dt-order-vk tbody').on('click', '.btn-hapus-order-vk', function() {
                const orderId = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Order persalinan ini akan dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/simrs/order-vk/delete/${orderId}`,
                            method: 'DELETE',
                            success: (response) => {
                                if (response.success) {
                                    showSuccessAlert(response.message ||
                                        'Order berhasil dihapus.');
                                    loadOrderVkData();
                                } else {
                                    showErrorAlertNoRefresh(response.message ||
                                        'Gagal menghapus order.');
                                }
                            },
                            error: (xhr) => showErrorAlertNoRefresh('Kesalahan server.')
                        });
                    }
                });
            });

            // --- HELPER FUNCTIONS ---
            function loadOrderVkData() {
                const table = $('#dt-order-vk').DataTable();
                table.processing(true);
                const registrationId = $('#vk_registration_id').val();

                $.get(`/api/simrs/get-order-vk/${registrationId}`, function(response) {
                    table.clear();
                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        response.data.forEach(item => {
                            table.row.add([
                                item.tgl_rencana_formatted || 'N/A',
                                item.dokter_name || 'N/A',
                                item.bidan_name || 'N/A',
                                item.jenis_persalinan_name || 'N/A',
                                item.indikasi || '-',
                                `<button class="btn btn-xs btn-danger btn-hapus-order-vk" data-id="${item.id}" title="Hapus Order"><i class="fal fa-trash"></i></button>`
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

            function loadTindakanVkData() {
                const table = $('#dt-tindakan-vk').DataTable();
                table.processing(true);
                const registrationId = $('#vk_registration_id').val();

                $.get(`/api/simrs/get-tindakan-vk/${registrationId}`, function(response) {
                    table.clear();
                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        response.data.forEach(item => {
                            table.row.add([
                                item.tgl_tindakan_formatted || 'N/A',
                                item.dokter_name || 'N/A',
                                item.bidan_name || 'N/A',
                                item.lama_kala_1 || '-',
                                item.lama_kala_2 || '-',
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

            function loadMasterDataForVkModal() {
                const endpoints = {
                    '#dokter_dpjp_vk': '/api/simrs/master/doctors',
                    '#bidan_vk': '/api/simrs/master/midwives', // Anda perlu membuat endpoint ini
                    '#jenis_persalinan': '/api/simrs/master/jenis-persalinan', // dan endpoint ini
                    '#kelas_vk': '/api/simrs/master/kelas-rawat',
                };

                for (const selector in endpoints) {
                    const url = endpoints[selector];
                    const $select = $(selector);

                    $.get(url, function(data) {
                        $select.empty().append('<option value=""></option>');
                        data.forEach(item => {
                            const itemName = item.name || item.fullname || item.jenis || item.kelas;
                            $select.append(`<option value="${item.id}">${itemName}</option>`);
                        });
                        $select.trigger('change.select2');
                    });
                }
            }
        });
    </script>
@endsection
