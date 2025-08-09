@extends('inc.layout')
@section('title', 'Laporan Internal IT')

@section('extended-css')
    <style>
        /* Add to your extended-css section */
        .modal-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .timeline-inputs .input-group-text {
            min-width: 80px;
            justify-content: center;
        }

        .timeline-inputs .form-control {
            border-left: 0;
        }

        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            transition: all 0.5s ease;
        }

        .notification-toast.hide {
            transform: translateX(150%);
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            @can('filter laporan internal')
                @include('pages.laporan-internal.partials.filter')
            @endcan
            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-2" class="panel">
                        <div class="panel-hdr">
                            <h2>Laporan Internal IT</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row mb-3">
                                    <div class="col">
                                        @can('create laporan internal')
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#tambah-data">
                                                <i class="fas fa-plus me-1"></i> Tambah Laporan
                                            </button>
                                        @endcan
                                        @can('export excel laporan internal')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#exportModal">
                                                <i class="fas fa-file-excel mr-2"></i>Download Harian
                                            </button>
                                        @endcan
                                        @can('export pptx laporan internal')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#exportPPTXModal">
                                                <i class="fas fa-file-excel mr-2"></i>Download Laporan Bulanan
                                            </button>
                                        @endcan
                                        @can('import laporan internal')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#importLaporan">
                                                <i class="fas fa-file-excel mr-2"></i>Import Laporan
                                            </button>
                                        @endcan

                                        <!-- Tombol Export Word Harian -->
                                        @can('export word laporan internal')
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                                data-target="#exportWordModal">
                                                <i class="fas fa-file-word mr-2"></i> Download Word Harian
                                            </button>
                                        @endcan

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped w-100" id="laporanTable">
                                        <thead class="bg-primary-50">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Uraian</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                {{-- <th>Dokumentasi</th> --}}
                                                @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)')
                                                    <th>Jam Masuk</th>
                                                    <th>Jam Diproses</th>
                                                    <th>Respon Time</th>
                                                @endif
                                                <th>User</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.laporan-internal.partials.modal.create')
    @include('pages.laporan-internal.partials.modal.edit')
    @include('pages.laporan-internal.partials.modal.dokumentasi')
    @include('pages.laporan-internal.partials.modal.export-word')
    @include('pages.laporan-internal.partials.modal.export-excel')
    @include('pages.laporan-internal.partials.modal.export-pptx')
    @include('pages.laporan-internal.partials.modal.import')
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        // SweetAlert2 Toast Configuration
        // const Toast = Swal.mixin({
        //     toast: true,
        //     position: 'top-end',
        //     showConfirmButton: false,
        //     timer: 3000,
        //     timerProgressBar: true,
        //     didOpen: (toast) => {
        //         toast.addEventListener('mouseenter', Swal.stopTimer);
        //         toast.addEventListener('mouseleave', Swal.resumeTimer);
        //     }
        // });

        function showToast(message, icon = 'success') {
            Toast.fire({
                icon: icon,
                title: message
            });
        }

        $(document).ready(function() {
            // Initialize select2
            $('#create-form-laporan .select2').select2({
                dropdownParent: $('#tambah-data')
            });
            $('#edit-form-laporan .select2').select2({
                dropdownParent: $('#ubah-data')
            });

            $(function() {
                $('.select3').select2();
                $('#pic').select2({
                    placeholder: 'Pilih data berikut',
                    dropdownParent: $('#exportWordModal'),
                    allowClear: true
                });
            });

            // Show/hide organization field based on jenis selection
            $('#create-jenis').change(function() {
                if ($(this).val() === 'kendala') {
                    $('#create-organization-field').show();
                    $('input[name="unit_terkait"]').remove();
                } else {
                    $('#create-organization-field').hide();
                    if (!$('input[name="unit_terkait"]').length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'unit_terkait',
                            value: '{{ auth()->user()->employee->organization_id }}'
                        }).appendTo('form');
                    }
                }
            });

            $('#edit-jenis').change(function() {
                if ($(this).val() === 'kendala') {
                    $('#edit-organization-field').show();
                    $('input[name="unit_terkait"]').remove();
                } else {
                    $('#edit-organization-field').hide();
                    if (!$('input[name="unit_terkait"]').length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'unit_terkait',
                            value: '{{ auth()->user()->employee->organization_id }}'
                        }).appendTo('form');
                    }
                }
            });

            // Show/hide keterangan field based on status selection
            $('#edit-status').change(function() {
                if ($(this).val() === 'ditunda' || $(this).val() === 'ditolak') {
                    $('#edit-keterangan-field').show();
                } else {
                    $('#edit-keterangan-field').hide();
                }
            });

            $('#create-status').change(function() {
                if ($(this).val() === 'ditunda' || $(this).val() === 'ditolak') {
                    $('#create-keterangan-field').show();
                } else {
                    $('#create-keterangan-field').hide();
                }
            });

            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Set today's date as default
            $('.datepicker').datepicker('setDate', new Date());

            // Initialize DataTable with local language config
            // var table = $('#laporanTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: '/laporan-internal-list',
            //         type: 'GET',
            //         data: function(d) {
            //             console.log(d);

            //             // Tambahkan data filter ke request
            //             d.user_id = $('#filter-user').val();
            //             d.jenis = $('#filter-jenis').val();
            //             d.status = $('#filter-status').val();
            //             d.tanggal = $('#filter-tanggal').val();
            //             d.organization = {{ auth()->user()->employee->organization->id }};
            //         },
            //         error: function(xhr) {
            //             console.error('DataTables error:', xhr.responseText);
            //             showToast('Gagal memuat data laporan', 'error');
            //         }
            //     },
            //     columns: [{
            //             data: null,
            //             name: 'no',
            //             orderable: false,
            //             render: function(data, type, row, meta) {
            //                 return meta.row + meta.settings._iDisplayStart + 1;
            //             }
            //         },
            //         {
            //             data: 'tanggal',
            //             name: 'tanggal',
            //             render: function(data) {
            //                 return new Date(data).toLocaleDateString('id-ID', {
            //                     weekday: 'long',
            //                     year: 'numeric',
            //                     month: 'long',
            //                     day: 'numeric'
            //                 });
            //             }
            //         },
            //         {
            //             data: 'jenis',
            //             name: 'jenis',
            //             render: function(data) {
            //                 const badgeClass = data === 'kegiatan' ? 'bg-success' : 'bg-warning';
            //                 const displayText = data === 'kegiatan' ? 'Kegiatan' : 'Kendala';
            //                 return `<span class="badge ${badgeClass}">${displayText}</span>`;
            //             }
            //         },
            //         {
            //             data: 'kegiatan',
            //             name: 'kegiatan'
            //         },
            //         {
            //             data: 'status',
            //             name: 'status',
            //             render: function(data) {
            //                 let badgeClass = 'bg-secondary';
            //                 if (data == 'selesai') badgeClass = 'bg-success';
            //                 if (data == 'diproses') badgeClass = 'bg-primary';
            //                 if (data == 'baru') badgeClass = 'bg-info';
            //                 if (data == 'ditolak') badgeClass = 'bg-danger';
            //                 return `<span class="badge ${badgeClass}">${data}</span>`;
            //             }
            //         },
            //         {
            //             data: 'keterangan',
            //             name: 'keterangan',
            //             render: function(data) {
            //                 return data ?? '-';
            //             }
            //         },
            //         @if (auth()->user()->employee->organization->name == 'Informasi Teknologi (IT)')
            //             {
            //                 data: 'jam_masuk',
            //                 name: 'jam_masuk',
            //                 render: function(data) {
            //                     if (!data) return '-';
            //                     try {
            //                         // Jika format waktu sudah HH:MM:SS
            //                         if (typeof data === 'string' && data.match(
            //                                 /^\d{2}:\d{2}:\d{2}$/)) {
            //                             return data;
            //                         }
            //                         // Jika format timestamp
            //                         return new Date(data).toLocaleTimeString('id-ID', {
            //                             hour: '2-digit',
            //                             minute: '2-digit',
            //                             second: '2-digit',
            //                             hour12: false
            //                         });
            //                     } catch (e) {
            //                         return '<span class="text-warning">Invalid</span>';
            //                     }
            //                 }
            //             }, {
            //                 data: 'jam_diproses',
            //                 name: 'jam_diproses',
            //                 render: function(data) {
            //                     if (!data) return '-';
            //                     try {
            //                         if (typeof data === 'string' && data.match(
            //                                 /^\d{2}:\d{2}:\d{2}$/)) {
            //                             return data;
            //                         }
            //                         return new Date(data).toLocaleTimeString('id-ID', {
            //                             hour: '2-digit',
            //                             minute: '2-digit',
            //                             second: '2-digit',
            //                             hour12: false
            //                         });
            //                     } catch (e) {
            //                         return '<span class="text-warning">Invalid</span>';
            //                     }
            //                 }
            //             }, {
            //                 data: null,
            //                 name: 'respon_time',
            //                 render: function(data, type, row) {
            //                     // Hitung waktu respon dari jam_masuk ke jam_diproses
            //                     if (row.jam_masuk && row.jam_diproses) {
            //                         try {
            //                             const start = this.parseTime(row.jam_masuk);
            //                             const respon = this.parseTime(row.jam_diproses);

            //                             if (respon < start) {
            //                                 return '<span class="text-danger">Invalid</span>';
            //                             }

            //                             const diff = Math.abs(respon - start);
            //                             return this.formatDuration(diff);
            //                         } catch (e) {
            //                             console.error('Error calculating response time:', e);
            //                             return '<span class="text-warning">Error</span>';
            //                         }
            //                     }
            //                     return '-';
            //                 }
            //             },
            //         @endif {
            //             data: 'fullname',
            //             name: 'fullname',
            //             render: function(data) {
            //                 return data ?? '-';
            //             }
            //         },
            //         {
            //             data: 'id',
            //             name: 'action',
            //             orderable: false,
            //             searchable: false,
            //             render: function(data, type, row) {
            //                 // Tombol default (edit dan delete)
            //                 let buttons = `
        //                             <div class="btn-group">
        //                                 <button class="btn btn-sm btn-icon btn-primary" onclick="editLaporan(${data})">
        //                                     <i class="fas fa-edit"></i>
        //                                 </button>
        //                                 @can('delete laporan internal')
        //                                     <button class="btn btn-sm btn-icon btn-danger" onclick="deleteLaporan(${data})">
        //                                         <i class="fas fa-trash"></i>
        //                                     </button>
        //                                 @endcan
        //                             `;

            //                 // Tambahkan tombol dokumentasi jika ada
            //                 if (row.dokumentasi && !isNumeric(row.dokumentasi)) {
            //                     buttons += `
        //                                 <button class="btn btn-sm btn-icon btn-info btn-show-dokumentasi"
        //                                         data-file="${row.dokumentasi.startsWith('http') ? row.dokumentasi : assetUrl(row.dokumentasi)}">
        //                                     <i class="fas fa-eye"></i>
        //                                 </button>
        //                             `;
            //                 }

            //                 // Tambahkan tombol checklist jika status Diproses atau Ditolak
            //                 if (row.status === 'diproses') {
            //                     buttons += `
        //                                     <button class="btn btn-sm btn-icon btn-success" onclick="completeLaporan(${data})" title="Tandai Selesai">
        //                                         <i class="fas fa-check"></i>
        //                                     </button>
        //                                 `;
            //                 }

            //                 buttons += `</div>`;
            //                 return buttons;
            //             }
            //         }
            //     ],
            //     // Tambahkan fungsi helper di luar columns
            //     createdRow: function(row, data, dataIndex) {
            //         // Helper functions untuk digunakan dalam render
            //         $.fn.dataTable.render.formatDuration = function(diff) {
            //             const hours = Math.floor(diff / (1000 * 60 * 60));
            //             const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            //             const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            //             let durationText = '';
            //             if (hours > 0) durationText += `${hours} jam `;
            //             if (minutes > 0) durationText += `${minutes} menit `;
            //             if (seconds > 0 || (hours === 0 && minutes === 0)) durationText +=
            //                 `${seconds} detik`;

            //             return durationText.trim();
            //         };

            //         $.fn.dataTable.render.parseTime = function(timeStr) {
            //             // Handle both timestamp and HH:MM:SS format
            //             if (typeof timeStr === 'string' && timeStr.match(/^\d{2}:\d{2}:\d{2}$/)) {
            //                 const [hours, minutes, seconds] = timeStr.split(':');
            //                 const date = new Date();
            //                 date.setHours(hours, minutes, seconds);
            //                 return date;
            //             }
            //             return new Date(timeStr);
            //         };
            //     },
            //     language: {
            //         "decimal": "",
            //         "emptyTable": "Tidak ada data yang tersedia",
            //         "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            //         "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            //         "infoFiltered": "(disaring dari _MAX_ total data)",
            //         "infoPostFix": "",
            //         "thousands": ",",
            //         "lengthMenu": "Tampilkan _MENU_ data",
            //         "loadingRecords": "Memuat...",
            //         "processing": "Memproses...",
            //         "search": "Cari:",
            //         "zeroRecords": "Tidak ditemukan data yang sesuai",
            //         "paginate": {
            //             "first": "Pertama",
            //             "last": "Terakhir",
            //             "next": "Selanjutnya",
            //             "previous": "Sebelumnya"
            //         }
            //     },
            //     responsive: true
            // });

            // Helper function to check if value is numeric
            function isNumeric(n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            }

            // Helper function to get asset URL
            function assetUrl(path) {
                return path.startsWith('/') ? path : '/' + path;
            }

            $('#filter-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah pengiriman form default

                // Reload DataTable dengan filter
                table.ajax
                    .reload(); // Ini akan memanggil URL yang ditentukan di ajax.url dan mengirimkan data filter
            });


            // Form submission with file upload support
            $('#create-form-laporan').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                // Create FormData object to handle file upload
                const formData = new FormData(this);

                // Get CSRF token from meta tag
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/laporan-internal',
                    method: 'POST',
                    data: formData,
                    processData: false, // Important for file upload
                    contentType: false, // Important for file upload
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        showToast(response.message);
                        $('#create-form-laporan')[0].reset();
                        $('#tambah-data').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            'Gagal menyimpan data';
                        showToast(errorMessage, 'error');

                        // If there are validation errors, display them
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                const errorMessage = errors[field][0];
                                $(`#${field}-error`).text(errorMessage).show();
                            }
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Laporan');
                    }
                });
            });

            $('#import-form-laporan').on('submit', function(e) {
                e.preventDefault();

                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Mengunggah...');

                // Bersihkan pesan error sebelumnya
                $('#file-error').hide().text('');

                const formData = new FormData(this);
                console.log(formData);

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/laporan-internal/import',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        showToast(response.message || 'Data berhasil diimpor!');
                        $('#import-form-laporan')[0].reset();
                        $('#importLaporan').modal('hide');
                        if (typeof table !== 'undefined') {
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Gagal mengimpor data.';
                        showToast(message, 'error');

                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.file) {
                                $('#file-error').text(errors.file[0]).show();
                            }
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-file-import me-1"></i> Import Excel');
                    }
                });
            });


            $('#edit-form-laporan').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                // Ambil ID laporan
                const laporanId = $('#edit-laporan-id').val();
                console.log("Laporan ID: " + laporanId);

                // Buat FormData objek untuk menangani file upload
                const formData = new FormData(this); // this refers to the form element

                // Debugging: Periksa semua data yang ada di FormData
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ": " + pair[1]);
                }

                // Ambil CSRF token dari meta tag
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: `/laporan-internal/${laporanId}`, // URL untuk PUT dengan ID di URL
                    method: 'POST',
                    data: formData,
                    processData: false, // Jangan proses data
                    contentType: false, // Jangan set contentType, biar FormData mengatur sendiri
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        showToast(response.message);
                        $('#ubah-data').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        $('#ubah-data').modal('hide');
                        const errorMessage = xhr.responseJSON?.message ||
                            'Gagal menyimpan data';
                        showToast(errorMessage, 'error');

                        // Jika ada error validasi, tampilkan pesan kesalahan
                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                const errorMessage = errors[field][0];
                                $(`#${field}-error`).text(errorMessage).show();
                            }
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Simpan Laporan');
                    }
                });
            });

            // Reset form when modal is closed
            $('#tambah-data').on('hidden.bs.modal', function() {
                $('#create-form-laporan')[0].reset();
                $('.select2').val(null).trigger('change');
                $('.datepicker').datepicker('setDate', new Date());
                $('#create-organization-field').hide();
            });
        });

        // Show Dokumentasi
        $(document).on('click', '.btn-show-dokumentasi', function() {
            const rawFileUrl = $(this).data('file');

            // Reset modal state
            $('#noDocument').hide();
            $('#dokumentasiImage').hide().attr('src', '');
            $('#dokumentasiPdf').hide();
            $('#pdfViewer').attr('src', '');
            $('#downloadDokumentasi').hide().attr('href', '#');
            $('#unsupportedFormat').hide();
            $('#invalidUrl').hide();

            // Validasi dasar URL
            if (!rawFileUrl || typeof rawFileUrl !== 'string' || rawFileUrl.trim() === '') {
                $('#noDocument').show();
                $('#dokumentasiModal').modal('show');
                return;
            }

            try {
                let fileUrl;
                let extension;

                // Coba parse sebagai URL lengkap
                try {
                    const url = new URL(rawFileUrl);
                    fileUrl = url.toString();
                    const pathname = url.pathname;
                    extension = pathname.split('.').pop().toLowerCase().split(/[#?]/)[0];
                }
                // Jika gagal, anggap sebagai path relatif
                catch (e) {
                    // Handle relative paths
                    if (rawFileUrl.startsWith('storage/') || rawFileUrl.startsWith('/storage/')) {
                        fileUrl = assetUrl(rawFileUrl);
                    } else {
                        fileUrl = assetUrl('storage/' + rawFileUrl);
                    }

                    // Extract extension from relative path
                    const pathParts = rawFileUrl.split('.');
                    extension = pathParts.length > 1 ? pathParts.pop().toLowerCase().split(/[#?]/)[0] : '';
                }

                // Validasi ekstensi file
                const supportedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                if (!supportedExtensions.includes(extension)) {
                    $('#unsupportedFormat').show();
                    $('#dokumentasiModal').modal('show');
                    return;
                }

                // Tampilkan viewer sesuai tipe file
                if (extension === 'pdf') {
                    $('#pdfViewer').attr('src', fileUrl);
                    $('#dokumentasiPdf').show();
                } else {
                    $('#dokumentasiImage').attr('src', fileUrl).show();
                }

                $('#downloadDokumentasi').attr('href', fileUrl).show();
                $('#dokumentasiModal').modal('show');
            } catch (e) {
                console.error('Error showing documentation:', e);
                $('#invalidUrl').show();
                $('#dokumentasiModal').modal('show');
            }
        });

        // Export Word form submission
        $('#exportWordForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action') + '?' + form.serialize();

            window.open(url, '_blank');
            $('#exportWordModal').modal('hide');
        });


        // Export form submission
        $('#exportForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action') + '?' + form.serialize();

            // Open download in new tab
            window.open(url, '_blank');

            // Close modal
            $('#exportModal').modal('hide');
        });

        // Export form submission
        $('#exportPPTXForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action') + '?' + form.serialize();

            // Open download in new tab
            window.open(url, '_blank');

            // Close modal
            $('#exportPPTXModal').modal('hide');
        });

        // Helper function untuk path relatif
        function assetUrl(path) {
            // Hapus slash di awal jika ada
            const cleanPath = path.startsWith('/') ? path.substring(1) : path;
            return window.location.origin + '/' + cleanPath;
        }

        function formatTime(time) {
            // Jika waktu memiliki format "HH:MM:SS", ambil hanya "HH:MM"
            return time ? time.substring(0, 5) : '';
        }

        function editLaporan(id) {
            // Misalkan id digunakan untuk mengambil data tertentu atau mengubah konten di modal
            // Anda bisa menggunakan id ini untuk mengisi data ke dalam form modal jika diperlukan

            // Contoh mengambil data dari server menggunakan id dan menampilkannya di modal (menggunakan jQuery untuk simplicity)
            $.ajax({
                url: `/laporan-internal/get/${id}`, // Ganti dengan URL yang sesuai
                method: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success' && response.data) {
                        // Mengisi form dengan data dari response
                        $('#ubah-data').find('#edit-tanggal').val(response.data.tanggal);
                        $('#ubah-data').find('#edit-unit-terkait').val(response.data.unit_terkait).trigger(
                            'change');
                        $('#ubah-data').find('#edit-kegiatan').val(response.data.kegiatan);
                        $('#ubah-data').find('#edit-jam-masuk').val(formatTime(response.data.jam_masuk));
                        $('#ubah-data').find('#edit-jam-diproses').val(formatTime(response.data.jam_diproses));
                        $('#ubah-data').find('#edit-jam-selesai').val(formatTime(response.data.jam_selesai));
                        $('#ubah-data').find('#edit-status').val(response.data.status).trigger('change');
                        $('#ubah-data').find('#edit-jenis').val(response.data.jenis).trigger('change');
                        $('#ubah-data').find('#edit-user-id').val(response.data.user_id);
                        $('#ubah-data').find('#edit-laporan-id').val(response.data.id);
                        $('#ubah-data').find('#edit-organization-id').val(response.data.organization_id);

                        // Hanya menampilkan nama file (tidak bisa mengubah nilai file input)
                        $('#ubah-data').find('#edit-dokumentasi').val(
                            ''); // Clear file input as it cannot be set programmatically

                        // Menampilkan preview dokumentasi jika ada
                        const previewContainer = $('#dokumentasi-preview');
                        if (response.data.dokumentasi) {
                            const fileUrl = response.data.dokumentasi;
                            const fileExtension = fileUrl.split('.').pop().toLowerCase();
                            if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension ===
                                'png') {
                                // Menampilkan preview gambar
                                previewContainer.html(
                                    `<img src="${fileUrl}" alt="Preview" style="max-width: 100%; max-height: 200px;">`
                                );
                            } else if (fileExtension === 'pdf') {
                                // Menampilkan link untuk file PDF
                                previewContainer.html(`<a href="${fileUrl}" target="_blank">View PDF</a>`);
                            }
                            previewContainer.show(); // Menampilkan preview
                        } else {
                            previewContainer.hide(); // Menyembunyikan preview jika tidak ada file
                        }

                        // Menampilkan modal
                        $('#ubah-data').modal('show');
                    } else {
                        alert('Data tidak ditemukan atau terjadi kesalahan');
                    }
                },
                error: function() {
                    alert('Gagal mengambil data');
                }
            });

            // Mengatur preview gambar baru setelah file dipilih
            $('#edit-dokumentasi').on('change', function(event) {
                const file = event.target.files[0]; // Ambil file yang dipilih
                const previewContainer = $('#dokumentasi-preview');

                if (file) {
                    const fileReader = new FileReader();

                    fileReader.onload = function(e) {
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'png') {
                            // Menampilkan preview gambar
                            previewContainer.html(
                                `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 11px;">`
                            );
                        } else if (fileExtension === 'pdf') {
                            // Menampilkan link untuk file PDF
                            previewContainer.html(`<a href="${e.target.result}" target="_blank">View PDF</a>`);
                        }
                        previewContainer.show(); // Menampilkan preview
                    };

                    fileReader.readAsDataURL(file); // Membaca file dan menampilkan preview
                }
            });
        }

        function completeLaporan(id) {
            Swal.fire({
                title: 'Ubah Status Laporan?',
                text: "Laporan akan di ubah statusnya menjadi selesai!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/laporan-internal/complete/' + id,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: 'Selesai'
                        },
                        success: function() {
                            showToast('Data laporan berhasil dihapus');
                            $('#laporanTable').DataTable().ajax.reload();
                        },
                        error: function() {
                            showToast('Gagal menghapus data laporan', 'error');
                        }
                    });
                }
            });
        }

        function deleteLaporan(id) {
            Swal.fire({
                title: 'Hapus Laporan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/laporan-internal/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            showToast('Data laporan berhasil dihapus');
                            $('#laporanTable').DataTable().ajax.reload();
                        },
                        error: function() {
                            showToast('Gagal menghapus data laporan', 'error');
                        }
                    });
                }
            });
        }

        // Definisikan fungsi helper di luar DataTables
        function parseTime(timeStr) {
            // Handle both timestamp and HH:MM:SS format
            if (typeof timeStr === 'string' && timeStr.match(/^\d{2}:\d{2}:\d{2}$/)) {
                const [hours, minutes, seconds] = timeStr.split(':');
                const date = new Date();
                date.setHours(hours, minutes, seconds);
                return date;
            }
            return new Date(timeStr);
        }

        function formatDuration(diff) {
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            let durationText = '';
            if (hours > 0) durationText += `${hours} jam `;
            if (minutes > 0) durationText += `${minutes} menit `;
            if (seconds > 0 || (hours === 0 && minutes === 0)) durationText += `${seconds} detik`;

            return durationText.trim();
        }

        document.getElementById('create-dokumentasi').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            // Cek apakah file ada dan apakah file tersebut adalah gambar
            if (file && file.type.match('image.*')) {
                // Membaca file dan menampilkan preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result; // Menyimpan hasil pembacaan file (URL data)
                    previewContainer.style.display = 'block'; // Menampilkan container preview
                }
                reader.readAsDataURL(file);
            } else {
                // Menyembunyikan preview jika file bukan gambar
                previewContainer.style.display = 'none';
            }
        });

        // initialize datatable
        $(document).ready(function() {
            $('#laporanTable').DataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: false, // Responsif diaktifkan
                scrollX: true, // Tambahkan scroll horizontal
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end buttons-container'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm custom-margin'
                    }
                ]
            });
        });
    </script>
@endsection
