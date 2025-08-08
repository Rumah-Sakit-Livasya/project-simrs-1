<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Barang Terhitung Dalam Gudang</i></span>
                    &nbsp;
                    <i class="loading fas fa-spinner fa-spin"></i>
                    <span class="loading loading-message text-info">Loading...</span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="loading loading-page"></div>
                <div class="panel-content">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <!-- datatable start -->
                    <table id="datatable" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>Kode PB</th>
                                <th>No Batch</th>
                                <th>Tanggal Terima</th>
                                <th>Tanggal Exp</th>
                                <th>System Stock</th>
                                <th>Actual Stock</th>
                                <th>Adjustment</th>
                                <th>Pergerakan</th>
                                <th>Final Stock</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Kode PB</th>
                                <th>No Batch</th>
                                <th>Tanggal Terima</th>
                                <th>Tanggal Exp</th>
                                <th>System Stock</th>
                                <th>Actual Stock</th>
                                <th>Adjustment</th>
                                <th>Pergerakan</th>
                                <th>Final Stock</th>
                                <th>Keterangan</th>
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
