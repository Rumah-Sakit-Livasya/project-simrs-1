<div class="modal fade" id="ubah-salary" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" id="update-form" method="post"
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
                        <label for="basic_salary">Basic Salary</label>
                        <input type="number" value="{{ old('basic_salary') }}"
                            class="form-control @error('basic_salary') is-invalid @enderror" id="basic_salary"
                            name="basic_salary" placeholder="Basic Salary">
                        @error('basic_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_jabatan">Tunjangan Jabatan</label>
                        <input type="number" value="{{ old('tunjangan_jabatan') }}"
                            class="form-control @error('tunjangan_jabatan') is-invalid @enderror" id="tunjangan_jabatan"
                            name="tunjangan_jabatan" placeholder="Tunjangan Jabatan">
                        @error('tunjangan_jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_profesi">Tunjangan Profesi</label>
                        <input type="number" value="{{ old('tunjangan_profesi') }}"
                            class="form-control @error('tunjangan_profesi') is-invalid @enderror" id="tunjangan_profesi"
                            name="tunjangan_profesi" placeholder="Tunjangan Profesi">
                        @error('tunjangan_profesi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_makan_dan_transport">Tunjangan Makan & Transport</label>
                        <input type="number" value="{{ old('tunjangan_makan_dan_transport') }}"
                            class="form-control @error('tunjangan_makan_dan_transport') is-invalid @enderror"
                            id="tunjangan_makan_dan_transport" name="tunjangan_makan_dan_transport"
                            placeholder="Tunjangan Makan & Transport">
                        @error('tunjangan_makan_dan_transport')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_masa_kerja">Tunjangan Masa Kerja</label>
                        <input type="number" value="{{ old('tunjangan_masa_kerja') }}"
                            class="form-control @error('tunjangan_masa_kerja') is-invalid @enderror"
                            id="tunjangan_masa_kerja" name="tunjangan_masa_kerja" placeholder="Tunjangan Masa Kerja">
                        @error('tunjangan_masa_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="guarantee_fee">Guarantee Fee</label>
                        <input type="number" value="{{ old('guarantee_fee') }}"
                            class="form-control @error('guarantee_fee') is-invalid @enderror" id="guarantee_fee"
                            name="guarantee_fee" placeholder="Guarantee Fee">
                        @error('guarantee_fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="uang_duduk">Uang Duduk</label>
                        <input type="number" value="{{ old('uang_duduk') }}"
                            class="form-control @error('uang_duduk') is-invalid @enderror" id="uang_duduk"
                            name="uang_duduk" placeholder="Uang Duduk">
                        @error('uang_duduk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tax_allowance">Tax Allowance</label>
                        <input type="number" value="{{ old('tax_allowance') }}"
                            class="form-control @error('tax_allowance') is-invalid @enderror" id="tax_allowance"
                            name="tax_allowance" placeholder="Tax Allowance">
                        @error('tax_allowance')
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
