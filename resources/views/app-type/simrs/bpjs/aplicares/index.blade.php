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
                            Ketersediaan <span class="fw-300"><i>Tempat Tidur & Mapping Aplicare</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            {{-- Bisa ditambahkan tombol aksi global di sini --}}
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="aplicares-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Aplicare</th>
                                        <th>Nama Kelas</th>
                                        <th>Kode Ruangan</th>
                                        <th>Nama Ruangan</th>
                                        <th>Total Bed</th>
                                        <th>Bed Terpakai</th>
                                        <th>Sisa Bed</th>
                                        <th>Mapping Ruangan</th>
                                        <th style="width: 120px;">Fungsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan diisi oleh DataTables --}}
                                </tbody>
                            </table>
                            <!-- datatable end -->
                            <div class="mt-4">
                                <h5 class="frame-heading">Aksi Global</h5>
                                <div class="frame-wrap">
                                    <button class="btn btn-primary">UPDATE ALL ROOM</button>
                                    <button class="btn btn-success">INSERT ALL ROOM</button>
                                    <button class="btn btn-danger">REMOVE ALL ROOM</button>
                                    <button class="btn btn-warning">REMOVE ALL ROOM NONAKTIF</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

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
                    {
                        data: 'aplicare_code',
                        name: 'kelas_rawat.kode_bpjs'
                    },
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
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        // Fungsi untuk Aksi per Ruangan (PLACEHOLDER)
        function callAplicareApi(url, method, successMessage, errorMessage) {
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses permintaan ke server BPJS.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: url,
                type: method,
                success: function(response) {
                    if (response.success) {
                        Swal.close();
                        showSuccessAlert(response.message);
                        $('#aplicares-table').DataTable().ajax.reload();
                    } else {
                        showErrorAlert(response.message || errorMessage);
                    }
                },
                error: function(xhr) {
                    showErrorAlert(errorMessage);
                }
            });
        }

        function updateRoom(roomId) {
            let url = "{{ route('aplicares.update', ':id') }}".replace(':id', roomId);
            callAplicareApi(url, 'POST', 'Ruangan berhasil diupdate.', 'Gagal mengupdate ruangan di Aplicares!');
        }

        function insertRoom(roomId) {
            let url = "{{ route('aplicares.insert', ':id') }}".replace(':id', roomId);
            callAplicareApi(url, 'POST', 'Ruangan berhasil ditambahkan.', 'Gagal menambahkan ruangan di Aplicares!');
        }

        function deleteRoom(roomId) {
            showDeleteConfirmation(function() {
                let url = "{{ route('aplicares.delete', ':id') }}".replace(':id', roomId);
                callAplicareApi(url, 'DELETE', 'Ruangan berhasil dihapus.',
                    'Gagal menghapus ruangan di Aplicares!');
            });
        }

        // Fungsi untuk membuka modal mapping (jika diperlukan)
        function openMappingModal(roomId) {
            // Anda bisa membuat modal untuk form mapping di sini
            alert('Fungsi mapping untuk Room ID: ' + roomId + ' belum diimplementasikan.');
        }
    </script>
@endsection
