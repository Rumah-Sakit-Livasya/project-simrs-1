{{-- resources/views/pages/simrs/erm/form/pengkajian-lanjutan.blade.php --}}
@extends('pages.simrs.erm.index')

@section('erm')

    {{-- content start --}}
    @if (isset($registration) && $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.poliklinik.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <div class="row">
                    {{-- Bagian untuk Menambah Form Baru (sudah benar menggunakan popup) --}}
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tbody>
                                @foreach ($form as $item)
                                    <tr>
                                        <td style="width: 20%;" valign="middle">
                                            <label class="mt-2">{{ $item->nama_kategori }}</label>
                                        </td>
                                        <td style="width: 3%;" valign="middle">
                                            <label class="mt-2">:</label>
                                        </td>
                                        <td style="width: 50%;">
                                            {{-- ID select dibuat unik berdasarkan ID kategori --}}
                                            <select class="select2 form-control" name="form_id"
                                                id="form_id_{{ $item->id }}">
                                                <option value=""></option>
                                                @foreach ($item->form_templates as $template)
                                                    <option value="{{ $template->id }}">
                                                        {{ $template->nama_form }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="width: 20%;">
                                            <button class="btn btn-primary tambah-form btn-block">Tambah</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bagian untuk Menampilkan Daftar Form yang Sudah Diisi --}}
                    <div class="col-12" style="margin-bottom: 100px;">
                        <h5 class="mt-4 mb-3">Dokumen yang Sudah Diisi</h5>
                        @forelse ($daftar_pengkajian as $item)
                            <div class="card mb-2">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="nama-form">
                                        {{ $item->form_template->nama_form ?? 'Nama Form Tidak Ditemukan' }} <br>
                                        <small class="text-muted">Diisi oleh: {{ $item->creator->name ?? 'N/A' }} pada
                                            {{ $item->created_at->format('d-m-Y H:i') }}
                                            @if ($item->is_final)
                                                <span class="badge bg-success ms-1">Final</span>
                                            @else
                                                <span class="badge bg-warning ms-1">Draft</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="action-form">
                                        {{-- [DIUBAH] Tombol untuk Lihat/Cetak (Membuka Popup) --}}
                                        <button type="button"
                                            class="btn btn-link text-primary p-0 m-0 me-2 open-form-popup"
                                            data-url="{{ route('poliklinik.pengkajian-lanjutan.show', $item->id) }}"
                                            title="Lihat & Cetak Form">
                                            <i class="fas fa-print"></i>
                                        </button>

                                        {{-- [DIUBAH] Tombol untuk Edit (Membuka Popup), hanya jika belum final --}}
                                        @if (!$item->is_final)
                                            <button type="button"
                                                class="btn btn-link text-warning p-0 m-0 me-2 open-form-popup"
                                                data-url="{{ route('poliklinik.pengkajian-lanjutan.edit', $item->id) }}"
                                                title="Edit Form">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endif

                                        {{-- Tombol Hapus dengan form --}}
                                        <form action="{{ route('poliklinik.pengkajian-lanjutan.destroy', $item->id) }}"
                                            method="POST" class="d-inline form-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 m-0"
                                                title="Hapus Form">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                Belum ada dokumen yang diisi untuk pasien ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('plugin-erm')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi awal (jika ada)
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Form',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });

            // =========================================================
            // FUNGSI UNTUK MEMBUKA POPUP JENDELA BARU
            // =========================================================
            function openPopupWindow(url, title = 'formWindow') {
                // Get the screen dimensions
                const screenWidth = window.screen.width;
                const screenHeight = window.screen.height;

                const popupWindow = window.open(
                    url,
                    title,
                    `width=${screenWidth},height=${screenHeight},top=0,left=0,scrollbars=yes,resizable=yes,fullscreen=yes`
                );

                if (window.focus) {
                    popupWindow.focus();
                }
                return popupWindow;
            }

            // =========================================================
            // EVENT LISTENER UNTUK TOMBOL-TOMBOL
            // =========================================================

            // 1. Tombol "Tambah" Form Baru
            $('.tambah-form').on('click', function(e) {
                e.preventDefault();
                let selectElement = $(this).closest('tr').find('select');
                let idForm = selectElement.val();

                if (idForm) {
                    let registrationId = "{{ $registration->id ?? '' }}";
                    if (!registrationId) {
                        Swal.fire('Error', 'ID Registrasi tidak ditemukan.', 'error');
                        return;
                    }
                    // Enkripsi ID form dengan Base64 di sisi client
                    let encryptedId = btoa(idForm);
                    let url =
                        "{{ route('poliklinik.pengkajian-lanjutan.create', [':registrationId', ':encryptedId']) }}"
                        .replace(':registrationId', registrationId)
                        .replace(':encryptedId', encryptedId);

                    openPopupWindow(url, 'createFormWindow');
                } else {
                    Swal.fire('Perhatian', 'Silakan pilih jenis form terlebih dahulu.', 'warning');
                }
            });

            // 2. Tombol "Lihat/Edit" dari Daftar Form yang Sudah Ada
            $('.open-form-popup').on('click', function() {
                const url = $(this).data('url');
                openPopupWindow(url, 'viewEditFormWindow');
            });

            // 3. Tombol "Hapus" dengan konfirmasi Swal
            $('.form-hapus').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
@endsection
