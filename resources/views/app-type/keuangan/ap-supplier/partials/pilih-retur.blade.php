@extends('inc.layout-no-side')
@section('title', 'Pilih Retur Barang')

@push('styles')
    <style>
        /* Style dasar agar konsisten */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .panel {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            border: none;
        }

        .panel-hdr {
            background-color: #6a1b9a;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .panel-hdr h2 {
            font-size: 1rem;
        }

        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .table {
            font-size: 0.8rem;
        }

        .btn-primary {
            background-color: #6a1b9a;
            border-color: #6a1b9a;
        }

        .btn-primary:hover {
            background-color: #4a148c;
            border-color: #4a148c;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content p-3">
        <div class="panel">
            <div class="panel-hdr">
                <h2>Pilih Retur Barang</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- Form Pencarian -->
                    <form action="{{ route('keuangan.ap-supplier.pilihRetur') }}" method="get" class="mb-3">
                        <input type="hidden" name="supplier_id" value="{{ $supplierId }}">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="search_kode"
                                placeholder="Cari berdasarkan Kode Retur..." value="{{ request('search_kode') }}">
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Data Retur -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <thead class="bg-primary-600">
                                <tr>
                                    <th class="text-center" style="width: 5%;">
                                        <input type="checkbox" id="check-all">
                                    </th>
                                    <th>Tgl Retur</th>
                                    <th>Kode Retur</th>
                                    {{-- KOLOM BARU --}}
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Total Qty</th>
                                    <th class="text-right">Total Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($availableReturs as $retur)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="retur-checkbox" data-id="{{ $retur->id }}"
                                                data-nominal="{{ $retur->nominal }}">
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d M Y') }}</td>
                                        <td>{{ $retur->kode_retur }}</td>

                                        {{-- TAMPILKAN DATA YANG SUDAH DIGABUNG DARI CONTROLLER --}}
                                        <td>{!! $retur->item_codes !!}</td>
                                        <td>{!! $retur->item_names !!}</td>
                                        <td class="text-center">{{ $retur->total_qty }}</td>

                                        <td class="text-right font-weight-bold">
                                            {{ number_format($retur->nominal, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Sesuaikan colspan menjadi 7 --}}
                                        <td colspan="7" class="text-center text-muted">Tidak ada retur yang tersedia
                                            untuk supplier ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary mr-2" onclick="window.close();">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btn-pilih-retur">Pilih Retur Terpilih</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        $(document).ready(function() {
            // Fungsi untuk check/uncheck semua
            $('#check-all').on('click', function() {
                $('.retur-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Fungsi tombol "Pilih"
            $('#btn-pilih-retur').on('click', function() {
                let selectedData = [];

                // Kumpulkan data dari checkbox yang terpilih
                $('.retur-checkbox:checked').each(function() {
                    selectedData.push({
                        id: $(this).data('id'),
                        nominal: parseFloat($(this).data('nominal')) || 0
                    });
                });

                if (selectedData.length === 0) {
                    alert('Silakan pilih minimal satu retur.');
                    return;
                }

                // Kirim data kembali ke halaman utama (parent window)
                window.opener.postMessage({
                    type: 'RETUR_SELECTED',
                    data: selectedData
                }, window.location.origin);

                // Tutup jendela popup
                window.close();
            });
        });
    </script>
@endsection
