@extends('inc.layout')
@section('title', 'Form Edit Resep [' . $resep->kode_resep .']')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        #modal-jam-pemberian-content {
            max-width: 510px !important;
        }

        #modal-signa-content {
            max-width: 700px !important;
        }

        .borderless-input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0
        }

        .qty {
            min-width: 100px;
            margin-left: 10px;
        }

        input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0;
        }

        input[type='checkbox'] {
            width: 1.5rem;
            height: 1.5rem;
            margin: 0.5rem;
        }

        #loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }

        .embalase-label {
            display: inline-block;
            width: 80px;
        }

        /* Define the fade animation */
        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
                /* or 0 for complete fade out */
            }
        }

        /* Apply the animation to the element */
        #add-to-racikan {
            animation: fadeInOut 0.75s infinite ease-in-out;
            position: absolute;
            text-align: right;
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @include('pages.simrs.farmasi.transaksi-resep.partials.modal-jam-pemberian')
    @include('pages.simrs.farmasi.transaksi-resep.partials.modal-signa')
    @include('pages.simrs.farmasi.transaksi-resep.partials.modal-pilih-obat')

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Edit Resep [{{ $resep->kode_resep }}]
                            &nbsp;
                            <i id="loading-spinner-head" class="loading fas fa-spinner fa-spin"></i>
                            <span class="loading text-info" id="loading-message">Loading...</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page" class="loading"></div>
                        <div class="panel-content">
                            <form id="form-resep" name="form-resep" action="{{ route('farmasi.transaksi-resep.update', ['id' => $resep->id]) }}"
                                method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="resep_id" id="resep-id" value="{{ $resep->id }}">

                                @php
                                    $isOTC = $resep->otc_id != null;
                                @endphp


                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="order_date">
                                                        Tanggal Resep
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="date" class="form-control unclearable"
                                                        id="datepicker-1" placeholder="Select date" name="order_date"
                                                        value="{{ $resep->order_date }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="nama_pasien">
                                                        Nama Pasien
                                                    </label>
                                                    <button disabled onclick="event.preventDefault()" class="btn btn-primary"
                                                        id="pilih-pasien-btn"><span
                                                            class="fal fa-search mr-1"></span></button>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $isOTC ? $resep->otc->nama_pasien : $resep->registration->patient->name }}" required
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="nama_pasien" name="nama_pasien">
                                                    @error('nama_pasien')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="nama_dokter">
                                                        Nama Dokter
                                                    </label>
                                                    <button disabled onclick="event.preventDefault()" class="btn btn-primary"
                                                        id="pilih-dokter-btn"><span
                                                            class="fal fa-search mr-1"></span></button>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $resep->doctor?->employee->fullname }}" required
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="nama_dokter" name="nama_dokter">
                                                    @error('nama_dokter')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="gudang_id">
                                                        Gudang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="gudang_id" id="gudang_id"
                                                        class="form-control unclearable select2" required>
                                                        <option value=""
                                                            {{ !isset($default_apotek) ? 'selected' : '' }} disabled hidden>
                                                            Pilih Gudang</option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}"
                                                                {{ isset($default_apotek) && $default_apotek->id == $gudang->id ? 'selected' : '' }}>
                                                                {{ $gudang->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="mrn_registration_number">
                                                        No RM / No Reg
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $isOTC ? 'OTC' : $resep->registration->patient->medical_record_number . ' / ' . $resep->registration->registration_number }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="mrn_registration_number"
                                                        name="mrn_registration_number">
                                                    @error('mrn_registration_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="penjamin">
                                                        Penjamin
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $isOTC ? 'OTC' : $resep->registration->penjamin->nama_perusahaan }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="penjamin" name="penjamin">
                                                    @error('penjamin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe_pasien">
                                                        Tipe Pasien
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select disabled class="form-control unclearable" name="tipe_pasien"
                                                        id="tipe_pasien">
                                                        <option {{ $resep->tipe_pasien == 'rajal' ? 'selected' : '' }} value="rajal">Rawat Jalan</option>
                                                        <option {{ $resep->tipe_pasien == 'ranap' ? 'selected' : '' }} value="ranap">Rawat Inap</option>
                                                        <option {{ $resep->tipe_pasien == 'otc' ? 'selected' : '' }} value="otc">OTC</option>
                                                    </select>
                                                    @error('tipe_pasien')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="umur_jk">
                                                        Umur / Jenis Kelamin
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $isOTC ? 'OTC' : displayAge($resep->registration->patient->date_of_birth) . ' / ' . $resep->registration->patient->gender }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="umur_jk" name="umur_jk">
                                                    @error('umur_jk')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="alamat">
                                                        Alamat
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="text" value="{{ $isOTC ? 'OTC' : $resep->registration->patient->address }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control otc-change" id="alamat" name="alamat">
                                                    @error('alamat')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="poly_ruang">
                                                        Poly / Ruang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    @php
                                                        $poly_ruang = "";
                                                        // Ensure $resep exists and is not null
                                                        if (isset($resep)) {
                                                            switch ($resep->tipe_pasien) {
                                                                case 'otc':
                                                                    $poly_ruang = 'OTC';
                                                                    break;
                                                                case 'rajal':
                                                                    $poly_ruang = 'RAWAT JALAN ('. $resep->doctor->department_from_doctors->name .')';
                                                                    break;
                                                                case 'ranap':
                                                                    $poly_ruang = 'RAWAT INAP ('. $resep->registration->kelas_rawat->kelas .' - '. $resep->registration->patient->bed->room->ruangan .' '. $resep->registration->patient->bed->nama_tt .')';
                                                                    break;
                                                            }
                                                        }
                                                    @endphp
                                                    <input type="text" readonly value="{{ $poly_ruang }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="poly_ruang" name="poly_ruang">
                                                    @error('poly_ruang')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="no_telp">
                                                        No Telp
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" readonly value="{{ $isOTC ? 'OTC' : $resep->registration->patient->mobile_phone_number }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="no_telp" name="no_telp">
                                                    @error('no_telp')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="kronis">
                                                        Kronis
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="checkbox" {{ $resep->kronis == 1 ? 'checked' : '' }} name="kronis" id="kronis"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="embalase">
                                                        Embalase*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- create radio with name="embalase" with values "tidak", "item", "racikan" --}}
                                                    <div class="form-check">
                                                        <input class="form-check-input unclearable" type="radio" {{ $resep->embalase == 'racikan' ? 'checked' : '' }}
                                                            name="embalase" id="embalase_racikan" value="racikan">
                                                        <label class="embalase-label">Racikan</label>
                                                        <input class="form-check-input unclearable" type="radio" {{ $resep->embalase == 'item' ? 'checked' : '' }}
                                                            name="embalase" id="embalase_item" value="item">
                                                        <label class="embalase-label">Item</label>
                                                        <input class="form-check-input unclearable" type="radio" {{ $resep->embalase == 'tidak' ? 'checked' : '' }}
                                                            name="embalase" id="embalase_tidak" value="tidak">
                                                        <label class="embalase-label">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="bmhp">
                                                        B.M.H.P.
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- create checkbox with name "bmhp" and label "Tidak ditagihkan ke pasien" --}}
                                                    <input type="checkbox" {{ $resep->bmhp == 1 ? 'checked' : '' }} name="bmhp" id="bmhp">
                                                    <label for="bmhp"> Tidak ditagihkan ke pasien </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="dispensing">
                                                        Dispensing
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="checkbox" {{ $resep->dispensing == 1 ? 'checked' : '' }} name="dispensing" id="dispensing"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="cari_obat">
                                                        Cari Obat
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select id="obat-select" class="form-control unclearable">
                                                        <option value="" selected disabled hidden> Pilih Obat
                                                        </option>
                                                        @if (isset($obats))
                                                            @foreach ($obats as $obat)
                                                                @php
                                                                    $items = $obat->stored_items->where(
                                                                        'gudang_id',
                                                                        $default_apotek->id,
                                                                    );
                                                                    $qty = $items->sum('qty');
                                                                    $obat->qty = $qty;
                                                                @endphp
                                                                @if ($qty > 0)
                                                                    <option value="{{ $obat->id }}" class="obat"
                                                                        data-zat="@foreach ($obat->zat_aktif as $zat_aktif) {{ $zat_aktif->zat->nama }}, @endforeach"
                                                                        data-qty="{{ $qty }}"
                                                                        data-item="{{ json_encode($obat) }}">
                                                                        {{ $obat->nama }} (Stock:
                                                                        {{ $qty }})</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <br>
                                                    {{-- create a checkbox with label "Zat Aktif" --}}
                                                    <div class="row">
                                                        <div class="col-xl">
                                                            <span title="Batal" id="add-to-racikan"
                                                                class="text-info pointer"></span>
                                                            <input type="checkbox" name="zat_aktif" id="zat_aktif">
                                                            <label for="zat_aktif"> Zat Aktif </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="resep_manual">
                                                        Resep Manual
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <textarea name="resep_manual" readonly id="resep-manual" cols="10" rows="3" class="form-control">{{$resep->re_id != null ? $resep->re->resep_manual : ''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        {{--  --}}
                                    </div>
                                </div>

                                <hr>

                                <div class="row justify-content-center">
                                    <table class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Unit</th>
                                                <th>Restriksi</th>
                                                <th>Batch</th>
                                                <th>ED</th>
                                                <th>Qty</th>
                                                <th>Signa</th>
                                                <th>Instruksi</th>
                                                <th>Jam Pemberian</th>
                                                <th>Harga</th>
                                                <th>Embalase</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems">

                                            @php
                                                $count = 0;
                                            @endphp
                                            
                                            @foreach($resep->items as $item)
                                                @if($item->tipe == "obat" && $item->racikan_id == null)
                                                    <tr id="item{{ ++$count }}" class="obat singleton">
                                                        <input type="hidden" name="signa[{{ $count }}]" value="{{ $item->signa }}">
                                                        <input type="hidden" name="jam_pemberian[{{ $count }}]" value="{{ $item->jam_pemberian }}">
                                                        <input type="hidden" name="hna[{{ $count }}]" value="{{ $item->harga }}">
                                                        <input type="hidden" name="harga_embalase[{{ $count }}]" value="{{ $item->embalase }}">
                                                        <input type="hidden" name="obat_id[{{ $count }}]" value="{{ $item->stored->pbi->barang_id }}">
                                                        <input type="hidden" name="subtotal[{{ $count }}]" value="{{ $item->subtotal }}">
                                                        <input type="hidden" name="type[{{ $count }}]" value="obat">
                                                        <input type="hidden" name="si_id[{{ $count }}]" value="{{ $item->si_id }}">
                                                        <input type="hidden" name="item_id[{{ $count }}]" value="{{ $item->id }}">

                                                        <td>{{$item->stored->pbi->kode_barang}}</td>
                                                        <td>{{$item->stored->pbi->nama_barang}}</td>
                                                        <td>{{$item->stored->pbi->unit_barang}}</td>
                                                        <td>
                                                            @if ($item->stored->pbi->item->restriksi != null)
                                                                <a class="mdi mdi-24px pointer mdi-alert text-warning"
                                                                    onclick="ResepClass.restriksiSwal('{{ $item->stored->pbi->nama_barang }}','{{ $item->restriksi }}')" title="Ada restriksi"></a>
                                                            @else
                                                                <a class="mdi mdi-24px pointer mdi-check-circle text-success"
                                                                    onclick="ResepClass.noRestriksiSwal('{{ $item->stored->pbi->nama_barang }}')" title="Tidak ada restriksi"></a>
                                                            @endif
                                                        </td>
                                                        <td class="batch">{{$item->stored->pbi->batch_no}}</td>
                                                        <td class="ed">{{tgl($item->stored->pbi->tanggal_exp)}}</td>
                                                        <td><input type="number" name="qty[{{ $count }}]" min="1" step="1" class="form-control" value="{{ $item->qty }}" max="{{ $item->stored->qty + $item->qty }}"></td>
                                                        <td class="signa">{{$item->signa}}</td>
                                                        <td>
                                                            <select name="instruksi[{{ $count }}]" id="instruksi{{ $count }}" class="select2special">
                                                                <option value="Sesudah Makan" {{ $item->instruksi == "Sesudah Makan" ? 'selected' : '' }}>Sesudah Makan</option>
                                                                <option value="Sebelum Makan" {{ $item->instruksi == "Sebelum Makan" ? 'selected' : '' }}>Sebelum Makan</option>
                                                                <option value="Saat Makan" {{ $item->instruksi == "Saat Makan" ? 'selected' : '' }}>Saat Makan</option>

                                                                @php
                                                                    $defaults = ["Sesudah Makan", "Sebelum Makan", "Saat Makan"];
                                                                    // if $item->instruksi is not within the defaults
                                                                    // create a new option
                                                                    $isDefault = in_array($item->instruksi, $defaults);
                                                                @endphp
                                                                @if(!$isDefault)
                                                                    <option value="{{ $item->instruksi }}" selected>{{ $item->instruksi }}</option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td class="jam-pemberian">
                                                            @php
                                                                $Hours = json_decode($item->jam_pemberian);
                                                            @endphp
                                                            @if (!empty($Hours))
                                                                {{ implode(
                                                                    ', ',
                                                                    array_map(function ($hour) {
                                                                        return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                                                                    }, $Hours),
                                                                ) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{rp($item->harga)}}</td>
                                                        <td class="embalase">{{rp($item->embalase)}}</td>
                                                        <td class="subtotal">{{rp($item->subtotal)}}</td>
                                                        <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                            title="Hapus" onclick="ResepClass.deleteItem({{ $count }})"></a>
                                                            <a class="mdi mdi-clock-time-eight pointer mdi-24px text-secondary jam-pemberian-btn"
                                                                title="Ubah jam pemberian" onclick="ResepClass.jamPemberian({{ $count }}, '{{$item->stored->pbi->nama_barang}}')">
                                                            <a class="mdi mdi-medication pointer mdi-24px text-success signa-btn"
                                                                title="Ubah signa" onclick="ResepClass.signa({{ $count }}, '{{ $item->stored->pbi->nama_barang }}')"></a>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                            @foreach($resep->items as $item)
                                                @if($item->tipe == "racikan")
                                                    <tr id="item{{ ++$count }}" class="racikan">
                                                        <input type="hidden" name="signa[{{ $count }}]" value="{{ $item->signa }}">
                                                        <input type="hidden" name="jam_pemberian[{{ $count }}]" value="{{ $item->jam_pemberian }}">
                                                        <input type="hidden" name="hna[{{ $count }}]" value="0">
                                                        <input type="hidden" name="harga_embalase[{{ $count }}]" value="{{ $item->embalase }}">
                                                        <input type="hidden" name="subtotal[{{ $count }}]" value="{{ $item->embalase }}">
                                                        <input type="hidden" name="qty[{{ $count }}]" value="1">
                                                        <input type="hidden" name="type[{{ $count }}]" value="racikan">
                                                        <input type="hidden" name="nama_racikan[{{ $count }}]" value="{{ $item->nama_racikan }}">
                                                        <input type="hidden" name="item_id[{{ $count }}]" value="{{ $item->id }}">

                                                        <td class="kode_barang">RACIKAN</td>
                                                        <td class="nama_barang"><u>{{ $item->nama_racikan }}</u></td>
                                                        <td><!-- Racikan --></td>
                                                        <td><!-- Racikan --></td>
                                                        <td><!-- Racikan --></td>
                                                        <td><!-- Racikan --></td>
                                                        <td><!-- Racikan --></td>
                                                        <td class="signa">{{$item->signa}}</td>
                                                        <td>
                                                            <select name="instruksi[{{ $count }}]" id="instruksi{{ $count }}" class="select2special">
                                                                <option value="Sesudah Makan" {{ $item->instruksi == "Sesudah Makan" ? 'selected' : '' }}>Sesudah Makan</option>
                                                                <option value="Sebelum Makan" {{ $item->instruksi == "Sebelum Makan" ? 'selected' : '' }}>Sebelum Makan</option>
                                                                <option value="Saat Makan" {{ $item->instruksi == "Saat Makan" ? 'selected' : '' }}>Saat Makan</option>

                                                                @php
                                                                    $defaults = ["Sesudah Makan", "Sebelum Makan", "Saat Makan"];
                                                                    // if $item->instruksi is not within the defaults
                                                                    // create a new option
                                                                    $isDefault = in_array($item->instruksi, $defaults);
                                                                @endphp
                                                                @if(!$isDefault)
                                                                    <option value="{{ $item->instruksi }}" selected>{{ $item->instruksi }}</option>
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td class="jam-pemberian">
                                                            @php
                                                                $Hours = json_decode($item->jam_pemberian);
                                                            @endphp
                                                            @if (!empty($Hours))
                                                                {{ implode(
                                                                    ', ',
                                                                    array_map(function ($hour) {
                                                                        return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                                                                    }, $Hours),
                                                                ) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{rp(0)}}</td>
                                                        <td class="embalase">{{rp($item->embalase)}}</td>
                                                        <td class="subtotal">{{rp($item->embalase)}}</td>
                                                        <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                            title="Hapus" onclick="ResepClass.deleteRacikan({{ $count }})"></a>
                                                            <a class="mdi mdi-clock-time-eight pointer mdi-24px text-secondary jam-pemberian-btn"
                                                            title="Ubah jam pemberian" onclick="ResepClass.jamPemberian({{ $count }}, '{{ $item->nama_racikan }}')"></a>
                                                            <a class="mdi mdi-medication pointer mdi-24px text-success signa-btn"
                                                            title="Ubah signa" onclick="ResepClass.signa({{ $count }}, '{{ $item->nama_racikan }}')"></a>
                                                            <a class="mdi mdi-plus pointer mdi-24px text-primary add-to-racikan-btn"
                                                            title="Tambah Obat" onclick="ResepClass.tambahObatRacikan({{ $count }}, '{{ $item->nama_racikan }}')"></a>
                                                        </td>
                                                    </tr>

                                                    @php
                                                        $racikan_key = $count;
                                                    @endphp

                                                    @foreach ($resep->items as $item2)
                                                        @if ($item2->racikan_id == $item->id)
                                                            <tr id="item{{ ++$count }}" class="obat detail-racikan">
                                                                <input type="hidden" name="signa[{{ $count }}]" value="{{ $item2->signa }}">
                                                                <input type="hidden" name="jam_pemberian[{{ $count }}]" value="{{ $item2->jam_pemberian }}">
                                                                <input type="hidden" name="hna[{{ $count }}]" value="{{ $item2->harga }}">
                                                                <input type="hidden" name="harga_embalase[{{ $count }}]" value="{{ $item2->embalase }}">
                                                                <input type="hidden" name="obat_id[{{ $count }}]" value="{{ $item2->stored->pbi->barang_id }}">
                                                                <input type="hidden" name="subtotal[{{ $count }}]" value="{{ $item2->subtotal }}">
                                                                <input type="hidden" name="type[{{ $count }}]" value="obat">
                                                                <input type="hidden" name="si_id[{{ $count }}]" value="{{ $item2->si_id }}">
                                                                <input type="hidden" name="item_id[{{ $count }}]" value="{{ $item2->id }}">
                                                                <input type="hidden" name="detail_racikan[{{ $count }}]" value="{{ $racikan_key }}">

                                                                <td>{{$item2->stored->pbi->kode_barang}}</td>
                                                                <td>{{$item2->stored->pbi->nama_barang}}</td>
                                                                <td>{{$item2->stored->pbi->unit_barang}}</td>
                                                                <td>
                                                                    @if ($item2->stored->pbi->item->restriksi != null)
                                                                        <a class="mdi mdi-24px pointer mdi-alert text-warning"
                                                                            onclick="ResepClass.restriksiSwal('{{ $item2->stored->pbi->nama_barang }}','{{ $item2->restriksi }}')" title="Ada restriksi"></a>
                                                                    @else
                                                                        <a class="mdi mdi-24px pointer mdi-check-circle text-success"
                                                                            onclick="ResepClass.noRestriksiSwal('{{ $item2->stored->pbi->nama_barang }}')" title="Tidak ada restriksi"></a>
                                                                    @endif
                                                                </td>
                                                                <td class="batch">{{$item2->stored->pbi->batch_no}}</td>
                                                                <td class="ed">{{tgl($item2->stored->pbi->tanggal_exp)}}</td>
                                                                <td><input type="number" name="qty[{{ $count }}]" min="1" step="1" class="form-control" value="{{ $item2->qty }}" max="{{ $item2->stored->qty + $item2->qty }}"></td>
                                                                <td class="signa"></td>
                                                                <td></td>
                                                                <td class="jam-pemberian"></td>
                                                                <td>{{rp($item2->harga)}}</td>
                                                                <td class="embalase">{{rp($item2->embalase)}}</td>
                                                                <td class="subtotal">{{rp($item2->subtotal)}}</td>
                                                                <td><a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                                    title="Hapus" onclick="ResepClass.deleteItem({{ $count }})"></a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="11">
                                                    <button type="button" id="update-task-id-6-btn" disabled
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-check mr-1"></span>
                                                        Update Task Id 6
                                                    </button>

                                                    <button disabled type="button" id="resep-elektronik-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-clipboard-list mr-1"></span>
                                                        Resep Elektronik
                                                    </button>

                                                    <button type="button" id="resep-harian-btn" disabled
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-notes-medical mr-1"></span>
                                                        Resep Harian
                                                    </button>

                                                    <button type="button" id="tambah-racikan-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-plus mr-1"></span>
                                                        Tambah Racikan
                                                    </button>
                                                </td>
                                                <td class="text-right">Total
                                                    <input type="hidden" value="{{ $resep->total }}" name="total">
                                                </td>
                                                <td>
                                                    <span id="total-display">{{rp($resep->total)}}</span>
                                                </td>
                                                <td>{{--  --}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="col-xl-12 mt-5">
                                    <div class="row">
                                        <div class="col-xl">
                                            <a onclick="window.history.back()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-xl text-right">
                                            <button type="submit" disabled id="telaah-resep-btn"
                                                class="btn btn-lg btn-info waves-effect waves-themed">
                                                <span class="fas fa-clipboard-list mr-1"></span>
                                                Telaah Resep
                                            </button>
                                            <button type="submit" id="submit-btn"
                                                class="btn btn-lg btn-primary waves-effect waves-themed">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Datepicker --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    {{-- Datepicker Range --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(".select2").select2();
        $(".select2special").select2({tags:true});
    </script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/api.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/utils.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/uihtmlrenderer.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/uiformhandler.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/uitablehandler.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/uimischandler.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/resepstatehandler.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/resepeventhandler.js') }}?v={{ time() }}">
    </script>
    <script src="{{ asset('js/simrs/farmasi/transaksi-resep/resep/resephandler.js') }}?v={{ time() }}"></script>

@endsection
