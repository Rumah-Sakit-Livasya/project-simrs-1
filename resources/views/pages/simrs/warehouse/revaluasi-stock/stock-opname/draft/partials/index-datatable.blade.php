<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar <span class="fw-300"><i>Barang Dalam Gudang</i></span>
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
                                <th>Detail</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Stock System</th>
                                <th>Stock Actual</th>
                                <th>Adjustment</th>
                                <th>Pergerakan</th>
                                <th>Final Stock</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Detail</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Stock System</th>
                                <th>Stock Actual</th>
                                <th>Adjustment</th>
                                <th>Pergerakan</th>
                                <th>Final Stock</th>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- datatable end -->
                </div>
            </div>
        </div>
    </div>
</div>
