{{-- ========================================================== --}}
{{-- INDIKATOR LOADING --}}
{{-- ========================================================== --}}
<div id="loading-indicator" style="display: none;">
    <div class="loading-overlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="text-primary d-block mt-2">Memproses Panggilan...</div>
    </div>
</div>

{{-- ========================================================== --}}
{{-- DETAIL PASIEN & DOKTER --}}
{{-- ========================================================== --}}
<div class="panel">
    <div class="panel-container show">
        <div class="panel-content">
            <div class="row">
                {{-- Kolom Informasi Pasien --}}
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-3 d-flex align-items-center justify-content-center">
                            @if ($registration->patient->gender == 'Laki-laki')
                                <img src="{{ asset('img/patient/man-icon.png') }}" alt="Pasien Laki-laki"
                                    class="img-fluid" style="max-height: 120px;">
                            @else
                                <img src="{{ asset('img/patient/woman-icon.png') }}" alt="Pasien Perempuan"
                                    class="img-fluid" style="max-height: 120px;">
                            @endif
                        </div>
                        <div class="col-lg-9">
                            <a href="#">
                                <h5 class="text-danger font-weight-bold">{{ $registration->patient->name }}</h5>
                            </a>
                            <p class="text-muted small mb-1">
                                {{ \Carbon\Carbon::parse($registration->patient->birth_of_date)->isoFormat('DD MMMM YYYY') }}
                                ({{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y thn %m bln %d hr') }})
                            </p>
                            <p class="text-muted small mb-1"><strong>RM:</strong>
                                {{ $registration->patient->medical_record_number }}</p>
                            <p class="text-muted small mb-1"><strong>Penjamin:</strong>
                                {{ $registration->penjamin->nama_perusahaan }}</p>
                            {{-- TODO: Ganti dengan data billing dinamis --}}
                            <p class="text-muted small mb-1"><strong>Info Billing:</strong> <span
                                    class="text-success font-weight-bold">30.000</span></p>
                            {{-- TODO: Ganti dengan data alergi dinamis --}}
                            <p class="text-muted small mb-1">Tidak ada alergi</p>
                        </div>
                    </div>
                </div>

                {{-- Kolom Informasi Dokter & Registrasi --}}
                <div class="col-lg-6">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-3 d-flex align-items-center justify-content-center">
                            @if ($registration->doctor->employee->gender == 'Laki-laki')
                                <img src="{{ asset('img/doctor/man-doctor.png') }}" alt="Dokter Laki-laki"
                                    class="img-fluid" style="max-height: 120px;">
                            @else
                                <img src="{{ asset('img/doctor/woman-doctor.png') }}" alt="Dokter Perempuan"
                                    class="img-fluid" style="max-height: 120px;">
                            @endif
                        </div>
                        <div class="col-lg-9">
                            <a href="#">
                                <h5 class="text-danger font-weight-bold">{{ $registration->doctor->employee->fullname }}
                                </h5>
                            </a>
                            <p class="text-muted small mb-1"><strong>Unit:</strong>
                                {{ $registration->departement->name }}</p>
                            <p class="text-muted small mb-1"><strong>Reg:</strong>
                                {{ $registration->registration_number }}
                                ({{ \Carbon\Carbon::parse($registration->date)->isoFormat('DD MMM YYYY') }})</p>
                            <p class="text-muted small mb-1">{{ $registration->registration_type }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================== --}}
            {{-- TOMBOL AKSI --}}
            {{-- ========================================================== --}}
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-themed"
                            id="tombol-panggil-pasien" data-registration-id="{{ $registration->id }}"
                            data-plasma-id="{{ $plasmaId ?? 1 }}">
                            <i class="fas fa-bullhorn mr-1"></i> Panggil Antrian
                        </button>
                        <button class="btn btn-warning text-white waves-effect waves-themed"
                            onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/update_waktu_antrean_vclaim/{{ $registration->registration_number }}','p_card', 900,600,'no'); return false;">
                            <i class="fas fa-sync-alt mr-1"></i> Antrol BPJS
                        </button>
                        <button class="btn btn-danger waves-effect waves-themed" onclick="showIcare();">
                            <i class="fas fa-notes-medical mr-1"></i> Bridging Icare
                        </button>
                        <button class="btn btn-info waves-effect waves-themed" id="popup_klpcm">
                            <i class="fas fa-file-alt mr-1"></i> KLPCM
                        </button>
                        <button class="btn btn-danger waves-effect waves-themed"
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
    {{-- CSS untuk loading indicator --}}
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
    </style>
@endpush

@push('scripts')
    {{-- Script untuk tombol panggil --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const panggilButton = document.getElementById('tombol-panggil-pasien');
            const loadingIndicator = document.getElementById('loading-indicator');

            panggilButton.addEventListener('click', function() {
                const registrationId = this.getAttribute('data-registration-id');
                const plasmaId = this.getAttribute('data-plasma-id');

                loadingIndicator.style.display = 'flex'; // Tampilkan loading

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
                            // Jika response bukan 200, throw error untuk ditangkap di .catch
                            return response.json().then(err => {
                                throw new Error(err.message || 'Gagal memanggil pasien.')
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        loadingIndicator.style.display = 'none'; // Sembunyikan loading
                        if (data.success) {
                            // Tampilkan notifikasi sukses (contoh: menggunakan alert)
                            // Untuk pengalaman yang lebih baik, gunakan library notifikasi seperti SweetAlert2 atau Toastr
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        loadingIndicator.style.display = 'none'; // Sembunyikan loading
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                    });
            });
        });
    </script>
@endpush
