{{-- ========================================================== --}}
{{-- DETAIL PASIEN & DOKTER --}}
{{-- ========================================================== --}}
<div class="panel">
    <div class="panel-container show">
        <div class="panel-content">
            <div class="row">
                {{-- Kolom Informasi Pasien --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 mr-3">
                                    @if ($registration->patient->gender == 'Laki-laki')
                                        <img src="{{ asset('img/patient/man-icon.png') }}" alt="Pasien Laki-laki"
                                            class="rounded-circle border"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('img/patient/woman-icon.png') }}" alt="Pasien Perempuan"
                                            class="rounded-circle border"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 font-weight-bold text-primary">{{ $registration->patient->name }}
                                    </h5>
                                    <div class="d-flex flex-wrap align-items-center mb-2">
                                        <span class="badge badge-light border mr-2 mb-1">
                                            <i class="fas fa-birthday-cake mr-1"></i>
                                            {{ \Carbon\Carbon::parse($registration->patient->birth_of_date)->isoFormat('DD MMM YYYY') }}
                                        </span>
                                        <span class="badge badge-light border mr-2 mb-1">
                                            <i class="fas fa-hourglass-half mr-1"></i>
                                            {{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y thn %m bln %d hr') }}
                                        </span>
                                        <span class="badge badge-light border mb-1">
                                            <i
                                                class="mdi mdi-gender-{{ $registration->patient->gender == 'Laki-laki' ? 'male' : 'female' }} mr-1"></i>
                                            {{ $registration->patient->gender }}
                                        </span>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-6 px-0">
                                            <small class="text-muted"><strong>RM:</strong>
                                                {{ $registration->patient->medical_record_number }}</small>
                                        </div>
                                        <div class="col-6 px-0">
                                            <small class="text-muted"><strong>Penjamin:</strong>
                                                {{ $registration->penjamin->nama_perusahaan }}</small>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-6 px-0">
                                            <small class="text-muted"><strong>Billing:</strong>
                                                <span class="text-success font-weight-bold">30.000</span>
                                            </small>
                                        </div>
                                        <div class="col-6 px-0">
                                            <small class="text-muted"><strong>Alergi:</strong>
                                                <span class="text-danger">Tidak ada</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Informasi Dokter & Registrasi --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                        style="width: 80px; height: 80px; overflow: hidden;">
                                        @if ($registration->doctor->employee->gender == 'Laki-laki')
                                            <img src="{{ asset('img/doctor/man-doctor.png') }}" alt="Dokter Laki-laki"
                                                class="img-fluid rounded-circle border"
                                                style="width: 80px; height: 80px; object-fit: cover;;">
                                        @else
                                            <img src="{{ asset('img/doctor/woman-doctor.png') }}"
                                                alt="Dokter Perempuan" class="img-fluid rounded-circle border"
                                                style="width: 80px; height: 80px; object-fit: cover;;">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <h5 class="mb-0 font-weight-bold text-danger" style="font-size: 1.25rem;">
                                            {{ $registration->doctor->employee->fullname }}
                                        </h5>
                                        <span class="badge badge-pill badge-primary ml-2" style="font-size: 0.85rem;">
                                            {{ ucwords(str_replace('-', ' ', $registration->registration_type)) }}
                                        </span>
                                    </div>
                                    <div class="mb-1">
                                        <span class="text-muted small"><i class="fas fa-hospital mr-1"></i>
                                            <strong>Unit:</strong> {{ $registration->departement->name }}</span>
                                    </div>
                                    <div class="mb-1">
                                        <span class="text-muted small"><i class="fas fa-id-badge mr-1"></i>
                                            <strong>Reg:</strong> {{ $registration->registration_number }}</span>
                                        <span class="text-muted small ml-2"><i class="fas fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($registration->date)->isoFormat('DD MMM YYYY') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================== --}}
            {{-- TOMBOL AKSI --}}
            {{-- ========================================================== --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-2 px-2">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-themed mx-1 my-1"
                            id="tombol-panggil-pasien" data-registration-id="{{ $registration->id }}"
                            data-plasma-id="{{ $plasmaId ?? 1 }}">
                            <i class="fas fa-bullhorn mr-1"></i> Panggil Antrian
                        </button>
                        <button type="button" class="btn btn-warning text-white waves-effect waves-themed mx-1 my-1"
                            onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/update_waktu_antrean_vclaim/{{ $registration->registration_number }}','p_card', 900,600,'no'); return false;">
                            <i class="fas fa-sync-alt mr-1"></i> Antrol BPJS
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-themed mx-1 my-1"
                            onclick="showIcare();">
                            <i class="fas fa-notes-medical mr-1"></i> Bridging Icare
                        </button>
                        <button type="button" class="btn btn-info waves-effect waves-themed mx-1 my-1"
                            id="popup_klpcm">
                            <i class="fas fa-file-alt mr-1"></i> KLPCM
                        </button>
                        <button type="button" class="btn btn-danger waves-effect waves-themed mx-1 my-1"
                            onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/2/{{ $registration->id }}'); return false;">
                            <i class="fas fa-print mr-1"></i> Rencana Kontrol
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('styles')
    {{-- CSS untuk loading indicator dan tombol aksi agar tidak menyebabkan scroll --}}
    <style>
        #loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            pointer-events: all;
        }

        /* Perkecil tombol aksi dan hilangkan scroll jika melebihi */
        .d-flex.flex-wrap.gap-2.px-2 {
            flex-wrap: wrap;
            gap: 0.25rem !important;
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
            overflow-x: visible !important;
            overflow-y: visible !important;
        }

        .d-flex.flex-wrap.gap-2.px-2>.btn {
            font-size: 0.85rem;
            padding: 0.3rem 0.7rem;
            min-width: 0;
            margin: 0.15rem !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- Script untuk tombol panggil --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const panggilButton = document.getElementById('tombol-panggil-pasien');

            panggilButton.addEventListener('click', function() {
                const registrationId = this.getAttribute('data-registration-id');
                const plasmaId = this.getAttribute('data-plasma-id');

                // Tampilkan loading dengan SweetAlert2
                Swal.fire({
                    title: 'Memproses Panggilan...',
                    html: '<div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status"></div>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'swal2-loading-popup'
                    }
                });

                // Kirim request ke API untuk mengubah status antrian
                fetch('{{ route('api.antrian.panggil') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            registration_id: registrationId,
                            plasma_id: plasmaId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.message || 'Gagal memanggil pasien.')
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.close(); // Sembunyikan loading
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close(); // Sembunyikan loading
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: error.message || 'Terjadi kesalahan saat memanggil pasien.'
                        });
                    });
            });
        });
    </script>
@endpush
