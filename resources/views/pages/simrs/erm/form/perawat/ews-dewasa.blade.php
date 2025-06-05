@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" autocomplete="off" id="ews-dewasa">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">EARLY WARNING SCORING SYSTEM (DEWASA)</h2>
                    </header>
                    <header class="text-success">
                        <h4 class="mt-5 font-weight-bold text-center">MASUK RUMAH SAKIT</h4>
                    </header>
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tgl" class="text-primary d-block text-center">Tanggal &amp; jam
                                    masuk</label>
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="date" name="tgl" class="form-control " placeholder="Tanggal"
                                            id="tgl" value="{{ $registration->created_at->format('Y-m-d') }}">
                                        <input type="time" name="jam" class="form-control " placeholder="Jam"
                                            id="jam" value="{{ $registration->created_at->format('h:i') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <table class="table table-bordered table-hover m-0">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th class="bg-primary text-white">Blue</th>
                                        <th>Skor : 3</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 2</th>
                                        <th>Skor : 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">LAJU RESPIRASI /MENIT</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-1">
                                                        <input name="laju_respirasi" id="field-1" value="≤ 5"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≤ 5</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-2">
                                                        <input name="laju_respirasi" id="field-2" value="6 - 8"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">6 - 8</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-3">
                                                        <input name="laju_respirasi" id="field-3" value="9 - 11"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">9 - 11</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-4">
                                                        <input name="laju_respirasi" id="field-4" value="12 - 20"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">12 - 20</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-5">
                                                        <input name="laju_respirasi" id="field-5" value="21 - 24"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">21 - 24</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-6">
                                                        <input name="laju_respirasi" id="field-6" value="25 - 34"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">25 - 34</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-hover m-0 mt-3">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 2</th>
                                        <th>Skor : 3</th>
                                        <th>Skor : 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">SATURASI 02</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-7">
                                                        <input name="saturasi" id="field-7" value="≥ 96"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≥ 96</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-8">
                                                        <input name="saturasi" id="field-8" value="94 - 95"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">94 - 95</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-9">
                                                        <input name="saturasi" id="field-9" value="92 - 92"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">92 - 92</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-10">
                                                        <input name="saturasi" id="field-10" value="≤ 91"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≤ 91</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">SUPLEMEN 02</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-11">
                                                        <input name="suplemen" id="field-11" value="%"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">%</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-hover m-0 mt-3">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Skor : 3</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 2</th>
                                        <th>Skor : 3</th>
                                        <th class="bg-primary text-white">Blue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">Tekanan darah Sistolik (mmHg)</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-12">
                                                        <input name="tekanan_darah" id="field-12" value="≥ 220"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≥ 220</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-13">
                                                        <input name="tekanan_darah" id="field-13" value="181 - 220"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">181 - 220</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-14">
                                                        <input name="tekanan_darah" id="field-14" value="111 - 180"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">111 - 180</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-15">
                                                        <input name="tekanan_darah" id="field-15" value="101 - 110"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">101 - 110</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-16">
                                                        <input name="tekanan_darah" id="field-16" value="91 - 100"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">91 - 100</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-17">
                                                        <input name="tekanan_darah" id="field-17" value="71 - 90"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">71 - 90</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-18">
                                                        <input name="tekanan_darah" id="field-18" value="≤ 71"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≤ 71</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-hover m-0 mt-3">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th class="bg-primary text-white">Blue</th>
                                        <th>Skor : 3</th>
                                        <th>Skor : 2</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 1</th>
                                        <th class="bg-primary text-white">Blue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">Laju Jantung /Menit</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-19">
                                                        <input name="laju_jantung" id="field-19" value="≥ 140"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≥ 140</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-20">
                                                        <input name="laju_jantung" id="field-20" value="131 - 140"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">131 - 140</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-21">
                                                        <input name="laju_jantung" id="field-21" value="111 - 130"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">111 - 130</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-22">
                                                        <input name="laju_jantung" id="field-22" value="91 - 100"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">91 - 100</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-23">
                                                        <input name="laju_jantung" id="field-23" value="51 - 90"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">51 - 90</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-24">
                                                        <input name="laju_jantung" id="field-24" value="41 - 50"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">41 - 50</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-25">
                                                        <input name="laju_jantung" id="field-25" value="≤ 40"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≤ 40</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-hover m-0 mt-3">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 3</th>
                                        <th class="bg-primary text-white">Blue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">Kesadaran</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-26">
                                                        <input name="kesadaran" id="field-26" value="Sadar"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Sadar</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-27">
                                                        <input name="kesadaran" id="field-27" value="Nyeri / Verbal"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Nyeri /
                                                            Verbal</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-28">
                                                        <input name="kesadaran" id="field-28" value="Unrespon"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">Unrespon</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-hover m-0 mt-3">
                                <thead class="thead-themed">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Skor : 3</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 0</th>
                                        <th>Skor : 1</th>
                                        <th>Skor : 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" style="white-space: nowrap">Temperatur (°C)</th>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-29">
                                                        <input name="temperatur" id="field-29" value="≤ 35"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≤ 35</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-30">
                                                        <input name="temperatur" id="field-30" value="35.1 - 36"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">35.1 - 36</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-31">
                                                        <input name="temperatur" id="field-31" value="36.1 - 38"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">36.1 - 38</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-32">
                                                        <input name="temperatur" id="field-32" value="38.1 - 39"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">38.1 - 39</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="field-33">
                                                        <input name="temperatur" id="field-33" value="≥ 39"
                                                            type="radio" class="custom-control-input">
                                                        <span class="custom-control-label text-primary">≥ 39</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-hover m-0 mt-3">
                                <tbody>
                                    <tr>
                                        <td>Skor Total <br>
                                            <input class="form-control border-0" readonly
                                                style="font-size: 25pt; border-bottom: 2px solid #eaeaea !important"
                                                type="text" name="skor_total" id="skor">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GDS <br>
                                            <input class="form-control border-0"
                                                style="font-size: 25pt; border-bottom: 2px solid #eaeaea !important"
                                                type="text" name="gds" id="gds">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Skor Nyeri <br>
                                            <input class="form-control border-0"
                                                style="font-size: 25pt; border-bottom: 2px solid #eaeaea !important"
                                                type="text" name="skor_nyeri" id="skor-nyeri">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Urin Output <br>
                                            <input class="form-control border-0"
                                                style="font-size: 25pt; border-bottom: 2px solid #eaeaea !important"
                                                type="text" name="urin_output" id="urin_output">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-hover m-0">
                                <tbody>
                                    <tr>
                                        <th><strong>SKOR 1-4 (resiko ringan)</strong></th>
                                        <td>Assesment segera oleh perawat senior, respon segera, maks 5 menit, eskalasi
                                            perawatan dan frekuensi monitoring per 4-6 jam, Jika diperlukan assesment oleh
                                            dokter jaga bangsal</td>
                                    </tr>
                                    <tr>
                                        <th><strong>SKOR 5-6 (resiko sedang)</strong></th>
                                        <td>Assesment segera oleh dokter jaga (respon segera, maks 5 menit), konsultasi DPJP
                                            dan spesialis terkait, eksalasi perawatan dn monitoring tiap jam, pertimbangkan
                                            perawatan dengan monitoring yang sesuai (HCU/ICU)</td>
                                    </tr>
                                    <tr>
                                        <th><strong>SKOR 7 ATAU lebih /1 parameter Kriteria Blue (resiko tinggi)</strong>
                                        </th>
                                        <td>Resusitasi dan monitoring secara kontinyu oleh dokter jaga dan perawat senior,
                                            Aktivitas kegawatan medis respon Tim Medis Reaksi Cepat (TMRC) Telp (224) respon
                                            segera, maksimal 10 menit, Informasikan dan konsultasikan ke DPJP</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-primary text-white"><strong>HENTI NAPAS / JANTUNG</strong></th>
                                        <td>Lakukan RJP oleh petugas/tim primer, aktivitas code blue henti jantung (199),
                                            Respon Tim Medis Reaksi Cepat (TMRC) segera, maksimal 5 mneit, informasikan dan
                                            konsultasikan DPJP</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- <tr>
                        <td colspan="7">Skor Total: <br>
                            <span id="score" class="h1" style="font-size: 30pt">0</span>
                            <hr>
                        </td>
                    </tr> --}}

                    <div class="row mt-5">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer mr-2"></span> Print
                                    </button>
                                    <div style="width: 33%" class="d-flex justify-content-between">
                                        {{-- <button type="button"
                                            class="btn btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-transfer-pasien-antar-ruangan">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button> --}}
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-transfer-pasien-antar-ruangan">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Scoring logic mapping based on your thead scorings, index corresponds to TD position with inputs
            const scoringLogic = {
                'laju_respirasi': [0, 3, 1, 0, 2, 3],
                'saturasi': [0, 1, 2, 3],
                'suplemen': [0, 0, 0, 0, 2], // Only one option
                'tekanan_darah': [3, 1, 0, 1, 2, 3, 0],
                'laju_jantung': [0, 3, 2, 1, 0, 1, 0],
                'kesadaran': [0, 3, 0],
                'temperatur': [3, 1, 0, 1, 2],
            };

            // buatkan fungsi konversi rupiah menjadi angka

            function calculateTotalScore() {
                let totalScore = 0;
                for (const [groupName, scoreArray] of Object.entries(scoringLogic)) {
                    // Find checked input in the group
                    const checkedInput = document.querySelector(`input[name="${groupName}"]:checked`);
                    if (checkedInput) {
                        // The checked input is inside: label > div.form-radio > div.form-group > td
                        // We want the TD element that contains the checked input to find index in the TR
                        // DOM traversal up to TD:
                        let tdElement = checkedInput.closest('td');
                        if (!tdElement) continue;

                        let trElement = tdElement.parentElement;
                        if (!trElement) continue;

                        // Get all TD siblings in the TR
                        const tdSiblings = Array.from(trElement.querySelectorAll('td'));

                        // Find index of this TD among all TDs in the TR
                        const tdIndex = tdSiblings.indexOf(tdElement);

                        // It's possible the scoreArray length does not match td count exactly,
                        // map index to score safely; if out of range, count as zero.
                        const score = scoreArray[tdIndex] !== undefined ? scoreArray[tdIndex] : 0;
                        totalScore += score;
                    }
                }
                // Update the score input field
                const scoreField = document.getElementById('skor');
                if (scoreField) {
                    scoreField.value = totalScore;
                }
            }

            // Attach event listener on all radio buttons for immediate score calculation on change
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', calculateTotalScore);
            });
        });

        $(document).ready(function() {
            // Tangani submit form
            $('#ews-dewasa').on('submit', function(e) {
                e.preventDefault(); // mencegah submit form default
                const form = $(this);
                const formData = form.serialize(); // serialize data form
                $.ajax({
                    url: '/api/simrs/erm/ews-dewasa',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showSuccessAlert(response.message || 'Data berhasil disimpan');
                        // lakukan tindakan lain jika perlu
                    },
                    error: function(xhr) {
                        let errMsg = 'Gagal menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        showErrorAlertNoRefresh(errMsg);
                    }
                });
            });

            // GET DATA REGISTRATION BY ID
            var registrationId = {{ $registration->id }};
            console.log('Registration ID:', registrationId);

            if (registrationId) {
                $.ajax({
                    url: `/api/simrs/erm/ews-dewasa/${registrationId}`, // Ganti dengan URL endpoint yang sesuai
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#loading-indicator').show(); // Tampilkan loader
                    },
                    success: function(response) {
                        console.log(response.data); // 👉 Lihat data di console
                        if (response.data) {
                            let data = response.data;

                            // Mengisi input form dengan data yang diterima
                            $('input[name="tgl"]').val(data.tgl);
                            $('input[name="jam"]').val(data.jam);
                            $('input[name="skor_total"]').val(data.skor_total);
                            $('input[name="gds"]').val(data.gds);
                            $('input[name="skor_nyeri"]').val(data.skor_nyeri);
                            $('input[name="urin_output"]').val(data.urin_output);

                            // Set radio buttons for each scoring parameter
                            $('input[name="laju_respirasi"][value="' + data.laju_respirasi + '"]').prop(
                                'checked', true);
                            $('input[name="saturasi"][value="' + data.saturasi + '"]').prop('checked',
                                true);
                            $('input[name="suplemen"][value="' + data.suplemen + '"]').prop('checked',
                                true);
                            $('input[name="tekanan_darah"][value="' + data.tekanan_darah + '"]').prop(
                                'checked', true);
                            $('input[name="laju_jantung"][value="' + data.laju_jantung + '"]').prop(
                                'checked', true);
                            $('input[name="kesadaran"][value="' + data.kesadaran + '"]').prop('checked',
                                true);
                            $('input[name="temperatur"][value="' + data.temperatur + '"]').prop(
                                'checked', true);
                        }
                    },
                    error: function(xhr) {
                        console.error('Load data error:', xhr.responseText);
                    },
                    complete: function() {
                        $('#loading-indicator').hide(); // Sembunyikan loader setelah selesai
                    },
                });
            }

        });
    </script>
@endsection
