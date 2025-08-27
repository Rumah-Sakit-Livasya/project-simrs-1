{{-- File: resources/views/pages/simrs/pendaftaran/partials/modal-operasi.blade.php --}}

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
                {{-- Form ini akan mengirim data ke OperasiController --}}
                <form id="form-order-operasi">
                    @csrf
                    {{-- Hidden fields untuk data konteks --}}
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

                            {{-- <!-- DIUBAH: DPJP diganti dengan Ruangan --> --}}
                            <div class="form-group">
                                <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                                <select name="ruangan_id" class="form-control select2" required>
                                    <option value="">Pilih Ruangan</option>
                                    {{-- Pastikan variabel $ruangans dikirim dari controller --}}
                                    @foreach ($ruangans as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_rawat_id" class="form-control select2" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas_rawats as $class)
                                        <option value="{{ $class->id }}">{{ $class->kelas }}</option>
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
                                    @foreach ($jenisOperasi as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama_tipe ?? $jenis->tipe }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label class="form-label">Diagnosa <span class="text-danger">*</span></label>
                                <textarea name="diagnosa_awal" rows="3" class="form-control" required></textarea>
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
