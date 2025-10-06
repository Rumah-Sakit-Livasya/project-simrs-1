@extends('inc.layout-no-side')
@section('title', 'Tambah barang farmasi')
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
                            Tambah barang farmasi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.master-data.barang-farmasi.store') }}" method="post">
                                @csrf
                                @method('post')
                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Kategori Inventory*', 'for' => 'kategori_id'])
                                            @select([
                                                'name' => 'kategori_id',
                                                'id' => 'kategori_id',
                                                'required' => true,
                                                'options' => $kategoris->mapWithKeys(fn($item) => [$item->id => $item->nama])->prepend('Pilih Kategori', ''),
                                                'selected' => old('kategori_id'),
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Harga Beli (HNA)*', 'for' => 'hna'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'hna',
                                                'id' => 'hna',
                                                'value' => old('hna', 0),
                                                'class' => 'form-control borderless-input',
                                                'required' => true,
                                                'onkeyup' => 'formatInputToNumber(this)',
                                            ])
                                            @error('hna')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Kode Barang*', 'for' => 'kode'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'kode',
                                                'id' => 'kode',
                                                'value' => old('kode'),
                                                'class' => 'form-control borderless-input',
                                                'required' => true,
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'PPN Beli (%)', 'for' => 'ppn'])
                                            <div class="row">
                                                <div class="col-xl-2">
                                                    @input([
                                                        'type' => 'text',
                                                        'name' => 'ppn',
                                                        'id' => 'ppn',
                                                        'value' => old('ppn', 0),
                                                        'class' => 'form-control borderless-input',
                                                        'onkeyup' => 'formatInputToNumber(this)',
                                                    ])
                                                    @error('ppn')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-xl">
                                                    @input([
                                                        'type' => 'text',
                                                        'id' => 'ppn_prev',
                                                        'value' => 0,
                                                        'class' => 'form-control borderless-input',
                                                        'disabled' => true,
                                                    ])
                                                </div>
                                            </div>
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Nama Barang*', 'for' => 'nama'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'nama',
                                                'id' => 'nama',
                                                'value' => old('nama'),
                                                'class' => 'form-control borderless-input',
                                                'required' => true,
                                            ])
                                            @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'PPN Jual Rawat Jalan (%)', 'for' => 'ppn_rajal'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'ppn_rajal',
                                                'id' => 'ppn_rajal',
                                                'value' => old('ppn_rajal', 0),
                                                'class' => 'form-control borderless-input',
                                                'onkeyup' => 'formatInputToNumber(this)',
                                            ])
                                            @error('ppn_rajal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Golongan*', 'for' => 'golongan_id'])
                                            @select([
                                                'name' => 'golongan_id',
                                                'id' => 'golongan_id',
                                                'options' => $golongans->mapWithKeys(fn($item) => [$item->id => $item->nama])->prepend('Pilih Golongan', ''),
                                                'selected' => old('golongan_id'),
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'PPN Jual Rawat Inap (%)', 'for' => 'ppn_ranap'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'ppn_ranap',
                                                'id' => 'ppn_ranap',
                                                'value' => old('ppn_ranap', 0),
                                                'class' => 'form-control borderless-input',
                                                'onkeyup' => 'formatInputToNumber(this)',
                                            ])
                                            @error('ppn_ranap')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Restriksi', 'for' => 'restriksi'])
                                            @textarea([
                                                'name' => 'restriksi',
                                                'id' => 'restriksi',
                                                'class' => 'form-control',
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Tipe Barang*', 'for' => 'tipe'])
                                            @select([
                                                'name' => 'tipe',
                                                'id' => 'tipe',
                                                'required' => true,
                                                'options' => [
                                                    '' => 'Pilih tipe barang',
                                                    'FN' => 'Formularium Nasional',
                                                    'NFN' => 'Non Formularium Nasional',
                                                ],
                                                'selected' => old('tipe'),
                                            ])
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Principal', 'for' => 'principal'])
                                            @select([
                                                'name' => 'principal',
                                                'id' => 'principal',
                                                'options' => $pabriks->mapWithKeys(fn($item) => [$item->id => $item->nama])->prepend('Pilih Principal', ''),
                                                'selected' => old('principal'),
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Kelompok Barang', 'for' => 'kelompok_id'])
                                            @select([
                                                'name' => 'kelompok_id',
                                                'id' => 'kelompok_id',
                                                'options' => $kelompoks->mapWithKeys(fn($item) => [$item->id => $item->nama])->prepend('Pilih Kelompok', ''),
                                                'selected' => old('kelompok_id'),
                                            ])
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Info expired', 'for' => 'exp'])
                                            @select([
                                                'name' => 'exp',
                                                'id' => 'exp',
                                                'options' => [
                                                    '' => 'Pilih info expired',
                                                    '1w' => '1 minggu',
                                                    '2w' => '2 minggu',
                                                    '3w' => '3 minggu',
                                                    '1mo' => '1 bulan',
                                                    '2mo' => '2 bulan',
                                                    '3mo' => '3 bulan',
                                                    '6mo' => '6 bulan',
                                                ],
                                                'selected' => old('exp'),
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Zat Aktif', 'for' => 'zat_aktif'])
                                            @select([
                                                'name' => 'zat_aktif[]',
                                                'id' => 'zat_aktif',
                                                'class' => 'form-control select2 w-100',
                                                'multiple' => true,
                                                'options' => $zats->mapWithKeys(fn($zat) => [$zat->id => $zat->nama]),
                                                'selected' => old('zat_aktif', []),
                                            ])
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Satuan Default*', 'for' => 'satuan_id'])
                                            @select([
                                                'name' => 'satuan_id',
                                                'id' => 'satuan_id',
                                                'required' => true,
                                                'options' => $satuans->mapWithKeys(fn($item) => [$item->id => $item->nama])->prepend('Pilih Satuan', ''),
                                                'selected' => old('satuan_id'),
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Aktif?', 'for' => 'aktif'])
                                            @select([
                                                'name' => 'aktif',
                                                'id' => 'aktif',
                                                'options' => [
                                                    '1' => 'Aktif',
                                                    '0' => 'Non Aktif',
                                                ],
                                                'selected' => old('aktif', 1),
                                            ])
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Satuan Tambahan', 'for' => 'satuan_id'])
                                            @select([
                                                'id' => 'satuan-tambahan-select',
                                                'options' => $satuans->mapWithKeys(fn($item) => [$item->id => $item->nama])->prepend('Pilih Satuan Tambahan', ''),
                                            ])
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

                                                </tbody>
                                            </table>
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Keterangan', 'for' => 'keterangan'])
                                            @textarea([
                                                'name' => 'keterangan',
                                                'id' => 'keterangan',
                                                'class' => 'form-control',
                                                'value' => old('keterangan'),
                                            ])
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Harga Principal', 'for' => 'harga_principal'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'harga_principal',
                                                'id' => 'harga_principal',
                                                'value' => old('harga_principal', 0),
                                                'class' => 'form-control borderless-input',
                                                'onkeyup' => 'formatInputToNumber(this)',
                                            ])
                                            @error('harga_principal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Diskon Principal (%)', 'for' => 'diskon_principal'])
                                            @input([
                                                'type' => 'text',
                                                'name' => 'diskon_principal',
                                                'id' => 'diskon_principal',
                                                'value' => old('diskon_principal', 0),
                                                'class' => 'form-control borderless-input',
                                                'onkeyup' => 'formatInputToNumber(this)',
                                            ])
                                            @error('diskon_principal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endgroup
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-6">
                                        @group(['label' => 'Jenis Obat', 'for' => 'jenis_obat'])
                                            @select([
                                                'name' => 'jenis_obat',
                                                'id' => 'jenis_obat',
                                                'options' => [
                                                    '' => 'Pilih Jenis Obat',
                                                    'generik' => 'Generik',
                                                    'paten' => 'Paten',
                                                ],
                                                'selected' => old('jenis_obat'),
                                            ])
                                        @endgroup
                                    </div>
                                    <div class="col-xl-6">
                                        @group(['label' => 'Formularium', 'for' => 'formularium'])
                                            @select([
                                                'name' => 'formularium',
                                                'id' => 'formularium',
                                                'options' => [
                                                    '' => 'Pilih Formularium',
                                                    'RS' => 'Formularium Rumah Sakit',
                                                    'NRS' => 'Formularium Non Rumah Sakit',
                                                ],
                                                'selected' => old('formularium'),
                                            ])
                                        @endgroup
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
@endsection
