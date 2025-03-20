<div class="modal fade" id="modal-tambah-parameter-laboratorium" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Parameter Laboratorium</h5>
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
                                    <label for="grup_parameter_laboratorium_id">Grup <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="grup_parameter_laboratorium_id"
                                        name="grup_parameter_laboratorium_id">
                                        <option value=""></option>
                                        @foreach ($grup_parameter as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->nama_grup }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kategori_laboratorium_id">Kategori Lab <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="kategori_laboratorium_id"
                                        name="kategori_laboratorium_id">
                                        <option value=""></option>
                                        @foreach ($kategori as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="tipe_laboratorium_id">Tipe Lab <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="tipe_laboratorium_id"
                                        name="tipe_laboratorium_id">
                                        <option value=""></option>
                                        @foreach ($tipe as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->nama_tipe }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="parameter">Parameter <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="parameter" name="parameter">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="satuan">Satuan
                                    </label>
                                    <input type="text" class="form-control" id="satuan" name="satuan">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Status</label>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" name="is_hasil" class="custom-control-input"
                                            id="is_hasil">
                                        <label class="custom-control-label" for="is_hasil">Isi Hasil</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" name="is_order"
                                            id="is_order" checked="">
                                        <label class="custom-control-label" for="is_order">Bisa Di Order</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="satuan">Tipe Isi Hasil
                                    </label>
                                    <select class="select2 form-control w-100" id="tipe_hasil" name="tipe_hasil">
                                        <option value="Angka">Angka</option>
                                        <option value="Text">Text</option>
                                        <option value="Negatif/Positif">Negatif/Positif</option>
                                        <option value="Reaktif/NonReaktif">Reaktif/NonReaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="metode">Metode
                                    </label>
                                    <input type="text" class="form-control" id="metode" name="metode">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="no_urut">No Urut
                                    </label>
                                    <input type="text" class="form-control" id="no_urut" name="no_urut">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="sub_parameter">Sub Parameter
                                    </label>
                                    <select class="form-control select2 w-100" id="sub_parameter"
                                        name="sub_parameter[]" multiple="multiple">
                                        @foreach ($parameter as $p)
                                            @if (!$p->is_order && $p->is_hasil)
                                                <option value="{{ $p->id }}">{{ $p->parameter }}</option>
                                            @endif
                                        @endforeach
                                    </select>
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
