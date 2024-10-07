<div class="card-head">
    <div class="header-pasien">
        @if ($registration->patient->gender == 'Laki-laki')
            <img src="http://103.191.196.126:8888/real/include/avatar/man-icon.png" width="100">
        @else
            <img src="http://103.191.196.126:8888/real/include/avatar/woman-icon.png" width="100">
        @endif
        <div>
            <div class="detail-regist-name" onclick="reg_patient()">
                {{ $registration->patient->name }}</div>
            <div class="birth">{{ formatTanggalDetail($registration->patient->date_of_birth) }}
                @if ($registration->patient->gender == 'Laki-laki')
                    <i class="mdi mdi-gender-male"></i>
                @else
                    <i class="mdi mdi-gender-female"></i>
                @endif
            </div>
            <div>RM {{ $registration->patient->medical_record_number }}</div>
            <div class="birth">{{ $registration->penjamin->nama_perusahaan }}</div>
            <div>
                Info Billing: <span title="Billing: 164.574, Proses Order: 0"
                    style="color: green; font-weight: 400;text-decoration: underline; margin-right: 5px;"
                    id="info_billing">164.574</span><i class="fa fa-refresh pointer" id="get_info_bill"></i>
            </div>
            <!-- tambahan by rizal -->
            <div class="detail-alergi" onclick="openForm()">Tidak ada alergi</div>
        </div>
        @if ($registration->doctor->employee->gender == 'Laki-laki')
            <img src="http://103.191.196.126:8888/real/include/avatar/man-icon.png" width="100">
        @else
            <img src="http://103.191.196.126:8888/real/include/avatar/woman-icon.png" width="100">
        @endif
        <div>
            <div class="detail-regist-name">{{ $registration->doctor->employee->fullname }}</div>
            <div class="birth">{{ $registration->doctor->department_from_doctors->name }}</div>
            <div>Reg {{ $registration->registration_number }}
                ({{ tgl_waktu($registration->registration_date) }})
            </div>
            <div>{{ ucwords(str_replace('-', ' ', $registration->registration_type)) }}</div>
        </div>
    </div>
</div>
<div class="card-actionbar p-3">
    <div class="card-actionbar-row-left">
        <button type="button" class="btn btn-primary waves-effect waves-light margin-left-xl" id="panggil"
            onclick="panggil()"><span class="glyphicon glyphicon-music "></span>&nbsp;&nbsp;Panggil Antrian</button>
        <button class="btn btn-warning"
            onclick="popupFull('http://103.191.196.126:8888/real/antrol_bpjs/update_waktu_antrean_vclaim/2409047399','p_card', 900,600,'no'); return false;">
            <i class="mdi mdi-update"></i> Antrol BPJS
        </button>
        <button class="btn btn-danger waves-effect waves-light" onclick="showIcare();"><i
                class="mdi mdi-account-convert"></i> Bridging Icare</button>
        <button class="btn btn-info margin-left-md" id="popup_klpcm">
            <i class="mdi mdi-file" id="mdi-chk"></i> KLPCM
        </button>
    </div>
</div>
