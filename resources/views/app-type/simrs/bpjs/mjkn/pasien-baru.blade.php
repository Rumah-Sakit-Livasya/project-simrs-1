@extends('inc.layout')
@section('title', 'Pasien Baru via Mobile JKN')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel">
            <div class="panel-hdr">
                <h2><i class="fas fa-search mr-2"></i> Form Pencarian</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form id="form-search">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="tgl1" class="form-label">Periode Pendaftaran</label>
                                    <div class="input-daterange input-group">
                                        <input type="text" class="form-control bg-white" name="tgl1" id="tgl1"
                                            value="{{ date('d-m-Y') }}" readonly>
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text fs-xl"><i
                                                    class="fal fa-long-arrow-right"></i></span>
                                        </div>
                                        <input type="text" class="form-control bg-white" name="tgl2" id="tgl2"
                                            value="{{ date('d-m-Y') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex">
                    <button type="button" class="btn btn-primary ml-auto" id="btSearch">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-hdr">
                <h2><i class="fas fa-list-alt mr-2"></i> Daftar Pasien Baru via MJKN</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="pasien-baru-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Kartu</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Gender</th>
                                <th>Tgl. Lahir</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Kecamatan</th>
                                <th>Kab/Kota</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi plugin
            $('#tgl1, #tgl2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = $('#pasien-baru-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('mjkn.list-pasien-baru') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl1 = $('#tgl1').val();
                        d.tgl2 = $('#tgl2').val();
                    }
                },
                columns: [{
                        data: 'nomorkartu',
                        name: 'nomorkartu'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'jeniskelamin',
                        name: 'jeniskelamin'
                    },
                    {
                        data: 'tanggallahir',
                        name: 'tanggallahir'
                    },
                    {
                        data: 'nohp',
                        name: 'nohp'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'namakec',
                        name: 'namakec'
                    },
                    {
                        data: 'namadati2',
                        name: 'namadati2'
                    },
                    {
                        data: 'nomorkartu',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // URL ini akan mengarah ke form pendaftaran pasien baru di SIMRS Anda,
                            // dengan data dari MJKN sudah terisi (pre-filled).
                            // Anda perlu membuat halaman/fitur ini selanjutnya.
                            let url =
                                `{{ url('simrs/pendaftaran/pasien-baru-dari-mjkn') }}?noka=${data}`;
                            return `<a href="javascript:void(0);" onclick="konfirmasiPasien('${url}')" class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" title="Konfirmasi & Daftarkan Pasien"><i class="fas fa-arrow-right"></i></a>`;
                        }
                    }
                ],
                order: [],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#btSearch').on('click', function() {
                table.draw();
            });
        });

        function konfirmasiPasien(url) {
            // Membuka halaman pendaftaran baru di tab/window baru
            popupwindow(url, 'Pendaftaran Pasien Baru MJKN', 1200, 800, 'yes');
        }

        function popupwindow(url, title, w, h, scroll) {
            let left = (screen.width / 2) - (w / 2);
            let top = (screen.height / 2) - (h / 2);
            return window.open(url, title,
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=${scroll}, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`
                );
        }
    </script>
@endsection
