<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian Penjualan</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="order_date" class="form-label text-end">Periode</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="form-group row">
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" id="datepicker-1"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        placeholder="Select date" name="order_date"
                                                        value="01/01/2018 - 01/15/2018">
                                                </div>
                                            </div>
                                            @error('order_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="gudang_id">
                                                Gudang
                                            </label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="gudang_id" name="gudang_id">
                                                <option value="">Semua Gudang</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">
                                                        {{ $gudang->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="doctor_id" class="form-label text-end">Dokter</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                                <option value="">Semua Dokter</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">
                                                        {{ $doctor->employee->fullname }}
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="registration_type">
                                                Tipe Registrasi
                                            </label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="registration_type"
                                                name="registration_type">
                                                <option value="">Semua Tipe Registrasi</option>
                                                <option value="rajal">Rawat Jalan</option>
                                                <option value="ranap">Rawat Inap</option>
                                                <option value="otc">OTC/APS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="departement_id" class="form-label text-end">Poliklinik</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="departement_id"
                                                name="departement_id">
                                                <option value="">Semua Poliklinik</option>
                                                @foreach ($departements as $departement)
                                                    <option value="{{ $departement->id }}">
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="kelas_rawat_id">
                                                Kelas Perawatan
                                            </label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="kelas_rawat_id"
                                                name="kelas_rawat_id">
                                                <option value="">Semua Kelas Rawat</option>
                                                @foreach ($kelas_rawats as $kelas_rawat)
                                                    <option value="{{ $kelas_rawat->id }}">{{ $kelas_rawat->kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="kategori_id" class="form-label text-end">Kategori
                                                Barang</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="kategori_id"
                                                name="kategori_id">
                                                <option value="">Semua Kategori Barang</option>
                                                @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">
                                                        {{ $kategori->nama }}
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="room_id">
                                                Ruang Perawatan
                                            </label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="room_id" name="room_id">
                                                <option value="">Semua Ruang Rawat</option>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}">{{ $room->ruangan }}
                                                        ({{ $room->no_ruang }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="golongan_id" class="form-label text-end">Golongan
                                                Barang</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="golongan_id"
                                                name="golongan_id">
                                                <option value="">Semua Golongan Barang</option>
                                                @foreach ($golongans as $golongan)
                                                    <option value="{{ $golongan->id }}">
                                                        {{ $golongan->nama }}
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="kelompok_id" class="form-label text-end">Kelompok
                                                Barang</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="kelompok_id"
                                                name="kelompok_id">
                                                <option value="">Semua Kelompok Barang</option>
                                                @foreach ($kelompoks as $kelompok)
                                                    <option value="{{ $kelompok->id }}">
                                                        {{ $kelompok->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="pabrik_id" class="form-label text-end">Pabrik</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="pabrik_id"
                                                name="pabrik_id">
                                                <option value="">Semua Pabrik</option>
                                                @foreach ($pabriks as $pabrik)
                                                    <option value="{{ $pabrik->id }}">
                                                        {{ $pabrik->nama }}
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="nama_pasien" class="form-label text-end">Nama Pasien</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <input type="text" name="nama_pasien" id="nama_pasien"
                                                class="form-control"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="nama_obat" class="form-label text-end">Nama Obat</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <input type="text" name="nama_obat" id="nama_obat"
                                                class="form-control"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="registration_number" class="form-label text-end">No
                                                Registrasi</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <input type="text" name="registration_number" id="registration_number"
                                                class="form-control"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="medical_record_number" class="form-label text-end">No
                                                RM</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <input type="text" name="nama_obat" id="medical_record_number"
                                                class="form-control"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="registration_number" class="form-label text-end">BHP (Not
                                                Billed)</label>
                                        </div>
                                        <div class="col-xl-8">
                                            {{-- radio button with name 'bhp', options: Ya, Tidak --}}
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="bhp"
                                                    id="bhp1" value="Ya">
                                                <label class="form-check-label" for="bhp1">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="bhp"
                                                    id="bhp2" value="Tidak">
                                                <label class="form-check-label" for="bhp2">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="penjamin_id" class="form-label text-end">Penjamin</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="penjamin_id"
                                                name="penjamin_id">
                                                <option value="">Semua Penjamin</option>
                                                @foreach ($penjamins as $penjamin)
                                                    <option value="{{ $penjamin->id }}">
                                                        {{ $penjamin->nama_perusahaan }}
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="tipe_barang" class="form-label text-end">Tipe Barang</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="tipe_barang"
                                                name="tipe_barang">
                                                <option value="">Semua Tipe Barang</option>
                                                <option value="FN">Formularium Nasional</option>
                                                <option value="NFN">Formularium Non Nasional</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="formularium_barang" class="form-label text-end">Formularium Barang</label>
                                        </div>
                                        <div class="col-xl-8">
                                            <select class="select2 form-control w-100" id="formularium_barang"
                                                name="formularium_barang">
                                                <option value="">Semua Formularium Barang</option>
                                                <option value="RS">Formularium RS</option>
                                                <option value="NRS">Non Formularium RS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                {{-- /// --}}
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-xl-3 text-center">
                                <button type="button" onclick="showReport(event, 'order')"
                                    class="btn btn-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Lihat Per Resep
                                </button>
                            </div>
                            <div class="col-xl-3 text-center">
                                <button type="button" disabled
                                    class="btn btn-primary waves-effect waves-themed">
                                    <span class="fal fa-file-excel mr-1"></span>
                                    XLS Per Resep
                                </button>
                            </div>
                            <div class="col-xl-3 text-center">
                                <button type="button" onclick="showReport(event, 'doctor')"
                                    class="btn btn-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Lihat Per Dokter
                                </button>
                            </div>
                            <div class="col-xl-3 text-center">
                                <button type="button" disabled
                                    class="btn btn-primary waves-effect waves-themed">
                                    <span class="fal fa-file-excel mr-1"></span>
                                    XLS Per Dokter
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showReport(event, type) {
        event.preventDefault();
        const target = event.target;
        const form = target.form;

        // create JSON from the form inputs
        const formData = new FormData(form);
        const data = {};
        for (const [key, value] of formData.entries()) {
            data[key] = value;
        }

        const Request = btoa(JSON.stringify(data));

        const src =
            `/simrs/farmasi/laporan/penjualan/view/${type}/${Request}`;

        window.open(
            src,
            "popupWindow_farmasiReportPenjualan",
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
