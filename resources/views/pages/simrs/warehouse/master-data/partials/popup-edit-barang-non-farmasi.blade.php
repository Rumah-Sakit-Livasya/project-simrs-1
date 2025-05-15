@extends('inc.layout-no-side')
@section('title', 'Edit barang non farmasi')
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
                            Edit barang non farmasi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.master-data.barang-non-farmasi.update', ['id' => $barang->id]) }}" method="post">
                                @csrf
                                @method('put')
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
                                                        <option value="" selected disabled hidden>Pilih Kategori
                                                        </option>
                                                        @foreach ($kategoris as $item)
                                                            <option value="{{ $item->id }}" {{ $barang->kategori_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
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
                                                    <input type="text" value="{{ $barang->hna }}"
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
                                                    <input type="text" value="{{ $barang->ppn }}"
                                                        style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="ppn" name="ppn"
                                                        onkeyup="formatInputToNumber(this)">
                                                    @error('ppn')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" value="0"
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
                                                    <label class="form-label text-end" for="kelompok_id">
                                                        Kelompok Barang
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="kelompok_id" id="kelompok_id" class="form-control">
                                                        <option value="" selected disabled hidden>Pilih Kelompok
                                                        </option>
                                                        @foreach ($kelompoks as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama }}
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
                                                        Satuan Default
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="satuan_id" id="satuan_id" class="form-control"
                                                        required>
                                                        <option value="" selected disabled hidden>Pilih Satuan
                                                        </option>
                                                        @foreach ($satuans as $item)
                                                            <option value="{{ $item->id }}" {{ $barang->satuan_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}
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
                                                    <label class="form-label text-end" for="golongan_id">
                                                        Golongan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="golongan_id" id="golongan_id" class="form-control">
                                                        <option value="" selected disabled hidden>Pilih Golongan
                                                        </option>
                                                        @foreach ($golongans as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == $barang->golongan_id ? 'selected' : '' }}>{{ $item->nama }}
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
                                                        Satuan Tambahan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select id="satuan-tambahan-select" class="form-control">
                                                        <option value="" selected disabled hidden>Pilih Satuan
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
                                                                            title="Hapus" onclick="PopupBarangNonFarmasiClass.deleteSatuanTambahan({{ $loop->iteration }})"></a>
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
                                                    <label class="form-label text-end" for="aktif">
                                                        Aktif?
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="aktif" id="aktif" class="form-control">
                                                        <option value="1" {{ $barang->aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0" {{ $barang->aktif == 0 ? 'selected' : '' }}>Non Aktif</option>
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
                                                    <label class="form-label text-end" for="jual_pasien">
                                                        Bisa dijual ke pasien?
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="jual_pasien" id="jual_pasien" class="form-control">
                                                        <option value="1" {{ $barang->jual_pasien == 1 ? 'selected' : '' }}>Bisa</option>
                                                        <option value="0" {{ $barang->jual_pasien == 0 ? 'selected' : '' }}>Tidak</option>
                                                    </select>
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
    <script src="{{ asset('js/simrs/warehouse/master-data/popup-barang-non-farmasi.js') }}?v={{ time() }}">
    </script>
    <script>
        PopupBarangNonFarmasiClass.SelectedSatuanId = {{ $barang->satuan_id }};
        PopupBarangNonFarmasiClass.HNA = {{ $barang->hna }};
        PopupBarangNonFarmasiClass.PPN = {{ $barang->ppn }};
        PopupBarangNonFarmasiClass.calculatePPNPrev();
       PopupBarangNonFarmasiClass.SelectedSatuanTambahanIds = [...tempIds];
        PopupBarangNonFarmasiClass.refreshSatuanTambahanSelect();
    </script>
@endsection
