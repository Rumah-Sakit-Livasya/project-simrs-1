@extends('inc.layout') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Edit Plasma Antrian: {{ $plasmaRawatJalan->name }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form class="form-horizontal"
                                action="{{ route('poliklinik.antrian-poli.update', $plasmaRawatJalan->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                {{-- ... (Field Nama dan Status sama seperti form create) ... --}}
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 col-form-label">Nama Display</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $plasmaRawatJalan->name) }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" {{ $plasmaRawatJalan->status == 1 ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="0" {{ $plasmaRawatJalan->status == 0 ? 'selected' : '' }}>
                                                Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group row">
                                    <label for="pilih_poliklinik" class="col-sm-3 col-form-label">Pilih Departemen
                                        (Poliklinik)</label>
                                    <div class="col-sm-9">
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
                                                    {{-- Muat data yang sudah ada --}}
                                                    @foreach ($plasmaRawatJalan->departements as $departement)
                                                        <tr>
                                                            <td class="text-center"><button type="button"
                                                                    class="btn btn-sm btn-danger removeItem"><i
                                                                        class="fas fa-trash-alt"></i></button></td>
                                                            <td>{{ $departement->name }}<input type="hidden"
                                                                    name="departements[]" value="{{ $departement->id }}">
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
                                        <span class="fas fa-save"></span> Simpan Perubahan
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

    {{-- Script JavaScript di sini SAMA PERSIS dengan script di create.blade.php --}}
    <script>
        $(document).ready(function() {
            $('#pilih_poliklinik').select2({
                placeholder: "-- Pilih Satu atau Lebih Departemen --",
            });

            $('#add-dept-button').on('click', function() {
                const selectedOptions = $('#pilih_poliklinik').select2('data');

                if (selectedOptions.length === 0) {
                    alert('Silakan pilih departemen terlebih dahulu.');
                    return;
                }

                selectedOptions.forEach(function(option) {
                    const deptId = option.id;
                    const deptName = option.text;

                    let isExist = false;
                    $('#ListDept tbody input[name="departements[]"]').each(function() {
                        if ($(this).val() == deptId) {
                            isExist = true;
                        }
                    });

                    if (!isExist) {
                        const newRow = `
                        <tr>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeItem"><i class="fas fa-trash-alt"></i></button></td>
                            <td>${deptName}<input type="hidden" name="departements[]" value="${deptId}"></td>
                        </tr>`;
                        $('#ListDept tbody').append(newRow);
                    }
                });

                $('#pilih_poliklinik').val(null).trigger('change');
            });

            $('#ListDept').on('click', '.removeItem', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
