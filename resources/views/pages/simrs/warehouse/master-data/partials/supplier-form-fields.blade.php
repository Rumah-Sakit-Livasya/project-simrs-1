{{-- This is a partial view for supplier form fields --}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-nama">Nama Supplier</label>
            <input type="text" id="{{ $prefix }}-nama" name="nama" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <div class="frame-wrap">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="{{ $prefix }}-kategori-farmasi"
                        name="kategori" value="FARMASI" checked>
                    <label class="custom-control-label" for="{{ $prefix }}-kategori-farmasi">Farmasi</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="{{ $prefix }}-kategori-umum"
                        name="kategori" value="UMUM">
                    <label class="custom-control-label" for="{{ $prefix }}-kategori-umum">Umum</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="form-label" for="{{ $prefix }}-alamat">Alamat</label>
    <textarea id="{{ $prefix }}-alamat" name="alamat" class="form-control" rows="2"></textarea>
</div>
<hr>
<h5 class="form-heading">Info Kontak Perusahaan</h5>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-phone">Telepon</label>
            <input type="text" id="{{ $prefix }}-phone" name="phone" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-fax">Fax</label>
            <input type="text" id="{{ $prefix }}-fax" name="fax" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-email">Email</label>
            <input type="email" id="{{ $prefix }}-email" name="email" class="form-control">
        </div>
    </div>
</div>
<hr>
<h5 class="form-heading">Info Contact Person</h5>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-contact_person">Nama CP</label>
            <input type="text" id="{{ $prefix }}-contact_person" name="contact_person" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-contact_person_phone">Telepon CP</label>
            <input type="text" id="{{ $prefix }}-contact_person_phone" name="contact_person_phone"
                class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-contact_person_email">Email CP</label>
            <input type="email" id="{{ $prefix }}-contact_person_email" name="contact_person_email"
                class="form-control">
        </div>
    </div>
</div>
<hr>
<h5 class="form-heading">Info Keuangan</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-no_rek">No. Rekening</label>
            <input type="text" id="{{ $prefix }}-no_rek" name="no_rek" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-bank">Bank</label>
            <input type="text" id="{{ $prefix }}-bank" name="bank" class="form-control">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-top">Term of Payment (TOP)</label>
            <select class="form-control" id="{{ $prefix }}-top" name="top">
                <option value="">Pilih TOP...</option>
                @foreach ($topOptions as $option)
                    <option value="{{ $option }}">{{ str_replace('HARI', ' HARI', $option) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-tipe_top">Tipe TOP</label>
            <select class="form-control" id="{{ $prefix }}-tipe_top" name="tipe_top">
                <option value="">Pilih Tipe TOP...</option>
                @foreach ($tipeTopOptions as $option)
                    <option value="{{ $option }}">{{ ucwords(strtolower(str_replace('_', ' ', $option))) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="{{ $prefix }}-ppn">PPN (%)</label>
            <input type="number" id="{{ $prefix }}-ppn" name="ppn" class="form-control" value="0"
                min="0" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Status</label>
            <div class="frame-wrap">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="{{ $prefix }}-aktif-true"
                        name="aktif" value="1" checked>
                    <label class="custom-control-label" for="{{ $prefix }}-aktif-true">Aktif</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="{{ $prefix }}-aktif-false"
                        name="aktif" value="0">
                    <label class="custom-control-label" for="{{ $prefix }}-aktif-false">Non Aktif</label>
                </div>
            </div>
        </div>
    </div>
</div>
