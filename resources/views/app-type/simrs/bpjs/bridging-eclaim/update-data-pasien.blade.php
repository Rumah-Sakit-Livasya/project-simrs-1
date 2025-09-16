@extends('inc.layout')
@section('title', 'Update Data Pasien')
@section('content')
    <style>
        /* Style kustom untuk meniru garis putus-putus di bawah data pasien */
        .data-display {
            border-bottom: 1px dotted #ced4da;
            padding: 8px 0;
            min-height: 38px;
            /* Menyamakan tinggi dengan form control */
            font-weight: 500;
            color: #495057;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        {{-- Kedua panel kini ditampilkan secara default --}}
        <div class="row justify-content-center">
            <div class="col-xl-10">
                {{-- Panel Pencarian --}}
                <div id="panel-search" class="panel">
                    <div class="panel-hdr">
                        <h2>Search Data Pasien</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-cari-pasien">
                                <div class="row align-items-end">
                                    <div class="col-md-10">
                                        <label class="form-label" for="no_registrasi">No Registrasi</label>
                                        <input type="text" id="no_registrasi" name="no_registrasi" class="form-control"
                                            placeholder="Masukkan Nomor Registrasi Pasien..." required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fal fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel Hasil Data Pasien --}}
                <div id="panel-data-pasien" class="panel mt-4">
                    <div class="panel-hdr">
                        <h2>Data Pasien</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Baris Data Nama --}}
                            <div class="row mb-2">
                                <div class="col-md-3"><strong>Nama</strong></div>
                                <div class="col-md-9 data-display" id="display_nama">-</div>
                            </div>
                            {{-- Baris Data No RM/Reg --}}
                            <div class="row mb-2">
                                <div class="col-md-3"><strong>No Rm/No Reg</strong></div>
                                <div class="col-md-9 data-display" id="display_norm_reg">-</div>
                            </div>
                            {{-- Baris Data Tanggal Regis --}}
                            <div class="row mb-2">
                                <div class="col-md-3"><strong>Tanggal Regis</strong></div>
                                <div class="col-md-9 data-display" id="display_tgl_regis">-</div>
                            </div>
                            {{-- Baris Data Departemen --}}
                            <div class="row mb-2">
                                <div class="col-md-3"><strong>Departement</strong></div>
                                <div class="col-md-9 data-display" id="display_departemen">-</div>
                            </div>
                            {{-- Baris Data Dokter --}}
                            <div class="row mb-2">
                                <div class="col-md-3"><strong>Dokter</strong></div>
                                <div class="col-md-9 data-display" id="display_dokter">-</div>
                            </div>

                            {{-- Tombol Aksi (dinonaktifkan secara default) --}}
                            <hr class="mt-4">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-info mr-2" id="btn-ubah-tgl-reg" disabled>Ubah Tanggal
                                    Registrasi</button>
                                <button class="btn btn-warning mr-2" id="btn-ubah-tgl-pulang" disabled>Ubah Tanggal
                                    Pulang</button>
                                <button class="btn btn-danger" id="btn-edit-billing" disabled>Edit Billing</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Handle form pencarian
            $('#form-cari-pasien').on('submit', function(e) {
                e.preventDefault();
                var noRegistrasi = $('#no_registrasi').val();
                var searchButton = $(this).find('button[type="submit"]');

                if (!noRegistrasi) {
                    toastr.error('Nomor Registrasi tidak boleh kosong.', 'Error');
                    return;
                }

                // Tampilkan status loading
                searchButton.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...'
                );

                // Reset data display dan nonaktifkan tombol
                $('#display_nama, #display_norm_reg, #display_tgl_regis, #display_departemen, #display_dokter')
                    .text('-');
                $('#btn-ubah-tgl-reg, #btn-ubah-tgl-pulang, #btn-edit-billing').prop('disabled', true);

                // --- AJAX Call Placeholder ---
                // Di aplikasi nyata, Anda akan melakukan panggilan AJAX ke backend di sini.

                // Untuk DEMO, kita simulasikan proses pencarian
                setTimeout(function() {
                    // Reset tombol
                    searchButton.prop('disabled', false).html(
                        '<i class="fal fa-search mr-1"></i> Cari');

                    // Data contoh
                    var mockPatientData = {
                        nama: 'BUDI SANTOSO',
                        norm: '123456',
                        no_reg: noRegistrasi,
                        tgl_regis: '2025-09-12 10:30:00',
                        departemen: 'POLI PENYAKIT DALAM',
                        dokter: 'DR. ADI KURNIAWAN, SP.PD'
                    };

                    // Isi data ke dalam panel
                    $('#display_nama').text(mockPatientData.nama);
                    $('#display_norm_reg').text(mockPatientData.norm + ' / ' + mockPatientData
                        .no_reg);
                    $('#display_tgl_regis').text(mockPatientData.tgl_regis);
                    $('#display_departemen').text(mockPatientData.departemen);
                    $('#display_dokter').text(mockPatientData.dokter);

                    // Aktifkan tombol aksi setelah data ditemukan
                    $('#btn-ubah-tgl-reg, #btn-ubah-tgl-pulang, #btn-edit-billing').prop('disabled',
                        false);

                    toastr.success('Data pasien ditemukan!', 'Sukses');
                }, 1000); // Simulasi delay 1 detik
            });

            // Placeholder untuk tombol aksi
            $('#btn-ubah-tgl-reg').on('click', function() {
                Swal.fire('Info', 'Fungsi untuk "Ubah Tanggal Registrasi" akan dijalankan.', 'info');
            });

            $('#btn-ubah-tgl-pulang').on('click', function() {
                Swal.fire('Info', 'Fungsi untuk "Ubah Tanggal Pulang" akan dijalankan.', 'info');
            });

            $('#btn-edit-billing').on('click', function() {
                Swal.fire('Info', 'Fungsi untuk "Edit Billing" akan dijalankan.', 'info');
            });
        });
    </script>
@endsection
