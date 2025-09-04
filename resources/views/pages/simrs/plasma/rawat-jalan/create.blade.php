@extends('inc.layout')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tambah Plasma Antrian Poliklinik Baru
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form class="form-horizontal" action="{{ route('poliklinik.antrian-poli.store') }}"
                                method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="nama_loket" class="col-sm-3 col-form-label">Nama Plasma Antrian</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="nama_loket" id="nama_loket" class="form-control"
                                            required value="{{ old('nama_loket') }}">
                                        @error('nama_loket')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="is_del" class="col-sm-3 col-form-label">Status Plasma</label>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_del" name="is_del"
                                                value="1" checked>
                                            <label class="form-check-label" for="is_del">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="pilih_poliklinik" class="col-sm-3 col-form-label">Pilih Departemen
                                        (Poliklinik)</label>
                                    <div class="col-sm-9">
                                        {{-- PERUBAHAN DI SINI --}}
                                        <div class="input-group">
                                            <select id="pilih_poliklinik" class="form-control select2" style="width: 85%;"
                                                multiple="multiple">
                                                @foreach ($allDepartements as $departement)
                                                    <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="add-dept-button">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </div>
                                        </div>
                                        {{-- AKHIR PERUBAHAN --}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered table-striped" id="ListDept">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-center" style="width: 10%;">Hapus</th>
                                                        <th>Poliklinik</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Akan diisi oleh JavaScript --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-3">
                                    <a href="{{ route('poliklinik.antrian-poli.index') }}" class="btn btn-secondary">
                                        <span class="fas fa-arrow-left"></span> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary ml-auto">
                                        <span class="fas fa-save"></span> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    {{-- Pastikan Anda memuat jQuery & Select2 di layout utama Anda --}}
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#pilih_poliklinik').select2({
                placeholder: "-- Pilih Satu atau Lebih Departemen --",
            });

            // ==========================================================
            // LOGIKA JAVASCRIPT BARU UNTUK MULTI-SELECT
            // ==========================================================
            $('#add-dept-button').on('click', function() {
                // Ambil semua data yang dipilih dari Select2
                const selectedOptions = $('#pilih_poliklinik').select2('data');

                if (selectedOptions.length === 0) {
                    alert('Silakan pilih departemen terlebih dahulu.');
                    return;
                }

                // Loop melalui setiap item yang dipilih
                selectedOptions.forEach(function(option) {
                    const deptId = option.id;
                    const deptName = option.text;

                    // Cek agar tidak duplikat
                    let isExist = false;
                    $('#ListDept tbody input[name="did[]"]').each(function() {
                        if ($(this).val() == deptId) {
                            isExist = true;
                        }
                    });

                    // Jika belum ada, tambahkan ke tabel
                    if (!isExist) {
                        const newRow = `
                        <tr>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeItem"><i class="fas fa-trash-alt"></i></button></td>
                            <td>${deptName}<input type="hidden" name="did[]" value="${deptId}"></td>
                        </tr>`;
                        $('#ListDept tbody').append(newRow);
                    }
                });

                // Kosongkan kembali pilihan di Select2
                $('#pilih_poliklinik').val(null).trigger('change');
            });

            // Logika untuk menghapus item dari tabel (tetap sama)
            $('#ListDept').on('click', '.removeItem', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
