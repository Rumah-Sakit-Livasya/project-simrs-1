<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian Klaim Embalase</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-4">
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

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
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

                            <div class="col-xl-4">
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
                                                <option value="otc">OTC/APS</option>
                                            </select>
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

        const gudang_id = form.querySelector('select[name="gudang_id"]').value || '-';
        const tipe = form.querySelector('select[name="tipe"]').value || '-';

        const src =
            `/simrs/farmasi/laporan/embalase/view/${startDate}/${endDate}/${gudang_id}/${tipe}/`;

        window.open(
            src,
            "popupWindow_" + new Date().getTime(),
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
