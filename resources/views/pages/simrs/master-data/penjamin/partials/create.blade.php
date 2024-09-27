<div class="modal fade" id="modal-tambah-penjamin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Penjamin</h5>
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
                                    <label for="kode">
                                        Mulai Kerjasama <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="mulai_kerjasama" id="mulai_kerjasama"
                                            class="form-control date-picker-modal" placeholder="Select a date"
                                            aria-label="date" aria-describedby="mulai_kerjasama">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode">
                                        Akhir Kerjasama <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="akhir_kerjasama" id="akhir_kerjasama"
                                            class="form-control date-picker-modal" placeholder="Select a date"
                                            aria-label="date" aria-describedby="akhir_kerjasama">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode_perusahaan">Kode Perusahaan</label>
                                    <input type="text" value="{{ old('kode_perusahaan') }}" class="form-control"
                                        name="kode_perusahaan" placeholder="Masukan kode perusahaan...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="tipe_perusahaan">Tipe Perusahaan</label>
                                    <select name="tipe_perusahaan" id="tipe_perusahaan" class="form-control select2">
                                        <option value="Asuransi Non Penjamin">Asuransi Non Penjamin</option>
                                        <option value="Asuransi Penjamin">Asuransi Penjamin</option>
                                        <option value="Lain-lain">Lain-lain</option>
                                        <option value="Perorangan / Pejabat Teras">Perorangan / Pejabat Teras</option>
                                        <option value="Perusahaan BUMN">Perusahaan BUMN</option>
                                        <option value="Perusahaan Kartu Kredit">Perusahaan Kartu Kredit</option>
                                        <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                                        <option value="TPA">TPA</option>
                                        <option value="Yayasan">Yayasan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_perusahaan">Nama Perusahaan
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" value="{{ old('nama_perusahaan') }}" class="form-control"
                                        name="nama_perusahaan" placeholder="Masukan nama perusahaan...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="alamat_surat">Alamat Surat</label>
                                    <input type="text" id="alamat_surat" class="form-control" name="alamat_surat"
                                        placeholder="Masukan alamat surat...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="alamat_surat">Tarif Grup Perusahaan
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <select name="group_penjamin_id" id="group_penjamin_id"
                                        class="form-control select2">
                                        <option value="1">Standar</option>
                                        <option value="2">BPJS</option>
                                        <option value="3">UMUM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="alamat_email">Alamat Email
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="alamat_email" class="form-control" name="alamat_email"
                                        placeholder="Masukan alamat email...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="direktur">Direktur</label>
                                    <input type="text" id="direktur" class="form-control" name="direktur"
                                        placeholder="Masukan direktur...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_kontak">Nama Kontak
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="nama_kontak" class="form-control" name="nama_kontak"
                                        placeholder="Masukan nama kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="diskon">Diskon
                                    </label>
                                    <input type="text" id="diskon" class="form-control" name="diskon"
                                        placeholder="Masukan nama kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="jabatan" class="form-control" name="jabatan"
                                        placeholder="Masukan jabatan...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Termasuk Penjamin
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="termasuk_penjamin" class="custom-control-input"
                                            id="termasuk_penjamin_ya_tambah" value=1>
                                        <label class="custom-control-label"
                                            for="termasuk_penjamin_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input"
                                            name="termasuk_penjamin" id="termasuk_penjamin_tidak_tambah"
                                            checked="">
                                        <label class="custom-control-label"
                                            for="termasuk_penjamin_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="fax_kontak">Fax Kontak
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="fax_kontak" class="form-control" name="fax_kontak"
                                        placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="alamat">Alamat
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="alamat" class="form-control" name="alamat"
                                        placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="alamat_tagihan">Alamat Tagihan
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="alamat_tagihan" class="form-control"
                                        name="alamat_tagihan" placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="telepon_kontak">Telepon Kontak
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="telepon_kontak" class="form-control"
                                        name="telepon_kontak" placeholder="Masukan telepon kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="email_kontak">Email Kontak
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="email_kontak" class="form-control" name="email_kontak"
                                        placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kota">Kota
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="kota" class="form-control" name="kota"
                                        placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="status" class="d-block">Status
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="status" class="custom-control-input"
                                            id="status_ya_tambah" value=1>
                                        <label class="custom-control-label" for="status_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="status"
                                            id="status_tidak_tambah" checked="">
                                        <label class="custom-control-label" for="status_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode_pos">Kode Pos
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="kode_pos" class="form-control" name="kode_pos"
                                        placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="jenis_kerjasama">Jenis Kerjasama
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="jenis_kerjasama" class="form-control"
                                        name="jenis_kerjasama" placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="jenis_kontrak">Jenis Kontrak
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="jenis_kontrak" class="form-control"
                                        name="jenis_kontrak" placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" id="keterangan" class="form-control" name="keterangan"
                                        placeholder="Masukan fax kontak...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Pasien OTC
                                        <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="pasien_otc" class="custom-control-input"
                                            id="pasien_otc_ya_tambah" value=1>
                                        <label class="custom-control-label" for="pasien_otc_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="pasien_otc"
                                            id="pasien_otc_tidak_tambah" checked="">
                                        <label class="custom-control-label"
                                            for="pasien_otc_tidak_tambah">Tidak</label>
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
