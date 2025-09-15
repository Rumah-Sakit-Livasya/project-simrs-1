<div class="card-actionbar-row " id="group-print-pasien">
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.kartu.pasien', ['patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? '')))]) }}', '_blank', 'width=400,height=400'); return false;">
        <i class="mdi mdi-printer"></i> Kartu pasien
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.identitas.pasien', ['patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? ''))), 'regId' => $registration->id ?? ($regId ?? '')]) }}','p_card', 400,400,'yes'); return false;">
        <i class="mdi mdi-printer"></i> Identitas Pasien
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.label.rm.pdf', ['patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? '')))]) }}', '_blank', 'width=400,height=400'); return false;">
        <i class="mdi mdi-printer"></i> Label RM (PDF)
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.label.rm', ['patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? '')))]) }}', '_blank', 'width=400,height=400'); return false;">
        <i class="mdi mdi-printer"></i> Label RM
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.label.gelang.anak', ['patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? '')))]) }}', '_blank', 'width=400,height=400'); return false;">
        <i class="mdi mdi-printer"></i> Label Gelang Anak
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.label.gelang.dewasa', ['patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? '')))]) }}', '_blank', 'width=400,height=400'); return false;">
        <i class="mdi mdi-printer"></i> Label Gelang Dewasa
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.tracer', [
            'patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? ''))),
            'registration' => $registration->id ?? ($registration ?? ''),
        ]) }}', '_blank', 'toolbar=0,location=0,menubar=0,scrollbars=1,resizable=1,status=0,width=' + screen.width + ',height=' + screen.height + ',top=0,left=0'); return false;">
        <i class="mdi mdi-printer"></i> Tracer
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.charges.slip', [
            'patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? ''))),
            'registration' => $registration->id ?? ($registration ?? ''),
        ]) }}', '_blank', 'width=400,height=400'); return false;">
        <i class="mdi mdi-printer"></i> Charges Slip
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.surat.keterangan.lahir', [
            'patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? ''))),
            'registration' => $registration->id ?? ($registration ?? ''),
        ]) }}', '_blank', 'toolbar=0,location=0,menubar=0,scrollbars=1,resizable=1,status=0,width=' + screen.width + ',height=' + screen.height + ',top=0,left=0'); return false;">
        <i class="mdi mdi-printer"></i> Surat Keterangan Lahir
    </button>
    <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
        onclick="window.open('{{ route('print.general.consent', [
            'patient' => $registration->patient_id ?? ($registration->patient->id ?? ($patient->id ?? ($patient ?? ''))),
            'registration' => $registration->id ?? ($registration ?? ''),
        ]) }}', '_blank', 'toolbar=0,location=0,menubar=0,scrollbars=1,resizable=1,status=0,width=' + screen.width + ',height=' + screen.height + ',top=0,left=0'); return false;">
        <i class="mdi mdi-printer"></i> General Consent
    </button>
    <div class="col-sm-12"></div>
    <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
        onclick="popupFull('http://192.168.1.253/real/vclaim/print_sep_pdf/180789'); return false;"><i
            class="mdi mdi-printer"></i> Print SEP</button>
    <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
        onclick="popupFull('http://192.168.1.253/real/vclaim/sep_internal/180789'); return false;"><i
            class="mdi mdi-printer"></i> Cek SEP Internal</button>
    <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
        onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/2/180789'); return false;"><i
            class="mdi mdi-printer"></i> Rencana Kontrol</button>
    <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
        onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/1/180789'); return false;"><i
            class="mdi mdi-printer"></i> Surat SPRI</button>
    <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
        onclick="popupFull('http://192.168.1.253/real/vclaim/rujukan/180789'); return false;"><i
            class="mdi mdi-printer"></i> Rujukan BPJS</button>
    <button class="btn btn-danger pull-left waves-effect"
        onclick="popupwindow('http://192.168.1.253/real/vclaim/pengajuan_add/180789','p_card', 900,600,'no'); return false;"><i
            class="mdi mdi-printer" style="margin: 2px"></i> Pengajuan</button>
    <button style="display: ;" class="btn btn-success pull-left waves-effect"
        onclick="popupwindow('http://192.168.1.253/real/satu_sehat/get_encounter/180789','p_card', 900,600,'no'); return false;"><i
            class="mdi mdi-history" style="margin: 2px"></i> Status Kunjungan
        (SatuSehat)</button>
</div>
