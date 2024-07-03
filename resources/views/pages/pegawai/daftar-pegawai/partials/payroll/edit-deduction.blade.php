<div class="modal fade" id="ubah-deduction" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" id="update-deduction-form" method="post"
                enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="potongan_keterlambatan">Potongan Keterlambatan</label>
                        <input type="number" value="{{ old('potongan_keterlambatan') }}"
                            class="form-control @error('potongan_keterlambatan') is-invalid @enderror"
                            id="potongan_keterlambatan" name="potongan_keterlambatan"
                            placeholder="Potongan Keterlambatan">
                        @error('potongan_keterlambatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_izin">Potongan Izin</label>
                        <input type="number" value="{{ old('potongan_izin') }}"
                            class="form-control @error('potongan_izin') is-invalid @enderror" id="potongan_izin"
                            name="potongan_izin" placeholder="Potongan Izin">
                        @error('potongan_izin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_sakit">Potongan Sakit</label>
                        <input type="number" value="{{ old('potongan_sakit') }}"
                            class="form-control @error('potongan_sakit') is-invalid @enderror" id="potongan_sakit"
                            name="potongan_sakit" placeholder="Potongan Sakit">
                        @error('potongan_sakit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="simpanan_pokok">Simpanan Pokok</label>
                        <input type="number" value="{{ old('simpanan_pokok') }}"
                            class="form-control @error('simpanan_pokok') is-invalid @enderror" id="simpanan_pokok"
                            name="simpanan_pokok" placeholder="Simpanan Pokok">
                        @error('simpanan_pokok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_koperasi">Potongan Koperasi</label>
                        <input type="number" value="{{ old('potongan_koperasi') }}"
                            class="form-control @error('potongan_koperasi') is-invalid @enderror" id="potongan_koperasi"
                            name="potongan_koperasi" placeholder="Potongan Koperasi">
                        @error('potongan_koperasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_absensi">Potongan Absensi</label>
                        <input type="number" value="{{ old('potongan_absensi') }}"
                            class="form-control @error('potongan_absensi') is-invalid @enderror" id="potongan_absensi"
                            name="potongan_absensi" placeholder="Potongan Absensi">
                        @error('potongan_absensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_bpjs_kesehatan">Potongan BPJS Kesehatan</label>
                        <input type="number" value="{{ old('potongan_bpjs_kesehatan') }}"
                            class="form-control @error('potongan_bpjs_kesehatan') is-invalid @enderror"
                            id="potongan_bpjs_kesehatan" name="potongan_bpjs_kesehatan"
                            placeholder="Potongan BPJS Kesehatan">
                        @error('potongan_bpjs_kesehatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_bpjs_ketenagakerjaan">Potongan BPJS Ketenagakerjaan</label>
                        <input type="number" value="{{ old('potongan_bpjs_ketenagakerjaan') }}"
                            class="form-control @error('potongan_bpjs_ketenagakerjaan') is-invalid @enderror"
                            id="potongan_bpjs_ketenagakerjaan" name="potongan_bpjs_ketenagakerjaan"
                            placeholder="Potongan BPJS Ketenagakerjaan">
                        @error('potongan_bpjs_ketenagakerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="potongan_pajak">Potongan Pajak</label>
                        <input type="number" value="{{ old('potongan_pajak') }}"
                            class="form-control @error('potongan_pajak') is-invalid @enderror" id="potongan_pajak"
                            name="potongan_pajak" placeholder="Potongan Pajak">
                        @error('potongan_pajak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil-alt mr-1"></span>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
