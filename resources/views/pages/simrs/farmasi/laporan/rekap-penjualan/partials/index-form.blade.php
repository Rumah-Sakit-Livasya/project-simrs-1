<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian Rekap Penjualan</i></span>
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
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="gudang_id">
                                                Gudang
                                            </label>
                                        </div>
                                        <div class="col-xl">
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
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
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
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="kelompok_id">
                                                Kelompok Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control" id="kelompok_id" name="kelompok_id">
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
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="penjamin_id">
                                                Penjamin
                                            </label>
                                        </div>
                                        <div class="col-xl">
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
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="nama_obat">
                                                Nama Obat
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" name="nama_obat" id="nama_obat"
                                                placeholder="Semua nama barang" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                        @php
                            $startYear = 2019;
                            $currentYear = date('Y');
                            $currentMonth = date('n');
                            $years = range($startYear, $currentYear);
                            $months = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        <div class="accordion" id="js_demo_accordion-4">
                            <div class="card">
                                <div class="card-header">
                                    <a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse"
                                        data-target="#js_demo_accordion-4a" aria-expanded="false">
                                        Rekap: Per Tanggal
                                        <span class="ml-auto">
                                            <span class="collapsed-reveal">
                                                <i class="fal fa-minus-circle text-danger fs-xl"></i>
                                            </span>
                                            <span class="collapsed-hidden">
                                                <i class="fal fa-plus-circle text-success fs-xl"></i>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <div id="js_demo_accordion-4a" class="collapse" data-parent="#js_demo_accordion-4"
                                    style="">
                                    <div class="card-body">
                                        <div class="row justify-content-center">

                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-xl-4" style="text-align: right">
                                                            <label for="tanggal_order">Periode</label>
                                                        </div>
                                                        <div class="col-xl">
                                                            <div class="form-group row">
                                                                <div class="col-xl ">
                                                                    <input type="text" class="form-control"
                                                                        id="datepicker-1"
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
                                                <div class="col-xl">
                                                    <button type="button" onclick="showReport(event, 'tanggal')"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-search mr-1"></span>
                                                        Tampilkan Rekap
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse"
                                        data-target="#js_demo_accordion-4b" aria-expanded="false">
                                        Rekap: Per Bulan
                                        <span class="ml-auto">
                                            <span class="collapsed-reveal">
                                                <i class="fal fa-minus-circle text-danger fs-xl"></i>
                                            </span>
                                            <span class="collapsed-hidden">
                                                <i class="fal fa-plus-circle text-success fs-xl"></i>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <div id="js_demo_accordion-4b" class="collapse" data-parent="#js_demo_accordion-4"
                                    style="">
                                    <div class="card-body">
                                        <div class="row justify-content-center">

                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="year">Pilih Tahun:</label>
                                                    <select name="year_bulan" id="year_bulan" class="form-control">
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year }}"
                                                                {{ $year == $currentYear ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="month">Pilih Bulan:</label>
                                                    <select name="month_bulan" id="month_bulan" class="form-control">
                                                        @foreach ($months as $key => $month)
                                                            <option value="{{ $key }}/{{ $month }}"
                                                                {{ $key == $currentMonth ? 'selected' : '' }}>
                                                                {{ $month }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl">
                                                    <button type="button" onclick="showReport(event, 'bulan')"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-search mr-1"></span>
                                                        Tampilkan Rekap
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse"
                                        data-target="#js_demo_accordion-4c" aria-expanded="false">
                                        Rekap: Per Tahun
                                        <span class="ml-auto">
                                            <span class="collapsed-reveal">
                                                <i class="fal fa-minus-circle text-danger fs-xl"></i>
                                            </span>
                                            <span class="collapsed-hidden">
                                                <i class="fal fa-plus-circle text-success fs-xl"></i>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <div id="js_demo_accordion-4c" class="collapse" data-parent="#js_demo_accordion-4"
                                    style="">
                                    <div class="card-body">
                                        <div class="row justify-content-center">

                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="year">Pilih Tahun:</label>
                                                    <select name="year_tahun" id="year_tahun" class="form-control">
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year }}"
                                                                {{ $year == $currentYear ? 'selected' : '' }}>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="col-xl">
                                                    <button type="button" onclick="showReport(event, 'tahun')"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-search mr-1"></span>
                                                        Tampilkan Rekap
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showReport(event, tipe) {
        event.preventDefault();
        const target = event.target;
        const form = target.form;

        // tanggal_order is split into startDate and endDate
        const gudang_id = form.querySelector('select[name="gudang_id"]').value || '-';
        const doctor_id = form.querySelector('select[name="doctor_id"]').value || '-';
        const kelompok_id = form.querySelector('select[name="kelompok_id"]').value || '-';
        const penjamin_id = form.querySelector('select[name="penjamin_id"]').value || '-';
        const nama_obat = form.querySelector('input[name="nama_obat"]').value || '-';
        const Request = {
            gudang_id,
            doctor_id,
            kelompok_id,
            penjamin_id,
            nama_obat
        };

        switch (tipe) {
            case 'tanggal':
                const tanggal_order = form.querySelector('input[name="tanggal_order"]').value;
                Request['tanggal_order'] = tanggal_order;
                break;

            case 'bulan':
                const tahunBulan = form.querySelector('select[name="year_bulan"]').value;
                const bulanNama = form.querySelector('select[name="month_bulan"]').value.split('/');
                const bulan = bulanNama[0];
                const NamaBulan = bulanNama[1];

                Request['tahun'] = tahunBulan;
                Request['bulan'] = bulan;
                Request['nama_bulan'] = NamaBulan;
                break;

            case 'tahun':
                const tahunTahun = form.querySelector('select[name="year_tahun"]').value;
                Request['tahun'] = tahunTahun;
                break;

        }

        const Encrypted = btoa(JSON.stringify(Request));
        const src =
            `/simrs/farmasi/laporan/rekap-penjualan/view/${tipe}/${Encrypted}/`;

        window.open(
            src,
            "popupWindow_reportFarmasiRekapPenjualan",
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
