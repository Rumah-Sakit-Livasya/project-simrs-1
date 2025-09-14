@extends('inc.layout')
@section('title', 'Lembar Pengajuan Klaim')

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
                            {{-- Kolom Kiri --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl1" class="form-label">Periode Tanggal Registrasi</label>
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
                                <div class="form-group">
                                    <label class="form-label">No SEP</label>
                                    <input type="text" value="" id="nosep" name="nosep" class="form-control">
                                </div>
                            </div>
                            {{-- Kolom Kanan --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status LPK</label>
                                    <select name="lpk" id="lpk" class="form-control select2">
                                        <option value="">Semua</option>
                                        <option value="sudah">Sudah di Buat Pengajuan Klaim</option>
                                        <option value="belum">Belum di Buat Pengajuan Klaim</option>
                                    </select>
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
                <h2><i class="fas fa-list-alt mr-2"></i> Daftar Lembar Pengajuan Klaim</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="lpk-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No SEP</th>
                                <th>No Kartu</th>
                                <th>Nama</th>
                                <th>Tanggal Masuk</th>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi plugin
            $('.select2').select2({
                width: '100%'
            });
            $('#tgl1, #tgl2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = $('#lpk-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-lpk-data') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl1 = $('#tgl1').val();
                        d.tgl2 = $('#tgl2').val();
                        d.lpk = $('#lpk').val();
                        d.nosep = $('#nosep').val();
                    }
                },
                columns: [{
                        data: 'nosep',
                        name: 'bpjs_seps.sep_number'
                    },
                    {
                        data: 'nokartu',
                        name: 'patients.nomor_penjamin'
                    },
                    {
                        data: 'nama',
                        name: 'patients.name'
                    },
                    {
                        data: 'tglmasuk',
                        name: 'registrations.date'
                    },
                    {
                        data: 'id',
                        name: 'fungsi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let url = `{{ url('vclaim/add_lpk') }}/${row.id}`;
                            let title = row.lpk_id ? 'Lihat/Edit LPK' : 'Buat LPK';
                            let iconClass = row.lpk_id ? 'fas fa-edit text-warning' :
                                'fas fa-plus-circle text-success';

                            return `<a href="javascript:void(0);" onclick="popupwindow('${url}','lpk', 950, 700, 'yes');" class="btn btn-sm btn-icon" data-toggle="tooltip" title="${title}"><i class="${iconClass}"></i></a>`;
                        }
                    }
                ],
                order: [
                    [3, 'desc']
                ], // Default order by Tanggal Masuk
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
