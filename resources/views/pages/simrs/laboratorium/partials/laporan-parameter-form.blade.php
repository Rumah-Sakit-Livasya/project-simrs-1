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
                                            <label for="registration_date">Tgl. Registrasi</label>
                                        </div>
                                        <div class="col-xl">
                                            <div class="form-group row">
                                                <div class="col-xl ">
                                                    <input type="text" class="form-control" id="datepicker-1"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        placeholder="Select date" name="registration_date"
                                                        value="01/01/2018 - 01/15/2018">
                                                </div>
                                            </div>
                                            @error('registration_date')
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
                                            <label class="form-label text-end" for="medical_record_number">
                                                Tipe Rawat
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="tipe_rawat"
                                                name="tipe_rawat">
                                                <option value="">ALL</option>
                                                <option value="rajal">Rawat Jalan</option>
                                                <option value="ranap">Rawat Inap</option>
                                                <option value="otc">OTC</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="name">
                                                Penjamin / Asuransi
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="penjamin"
                                                name="penjamin">
                                                <option value=""></option>
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

                            {{-- <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="name">
                                                Group Parameter
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="group_parameter"
                                                name="group_parameter">
                                                <option value=""></option>
                                                @foreach ($groupParameters as $groupParameter)
                                                    <option value="{{ $groupParameter->id }}">
                                                        {{ $groupParameter->nama_grup }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        {{-- <div class="row justify-content-center mt-4">

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="status">Radiografer</label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="radiografer"
                                                name="radiografer">
                                                <option value=""></option>
                                                @foreach ($radiografers as $radiografer)
                                                    <option value="{{ $radiografer->id }}">
                                                        {{ $radiografer->fullname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> --}}

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

        // registration_date is split into startDate and endDate
        const registration_date = form.querySelector('input[name="registration_date"]').value;
        const [startDate, endDate] = registration_date.split(' - ');

        const tipe_rawat = form.querySelector('select[name="tipe_rawat"]').value || '-';
        const penjamin = form.querySelector('select[name="penjamin"]').value || '-';

        const src =
            `/simrs/laboratorium/laporan-parameter-view/${startDate}/${endDate}/${tipe_rawat}/${penjamin}/`;

        window.open(
            src,
            "popupWindow_" + new Date().getTime(),
            "width=" + screen.width + ",height=" + screen.height +
            ",scrollbars=yes,resizable=yes"
        );
    }
</script>
