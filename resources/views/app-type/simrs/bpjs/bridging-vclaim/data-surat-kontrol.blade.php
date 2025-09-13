@extends('inc.layout')
@section('title', 'Data Surat Kontrol dari Webservice')

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
                                    <label for="filter" class="form-label">Filter Berdasarkan</label>
                                    <select name="filter" id="filter" class="form-control select2">
                                        <option value="1">Tanggal Entri</option>
                                        <option value="2">Tanggal Rencana Kontrol</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Pencarian Spesifik</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filtnoka" name="filtnoka">
                                        <label class="custom-control-label" for="filtnoka">Cari Berdasarkan Nomor
                                            Kartu</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Filter by Tanggal --}}
                        <div class="row filter-tgl">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Rentang Tanggal</label>
                                    <div class="input-daterange input-group">
                                        <input type="text" class="form-control bg-white" name="tgl_awal" id="tgl_awal"
                                            value="{{ date('d-m-Y') }}" readonly>
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text fs-xl"><i
                                                    class="fal fa-long-arrow-right"></i></span>
                                        </div>
                                        <input type="text" class="form-control bg-white" name="tgl_akhir" id="tgl_akhir"
                                            value="{{ date('d-m-Y') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Filter by Noka --}}
                        <div class="row filter-noka" style="display: none;">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-control select2">
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09" selected>September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Tahun</label>
                                    <input class="form-control" type="number" id="tahun" name="tahun"
                                        value="{{ date('Y') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Nomor Kartu BPJS</label>
                                    <input class="form-control" type="text" id="noka" name="noka"
                                        placeholder="Masukkan nomor kartu...">
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
                <h2><i class="fas fa-list-alt mr-2"></i> Data Surat Rencana Kontrol</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="surat-kontrol-table" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>No Surat Kontrol</th>
                                <th>Jenis Pelayanan</th>
                                <th>Jenis Kontrol</th>
                                <th>Tgl Kontrol</th>
                                <th>Tgl Terbit</th>
                                <th>Nama Dokter</th>
                                <th>No Kartu</th>
                                <th>Nama Peserta</th>
                                <th>No SEP Asal</th>
                                <th>Poli Asal</th>
                                <th>Poli Tujuan</th>
                                <th>Tgl SEP</th>
                                <th>Detail</th>
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
            $('#tgl_awal, #tgl_akhir').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            // Logic untuk show/hide filter
            $('#filtnoka').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.filter-tgl').hide();
                    $('.filter-noka').show();
                } else {
                    $('.filter-tgl').show();
                    $('.filter-noka').hide();
                }
            });

            var table = $('#surat-kontrol-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('bpjs.bridging-vclaim.list-data-surat-kontrol') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.tgl_awal = $('#tgl_awal').val();
                        d.tgl_akhir = $('#tgl_akhir').val();
                        d.filter = $('#filter').val();
                        d.filtnoka = $('#filtnoka').is(':checked');
                        d.bulan = $('#bulan').val();
                        d.tahun = $('#tahun').val();
                        d.noka = $('#noka').val();
                    }
                },
                columns: [{
                        data: 'noSuratKontrol',
                        name: 'noSuratKontrol'
                    },
                    {
                        data: 'jnsPelayanan',
                        name: 'jnsPelayanan'
                    },
                    {
                        data: 'namaJnsKontrol',
                        name: 'namaJnsKontrol'
                    },
                    {
                        data: 'tglRencanaKontrol',
                        name: 'tglRencanaKontrol'
                    },
                    {
                        data: 'tglTerbitKontrol',
                        name: 'tglTerbitKontrol'
                    },
                    {
                        data: 'namaDokter',
                        name: 'namaDokter'
                    },
                    {
                        data: 'noKartu',
                        name: 'noKartu'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'noSepAsalKontrol',
                        name: 'noSepAsalKontrol'
                    },
                    {
                        data: 'namaPoliAsal',
                        name: 'namaPoliAsal'
                    },
                    {
                        data: 'namaPoliTujuan',
                        name: 'namaPoliTujuan'
                    },
                    {
                        data: 'tglSEP',
                        name: 'tglSEP'
                    },
                    {
                        data: 'noSuratKontrol',
                        name: 'fungsi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let url =
                                `{{ url('vclaim/get_detail_surat_kontrol/noSuratKontrol') }}/${data}`;
                            return `<a href="javascript:void(0);" onclick="popupwindow('${url}','Detail Surat Kontrol', 950, 700, 'yes');" class="btn btn-sm btn-icon btn-info" data-toggle="tooltip" title="Detail Rencana Kontrol"><i class="fas fa-eye"></i></a>`;
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

        function popupwindow(url, title, w, h, scroll) {
            let left = (screen.width / 2) - (w / 2);
            let top = (screen.height / 2) - (h / 2);
            return window.open(url, title,
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=${scroll}, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`
                );
        }
    </script>
@endsection
