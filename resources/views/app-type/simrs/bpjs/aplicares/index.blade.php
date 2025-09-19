@extends('inc.layout')

@section('title', 'BPJS Bridging - Aplicare')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">SIMRS</a></li>
            <li class="breadcrumb-item">BPJS</li>
            <li class="breadcrumb-item active">Bridging Aplicare</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Ketersediaan <span class="fw-300"><i>Tempat Tidur & Sinkronisasi Aplicare</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="alert alert-info">
                                Tabel ini menampilkan data ruangan dari sistem internal Anda dan status sinkronisasinya
                                dengan server BPJS.
                            </div>
                            <!-- datatable start -->
                            <table id="aplicares-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        {{-- <th>Kode Aplicare</th> --}}
                                        <th>Nama Kelas</th>
                                        <th>Kode Ruangan</th>
                                        <th>Nama Ruangan</th>
                                        <th>Total</th>
                                        <th>Terpakai</th>
                                        <th>Sisa</th>
                                        <th>Status Sinkronisasi</th> {{-- HEADER DIPERBARUI --}}
                                        <th style="width: 100px;">Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data diisi oleh DataTables --}}
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Data Ketersediaan Kamar <span class="fw-300"><i>di Server BPJS (Live)</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="alert alert-info">
                                Tabel ini menampilkan data ketersediaan kamar yang saat ini tercatat di server BPJS Aplicare
                                secara real-time. Gunakan tabel ini sebagai perbandingan dengan data di atas.
                            </div>
                            <!-- datatable start -->
                            <table id="aplicares-bpjs-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-success-600">
                                    <tr>
                                        <th>Kode Kelas</th>
                                        <th>Nama Kelas</th>
                                        <th>Kode Ruang</th>
                                        <th>Nama Ruang</th>
                                        <th>Kapasitas</th>
                                        <th>Tersedia</th>
                                        <th>Tersedia Pria</th>
                                        <th>Tersedia Wanita</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan diisi oleh DataTables dari API BPJS --}}
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL UNTUK MAPPING --}}
    <div class="modal fade" id="mappingModal" tabindex="-1" role="dialog" aria-labelledby="mappingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mappingModalLabel">Mapping Ruangan ke Kelas Aplicare</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="mappingForm">
                        {{-- Hidden input untuk menyimpan ID ruangan yang sedang di-mapping --}}
                        <input type="hidden" id="mappingRoomId" name="room_id">

                        <div class="form-group">
                            <label for="roomName">Nama Ruangan</label>
                            <input type="text" id="roomName" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="bpjsClassSelect">Pilih Kelas BPJS</label>
                            <select class="form-control" id="bpjsClassSelect" name="kode_bpjs" required>
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                {{-- Pilihan kelas akan diisi dari controller --}}
                                @if (isset($kelasBpjs) && !empty($kelasBpjs))
                                    @foreach ($kelasBpjs as $kelas)
                                        <option value="{{ $kelas['kodekelas'] }}">{{ $kelas['namakelas'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveMapping()">Simpan Mapping</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('plugin')
    {{-- Pastikan Anda sudah memuat SweetAlert2, biasanya ada di layout utama --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Plugin lain yang mungkin Anda butuhkan --}}

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            var table = $('#aplicares-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('aplicares.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'aplicare_code',
                    //     name: 'kelas_rawat.kode_bpjs'
                    // },
                    {
                        data: 'class_name',
                        name: 'kelas_rawat.nama_bpjs'
                    },
                    {
                        data: 'no_ruang',
                        name: 'no_ruang'
                    },
                    {
                        data: 'ruangan',
                        name: 'ruangan'
                    },
                    {
                        data: 'beds_count',
                        name: 'beds_count',
                        searchable: false
                    },
                    {
                        data: 'beds_terpakai_count',
                        name: 'beds_terpakai_count',
                        searchable: false
                    },
                    {
                        data: 'sisa_bed',
                        name: 'sisa_bed',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'mapping_status',
                        name: 'mapping_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // =================================================================
            // TAMBAHKAN KODE INI UNTUK MENGINISIALISASI TABEL KEDUA
            // =================================================================
            var bpjsTable = $('#aplicares-bpjs-table').DataTable({
                processing: true,
                serverSide: false, // Karena kita mengambil semua data sekaligus
                ajax: {
                    url: "{{ route('aplicares.bpjs-data') }}",
                    type: "GET",
                    // Jika ada error dari server, tampilkan di body tabel
                    error: function(xhr) {
                        let errorMsg = 'Gagal memuat data. Periksa koneksi atau kredensial Anda.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        $("#aplicares-bpjs-table tbody").html(
                            '<tr><td colspan="8" class="text-center text-danger">' + errorMsg +
                            '</td></tr>'
                        );
                    }
                },
                columns: [
                    // Sesuaikan 'data' dengan nama kunci dari JSON response BPJS
                    {
                        data: 'kodekelas'
                    },
                    {
                        data: 'namakelas'
                    },
                    {
                        data: 'koderuang'
                    },
                    {
                        data: 'namaruang'
                    },
                    {
                        data: 'kapasitas'
                    },
                    {
                        data: 'tersedia'
                    },
                    {
                        data: 'tersediapria'
                    },
                    {
                        data: 'tersediawanita'
                    }
                ],
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

        });

        // =========================================================================
        // HELPER FUNCTIONS UNTUK NOTIFIKASI (MENGGUNAKAN SWEETALERT2)
        // =========================================================================
        function showSuccessAlert(message) {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                title: 'Gagal!',
                text: message,
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
        }

        function showDeleteConfirmation(callback) {
            Swal.fire({
                title: 'Anda yakin ingin menghapus?',
                text: "Ruangan ini akan dihapus dari server BPJS. Aksi ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback(); // Jalankan fungsi AJAX jika dikonfirmasi
                }
            });
        }


        // =========================================================================
        // FUNGSI UTAMA UNTUK MEMANGGIL API APLICARES
        // =========================================================================
        function callAplicareApi(url, method, successMessage, errorMessage) {
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses permintaan ke server BPJS.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Gunakan Fetch API modern
            fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF Token dari Blade
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    // Cek jika response dari server tidak OK (e.g., error 500, 422)
                    if (!response.ok) {
                        // Coba ambil pesan error dari JSON response
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Terjadi kesalahan server.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.close();
                    if (data.success) {
                        showSuccessAlert(data.message || successMessage);
                        $('#aplicares-table').DataTable().ajax.reload(null, false); // Reload tabel tanpa reset paging
                    } else {
                        showErrorAlert(data.message || errorMessage);
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    showErrorAlert(error.message || errorMessage);
                });
        }

        function updateRoom(roomId) {
            let url = `{{ route('aplicares.update', ['roomId' => ':id']) }}`.replace(':id', roomId);
            callAplicareApi(url, 'POST', 'Ruangan berhasil diupdate.', 'Gagal mengupdate ruangan di Aplicares!');
        }

        function insertRoom(roomId) {
            let url = `{{ route('aplicares.insert', ['roomId' => ':id']) }}`.replace(':id', roomId);
            callAplicareApi(url, 'POST', 'Ruangan berhasil ditambahkan.', 'Gagal menambahkan ruangan di Aplicares!');
        }

        function deleteRoom(roomId) {
            showDeleteConfirmation(function() {
                let url = `{{ route('aplicares.delete', ['roomId' => ':id']) }}`.replace(':id', roomId);
                callAplicareApi(url, 'DELETE', 'Ruangan berhasil dihapus.',
                    'Gagal menghapus ruangan di Aplicares!');
            });
        }

        // Ganti fungsi openMappingModal yang lama dengan ini
        function openMappingModal(roomId) {
            // Ambil data dari baris tabel untuk ditampilkan di modal
            let rowData = $('#aplicares-table').DataTable().rows().data().toArray().find(row => row.id == roomId);
            if (rowData) {
                $('#mappingRoomId').val(roomId);
                $('#roomName').val(rowData.ruangan); // Tampilkan nama ruangan

                // Reset pilihan select
                $('#bpjsClassSelect').val('');

                $('#mappingModal').modal('show');
            }
        }

        // Tambahkan fungsi baru ini untuk menyimpan
        function saveMapping() {
            const roomId = $('#mappingRoomId').val();
            const kodeBpjs = $('#bpjsClassSelect').val();

            if (!kodeBpjs) {
                alert('Silakan pilih kelas BPJS terlebih dahulu.');
                return;
            }

            let url = `{{ route('aplicares.save-mapping', ['roomId' => ':id']) }}`.replace(':id', roomId);
            let data = {
                kode_bpjs: kodeBpjs
            };

            // Tampilkan notifikasi loading
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        $('#mappingModal').modal('hide');
                        Swal.fire('Berhasil!', result.message, 'success');
                        $('#aplicares-table').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal!', result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Tidak dapat menyimpan mapping.', 'error');
                });
        }
    </script>
@endsection
