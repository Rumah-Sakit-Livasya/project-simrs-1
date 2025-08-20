<div class="modal fade" id="modal-intervensi-keperawatan" tabindex="-1" role="dialog"
    aria-labelledby="modalLabelIntervensi" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelIntervensi">Pilih Intervensi Keperawatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">Form Pencarian</div>
                    <div class="card-body">
                        <form id="intervensi-search-form">
                            <div class="input-group">
                                <input type="text" class="form-control" id="intervensi_search_input"
                                    placeholder="Ketik nama intervensi...">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"
                                        id="intervensi_search_btn">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100" id="intervensi-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Intervensi</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
