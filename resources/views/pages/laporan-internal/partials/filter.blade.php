    <div class="row mb-3">
        <div class="col-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>Filter Laporan</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <form id="filter-form" method="POST" action="{{ route('laporan-internal.filter') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="form-label mb-1 font-weight-normal" for="filter-tanggal">Tanggal<i
                                                class="text-danger">*</i></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text fs-xl"><i
                                                        class="fal fa-calendar"></i></span>
                                            </div>
                                            <input type="text" id="filter-tanggal"
                                                class="form-control datepicker @error('tanggal') is-invalid @enderror"
                                                placeholder="Select a date" name="tanggal">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <label for="filter-jenis">Jenis</label>
                                        <!-- Mengubah input menjadi select3 -->
                                        <select class="select3 form-control @error('jenis') is-invalid @enderror"
                                            name="jenis" id="filter-jenis">
                                            <option value="">Pilih Jenis</option>
                                            <!-- Placeholder option -->
                                            <option value="kendala"
                                                {{ (old('jenis') ?? request('jenis')) == 'kendala' ? 'selected' : '' }}>
                                                Kendala</option>
                                            <option value="kegiatan"
                                                {{ (old('jenis') ?? request('jenis')) == 'kegiatan' ? 'selected' : '' }}>
                                                Kegiatan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <label for="filter-user">User</label>
                                        <select class="select3 form-control @error('user') is-invalid @enderror"
                                            name="user[]" id="filter-user" multiple>
                                            @foreach ($umum as $employee)
                                                <option value="{{ $employee->user->id }}">
                                                    {{ old('user', $employee->fullname) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <label for="filter-status">Status</label>
                                        <!-- Mengubah input menjadi select3 -->
                                        <select class="select3 form-control @error('status') is-invalid @enderror"
                                            name="status" id="filter-status">
                                            <option value="">Pilih Status</option>
                                            <!-- Placeholder option -->
                                            <option value="selesai"
                                                {{ (old('status') ?? request('status')) == 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="diproses"
                                                {{ (old('status') ?? request('status')) == 'diproses' ? 'selected' : '' }}>
                                                Diproses</option>
                                            <option value="ditunda"
                                                {{ (old('status') ?? request('status')) == 'ditunda' ? 'selected' : '' }}>
                                                Ditunda</option>
                                            <option value="ditolak"
                                                {{ (old('status') ?? request('status')) == 'ditolak' ? 'selected' : '' }}>
                                                Ditolak</option>
                                        </select>

                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="">&nbsp</label>
                                    <button type="submit" class="btn btn-primary btn-block w-100">
                                        <div class="ikon-tambah">
                                            <span class="fal fa-search mr-1"></span>Cari
                                        </div>
                                        <div class="span spinner-text d-none">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
