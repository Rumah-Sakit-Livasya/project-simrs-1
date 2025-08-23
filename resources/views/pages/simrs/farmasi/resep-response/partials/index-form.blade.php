<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form action="{{ route('farmasi.response-time') }}" method="get" id="form-response-time">
                        @csrf
                        <input type="hidden" name="user_id" value={{ auth()->user()->id }}>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal">
                                                Tanggal
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('tanggal') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="datepicker-1" name="tanggal">
                                            @error('tanggal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="nama_pasien">
                                                Nama Pasien
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama_pasien') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="nama_pasien" name="nama_pasien">
                                            @error('nama_pasien')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="medical_record_number">
                                                No RM
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('medical_record_number') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" onkeyup="formatAngka(this)"
                                                id="medical_record_number" name="medical_record_number">
                                            @error('medical_record_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="registration_number">
                                                No Reg
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('registration_number') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="registration_number"
                                                name="registration_number">
                                            @error('registration_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="departement_id">
                                                Poliklinik
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="departement_id" class="form-control select2">
                                                @foreach ($departements as $departement)
                                                    <option value="">Semua</option>
                                                    <option value="{{ $departement->id }}"
                                                        {{ request('departement_id') == $departement->id ? 'selected' : '' }}>
                                                        {{ $departement->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="billed">
                                                Status Bill
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="billed" class="form-control">
                                                <option value="">Semua</option>
                                                <option value="1">Lunas</option>
                                                <option value="0">Belum Bill</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-3">
                                <button type="button" id="print-btn"
                                    class="btn btn-primary waves-effect waves-themed">
                                    <span class="fas fa-print mr-1"></span>
                                    Print
                                </button>
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
