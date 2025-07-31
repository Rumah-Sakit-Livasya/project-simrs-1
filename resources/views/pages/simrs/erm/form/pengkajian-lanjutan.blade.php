@extends('pages.simrs.erm.index')
@section('erm')
    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.poliklinik.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <div class="row">
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

                    <div class="col-12" style="margin-bottom: 100px;">
                        {{-- File: pages/simrs/erm/index.blade.php (Bagian yang diubah) --}}
                        @foreach ($daftar_pengkajian as $item)
                            <div class="card mb-2">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="nama-form">
                                        {{ $item->form_template->nama_form }} <br>
                                        <small class="text-muted">Diisi oleh: {{ $item->created_by }} pada
                                            {{ $item->created_at->format('d-m-Y H:i') }}</small>
                                    </div>
                                    <div class="action-form">
                                        {{-- Tautan untuk Print dan Edit --}}
                                        <a href="{{ route('poliklinik.pengkajian-lanjutan.show', $item->id) }}"
                                            target="_blank" title="Lihat & Cetak Form">
                                            <i class="fas fa-print mr-2 text-primary"></i>
                                        </a>
                                        <a href="{{ route('poliklinik.pengkajian-lanjutan.edit', $item->id) }}"
                                            target="_blank" title="Edit Form">
                                            <i class="fas fa-pencil mr-2 text-warning"></i>
                                        </a>
                                        {{-- Tambahkan form untuk delete jika perlu --}}
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            // $('#doctor_id').select2({
            //     placeholder: 'Pilih Dokter',
            // });

            $('.tambah-form').on('click', function(e) {
                e.preventDefault();

                let idForm = $(this).closest('tr').find('select').val();

                if (idForm) {
                    // Panggil route yang sudah dienkripsi dari Blade
                    let registrationId = "{{ $registration->id }}"; // Ambil registration ID dari Blade
                    let url =
                        "{{ route('poliklinik.pengkajian-lanjutan.create', [':registrationId', ':encryptedId']) }}"
                        .replace(':encryptedId', btoa(idForm)) // Enkripsi dengan Base64
                        .replace(':registrationId', registrationId); // Tambahkan registration ID

                    // Ukuran popup
                    let popupWidth = 1200;
                    let popupHeight = 600;

                    // Hitung posisi tengah
                    let screenWidth = window.screen.width;
                    let screenHeight = window.screen.height;
                    let left = (screenWidth - popupWidth) / 2;
                    let top = (screenHeight - popupHeight) / 2.8;

                    // Buka popup di tengah
                    window.open(url, '_blank',
                        `width=${popupWidth},height=${popupHeight},top=${top},left=${left}`);
                } else {
                    alert('Silakan pilih departement terlebih dahulu.');
                }
            });
        });

        $('.form-hapus').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    </script>
@endsection
