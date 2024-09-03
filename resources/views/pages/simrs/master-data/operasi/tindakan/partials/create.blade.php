<div class="modal fade" id="modal-tambah-tindakan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Tindakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-2 row">
                    <div class="col-md-12">
                        <hr style="border-color: #dedede;" class="mb-1 mt-1">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="jenis_operasi_id">Jenis Operasi <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="jenis_operasi_id"
                                        name="jenis_operasi_id">
                                        <option value=""></option>
                                        @foreach ($jenis_operasi as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kategori_operasi_id">Kategori Operasi <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="kategori_operasi_id"
                                        name="kategori_operasi_id">
                                        <option value=""></option>
                                        @foreach ($kategori_operasi as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode_operasi">Kode Operasi <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="kode_operasi" name="kode_operasi">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_operasi">Nama Operasi <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_operasi" name="nama_operasi">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_billing">Nama Operasi (di billingan) <span
                                            class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_billing" name="nama_billing">
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
