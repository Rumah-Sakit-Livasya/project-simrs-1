<div class="modal fade" id="modal-pilih-obat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-pilih-obat-content" id="modal-pilih-obat-content" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1 mb-0">
                <h5 id="title" class="modal-title font-weight-bold text-center">
                    Pilih Batch Obat 「<span id="nama-obat"></span>」
                </h5>
            </div>
            <div class="modal-body py-2 row">
                <div class="col-md-12">
                    <hr style="border-color: #dedede;" class="mb-1 mt-1">
                </div>
                <br>
                <div id="loading-page" class="loading"></div>
                <div class="col-md-12 mb-3 auto-grid">
                    <div class="row justify-content-center">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-4" style="text-align: right">
                                    <label class="form-label text-end" for="batch">
                                        Cari Batch
                                    </label>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="batch" name="batch">
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <table class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>Batch</th>
                                    <th>Stock</th>
                                    <th>Expired Date</th>
                                </tr>
                            </thead>
                            <tbody id="tableObats">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
