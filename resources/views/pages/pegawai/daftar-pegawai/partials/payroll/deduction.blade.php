<!-- datatable start -->
<div class="table-responsive">
    <table id="deduction-table" class="datatable table table-bordered table-hover table-striped w-100">
        <thead>
            <tr>
                <th style="white-space: nowrap">No</th>
                <th style="white-space: nowrap">Full Name</th>
                <th style="white-space: nowrap">Job Position</th>
                <th style="white-space: nowrap">Organization</th>
                <th style="white-space: nowrap">Potongan Keterlambatan</th>
                <th style="white-space: nowrap">Potongan Izin</th>
                <th style="white-space: nowrap">Potongan Sakit</th>
                <th style="white-space: nowrap">Simpanan Pokok</th>
                <th style="white-space: nowrap">Potongan Koperasi</th>
                <th style="white-space: nowrap">Potongan Absensi</th>
                <th style="white-space: nowrap">Potongan BPJS Kesehatan</th>
                <th style="white-space: nowrap">Potongan BPJS Ketenagakerjaan</th>
                <th style="white-space: nowrap">Potongan Pajak</th>
                <th style="white-space: nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->fullname }}</td>
                    <td>{{ $employee->jobPosition->name }}</td>
                    <td>{{ $employee->organization->name }}</td>
                    <td>{{ isset($employee->deduction->potongan_keterlambatan) ? rp($employee->deduction->potongan_keterlambatan) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->potongan_izin) ? rp($employee->deduction->potongan_izin) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->potongan_sakit) ? rp($employee->deduction->potongan_sakit) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->simpanan_pokok) ? rp($employee->deduction->simpanan_pokok) : 'Rp 0' }}
                    <td>{{ isset($employee->deduction->potongan_koperasi) ? rp($employee->deduction->potongan_koperasi) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->potongan_absensi) ? rp($employee->deduction->potongan_absensi) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->potongan_bpjs_kesehatan) ? rp($employee->deduction->potongan_bpjs_kesehatan) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->potongan_bpjs_ketenagakerjaan) ? rp($employee->deduction->potongan_bpjs_ketenagakerjaan) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->deduction->potongan_pajak) ? rp($employee->deduction->potongan_pajak) : 'Rp 0' }}
                    </td>
                    <td style="white-space: nowrap">
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-info p-2 border-0 text-white btn-edit"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-info-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Ubah Komponen"
                            onclick="btnDeduction(event)">
                            <span class="fal fa-pencil ikon-edit"></span>
                            <div class="span spinner-text d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- datatable end -->
