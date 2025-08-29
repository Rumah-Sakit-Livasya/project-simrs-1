<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian Klaim Dispensing</i></span>
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
                                            <label for="tanggal_order">Tgl. Order</label>
                                        </div>
                                        <div class="col-xl">
                                            <div class="form-group row">
                                                <div class="col-xl ">
                                                    <input type="text" class="form-control" id="datepicker-1"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        placeholder="Select date" name="tanggal_order"
                                                        value="01/01/2018 - 01/15/2018">
                                                </div>
                                            </div>
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
                                            <label class="form-label text-end" for="doctor_id">
                                                Dokter
                                            </label>
                                        </div>
                                        <div class="col-xl">
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

                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="tipe">
                                                Tipe Registrasi
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="tipe" name="tipe">
                                                <option value="">Semua Tipe</option>
                                                <option value="rajal">Rawat Jalan</option>
                                                <option value="ranap">Rawat Inap</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="departement_id">
                                                Poliklinik
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="departement_id"
                                                name="departement_id">
                                                <option value="">Semua Poliklinik</option>
                                                @foreach ($departements as $departement)
                                                    <option value="{{ $departement->id }}">{{ $departement->name }}
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
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="kelas_rawat_id">
                                                Kelas Perawatan
                                            </label>
                                        </div>
                                        <div class="col-xl">
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
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="nama_obat">
                                                Nama Obat
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" name="nama_obat" id="nama_obat" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-3">
                                <button type="submit" onclick="showReport(event)"
                                    class="btn btn-outline-primary waves-effect waves-themed">
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

<script>
    function showReport(event) {
        event.preventDefault();
        const target = event.target;
        const form = target.form;

        // tanggal_order is split into startDate and endDate
        const tanggal_order = form.querySelector('input[name="tanggal_order"]').value;
        const [startDate, endDate] = tanggal_order.split(' - ');

        const tipe = form.querySelector('select[name="tipe"]').value || '-';
        const doctor_id = form.querySelector('select[name="doctor_id"]').value || '-';
        const departement_id = form.querySelector('select[name="departement_id"]').value || '-';
        const kelas_rawat_id = form.querySelector('select[name="kelas_rawat_id"]').value || '-';
        const nama_obat = form.querySelector('input[name="nama_obat"]').value || '-';

        const src =
            `/simrs/farmasi/laporan/klaim-dispensing/view/${startDate}/${endDate}/${tipe}/${doctor_id}/${departement_id}/${kelas_rawat_id}/${nama_obat}`;

        window.open(
            src,
            "popupWindow_" + new Date().getTime(),
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
