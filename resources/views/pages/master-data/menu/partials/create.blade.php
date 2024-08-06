<div class="modal fade p-0" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <label for="title">Menu</label>
                                <input type="text" value="{{ old('title') }}" class="form-control" id="title"
                                    name="title" placeholder="Masukan nama menu...">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <label for="title">Parent Menu</label>
                                <select class="select2 form-control w-100  @error('parent_id') is-invalid @enderror"
                                    id="parent_id1" name="parent_id">
                                    <option value=""></option>
                                    @foreach ($menus as $item)
                                        <option value="{{ $item->id }}">{{ $item->id }} -
                                            {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <label for="title">URL</label>
                                <input type="text" class="form-control" id="url" name="url"
                                    placeholder="/attendances/index...">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <label for="title">Icon</label>
                                <input type="text" class="form-control" id="icon" name="icon"
                                    placeholder="bx bxs-user-pin">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <label for="title">Urutan Menu</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order"
                                    placeholder="1">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <label for="title">Permission</label>
                                <input type="text" class="form-control" id="permission" name="permission"
                                    placeholder="view absensi">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <label for="title">Type Web</label>
                                <select class="select2 form-control w-100  @error('parent_id') is-invalid @enderror"
                                    id="type" name="type">
                                    <option value="simrs">SIMRS</option>
                                    <option value="hr">SMART HR</option>
                                </select>
                            </div>
                        </div>
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
