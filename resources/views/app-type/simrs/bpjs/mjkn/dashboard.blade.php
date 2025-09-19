@extends('inc.layout')
@section('title', 'Dashboard Monitoring Antrean MJKN')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">SIMRS</a></li>
            <li class="breadcrumb-item">BPJS</li>
            <li class="breadcrumb-item active">Dashboard Monitoring Antrean MJKN</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class="fal fa-filter mr-2"></i>
                            Filter Data Antrean
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Filter Section --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="filterPoli">Poli</label>
                                        <select id="filterPoli" class="form-control select2">
                                            <option value="">-- Pilih Poli --</option>
                                            @foreach ($departements as $departement)
                                                <option value="{{ $departement->kode_poli }}">
                                                    {{ $departement->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="filterDokter">Dokter</label>
                                        <select id="filterDokter" class="form-control select2">
                                            <option value="">-- Pilih Dokter --</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->kode_dpjp }}">{{ $doctor->employee->fullname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label" for="filterTanggal">Tanggal Periksa</label>
                                        <input type="text" id="filterTanggal" class="form-control datepicker"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <button id="btnRefresh" class="btn btn-primary">
                                            <i class="fal fa-sync mr-1"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            {{-- Result Section (Hidden by default) --}}
                            <div id="result-container" class="row d-none">
                                <div class="col-md-6">
                                    <h5 class="frame-heading">Informasi Antrean Poli</h5>
                                    <div class="frame-wrap">
                                        <table class="table table-clean m-0">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 40%;"><strong>Nama Poli</strong></td>
                                                    <td id="resNamaPoli">-</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama Dokter</strong></td>
                                                    <td id="resNamaDokter">-</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Antrean</strong></td>
                                                    <td id="resTotalAntrean">-</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sisa Antrean</strong></td>
                                                    <td id="resSisaAntrean">-</td>
                                                </tr>
                                                <tr class="bg-primary-50">
                                                    <td class="fs-lg"><strong>Antrean Dipanggil</strong></td>
                                                    <td class="fs-xl fw-700" id="resAntreanPanggil">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Kolom Kanan: Informasi Kuota --}}
                                <div class="col-md-6">
                                    <h5 class="frame-heading">Informasi Kuota</h5>
                                    <div class="frame-wrap">
                                        <table class="table table-clean m-0">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 40%;"><strong>Sisa Kuota JKN</strong></td>
                                                    <td id="resSisaKuotaJkn">-</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Kuota JKN</strong></td>
                                                    <td id="resKuotaJkn">-</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sisa Kuota Non-JKN</strong></td>
                                                    <td id="resSisaKuotaNonJkn">-</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total Kuota Non-JKN</strong></td>
                                                    <td id="resKuotaNonJkn">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%'
            });

            // Initialize Datepicker
            $('.datepicker').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            // Reset result fields
            function resetResultFields() {
                $('#resNamaPoli').text('-');
                $('#resNamaDokter').text('-');
                $('#resTotalAntrean').text('-');
                $('#resSisaAntrean').text('-');
                $('#resAntreanPanggil').text('-');
                $('#resSisaKuotaJkn').text('-');
                $('#resKuotaJkn').text('-');
                $('#resSisaKuotaNonJkn').text('-');
                $('#resKuotaNonJkn').text('-');
            }

            // Fungsi utama untuk mengambil data status antrean
            function fetchStatusAntrean() {
                const kodePoli = $('#filterPoli').val();
                const kodeDokter = $('#filterDokter').val();
                const tanggalPeriksa = $('#filterTanggal').val();

                if (!kodePoli || !kodeDokter || !tanggalPeriksa) {
                    showErrorAlertNoRefresh('Poli, Dokter, dan Tanggal Periksa harus diisi.');
                    return;
                }

                // Tampilkan loading
                const btn = $('#btnRefresh');
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                // Reset field dan sembunyikan container hasil
                resetResultFields();
                $('#result-container').addClass('d-none');

                $.ajax({
                    url: "{{ route('api.mjkn.token') }}", // Menggunakan named route
                    type: 'GET',
                    headers: {
                        'X-Username': '{{ config('mjkn.ws_username') }}',
                        'X-Password': '{{ config('mjkn.ws_password') }}'
                    },
                    success: function(tokenData) {
                        if (tokenData.metadata.code !== 200) {
                            throw new Error('Gagal mendapatkan token: ' + tokenData.metadata.message);
                        }

                        const token = tokenData.response.token;

                        // Gunakan token untuk mengambil status antrean
                        $.ajax({
                            url: "{{ route('api.mjkn.statusantrean') }}", // Menggunakan named route
                            type: 'POST',
                            headers: {
                                'X-Token': token,
                                'X-Username': '{{ config('mjkn.ws_username') }}',
                                'Accept': 'application/json',
                            },
                            data: {
                                kodepoli: kodePoli,
                                kodedokter: kodeDokter,
                                tanggalperiksa: tanggalPeriksa,
                                _token: '{{ csrf_token() }}' // Tambahkan CSRF token untuk keamanan
                            },
                            success: function(data) {
                                if (data.metadata.code === 200) {
                                    const res = data.response;
                                    $('#resNamaPoli').text(res.namapoli);
                                    $('#resNamaDokter').text(res.namadokter);
                                    $('#resTotalAntrean').text(res.totalantrean);
                                    $('#resSisaAntrean').text(res.sisaantrean);
                                    $('#resAntreanPanggil').text(res.antreanpanggil);
                                    $('#resSisaKuotaJkn').text(res.sisakuotajkn);
                                    $('#resKuotaJkn').text(res.kuotajkn);
                                    $('#resSisaKuotaNonJkn').text(res.sisakuotanonjkn);
                                    $('#resKuotaNonJkn').text(res.kuotanonjkn);

                                    // Tampilkan container hasil
                                    $('#result-container').removeClass('d-none');
                                } else {
                                    showErrorAlertNoRefresh('Error: ' + data.metadata
                                        .message);
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error fetching status:', textStatus,
                                    errorThrown);
                                showErrorAlertNoRefresh(
                                    'Gagal mengambil data status antrean. Cek konsol untuk detail.'
                                );
                            },
                            complete: function() {
                                // Sembunyikan loading
                                btn.prop('disabled', false).html(
                                    '<i class="fal fa-sync mr-1"></i> Refresh');
                            }
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching token:', textStatus, errorThrown);
                        showErrorAlertNoRefresh(
                            'Gagal mendapatkan token otentikasi. Cek konsol untuk detail.');
                        // Sembunyikan loading jika gagal di tahap token
                        btn.prop('disabled', false).html('<i class="fal fa-sync mr-1"></i> Refresh');
                    }
                });
            }

            // Event listener untuk tombol refresh
            $('#btnRefresh').on('click', fetchStatusAntrean);
        });
    </script>
@endsection
