{{--
    FILE INI ADALAH MODAL BOOTSTRAP SHELL untuk tambah item pada distribusi barang farmasi.
    Jangan letakkan konten barang di sini (konten dinamis hasil AJAX akan dimuat pada #modal-add-item-content).
--}}
<div class="modal fade" id="modal-add-item" tabindex="-1" role="dialog" aria-labelledby="modalAddItemLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddItemLabel">Tambah Item dari Gudang Asal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body" id="modal-add-item-content">
                {{-- Konten barang akan dimuat secara dinamis via AJAX --}}
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Memuat...</span>
                    </div>
                    <div>Memuat data barang...</div>
                </div>
            </div>
        </div>
    </div>
</div>
