<div class="modal fade" id="modal-tambah-nilai-parameter-laboratorium" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <input type="hidden" name="user_input" value="{{ auth()->user()->id }}">
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Nilai Parameter Laboratorium</h5>
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
                                    <label for="tanggal">Tanggal <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="tanggal"
                                        value="{{ now()->format('Y-m-d') }}" name="tanggal" disabled>
                                    <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="user_input">User <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="user_input"
                                        value="{{ auth()->user()->name }}" name="user_input" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="parameter_laboratorium_id">Parameter <span
                                            class="text-danger fw-bold">*</span></label>
                                    <select class="select2 form-control w-100" id="parameter_laboratorium_id"
                                        name="parameter_laboratorium_id">
                                        <option value=""></option>
                                        @foreach ($parameter as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->parameter }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Jenis Kelamin</label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" checked="" class="custom-control-input"
                                            id="laki_laki_tambah" name="jenis_kelamin" value="Laki-laki">
                                        <label class="custom-control-label" for="laki_laki_tambah">Laki - Laki</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="perempuan_tambah"
                                            name="jenis_kelamin" value="Perempuan">
                                        <label class="custom-control-label mr-1"
                                            for="perempuan_tambah">Perempuan</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="semuanya_tambah"
                                            name="jenis_kelamin" value="Semuanya">
                                        <label class="custom-control-label" for="semuanya_tambah">Semuanya</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Dari Umur</label>
                                    <div class="form-umur-wrapper d-flex align-items-center">
                                        <input type="text" name="tahun_1" id="tahun_1" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="tahun_1" class="form-label d-inline mr-2">Tahun</label>
                                        <input type="text" name="bulan_1" id="bulan_1" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="bulan_1" class="form-label d-inline mr-2">Bulan</label>
                                        <input type="text" name="hari_1" id="hari_1" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="hari_1" class="form-label d-inline mr-2">Hari</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Sampai Umur</label>
                                    <div class="form-umur-wrapper d-flex align-items-center">
                                        <input type="text" name="tahun_2" id="tahun_2" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="tahun_2" class="form-label d-inline mr-2">Tahun</label>
                                        <input type="text" name="bulan_2" id="bulan_2" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="bulan_2" class="form-label d-inline mr-2">Bulan</label>
                                        <input type="text" name="hari_2" id="hari_2" value="0"
                                            class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0 mr-2">
                                        <label for="hari_2" class="form-label d-inline mr-2">Hari</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="min">Min <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" id="min" name="min"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="max">Max <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" id="max" name="max"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="nilai_normal" class="d-block">Nilai Normal</label>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input"
                                                    id="negatif_tambah" name="nilai_normal" value="Negatif">
                                                <label class="custom-control-label"
                                                    for="negatif_tambah">Negatif</label>
                                            </div>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input"
                                                    id="positif_tambah" name="nilai_normal" value="Positif">
                                                <label class="custom-control-label"
                                                    for="positif_tambah">Positif</label>
                                            </div>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input"
                                                    id="reaktif_tambah" name="nilai_normal" value="Reaktif">
                                                <label class="custom-control-label"
                                                    for="reaktif_tambah">Reaktif</label>
                                            </div>
                                            <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                                <input type="radio" class="custom-control-input"
                                                    id="non_reaktif_tambah" name="nilai_normal" value="Non Reaktif">
                                                <label class="custom-control-label" for="non_reaktif_tambah">Non
                                                    Reaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <div class="form-group">
                                            <label for="hasil">Hasil</label>
                                            <input type="text" class="form-control" id="hasil"
                                                name="hasil">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="min_kritis">Kritis Jika Kurang Dari</label>
                                    <input type="text" class="form-control" id="min_kritis" name="min_kritis"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="max_kritis">Kritis Jika Lebih Dari</label>
                                    <input type="text" class="form-control" id="max_kritis" name="max_kritis"
                                        placeholder="0">
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
