@extends('inc.layout')
@section('title', 'Get Fingerprint Peserta')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class="fas fa-fingerprint mr-2"></i>
                            Get Data Fingerprint Peserta
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-get-fingerprint">
                                <div class="form-group">
                                    <label class="form-label" for="tgl_pelayanan">Tanggal Pelayanan</label>
                                    <input type="text" class="form-control bg-white" name="tgl_pelayanan"
                                        id="tgl_pelayanan" value="{{ date('d-m-Y') }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="noka">Nomor Kartu BPJS</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="noka"
                                            placeholder="Masukkan nomor kartu peserta..." required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit" id="btnSearch">
                                                <i class="fas fa-search mr-1"></i> Get Fingerprint
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Datepicker
            $('#tgl_pelayanan').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: "dd-mm-yyyy",
                autoclose: true
            });

            // Handle submit form
            $('#form-get-fingerprint').on('submit', function(e) {
                e.preventDefault();

                const btn = $('#btnSearch');
                const noka = $('#noka').val();
                const tgl_pelayanan = $('#tgl_pelayanan').val();

                if (!noka) {
                    showErrorAlertNoRefresh('Nomor Kartu tidak boleh kosong.');
                    return;
                }

                // Tampilkan loading di tombol
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    );

                $.ajax({
                    type: 'POST',
                    url: '{{ route('ws-bpjs.get-data-fingerprint') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        noka: noka,
                        tgl_pelayanan: tgl_pelayanan
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                html: `Response Code: <b class="text-success">${response.code}</b><br>Message: <b class="text-success">${response.message}</b><br>Status: <b class="text-success">${response.status}</b>`
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: `Response Code: <b class="text-danger">${response.code}</b><br>Message: <b class="text-danger">${response.message}</b><br>Status: <b class="text-danger">${response.status}</b>`
                            });
                        }
                    },
                    error: function(xhr) {
                        // Menggunakan fungsi showErrorAlertNoRefresh dari layout utama Anda
                        showErrorAlertNoRefresh(
                            'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.');
                    },
                    complete: function() {
                        // Kembalikan tombol ke state semula
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-search mr-1"></i> Get Fingerprint');
                    }
                });
            });
        });
    </script>
@endsection
