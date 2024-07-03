<div class="modal fade" id="ubah-akses" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" id="akses-form" method="post"
                enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Akses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Pegawai">Pegawai</label>
                        <select class="select2 form-control w-100 " id="role" name="role">
                            <option value=""></option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ strlen($role->name) < 3 ? strtoupper($role->name) : ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
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
