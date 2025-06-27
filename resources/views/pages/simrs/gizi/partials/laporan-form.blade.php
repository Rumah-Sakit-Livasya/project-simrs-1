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
                                            <label class="form-label text-end" for="kategori_id">
                                                Kategori Menu
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="kategori_id"
                                                name="kategori_id">
                                                <option value=""></option>
                                                @foreach ($categories as $kategori)
                                                    <option value="{{ $kategori->id }}">
                                                        {{ $kategori->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="waktu_makan">Waktu Makan</label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="waktu_makan"
                                                name="waktu_makan">
                                                <option value=""></option>
                                                <option value="pagi">Pagi</option>
                                                <option value="siang">Siang</option>
                                                <option value="sore">Sore</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="food_id">Makanan</label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="food_id" name="food_id">
                                                <option value=""></option>
                                                @foreach ($foods as $food)
                                                    <option value="{{ $food->id }}">
                                                        {{ $food->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="status_payment">
                                                Status Payment
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="status_payment"
                                                name="status_payment">
                                                <option value=""></option>
                                                <option value="0">Not Billed</option>
                                                <option value="1">Payment (closed)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="untuk">
                                                Untuk
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="untuk"
                                                name="untuk">
                                                <option value=""></option>
                                                <option value="pasien">Pasien</option>
                                                <option value="keluarga">Keluarga Pasien</option>
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

        const kategori_id = form.querySelector('select[name="kategori_id"]').value || '-';
        const food_id = form.querySelector('select[name="food_id"]').value || '-';
        const status_payment = form.querySelector('select[name="status_payment"]').value || '-';
        const waktu_makan = form.querySelector('select[name="waktu_makan"]').value || '-';
        const untuk = form.querySelector('select[name="untuk"]').value || '-';

        const src =
            `/simrs/gizi/reports/view/${startDate}/${endDate}/${kategori_id}/${food_id}/${status_payment}/${waktu_makan}/${untuk}/`;

        window.open(
            src,
            "popupWindow_" + new Date().getTime(),
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
