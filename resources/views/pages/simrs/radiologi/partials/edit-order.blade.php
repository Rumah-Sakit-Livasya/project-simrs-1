@extends('inc.layout-no-side')
@section('title', 'Edit Order Radiologi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Order Radiologi [{{ $order->no_order }}]
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">


                            <form id="form-radiologi">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="order_date">
                                                        Tanggal Order
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="order_date" readonly
                                                        value="{{ $order->order_date }}" name="order_date">
                                                    @error('order_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="penjamin">Penjamin</label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="penjamin" readonly
                                                        value="{{ $order->registration->penjamin->nama_perusahaan ?? ' - ' }}"
                                                        name="penjamin">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="doctor_id">Poly/Ruang</label>
                                                </div>
                                                <div class="col-xl-8">
                                                    @if ($order->registration->registration_type != 'rawat-inap')
                                                        <input type="text"
                                                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                            class="form-control" id="poly_ruang" readonly
                                                            value="{{ $order->registration->poliklinik }}"
                                                            name="poly_ruang">
                                                    @else
                                                        <input type="text"
                                                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                            class="form-control" id="poly_ruang" readonly
                                                            value="[Belum Ada]" name="poly_ruang">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="doctor_id">Diagnosa Klinis*</label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="diagnosa_klinis"
                                                        value="{{ $order->diagnosa_klinis }}" name="diagnosa_klinis">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="doctor_perujuk">Dokter Perujuk</label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="doctor_perujuk" readonly
                                                        value="{{ $order->registration->doctor->employee->fullname }}" name="doctor_perujuk">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="patient_name">
                                                        Nama Pasien
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="patient_name" readonly
                                                        value="{{ $order->registration->patient->name }}" name="patient_name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="gender">Jenis Kelamin</label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="gender" readonly
                                                        value="{{ $order->registration->patient->gender }}"
                                                        name="gender">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="date_of_birth">Tgl. Lahir</label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="date_of_birth" readonly
                                                        value="{{ $order->registration->patient->date_of_birth }}"
                                                        name="date_of_birth">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="address">
                                                        Alamat
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="address" readonly
                                                        value="{{ $order->registration->patient->address }}"
                                                        name="address">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="inspection_date">
                                                        Tgl Pemeriksaan
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="inspection_date"
                                                        value="{{ $today }}"
                                                        name="inspection_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    &nbsp;
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Radiografer</th>
                                                        <th>Parameter</th>
                                                        <th>Dokter</th>
                                                        <th>Photo</th>
                                                        <th>Jumlah Film</th>
                                                        <th>Verifikasi</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="radiologyTable">
                                                    @php
                                                        $totalCount = 0;
                                                    @endphp
                                                    @foreach ($parametersInCategory as $categoryName => $parameters)
                                                        <tr class="table-info">
                                                            <td colspan="8">
                                                                <h4 style="text-align: center">
                                                                    {{ $categoryName }}</h4>
                                                            </td>
                                                        </tr>
                                                        @foreach ($parameters as $parameter)
                                                            <td>{{ ++$totalCount }}</td>
                                                            <td> [Radiografer] </td>
                                                            <td>
                                                                <h3> {{ $parameter->parameter_radiologi->parameter }}</h3>
                                                                <p>
                                                                    {{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($parameter->nominal_rupiah, 'IDR') }}
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-xl-8">
                                                                            <input class="form-control" disabled
                                                                                value="{{ $order->doctor->employee->fullname }}" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td> [Photo] </td>
                                                            <td> [Jumlah Film] </td>
                                                            <td> [Verifikasi] </td>
                                                            <td> [Action] </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    <tr>
                                                        <td class="text-danger" colspan="8">
                                                            <h1> <i class="fa fa-calculator"></i>
                                                                Total:
                                                                {{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($order->order_parameter_radiologi->sum('nominal_rupiah'), 'IDR') }}
                                                            </h1>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 mt-5">
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <a onclick="window.close()"
                                                    class="btn btn-lg btn-default waves-effect waves-themed">
                                                    <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                    <span class="text-primary">Kembali</span>
                                                </a>
                                            </div>
                                            <div class="col-xl-6 text-right">
                                                <button type="submit" id="radiologi-submit"
                                                    class="btn btn-lg btn-primary waves-effect waves-themed">
                                                    <span class="fal fa-save mr-1"></span>
                                                    Simpan
                                                </button>
                                            </div>
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
