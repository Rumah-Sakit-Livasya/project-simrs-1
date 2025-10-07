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
                                                <span class="badge badge-success">Payment (closed)</span>
                                            @else
                                                <span class="badge badge-secondary">Not Billed</span>
                                            @endif

                                        <td>
                                            @if (!Str::contains($order->registration_otc->registration_number ?? '', 'OTC'))
                                                <a href="{{ url('/daftar-registrasi-pasien/' . $order->registration->id) }}"
                                                    class="mdi mdi-account pointer mdi-24px text-primary user-btn"
                                                    title="Detail Pasien" data-id="{{ $order->id }}"></a>
                                            @endif

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
                                            <a class="mdi mdi-delete pointer mdi-24px text-danger delete-btn"
                                                title="Hapus Order" data-id="{{ $order->id }}"></a>
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

    @push('plugin-order-lab')
        <script>
            $(document).ready(function() {

                let table = $('#dt-lab-orders').DataTable({
                    "drawCallback": function(settings) {
                        $('#loading-spinner').hide();
                    },
                    responsive: true,
                    lengthChange: false,
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            titleAttr: 'Generate PDF',
                            className: 'btn-outline-danger btn-sm mr-1'
                        },
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            titleAttr: 'Generate Excel',
                            className: 'btn-outline-success btn-sm mr-1'
                        },
                        {
                            extend: 'csvHtml5',
                            text: 'CSV',
                            titleAttr: 'Generate CSV',
                            className: 'btn-outline-primary btn-sm mr-1'
                        },
                        {
                            extend: 'copyHtml5',
                            text: 'Copy',
                            titleAttr: 'Copy to clipboard',
                            className: 'btn-outline-primary btn-sm mr-1'
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            titleAttr: 'Print Table',
                            className: 'btn-outline-primary btn-sm'
                        }
                    ],
                    initComplete: function() {
                        // Inisialisasi Popovers setelah tabel selesai dimuat
                        $('[data-toggle="popover"]').each(function() {
                            var contentId = $(this).data('content-id');
                            var contentHtml = $('#' + contentId).html();
                            $(this).popover({
                                html: true,
                                content: contentHtml,
                                sanitize: false // Penting jika kontennya HTML
                            });
                        });
                    }
                });

                $('#dt-lab-orders tbody').on('click', '.delete-btn', function() {
                    var orderId = $(this).data('id');
                    var row = $(this).closest('tr'); // Ambil elemen <tr> dari baris yang akan dihapus

                    // Tampilkan konfirmasi menggunakan SweetAlert
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Order ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika user mengonfirmasi, kirim request AJAX
                            $.ajax({
                                url: `/simrs/laboratorium/order/${orderId}`, // Sesuaikan dengan URL route Anda
                                type: 'DELETE',
                                headers: {
                                    // Kirim CSRF token untuk keamanan
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Hapus baris dari DataTable
                                        table.row(row).remove().draw();

                                        // Tampilkan notifikasi sukses
                                        Swal.fire(
                                            'Dihapus!',
                                            response.message,
                                            'success'
                                        );
                                    } else {
                                        // Tampilkan notifikasi error dari server
                                        Swal.fire(
                                            'Gagal!',
                                            response.message,
                                            'error'
                                        );
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Tangani error AJAX (misal: server down, 404, dll)
                                    Swal.fire(
                                        'Error!',
                                        'Tidak dapat menghubungi server. Coba lagi nanti.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });

                // Event listener untuk membuka dan menutup detail
                $('#dt-lab-orders tbody').on('click', 'td.details-control', function() {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    var icon = $(this).find('i');
                    var detailData = JSON.parse(tr.attr('data-details'));
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('details-shown');
                        icon.removeClass('fa-minus-circle text-danger').addClass('fa-plus-circle text-success');
                    } else {
                        row.child(format(detailData)).show();
                        tr.addClass('details-shown');
                        icon.removeClass('fa-plus-circle text-success').addClass('fa-minus-circle text-danger');
                    }
                });

                function format(details) {
                    if (!details || details.length === 0) {
                        return '<div class="p-3 text-center">Tidak ada detail parameter untuk order ini.</div>';
                    }
                    let totalPrice = 0;
                    let table = `<table class="table table-sm table-striped table-bordered child-table">
                            <thead class="bg-info-50">
                                <tr>
                                    <th style="width: 30px;">#</th>
                                    <th>Parameter</th>
                                    <th>Harga</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>`;
                    details.forEach((item, index) => {
                        totalPrice += (parseFloat(item.nominal_rupiah) || 0);
                        const formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 2
                        }).format(item.nominal_rupiah || 0);
                        const parameterName = item.parameter_laboratorium ? item.parameter_laboratorium
                            .parameter : '<i class="text-muted">N/A</i>';
                        table += `<tr>
                            <td>${index + 1}</td>
                            <td>${parameterName}</td>
                            <td>${formattedPrice}</td>
                            <td>${item.catatan || ''}</td>
                          </tr>`;
                    });
                    table += '</tbody>';
                    const formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(totalPrice);
                    table += `<tfoot>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Total Biaya</td>
                            <td class="font-weight-bold">${formattedTotal}</td>
                            <td></td>
                        </tr>
                      </tfoot>`;
                    table += '</table>';
                    return table;
                }
            });
        </script>
    @endpush
