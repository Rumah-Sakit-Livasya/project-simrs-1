@extends('pages.simrs.pendaftaran.detail-registrasi-pasien')
@push('css-detail-regis')
    <style>
        tr.details-shown>td {
            padding: 0 !important;
            border-bottom: 2px solid #3c6eb4 !important;
        }

        .child-table {
            width: 95%;
            margin: 10px auto;
        }

        .child-table thead {
            background-color: #eef3f9;
        }
    </style>
@endpush

@section('page-layanan')
    <div class="panel-hdr border-top">
        <h2 class="text-light">
            <i class="fas fa-flask mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
            <span class="text-primary">Laboratorium</span>
        </h2>
    </div>
    <div class="row">
        <div class="col-md-12 px-4 pb-2 pt-4">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="fal fa-notes-medical mr-2"></i> Daftar Order Laboratorium
                        </h4>
                        <button class="btn btn-primary btn-sm" id="btn-show-lab-form">
                            <i class="fal fa-plus mr-1"></i> Buat Order Baru
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="dt-lab-orders" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th class="text-center" style="width: 30px;">#</th>
                                    <th>Tgl Order</th>
                                    <th>No. Order</th>
                                    <th>Dokter Lab</th>
                                    <th>Tipe</th>
                                    <th>Status Hasil</th>
                                    <th>Status Billing</th>
                                    <th style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laboratoriumOrders as $order)
                                    <tr data-details="{{ json_encode($order->order_parameter_laboratorium) }}">
                                        <td class="text-center details-control">
                                            <i class="fal fa-plus-circle text-success" style="cursor: pointer;"></i>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i') }}</td>
                                        <td>{{ $order->no_order }}</td>
                                        <td>{{ $order->doctor->employee->fullname ?? 'N/A' }}</td>
                                        <td>
                                            @if ($order->is_cito)
                                                <span class="badge badge-danger">CITO</span>
                                            @else
                                                <span class="badge badge-primary">Normal</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->status_isi_hasil == 1)
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning">Proses</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->status_billed == 1)
                                                <span class="badge badge-success">Payment (closed)</span>
                                            @else
                                                <span class="badge badge-secondary">Not Billed</span>
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-xs btn-outline-primary" data-toggle="tooltip"
                                                title="Cetak Hasil">
                                                <i class="fal fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if (str_contains(\Illuminate\Support\Facades\Route::currentRouteName(), 'daftar-registrasi-pasien') ||
                            str_contains(url()->current(), '/daftar-registrasi-pasien/'))
                        <div class="d-flex justify-content-start m-3">
                            <a href="{{ route('detail.registrasi.pasien', ['registrations' => $registration->id]) }}"
                                class="btn btn-outline-primary px-4 shadow-sm d-flex align-items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                <span>Kembali ke Menu</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="panel-laboratorium-form" style="display: none;">
        <div class="col-md-12 px-4 pb-2 pt-4">
            <div class="panel-container show">
                <div class="panel-content">
                    @include('pages.simrs.pendaftaran.partials.order-laboratorium')
                </div>
            </div>
        </div>
    </div>
@endsection
