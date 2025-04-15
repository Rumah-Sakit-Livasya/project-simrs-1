{{-- ==================== DP Pasien ==================== --}}
<div class="tab-pane fade" id="dp_pasien" role="tabpanel">
    <div class="row mb-3">
        <div class="col">
            <label>Nama Pasien:</label>
            <input type="text" class="form-control" value="{{ $bilingan->registration->patient->name ?? 'N/A' }}"
                readonly>
        </div>
        <div class="col">
            <label>RM:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->patient->medical_record_number ?? 'N/A' }}" readonly>
        </div>
        <div class="col">
            <label>No Registrasi:</label>
            <input type="text" class="form-control"
                value="{{ $bilingan->registration->registration_number ?? 'N/A' }}" readonly>
        </div>
        <div class="col">
            <label>Tipe Kunjungan:</label>
            <input type="text" class="form-control"
                value="{{ ucwords(str_replace('-', ' ', $bilingan->registration->registration_type ?? 'N/A')) }}"
                readonly>
        </div>
    </div>
    <form id="downPaymentForm">
        <div class="row mb-3">
            <div class="col">
                <label>Metode Pembayaran:</label>
                <select class="form-control select2" name="metode_pembayaran">
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </div>
            <div class="col">
                <label>Nominal:</label>
                <input type="text" class="form-control" name="nominal" placeholder="Masukkan Nominal">
            </div>
            <div class="col">
                <label>Keterangan:</label>
                <input type="text" class="form-control" name="keterangan" placeholder="Masukkan Keterangan"
                    value="DP Pasien">
            </div>
            <div class="col">
                <label>Total DP:</label>
                <input type="text" class="form-control" name="total_dp"
                    value="{{ $bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') - $bilingan->down_payment->where('tipe', 'DP Refund')->sum('nominal') : '0') : '0' }}"
                    readonly>
                <div class="row my-3">
                    <div class="col text-left">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fal fa-undo"></i> Reset
                        </button>
                    </div>
                    <div class="col text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fal fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- DataTable for List DP Pasien --}}
    <div class="row mt-3">
        <div class="col">
            <h4>List DP Pasien</h4>
            <table class="table table-striped table-bordered" id="DownPaymentTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode Pembayaran</th>
                        <th>Nominal</th>
                        <th>Tipe</th>
                        <th>User Input</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data will be populated here using DataTable --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- ==================== DP Pasien ==================== --}}
@section('plugin-down-payment')
    <script>
        $(document).ready(function() {

            $('#DownPaymentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/simrs/kasir/down-payment/data/{{ $bilingan->id }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json && json.data ? json.data : [];
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran'
                    },
                    {
                        data: 'nominal',
                        name: 'nominal'
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'user_input',
                        name: 'user_input'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        render: function(data, type, row) {
                            return '<button class="btn btn-danger btn-delete" data-id="' + row.id +
                                '"><i class="fal fa-trash"></i></button>';
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada data yang tersedia"
                },
                autoWidth: false,
                responsive: true,
                pagingType: "simple",
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5
            });

            $('#downPaymentForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                const bilinganId = '{{ $bilingan->id }}';
                const userId = '{{ auth()->user()->id }}';
                formData += `&bilingan_id=${bilinganId}&user_id=${userId}`;
                // Send the form data to the server
                // using AJAX
                $.ajax({
                    url: '/simrs/kasir/down-payment',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showSuccessAlert('Down payment saved successfully.');
                        $('#DownPaymentTable').DataTable().ajax.reload();
                        // Optionally update the Total DP field and refresh the DataTable if needed.
                    },
                    error: function(xhr) {
                        showErrorAlert('Error saving down payment.');
                        $('#DownPaymentTable').DataTable().ajax.reload();
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Anda yakin ingin menghapus data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/simrs/kasir/down-payment/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'Data berhasil dihapus.',
                                    'success');
                                $('#DownPaymentTable').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Gagal menghapus data.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
