@extends('inc.layout') {{-- Sesuaikan dengan layout utama Anda --}}
@section('title', 'Set Tarif Visite Dokter')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-user-md'></i> Pengaturan Tarif Visite Dokter
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Dokter
                        </h2>
                        <div class="panel-toolbar">
                            {{-- Toolbar bisa dikosongkan atau diisi tombol lain jika perlu --}}
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="doctor-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lengkap Dokter</th>
                                        <th>Jabatan</th>
                                        <th>Unit/Departemen</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data akan diisi oleh DataTables --}}
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Pastikan path JS ini sudah benar --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        // Fungsi global yang akan dipanggil oleh popup window untuk memberitahu ada perubahan
        // Kita tidak perlu refresh tabel ini, tapi fungsi ini bisa digunakan untuk notifikasi
        function onTariffUpdated() {
            showSuccessAlert('Tarif berhasil diperbarui!');
        }

        $(document).ready(function() {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi DataTables untuk menampilkan daftar dokter
            const table = $('#doctor-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tarif-visite-dokter.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'job_position_name',
                        name: 'jobPosition.name'
                    },
                    {
                        data: 'organization_name',
                        name: 'organization.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                responsive: true
            });

            // Event listener untuk tombol "Set Tarif per Kelas"
            $('#doctor-table tbody').on('click', '.set-tarif-btn', function() {
                const doctorId = $(this).data('doctor-id');
                if (!doctorId) {
                    showErrorAlert('ID Dokter tidak valid.');
                    return;
                }

                // URL ke halaman popup
                const url = "{{ url('simrs/set-tarif-visite') }}/" + doctorId;

                // Buka di jendela baru
                window.open(url, 'SetTarifWindow', 'width=1200,height=700,scrollbars=yes,resizable=yes');
            });
        });
    </script>
@endsection
