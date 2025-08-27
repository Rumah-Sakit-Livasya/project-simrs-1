<style>
    .display-none {
        display: none;
    }

    .popover {
        max-width: 100%;
        max-height:
    }
</style>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Histori Perubahan Master Barang</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        <thead class="bg-primary-600">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Keterangan Edit</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Golongan</th>
                                <th>Kelompok</th>
                                <th>Harga Beli</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ tgl_waktu($log->created_at) }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->keterangan }}</td>
                                    <td>{{ $log->kode_barang }}</td>
                                    <td>{{ $log->nama_barang }}</td>
                                    <td>{{ $log->satuan->nama }}</td>
                                    <td>{{ $log->golongan?->nama }}</td>
                                    <td>{{ $log->kelompok?->nama }}</td>
                                    <td>{{ rp($log->hna) }}</td>
                                    <td>{{ $log->status_aktif ? 'Aktif' : 'Non Aktif' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Keterangan Edit</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Golongan</th>
                                <th>Kelompok</th>
                                <th>Harga Beli</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
