<div class="modal fade font-weight-bold p-0" id="ubah-data-payroll" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="update-form-payroll">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="text-muted">-Potongan</h4>
                            <div class="form-group">
                                <label for="potongan_absensi">Absensi</label>
                                <input readonly type="text" value="{{ old('potongan_absensi') }}"
                                    class="form-control @error('potongan_absensi') is-invalid @enderror"
                                    id="potongan_absensi" name="potongan_absensi" placeholder="Potongan Absensi">
                                @error('potongan_absensi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_keterlambatan">Keterlambatan</label>
                                <input readonly type="text" value="{{ old('potongan_keterlambatan') }}"
                                    class="form-control @error('potongan_keterlambatan') is-invalid @enderror"
                                    id="potongan_keterlambatan" name="potongan_keterlambatan"
                                    placeholder="Potongan Keterlambatan">
                                @error('potongan_keterlambatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_izin">Izin</label>
                                <input readonly type="text" value="{{ old('potongan_izin') }}"
                                    class="form-control @error('potongan_izin') is-invalid @enderror" id="potongan_izin"
                                    name="potongan_izin" placeholder="Potongan Izin">
                                @error('potongan_izin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_sakit">Sakit</label>
                                <input readonly type="text" value="{{ old('potongan_sakit') }}"
                                    class="form-control @error('potongan_sakit') is-invalid @enderror"
                                    id="potongan_sakit" name="potongan_sakit" placeholder="Potongan Sakit">
                                @error('potongan_sakit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_koperasi">Koperasi</label>
                                <input type="text" value="{{ old('potongan_koperasi') }}"
                                    class="form-control @error('potongan_koperasi') is-invalid @enderror"
                                    id="potongan_koperasi" name="potongan_koperasi" placeholder="Potongan Koperasi">
                                @error('potongan_koperasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_bpjs_kesehatan">BPJS Kesehatan</label>
                                <input type="text" value="{{ old('potongan_bpjs_kesehatan') }}"
                                    class="form-control @error('potongan_bpjs_kesehatan') is-invalid @enderror"
                                    id="potongan_bpjs_kesehatan" name="potongan_bpjs_kesehatan"
                                    placeholder="Potongan BPJS Kesehatan">
                                @error('potongan_bpjs_kesehatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_bpjs_ketenagakerjaan">BPJS Ketenagakerjaan</label>
                                <input type="text" value="{{ old('potongan_bpjs_ketenagakerjaan') }}"
                                    class="form-control @error('potongan_bpjs_ketenagakerjaan') is-invalid @enderror"
                                    id="potongan_bpjs_ketenagakerjaan" name="potongan_bpjs_ketenagakerjaan"
                                    placeholder="Potongan BPJS Ketenagakerjaan">
                                @error('potongan_bpjs_ketenagakerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="potongan_pajak">Pajak</label>
                                <input type="text" value="{{ old('potongan_pajak') }}"
                                    class="form-control @error('potongan_pajak') is-invalid @enderror"
                                    id="potongan_pajak" name="potongan_pajak" placeholder="Potongan Pajak">
                                @error('potongan_pajak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="simpanan_pokok">Simpanan Pokok</label>
                                <input type="text" value="{{ old('simpanan_pokok') }}"
                                    class="form-control @error('simpanan_pokok') is-invalid @enderror"
                                    id="simpanan_pokok" name="simpanan_pokok" placeholder="Simpanan Pokok">
                                @error('simpanan_pokok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="text-muted">+Tunjangan</h4>
                            <div class="form-group">
                                <label for="tunjangan_masa_kerja">Tunjangan Masa Kerja</label>
                                <input type="text" value="{{ old('tunjangan_masa_kerja') }}"
                                    class="form-control @error('tunjangan_masa_kerja') is-invalid @enderror"
                                    id="tunjangan_masa_kerja" name="tunjangan_masa_kerja"
                                    placeholder="Tunjangan Masa Kerja">
                                @error('tunjangan_masa_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tunjangan_makan_dan_transport">Tunjangan Makan & Transport</label>
                                <input type="text" value="{{ old('tunjangan_makan_dan_transport') }}"
                                    class="form-control @error('tunjangan_makan_dan_transport') is-invalid @enderror"
                                    id="tunjangan_makan_dan_transport" name="tunjangan_makan_dan_transport"
                                    placeholder="Tunjangan Makan & Transport">
                                @error('tunjangan_makan_dan_transport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tunjangan_jabatan">Tunjangan Jabatan</label>
                                <input type="text" value="{{ old('tunjangan_jabatan') }}"
                                    class="form-control @error('tunjangan_jabatan') is-invalid @enderror"
                                    id="tunjangan_jabatan" name="tunjangan_jabatan" placeholder="Tunjangan Jabatan">
                                @error('tunjangan_jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="total_allowance">Total Tunjangan</label>
                                <input type="text" value="{{ old('total_allowance') }}"
                                    class="form-control @error('total_allowance') is-invalid @enderror"
                                    id="total_allowance" name="total_allowance" placeholder="Total Tunjangan">
                                @error('total_allowance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="total_deduction">Total Potongan</label>
                                <input type="text" value="{{ old('total_deduction') }}"
                                    class="form-control @error('total_deduction') is-invalid @enderror"
                                    id="total_deduction" name="total_deduction" placeholder="Total Potongan">
                                @error('total_deduction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
