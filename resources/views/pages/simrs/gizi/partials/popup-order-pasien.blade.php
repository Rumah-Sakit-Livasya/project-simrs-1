@extends('inc.layout-no-side')
@section('title', 'Order gizi untuk pasien')
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
                            Pesan makanan untuk pasien
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-order-gizi" name="form-order-gizi" action="/api/simrs/gizi/order/store"
                                method="get">
                                @csrf
                                @method('get')
                                <input type="hidden" name="_method" value="get">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="untuk" value="pasien">
                                <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                                <table style="width: 100%">
                                    <tr>
                                        <td>Nama Pasien</td>
                                        <td>
                                            <input type="text" value="{{ $registration->patient->name }}"
                                                class="form-control" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>RM / No. Reg</td>
                                        <td>
                                            <input type="text"
                                                value="{{ $registration->patient->medical_record_number }} / {{ $registration->registration_number }}"
                                                class="form-control" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Nama Pemesan</td>
                                        <td>
                                            <input type="text" value="{{ $registration->patient->name }}"
                                                class="form-control" name="nama_pemesan" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tanggal Pemesanan</td>
                                        <td>
                                            <input type="datetime-local" name="tanggal_order" class="form-control" required
                                                value="{{ now()->addHours(7)->format('Y-m-d\TH:i') }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Kategori</td>
                                        <td>
                                            <select name="kategori_id" id="kategori_id" class="select2 form-control">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Waktu Makan</td>
                                        <td>
                                            <select class="select2 form-control w-100" name="waktu_makan">
                                                <option value=""></option>
                                                @foreach ($jam_makans as $jam_makan)
                                                    <option value="{{ $jam_makan->nama }}">
                                                        {{ $jam_makan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Ditagihkan <span style="color: red">(pasien Super VIP pilih tidak)</span></td>
                                        <td>
                                            <label for="ditagihkan_yes"><input type="radio" name="ditagihkan"
                                                    id="ditagihkan_yes" value="1" checked>Ya</label>

                                            <label for="ditagihkan_no"><input type="radio" name="ditagihkan"
                                                    id="ditagihkan_no" value="0">Tidak</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Digabung <span style="color: red">(berlaku hanya untuk pemesanan keluarga
                                                pasien)</span></td>
                                        <td>
                                            <label for="digabung_yes"><input type="radio" name="digabung"
                                                    id="digabung_yes" value="1" checked>Ya</label>

                                            <label for="digabung_no"><input type="radio" name="digabung" id="digabung_no"
                                                    value="0">Tidak</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Cari Menu</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#pilihMenuModal" title="Pilih menu">
                                                <span class="mdi mdi-magnify"></span>
                                            </button>
                                            @include('pages.simrs.gizi.partials.pilih-menu-modal', [
                                                'menus' => $menus,
                                            ])
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Cari Makanan</td>
                                        <td>
                                            <select class="select2 form-control w-100" id="search-food">
                                                <option value=""></option>
                                                @foreach ($foods as $food)
                                                    <option value="{{ $food->id }}">
                                                        [{{ rp($food->harga) }}] {{ $food->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <table class="table table-bordered table-hover table-striped w-100">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th>Nama Makanan</th>
                                                    <th>Qty</th>
                                                    <th>Harga</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-food">

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right">Total
                                                        <input type="hidden" value="0" name="total_harga">
                                                    </td>
                                                    <td id="harga-display">Rp. 0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </tr>

                                </table>

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
    <script>
        window._foods = @json($foods);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/simrs/popup-order-gizi.js') }}?v={{ time() }}"></script>
@endsection
