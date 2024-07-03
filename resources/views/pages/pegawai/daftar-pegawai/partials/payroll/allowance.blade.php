<!-- datatable start -->
<div class="table-responsive">
    <table id="allowance-table" class="datatable table table-bordered table-hover table-striped w-100">
        <thead>
            <tr>
                <th style="white-space: nowrap">No</th>
                <th style="white-space: nowrap">Full Name</th>
                <th style="white-space: nowrap">Job Position</th>
                <th style="white-space: nowrap">Organization</th>
                <th style="white-space: nowrap">Basic Salary</th>
                <th style="white-space: nowrap">Tunjangan Jabatan</th>
                <th style="white-space: nowrap">Tunjangan Profesi</th>
                <th style="white-space: nowrap">Tunjangan Makanan & Transport</th>
                <th style="white-space: nowrap">Tunjangan Masa Kerja</th>
                <th style="white-space: nowrap">Guarantee Fee</th>
                <th style="white-space: nowrap">Uang Duduk</th>
                <th style="white-space: nowrap">Tax Allowance</th>
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
                    <td>{{ isset($employee->salary->basic_salary) ? rp($employee->salary->basic_salary) : 'Rp 0' }}</td>
                    <td>{{ isset($employee->salary->tunjangan_jabatan) ? rp($employee->salary->tunjangan_jabatan) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->salary->tunjangan_profesi) ? rp($employee->salary->tunjangan_profesi) : 'Rp 0' }}
                    <td>{{ isset($employee->salary->tunjangan_makan_dan_transport) ? rp($employee->salary->tunjangan_makan_dan_transport) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->salary->tunjangan_masa_kerja) ? rp($employee->salary->tunjangan_masa_kerja) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->salary->guarantee_fee) ? rp($employee->salary->guarantee_fee) : 'Rp 0' }}
                    </td>
                    <td>{{ isset($employee->salary->uang_duduk) ? rp($employee->salary->uang_duduk) : 'Rp 0' }}</td>
                    <td>{{ isset($employee->salary->tax_allowance) ? rp($employee->salary->tax_allowance) : 'Rp 0' }}
                    </td>
                    <td style="white-space: nowrap">
                        <button type="button" data-backdrop="static" data-keyboard="false"
                            class="badge mx-1 badge-info p-2 border-0 text-white btn-edit"
                            data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-info-500&quot;></div></div>"
                            data-toggle="tooltip" data-id="{{ $employee->id }}" title="Ubah Komponen"
                            onclick="btnSalary(event)">
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
