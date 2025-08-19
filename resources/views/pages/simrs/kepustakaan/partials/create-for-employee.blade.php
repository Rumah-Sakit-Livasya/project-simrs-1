<div class="modal fade" id="modal-tambah-kepustakaan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form-kepustakaan"
                enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah File/Folder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <div class="row">
                        <div class="col-md-12">
                            <hr style="border-color: #dedede;" class="mb-1 mt-1">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="d-block">Tipe (File/Folder)</label>
                                        <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                            <input type="radio" checked class="custom-control-input" id="type_file"
                                                name="type" value="file">
                                            <label class="custom-control-label" for="type_file">File</label>
                                        </div>
                                        <div class="custom-control d-inline-block custom-radio mt-2">
                                            <input type="radio" class="custom-control-input" id="type_folder"
                                                name="type" value="folder">
                                            <label class="custom-control-label" for="type_folder">Folder</label>
                                        </div>

                                        <input type="hidden" name="parent_id" value="{{ $folder->id ?? '' }}">
                                        @if (auth()->user()->can('tambah kepustakaan'))
                                            <input type="hidden" name="organization_id"
                                                value="{{ auth()->user()->employee->organization_id ?? '' }}">
                                        @endif
                                    </div>
                                </div>
                                @if (auth()->user()->can('master kepustakaan') || auth()->user()->hasRole('super admin'))
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="organization_id">
                                                Unit
                                            </label>
                                            <select class="select2 form-control w-100" id="organization_id"
                                                name="organization_id">
                                                <option value=""></option>
                                                @foreach ($organizations as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ count($breadcrumbs) > 0 ? ($item->id == $folder->organization_id ? 'selected' : '') : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if (count($breadcrumbs) < 1)
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="kategori">
                                                    Kategori
                                                </label>
                                                <select class="select2 form-control w-100" id="kategori"
                                                    name="kategori">
                                                    <option value="Regulasi">Regulasi</option>
                                                    <option value="Laporan">Laporan</option>
                                                    <option value="Perizinan">Perizinan</option>
                                                    <option value="Mutu dan Manajemen Resiko">Mutu dan Manajemen Resiko
                                                    </option>
                                                    <option value="File Unit Lainnya">File Unit Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="kategori" value="{{ $folder->kategori ?? '' }}">
                                    @endif
                                @else
                                    <input type="hidden" name="kategori" value="{{ $folder->kategori ?? '' }}">
                                @endif
                                <div class="col-md-12 mt-3">
                                    <label for="name">Nama (File/Folder) <span
                                            class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        placeholder="Masukan nama file/folder...">
                                </div>

                                <div class="col-md-12 mt-3" id="laporan-periode-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="month">Bulan Laporan <span
                                                        class="text-danger">*</span></label>
                                                <select name="month" id="month-select"
                                                    class="form-control select2-laporan">
                                                    <option value="" disabled selected>Pilih Bulan</option>
                                                    @for ($m = 1; $m <= 12; $m++)
                                                        <option value="{{ $m }}">
                                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="year">Tahun Laporan <span
                                                        class="text-danger">*</span></label>
                                                <select name="year" id="year-select"
                                                    class="form-control select2-laporan">
                                                    <option value="" disabled selected>Pilih Tahun</option>
                                                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                        <option value="{{ $y }}">{{ $y }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3" id="file_upload_section">
                                    <label for="customFile">Upload (File) <span
                                            class="text-danger fw-bold">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file"
                                            id="customFile">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-tambah"
                        class="btn mx-1 btn-tambah btn-primary text-white" title="Tambah">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>Tambah
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================================================================== --}}
{{-- =                      BAGIAN SCRIPT YANG DIPERBARUI                 = --}}
{{-- ==================================================================== --}}
<script>
    $(document).ready(function() {
        // Fungsi untuk menampilkan/menyembunyikan input periode laporan
        function toggleLaporanPeriode() {
            const isFileType = $('#modal-tambah-kepustakaan #type_file').is(':checked');
            const kategoriDropdown = $('#modal-tambah-kepustakaan #kategori');
            const kategoriValue = kategoriDropdown.length > 0 ?
                kategoriDropdown.val() :
                $('#modal-tambah-kepustakaan input[name="kategori"]').val();

            if (isFileType && kategoriValue === 'Laporan') {
                $('#laporan-periode-section').slideDown();
            } else {
                $('#laporan-periode-section').slideUp();
            }
        }

        // --- UPDATE UTAMA ADA DISINI ---
        // Inisialisasi SEMUA elemen select2 di dalam modal
        // Ini akan mengaktifkan Select2 untuk dropdown Unit, Kategori, Bulan, dan Tahun
        $('#modal-tambah-kepustakaan .select2, #modal-tambah-kepustakaan .select2-laporan').select2({
            dropdownParent: $('#modal-tambah-kepustakaan'),
            placeholder: "Pilih salah satu" // Opsi tambahan agar lebih user-friendly
        });

        // Membersihkan placeholder untuk dropdown Kategori agar tidak duplikat
        $('#modal-tambah-kepustakaan #kategori').select2({
            dropdownParent: $('#modal-tambah-kepustakaan')
            // Tanpa placeholder di sini karena sudah ada opsi default
        });
        // --- AKHIR UPDATE ---


        // Event listener ketika pilihan Tipe (File/Folder) berubah
        $('#modal-tambah-kepustakaan').on('change', 'input[name="type"]', function() {
            toggleLaporanPeriode();
        });

        // Event listener ketika pilihan Kategori berubah (hanya untuk yang di root)
        $('#modal-tambah-kepustakaan').on('change', '#kategori', function() {
            toggleLaporanPeriode();
        });

        // Jalankan pengecekan pertama kali saat modal ditampilkan
        $('#modal-tambah-kepustakaan').on('shown.bs.modal', function() {
            // Reset form dan select2 saat modal dibuka
            $('#store-form-kepustakaan').trigger('reset');
            $('#modal-tambah-kepustakaan .select2, #modal-tambah-kepustakaan .select2-laporan').val(
                null).trigger('change');

            toggleLaporanPeriode();
        });
    });
</script>
