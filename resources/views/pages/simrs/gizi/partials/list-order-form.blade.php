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

                    <form action="{{ route('gizi.daftar-order.list-order-gizi') }}" method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="medical_record_number">
                                                No. RM
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('medical_record_number') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="medical_record_number"
                                                name="medical_record_number" onkeyup="formatAngka(this)">
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
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="registration_number">
                                                No. Registrasi
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('registration_number') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="registration_number" name="registration_number"
                                                onkeyup="formatAngka(this)">
                                            @error('registration_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="patient_name">
                                                Nama Pasien
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('patient_name') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="patient_name" name="patient_name">
                                            @error('patient_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label for="nama_pemesan">Nama Pemesan</label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama_pemesan') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="nama_pemesan" name="nama_pemesan">
                                            @error('nama_pemesan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal_order">
                                                Tanggal Order
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="date"
                                                value="{{ request('tanggal_order', now()->toDateString()) }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="tanggal_order" name="tanggal_order">
                                            @error('tanggal_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="waktu_makan">
                                                Waktu Makan
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select value="{{ request('waktu_makan') }}" name="waktu_makan"
                                                id="waktu_makan" class="select2 form-control w-100">
                                                <option value=""></option>
                                                <option value="pagi">Pagi</option>
                                                <option value="siang">Siang</option>
                                                <option value="sore">Sore</option>
                                            </select>
                                            @error('waktu_makan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="kelas_id">
                                                Kelas
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <div class="form-group">
                                                <select class="select2 form-control w-100" id="kelas_id"
                                                    name="kelas_id">
                                                    <option selected></option>
                                                    @foreach ($kelasRawats as $kelas)
                                                        <option value="{{ $kelas->id }}"
                                                            {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                            {{ $kelas->kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="status_order">
                                                Status Pesanan
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <div class="form-group">
                                                <select class="select2 form-control w-100" id="status_order"
                                                    name="status_order">
                                                    <option selected></option>
                                                    <option value="0"
                                                        {{ request('status_order') == '0' ? 'selected' : '' }}>
                                                        Process
                                                    </option>
                                                    <option value="1"
                                                        {{ request('status_order') == '1' ? 'selected' : '' }}>
                                                        Delivered
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-2">
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
