@extends('inc.layout-no-side')
@section('title', 'Tarif Persalinan')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tarif Persalinan {{ $persalinan->nama }}</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="store-form">
                                <input type="hidden" name="kelas_rawat_ids[]"
                                    value="{{ implode(',', $kelas_rawat->pluck('id')->toArray()) }}">
                                <div class="mb-3">
                                    <label for="grup-penjamin-id">Grup Penjamin</label>
                                    <select id="grup-penjamin-id" class="form-control select2" name="grup_penjamin_id">
                                        @foreach ($grup_penjamin as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-primary-600 text-white">
                                            <tr>
                                                <th rowspan="2">Kelas Rawat</th>
                                                <th colspan="3">Operator</th>
                                                <th colspan="2">Asst. Operator</th>
                                                <th colspan="2">Anastesi</th>
                                                <th colspan="2">Asst. Anastesi</th>
                                                <th colspan="2">Resusitator</th>
                                                <th colspan="2">Umum</th>
                                                <th rowspan="2">Ruang</th>
                                            </tr>
                                            <tr>
                                                <th>Dokter</th>
                                                <th>RS</th>
                                                <th>Prasarana</th>
                                                <th>Dokter</th>
                                                <th>RS</th>
                                                <th>Dokter</th>
                                                <th>RS</th>
                                                <th>Dokter</th>
                                                <th>RS</th>
                                                <th>Dokter</th>
                                                <th>RS</th>
                                                <th>Dokter</th>
                                                <th>RS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kelas_rawat as $row)
                                                <tr>
                                                    <td>{{ $row->kelas }}</td>
                                                    @foreach (['operator_dokter', 'operator_rs', 'operator_prasarana', 'ass_operator_dokter', 'ass_operator_rs', 'anastesi_dokter', 'anastesi_rs', 'ass_anastesi_dokter', 'ass_anastesi_rs', 'resusitator_dokter', 'resusitator_rs', 'umum_dokter', 'umum_rs', 'ruang'] as $field)
                                                        <td>
                                                            <input type="number"
                                                                name="{{ $field }}[{{ $row->id }}]"
                                                                step="0.01" value="0"
                                                                class="form-control form-control-sm">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 btn-block">Simpan Tarif</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            function loadTarif(grupPenjaminId) {
                const persalinanId = @json($persalinan->id);

                const url =
                    "{{ route('master-data.persalinan.tarif.get', ['persalinanId' => ':persalinanId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':persalinanId', persalinanId)
                    .replace(':grupPenjaminId', grupPenjaminId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.data.length > 0) {
                            response.data.forEach(function(item) {
                                const id = item.kelas_rawat_id;

                                $(`input[name="operator_dokter[${id}]"]`).val(item
                                    .operator_dokter);
                                $(`input[name="operator_rs[${id}]"]`).val(item.operator_rs);
                                $(`input[name="operator_prasarana[${id}]"]`).val(item
                                    .operator_prasarana);

                                $(`input[name="ass_operator_dokter[${id}]"]`).val(item
                                    .ass_operator_dokter);
                                $(`input[name="ass_operator_rs[${id}]"]`).val(item
                                    .ass_operator_rs);

                                $(`input[name="anastesi_dokter[${id}]"]`).val(item
                                    .anastesi_dokter);
                                $(`input[name="anastesi_rs[${id}]"]`).val(item.anastesi_rs);

                                $(`input[name="ass_anastesi_dokter[${id}]"]`).val(item
                                    .ass_anastesi_dokter);
                                $(`input[name="ass_anastesi_rs[${id}]"]`).val(item
                                    .ass_anastesi_rs);

                                $(`input[name="resusitator_dokter[${id}]"]`).val(item
                                    .resusitator_dokter);
                                $(`input[name="resusitator_rs[${id}]"]`).val(item
                                    .resusitator_rs);

                                $(`input[name="umum_dokter[${id}]"]`).val(item.umum_dokter);
                                $(`input[name="umum_rs[${id}]"]`).val(item.umum_rs);

                                $(`input[name="ruang[${id}]"]`).val(item.ruang);
                            });
                        } else {
                            // reset semua input ke 0 jika data tidak ditemukan
                            $('tbody input').val(0);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            }

            // Panggil loadTarif saat halaman ready dengan grup penjamin yang terpilih pertama
            const initialGrupPenjaminId = $('#grup-penjamin-id').val();
            if (initialGrupPenjaminId) {
                loadTarif(initialGrupPenjaminId);
            }

            // Event change grup penjamin
            $('#grup-penjamin-id').on('change', function() {
                loadTarif($(this).val());
            });

            // Submit form sama seperti sebelumnya...
            $('#store-form').on('submit', function(e) {
                e.preventDefault();

                const grupPenjaminId = $('#grup-penjamin-id').val();
                const persalinanId = @json($persalinan->id);
                const url =
                    "{{ route('master-data.persalinan.tarif.store', ['persalinanId' => ':persalinanId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':persalinanId', persalinanId)
                    .replace(':grupPenjaminId', grupPenjaminId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menyimpan data.');
                    }
                });
            });

        });
    </script>
@endsection
