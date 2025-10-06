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
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $kategoriOptions = $kategoris
                                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                                                    ->toArray();
                                                $kategoriOptions = ['' => 'Pilih Kategori'] + $kategoriOptions;
                                            @endphp
                                            <label for="kategori_id" class="form-label">Kategori Inventory*</label>
                                            <select name="kategori_id" id="kategori_id" required
                                                class="form-control{{ $errors->has('kategori_id') ? ' is-invalid' : '' }}">
                                                @foreach ($kategoriOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->kategori_id) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kategori_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'hna'" type="text" id="hna"
                                                label="Harga Beli (HNA)*" :value="$barang->hna ?? 0" required
                                                onkeyup="formatInputToNumber(this)" class="borderless-input" />
                                            @error('hna')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'kode'" type="text" id="kode"
                                                label="Kode Barang*" :value="$barang->kode" required class="borderless-input" />
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="ppn">
                                                        PPN Beli (%)
                                                    </label>
                                                </div>
                                                <div class="col-xl-2">
                                                    <x-form.input :name="'ppn'" type="text" id="ppn"
                                                        :value="$barang->ppn ?? 0" onkeyup="formatInputToNumber(this)"
                                                        class="borderless-input" />
                                                    @error('ppn')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-xl">
                                                    <x-form.input :name="'ppn_prev'" type="text" id="ppn_prev"
                                                        :value="$barang->ppn_prev ?? 0" disabled class="borderless-input" />
                                                </div>
                                            </div>
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'nama'" type="text" id="nama"
                                                label="Nama Barang*" :value="$barang->nama" required class="borderless-input" />
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'ppn_rajal'" type="text" id="ppn_rajal"
                                                label="PPN Jual Rawat Jalan (%)" :value="$barang->ppn_rajal ?? 0"
                                                onkeyup="formatInputToNumber(this)" class="borderless-input" />
                                            @error('ppn_rajal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $golonganOptions = $golongans
                                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                                                    ->toArray();
                                                $golonganOptions = ['' => 'Pilih Golongan'] + $golonganOptions;
                                            @endphp
                                            <label for="golongan_id" class="form-label">Golongan*</label>
                                            <select name="golongan_id" id="golongan_id" class="form-control">
                                                @foreach ($golonganOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->golongan_id) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'ppn_ranap'" type="text" id="ppn_ranap"
                                                label="PPN Jual Rawat Inap (%)" :value="$barang->ppn_ranap ?? 0"
                                                onkeyup="formatInputToNumber(this)" class="borderless-input" />
                                            @error('ppn_ranap')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.textarea :name="'restriksi'" id="restriksi" label="Restriksi"
                                                :value="$barang->restriksi" />
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $tipeOptions = [
                                                    '' => 'Pilih tipe barang',
                                                    'FN' => 'Formularium Nasional',
                                                    'NFN' => 'Non Formularium Nasional',
                                                ];
                                            @endphp
                                            <label for="tipe" class="form-label">Tipe Barang*</label>
                                            <select name="tipe" id="tipe" required class="form-control">
                                                @foreach ($tipeOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->tipe) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $principalOptions = $pabriks
                                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                                                    ->toArray();
                                                $principalOptions = ['' => 'Pilih Principal'] + $principalOptions;
                                            @endphp
                                            <label for="principal" class="form-label">Principal</label>
                                            <select name="principal" id="principal" class="form-control">
                                                @foreach ($principalOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->principal) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $kelompokOptions = $kelompoks
                                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                                                    ->toArray();
                                                $kelompokOptions = ['' => 'Pilih Kelompok'] + $kelompokOptions;
                                            @endphp
                                            <label for="kelompok_id" class="form-label">Kelompok Barang</label>
                                            <select name="kelompok_id" id="kelompok_id" class="form-control">
                                                @foreach ($kelompokOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->kelompok_id) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $expOptions = [
                                                    '' => 'Pilih info expired',
                                                    '1w' => '1 minggu',
                                                    '2w' => '2 minggu',
                                                    '3w' => '3 minggu',
                                                    '1mo' => '1 bulan',
                                                    '2mo' => '2 bulan',
                                                    '3mo' => '3 bulan',
                                                    '6mo' => '6 bulan',
                                                ];
                                            @endphp
                                            <label for="exp" class="form-label">Info expired</label>
                                            <select name="exp" id="exp" class="form-control">
                                                @foreach ($expOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->exp) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $zatOptions = $zats
                                                    ->mapWithKeys(fn($zat) => [$zat->id => $zat->nama])
                                                    ->toArray();
                                                $zatSelected = array_map(
                                                    'strval',
                                                    $barang->zat_aktif->pluck('zat_id')->toArray(),
                                                );
                                            @endphp
                                            <label for="zat_aktif" class="form-label">Zat Aktif</label>
                                            <select name="zat_aktif[]" id="zat_aktif" multiple
                                                class="form-control select2 w-100">
                                                @foreach ($zatOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if (in_array((string) $optionValue, $zatSelected, true)) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $satuanOptions = $satuans
                                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                                                    ->toArray();
                                                $satuanOptions = ['' => 'Pilih Satuan'] + $satuanOptions;
                                            @endphp
                                            <label for="satuan_id" class="form-label">Satuan Default*</label>
                                            <select name="satuan_id" id="satuan_id" required class="form-control">
                                                @foreach ($satuanOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->satuan_id) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $aktifOptions = [1 => 'Aktif', 0 => 'Non Aktif'];
                                            @endphp
                                            <label for="aktif" class="form-label">Aktif?</label>
                                            <select name="aktif" id="aktif" class="form-control">
                                                @foreach ($aktifOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->aktif) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <label class="form-label text-end" for="satuan-tambahan-select">
                                                Satuan Tambahan
                                            </label>
                                            @php
                                                $satuanTambahanOptions = $satuans
                                                    ->mapWithKeys(fn($item) => [$item->id => $item->nama])
                                                    ->toArray();
                                                $satuanTambahanOptions =
                                                    ['' => 'Pilih Satuan Tambahan'] + $satuanTambahanOptions;
                                            @endphp
                                            <select id="satuan-tambahan-select" class="form-control">
                                                @foreach ($satuanTambahanOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
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
                                                    <script>
                                                        const tempIds = [];
                                                    </script>
                                                    @foreach ($barang->satuan_tambahan as $satuan)
                                                        <script>
                                                            tempIds.push({{ $satuan->satuan->id }})
                                                        </script>
                                                        <tr id="satuan{{ $loop->iteration }}"
                                                            data-index={{ $loop->iteration - 1 }}>
                                                            <td>{{ $satuan->satuan->nama }}</td>
                                                            <td>
                                                                <input type="hidden"
                                                                    name="satuans_id[{{ $loop->iteration }}]"
                                                                    value="{{ $satuan->satuan->id }}">
                                                                <input type="number"
                                                                    name="satuans_jumlah[{{ $loop->iteration }}]"
                                                                    value="{{ $satuan->isi }}" class="form-control"
                                                                    min="1">
                                                            </td>
                                                            <td>
                                                                <input type="checkbox"
                                                                    name="satuans_status[{{ $loop->iteration }}]"
                                                                    value="1" title="Aktif?" checked>
                                                            </td>
                                                            <td>
                                                                <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                                    title="Hapus"
                                                                    onclick="PopupBarangFarmasiClass.deleteSatuanTambahan({{ $loop->iteration }})"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.textarea :name="'keterangan'" id="keterangan" label="Keterangan"
                                                :value="$barang->keterangan" />
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'harga_principal'" type="text" id="harga_principal"
                                                label="Harga Principal" :value="$barang->harga_principal ?? 0"
                                                onkeyup="formatInputToNumber(this)" class="borderless-input" />
                                            @error('harga_principal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            <x-form.input :name="'diskon_principal'" type="text" id="diskon_principal"
                                                label="Diskon Principal (%)" :value="$barang->diskon_principal ?? 0"
                                                onkeyup="formatInputToNumber(this)" class="borderless-input" />
                                            @error('diskon_principal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $jenisObatOptions = [
                                                    '' => 'Pilih Jenis Obat',
                                                    'generik' => 'Generik',
                                                    'paten' => 'Paten',
                                                ];
                                            @endphp
                                            <label for="jenis_obat" class="form-label">Jenis Obat</label>
                                            <select name="jenis_obat" id="jenis_obat" class="form-control">
                                                @foreach ($jenisObatOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->jenis_obat) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group>
                                            @php
                                                $formulariumOptions = [
                                                    '' => 'Pilih Formularium',
                                                    'RS' => 'Formularium Rumah Sakit',
                                                    'NRS' => 'Formularium Non Rumah Sakit',
                                                ];
                                            @endphp
                                            <label for="formularium" class="form-label">Formularium</label>
                                            <select name="formularium" id="formularium" class="form-control">
                                                @foreach ($formulariumOptions as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}"
                                                        @if ((string) $optionValue === (string) $barang->formularium) selected @endif>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </x-form.group>
                                    </div>
                                </x-form.group>

                                <br>

                                <x-form.group class="row justify-content-center">
                                    <div class="col-xl-12">
                                        <x-form.group>
                                            <x-form.input :name="'alasan_edit'" type="text" id="alasan_edit"
                                                label="Alasan Edit*" required />
                                        </x-form.group>
                                    </div>
                                </x-form.group>

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
