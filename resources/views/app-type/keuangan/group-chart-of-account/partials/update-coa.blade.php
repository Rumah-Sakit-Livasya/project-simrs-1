<div class="modal fade" id="ubah-group{{ $coa->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('group-chart-of-account.update', $coa->id) }}"
                method="post">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Group</label>
                        <input type="text" value="{{ old('name', $coa->name) }}"
                            class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            placeholder="Nama Group">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="description">Keterangan</label>
                        <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $coa->description) }}</textarea>
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
