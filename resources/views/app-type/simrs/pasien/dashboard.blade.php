@extends('inc.layout')
@section('title', 'Riwayat Lengkap Pasien')

@section('extended-css')
    <style>
        .patient-info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
            padding: 1.25rem;
        }

        .patient-info-box .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .patient-info-box .info-item strong {
            color: #495057;
        }

        .patient-info-box .info-item span {
            color: #212529;
            font-weight: 500;
            text-align: right;
        }

        .examination-table {
            margin-top: 10px;
        }

        .examination-table th {
            font-size: 0.8rem;
            background-color: #f3f3f3;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader mb-4">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-user-heart'></i> Riwayat Lengkap Pasien
                <small>
                    Semua riwayat registrasi dan pemeriksaan pasien.
                </small>
            </h1>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div id="panel-pasien" class="panel">
                    <div class="panel-hdr">
                        <h2>Informasi Pasien</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <img src="{{ $pasien->photo ?? ($pasien->gender == 'L' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png') }}"
                                        class="rounded-circle img-thumbnail" alt="Foto Pasien"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-md-5">
                                    <div class="patient-info-box h-100">
                                        <div class="info-item">
                                            <strong>Nama Pasien:</strong>
                                            <span>{{ $pasien->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>No. Rekam Medis:</strong>
                                            <span>{{ $pasien->medical_record_number ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>NIK:</strong>
                                            <span>{{ $pasien->nik ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="patient-info-box h-100">
                                        <div class="info-item">
                                            <strong>Tanggal Lahir:</strong>
                                            <span>
                                                {{ $pasien->date_of_birth ? \Carbon\Carbon::parse($pasien->date_of_birth)->format('d-m-Y') : 'N/A' }}
                                                ({{ $pasien->date_of_birth ? \Carbon\Carbon::parse($pasien->date_of_birth)->age : 'N/A' }}
                                                Thn)
                                            </span>
                                        </div>
                                        <div class="info-item">
                                            <strong>Jenis Kelamin:</strong>
                                            <span>{{ $pasien->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>Alamat:</strong>
                                            <span>{{ $pasien->address ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>Alergi:</strong>
                                            <span class="text-danger">{{ $pasien->alergi ?? 'Tidak ada data' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline seluruh riwayat registrasi pasien --}}
        <div class="row">
            <div class="col-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Timeline Kunjungan</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @forelse ($registrations as $reg)
                                <div class="panel panel-outline-secondary mb-3">
                                    <div class="panel-hdr">
                                        <div class="panel-title">
                                            <h5 class="mb-0">
                                                <strong>{{ $reg->jenis_rawat ?? 'Kunjungan' }}</strong>
                                                <span
                                                    class="text-muted small ml-2">{{ \Carbon\Carbon::parse($reg->created_at)->format('d M Y, H:i') }}</span>
                                                <span
                                                    class="badge badge-primary ml-2">{{ $reg->departement->name ?? 'N/A' }}</span>
                                            </h5>
                                        </div>
                                        <div class="panel-toolbar">
                                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                                data-offset="0,10" data-original-title="Collapse"></button>
                                        </div>
                                    </div>
                                    <div class="panel-container show">
                                        <div class="panel-content">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>No. Registrasi:</strong>
                                                    {{ $reg->registration_number ?? 'N/A' }}<br>
                                                    <strong>Dokter DPJP:</strong>
                                                    {{ $reg->doctor->employee->fullname ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Penjamin:</strong>
                                                    {{ $reg->penjamin->nama_perusahaan ?? 'N/A' }}<br>
                                                    <strong>Status:</strong> <span
                                                        class="badge badge-success">{{ $reg->status ?? 'Aktif' }}</span>
                                                </div>
                                            </div>
                                            <hr>

                                            {{-- Asesmen & Diagnosis (SOAP/CPPT) --}}
                                            <h5 class="mt-3"><i class="fal fa-stethoscope text-primary"></i> Asesmen &
                                                Diagnosis (SOAP/CPPT)</h5>
                                            @if ($reg->asesmen && $reg->asesmen->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tanggal</th>
                                                            <th>Profesi</th>
                                                            <th>SOAP</th>
                                                            <th>Diagnosis</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->asesmen as $item)
                                                            <tr>
                                                                <td>{{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $item->profesi ?? 'N/A' }}</td>
                                                                <td>
                                                                    <strong>S:</strong> {{ $item->subjective ?? '-' }}<br>
                                                                    <strong>O:</strong> {{ $item->objective ?? '-' }}<br>
                                                                    <strong>A:</strong> {{ $item->assessment ?? '-' }}<br>
                                                                    <strong>P:</strong> {{ $item->planning ?? '-' }}
                                                                </td>
                                                                <td>{{ $item->diagnosis ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada data asesmen.</span>
                                            @endif

                                            {{-- Laboratorium --}}
                                            <h5 class="mt-3"><i class="fal fa-flask text-primary"></i> Hasil Laboratorium
                                            </h5>
                                            @if ($reg->order_laboratorium && $reg->order_laboratorium->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tgl Order</th>
                                                            <th>Pemeriksaan</th>
                                                            <th>Hasil</th>
                                                            <th>Nilai Rujukan</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->order_laboratorium as $lab)
                                                            <tr>
                                                                <td>{{ $lab->created_at ? $lab->created_at->format('d-m-Y H:i') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $lab->nama_pemeriksaan ?? 'N/A' }}</td>
                                                                <td>{{ $lab->hasil ?? 'N/A' }}</td>
                                                                <td>{{ $lab->nilai_rujukan ?? 'N/A' }}</td>
                                                                <td>{{ $lab->status ?? 'N/A' }}</td>
                                                                <td>
                                                                    @if ($lab->hasil ?? false)
                                                                        <a href="{{ $lab->hasil_url ?? '#' }}"
                                                                            class="btn btn-xs btn-info"
                                                                            target="_blank">Lihat</a>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada pemeriksaan laboratorium.</span>
                                            @endif

                                            {{-- Radiologi --}}
                                            <h5 class="mt-3"><i class="fal fa-radiation-alt text-danger"></i> Hasil
                                                Radiologi</h5>
                                            @if ($reg->order_radiologi && $reg->order_radiologi->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tgl Order</th>
                                                            <th>Pemeriksaan</th>
                                                            <th>Hasil Expertise</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->order_radiologi as $radio)
                                                            <tr>
                                                                <td>{{ $radio->created_at ? $radio->created_at->format('d-m-Y H:i') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $radio->nama_pemeriksaan ?? 'N/A' }}</td>
                                                                <td>{{ $radio->hasil_expertise ?? 'N/A' }}</td>
                                                                <td>{{ $radio->status ?? 'N/A' }}</td>
                                                                <td>
                                                                    @if ($radio->hasil ?? false)
                                                                        <a href="{{ $radio->hasil_url ?? '#' }}"
                                                                            class="btn btn-xs btn-info"
                                                                            target="_blank">Lihat Gambar</a>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada pemeriksaan radiologi.</span>
                                            @endif

                                            {{-- Tindakan Medis --}}
                                            <h5 class="mt-3"><i class="fal fa-procedures text-success"></i> Tindakan Medis
                                            </h5>
                                            @if ($reg->order_tindakan_medis && $reg->order_tindakan_medis->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tgl Order</th>
                                                            <th>Nama Tindakan</th>
                                                            <th>Kode ICD-9 CM</th>
                                                            <th>Laporan Singkat</th>
                                                            <th>Pelaksana</th>
                                                            <th>Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->order_tindakan_medis as $tindakan)
                                                            <tr>
                                                                <td>{{ $tindakan->created_at ? $tindakan->created_at->format('d-m-Y H:i') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $tindakan->nama_tindakan ?? 'N/A' }}</td>
                                                                <td>{{ $tindakan->icd9_code ?? 'N/A' }}</td>
                                                                <td>{{ $tindakan->laporan_singkat ?? 'N/A' }}</td>
                                                                <td>{{ $tindakan->pelaksana->name ?? 'N/A' }}</td>
                                                                <td>{{ $tindakan->qty ?? '1' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada tindakan medis.</span>
                                            @endif

                                            {{-- Billing & Tagihan --}}
                                            <h5 class="mt-3"><i class="fal fa-file-invoice-dollar text-purple"></i>
                                                Billing
                                                & Tagihan</h5>
                                            @if ($reg->bilingan && $reg->bilingan->tagihanPasien->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Deskripsi</th>
                                                            <th>Qty</th>
                                                            <th>Harga</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->bilingan->tagihanPasien as $bill)
                                                            <tr>
                                                                <td>{{ $bill->tagihan ?? 'N/A' }}</td>
                                                                <td>{{ $bill->qty ?? 'N/A' }}</td>
                                                                <td>{{ number_format($bill->nominal, 2, ',', '.') ?? 'N/A' }}
                                                                </td>
                                                                <td>{{ number_format($bill->wajib_bayar, 2, ',', '.') ?? 'N/A' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class="text-right">Total Tagihan:</th>
                                                            <th>{{ number_format($reg->bilingan->tagihanPasien->sum('wajib_bayar'), 2, ',', '.') }}
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada data tagihan.</span>
                                            @endif

                                            {{-- Resep --}}
                                            <h5 class="mt-3"><i class="fal fa-prescription-bottle-alt text-danger"></i>
                                                Resep Obat</h5>
                                            @if ($reg->order_resep && $reg->order_resep->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tgl Order</th>
                                                            <th>Nama Resep</th>
                                                            <th>Dosis</th>
                                                            <th>Aturan Pakai</th>
                                                            <th>Status</th>
                                                            <th>Petugas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->order_resep as $resep)
                                                            <tr>
                                                                <td>{{ $resep->created_at ? \Carbon\Carbon::parse($resep->created_at)->format('d-m-Y H:i') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $resep->nama_resep ?? 'N/A' }}</td>
                                                                <td>{{ $resep->dosis ?? 'N/A' }}</td>
                                                                <td>{{ $resep->aturan_pakai ?? 'N/A' }}</td>
                                                                <td>{{ $resep->status ?? 'N/A' }}</td>
                                                                <td>{{ $resep->petugas->fullname ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada resep obat.</span>
                                            @endif

                                            {{-- Gizi --}}
                                            <h5 class="mt-3"><i class="fal fa-utensils-alt text-warning"></i> Order Gizi
                                            </h5>
                                            @if ($reg->order_gizi && $reg->order_gizi->count())
                                                <table
                                                    class="table table-bordered table-striped table-sm examination-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tgl Order</th>
                                                            <th>Pola/Rincian Diet</th>
                                                            <th>Status</th>
                                                            <th>Petugas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reg->order_gizi as $gizi)
                                                            <tr>
                                                                <td>{{ $gizi->created_at ? \Carbon\Carbon::parse($gizi->created_at)->format('d-m-Y H:i') : 'N/A' }}
                                                                </td>
                                                                <td>{{ $gizi->pola_diet ?? ($gizi->rincian_diet ?? 'N/A') }}
                                                                </td>
                                                                <td>{{ $gizi->status ?? 'N/A' }}</td>
                                                                <td>{{ $gizi->pegawai->fullname ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span class="text-muted">Tidak ada order gizi.</span>
                                            @endif

                                            {{-- Rencana Kontrol BPJS --}}
                                            <h5 class="mt-3"><i class="fal fa-calendar-check text-success"></i> Rencana
                                                Kontrol BPJS</h5>
                                            @if ($reg->rencanaKontrol)
                                                <div>
                                                    <strong>No. Surat Kontrol:</strong>
                                                    {{ $reg->rencanaKontrol->noSuratKontrol ?? 'N/A' }}<br>
                                                    <strong>Tgl. Kontrol:</strong>
                                                    {{ $reg->rencanaKontrol->tglRencanaKontrol ? \Carbon\Carbon::parse($reg->rencanaKontrol->tglRencanaKontrol)->format('d-m-Y') : 'N/A' }}<br>
                                                    <strong>Poli Tujuan:</strong>
                                                    {{ $reg->rencanaKontrol->poliTujuan ?? 'N/A' }}
                                                </div>
                                            @else
                                                <span class="text-muted">Tidak ada rencana kontrol BPJS.</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info text-center">
                                    Pasien ini belum memiliki riwayat kunjungan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
@endsection