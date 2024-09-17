<div class="modal fade p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="update-form">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Edit OKR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    {{-- Form Fields --}}
                    <input type="hidden" class="form-control" id="organization_id" name="organization_id" required>
                    <input type="hidden" class="form-control" id="user_id" name="user_id" required>
                    <div class="form-group mb-3">
                        <label for="bulan">Bulan</label>
                        <!-- Mengubah input menjadi select2 -->
                        <select class="select2 form-control @error('bulan') is-invalid @enderror" name="bulan"
                            id="bulan">
                            <option value="1"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 1 ? 'selected' : '') }}>
                                Januari</option>
                            <option value="2"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 2 ? 'selected' : '') }}>
                                Februari</option>
                            <option value="3"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 3 ? 'selected' : '') }}>
                                Maret</option>
                            <option value="4"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 4 ? 'selected' : '') }}>
                                April</option>
                            <option value="5"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 5 ? 'selected' : '') }}>
                                Mei</option>
                            <option value="6"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 6 ? 'selected' : '') }}>
                                Juni</option>
                            <option value="7"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 7 ? 'selected' : '') }}>
                                Juli</option>
                            <option value="8"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 8 ? 'selected' : '') }}>
                                Agustus</option>
                            <option value="9"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 9 ? 'selected' : '') }}>
                                September</option>
                            <option value="10"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 10 ? 'selected' : '') }}>
                                Oktober</option>
                            <option value="11"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 11 ? 'selected' : '') }}>
                                November</option>
                            <option value="12"
                                {{ old('bulan', isset($selectedBulan) && $selectedBulan == 12 ? 'selected' : '') }}>
                                Desember</option>
                        </select>

                        @error('bulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Masukkan Title" required>
                        <div class="invalid-feedback">Title wajib diisi.</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pic">PIC (Person In Charge)</label>
                        <!-- Mengubah input menjadi select2 -->
                        <select class="select2 form-control @error('pic') is-invalid @enderror" name="pic"
                            id="pic">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ old('pic', $employee->fullname) }}</option>
                            @endforeach
                        </select>
                        @error('pic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="frame-wrap">
                            <label for="" class="d-block">Satuan</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="update-baku" name="satuan"
                                    value="baku">
                                <label class="custom-control-label" for="update-baku">Baku</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="update-persen" name="satuan"
                                    value="persen">
                                <label class="custom-control-label" for="update-persen">Persen (%)</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actual">Actual</label>
                        <input type="number" step="any" class="form-control" id="actual" name="actual"
                            placeholder="Masukkan Actual" required>
                        <div class="invalid-feedback">Actual wajib diisi dengan angka atau desimal.</div>
                    </div>
                    @can('create target okr')
                        <div class="form-group">
                            <label for="target">Target</label>
                            <input type="number" step="any" class="form-control" id="target" name="target"
                                placeholder="Masukkan Target" required>
                            <div class="invalid-feedback">Target wajib diisi dengan angka atau desimal.</div>
                        </div>
                        <div class="form-group">
                            <label for="min_target">Min Target</label>
                            <input type="number" step="any" class="form-control" id="min_target" name="min_target"
                                placeholder="Masukkan Min Target" required>
                            <div class="invalid-feedback">Min Target wajib diisi dengan angka atau desimal.</div>
                        </div>
                    @endcan
                </div>
                <div class="modal-footer mt-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-tambah">
                            <span class="fal fa-save mr-1"></span>
                            Update
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
