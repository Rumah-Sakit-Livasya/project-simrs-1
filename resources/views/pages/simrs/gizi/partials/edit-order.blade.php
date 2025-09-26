@extends('inc.layout-no-side')
@section('title', 'Edit order gizi')
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
                            Edit order gizi [ORDER_ID: {{ $order->id }}]
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-order-gizi" name="form-order-gizi" action="{{ route('gizi.order.update') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="id" value="{{ $order->id }}">

                                <table style="width: 100%">
                                    <tr>
                                        <td>Nama Pasien</td>
                                        <td>
                                            <input type="text" value="{{ $order->registration->patient->name }}"
                                                class="form-control" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>RM / No. Reg</td>
                                        <td>
                                            <input type="text"
                                                value="{{ $order->registration->patient->medical_record_number }} / {{ $order->registration->registration_number }}"
                                                class="form-control" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Nama Pemesan</td>
                                        <td>
                                            <input type="text" value="{{ $order->registration->patient->name }}"
                                                class="form-control" name="nama_pemesan" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tanggal Pemesanan</td>
                                        <td>
                                            <input type="datetime-local" name="tanggal_order" class="form-control" required
                                                value="{{ $order->tanggal_order }}" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Kategori</td>
                                        <td>
                                            <input type="text" name="kategori_id" id="{{ $order->category->id }}"
                                                class="form-control" readonly required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Waktu Makan</td>
                                        <td>
                                            <input type="text" name="waktu_makan" id="waktu_makan" class="form-control"
                                                value="{{ ucfirst($order->waktu_makan) }}" readonly required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Ditagihkan <span style="color: red">(pasien Super VIP pilih tidak)</span></td>
                                        <td>
                                            <input type="text" name="ditagihkan" id="ditagihkan" class="form-control"
                                                value="{{ $order->ditagihkan ? 'Ya' : 'Tidak' }}" readonly required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Digabung <span style="color: red">(berlaku hanya untuk pemesanan keluarga
                                                pasien)</span></td>
                                        <td>
                                            <input type="text" name="digabung" id="digabung" class="form-control"
                                                value="{{ $order->digabung ? 'Ya' : 'Tidak' }}" readonly required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <table class="table table-bordered table-hover table-striped w-100">
                                            <thead class="bg-primary-600">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Makanan</th>
                                                    <th>Harga</th>
                                                    <th>% Habis</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-food">
                                                @foreach ($order->foods as $food)
                                                    <tr id="food{{ $food->id }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $food->food->nama }}</td>
                                                        <td id="harga{{ $food->id }}">{{ rp($food->harga) }}</td>
                                                        <td>
                                                            @php
                                                                $x = $food->persentase_habis;
                                                            @endphp

                                                            <select name="habis_{{ $food->id }}"
                                                                id="habis_{{ $food->id }}" class="form-control">
                                                                <option value="0" {{ $x <= 0 ? 'selected' : '' }}>
                                                                    Tidak
                                                                    Disentuh (utuh)</option>
                                                                <option value="25"
                                                                    {{ $x >= 1 && $x <= 25 ? 'selected' : '' }}>Sisa 3/4
                                                                </option>
                                                                <option value="50"
                                                                    {{ $x >= 26 && $x <= 50 ? 'selected' : '' }}>Sisa 1/2
                                                                </option>
                                                                <option value="75"
                                                                    {{ $x >= 51 && $x <= 75 ? 'selected' : '' }}>Sisa 1/4
                                                                </option>
                                                                <option value="100" {{ $x >= 76 ? 'selected' : '' }}>
                                                                    Habis</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right">Total
                                                        <input type="hidden" value="{{ $order->total_harga }}"
                                                            name="total_harga">
                                                    </td>
                                                    <td id="harga-display">{{ rp($order->total_harga) }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/jquery.js') }}"></script>
@endsection
