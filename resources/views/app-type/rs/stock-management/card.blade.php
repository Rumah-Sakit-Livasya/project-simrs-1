@extends('inc.layout')
@section('title', 'Kartu Stok: ' . $item->item_name)

@section('extended-css')
    <link rel="stylesheet" href="/js/datagrid/datatables/datatables.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Breadcrumb --}}
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item">RS</li>
            <li class="breadcrumb-item">
                <a href="{{ route('stock-management.index') }}">Manajemen Stok Proyek</a>
            </li>
            <li class="breadcrumb-item active">Kartu Stok: {{ $item->item_name }}</li>
        </ol>
        <div class="panel">
            <div class="panel-hdr d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="mb-0">
                        Kartu Stok: {{ $item->item_name }} ({{ $item->item_code }})
                    </h2>
                    <div class="text-muted small mt-1">
                        Satuan: {{ $item->unit ?? '-' }}
                    </div>
                </div>
                <a href="{{ route('stock-management.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fal fa-arrow-left mr-1"></i> Kembali ke Manajemen Stok
                </a>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    {{-- Info stok total per gudang --}}
                    @php
                        /** @var \App\Models\RS\ProjectBuildItem $item */
                        $currentStocks = $item->currentStocks()->with('warehouseMasterGudang')->get();
                    @endphp
                    <div class="mb-3">
                        <h5>Jumlah Stok Saat Ini per Gudang:</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Gudang</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($currentStocks as $stock)
                                        <tr>
                                            <td>
                                                {{ $stock->warehouseMasterGudang->kode ?? '-' }} -
                                                {{ $stock->warehouseMasterGudang->nama ?? '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($stock->quantity, 2) }} {{ $item->unit ?? '' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Belum ada stok tercatat</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr />

                    <h5 class="mb-3">Riwayat Transaksi Stok</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Deskripsi</th>
                                    <th>Gudang</th>
                                    <th>Qty</th>
                                    <th>Stok Awal</th>
                                    <th>Stok Akhir</th>
                                    <th>Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ledgers as $ledger)
                                    <tr>
                                        <td>{{ $ledger->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            @if ($ledger->type == 'in')
                                                <span class="badge badge-success">MASUK</span>
                                            @else
                                                <span class="badge badge-danger">KELUAR</span>
                                            @endif
                                        </td>
                                        <td>{{ $ledger->description }}</td>
                                        <td>
                                            {{ $ledger->gudang->kode ?? '-' }} -
                                            {{ $ledger->gudang->nama_gudang ?? ($ledger->gudang->nama ?? '-') }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($ledger->quantity, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($ledger->stock_before, 2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($ledger->stock_after, 2) }}
                                        </td>
                                        <td>{{ $ledger->user->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada transaksi stok untuk
                                            item ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination Links --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $ledgers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
