@extends('inc.layout')
@section('title', 'Rujukan Khusus')

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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl1" class="form-label">Periode Rujukan</label>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">No Rujukan</label>
                                    <input type="text" value="" id="norujukan" name="norujukan"
                                        class="form-control" placeholder="Cari berdasarkan nomor rujukan...">
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
                <h2><i class="fas fa-list-alt mr-2"></i> Data Rujukan Khusus (dari server BPJS)</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="rujukan-khusus-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Kartu</th>
                                <th>Nama Peserta</th>
                                <th>No Rujukan</th>
                                <th>ID Rujukan</th>
                                <th>Diagnosa PPK</th>
                                <th>Rujukan Awal</th>
                                <th>Rujukan Berakhir</th>
                                <th>Fungsi</th>
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
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Datepicker
            $('#tgl1, #tgl2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = $('#rujukan-khusus-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-rujukan-khusus-data') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl1 = $('#tgl1').val();
                        d.tgl2 = $('#tgl2').val();
                        d.norujukan = $('#norujukan').val();
                    }
                },
                columns: [{
                        data: 'nokartu',
                        name: 'nokartu'
                    },
                    {
                        data: 'nama_peserta',
                        name: 'nama_peserta'
                    },
                    {
                        data: 'norujukan',
                        name: 'norujukan'
                    },
                    {
                        data: 'idrujukan',
                        name: 'idrujukan'
                    },
                    {
                        data: 'diagppk',
                        name: 'diagppk'
                    },
                    {
                        data: 'tglrujukan_awal',
                        name: 'tglrujukan_awal'
                    },
                    {
                        data: 'tglrujukan_berakhir',
                        name: 'tglrujukan_berakhir'
                    },
                    {
                        data: 'idrujukan',
                        name: 'fungsi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let url =
                            `{{ url('vclaim/rujukankhusus') }}/${row.idrujukan}`; // Menggunakan idrujukan
                            return `<a href="javascript:void(0);" onclick="popupwindow('${url}','Rujukan Khusus', 950, 700, 'yes');" class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" title="Buat Rujukan Khusus"><i class="fas fa-share-square"></i></a>`;
                        }
                    }
                ],
                order: [], // Menonaktifkan sorting default karena data dari API
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#btSearch').on('click', function() {
                table.draw();
            });
        });

        // Fungsi helper untuk popup
        function popupwindow(url, title, w, h, scroll) {
            let left = (screen.width / 2) - (w / 2);
            let top = (screen.height / 2) - (h / 2);
            return window.open(url, title,
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=${scroll}, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`
            );
        }
    </script>
@endsection
