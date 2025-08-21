<!-- Modal SBAR -->
<div class="modal fade" id="modal-sbar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form SBAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sbar-form">
                    @csrf
                    <input type="hidden" id="sbar_cppt_id" name="cppt_id" value="">

                    {{-- Bagian S, B, A, R tidak berubah --}}
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Situation</label>
                        <textarea name="situation" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-danger">Background</label>
                        <textarea name="background" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Assessment</label>
                        <textarea name="assessment" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Recommendation</label>
                        <textarea name="recommendation" class="form-control" rows="3" required></textarea>
                    </div>

                    <hr class="mt-4 mb-4">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-5 text-center">
                            @include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Yang menerima perintah lisan,',
                                'name_prefix' => 'signatures[penerima]',
                                'pic' => auth()->user()->employee?->fullname ?? auth()->user()->name,
                                'index' => 1,
                                'signature_model' => null,
                            ])
                        </div>
                        <div class="col-md-5 text-center">
                            @include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Yang memberi perintah lisan,',
                                'name_prefix' => 'signatures[pemberi]',
                                'pic' => '',
                                'index' => 2,
                                'signature_model' => null,
                            ])
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="save-sbar-btn">Simpan</button>
            </div>
        </div>
    </div>
</div>
