@extends('inc.layout')
@section('title', 'Departemen')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Menggunakan Subheader untuk judul halaman yang konsisten --}}
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-building'></i> Daftar <span class='fw-300'>Departemen</span>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Departemen
                        </h2>
                        {{-- Memindahkan tombol aksi ke header panel agar mudah diakses --}}
                        <div class="panel-toolbar">
                            <button type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal"
                                data-target="#importModal">
                                <i class="fal fa-upload mr-1"></i>
                                Import Data
                            </button>
                            <a href="{{ route('master-data.setup.departemen.tambah') }}" class="btn btn-primary btn-sm"
                                title="Tambah Departemen Baru">
                                <i class="fal fa-plus-circle mr-1"></i>
                                Tambah Baru
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Menampilkan notifikasi sukses/error dari session --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                {{-- Spinner dipindahkan ke luar tabel agar tidak merusak layout --}}
                                <div id="loading-spinner" class="text-center" style="display: none;">
                                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                                </div>
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama</th>
                                        <th>Kode</th>
                                        <th>Keterangan</th>
                                        <th>Quota</th>
                                        <th>Default Dokter</th>
                                        <th>Kode Poli</th>
                                        <th class="text-center">Publish Online</th>
                                        <th>Revenue & Cost Center</th>
                                        <th>Master Layanan RL</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departements as $departement)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $departement->name }}</td>
                                            <td>{{ $departement->kode }}</td>
                                            <td>{{ $departement->keterangan }}</td>
                                            <td class="text-center">{{ $departement->quota ?? '-' }}</td>
                                            <td>
                                                {{-- Menggunakan relasi untuk menampilkan nama dokter --}}
                                                {{-- Pastikan relasi 'doctor' ada di model Departement --}}
                                                {{ $departement->doctor->employee->fullname ?? '-' }}
                                            </td>
                                            <td>{{ $departement->kode_poli }}</td>
                                            <td class="text-center">
                                                {{-- Mengubah tampilan boolean menjadi badge agar lebih jelas --}}
                                                @if ($departement->publish_online)
                                                    <span class="badge badge-success">Ya</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak</span>
                                                @endif
                                            </td>
                                            <td>{{ $departement->revenue_and_cost_center }}</td>
                                            <td>{{ $departement->master_layanan_rl }}</td>
                                            <td class="text-center">
                                                {{-- Placeholder untuk tombol aksi per baris --}}
                                                <a href="#" class="btn btn-xs btn-icon btn-warning"
                                                    title="Edit Data"><i class="fal fa-edit"></i></a>
                                                <a href="#" class="btn btn-xs btn-icon btn-danger"
                                                    title="Hapus Data"><i class="fal fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- Form untuk import via AJAX --}}
                <form id="import-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Departemen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Placeholder untuk pesan error dari AJAX --}}
                        <div id="import-errors" class="alert alert-danger" style="display: none;"></div>

                        <div class="alert alert-info">
                            <strong>Petunjuk:</strong>
                            <ul class="mb-0">
                                <li>Unggah file Excel (.xlsx) atau CSV (.csv).</li>
                                <li>Pastikan baris pertama file adalah header.</li>
                                <li>Header yang wajib: <strong>name, kode, keterangan</strong>.</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="import-file">Pilih File</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="import-file" name="file" required>
                                <label class="custom-file-label" for="import-file">Pilih file...</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="import-button">
                            <i class="fal fa-upload mr-1"></i>
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script>
        $(document).ready(function() {
            // Tampilkan spinner sebelum datatable diinisialisasi
            $('#loading-spinner').show();

            // Inisialisasi datatable
            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ],
                // Sembunyikan spinner setelah tabel selesai digambar
                "initComplete": function(settings, json) {
                    $('#loading-spinner').hide();
                }
            });

            // Menampilkan nama file pada input custom
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName || "Pilih file...");
            });

            // Menangani submit form import via AJAX
            $('#import-form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let button = $('#import-button');
                let errorContainer = $('#import-errors');
                let originalButtonHtml = button.html();
                let formData = new FormData(this);

                // Nonaktifkan tombol dan tampilkan spinner
                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengimpor...'
                );
                errorContainer.hide().html('');

                $.ajax({
                    url: "{{ route('master-data.setup.departemen.import') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#importModal').modal('hide'); // Tutup modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                        }).then(() => {
                            location
                                .reload(); // Refresh halaman untuk melihat data baru
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<ul>';
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorHtml += '<li>' + value[0] + '</li>';
                            });
                        } else {
                            errorHtml += '<li>Terjadi kesalahan yang tidak diketahui.</li>';
                        }
                        errorHtml += '</ul>';
                        errorContainer.html(errorHtml).show();
                    },
                    complete: function() {
                        // Kembalikan tombol ke keadaan semula
                        button.prop('disabled', false).html(originalButtonHtml);
                        form.find('#import-file').val(''); // Reset input file
                        $('.custom-file-label').html('Pilih file...');
                    }
                });
            });
        });
        // Fungsi formatAngka tidak digunakan di halaman ini, jadi bisa dihapus
    </script>
@endsection
