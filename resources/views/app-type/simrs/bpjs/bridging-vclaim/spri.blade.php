@extends('inc.layout')
@section('title', 'SPRI Pasien')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel">
            <div class="panel-hdr">
                <h2><i class="fas fa-search mr-2"></i> Form Pencarian SPRI</h2>
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
                                    <label class="form-label">Status SPRI</label>
                                    <select name="show_rencana_kontrol" id="show_rencana_kontrol"
                                        class="form-control select2">
                                        <option value="">Semua</option>
                                        <option value="sudah">Sudah di Buat SPRI</option>
                                        <option value="belum">Belum di Buat SPRI</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No SPRI</label>
                                    <input type="text" value="" id="no_surat_kontrol" name="no_surat_kontrol"
                                        class="form-control">
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
                <h2><i class="fas fa-list-alt mr-2"></i> Daftar SPRI Pasien BPJS Kesehatan</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="spri-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Kartu</th>
                                <th>Nama</th>
                                <th>No Reg</th>
                                <th>Department</th>
                                <th>No SEP</th>
                                <th>Tgl Rencana Inap</th>
                                <th>No SPRI</th>
                                <th>Poli Kontrol</th>
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

            var table = $('#spri-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-rencana-kontrol-data') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl1 = $('#tgl1').val();
                        d.tgl2 = $('#tgl2').val();
                        d.show_rencana_kontrol = $('#show_rencana_kontrol').val();
                        d.nosep = $('#nosep').val();
                        d.no_surat_kontrol = $('#no_surat_kontrol').val();
                        d.jenis_kontrol = '1'; // <-- INI KUNCINYA: Kirim parameter jenis_kontrol=1
                    }
                },
                columns: [{
                        data: 'nokartu',
                        name: 'patients.nomor_penjamin'
                    },
                    {
                        data: 'nama',
                        name: 'patients.name'
                    },
                    {
                        data: 'noreg',
                        name: 'registrations.registration_number'
                    },
                    {
                        data: 'name_formal',
                        name: 'departements.name'
                    },
                    {
                        data: 'nosep',
                        name: 'bpjs_seps.sep_number'
                    },
                    {
                        data: 'tgl_rencana_kontrol',
                        name: 'bpjs_rencana_kontrols.tgl_rencana_kontrol'
                    },
                    {
                        data: 'no_surat_kontrol',
                        name: 'bpjs_rencana_kontrols.no_surat_kontrol'
                    },
                    {
                        data: 'poli_kontrol',
                        name: 'bpjs_rencana_kontrols.poli_kontrol_nama'
                    },
                    {
                        data: 'id',
                        name: 'fungsi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let url =
                            `{{ url('vclaim/form_rencana_kontrol/1') }}/${row.id}`; // 1 = Jenis Kontrol (SPRI)
                            let title = (row.no_surat_kontrol !== '-') ? 'Edit SPRI' : 'Buat SPRI';
                            let iconClass = (row.no_surat_kontrol !== '-') ?
                                'fas fa-edit text-warning' : 'fas fa-plus-circle text-success';

                            return `<a href="javascript:void(0);" onclick="popupwindow('${url}','surat_kontrol', 950, 700, 'yes');" class="btn btn-sm btn-icon" data-toggle="tooltip" title="${title}"><i class="${iconClass}"></i></a>`;
                        }
                    }
                ],
                order: [
                    [5, 'desc']
                ], // Default order by Tgl Rencana Kontrol
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
