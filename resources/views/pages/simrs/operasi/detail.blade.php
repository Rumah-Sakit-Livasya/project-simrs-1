@extends('layouts.app')

@section('title', 'Detail Order Operasi - ' . $order->id)

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- Kolom Info Pasien --}}
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Info Pasien & Order</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    No. RM
                                    <span
                                        class="font-weight-bold">{{ $order->registration->patient->medical_record_number }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Nama Pasien
                                    <span class="font-weight-bold">{{ $order->registration->patient->name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Tgl Lahir
                                    <span
                                        class="font-weight-bold">{{ \Carbon\Carbon::parse($order->registration->patient->date_of_birth)->format('d M Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Tgl Rencana
                                    <span
                                        class="font-weight-bold">{{ \Carbon\Carbon::parse($order->tgl_operasi)->format('d M Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Diagnosa
                                    <span class="font-weight-bold">{{ $order->diagnosa_awal }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Prosedur & Laporan --}}
            <div class="col-md-8">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Manajemen Prosedur Operasi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Di sini nanti akan ada tabel untuk Prosedur Operasi --}}
                            <p>Tabel untuk menampilkan prosedur operasi, tim operasi, dan laporan akan ada di sini.</p>

                            <button class="btn btn-primary">Tambah Prosedur</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
