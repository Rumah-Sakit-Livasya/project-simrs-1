@extends('inc.layout-no-side')
@section('title', 'Tarif Operasi')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tarif Operasi</h2>
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

                                @php
                                    $sections = [
                                        'Operator' => [
                                            'operator_dokter' => 'Dokter',
                                            'operator_rs' => 'RS',
                                            'operator_anastesi_dokter' => 'Anastesi Dokter',
                                            'operator_anastesi_rs' => 'Anastesi RS',
                                            'operator_resusitator_dokter' => 'Resusitator Dokter',
                                            'operator_resusitator_rs' => 'Resusitator RS',
                                        ],
                                        'Asisten Tambahan' => [
                                            'asisten_operator_1_dokter' => 'Asst. Operator 1 Dokter',
                                            'asisten_operator_1_rs' => 'Asst. Operator 1 RS',
                                            'asisten_operator_2_dokter' => 'Asst. Operator 2 Dokter',
                                            'asisten_operator_2_rs' => 'Asst. Operator 2 RS',
                                            'asisten_operator_3_dokter' => 'Asst. Operator 3 Dokter',
                                            'asisten_operator_3_rs' => 'Asst. Operator 3 RS',
                                            'asisten_anastesi_1_dokter' => 'Asst. Anastesi 1 Dokter',
                                            'asisten_anastesi_1_rs' => 'Asst. Anastesi 1 RS',
                                            'asisten_anastesi_2_dokter' => 'Asst. Anastesi 2 Dokter',
                                            'asisten_anastesi_2_rs' => 'Asst. Anastesi 2 RS',
                                        ],
                                        'Dokter Tambahan' => [
                                            'dokter_tambahan_1_dokter' => 'Dokter 1',
                                            'dokter_tambahan_1_rs' => 'RS 1',
                                            'dokter_tambahan_2_dokter' => 'Dokter 2',
                                            'dokter_tambahan_2_rs' => 'RS 2',
                                            'dokter_tambahan_3_dokter' => 'Dokter 3',
                                            'dokter_tambahan_3_rs' => 'RS 3',
                                            'dokter_tambahan_4_dokter' => 'Dokter 4',
                                            'dokter_tambahan_4_rs' => 'RS 4',
                                            'dokter_tambahan_5_dokter' => 'Dokter 5',
                                            'dokter_tambahan_5_rs' => 'RS 5',
                                        ],
                                        'Ruang & Alat' => [
                                            'ruang_operasi' => 'Ruang Operasi',
                                            'bmhp' => 'BMHP',
                                            'alat_dokter' => 'Alat Dokter',
                                            'alat_rs' => 'Alat RS',
                                        ],
                                    ];
                                @endphp

                                @foreach ($sections as $sectionTitle => $fields)
                                    <h4 class="mt-4">{{ $sectionTitle }}</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="bg-primary-600 text-white">
                                                <tr>
                                                    <th>Nama Kelas</th>
                                                    @foreach ($fields as $fieldKey => $label)
                                                        <th>{{ $label }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($kelas_rawat as $row)
                                                    <tr>
                                                        <td>{{ $row->kelas }}</td>
                                                        @foreach ($fields as $fieldKey => $label)
                                                            <td>
                                                                <input type="number"
                                                                    name="{{ $fieldKey }}[{{ $row->id }}]"
                                                                    step="0.01" value="0"
                                                                    class="form-control form-control-sm">
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach

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
                const operasiId = @json($tindakan_operasi->id);

                const url =
                    "{{ route('master-data.operasi.tarif.get', ['operasiId' => ':operasiId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':operasiId', operasiId)
                    .replace(':grupPenjaminId', grupPenjaminId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.data.length > 0) {
                            response.data.forEach(function(item) {
                                const id = item.kelas_rawat_id;

                                // operator utama
                                $(`input[name="operator_dokter[${id}]"]`).val(item
                                    .operator_dokter);
                                $(`input[name="operator_rs[${id}]"]`).val(item.operator_rs);
                                $(`input[name="operator_anastesi_dokter[${id}]"]`).val(item
                                    .operator_anastesi_dokter);
                                $(`input[name="operator_anastesi_rs[${id}]"]`).val(item
                                    .operator_anastesi_rs);
                                $(`input[name="operator_resusitator_dokter[${id}]"]`).val(item
                                    .operator_resusitator_dokter);
                                $(`input[name="operator_resusitator_rs[${id}]"]`).val(item
                                    .operator_resusitator_rs);

                                // asisten operator
                                for (let i = 1; i <= 3; i++) {
                                    $(`input[name="asisten_operator_${i}_dokter[${id}]"]`).val(
                                        item[`asisten_operator_${i}_dokter`]);
                                    $(`input[name="asisten_operator_${i}_rs[${id}]"]`).val(item[
                                        `asisten_operator_${i}_rs`]);
                                }

                                // asisten anastesi
                                for (let i = 1; i <= 2; i++) {
                                    $(`input[name="asisten_anastesi_${i}_dokter[${id}]"]`).val(
                                        item[`asisten_anastesi_${i}_dokter`]);
                                    $(`input[name="asisten_anastesi_${i}_rs[${id}]"]`).val(item[
                                        `asisten_anastesi_${i}_rs`]);
                                }

                                // dokter tambahan
                                for (let i = 1; i <= 5; i++) {
                                    $(`input[name="dokter_tambahan_${i}_dokter[${id}]"]`).val(
                                        item[`dokter_tambahan_${i}_dokter`]);
                                    $(`input[name="dokter_tambahan_${i}_rs[${id}]"]`).val(item[
                                        `dokter_tambahan_${i}_rs`]);
                                }

                                // alat & ruang
                                $(`input[name="ruang_operasi[${id}]"]`).val(item.ruang_operasi);
                                $(`input[name="bmhp[${id}]"]`).val(item.bmhp);
                                $(`input[name="alat_dokter[${id}]"]`).val(item.alat_dokter);
                                $(`input[name="alat_rs[${id}]"]`).val(item.alat_rs);
                            });
                        } else {
                            $('tbody input').val(0); // reset semua
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
                const operasiId = @json($tindakan_operasi->id);
                const url =
                    "{{ route('master-data.tindakan_operasi.tarif.store', ['operasiId' => ':operasiId', 'grupPenjaminId' => ':grupPenjaminId']) }}"
                    .replace(':operasiId', operasiId)
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
