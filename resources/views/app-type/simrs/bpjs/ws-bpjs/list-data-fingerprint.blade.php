@extends('inc.layout')
@section('title', 'List Data Fingerprint Peserta')

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
                                    <label for="tgl_sep" class="form-label">Tanggal SEP</label>
                                    <input type="text" class="form-control bg-white" name="tgl_sep" id="tgl_sep"
                                        value="{{ date('d-m-Y') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="layanan">Jenis Pelayanan</label>
                                    <select class="form-control select2" name="layanan" id="layanan">
                                        <option value="2">RAWAT JALAN</option>
                                        <option value="1">RAWAT INAP</option>
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
                <h2><i class="fas fa-list-alt mr-2"></i> List Data Fingerprint (dari server BPJS)</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="fingerprint-list-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Kartu</th>
                                <th>No SEP</th>
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
            $('#tgl_sep').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = $('#fingerprint-list-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('ws-bpjs.get-list-fingerprint-data') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl_sep = $('#tgl_sep').val();
                        d.layanan = $('#layanan').val();
                    }
                },
                columns: [{
                        data: 'noKartu',
                        name: 'noKartu'
                    },
                    {
                        data: 'noSep',
                        name: 'noSep'
                    },
                ],
                order: [], // Menonaktifkan sorting default
            });

            $('#btSearch').on('click', function() {
                table.draw();
            });
        });
    </script>
@endsection
