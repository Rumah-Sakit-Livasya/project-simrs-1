{{-- Panel untuk Informasi Dasar --}}
<div class="panel">
    <div class="panel-hdr">
        <h5 class="panel-title">
            <i class="fal fa-info-circle mr-2"></i>
            Informasi Utama
        </h5>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="form-group row">
                <label for="{{ $prefix }}-nama" class="col-sm-3 col-form-label">Nama Supplier</label>
                <div class="col-sm-9">
                    <input type="text" id="{{ $prefix }}-nama" name="nama" class="form-control"
                        placeholder="Contoh: PT. Sumber Sehat Abadi" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kategori</label>
                <div class="col-sm-9">
                    <div class="custom-control custom-radio custom-control-inline mt-2">
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
            <div class="form-group row mb-0">
                <label for="{{ $prefix }}-alamat" class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-9">
                    <textarea id="{{ $prefix }}-alamat" name="alamat" class="form-control" rows="2"
                        placeholder="Alamat lengkap supplier"></textarea>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Panel untuk Informasi Kontak --}}
<div class="panel">
    <div class="panel-hdr">
        <h5 class="panel-title">
            <i class="fal fa-address-book mr-2"></i>
            Informasi Kontak
        </h5>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-phone">Telepon Perusahaan</label>
                        <input type="text" id="{{ $prefix }}-phone" name="phone" class="form-control"
                            placeholder="021-xxxxxxx">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-fax">Fax</label>
                        <input type="text" id="{{ $prefix }}-fax" name="fax" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-email">Email Perusahaan</label>
                        <input type="email" id="{{ $prefix }}-email" name="email" class="form-control"
                            placeholder="info@supplier.com">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="subheader">
                <h1 class="subheader-title">
                    <small>Contact Person (PIC)</small>
                </h1>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-contact_person">Nama PIC</label>
                        <input type="text" id="{{ $prefix }}-contact_person" name="contact_person"
                            class="form-control" placeholder="Nama PIC">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-contact_person_phone">Telepon PIC</label>
                        <input type="text" id="{{ $prefix }}-contact_person_phone"
                            name="contact_person_phone" class="form-control" placeholder="0812xxxxxxxx">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-contact_person_email">Email PIC</label>
                        <input type="email" id="{{ $prefix }}-contact_person_email"
                            name="contact_person_email" class="form-control" placeholder="pic@supplier.com">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Panel untuk Informasi Keuangan & Lainnya --}}
<div class="panel">
    <div class="panel-hdr">
        <h5 class="panel-title">
            <i class="fal fa-credit-card mr-2"></i>
            Informasi Keuangan & Lainnya
        </h5>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-no_rek">No. Rekening</label>
                        <input type="text" id="{{ $prefix }}-no_rek" name="no_rek" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-bank">Bank</label>
                        <input type="text" id="{{ $prefix }}-bank" name="bank" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-top">Term of Payment (TOP)</label>
                        <select class="form-control select2" id="{{ $prefix }}-top" name="top"
                            data-prefix="{{ $prefix }}">
                            <option value="">Pilih TOP...</option>
                            @foreach ($topOptions as $option)
                                <option value="{{ $option }}">{{ str_replace('HARI', ' HARI', $option) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-tipe_top">Tipe TOP</label>
                        <select class="form-control select2" id="{{ $prefix }}-tipe_top" name="tipe_top"
                            data-prefix="{{ $prefix }}">
                            <option value="">Pilih Tipe TOP...</option>
                            @foreach ($tipeTopOptions as $option)
                                <option value="{{ $option }}">
                                    {{ ucwords(strtolower(str_replace('_', ' ', $option))) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="{{ $prefix }}-ppn">PPN (%)</label>
                        <div class="input-group">
                            <input type="number" id="{{ $prefix }}-ppn" name="ppn" class="form-control"
                                value="11" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="custom-control custom-radio custom-control-inline mt-2">
                            <input type="radio" class="custom-control-input" id="{{ $prefix }}-aktif-true"
                                name="aktif" value="1" checked>
                            <label class="custom-control-label" for="{{ $prefix }}-aktif-true">Aktif</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="{{ $prefix }}-aktif-false"
                                name="aktif" value="0">
                            <label class="custom-control-label" for="{{ $prefix }}-aktif-false">Non
                                Aktif</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
