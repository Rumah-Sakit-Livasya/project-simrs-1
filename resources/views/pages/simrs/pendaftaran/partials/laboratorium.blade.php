{{-- BAGIAN 1: DAFTAR ORDER LABORATORIUM YANG SUDAH ADA --}}
<div class="panel" id="panel-laboratorium-list">
    <div class="panel-hdr">
        <h2>
            <i class="fal fa-notes-medical mr-2"></i> Daftar Order Laboratorium
        </h2>
        <div class="panel-toolbar">
            <button class="btn btn-primary btn-sm" id="btn-show-lab-form">
                <i class="fal fa-plus mr-1"></i> Buat Order Baru
            </button>
        </div>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            {{-- Tabel untuk menampilkan order yang sudah ada --}}
            <table id="dt-lab-orders" class="table table-bordered table-hover table-striped w-100">
                <thead class="bg-primary-600">
                    <tr>
                        <th>#</th>
                        <th>Detail</th>
                        <th>Tgl Order</th>
                        <th>No. Order</th>
                        <th>Dokter Lab</th>
                        <th>Tipe</th>
                        <th>Status Hasil</th>
                        <th>Status Billing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laboratoriumOrders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-placement="top"
                                    data-bs-toggle="popover" data-bs-title="Detail Order Laboratorium"
                                    data-bs-html="true" data-bs-content-id="popover-content-{{ $order->id }}">
                                    <i class="fas fa-list text-light" style="transform: scale(1.8)"></i>
                                </button>
                                <div class="display-none" id="popover-content-{{ $order->id }}">
                                    @include('pages.simrs.pendaftaran.partials.detail-order-laboratorium', [
                                        'order' => $order,
                                    ])
                                </div>
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
                                    <span class="badge badge-success">Sudah Ditagih</span>
                                @else
                                    <span class="badge badge-secondary">Belum Ditagih</span>
                                @endif
                            </td>
                            <td>
                                {{-- Tombol Aksi (Cetak Hasil, dll) --}}
                                <a href="#" class="btn btn-xs btn-outline-primary">
                                    <i class="fal fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- BAGIAN 2: FORM UNTUK MEMBUAT ORDER BARU (AWALNYA TERSEMBUNYI) --}}
<div class="panel" id="panel-laboratorium-form" style="display: none;">
    {{-- Kita akan @include form order baru di sini --}}
    @include('pages.simrs.pendaftaran.partials.order-laboratorium')
</div>


{{-- SCRIPT KHUSUS UNTUK TAB LABORATORIUM --}}
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk daftar order
        $('#dt-lab-orders').DataTable({
            responsive: true,
            "order": [
                [2, "desc"]
            ] // Urutkan berdasarkan tanggal order
        });

        // Inisialisasi Popover (jika masih menggunakan Bootstrap 4/SmartAdmin)
        $('[data-toggle="popover"]').popover();

        // Logika untuk menampilkan/menyembunyikan form
        $('#btn-show-lab-form').on('click', function() {
            $('#panel-laboratorium-list').hide();
            $('#panel-laboratorium-form').show();
        });

        // Event listener untuk tombol kembali di dalam form
        // Diletakkan di sini agar bisa mengakses kedua panel
        $(document).on('click', '.btn-back-to-lab-list', function() {
            $('#panel-laboratorium-form').hide();
            $('#panel-laboratorium-list').show();
        });
    });
</script>
