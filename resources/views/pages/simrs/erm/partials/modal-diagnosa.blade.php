<!-- Modal Diagnosa Keperawatan -->
<div class="modal fade" id="modal-diagnosa-keperawatan" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Diagnosa Keperawatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form Pencarian -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        Form Pencarian
                    </div>
                    <div class="card-body">
                        <form id="diagnosa-search-form">
                            <div class="form-group row">
                                <label for="diagnosa_search_input" class="col-sm-2 col-form-label">Diagnosa</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="diagnosa_search_input"
                                        placeholder="Ketik nama diagnosa...">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary w-100"
                                        id="diagnosa_search_btn">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100" id="diagnosa-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Domain</th>
                                <th>Kode</th>
                                <th>Diagnosa</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi oleh Datatables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
