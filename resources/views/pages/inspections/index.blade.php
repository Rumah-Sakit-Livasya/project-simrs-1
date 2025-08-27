@extends('inc.layout')
@section('title', 'Riwayat Inspeksi Kendaraan')

@section('style')
    {{-- Style untuk UI/UX modal detail yang baru --}}
    <style>
        /* Style untuk highlight baris tabel yang bermasalah */
        .table-danger-light {
            background-color: #fbeae5 !important;
            --bs-table-accent-bg: #fbeae5 !important;
            /* Kompatibilitas Bootstrap 5 */
        }

        /* Hover effect untuk baris yang di-highlight */
        .table-hover tbody tr.table-danger-light:hover {
            background-color: #f8d7da !important;
            --bs-table-hover-bg: #f8d7da !important;
        }

        /* Style untuk icon foto di dalam tabel */
        .detail-photo-icon {
            font-size: 2rem;
            color: #007bff;
            cursor: pointer;
            transition: color 0.2s;
        }

        .detail-photo-icon:hover {
            color: #0056b3;
        }

        /* Style untuk gambar di modal foto */
        #photoModalImg {
            max-width: 100%;
            max-height: 70vh;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.15);
        }

        /* Memastikan konten tabel rata tengah secara vertikal */
        #detailResults td,
        #detailResults th {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Riwayat Inspeksi</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Riwayat Inspeksi Kesiapan Kendaraan</h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('vehicles.inspections.create') }}" class="btn btn-primary btn-sm">Buat
                                Inspeksi Baru</a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="inspection-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal Inspeksi</th>
                                        <th>Petugas</th>
                                        <th>Hasil Temuan</th>
                                        <th>Waktu Input</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Konten diisi oleh DataTables --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL DETAIL INSPEKSI (Struktur HTML) --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary-600 text-white align-items-center">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fal fa-clipboard-list-check mr-2"></i> Detail Inspeksi Kendaraan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 1;">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body px-4 py-3">
                    {{-- Info dasar inspeksi --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-info mr-2"><i class="fal fa-calendar-alt"></i></span>
                                <span>
                                    <strong>Tanggal Inspeksi:</strong>
                                    <span id="detailDate" class="ml-1"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-primary mr-2"><i class="fal fa-user"></i></span>
                                <span>
                                    <strong>Petugas:</strong>
                                    <span id="detailInspector" class="ml-1"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Hasil inspeksi per kendaraan akan di-render di sini oleh JavaScript --}}
                    <div id="detailResults">
                        {{-- Contoh: <div class="card">...</div> --}}
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fal fa-arrow-left mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UNTUK FOTO INSPEKSI --}}
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary-600 text-white align-items-center">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fal fa-image mr-2"></i> Dokumentasi Inspeksi
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 1;">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="photoModalImg" src="" alt="Dokumentasi Inspeksi" @class(['w-100', 'font-bold' => true])>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fal fa-arrow-left mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            const table = $('#inspection-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/api/internal/inspections') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'inspection_date',
                        name: 'inspection_date'
                    },
                    {
                        data: 'inspector.name',
                        name: 'inspector.name',
                        defaultContent: '<i>N/A</i>'
                    },
                    {
                        data: 'findings_count',
                        name: 'findings_count',
                        render: function(data, type, row) {
                            if (data > 0) {
                                return `<span class="badge badge-danger">${data} Temuan Rusak</span>`;
                            }
                            return '<span class="badge badge-success">Semua Baik</span>';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return new Date(data).toLocaleString('id-ID', {
                                dateStyle: 'medium',
                                timeStyle: 'short'
                            });
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-info btn-xs btn-detail" data-id="${data}" title="Lihat Detail">Detail</button>
                                <button class="btn btn-danger btn-xs btn-delete" data-id="${data}" title="Hapus Data">Hapus</button>
                            `;
                        }
                    }
                ],
                order: [
                    [0, 'desc'] // Urutkan berdasarkan ID terbaru secara default
                ]
            });

            // FUNGSI BARU (REFACTOR): Event listener untuk tombol Detail dengan UI/UX baru
            $('#inspection-datatable tbody').on('click', '.btn-detail', function() {
                const id = $(this).data('id');
                const button = $(this);
                // Tampilkan spinner di tombol untuk feedback
                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                );

                $.get(`/api/internal/inspections/${id}`, function(data) {
                    // Isi info dasar dengan format tanggal yang lebih baik
                    $('#detailDate').text(new Date(data.inspection.inspection_date)
                        .toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        }));
                    $('#detailInspector').text(data.inspection.inspector.name);

                    const resultsContainer = $('#detailResults');
                    resultsContainer.empty(); // Kosongkan konten sebelumnya

                    // Handle jika tidak ada hasil checklist sama sekali
                    if (Object.keys(data.results).length === 0) {
                        resultsContainer.html(
                            '<div class="alert alert-warning">Tidak ada checklist yang diisi untuk inspeksi ini.</div>'
                        );
                        $('#detailModal').modal('show');
                        return;
                    }

                    // Loop untuk setiap kendaraan dan buat Card + Tabel
                    for (const vehicleName in data.results) {
                        const items = data.results[vehicleName];

                        // Hitung temuan kerusakan untuk ringkasan di header card
                        const findingsCount = items.filter(item => item.status === 'Rusak').length;
                        let summaryBadge;
                        if (findingsCount > 0) {
                            summaryBadge =
                                `<span class="badge badge-danger"><i class="fal fa-exclamation-triangle mr-1"></i> Ditemukan ${findingsCount} Kerusakan</span>`;
                        } else {
                            summaryBadge =
                                `<span class="badge badge-success"><i class="fal fa-check-circle mr-1"></i> Semua Poin Baik</span>`;
                        }

                        // Mulai membangun HTML untuk Card kendaraan
                        let vehicleHtml = `
                            <div class="card shadow-sm mb-3 border">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 font-weight-bold">${vehicleName}</h5>
                                    ${summaryBadge}
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 30%;">Poin Pemeriksaan</th>
                                                    <th style="width: 15%;" class="text-center">Status</th>
                                                    <th style="width: 35%;">Catatan</th>
                                                    <th style="width: 20%;" class="text-center">Dokumentasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                        `;

                        // Loop untuk setiap item checklist dan buat baris tabel (<tr>)
                        items.forEach(item => {
                            const rowClass = item.status === 'Rusak' ?
                                'table-danger-light' : '';
                            const statusBadge = item.status === 'Rusak' ?
                                '<span class="badge badge-danger badge-pill">Rusak</span>' :
                                '<span class="badge badge-success badge-pill">Baik</span>';

                            const notes = item.notes ?
                                `<span class="text-muted small"><em>${item.notes}</em></span>` :
                                '<span class="text-muted small">-</span>';

                            // Ganti: Jangan langsung load gambar, tampilkan icon, klik icon baru tampil modal gambar
                            const photo = item.photo_path ?
                                `<span class="detail-photo-icon" data-photo="${item.photo_path}" title="Lihat Dokumentasi">
                                    <i class="fal fa-image"></i>
                                </span>` :
                                '<span class="text-muted small">-</span>';

                            vehicleHtml += `
                                <tr class="${rowClass}">
                                    <td>${item.item.name}</td>
                                    <td class="text-center">${statusBadge}</td>
                                    <td>${notes}</td>
                                    <td class="text-center">${photo}</td>
                                </tr>
                            `;
                        });

                        vehicleHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                        resultsContainer.append(vehicleHtml);
                    }

                    $('#detailModal').modal('show');

                }).always(function() {
                    // Selalu kembalikan tombol ke keadaan normal setelah AJAX selesai (baik sukses maupun gagal)
                    button.prop('disabled', false).html('Detail');
                });
            });

            // Event listener untuk klik icon foto (delegasi karena konten dinamis)
            $(document).on('click', '.detail-photo-icon', function() {
                const photoUrl = $(this).data('photo');
                if (photoUrl) {
                    $('#photoModalImg').attr('src', photoUrl);
                    $('#photoModal').modal('show');
                }
            });

            // Bersihkan src gambar saat modal ditutup agar tidak ada gambar lama
            $('#photoModal').on('hidden.bs.modal', function() {
                $('#photoModalImg').attr('src', '');
            });

            // Event listener untuk tombol Hapus
            $('#inspection-datatable tbody').on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data inspeksi ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/internal/inspections/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Terhapus!', response.message, 'success');
                                table.ajax.reload(); // Muat ulang data tabel
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error');
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
