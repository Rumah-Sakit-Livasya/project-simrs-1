<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="update-form">
                @method('patch')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Checklist Harian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="checklist_harian_category_id">Kategori</label>
                        <select class="form-control select2 @error('checklist_harian_category_id') is-invalid @enderror"
                            id="checklist_harian_category_id" name="checklist_harian_category_id">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($checklistKategori as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('checklist_harian_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kegiatan">Kegiatan</label>
                        <input type="text" value="{{ old('kegiatan') }}"
                            class="form-control @error('kegiatan') is-invalid @enderror" id="kegiatan" name="kegiatan"
                            placeholder="Nama Kategori">
                        @error('kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil mr-1"></span>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
