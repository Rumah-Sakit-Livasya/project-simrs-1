<div class="col-md-12 mt-3">
    <label><b>Nilai Apgar Score</b></label>
    <div class="table-responsive" @style('max-width: 70vw;')>
        <table class="table table-bordered apgar-table">
            <thead>
                <tr>
                    <th rowspan="2">Tanda</th>
                    <th colspan="3">1 Menit</th>
                    <th colspan="3">5 Menit</th>
                    <th colspan="3">10 Menit</th>
                </tr>
                <tr>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>Bunyi Jantung</b></td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_1mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_1mnt" @checked(isset($data['bunyi_jantung']['1mnt']) && $data['bunyi_jantung']['1mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_1mnt" value="< 100 x/menit" data-skor="1"
                                    class="apgar_1mnt" @checked(isset($data['bunyi_jantung']['1mnt']) && $data['bunyi_jantung']['1mnt'] == 1)>
                                <span>&lt; 100 x/menit</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_1mnt" value="> 100 x/menit" data-skor="2"
                                    class="apgar_1mnt" @checked(isset($data['bunyi_jantung']['1mnt']) && $data['bunyi_jantung']['1mnt'] == 2)>
                                <span>&gt; 100 x/menit</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_5mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_5mnt" @checked(isset($data['bunyi_jantung']['5mnt']) && $data['bunyi_jantung']['5mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_5mnt" value="< 100 x/menit" data-skor="1"
                                    class="apgar_5mnt" @checked(isset($data['bunyi_jantung']['5mnt']) && $data['bunyi_jantung']['5mnt'] == 1)>
                                <span>&lt; 100 x/menit</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_5mnt" value="> 100 x/menit" data-skor="2"
                                    class="apgar_5mnt" @checked(isset($data['bunyi_jantung']['5mnt']) && $data['bunyi_jantung']['5mnt'] == 2)>
                                <span>&gt; 100 x/menit</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_10mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_10mnt" @checked(isset($data['bunyi_jantung']['10mnt']) && $data['bunyi_jantung']['10mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_10mnt" value="< 100 x/menit" data-skor="1"
                                    class="apgar_10mnt" @checked(isset($data['bunyi_jantung']['10mnt']) && $data['bunyi_jantung']['10mnt'] == 1)>
                                <span>&lt; 100 x/menit</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="bunyi_jantung_10mnt" value="> 100 x/menit" data-skor="2"
                                    class="apgar_10mnt" @checked(isset($data['bunyi_jantung']['10mnt']) && $data['bunyi_jantung']['10mnt'] == 2)>
                                <span>&gt; 100 x/menit</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><b>Pernapasan</b></td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_1mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_1mnt" @checked(isset($data['pernapasan']['1mnt']) && $data['pernapasan']['1mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_1mnt" value="Tidak Teratur" data-skor="1"
                                    class="apgar_1mnt" @checked(isset($data['pernapasan']['1mnt']) && $data['pernapasan']['1mnt'] == 1)>
                                <span>Tidak Teratur</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_1mnt" value="Menangis" data-skor="2"
                                    class="apgar_1mnt" @checked(isset($data['pernapasan']['1mnt']) && $data['pernapasan']['1mnt'] == 2)>
                                <span>Menangis</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_5mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_5mnt" @checked(isset($data['pernapasan']['5mnt']) && $data['pernapasan']['5mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_5mnt" value="Tidak Teratur" data-skor="1"
                                    class="apgar_5mnt" @checked(isset($data['pernapasan']['5mnt']) && $data['pernapasan']['5mnt'] == 1)>
                                <span>Tidak Teratur</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_5mnt" value="Menangis" data-skor="2"
                                    class="apgar_5mnt" @checked(isset($data['pernapasan']['5mnt']) && $data['pernapasan']['5mnt'] == 2)>
                                <span>Menangis</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_10mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_10mnt" @checked(isset($data['pernapasan']['10mnt']) && $data['pernapasan']['10mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_10mnt" value="Tidak Teratur" data-skor="1"
                                    class="apgar_10mnt" @checked(isset($data['pernapasan']['10mnt']) && $data['pernapasan']['10mnt'] == 1)>
                                <span>Tidak Teratur</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="pernapasan_10mnt" value="Menangis" data-skor="2"
                                    class="apgar_10mnt" @checked(isset($data['pernapasan']['10mnt']) && $data['pernapasan']['10mnt'] == 2)>
                                <span>Menangis</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><b>Tonus Otot</b></td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_1mnt" value="Lemas" data-skor="0"
                                    class="apgar_1mnt" @checked(isset($data['tonus_otot']['1mnt']) && $data['tonus_otot']['1mnt'] == 0)>
                                <span>Lemas</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_1mnt" value="Sedikit Fleksi" data-skor="1"
                                    class="apgar_1mnt" @checked(isset($data['tonus_otot']['1mnt']) && $data['tonus_otot']['1mnt'] == 1)>
                                <span>Sedikit Fleksi</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_1mnt" value="Pergerakan Aktif" data-skor="2"
                                    class="apgar_1mnt" @checked(isset($data['tonus_otot']['1mnt']) && $data['tonus_otot']['1mnt'] == 2)>
                                <span>Pergerakan Aktif</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_5mnt" value="Lemas" data-skor="0"
                                    class="apgar_5mnt" @checked(isset($data['tonus_otot']['5mnt']) && $data['tonus_otot']['5mnt'] == 0)>
                                <span>Lemas</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_5mnt" value="Sedikit Fleksi" data-skor="1"
                                    class="apgar_5mnt" @checked(isset($data['tonus_otot']['5mnt']) && $data['tonus_otot']['5mnt'] == 1)>
                                <span>Sedikit Fleksi</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_5mnt" value="Pergerakan Aktif" data-skor="2"
                                    class="apgar_5mnt" @checked(isset($data['tonus_otot']['5mnt']) && $data['tonus_otot']['5mnt'] == 2)>
                                <span>Pergerakan Aktif</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_10mnt" value="Lemas" data-skor="0"
                                    class="apgar_10mnt" @checked(isset($data['tonus_otot']['10mnt']) && $data['tonus_otot']['10mnt'] == 0)>
                                <span>Lemas</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_10mnt" value="Sedikit Fleksi" data-skor="1"
                                    class="apgar_10mnt" @checked(isset($data['tonus_otot']['10mnt']) && $data['tonus_otot']['10mnt'] == 1)>
                                <span>Sedikit Fleksi</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="tonus_otot_10mnt" value="Pergerakan Aktif"
                                    data-skor="2" class="apgar_10mnt" @checked(isset($data['tonus_otot']['10mnt']) && $data['tonus_otot']['10mnt'] == 2)>
                                <span>Pergerakan Aktif</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><b>Reflek</b></td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_1mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_1mnt" @checked(isset($data['reflek']['1mnt']) && $data['reflek']['1mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_1mnt" value="Menyeringai" data-skor="1"
                                    class="apgar_1mnt" @checked(isset($data['reflek']['1mnt']) && $data['reflek']['1mnt'] == 1)>
                                <span>Menyeringai</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_1mnt" value="Menangis" data-skor="2"
                                    class="apgar_1mnt" @checked(isset($data['reflek']['1mnt']) && $data['reflek']['1mnt'] == 2)>
                                <span>Menangis</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_5mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_5mnt" @checked(isset($data['reflek']['5mnt']) && $data['reflek']['5mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_5mnt" value="Menyeringai" data-skor="1"
                                    class="apgar_5mnt" @checked(isset($data['reflek']['5mnt']) && $data['reflek']['5mnt'] == 1)>
                                <span>Menyeringai</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_5mnt" value="Menangis" data-skor="2"
                                    class="apgar_5mnt" @checked(isset($data['reflek']['5mnt']) && $data['reflek']['5mnt'] == 2)>
                                <span>Menangis</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_10mnt" value="Tidak Ada" data-skor="0"
                                    class="apgar_10mnt" @checked(isset($data['reflek']['10mnt']) && $data['reflek']['10mnt'] == 0)>
                                <span>Tidak Ada</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_10mnt" value="Menyeringai" data-skor="1"
                                    class="apgar_10mnt" @checked(isset($data['reflek']['10mnt']) && $data['reflek']['10mnt'] == 1)>
                                <span>Menyeringai</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="reflek_10mnt" value="Menangis" data-skor="2"
                                    class="apgar_10mnt" @checked(isset($data['reflek']['10mnt']) && $data['reflek']['10mnt'] == 2)>
                                <span>Menangis</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><b>Warna Kulit</b></td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_1mnt" value="Biru Pucat" data-skor="0"
                                    class="apgar_1mnt" @checked(isset($data['warna_kulit']['1mnt']) && $data['warna_kulit']['1mnt'] == 0)>
                                <span>Biru Pucat</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_1mnt" value="Badan Merah Ekstremitas Biru"
                                    data-skor="1" class="apgar_1mnt" @checked(isset($data['warna_kulit']['1mnt']) && $data['warna_kulit']['1mnt'] == 1)>
                                <span>Badan Merah Ekstremitas Biru</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_1mnt" value="Badan Merah" data-skor="2"
                                    class="apgar_1mnt" @checked(isset($data['warna_kulit']['1mnt']) && $data['warna_kulit']['1mnt'] == 2)>
                                <span>Badan Merah</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_5mnt" value="Biru Pucat" data-skor="0"
                                    class="apgar_5mnt" @checked(isset($data['warna_kulit']['5mnt']) && $data['warna_kulit']['5mnt'] == 0)>
                                <span>Biru Pucat</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_5mnt" value="Badan Merah Ekstremitas Biru"
                                    data-skor="1" class="apgar_5mnt" @checked(isset($data['warna_kulit']['5mnt']) && $data['warna_kulit']['5mnt'] == 1)>
                                <span>Badan Merah Ekstremitas Biru</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_5mnt" value="Badan Merah" data-skor="2"
                                    class="apgar_5mnt" @checked(isset($data['warna_kulit']['5mnt']) && $data['warna_kulit']['5mnt'] == 2)>
                                <span>Badan Merah</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_10mnt" value="Biru Pucat" data-skor="0"
                                    class="apgar_10mnt" @checked(isset($data['warna_kulit']['10mnt']) && $data['warna_kulit']['10mnt'] == 0)>
                                <span>Biru Pucat</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_10mnt" value="Badan Merah Ekstremitas Biru"
                                    data-skor="1" class="apgar_10mnt" @checked(isset($data['warna_kulit']['10mnt']) && $data['warna_kulit']['10mnt'] == 1)>
                                <span>Badan Merah Ekstremitas Biru</span>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="radio radio-styled">
                            <label>
                                <input type="radio" name="warna_kulit_10mnt" value="Badan Merah" data-skor="2"
                                    class="apgar_10mnt" @checked(isset($data['warna_kulit']['10mnt']) && $data['warna_kulit']['10mnt'] == 2)>
                                <span>Badan Merah</span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4>Jumlah</h4>
                    </td>
                    <td colspan="3">
                        <div class="form-group no-padding form-inline">
                            <label for="apgar_score_1mnt" class="control-label">1 Menit :</label>
                            &nbsp;
                            <input type="text" name="apgar_score_1mnt" id="apgar_score_1mnt" class="form-control"
                                style="font-size: 2rem; height: 50px; text-align: center;" readonly="readonly"
                                value="{{ $data['total_1mnt'] ?? '' }}">
                        </div>
                    </td>
                    <td colspan="3">
                        <div class="form-group no-padding form-inline">
                            <label for="apgar_score_5mnt" class="control-label">5 Menit :</label>
                            &nbsp;
                            <input type="text" name="apgar_score_5mnt" id="apgar_score_5mnt" class="form-control"
                                style="font-size: 2rem; height: 50px; text-align: center;" readonly="readonly"
                                value="{{ $data['total_5mnt'] ?? '' }}">
                        </div>
                    </td>
                    <td colspan="3">
                        <div class="form-group no-padding form-inline">
                            <label for="apgar_score_10mnt" class="control-label">10 Menit :</label>
                            &nbsp;
                            <input type="text" name="apgar_score_10mnt" id="apgar_score_10mnt"
                                class="form-control" style="font-size: 2rem; height: 50px; text-align: center;"
                                readonly="readonly" value="{{ $data['total_10mnt'] ?? '' }}">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style>
    /* Responsive adjustments for Apgar table */
    @media (max-width: 991.98px) {

        .apgar-table th,
        .apgar-table td {
            font-size: 0.85rem !important;
            padding: 0.25rem !important;
        }

        .apgar-table input[type="text"] {
            font-size: 1.3rem !important;
            height: 35px !important;
        }

        .form-group.form-inline label {
            font-size: 0.95rem;
        }
    }

    @media (max-width: 767.98px) {

        .apgar-table th,
        .apgar-table td {
            font-size: 0.76rem !important;
            padding: 0.175rem !important;
        }

        .apgar-table input[type="text"] {
            font-size: 1rem !important;
            height: 28px !important;
        }

        .form-group.form-inline label {
            font-size: 0.8rem;
        }

        .apgar-table h4 {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .apgar-table {
            min-width: 600px;
        }

        .apgar-table h4 {
            font-size: 0.95rem;
        }
    }
</style>
