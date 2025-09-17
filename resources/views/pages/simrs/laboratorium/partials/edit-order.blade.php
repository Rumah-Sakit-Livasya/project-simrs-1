@extends('inc.layout-no-side')
@section('title', 'Edit Order Laboratorium')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        .parameter-photo {
            max-width: 80px;
            max-height: 80px;
        }

        .modal-dialog {
            max-width: 70%;
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
                            Order Laboratorium [{{ $order->no_order }}]
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">


                            <form id="form-laboratorium" action="{{ route('order.laboratorium.edit-order') }}"
                                method="POST">
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
                                                    @if ($order->registration_otc)
                                                        <a>
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="poly_ruang" readonly
                                                                value="{{ $order->registration_otc->poly_ruang }}"
                                                                name="poly_ruang">
                                                        </a>
                                                    @else
                                                        @if ($order->registration->registration_type != 'rawat-inap')
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="poly_ruang" readonly
                                                                value="{{ $order->registration->poliklinik }}"
                                                                name="poly_ruang">
                                                        @elseif(isset($order->registration->kelas_rawat))
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="poly_ruang" readonly
                                                                value="{{ $order->registration->kelas_rawat->room->ruangan }}"
                                                                name="poly_ruang">
                                                        @else
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="poly_ruang" readonly value=" - "
                                                                name="poly_ruang">
                                                        @endif
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
                                                    <input type="text" class="form-control" id="diagnosa_klinis"
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
                                                        value="{{ $order->registration ? $order->registration->doctor->employee->fullname : 'OTC' }}"
                                                        name="doctor_perujuk">
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
                                                        value="{{ $order->registration ? $order->registration->patient->name : $order->registration_otc->nama_pasien }}"
                                                        name="patient_name">
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
                                                        value="{{ $order->registration ? $order->registration->patient->gender : $order->registration_otc->jenis_kelamin }}"
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
                                                        value="{{ $order->registration ? $order->registration->patient->date_of_birth : $order->registration_otc->date_of_birth }}"
                                                        name="date_of_birth">
                                                </div>
                                            </div>
                                        </div>
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
                                                        value="{{ $order->registration ? $order->registration->patient->address : $order->registration_otc->alamat }}"
                                                        name="address">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="inspection_date">
                                                        Tgl Sampel*
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="datetime-local"
                                                        class="@error('inspection_date') is-invalid @enderror form-control"
                                                        id="inspection_date" placeholder="Tanggal Lahir"
                                                        name="inspection_date"
                                                        value="{{ $order->inspection_date ? \Carbon\Carbon::parse($order->inspection_date)->format('Y-m-d\TH:i') : old('inspection_date') }}">
                                                    @error('inspection_date')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="rm_reg">
                                                        No RM / Registrasi
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    @if ($order->registration_otc)
                                                        <input type="text"
                                                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                            class="form-control" id="rm_reg" readonly value="OTC"
                                                            name="rm_reg">
                                                    @else
                                                        <input type="text"
                                                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                            class="form-control" id="rm_reg" readonly
                                                            value="{{ $order->registration->patient->medical_record_number }} / {{ $order->registration->registration_number }}"
                                                            name="rm_reg">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="mobile_phone_number">
                                                        No Telp
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="mobile_phone_number" readonly
                                                        value="{{ $order->registration ? $order->registration->patient->mobile_phone_number : $order->registration_otc->no_telp }}"
                                                        name="mobile_phone_number">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="age">
                                                        Umur
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="text"
                                                        style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="age" readonly
                                                        value="{{ $order->registration ? displayAge($order->registration->patient->date_of_birth) : displayAge($order->registration_otc->date_of_birth) }}"
                                                        name="age">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 text-right">
                                                    <label class="form-label" for="result_date">
                                                        Tgl Hasil*
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="date"
                                                        class="@error('result_date') is-invalid @enderror form-control"
                                                        id="result_date" placeholder="Tanggal Lahir" name="result_date"
                                                        value="{{ $order->result_date ? \Carbon\Carbon::parse($order->result_date)->format('Y-m-d') : old('result_date') }}">
                                                    @error('result_date')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
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
                                                        <th>CITO</th>
                                                        <th>Pemeriksaan</th>
                                                        <th>Hasil</th>
                                                        <th>Satuan</th>
                                                        <th>Nilai Normal</th>
                                                        <th>Info N.Reff</th>
                                                        <th>Keterangan</th>
                                                        <th>Dokter</th>
                                                        <th>Verifikasi</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="laboratoriumTable">
                                                    @php
                                                        $totalCount = 0;
                                                    @endphp
                                                    @foreach ($parametersInCategory as $categoryName => $parameters)
                                                        <tr class="table-info">
                                                            <td colspan="11">
                                                                <h4 style="text-align: center">
                                                                    {{ $categoryName }}</h4>
                                                            </td>
                                                        </tr>
                                                        @foreach ($parameters as $parameter)
                                                            @php
                                                                $nilai_normal_parameter = null;

                                                                if ($order->registration) {
                                                                    $dob = $order->registration->patient->date_of_birth;
                                                                    $jenis_kelamin =
                                                                        $order->registration->patient->gender;
                                                                } else {
                                                                    // otc
                                                                    $dob = $order->registration_otc->date_of_birth;
                                                                    $jenis_kelamin =
                                                                        $order->registration_otc->jenis_kelamin;
                                                                }

                                                                foreach ($nilai_normals as $nilai_normal) {
                                                                    if (
                                                                        $nilai_normal->parameter_laboratorium_id ==
                                                                        $parameter->parameter_laboratorium_id
                                                                    ) {
                                                                        if (
                                                                            isWithinAgeRange(
                                                                                $dob,
                                                                                $nilai_normal->dari_umur,
                                                                                $nilai_normal->sampai_umur,
                                                                            ) &&
                                                                            ($nilai_normal->jenis_kelamin ==
                                                                                $jenis_kelamin ||
                                                                                $nilai_normal->jenis_kelamin ==
                                                                                    'Semuanya')
                                                                        ) {
                                                                            $nilai_normal_parameter = $nilai_normal;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            <td>{{ ++$totalCount }}</td>
                                                            <td>{{ $order->tipe_order == 'cito' && 'CITO' }}</td>
                                                            <td>
                                                                <h3> {{ $parameter->parameter_laboratorium->parameter }}
                                                                </h3>
                                                                @if ($parameter->parameter_laboratorium->is_order)
                                                                    <p>
                                                                        {{ number_format($parameter->nominal_rupiah, 0, ',', '.') }}
                                                                    </p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input @if (!$parameter->parameter_laboratorium->is_hasil) disabled @endif
                                                                    type="text" class="form-control"
                                                                    id="hasil_{{ $parameter->id }}"
                                                                    value="{{ $parameter->hasil ?? '' }}"
                                                                    name="hasil_{{ $parameter->id }}">
                                                            </td>
                                                            <td>
                                                                {{ $parameter->parameter_laboratorium->satuan }}
                                                            </td>
                                                            <td>
                                                                @if ($nilai_normal_parameter)
                                                                    @if ($parameter->parameter_laboratorium->tipe_hasil == 'Angka')
                                                                        {{ $nilai_normal_parameter->min }} -
                                                                        {{ $nilai_normal_parameter->max }}
                                                                    @else
                                                                        {{ $nilai_normal_parameter->nilai_normal }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $parameter->nreff && '{nreff}' }}
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" name="catatan_{{ $parameter->id }}" id="catatan_{{ $parameter->id }}">{{ $parameter->catatan }}</textarea>
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
                                                            <td>
                                                                @if (!isset($parameter->verifikator_id))
                                                                    <div align="center">
                                                                        <button type="button"
                                                                            data-id="{{ $parameter->id }}"
                                                                            class="btn btn-primary verify-btn">Verifikasi</button>
                                                                    </div>
                                                                @else
                                                                    <div align="center">
                                                                        <i class="mdi mdi-check text-success"
                                                                            style="font-size: 40px"></i>
                                                                        <p>Verified by
                                                                            <i>{{ $parameter->verifikator->fullname }}</i>
                                                                            <br>
                                                                            On
                                                                            <i>{{ $parameter->verifikasi_date }}</i>
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (
                                                                    $parameter->parameter_laboratorium->is_order &&
                                                                        count(array_filter($parameters, function ($param) {
                                                                                return $param['parameter_laboratorium']['is_order'] ?? false;
                                                                            })) > 1)
                                                                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                                        title="Hapus Pemeriksaan"
                                                                        data-id="{{ $parameter->id }}"></a>
                                                                @endif
                                                            </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    <tr>
                                                        <td class="text-danger" colspan="8">
                                                            <h1> <i class="fa fa-calculator"></i>
                                                                Total:
                                                                {{ number_format($order->order_parameter_laboratorium->sum('nominal_rupiah'), 0, ',', '.') }}
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
                                                <a href="{{ route('laboratorium.list-order') }}"
                                                    class="btn btn-lg btn-default waves-effect waves-themed">
                                                    <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                    <span class="text-primary">Kembali</span>
                                                </a>
                                            </div>
                                            <div class="col-xl-6 text-right">
                                                <button type="submit" id="laboratorium-submit"
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window._order = @json($order);
        });
    </script>
    <script src="{{ asset('js/simrs/edit-order-laboratorium.js') }}?v={{ time() }}"></script>
@endsection
