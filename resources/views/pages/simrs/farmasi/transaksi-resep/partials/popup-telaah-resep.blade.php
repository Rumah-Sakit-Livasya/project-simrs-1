@extends('inc.layout-no-side')
@section('title', 'Telaah Resep')
@section('extended-css')
    <style>
        .center {
            vertical-align: middle !important;
            text-align: center !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Telaah Resep Dan Obat Yang Sudah Disiapkan</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if (isset($telaah))
                                <form action="{{ route('farmasi.update.telaah', ['id' => $telaah->id]) }}" method="post" id="form">
                                    @method('put')
                                    @csrf
                                @else
                                    <form action="#" method="post" id="form">
                            @endif


                            <div class="row justify-content-center">
                                <table
                                    class="w-100 table-bordered table-bordered table-hover table-striped bg-white border border-gray-200"
                                    style="font-size: 32px">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th class="py-2 px-4 border-b text-left">No</th>
                                            <th class="py-2 px-4 border-b text-left">Aspek Telaah</th>
                                            <th class="py-2 px-4 border-b text-center">Ya</th>
                                            <th class="py-2 px-4 border-b text-center">Tidak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="py-2 px-4 border-b">1</td>
                                            <td class="py-2 px-4 border-b">Kejelasan Tulisan</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="kejelasan_tulisan" required
                                                    {{ isset($telaah) && $telaah?->kejelasan_tulisan == 1 ? 'checked' : '' }}
                                                    value="1" class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="kejelasan_tulisan"
                                                    {{ isset($telaah) && $telaah?->kejelasan_tulisan == 0 ? 'checked' : '' }}
                                                    value="0" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">2</td>
                                            <td class="py-2 px-4 border-b">Benar Pasien</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_pasien" value="1" required
                                                    {{ isset($telaah) && $telaah?->benar_pasien == 1 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_pasien" value="0"
                                                    {{ isset($telaah) && $telaah?->benar_pasien == 0 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">3</td>
                                            <td class="py-2 px-4 border-b">Benar Nama Obat</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_nama_obat" value="1"
                                                    required
                                                    {{ isset($telaah) && $telaah?->benar_nama_obat == 1 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_nama_obat" value="0"
                                                    {{ isset($telaah) && $telaah?->benar_nama_obat == 0 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">4</td>
                                            <td class="py-2 px-4 border-b">Benar Dosis</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_dosis" value="1" required
                                                    {{ isset($telaah) && $telaah?->benar_dosis == 1 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_dosis" value="0"
                                                    {{ isset($telaah) && $telaah?->benar_dosis == 0 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">5</td>
                                            <td class="py-2 px-4 border-b">Benar Waktu dan Frekuensi Pemberian</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_waktu_dan_frekeunsi_pemberian"
                                                    required
                                                    {{ isset($telaah) && $telaah?->benar_waktu_dan_frekeunsi_pemberian == 1 ? 'checked' : '' }}
                                                    value="1" class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_waktu_dan_frekeunsi_pemberian"
                                                    {{ isset($telaah) && $telaah?->benar_waktu_dan_frekeunsi_pemberian == 0 ? 'checked' : '' }}
                                                    value="0" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">6</td>
                                            <td class="py-2 px-4 border-b">Benar Rute dan Cara Pemberian</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_rute_dan_cara_pemberian" required
                                                    {{ isset($telaah) && $telaah?->benar_rute_dan_cara_pemberian == 1 ? 'checked' : '' }}
                                                    value="1" class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="benar_rute_dan_cara_pemberian"
                                                    {{ isset($telaah) && $telaah?->benar_rute_dan_cara_pemberian == 0 ? 'checked' : '' }}
                                                    value="0" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">7</td>
                                            <td class="py-2 px-4 border-b">Ada Alergi dengan Obat yang Diresepkan</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="ada_alergi_dengan_obat_yang_diresepkan"
                                                    {{ isset($telaah) && $telaah?->ada_alergi_dengan_obat_yang_diresepkan == 1 ? 'checked' : '' }}
                                                    value="1" class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required
                                                    name="ada_alergi_dengan_obat_yang_diresepkan"
                                                    {{ isset($telaah) && $telaah?->ada_alergi_dengan_obat_yang_diresepkan == 0 ? 'checked' : '' }}
                                                    value="0" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">8</td>
                                            <td class="py-2 px-4 border-b">Ada Duplikat Obat</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="ada_duplikat_obat" value="1"
                                                    {{ isset($telaah) && $telaah?->ada_duplikat_obat == 1 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="ada_duplikat_obat" value="0"
                                                    {{ isset($telaah) && $telaah?->ada_duplikat_obat == 0 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">9</td>
                                            <td class="py-2 px-4 border-b">Interaksi Obat yang Mungkin Terjadi</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="interaksi_obat_yang_mungkin_terjadi"
                                                    {{ isset($telaah) && $telaah?->interaksi_obat_yang_mungkin_terjadi == 1 ? 'checked' : '' }}
                                                    value="1" class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="interaksi_obat_yang_mungkin_terjadi"
                                                    {{ isset($telaah) && $telaah?->interaksi_obat_yang_mungkin_terjadi == 0 ? 'checked' : '' }}
                                                    value="0" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">10</td>
                                            <td class="py-2 px-4 border-b">Hal Lain yang Mungkin Terjadi</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="hal_lain_yang_mungkin_terjadi"
                                                    {{ isset($telaah) && $telaah?->hal_lain_yang_mungkin_terjadi == 1 ? 'checked' : '' }}
                                                    value="1" class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required name="hal_lain_yang_mungkin_terjadi"
                                                    {{ isset($telaah) && $telaah?->hal_lain_yang_mungkin_terjadi == 0 ? 'checked' : '' }}
                                                    value="0" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">11</td>
                                            <td class="py-2 px-4 border-b">Hal Lain yang Merupakan Masalah dengan Obat
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required
                                                    name="hal_lain_yang_merupakan_masalah_dengan_obat" value="1"
                                                    {{ isset($telaah) && $telaah?->hal_lain_yang_merupakan_masalah_dengan_obat == 1 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <input type="radio" required
                                                    name="hal_lain_yang_merupakan_masalah_dengan_obat" value="0"
                                                    {{ isset($telaah) && $telaah?->hal_lain_yang_merupakan_masalah_dengan_obat == 0 ? 'checked' : '' }}
                                                    class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="row justify-content-center">
                                <h1>List resep transaksi</h1>
                                <table class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th width="3%">No</th>
                                            <th width="30%">Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th width="3%">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($telaah))
                                            @foreach ($telaah->resep->items as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->tipe == 'obat' ? $item->stored->pbi->kode_barang : 'RACIKAN' }}
                                                    </td>
                                                    <td>{{ $item->tipe == 'obat' ? $item->stored->pbi->nama_barang : $item->nama_racikan }}
                                                    </td>
                                                    <td>{{ $item->qty }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item['kode'] }}</td>
                                                    <td>{{ $item['nama'] }}</td>
                                                    <td>{{ $item['qty'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="row justify-content-center">
                                <h1>Persetujuan perubahan resep</h1>
                                <table class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th colspan="2" class="text-center">Perubahan Resep</th>
                                            <th class="center" rowspan="2">Petugas Farmasi</th>
                                            <th class="center" rowspan="2">Disetujui</th>
                                        </tr>
                                        <tr>
                                            <th>Tertulis</th>
                                            <th>Menjadi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_tertulis_1"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_tertulis_1 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_menjadi_1"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_menjadi_1 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_petugas_1"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_petugas_1 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_disetujui_1"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_disetujui_1 : '' }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_tertulis_2"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_tertulis_2 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_menjadi_2"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_menjadi_2 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_petugas_2"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_petugas_2 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_disetujui_2"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_disetujui_2 : '' }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_tertulis_3"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_tertulis_3 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_menjadi_3"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_menjadi_3 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_petugas_3"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_petugas_3 : '' }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="perubahan_resep_disetujui_3"
                                                    value="{{ isset($telaah) ? $telaah?->perubahan_resep_disetujui_3 : '' }}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="justify-content-center text-center">
                                <h1>Alamat pasien & No telepon yang dapat dihubungi</h1>
                                <br>
                                <textarea name="alamat_no_telp_pasien" class="m-auto w-100 d-block form-class" rows="10" placeholder="-">{{ isset($telaah) ? $telaah?->alamat_no_telp_pasien : '' }}</textarea>
                            </div>

                            @if (isset($telaah))
                                <hr>
                                @include('pages.simrs.erm.partials.ttd-many')
                                @include('pages.simrs.erm.partials.ttd', [
                                    'pengkajian' => $telaah,
                                ])
                                <div class="row justify-content-center">
                                    <div class="card mb-4">
                                        <div class="card-header bg-info-100">
                                            <h4 class="card-title text-center">Serah Terima Pasien</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3 justify-content-center">
                                                <div class="col-md-5 text-center">@include('pages.simrs.erm.partials.signature-many', [
                                                    'judul' => 'Farmasi',
                                                    'name_prefix' => 'data_ttd1',
                                                    'pic' => auth()->user()->name,
                                                    'index' => 1,
                                                ])</div>
                                                <div class="col-md-5 text-center">@include('pages.simrs.erm.partials.signature-many', [
                                                    'judul' => 'Pasien',
                                                    'name_prefix' => 'data_ttd2',
                                                    'pic' => '',
                                                    'index' => 2,
                                                ])</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row justify-content-end mt-3">
                                <div class="col-xl-2">
                                    @if (!isset($telaah) || (isset($telaah) && !$telaah->resep->billed))
                                        <button type="submit" class="btn btn-primary waves-effect waves-themed">
                                            <span class="fal fa-save mr-1"></span>
                                            Simpan
                                        </button>
                                    @else
                                        <h1 style="color: red">Data sudah final</h1>
                                        <p>Tidak dapat diubah lagi</p>
                                    @endif

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
    {{-- <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script> --}}

    <script>
        $(document).ready(function() {
            $('#form').on('submit', function(e) {
                if (window.opener) {
                    e.preventDefault();
                    // gather the #form inputs
                    // and convert it to JSON string
                    var data = JSON.stringify($(this).serializeArray());

                    window.opener.postMessage({
                        data: data,
                        type: "telaah_resep"
                    }, "*");
                    window.close();
                }
            });


            // =====================================================================
            // INISIALISASI TANDA TANGAN (DENGAN PENYIMPANAN STATE EKSTERNAL)
            // =====================================================================
            (function() {
                const canvasManyElement = document.getElementById('canvas-many');
                if (!canvasManyElement || window.signaturePadManyInitialized) return;

                const ctxMany = canvasManyElement.getContext('2d', {
                    willReadFrequently: true
                });
                let signatureState = {};
                let currentSession = {
                    painting: false,
                    history: [],
                    hasDrawn: false,
                    index: null
                };

                function startNewSession(index) {
                    currentSession = {
                        painting: false,
                        history: [],
                        hasDrawn: false,
                        index: index
                    };
                    ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                }

                function startPositionMany(e) {
                    e.preventDefault();
                    currentSession.painting = true;
                    drawMany(e);
                }

                function endPositionMany(e) {
                    e.preventDefault();
                    if (!currentSession.painting) return;
                    currentSession.painting = false;
                    ctxMany.beginPath();
                    currentSession.history.push(ctxMany.getImageData(0, 0, canvasManyElement.width,
                        canvasManyElement
                        .height));
                }

                function drawMany(e) {
                    if (!currentSession.painting) return;
                    const rect = canvasManyElement.getBoundingClientRect();
                    const x = (e.clientX || e.touches?.[0]?.clientX) - rect.left;
                    const y = (e.clientY || e.touches?.[0]?.clientY) - rect.top;
                    ctxMany.lineWidth = 3;
                    ctxMany.lineCap = 'round';
                    ctxMany.strokeStyle = '#000';
                    ctxMany.lineTo(x, y);
                    ctxMany.stroke();
                    ctxMany.beginPath();
                    ctxMany.moveTo(x, y);
                    currentSession.hasDrawn = true;
                }

                function undoMany() {
                    if (currentSession.history.length > 0) {
                        currentSession.history.pop();
                        if (currentSession.history.length > 0) {
                            ctxMany.putImageData(currentSession.history[currentSession.history.length - 1], 0,
                                0);
                        } else {
                            ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                            currentSession.hasDrawn = false;
                        }
                    }
                }

                window.openSignaturePadMany = function(index) {
                    startNewSession(index);
                    $('#signatureModalMany').modal('show');
                }

                window.saveSignatureMany = function() {
                    if (!currentSession.hasDrawn) {
                        alert("Silakan buat tanda tangan terlebih dahulu.");
                        return;
                    }
                    const dataURL = canvasManyElement.toDataURL('image/png');
                    const currentIndex = currentSession.index;
                    const preview = document.getElementById(`signature_preview_${currentIndex}`);
                    const input = document.getElementById(`signature_image_${currentIndex}`);

                    signatureState[currentIndex] = dataURL;
                    if (preview) {
                        preview.src = dataURL;
                        preview.style.display = 'block';
                    }
                    if (input) {
                        input.value = dataURL;
                    }

                    console.log(`Signature for index ${currentIndex} saved to state.`);
                    $('#signatureModalMany').modal('hide');
                    const triggerButton = document.getElementById(`ttd_pegawai_${currentIndex}`);
                    if (triggerButton) {
                        triggerButton.focus();
                    }
                }

                window.syncSignatureStateToForm = function() {
                    console.log('Syncing signature state to form inputs...');
                    for (const index in signatureState) {
                        if (signatureState.hasOwnProperty(index)) {
                            const input = document.getElementById(`signature_image_${index}`);
                            if (input && signatureState[index]) {
                                input.value = signatureState[index];
                                console.log(`Input ${index} synced.`);
                            }
                        }
                    }
                }

                $('#signatureModalMany .btn-outline-danger').on('click', function() {
                    ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                    currentSession.history = [];
                    currentSession.hasDrawn = false;
                });
                $('#signatureModalMany .btn-outline-secondary').on('click', undoMany);
                $('#signatureModalMany .btn-success').on('click', window.saveSignatureMany);

                canvasManyElement.addEventListener('mousedown', startPositionMany);
                canvasManyElement.addEventListener('mouseup', endPositionMany);
                canvasManyElement.addEventListener('mousemove', drawMany);
                canvasManyElement.addEventListener('touchstart', startPositionMany, {
                    passive: false
                });
                canvasManyElement.addEventListener('touchend', endPositionMany, {
                    passive: false
                });
                canvasManyElement.addEventListener('touchmove', drawMany, {
                    passive: false
                });

                window.signaturePadManyInitialized = true;
                console.log('Signature Pad Initialized with EXTERNAL state management.');
            })();
        });
    </script>
@endsection
