@extends('pages.simrs.erm.index')
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-checklist-keperawatan" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">
            @php
                // Ambil data checklist dari model, atau array kosong jika belum ada
                $data = $pengkajian->checklist_data ?? [];
            @endphp

            <div class="card-body">
                <h3 class="text-center text-success font-weight-bold mb-4">CHECKLIST PELAKSANAAN/KEGIATAN KEPERAWATAN</h3>
                <div class="alert alert-info">
                    <strong>Petunjuk:</strong> Form ini menyimpan data per tanggal. Pilih tanggal di bawah untuk melihat
                    atau mengisi data pada hari tersebut.
                </div>

                <div class="form-group">
                    <label for="checklist_date" class="font-weight-bold">Pilih Tanggal Pelaksanaan</label>
                    <input type="date" class="form-control" id="checklist_date" name="tanggal"
                        value="{{ date('Y-m-d') }}">
                </div>
                <hr>

                {{-- Tabel Kegiatan --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center bg-light">
                            <tr>
                                <th class="align-middle">No</th>
                                <th class="align-middle">Kegiatan</th>
                                <th class="align-middle">Jam</th>
                                <th class="align-middle">Tindakan</th>
                                <th class="align-middle">Jam</th>
                                <th class="align-middle">Tindakan</th>
                                <th class="align-middle">Jam</th>
                                <th class="align-middle">Tindakan</th>
                                <th class="align-middle">Jam</th>
                                <th class="align-middle">Tindakan</th>
                                <th class="align-middle">Jam</th>
                                <th class="align-middle">Evaluasi Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="12" class="bg-light font-weight-bold">KEGIATAN RUTINITAS</td>
                            </tr>
                            @php
                                $kegiatanRutin = [
                                    'memandikan' => 'Memandikan',
                                    'bantu_bak' => 'Membantu BAK',
                                    'bantu_bab' => 'Membantu BAB',
                                    'beri_makan' => 'Memberikan makanan oral/NGT',
                                    'ganti_laken' => 'Mengganti Laken',
                                    'observasi_ttv' => 'Mengobservasi TTV dan KU',
                                    'personal_hygiene' => 'Melakukan personal hygiene',
                                    'rawat_luka' => 'Melakukan perawatan luka',
                                    'observasi_infus' => 'Melakukan observasi area infus',
                                    'atur_posisi' => 'Mengatur posisi tidur',
                                    'observasi_djj' => 'Melakukan observasi DJJ',
                                    'observasi_tfu' => 'Melakukan obs TFU',
                                    'observasi_perdarahan' => 'Observasi perdarahan',
                                    'beri_obat_oral' => 'Memberikan Obat Oral',
                                    'beri_obat_injeksi' => 'Memberikan Obat Injeksi',
                                    'pasang_infus' => 'Memasang Infus',
                                    'pasang_kateter' => 'Memasang Kateter',
                                    'pasang_ngt' => 'Memasang NGT',
                                    'aff_infus' => 'AFF Infus',
                                    'kompres_hangat' => 'Memberikan Kompres hangat',
                                    'skin_test' => 'Skin Test',
                                    'pasang_transfusi' => 'Memasang Transfusi darah',
                                ];
                            @endphp
                            @foreach ($kegiatanRutin as $key => $label)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $label }}</td>
                                    @for ($i = 0; $i < 5; $i++)
                                        <td><input type="time" class="form-control form-control-sm"
                                                name="rutinitas[{{ $key }}][{{ $i }}][jam]"
                                                value="{{ $data['rutinitas'][$key][$i]['jam'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control form-control-sm"
                                                name="rutinitas[{{ $key }}][{{ $i }}][tindakan]"
                                                value="{{ $data['rutinitas'][$key][$i]['tindakan'] ?? '' }}"></td>
                                    @endfor
                                    <td>
                                        <textarea class="form-control form-control-sm" name="rutinitas[{{ $key }}][evaluasi]" rows="1">{{ $data['rutinitas'][$key]['evaluasi'] ?? '' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="12" class="bg-light font-weight-bold">KEGIATAN TAMBAHAN (NON RUTINITAS)</td>
                            </tr>
                            @for ($j = 0; $j < 5; $j++)
                                <tr>
                                    <td class="text-center">{{ $j == 0 ? 1 : '' }}</td>
                                    <td><input type="text" placeholder="Ketik kegiatan tambahan..."
                                            class="form-control form-control-sm"
                                            name="tambahan[{{ $j }}][kegiatan]"
                                            value="{{ $data['tambahan'][$j]['kegiatan'] ?? '' }}"></td>
                                    @for ($i = 0; $i < 5; $i++)
                                        <td><input type="time" class="form-control form-control-sm"
                                                name="tambahan[{{ $j }}][{{ $i }}][jam]"
                                                value="{{ $data['tambahan'][$j][$i]['jam'] ?? '' }}"></td>
                                        <td><input type="text" class="form-control form-control-sm"
                                                name="tambahan[{{ $j }}][{{ $i }}][tindakan]"
                                                value="{{ $data['tambahan'][$j][$i]['tindakan'] ?? '' }}"></td>
                                    @endfor
                                    <td>
                                        <textarea class="form-control form-control-sm" name="tambahan[{{ $j }}][evaluasi]" rows="1">{{ $data['tambahan'][$j]['evaluasi'] ?? '' }}</textarea>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-checklist">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Logika untuk menampilkan data berdasarkan tanggal yang dipilih
            // (Ini memerlukan pengembangan lebih lanjut dengan AJAX untuk mengambil data per tanggal)

            // AJAX untuk menyimpan form
            $('#form-checklist-keperawatan').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const saveButton = $('#btn-save-checklist');

                saveButton.prop('disabled', true).html('Menyimpan...');

                $.ajax({
                    url: "{{ route('erm.checklist-keperawatan.store') }}",
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire('Sukses!', response.success, 'success');
                    },
                    error: function(jqXHR) {
                        Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    },
                    complete: function() {
                        saveButton.prop('disabled', false).html('Simpan Data');
                    }
                });
            });
        });
    </script>
@endpush
