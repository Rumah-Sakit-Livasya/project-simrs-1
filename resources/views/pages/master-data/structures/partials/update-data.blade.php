<div class="modal fade p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Struktur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="child_organization">Nama Organisasi</label>
                        <select
                            class="select2 ubah form-control w-100  @error('child_organization') is-invalid @enderror"
                            id="child_organization" name="child_organization">
                            @foreach ($organizations as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('child_organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="parent_organization">Parent Organisasi</label>
                        <select
                            class="select2 ubah form-control w-100  @error('parent_organization') is-invalid @enderror"
                            id="parent_organization" name="parent_organization">
                            <option value=""></option>
                            @foreach ($organizations as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-edit">
                            <span class="fal fa-pencil mr-1"></span>
                            Ubah
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
