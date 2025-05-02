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
            <div class="row mb-3">
                <div class="col-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>Laporan Internal IT</h2>
                            <div class="panel-toolbar">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah-data">
                                    <i class="fas fa-plus me-1"></i> Tambah Laporan
                                </button>
                            </div>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped w-100" id="laporanTable">
                                        <thead class="bg-primary-50">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Uraian</th>
                                                <th>Status</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Diproses</th>
                                                <th>Respon Time</th>
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

        <!-- Tambah Laporan Modal -->
        <div class="modal fade" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Tambah Laporan Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-laporan">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                            <div class="row mb-3">
                                <!-- Tanggal -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal" class="font-weight-bold">Tanggal Laporan</label>
                                        <input type="text" class="form-control datepicker" id="tanggal" name="tanggal"
                                            required>
                                    </div>
                                </div>

                                <!-- Jenis Laporan -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis" class="font-weight-bold">Jenis Laporan</label>
                                        <select class="form-control select2" id="jenis" name="jenis" required>
                                            <option value="" selected disabled>Pilih Jenis Laporan</option>
                                            <option value="kegiatan">Kegiatan</option>
                                            <option value="kendala">Kendala</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Organization Field (Hidden by default) -->
                            <div class="row mb-3">
                                <div class="col-12" id="organization-field" style="display: none;">
                                    <div class="form-group">
                                        <label for="organization" class="font-weight-bold">Organisasi Terkait</label>
                                        <select class="form-control select2" id="organization" name="organization_id">
                                            <option value="" selected disabled>Pilih Organisasi</option>
                                            @foreach (\App\Models\Organization::all() as $org)
                                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hanya untuk laporan kegiatan</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Uraian -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="kegiatan" class="font-weight-bold">Uraian Lengkap</label>
                                        <textarea class="form-control" id="kegiatan" name="kegiatan" rows="4" required
                                            placeholder="Deskripsikan laporan secara detail"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="font-weight-bold">Status</label>
                                        <select class="form-control select2" id="status" name="status" required>
                                            <option value="" disabled selected>Pilih Status</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="ditunda">Ditunda</option>
                                            <option value="ditolak">Ditolak</option>
                                        </select>
                                    </div>
                                    <!-- Keterangan -->

                                    <div class="form-group" id="keterangan-field" style="display: none;">
                                        <label for="keterangan" class="font-weight-bold">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4" required
                                            placeholder="Deskripsikan keterangan status secara detail"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Timeline</label>
                                        <div class="timeline-inputs">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light">Masuk</span>
                                                </div>
                                                <input type="time" class="form-control" name="jam_masuk">
                                            </div>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light">Diproses</span>
                                                </div>
                                                <input type="time" class="form-control" name="jam_diproses">
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light">Selesai</span>
                                                </div>
                                                <input type="time" class="form-control" name="jam_selesai">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function showToast(message, icon = 'success') {
            Toast.fire({
                icon: icon,
                title: message
            });
        }

        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2({
                dropdownParent: $('#tambah-data')
            });

            // Show/hide organization field based on jenis selection
            $('#jenis').change(function() {
                if ($(this).val() === 'kendala') {
                    $('#organization-field').show();
                    $('#organization').prop('required', true);
                    $('input[name="organization_id"]').remove();
                } else {
                    $('#organization-field').hide();
                    $('#organization').prop('required', false);
                    if (!$('input[name="organization_id"]').length) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'organization_id',
                            value: '{{ auth()->user()->employee->organization_id }}'
                        }).appendTo('form');
                    }
                }
            });

            // Show/hide keterangan field based on status selection
            $('#status').change(function() {
                if ($(this).val() === 'ditunda' || $(this).val() === 'ditolak') {
                    $('#keterangan-field').show();
                } else {
                    $('#keterangan-field').hide();
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
            var table = $('#laporanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/laporan-internal-list',
                    type: 'GET',
                    error: function(xhr) {
                        console.error('DataTables error:', xhr.responseText);
                        showToast('Gagal memuat data laporan', 'error');
                    }
                },
                columns: [{
                        data: null,
                        name: 'no',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
                        render: function(data) {
                            const badgeClass = data === 'kegiatan' ? 'bg-success' : 'bg-warning';
                            const displayText = data === 'kegiatan' ? 'Kegiatan' : 'Kendala';
                            return `<span class="badge ${badgeClass}">${displayText}</span>`;
                        }
                    },
                    {
                        data: 'kegiatan',
                        name: 'kegiatan'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let badgeClass = 'bg-secondary';
                            if (data === 'Selesai') badgeClass = 'bg-success';
                            if (data === 'Diproses') badgeClass = 'bg-primary';
                            if (data === 'Baru') badgeClass = 'bg-info';
                            if (data === 'Ditolak') badgeClass = 'bg-danger';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'jam_masuk',
                        name: 'jam_masuk',
                        render: function(data) {
                            if (!data) return '-';
                            try {
                                // Jika format waktu sudah HH:MM:SS
                                if (typeof data === 'string' && data.match(/^\d{2}:\d{2}:\d{2}$/)) {
                                    return data;
                                }
                                // Jika format timestamp
                                return new Date(data).toLocaleTimeString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: false
                                });
                            } catch (e) {
                                return '<span class="text-warning">Invalid</span>';
                            }
                        }
                    },
                    {
                        data: 'jam_diproses',
                        name: 'jam_diproses',
                        render: function(data) {
                            if (!data) return '-';
                            try {
                                if (typeof data === 'string' && data.match(/^\d{2}:\d{2}:\d{2}$/)) {
                                    return data;
                                }
                                return new Date(data).toLocaleTimeString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: false
                                });
                            } catch (e) {
                                return '<span class="text-warning">Invalid</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'respon_time',
                        render: function(data, type, row) {
                            // Hitung waktu respon dari jam_masuk ke jam_diproses
                            if (row.jam_masuk && row.jam_diproses) {
                                try {
                                    const start = this.parseTime(row.jam_masuk);
                                    const respon = this.parseTime(row.jam_diproses);

                                    if (respon < start) {
                                        return '<span class="text-danger">Invalid</span>';
                                    }

                                    const diff = Math.abs(respon - start);
                                    return this.formatDuration(diff);
                                } catch (e) {
                                    console.error('Error calculating response time:', e);
                                    return '<span class="text-warning">Error</span>';
                                }
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                <div class="btn-group">
                    <button class="btn btn-sm btn-icon btn-primary" onclick="editLaporan(${data})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-icon btn-danger" onclick="deleteLaporan(${data})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
                        }
                    }
                ],
                // Tambahkan fungsi helper di luar columns
                createdRow: function(row, data, dataIndex) {
                    // Helper functions untuk digunakan dalam render
                    $.fn.dataTable.render.formatDuration = function(diff) {
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        let durationText = '';
                        if (hours > 0) durationText += `${hours} jam `;
                        if (minutes > 0) durationText += `${minutes} menit `;
                        if (seconds > 0 || (hours === 0 && minutes === 0)) durationText +=
                            `${seconds} detik`;

                        return durationText.trim();
                    };

                    $.fn.dataTable.render.parseTime = function(timeStr) {
                        // Handle both timestamp and HH:MM:SS format
                        if (typeof timeStr === 'string' && timeStr.match(/^\d{2}:\d{2}:\d{2}$/)) {
                            const [hours, minutes, seconds] = timeStr.split(':');
                            const date = new Date();
                            date.setHours(hours, minutes, seconds);
                            return date;
                        }
                        return new Date(timeStr);
                    };
                },
                language: {
                    "decimal": "",
                    "emptyTable": "Tidak ada data yang tersedia",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                responsive: true
            });


            // Form submission
            $('#form-laporan').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                $.ajax({
                    url: '/laporan-internal',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        showToast(response.message);
                        $('#form-laporan')[0].reset();
                        $('#tambah-data').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            'Gagal menyimpan data';
                        showToast(errorMessage, 'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-1"></i> Simpan Laporan');
                    }
                });
            });

            // Reset form when modal is closed
            $('#tambah-data').on('hidden.bs.modal', function() {
                $('#form-laporan')[0].reset();
                $('.select2').val(null).trigger('change');
                $('.datepicker').datepicker('setDate', new Date());
                $('#organization-field').hide();
            });
        });

        function editLaporan(id) {
            Swal.fire({
                title: 'Edit Laporan',
                text: 'Fitur edit akan segera tersedia!',
                icon: 'info',
                confirmButtonText: 'OK'
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
    </script>
@endsection
