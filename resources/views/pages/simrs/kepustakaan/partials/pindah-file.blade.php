<div class="modal fade" id="modal-pindah-file-kepustakaan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="pindah-form"
                enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Edit Folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <div class="row">
                        <div class="col-md-12">
                            <hr style="border-color: #dedede;" class="mb-1 mt-1">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="name">Pindahkan ke Folder <span
                                            class="text-danger fw-bold">*</span></label>
                                    <select class="select2 form-control w-100" id="parent_id_pindah"
                                        name="parent_id">
                                        @if($childrenFolder->count() > 0)
                                        @foreach ($childrenFolder as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                        @else
                                        <option value="" disabled aria-readonly="true">Silahkan Buat Folder Terlebih Dahulu!</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-edit"
                        class="btn mx-1 btn-update btn-primary text-white" title="Hapus">
                        <div class="ikon-edit">
                            <span class="fal fa-plus-circle mr-1"></span>Update
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
