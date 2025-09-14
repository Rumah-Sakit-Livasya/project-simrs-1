@extends('inc.layout')
@section('title', 'Detail PRB Peserta')

@section('extended-css')
    <style>
        #data_prb {
            display: none;
        }

        .table-detail td:first-child {
            background-color: #f3f3f3;
            font-weight: 500;
            width: 25%;
        }

        .table-detail .bg-primary-custom {
            background-color: #886ab5 !important;
            color: white;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                {{-- Form Pencarian --}}
                <div class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fas fa-search mr-2"></i> Pencarian Detail PRB</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-search-prb" onsubmit="return false;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="sep">Nomor SEP</label>
                                            <input type="text" class="form-control" id="sep"
                                                placeholder="Masukkan No SEP asal..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="srb">Nomor SRB</label>
                                            <input type="text" class="form-control" id="srb"
                                                placeholder="Masukkan No Surat Rujuk Balik..." required>
                                        </div>
                                    </div>
                                </div>
                                <div class="help-block">* Data PRB yang muncul adalah data dari VClaim BPJS.</div>
                            </form>
                        </div>
                        <div class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex">
                            <button class="btn btn-primary ml-auto" type="button" id="btnPrb">
                                <i class="fas fa-search mr-1"></i> Cari Data PRB
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Hasil Pencarian --}}
                <div class="panel" id="data_prb">
                    <div class="panel-hdr">
                        <h2><i class="fas fa-file-medical-alt mr-2"></i> Detail Program Rujuk Balik</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-bordered table-hover table-detail">
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center bg-primary-custom">DATA SURAT</td>
                                    </tr>
                                    <tr>
                                        <td>No. SRB</td>
                                        <td id="noSrb"></td>
                                        <td>Tgl. SRB</td>
                                        <td id="tglSrb"></td>
                                    </tr>
                                    <tr>
                                        <td>No. SEP Asal</td>
                                        <td id="noSep"></td>
                                        <td>Program PRB</td>
                                        <td id="programPrb"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center bg-primary-custom">DATA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td>No. Kartu</td>
                                        <td id="noKartu"></td>
                                        <td>Nama Peserta</td>
                                        <td id="nama"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center bg-primary-custom">DETAIL MEDIS</td>
                                    </tr>
                                    <tr>
                                        <td>DPJP</td>
                                        <td colspan="3" id="dpjp"></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td colspan="3" id="keterangan"></td>
                                    </tr>
                                    <tr>
                                        <td>Saran</td>
                                        <td colspan="3" id="saran"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="mt-4">Daftar Obat</h5>
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Kode Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Signa</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody id="obat-list">
                                    {{-- Data obat akan diisi oleh Javascript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        $(document).ready(function() {
            $('#btnPrb').on('click', function() {
                const btn = $(this);
                const srb = $('#srb').val();
                const sep = $('#sep').val();

                if (!srb || !sep) {
                    showErrorAlertNoRefresh('Nomor SEP & Nomor SRB harus diisi.');
                    return;
                }

                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span> Loading...');
                $('#data_prb').hide();
                $('#obat-list').empty(); // Kosongkan daftar obat

                $.ajax({
                    type: 'POST',
                    url: '{{ route('prb.get-detail-prb-data') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        srb: srb,
                        sep: sep
                    },
                    success: function(data) {
                        if (data.metaData.code == '200') {
                            const prb = data.response.prb;

                            // Isi data utama
                            $('#noSrb').text(prb.noSrb);
                            $('#tglSrb').text(prb.tglSrb);
                            $('#noSep').text(prb.noSep);
                            $('#programPrb').text(
                                `${prb.programPrb.kode} - ${prb.programPrb.nama}`);
                            $('#noKartu').text(prb.peserta.noKartu);
                            $('#nama').text(prb.peserta.nama);
                            $('#dpjp').text(`${prb.dpjp.kode} - ${prb.dpjp.nama}`);
                            $('#keterangan').text(prb.keterangan);
                            $('#saran').text(prb.saran);

                            // Isi data obat
                            if (prb.obat && prb.obat.obat) {
                                prb.obat.obat.forEach(function(item) {
                                    $('#obat-list').append(`
                                        <tr>
                                            <td>${item.kodeObat}</td>
                                            <td>${item.namaObat}</td>
                                            <td>${item.signa}</td>
                                            <td>${item.jumlah}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                $('#obat-list').append(
                                    '<tr><td colspan="4" class="text-center">Tidak ada data obat.</td></tr>'
                                    );
                            }

                            $('#data_prb').show();
                            showSuccessAlert(data.metaData.message);
                        } else {
                            showErrorAlertNoRefresh(
                                `${data.metaData.code}: ${data.metaData.message}`);
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON;
                        showErrorAlertNoRefresh(error.message || 'Gagal menghubungi server.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-search mr-1"></i> Cari Data PRB');
                    }
                });
            });
        });
    </script>
@endsection
