@extends('inc.layout-no-side')
@section('title', 'Edit Order Radiologi')
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
                            Order Radiologi [{{ $order->no_order }}]
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">


                            <form id="form-radiologi" action="{{ route('order.radiologi.edit-order') }}" method="POST">
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
                                                        Tgl Pemeriksaan*
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
                                                    @if($order->registration_otc)
                                                        <input type="text"
                                                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                            class="form-control" id="rm_reg" readonly
                                                            value="OTC"
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
                                                    <label class="form-label" for="pickup_date">
                                                        Tgl Pengambilan*
                                                    </label>
                                                </div>
                                                <div class="col-xl-8">
                                                    <input type="date"
                                                        class="@error('pickup_date') is-invalid @enderror form-control"
                                                        id="pickup_date" placeholder="Tanggal Lahir" name="pickup_date"
                                                        value="{{ $order->pickup_date ?? old('pickup_date') }}">
                                                    @error('pickup_date')
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
                                                        <th>Radiografer</th>
                                                        <th>Parameter</th>
                                                        <th>Dokter</th>
                                                        <th>Photo</th>
                                                        <th>Film Qty</th>
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
                                                            <td>
                                                                <select class="select2 form-control w-100"
                                                                    id="radiografer_{{ $parameter->id }}"
                                                                    name="radiografer_{{ $parameter->id }}">
                                                                    <option value=""></option>
                                                                    @foreach ($radiografers as $employee)
                                                                        @if ($parameter->radiografer_id != $employee->id)
                                                                            <option value="{{ $employee->id }}">
                                                                                {{ $employee->fullname }}
                                                                            </option>
                                                                        @else
                                                                            <option value="{{ $employee->id }}" selected>
                                                                                {{ $employee->fullname }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
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
                                                            <td>
                                                                @if ($parameter->foto)
                                                                    @php
                                                                        $images = json_decode($parameter->foto);
                                                                    @endphp
                                                                    @foreach ($images as $image)
                                                                        <img src="{{ url('storage/' . $image) }}"
                                                                            class="parameter-photo pointer">
                                                                    @endforeach
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <input type="number" class="form-control"
                                                                style="width: 60px;"
                                                                id="jumlah_film_{{ $parameter->id }}"
                                                                value="{{ $parameter->film_qty ?? 0 }}"
                                                                name="jumlah_film_{{ $parameter->id }}">
                                                        </td>
                                                        <td>
                                                            @if (!isset($parameter->verifikator_id))
                                                                <div align="center">
                                                                    <button type="button" data-id="{{ $parameter->id }}"
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
                                                        <td> <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                                title="Edit Pemeriksaan"
                                                                data-id="{{ $parameter->id }}"></a>
                                                            <a class="mdi mdi-image pointer mdi-24px text-warning photo-up-btn"
                                                                title="Upload photo" data-toggle="modal"
                                                                data-target="#importModal{{ $parameter->id }}">
                                                            </a>

                                                            <div class="modal fade" id="importModal{{ $parameter->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="importModalLabel" aria-hidden="true">
                                                                @include(
                                                                    'pages.simrs.radiologi.partials.upload-photo-parameter',
                                                                    ['parameter' => $parameter]
                                                                )
                                                            </div>

                                                            <a class="mdi mdi-file-document pointer mdi-24px text-success template-btn"
                                                                title="Template Hasil" data-toggle="modal"
                                                                data-target="#templateModal{{ $parameter->id }}">
                                                            </a>
                                                            <div class="modal fade"
                                                                id="templateModal{{ $parameter->id }}" tabindex="-1"
                                                                role="dialog" aria-labelledby="templateModal"
                                                                aria-hidden="true">
                                                                @include(
                                                                    'pages.simrs.radiologi.partials.template-hasil-form',
                                                                    [
                                                                        'orderParameterId' => $parameter->id,
                                                                        'templates' => $templates,
                                                                    ]
                                                                )
                                                                @include(
                                                                    'pages.simrs.radiologi.partials.template-hasil-datatable',
                                                                    [
                                                                        'orderParameterId' => $parameter->id,
                                                                        'templates' => $templates,
                                                                    ]
                                                                )
                                                            </div>

                                                        </td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/simrs/edit-order-radiologi.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/simrs/upload-photo-parameter-radiologi.js') }}?v={{ time() }}"></script>
    <script>
        function initializePhotoUploadPopover() {
            const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            list.map((el) => {
                let opts = {
                    animation: true,
                }
                if (el.hasAttribute('data-bs-content-id')) {
                    opts.content = document.getElementById(el.getAttribute('data-bs-content-id')).innerHTML;
                    opts.html = true;
                    opts.sanitize = false;
                }
                new bootstrap.Popover(el, opts);
            })
        }
        initializePhotoUploadPopover();
    </script>

@endsection
