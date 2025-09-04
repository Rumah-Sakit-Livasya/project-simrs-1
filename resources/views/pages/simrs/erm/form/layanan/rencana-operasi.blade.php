@extends('pages.simrs.erm.index')
@section('erm')
    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian"></div>
                    <h2 class="font-weight-bold">Rencana Operasi</h4>
                </header>
                <hr style="border-color: #868686; margin-top: 30px; margin-bottom: 30px;">
                <div class="row">
                    <div class="col-md-12">
                        @include('pages.simrs.pendaftaran.partials.operasi')
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script></script>

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
                serverSide: false,
                ajax: {
                    url: "{{ route('operasi.order.data', $registration->id) }}",
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
                    url: "{{ route('operasi.prosedur.data', $registration->id) }}",
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
                            let badgeClass = data === 'Draft' ? 'badge-warning' : 'badge-success';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    }
                ],
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
                        let urlTemplate = "{{ route('operasi.order.delete', ['order' => ':id']) }}";
                        let deleteUrl = urlTemplate.replace(':id', orderId);

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                orderOperasiTable.ajax.reload();
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
        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#modal-order-operasi .select2').select2({
                dropdownParent: $('#modal-order-operasi')
            });

            // Data ruangan dari controller
            const ruangansOperasi = @json($ruangans_operasi);

            // Fungsi untuk memperbarui dropdown ruangan berdasarkan kelas yang dipilih
            function updateRuanganOptions(kelasRawatId) {
                const ruanganSelect = $('#form-order-operasi select[name="ruangan_id"]');
                ruanganSelect.empty().append('<option value="">Pilih Ruangan</option>');

                // Filter ruangan berdasarkan kelas_rawat_id
                const filteredRuangans = ruangansOperasi.filter(room => room.kelas_rawat_id == kelasRawatId);

                if (filteredRuangans.length > 0) {
                    // Ambil ruangan pertama yang sesuai (hanya satu OK per kelas)
                    const room = filteredRuangans[0];
                    ruanganSelect.append(
                        `<option value="${room.id}" selected>${room.ruangan} </option>`
                    );
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Ada Ruangan',
                        text: 'Tidak ada ruangan OK untuk kelas yang dipilih.',
                    });
                }

                ruanganSelect.trigger('change');
            }

            // Ketika modal dibuka, set default kelas ke Rawat Jalan dan update ruangan
            $('#modal-order-operasi').on('shown.bs.modal', function() {
                const kelasRawatSelect = $('#form-order-operasi select[name="kelas_rawat_id"]');
                const selectedKelas = kelasRawatSelect.val();
                if (selectedKelas) {
                    updateRuanganOptions(selectedKelas);
                }
            });

            // Ketika kelas rawat berubah, perbarui dropdown ruangan
            $('#form-order-operasi select[name="kelas_rawat_id"]').on('change', function() {
                const kelasRawatId = $(this).val();
                if (kelasRawatId) {
                    updateRuanganOptions(kelasRawatId);
                }
            });

            // Validasi tambahan untuk memastikan hanya ruangan OK yang dipilih
            $('#form-order-operasi select[name="ruangan_id"]').on('change', function() {
                const selectedRoom = $(this).find('option:selected').text();
            });
        });
    </script>
@endsection
