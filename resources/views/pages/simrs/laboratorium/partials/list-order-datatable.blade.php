    {{-- CSS untuk child row dan styling ikon --}}
    <style>
        /* Styling untuk child row agar lebih menonjol */
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

    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Daftar <span class="fw-300"><i>Order Laboratorium</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <!-- datatable start -->
                        <table id="dt-lab-orders" class="table table-bordered table-hover table-striped w-100">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th style="width: 20px;"></th> {{-- Kolom untuk ikon child row --}}
                                    <th>Tanggal</th>
                                    <th>No. RM</th>
                                    <th>No. Registrasi</th>
                                    <th>No. Order</th>
                                    <th>Nama Lengkap</th>
                                    <th>Poli / Ruang</th>
                                    <th>Penjamin</th>
                                    <th>Dokter</th>
                                    <th>Status Hasil</th>
                                    <th>Status Billed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    {{-- Simpan detail parameter di atribut data-details --}}
                                    <tr data-details="{{ json_encode($order->order_parameter_laboratorium) }}">
                                        {{-- Kolom untuk ikon + / - --}}
                                        <td class="details-control text-center">
                                            <i class="fal fa-plus-circle text-success" style="cursor: pointer;"></i>
                                        </td>

                                        {{-- Data ditampilkan dengan lebih sederhana --}}
                                        @if ($order->registration_otc)
                                            {{-- Baris untuk Pasien Luar (OTC) --}}
                                            <td>{{ $order->order_date }}</td>
                                            <td><span class="badge badge-info">OTC</span></td>
                                            <td>{{ $order->registration_otc->registration_number }}</td>
                                            <td>{{ $order->no_order }}</td>
                                            <td>{{ $order->registration_otc->nama_pasien }}</td>
                                            <td>{{ $order->registration_otc->poly_ruang }}</td>
                                            <td>{{ $order->registration_otc->penjamin->nama_perusahaan ?? '-' }}</td>
                                        @else
                                            {{-- Baris untuk Pasien Terdaftar --}}
                                            <td><a href="{{ $order->patient_detail_link }}">{{ $order->order_date }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ $order->patient_detail_link }}">{{ $order->registration->patient->medical_record_number }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ $order->patient_detail_link }}">{{ $order->registration->registration_number }}</a>
                                            </td>
                                            <td><a href="{{ $order->patient_detail_link }}">{{ $order->no_order }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ $order->patient_detail_link }}">{{ $order->registration->patient->name }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ $order->patient_detail_link }}">{{ $order->registration->poliklinik }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ $order->patient_detail_link }}">{{ $order->registration->patient->penjamin->nama_perusahaan ?? '-' }}</a>
                                            </td>
                                        @endif

                                        {{-- Kolom yang sama untuk kedua tipe pasien --}}
                                        <td>{{ $order->doctor->employee->fullname ?? 'N/A' }}</td>
                                        <td>
                                            @if ($order->status_isi_hasil == 1)
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning">Proses</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->status_billed == 1)
                                                <span class="badge badge-success">Sudah Ditagih</span>
                                            @else
                                                <span class="badge badge-secondary">Belum Ditagih</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->is_konfirmasi == 1)
                                                <a class="mdi mdi-printer pointer mdi-24px text-success nota-btn"
                                                    title="Print Nota Order" data-id="{{ $order->id }}"></a>
                                            @else
                                                <a class="mdi mdi-cash pointer mdi-24px text-danger pay-btn"
                                                    title="Konfirmasi Tagihan" data-id="{{ $order->id }}"></a>
                                            @endif
                                            <a class="mdi mdi-pencil pointer mdi-24px text-secondary edit-btn"
                                                title="Edit" data-id="{{ $order->id }}"></a>
                                            <a class="mdi mdi-tag pointer mdi-24px text-danger label-btn"
                                                title="Print Label" data-id="{{ $order->id }}"></a>
                                            <a class="mdi mdi-file-document pointer mdi-24px text-warning result-btn"
                                                title="Print Hasil" data-id="{{ $order->id }}"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- datatable end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
