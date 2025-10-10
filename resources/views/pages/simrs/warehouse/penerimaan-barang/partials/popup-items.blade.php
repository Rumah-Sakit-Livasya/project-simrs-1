<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Barang Retur - {{ $supplier->nama ?? 'Supplier' }}</title>
    {{-- Memuat CSS dasar dari template Anda --}}
    <link rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <style>
        body {
            padding: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="panel">
        <div class="panel-hdr">
            <h2>Pilih Barang dari Supplier: <span class="fw-300"><i>{{ $supplier->nama ?? '' }}</i></span></h2>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput"
                            placeholder="Cari berdasarkan nama barang, no faktur, kode terima..." class="form-control">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>#</th>
                                <th>Tgl Terima</th>
                                <th>No Faktur</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sbs as $barang)
                                <tr class="item-row">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="tgl-terima">{{ tgl($barang->pbi->pb->tanggal_terima) }}</td>
                                    <td class="no-faktur">{{ $barang->pbi->pb->no_faktur }}</td>
                                    <td class="nama-barang">{{ $barang->pbi->nama_barang }}</td>
                                    <td class="text-center">{{ $barang->qty }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary choose-item-btn"
                                            data-item='{{ json_encode($barang) }}'>
                                            <i class="fal fa-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada item yang dapat diretur untuk
                                        supplier ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Memuat JS dasar dan jQuery --}}
    <script src="/js/vendors.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Event listener untuk tombol "Pilih"
            $('.choose-item-btn').on('click', function() {
                const itemDataString = $(this).attr('data-item');
                if (!itemDataString) {
                    alert('Data item tidak ditemukan!');
                    return;
                }
                const itemObject = JSON.parse(itemDataString);

                // Memastikan window opener (halaman utama) dan fungsinya ada
                if (window.opener && !window.opener.closed && typeof window.opener.addItemFromPopup ===
                    'function') {
                    // Panggil fungsi di halaman utama untuk menambahkan item
                    window.opener.addItemFromPopup(itemObject);
                    // Tutup jendela popup ini
                    // window.close();
                } else {
                    alert(
                        'Tidak dapat terhubung kembali ke halaman utama. Jangan tutup halaman utama saat memilih item.'
                        );
                }
            });

            // Fungsi pencarian sederhana
            $('#searchInput').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('.item-row').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</body>

</html>
