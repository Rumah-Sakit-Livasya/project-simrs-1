<div class="modal fade" id="modal-order-operasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Order Operasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-order-operasi">
                    @csrf
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tgl Rencana Operasi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="tgl_operasi" name="tgl_operasi" class="form-control"
                                        placeholder="Pilih tanggal & waktu..." required readonly
                                        value="{{ date('d-m-Y H:i') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">No Registrasi</label>
                                <input type="text" class="form-control"
                                    value="{{ $registration->registration_number }}" readonly>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                                <select name="ruangan_id" class="form-control select2" required>
                                    <option value="">Pilih Ruangan</option>
                                    <!-- Opsi ruangan akan diisi oleh JavaScript -->
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_rawat_id" class="form-control select2" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas_rawats as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $class->kelas == 'Rawat Jalan' ? 'selected' : '' }}>{{ $class->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tipe Operasi <span class="text-danger">*</span></label>
                                <select name="kategori_operasi_id" class="form-control select2" required>
                                    <option value="">Pilih Tipe Operasi</option>
                                    @foreach ($kategoriOperasi as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tipe Penggunaan <span class="text-danger">*</span></label>
                                <select name="tipe_operasi_id" class="form-control select2" required>
                                    <option value="">Pilih Tipe Penggunaan</option>
                                    @foreach ($jenisOperasi as $tipe)
                                        <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe ?? $tipe->tipe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label class="form-label">Diagnosa <span class="text-danger">*</span></label>
                                <textarea name="diagnosa_awal" rows="3" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                {{-- UBAH LABEL DAN NAMA INPUT --}}
                                <label class="form-label">Dokter Operator</label>
                                <select name="dokter_operator_id" class="form-control select2">
                                    <option value="">Pilih Dokter</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            {{ $doctor->employee->fullname ?? 'Nama Tidak Tersedia' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-order-operasi">Simpan Order</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#modal-order-operasi .select2').select2({
            dropdownParent: $('#modal-order-operasi')
        });

        // Data ruangan dari controller
        const ruangansOperasi = @json($ruangans_operasi);

        // Fungsi untuk memperbarui dropdown ruangan berdasarkan kelas yang dipilih
        function updateRuanganOptions(kelasRawatId) {
            const ruanganSelect = $('#form-order-operasi select[name="ruangan_id"]');
            ruanganSelect.empty().append('<option value="">Pilih Ruangan</option>');

            // Filter ruangan berdasarkan kelas_rawat_id
            const filteredRuangans = ruangansOperasi.filter(room => room.kelas_rawat_id == kelasRawatId);

            if (filteredRuangans.length > 0) {
                // Ambil ruangan pertama yang sesuai (hanya satu OK per kelas)
                const room = filteredRuangans[0];
                ruanganSelect.append(
                    `<option value="${room.id}" selected>${room.ruangan} </option>`
                );
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Ruangan',
                    text: 'Tidak ada ruangan OK untuk kelas yang dipilih.',
                });
            }

            ruanganSelect.trigger('change');
        }

        // Ketika modal dibuka, set default kelas ke Rawat Jalan dan update ruangan
        $('#modal-order-operasi').on('shown.bs.modal', function() {
            const kelasRawatSelect = $('#form-order-operasi select[name="kelas_rawat_id"]');
            const selectedKelas = kelasRawatSelect.val();
            if (selectedKelas) {
                updateRuanganOptions(selectedKelas);
            }
        });

        // Ketika kelas rawat berubah, perbarui dropdown ruangan
        $('#form-order-operasi select[name="kelas_rawat_id"]').on('change', function() {
            const kelasRawatId = $(this).val();
            if (kelasRawatId) {
                updateRuanganOptions(kelasRawatId);
            }
        });

        // Validasi tambahan untuk memastikan hanya ruangan OK yang dipilih
        $('#form-order-operasi select[name="ruangan_id"]').on('change', function() {
            const selectedRoom = $(this).find('option:selected').text();
        });
    });
</script>
