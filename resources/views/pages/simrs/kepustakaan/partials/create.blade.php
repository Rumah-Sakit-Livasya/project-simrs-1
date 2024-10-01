<div class="modal fade" id="modal-tambah-kepustakaan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah File/Folder</h5>
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
                                    <div class="form-group">
                                        <label class="d-block">Tipe (File/Folder)</label>
                                        <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                            <input type="radio" checked="" class="custom-control-input"
                                                id="type_file" name="type" value='file'>
                                            <label class="custom-control-label" for="type_file">File</label>
                                        </div>
                                        <div class="custom-control d-inline-block custom-radio mt-2">
                                            <input type="radio" class="custom-control-input" id="type_folder"
                                                name="type" value='folder'>
                                            <label class="custom-control-label" for="type_folder">Folder</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="kategori">
                                            Kategori <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <select class="select2 form-control w-100" id="kategori" name="kategori">
                                            <option value="Regulasi">Regulasi</option>
                                            <option value="Laporan">Laporan</option>
                                            <option value="Perizinan">Perizinan</option>
                                            <option value="Mutu dan Manajemen Resiko">Mutu dan Manajemen Resiko</option>
                                            <option value="File Unit Lainnya">File Unit Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="parent_id">
                                            Parent (Folder) <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <select class="select2 form-control w-100" id="parent_id" name="parent_id">
                                            <option value=""></option>
                                            @foreach ($kepustakaan->where('type', 'folder') as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="organization_id">
                                            Unit <span class="text-danger fw-bold">*</span>
                                        </label>
                                        @if (auth()->user()->can('master kepustakaan') || auth()->user()->hasRole('super admin'))
                                            <select class="select2 form-control w-100" id="organization_id"
                                                name="organization_id">
                                                <option value=""></option>
                                                @foreach ($organizations as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select class="select2 form-control w-100" id="organization_id"
                                                name="organization_id">

                                                <option value="{{ auth()->user()->employee->organization->id }}">
                                                    {{ auth()->user()->employee->organization->name }}</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="name">
                                        Nama (File/Folder) <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukan nama file/folder...">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="name">
                                        Upload (File/Folder) <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file" id="customFile">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-tambah"
                        class="btn mx-1 btn-tambah btn-primary text-white" title="Hapus">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>Tambah
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
