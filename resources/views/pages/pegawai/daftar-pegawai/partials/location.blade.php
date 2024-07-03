<div class="modal fade p-0" id="tambah-lokasi" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form-location">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Lokasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="location">Nama</label>
                                <input type="text" id="name" name="name" class="form-control" disabled>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="location">Lokasi</label>
                                <select class="select2 form-control w-100  @error('employee_id') is-invalid @enderror"
                                    id="location_name" name="location_name[]" multiple="multiple">
                                    <option value=""></option>
                                    @foreach ($locations as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="tbh-lokasi">
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
