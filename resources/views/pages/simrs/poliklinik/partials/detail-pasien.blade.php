<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-3 d-flex align-items-center">
                <img src="http://192.168.1.253/real/include/avatar/man-icon.png" alt="" width="100%">
            </div>
            <div class="col-lg-9">
                <a href="#">
                    <h5 class="text-danger text-decoration-underline">{{$registration->patient->name}}
                    </h5>
                </a>
                <p class="text-small text-secondary mb-1">{{\Carbon\Carbon::parse($registration->patient->birth_of_date)->format('d M Y')}} ({{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y tahun %m bulan %d hari') }}
                    )</p>
                <p class="text-small text-secondary mb-1">RM {{$registration->patient->medical_record_number}}</p>
                <p class="text-small text-secondary mb-1">{{$registration->penjamin->nama_perusahaan}}</p>
                <p class="text-small text-secondary mb-1">Info Billing: <span class="text-success">30.000</span></p>
                <p class="text-small text-secondary mb-1">Tidak ada alergi</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row d-flex align-items-center">
            <div class="col-lg-3 d-flex align-items-center h-100">
                <img src="http://192.168.1.253/real/include/avatar/woman-doctor.png" alt="" width="100%">
            </div>
            <div class="col-lg-9">
                <a href="#">
                    <h5 class="text-danger text-decoration-underline">{{$registration->doctor->employee->fullname}}
                    </h5>
                </a>
                <p class="text-small text-secondary mb-1">{{$registration->departement->name}}</p>
                <p class="text-small text-secondary mb-1">Reg {{$registration->registration_number}} ({{\Carbon\Carbon::parse($registration->date)->format('d M Y')}})</p>
                <p class="text-small text-secondary mb-1">{{$registration->registration_type}}</p>
            </div>
        </div>
    </div>
</div>
<div class="row my-5">
    <div class="col-lg-12">
        <div class="card-actionbar">
            <div class="card-actionbar-row-left">
                <button type="button" class="btn btn-outline-primary waves-effect waves-light margin-left-xl"
                    id="panggil" onclick="panggil()"><span
                        class="glyphicon glyphicon-music "></span>&nbsp;&nbsp;Panggil
                    Antrian</button>
                <button class="btn btn-warning text-white"
                    onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/update_waktu_antrean_vclaim/2411055632','p_card', 900,600,'no'); return false;">
                    <i class="mdi mdi-update"></i> Antrol BPJS
                </button>
                <button class="btn btn-danger waves-effect waves-light" onclick="showIcare();"><i
                        class="mdi mdi-account-convert"></i> Bridging Icare</button>
                <button class="btn btn-info margin-left-md" id="popup_klpcm">
                    <i class="mdi mdi-file" id="mdi-chk"></i> KLPCM
                </button>
                <button class="btn btn-danger"
                    onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/2/197892'); return false;"><i
                        class="mdi mdi-printer"></i> Rencana Kontrol</button>
            </div>
        </div>
    </div>
</div>
