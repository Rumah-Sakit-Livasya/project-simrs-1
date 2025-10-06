@extends('inc.layout-no-side')

@section('title', 'Tambah Barang Non Farmasi')

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
            border-radius: 0;
        }

        .qty {
            width: 60px;
            margin-left: 10px;
        }

        .form-label {
            font-weight: 500;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #495057;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tambah Barang Non Farmasi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.master-data.barang-non-farmasi.store') }}" method="post"
                                autocomplete="off">
                                @csrf
                                @method('post')
                                <div class="row">
                                    <div class="col-xl-6">
                                        <x-form.group label="Kategori Inventory" for="kategori_id" required
                                            :error="$errors->first('kategori_id')">
                                            <x-form.select name="kategori_id" id="kategori_id" :options="$kategoris->pluck('nama', 'id')->toArray()"
                                                placeholder="Pilih Kategori" required :error="$errors->first('kategori_id')" />
                                        </x-form.group>
                                        <x-form.group label="Kode Barang" for="kode" required :error="$errors->first('kode')">
                                            <x-form.input name="kode" id="kode" :value="old('kode')"
                                                class="borderless-input" required :error="$errors->first('kode')" />
                                        </x-form.group>
                                        <x-form.group label="Nama Barang" for="nama" required :error="$errors->first('nama')">
                                            <x-form.input name="nama" id="nama" :value="old('nama')"
                                                class="borderless-input" required :error="$errors->first('nama')" />
                                        </x-form.group>
                                        <x-form.group label="Satuan Default" for="satuan_id" required :error="$errors->first('satuan_id')">
                                            <x-form.select name="satuan_id" id="satuan_id" :options="$satuans->pluck('nama', 'id')->toArray()"
                                                placeholder="Pilih Satuan" required :error="$errors->first('satuan_id')" />
                                        </x-form.group>
                                        <x-form.group label="Satuan Tambahan" for="satuan_tambahan" :error="$errors->first('satuan_tambahan')">
                                            <x-form.select name="satuan_tambahan" id="satuan-tambahan-select"
                                                :options="$satuans->pluck('nama', 'id')->toArray()" placeholder="Pilih Satuan Tambahan" :error="$errors->first('satuan_tambahan')" />
                                            <table class="table table-bordered table-hover table-striped w-100 mt-2">
                                                <thead class="bg-primary-600">
                                                    <tr>
                                                        <th>Satuan</th>
                                                        <th>Isi</th>
                                                        <th>Aktif?</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-satuan"></tbody>
                                            </table>
                                        </x-form.group>
                                        <x-form.group label="Bisa dijual ke pasien?" for="jual_pasien" :error="$errors->first('jual_pasien')">
                                            <x-form.select name="jual_pasien" id="jual_pasien" :options="['1' => 'Bisa', '0' => 'Tidak']"
                                                :selected="old('jual_pasien', '0')" :error="$errors->first('jual_pasien')" />
                                        </x-form.group>
                                    </div>
                                    <div class="col-xl-6">
                                        <x-form.group label="Harga Beli (HNA)" for="hna" required :error="$errors->first('hna')">
                                            <x-form.input name="hna" id="hna" :value="old('hna', 0)"
                                                class="borderless-input" required onkeyup="formatInputToNumber(this)"
                                                :error="$errors->first('hna')" />
                                        </x-form.group>
                                        <x-form.group label="PPN Beli (%)" for="ppn" :error="$errors->first('ppn')">
                                            <div class="row">
                                                <div class="col-4">
                                                    <x-form.input name="ppn" id="ppn" :value="old('ppn', 0)"
                                                        class="borderless-input" onkeyup="formatInputToNumber(this)"
                                                        :error="$errors->first('ppn')" />
                                                </div>
                                                <div class="col-8">
                                                    <x-form.input name="ppn_prev" id="ppn_prev" :value="0"
                                                        class="borderless-input" disabled />
                                                </div>
                                            </div>
                                        </x-form.group>
                                        <x-form.group label="Kelompok Barang" for="kelompok_id" :error="$errors->first('kelompok_id')">
                                            <x-form.select name="kelompok_id" id="kelompok_id" :options="$kelompoks->pluck('nama', 'id')->toArray()"
                                                placeholder="Pilih Kelompok" :error="$errors->first('kelompok_id')" />
                                        </x-form.group>
                                        <x-form.group label="Golongan" for="golongan_id" :error="$errors->first('golongan_id')">
                                            <x-form.select name="golongan_id" id="golongan_id" :options="$golongans->pluck('nama', 'id')->toArray()"
                                                placeholder="Pilih Golongan" :error="$errors->first('golongan_id')" />
                                        </x-form.group>
                                        <x-form.group label="Aktif?" for="aktif" :error="$errors->first('aktif')">
                                            <x-form.select name="aktif" id="aktif" :options="['1' => 'Aktif', '0' => 'Non Aktif']"
                                                :selected="old('aktif', '1')" :error="$errors->first('aktif')" />
                                        </x-form.group>
                                        <x-form.group label="Keterangan" for="keterangan" :error="$errors->first('keterangan')">
                                            <x-form.textarea name="keterangan" id="keterangan" rows="2"
                                                :error="$errors->first('keterangan')">{{ old('keterangan') }}</x-form.textarea>
                                        </x-form.group>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-6">
                                        <a onclick="window.close()"
                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                            <span class="text-primary">Kembali</span>
                                        </a>
                                    </div>
                                    <div class="col-xl-6 text-right">
                                        <button type="submit" id="order-submit"
                                            class="btn btn-lg btn-primary waves-effect waves-themed">
                                            <span class="fal fa-save mr-1"></span>
                                            Simpan
                                        </button>
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
        function formatInputToNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        window._satuans = @json($satuans);

        $(document).ready(function() {
            $("select").select2({
                width: '100%',
                theme: 'bootstrap4',
                placeholder: function() {
                    $(this).data('placeholder');
                }
            });
        });
    </script>
    <script src="{{ asset('js/simrs/warehouse/master-data/popup-barang-non-farmasi.js') }}?v={{ time() }}"></script>
@endsection
