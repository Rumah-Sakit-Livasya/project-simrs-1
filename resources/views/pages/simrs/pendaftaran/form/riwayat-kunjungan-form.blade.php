<div class="modal fade" id="riwayat-kunjungan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Riwayat Kunjungan Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover m-0">
                        <thead class="thead-themed">
                            <tr>
                                <th>No Reg</th>
                                <th>Tgl Masuk</th>
                                <th>Tgl Keluar</th>
                                <th>Departement</th>
                                <th>Dokter</th>
                                <th>Diagnosa Awal</th>
                                <th>Diagnosa Akhir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patient->registration as $registration)
                                <tr>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ $registration->registration_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ tgl_waktu($registration->registration_date) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ tgl_waktu($registration->registration_close_date) }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ $registration->doctor->department_from_doctors->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ $registration->doctor->employee->fullname }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ $registration->diagnosa_awal }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ $registration->diagnosa_awal }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('detail.registrasi.pasien', $registration->id) }}">
                                            {{ $registration->status }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
