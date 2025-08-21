<h5 class="font-weight-bold mt-4 mb-3">Pemakaian Antimikroba</h5>
@for ($i = 0; $i < 4; $i++)
    <div class="card mb-3">
        <div class="card-header"><b>Antimikroba #{{ $i + 1 }}</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Nama Antimikroba</label>
                    <input class="form-control" type="text" name="pemakaian_antimikroba[{{ $i }}][nama]"
                        value="{{ $data[$i]['nama'] ?? '' }}">
                </div>
                <div class="col-md-2 form-group">
                    <label>Dosis</label>
                    <input class="form-control" type="text" name="pemakaian_antimikroba[{{ $i }}][dosis]"
                        value="{{ $data[$i]['dosis'] ?? '' }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>Mulai Tgl</label>
                    <input class="form-control" type="date"
                        name="pemakaian_antimikroba[{{ $i }}][tgl_mulai]"
                        value="{{ $data[$i]['tgl_mulai'] ?? '' }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>s/d Tgl</label>
                    <input class="form-control" type="date"
                        name="pemakaian_antimikroba[{{ $i }}][tgl_selesai]"
                        value="{{ $data[$i]['tgl_selesai'] ?? '' }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>Waktu Pemberian (Profilaksis/Pengobatan)</label>
                    <select class="form-control" name="pemakaian_antimikroba[{{ $i }}][waktu_pemberian]">
                        <option value=""></option>
                        <option value="Pre Operasi" @selected(isset($data[$i]['waktu_pemberian']) && $data[$i]['waktu_pemberian'] == 'Pre Operasi')>Pre Operasi</option>
                        <option value="Selama Operasi" @selected(isset($data[$i]['waktu_pemberian']) && $data[$i]['waktu_pemberian'] == 'Selama Operasi')>Selama Operasi</option>
                        <option value="Sesudah Operasi" @selected(isset($data[$i]['waktu_pemberian']) && $data[$i]['waktu_pemberian'] == 'Sesudah Operasi')>Sesudah Operasi</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endfor
