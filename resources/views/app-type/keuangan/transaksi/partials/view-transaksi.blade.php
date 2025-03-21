<div class="modal fade" id="lihat-transaksi{{ $t->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                @if ($t->foto == null)
                    <div class="alert alert-danger" role="alert">
                        Tidak ada bukti yang diupload
                    </div>
                @else
                    <img src="{{ asset('/storage/' . $t->foto) }}" alt="{{ $t->foto }}"
                        class="img-thumbnail w-100">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
