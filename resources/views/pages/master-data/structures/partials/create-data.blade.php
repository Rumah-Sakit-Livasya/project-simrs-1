<div class="modal fade p-0" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Structure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="child_organization">Nama Organisasi</label>
                        <select
                            class="select2 form-control tambah w-100  @error('child_organization') is-invalid @enderror"
                            id="child_organization1" name="child_organization">
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
                            class="select2 tambah form-control w-100  @error('parent_organization') is-invalid @enderror"
                            id="parent_organization1" name="parent_organization">
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
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
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
