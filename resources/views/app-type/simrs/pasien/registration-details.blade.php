{{-- Asesmen & Diagnosis (CPPT) --}}
<h5 class="mt-3 mb-3">
    <i class="fal fa-stethoscope text-primary mr-2"></i> Asesmen & Diagnosis (CPPT)
</h5>
@if ($registration->cppt && $registration->cppt->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Tanggal</th>
                    <th>Profesi</th>
                    <th>SOAP</th>
                    <th>Diagnosis</th>
                    <th>Instruksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registration->cppt as $item)
                    <tr>
                        <td>
                            <small>{{ $item->created_at ? $item->created_at->format('d-m-Y H:i') : 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $item->profesi ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="mb-2">
                                <strong class="text-primary">S:</strong>
                                <span class="text-muted">{{ $item->subjective ?? '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-success">O:</strong>
                                <span class="text-muted">{{ $item->objective ?? '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong class="text-warning">A:</strong>
                                <span class="text-muted">{{ $item->assessment ?? '-' }}</span>
                            </div>
                            <div>
                                <strong class="text-danger">P:</strong>
                                <span class="text-muted">{{ $item->planning ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($item->diagnosis)
                                <span class="badge badge-primary">{{ $item->diagnosis }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item->instruksi ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i> Tidak ada data asesmen untuk kunjungan ini.
    </div>
@endif

{{-- Tindakan Medis --}}
<h5 class="mt-4 mb-3">
    <i class="fal fa-procedures text-success mr-2"></i> Tindakan Medis
</h5>
@if ($registration->order_tindakan_medis && $registration->order_tindakan_medis->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead class="bg-success text-white">
                <tr>
                    <th>Tanggal Order</th>
                    <th>Nama Tindakan</th>
                    <th>Pelaksana</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registration->order_tindakan_medis as $tindakan)
                    <tr>
                        <td>
                            <small>{{ $tindakan->created_at ? $tindakan->created_at->format('d-m-Y H:i') : 'N/A' }}</small>
                        </td>
                        <td>{{ $tindakan->nama_tindakan ?? 'N/A' }}</td>
                        <td>{{ $tindakan->pelaksana->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-primary">{{ $tindakan->qty ?? '1' }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match ($tindakan->status ?? 'pending') {
                                    'completed' => 'success',
                                    'in_progress' => 'warning',
                                    'pending' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">
                                {{ ucfirst($tindakan->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>{{ $tindakan->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i> Tidak ada tindakan medis untuk kunjungan ini.
    </div>
@endif

{{-- Pemeriksaan Laboratorium --}}
<h5 class="mt-4 mb-3">
    <i class="fal fa-flask text-warning mr-2"></i> Pemeriksaan Laboratorium
</h5>
@if ($registration->order_laboratorium && $registration->order_laboratorium->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead class="bg-warning text-white">
                <tr>
                    <th>Tanggal Order</th>
                    <th>No. Order</th>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registration->order_laboratorium as $lab)
                    <tr>
                        <td>
                            <small>{{ $lab->created_at ? $lab->created_at->format('d-m-Y H:i') : 'N/A' }}</small>
                        </td>
                        <td>{{ $lab->order_number ?? '-' }}</td>
                        <td>{{ $lab->nama_pemeriksaan ?? 'N/A' }}</td>
                        <td>
                            @if ($lab->hasil)
                                <span class="text-primary font-weight-bold">{{ $lab->hasil }}</span>
                            @else
                                <span class="text-muted">Belum ada hasil</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = match ($lab->status ?? 'pending') {
                                    'completed' => 'success',
                                    'processing' => 'warning',
                                    'pending' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">
                                {{ ucfirst($lab->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('order-lab.show', $lab->id) }}" class="btn btn-xs btn-info"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i> Tidak ada pemeriksaan laboratorium untuk kunjungan ini.
    </div>
@endif

{{-- Pemeriksaan Radiologi --}}
<h5 class="mt-4 mb-3">
    <i class="fal fa-x-ray text-info mr-2"></i> Pemeriksaan Radiologi
</h5>
@if ($registration->order_radiologi && $registration->order_radiologi->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead class="bg-info text-white">
                <tr>
                    <th>Tanggal Order</th>
                    <th>No. Order</th>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registration->order_radiologi as $rad)
                    <tr>
                        <td>
                            <small>{{ $rad->created_at ? $rad->created_at->format('d-m-Y H:i') : 'N/A' }}</small>
                        </td>
                        <td>{{ $rad->order_number ?? '-' }}</td>
                        <td>{{ $rad->nama_pemeriksaan ?? 'N/A' }}</td>
                        <td>
                            @if ($rad->hasil)
                                <span class="text-primary font-weight-bold">{{ $rad->hasil }}</span>
                            @else
                                <span class="text-muted">Belum ada hasil</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = match ($rad->status ?? 'pending') {
                                    'completed' => 'success',
                                    'processing' => 'warning',
                                    'pending' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">
                                {{ ucfirst($rad->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('order-radiology.show', $rad->id) }}" class="btn btn-xs btn-info"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i> Tidak ada pemeriksaan radiologi untuk kunjungan ini.
    </div>
@endif

{{-- Quick Stats untuk Kunjungan Ini --}}
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $registration->order_tindakan_medis->count() }}</h3>
                <small>Tindakan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $registration->cppt->count() }}</h3>
                <small>Asesmen</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $registration->order_laboratorium->count() }}</h3>
                <small>Lab</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $registration->order_radiologi->count() }}</h3>
                <small>Radiologi</small>
            </div>
        </div>
    </div>
</div>
