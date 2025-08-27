<div class="modal fade" id="modal-signa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-signa-content" id="modal-signa-content" role="document">
        <div class="modal-content">
            <div class="modal-header pb-1 mb-0">
                <h5 id="title" class="modal-title font-weight-bold text-center">
                    <button class="btn btn-danger waves-effect waves-themed" type="button"
                        onclick="$('#signa-content').val('')"><i class="fal fa-trash"></i></button>
                    Signa「<span id="nama-obat"></span>」
                </h5>
            </div>
            <div class="modal-body py-2 row">
                <div class="col-md-12">
                    <hr style="border-color: #dedede;" class="mb-1 mt-1">
                </div>
                <div class="col-md-12 mb-3 auto-grid">
                    <input type="text" name="signa-content" id="signa-content" class="form-control">
                </div>
                <div class="text-center">Kata Kunci: </div>
                <div class="col-md-12 mb-3 auto-grid">
                    @foreach ($signas as $signa)
                        <button type="button" class="btn btn-secondary mr-2 mb-2 signa-button"
                            data-value="{{ $signa->kata }}">
                            {{ $signa->kata }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
