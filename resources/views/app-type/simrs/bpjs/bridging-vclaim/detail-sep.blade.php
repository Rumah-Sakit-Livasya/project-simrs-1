@extends('inc.layout')
@section('title', 'Detail SEP Peserta')

@section('extended-css')
    <style>
        #data_sep {
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

        .table-detail .bg-success-custom {
            background-color: #1dc9b7 !important;
            color: white;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fas fa-search mr-2"></i> Pencarian Detail SEP</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-search-sep" onsubmit="return false;">
                                <div class="form-group">
                                    <label class="form-label" for="sep">Nomor SEP</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sep"
                                            placeholder="Masukkan Nomor SEP yang akan dicari..." required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit" id="btnSearch">
                                                <i class="fas fa-search mr-1"></i> Cari SEP
                                            </button>
                                        </div>
                                    </div>
                                    <div class="help-block">* Data SEP yang muncul adalah data dari VClaim BPJS.</div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel" id="data_sep">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-bordered table-hover table-detail">
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center bg-primary-custom">DATA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Peserta</td>
                                        <td id="nama"></td>
                                        <td>No Kartu</td>
                                        <td id="noka"></td>
                                    </tr>
                                    <tr>
                                        <td>No RM</td>
                                        <td id="norm"></td>
                                        <td>Tanggal Lahir</td>
                                        <td id="tgl_lahir"></td>
                                    </tr>
                                    <tr>
                                        <td>Hak Kelas</td>
                                        <td id="hakkelas"></td>
                                        <td>Asuransi</td>
                                        <td id="asuransi"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center bg-primary-custom">DATA SEP</td>
                                    </tr>
                                    <tr>
                                        <td>No SEP</td>
                                        <td id="nosep"></td>
                                        <td>Tanggal SEP</td>
                                        <td id="tglsep"></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Pelayanan</td>
                                        <td id="jenpel"></td>
                                        <td>Kelas Rawat</td>
                                        <td id="kelasrawat"></td>
                                    </tr>
                                    <tr>
                                        <td>Poli</td>
                                        <td id="poli"></td>
                                        <td>Eksekutif</td>
                                        <td id="eksekutif"></td>
                                    </tr>
                                    <tr>
                                        <td>Diagnosa</td>
                                        <td colspan="3" id="diagnosa"></td>
                                    </tr>
                                    <tr>
                                        <td>Catatan</td>
                                        <td colspan="3" id="catatan"></td>
                                    </tr>
                                    <tr>
                                        <td>Penjamin</td>
                                        <td colspan="3" id="penjamin"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center bg-success-custom">DATA PASIEN DI SIMRS</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Pasien</td>
                                        <td id="nama_pasien_simrs"></td>
                                        <td>No Registrasi</td>
                                        <td id="noreg_simrs"></td>
                                    </tr>
                                    <tr>
                                        <td>No RM</td>
                                        <td id="no_rm_simrs"></td>
                                        <td>Tanggal Registrasi</td>
                                        <td id="tgl_reg_simrs"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex justify-content-end">
                            <input type="hidden" id="pregid_simrs">
                            <button type="button" class="btn btn-warning waves-effect waves-themed mr-2"
                                onclick="edit_sep()">
                                <i class="fas fa-edit"></i> Edit SEP
                            </button>
                            <button type="button" class="btn btn-info waves-effect waves-themed mr-2"
                                onclick="print_sep()">
                                <i class="fas fa-print"></i> Print SEP
                            </button>
                            <button type="button" class="btn btn-secondary waves-effect waves-themed mr-2"
                                onclick="update_tgl_sep()">
                                <i class="fas fa-calendar-alt"></i> Tanggal Pulang
                            </button>
                            <button type="button" class="btn btn-danger waves-effect waves-themed" onclick="hapus_sep()">
                                <i class="fas fa-trash"></i> Hapus SEP
                            </button>
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
            $('#form-search-sep').on('submit', function(e) {
                e.preventDefault();
                search_sep();
            });
        });

        function search_sep() {
            const btn = $('#btnSearch');
            const sep = $('#sep').val();

            if (!sep) {
                showErrorAlertNoRefresh('Nomor SEP tidak boleh kosong.');
                return;
            }

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Loading...');
            $('#data_sep').hide();

            $.ajax({
                type: 'POST',
                url: '{{ route('bpjs.bridging-vclaim.get-detail-sep-data') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    sep: sep
                },
                success: function(data) {
                    if (data.metaData.code == '200') {
                        const res = data.response;
                        // Populate data VClaim
                        $('#nama').text(`${res.peserta.nama} (${res.peserta.kelamin})`);
                        $('#noka').text(res.peserta.noKartu);
                        $('#norm').text(res.peserta.noMr);
                        $('#tgl_lahir').text(res.peserta.tglLahir);
                        $('#hakkelas').text(res.peserta.hakKelas);
                        $('#asuransi').text(res.peserta.asuransi || '-');
                        $('#nosep').text(res.noSep);
                        $('#tglsep').text(res.tglSep);
                        $('#jenpel').text(res.jnsPelayanan);
                        $('#kelasrawat').text(res.kelasRawat);
                        $('#poli').text(res.poli || '-');
                        $('#eksekutif').text(res.poliEksekutif == '1' ? 'Ya' : 'Tidak');
                        $('#diagnosa').text(res.diagnosa);
                        $('#catatan').text(res.catatan || '-');
                        $('#penjamin').text(res.penjamin || '-');

                        // Populate data SIMRS
                        $('#nama_pasien_simrs').text(res.nama_pasien_simrs);
                        $('#noreg_simrs').text(res.noreg_simrs);
                        $('#no_rm_simrs').text(res.peserta.noMr);
                        $('#tgl_reg_simrs').text(res.tgl_reg_simrs);
                        $('#pregid_simrs').val(res.pregid_simrs);

                        $('#data_sep').show();
                        showSuccessAlert(data.metaData.message);
                    } else {
                        showErrorAlertNoRefresh(`${data.metaData.code}: ${data.metaData.message}`);
                    }
                },
                error: function() {
                    showErrorAlertNoRefresh('Gagal menghubungi server.');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Cari SEP');
                }
            });
        }

        function edit_sep() {
            const pregid = $('#pregid_simrs').val();
            if (!pregid) {
                showErrorAlertNoRefresh('Data registrasi pasien di SIMRS tidak ditemukan untuk SEP ini.');
                return;
            }
            // Ganti URL dengan rute yang benar jika sudah ada
            let url = `{{ url('vclaim/edit_sep') }}/${pregid}`;
            popupFull(url);
        }

        function print_sep() {
            const pregid = $('#pregid_simrs').val();
            if (!pregid) {
                showErrorAlertNoRefresh('Data registrasi pasien di SIMRS tidak ditemukan untuk SEP ini.');
                return;
            }
            let url = `{{ url('vclaim/print_sep_pdf') }}/${pregid}`;
            popupwindow(url, 'Print SEP', 1100, 900, 'no');
        }

        function update_tgl_sep() {
            const pregid = $('#pregid_simrs').val();
            if (!pregid) {
                showErrorAlertNoRefresh('Data registrasi pasien di SIMRS tidak ditemukan untuk SEP ini.');
                return;
            }
            let url = `{{ url('vclaim/update_tgl_pulang') }}/${pregid}`;
            popupwindow(url, 'Update Tgl Pulang', 500, 400, 'no');
        }

        function hapus_sep() {
            const sep = $('#sep').val();
            const noreg = $('#noreg_simrs').text();

            let message = "Apakah Anda yakin akan menghapus SEP ini dari server BPJS?";
            if (noreg && noreg !== '-') {
                message =
                    `No SEP ini terhubung dengan No Registrasi <b>${noreg}</b> di SIMRS. <br><strong class='text-danger'>Jika registrasi ini masih aktif, tindakan ini dapat menyebabkan klaim ditolak.</strong><br><br>Lanjutkan menghapus SEP dari server BPJS?`;
            }

            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                html: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus SEP!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('bpjs.bridging-vclaim.delete-sep-data') }}',
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                            sep: sep
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                $('#data_sep').hide();
                                $('#sep').val('');
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON;
                            showErrorAlertNoRefresh(error.message || 'Gagal menghapus SEP.');
                        }
                    });
                }
            });
        }

        // Fungsi helper untuk popup
        function popupwindow(url, title, w, h, scroll) {
            let left = (screen.width / 2) - (w / 2);
            let top = (screen.height / 2) - (h / 2);
            return window.open(url, title,
                `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=${scroll}, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`
                );
        }

        function popupFull(url) {
            let params = 'width=' + screen.width + ', height=' + screen.height +
                ', top=0, left=0, scrollbars=yes, fullscreen=yes';
            let newwin = window.open(url, 'Popup Full', params);
            if (window.focus) {
                newwin.focus();
            }
            return false;
        }
    </script>
@endsection
