@extends('inc.layout-no-side')
@section('title', 'Edit barang farmasi')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        .modal-dialog {
            max-width: 70%;
        }

        .borderless-input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0
        }

        .qty {
            width: 60px;
            margin-left: 10px;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Edit barang farmasi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.master-data.barang-farmasi.update', ['id' => $barang->id]) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $barang->id }}">
                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="kategori_id">
                                                        Kategori Inventory*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="kategori_id" id="kategori_id" class="form-control"
                                                        required>
                                                        <option value="" disabled hidden>Pilih Kategori
                                                        </option>
                                                        @foreach ($kategoris as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $barang->kategori_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="hna">
                                                        Harga Beli (HNA)*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->hna ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="hna" name="hna"
                                                        onkeyup="formatInputToNumber(this)" required>
                                                    @error('hna')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="kode">
                                                        Kode Barang*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->kode }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="kode" name="kode" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="ppn">
                                                        PPN Beli (%)
                                                    </label>
                                                </div>
                                                <div class="col-xl-2">
                                                    <input type="text" value="{{ $barang->ppn ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="ppn" name="ppn"
                                                        onkeyup="formatInputToNumber(this)">
                                                    @error('ppn')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->ppn_prev ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="ppn_prev" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="nama">
                                                        Nama Barang*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->nama }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="nama" name="nama" required>
                                                    @error('nama')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="ppn_rajal">
                                                        PPN Jual Rawat Jalan (%)
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->ppn_rajal ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="ppn_rajal" name="ppn_rajal"
                                                        onkeyup="formatInputToNumber(this)">
                                                    @error('ppn_rajal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="golongan_id">
                                                        Golongan*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="golongan_id" id="golongan_id" class="form-control">
                                                        <option value="" disabled hidden>Pilih Golongan
                                                        </option>
                                                        @foreach ($golongans as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $barang->golongan_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama }}
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="ppn_ranap">
                                                        PPN Jual Rawat Inap (%)
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->ppn_ranap ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="ppn_ranap" name="ppn_ranap"
                                                        onkeyup="formatInputToNumber(this)">
                                                    @error('ppn_ranap')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        {{-- /// --}}
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe">
                                                        Tipe Barang*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="tipe" id="tipe" class="form-control" required>
                                                        <option value="" disabled hidden>Pilih tipe barang
                                                        </option>
                                                        <option value="FN"
                                                            {{ $barang->tipe == 'FN' ? 'selected' : '' }}>Formularium
                                                            Nasional</option>
                                                        <option value="NFN"
                                                            {{ $barang->tipe == 'NFN' ? 'selected' : '' }}>Non Formularium
                                                            Nasional</option>
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="principal">
                                                        Principal
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->principal }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="principal" name="principal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="kelompok_id">
                                                        Kelompok Barang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="kelompok_id" id="kelompok_id" class="form-control">
                                                        <option value="" disabled hidden>Pilih Kelompok
                                                        </option>
                                                        @foreach ($kelompoks as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $barang->kelompok_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama }}
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="exp">
                                                        Info expired
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="exp" id="exp" class="form-control">
                                                        <option value="" selected hidden disabled>Pilih info expired
                                                        </option>
                                                        <option value="1w"
                                                            {{ $barang->exp == '1w' ? 'selected' : '' }}>1 minggu</option>
                                                        <option value="2w"
                                                            {{ $barang->exp == '2w' ? 'selected' : '' }}>2 minggu</option>
                                                        <option value="3w"
                                                            {{ $barang->exp == '3w' ? 'selected' : '' }}>3 minggu</option>
                                                        <option value="1mo"
                                                            {{ $barang->exp == '1mo' ? 'selected' : '' }}>1 bulan</option>
                                                        <option value="2mo"
                                                            {{ $barang->exp == '2mo' ? 'selected' : '' }}>2 bulan</option>
                                                        <option value="3mo"
                                                            {{ $barang->exp == '3mo' ? 'selected' : '' }}>3 bulan</option>
                                                        <option value="6mo"
                                                            {{ $barang->exp == '6mo' ? 'selected' : '' }}>6 bulan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="zat_aktif">
                                                        Zat Aktif
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control select2 w-100" id="zat_aktif"
                                                        name="zat_aktif[]" multiple="multiple">
                                                        @foreach ($zats as $zat)
                                                            <option value="{{ $zat->id }}"
                                                                {{ $barang->zat_aktif->contains('zat_id', $zat->id) ? 'selected' : '' }}>
                                                                {{ $zat->nama }}
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="satuan_id">
                                                        Satuan Default*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="satuan_id" id="satuan_id" class="form-control"
                                                        required>
                                                        <option value="" disabled hidden>Pilih Satuan
                                                        </option>
                                                        @foreach ($satuans as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $barang->satuan_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama }}
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="aktif">
                                                        Aktif?
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="aktif" id="aktif" class="form-control">
                                                        <option value="1"
                                                            {{ $barang->aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0"
                                                            {{ $barang->aktif == 0 ? 'selected' : '' }}>Non Aktif</option>
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="satuan_id">
                                                        Satuan Tambahan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select id="satuan-tambahan-select" class="form-control">
                                                        <option value="" disabled hidden>Pilih Satuan
                                                            Tambahan
                                                        </option>
                                                        @foreach ($satuans as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <table class="table table-bordered table-hover table-striped w-100">
                                                        <thead class="bg-primary-600">
                                                            <tr>
                                                                <th>Satuan</th>
                                                                <th>Isi</th>
                                                                <th>Aktif?</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="table-satuan">
                                                            <script> const tempIds = []; </script>
                                                            @foreach ($barang->satuan_tambahan as $satuan)
                                                                <script> tempIds.push({{ $satuan->satuan->id }}) </script>
                                                                <tr id="satuan{{ $loop->iteration }}" data-index={{ $loop->iteration - 1 }}>
                                                                    <td>{{ $satuan->satuan->nama }}</td>
                                                                    <td>
                                                                        <input type="hidden" name="satuans_id[{{ $loop->iteration }}]" value="{{ $satuan->satuan->id }}">
                                                                        <input type="number" name="satuans_jumlah[{{ $loop->iteration }}]" value="{{ $satuan->isi }}" class="form-control" min="1">
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" name="satuans_status[{{ $loop->iteration }}]" value="1" title="Aktif?" checked>
                                                                    </td>
                                                                    <td>
                                                                        <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                                            title="Hapus" onclick="PopupBarangFarmasiClass.deleteSatuanTambahan({{ $loop->iteration }})"></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan">
                                                        Keterangan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <textarea name="keterangan" class="form-control" id="keterangan">{{ $barang->keterangan }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="harga_principal">
                                                        Harga Principal
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->harga_principal ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="harga_principal" name="harga_principal"
                                                        onkeyup="formatInputToNumber(this)">
                                                    @error('harga_principal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="diskon_principal">
                                                        Diskon Principal (%)
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="{{ $barang->diskon_principal ?? 0 }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="diskon_principal"
                                                        name="diskon_principal" onkeyup="formatInputToNumber(this)">
                                                    @error('diskon_principal')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="jenis_obat">
                                                        Jenis Obat
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="jenis_obat" id="jenis_obat" class="form-control">
                                                        <option value="" disabled hidden>Pilih Jenis Obat
                                                        </option>
                                                        <option value="generik"
                                                            {{ $barang->jenis_obat == 'generik' ? 'selected' : '' }}>
                                                            Generik</option>
                                                        <option value="paten"
                                                            {{ $barang->jenis_obat == 'paten' ? 'selected' : '' }}>Paten
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="formularium">
                                                        Formularium
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="formularium" id="formularium" class="form-control">
                                                        <option value="" disabled hidden>Pilih Formularium
                                                        </option>
                                                        <option value="RS"
                                                            {{ $barang->formularium == 'RS' ? 'selected' : '' }}>
                                                            Formularium Rumah Sakit</option>
                                                        <option value="NRS"
                                                            {{ $barang->formularium == 'NRS' ? 'selected' : '' }}>
                                                            Formularium Non Rumah Sakit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12 mt-5">
                                    <div class="row">
                                        <div class="col-xl">
                                            <a onclick="window.close()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-xl text-right">
                                            <button type="submit" id="order-submit"
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        // format input to number only function
        // on keyup
        function formatInputToNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        window._satuans = @json($satuans);
        $(document).ready(function() {
            $("select").select2();
        });
    </script>
    <script src="{{ asset('js/simrs/warehouse/master-data/popup-barang-farmasi.js') }}?v={{ time() }}"></script>
    <script>
        PopupBarangFarmasiClass.SelectedSatuanId = {{ $barang->satuan_id }};
        PopupBarangFarmasiClass.HNA = {{ $barang->hna }};
        PopupBarangFarmasiClass.PPN = {{ $barang->ppn }};
        PopupBarangFarmasiClass.calculatePPNPrev();
        PopupBarangFarmasiClass.SelectedSatuanTambahanIds = [...tempIds];
        PopupBarangFarmasiClass.refreshSatuanTambahanSelect();
    </script>
@endsection
