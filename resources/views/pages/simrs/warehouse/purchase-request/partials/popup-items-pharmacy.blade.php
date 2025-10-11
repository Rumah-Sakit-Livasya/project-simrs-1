<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Barang Farmasi</title>
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
            <h2>Pilih Barang Farmasi</h2>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" placeholder="Cari berdasarkan nama atau kode barang..."
                            class="form-control">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th class="text-right">HNA</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangs as $barang)
                                <tr class="item-row">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $barang->kode }}</td>
                                    <td>{{ $barang->nama }}</td>
                                    <td>{{ $barang->satuan->nama ?? '' }}</td>
                                    <td class="text-right">{{ rp($barang->hna) }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary choose-item-btn"
                                            data-item='{{ json_encode($barang) }}'>
                                            <i class="fal fa-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data barang farmasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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

                if (window.opener && !window.opener.closed && typeof window.opener.addItemFromPopup ===
                    'function') {
                    // Panggil fungsi di halaman utama (opener)
                    window.opener.addItemFromPopup(itemObject);
                } else {
                    alert(
                        'Tidak dapat terhubung kembali ke halaman utama. Jangan tutup halaman utama saat memilih item.'
                        );
                }
                // Anda bisa memilih untuk menutup popup setelah memilih atau tidak
                // window.close();
            });

            // Fungsi pencarian
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
