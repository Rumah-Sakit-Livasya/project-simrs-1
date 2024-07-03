<div class="modal fade p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form-link">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Approval Line</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="form-group">
                        <label for="approval_line">Approval Line</label>
                        <select class="select2 form-control w-100  @error('approval_line') is-invalid @enderror"
                            id="approval_line_edit" name="approval_line">
                            <option value=""></option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}">{{ $item->id }} -
                                    {{ $item->fullname }}
                                </option>
                            @endforeach
                        </select>
                        @error('approval_line_edit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="approval_line_parent">Approval Line Parent</label>
                        <select class="select2 form-control w-100  @error('approval_line_parent') is-invalid @enderror"
                            id="approval_line_parent_edit" name="approval_line_parent">
                            <option value=""></option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}">{{ $item->id }}
                                    -
                                    {{ $item->fullname }}
                                </option>
                            @endforeach
                        </select>
                        @error('approval_line_parent')
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
