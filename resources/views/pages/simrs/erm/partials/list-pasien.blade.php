<style>
    #daftar-pasien::-webkit-scrollbar {
        width: 0px;
    }
</style>
<div class="row" id="daftar-pasien" style="overflow-y: auto; height: 75vh;">
    <div class="col-12">
        @if ($registrations->isEmpty())
            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 40vh;">
                <h5 class="mt-4 mb-2 text-muted font-weight-bold">Belum ada pasien terdaftar</h5>
                <p class="text-secondary text-center mb-0">Silakan cek kembali filter tanggal atau lakukan pendaftaran
                    pasien baru.
                </p>
            </div>
        @else
            <ul>
                @foreach ($registrations as $registration)
                    @php
                        $today = \Carbon\Carbon::today()->format('Y-m-d');
                        $registrationDate = \Carbon\Carbon::parse($registration->registration_date)->format('Y-m-d');

                        // Jika path IGD, hanya tampilkan yang tanggalnya hari ini
                        // Jika path poliklinik (rawat jalan), hanya tampilkan yang tanggalnya hari ini
                        // Jika path lain, hanya tampilkan yang statusnya aktif
                        if ($path === 'igd') {
                            if ($registrationDate !== $today) {
                                continue;
                            }
                        } elseif ($path === 'poliklinik') {
                            if ($registrationDate !== $today) {
                                continue;
                            }
                        } else {
                            if ($registration->status !== 'aktif') {
                                continue;
                            }
                        }

                        $menu = request('menu');
                        if (!$menu) {
                            $menu = $path === 'igd' ? 'triage' : 'pengkajian_perawat';
                        }

                        $query = http_build_query([
                            'registration' => $registration->registration_number,
                            'menu' => $menu,
                        ]);
                    @endphp
                    @if ($path === 'igd')
                        {{-- @if ($registration->registration_type === 'igd') --}}
                        <a href="{{ url('simrs/igd/catatan-medis') . '?' . $query }}">
                            <li style="background: #f5f5f5; border-radius: 11px; padding: 18px;">
                                @php
                                    $kesimpulanRaw = optional($registration->triage)->kesimpulan;
                                    $kesimpulanArray = json_decode($kesimpulanRaw, true); // konversi JSON ke array
                                    $kesimpulan = is_array($kesimpulanArray) ? $kesimpulanArray[0] ?? null : null;

                                    $bgColor = match ($kesimpulan) {
                                        'Hijau' => 'bg-success',
                                        'Kuning' => 'bg-warning',
                                        'Merah' => 'bg-danger',
                                        'Hitam' => 'bg-fusion-900',
                                        default => 'bg-white text-dark',
                                    };
                                @endphp

                                <div class="number mr-4 p-2 text-white text-center {{ $bgColor }}"
                                    style="border-radius: 11px; width: 65px; height: 65px; line-height: 50px;">
                                    {{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="patient-name"
                                    data-pregid="{{ $registration->patient->medical_record_number }}">
                                    {{ Illuminate\Support\Str::limit($registration->patient->name, 23) }}
                                </div>
                                <div class="birth">
                                    {{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d M Y') }}
                                    ({{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->age }}
                                    Thn)
                                    <i
                                        class="mdi mdi-gender-{{ $registration->gender == 'M' ? 'male' : 'female' }}"></i>
                                    <i class="fa fa-check green-text d-none"
                                        style="float: right; margin-right: 10px;"></i>
                                    <i class="fa fa-check blue-text d-none"
                                        style="float: right; margin-right: 20px;"></i>
                                </div>
                                <div class="rm">No. RM :
                                    {{ $registration->patient->medical_record_number }}</div>
                            </li>
                        </a>
                    @elseif ($path === 'poliklinik')
                        <a href="{{ url('simrs/poliklinik/daftar-pasien') . '?' . $query }}">
                            <li style="background: #f5f5f5; border-radius: 11px; padding: 18px;">
                                <div class="number mr-4 p-2 text-center bg-primary text-white"
                                    style="border-radius: 11px; width: 65px; height: 65px; line-height: 50px;">
                                    {{ str_pad($registration->no_urut, 3, '0', STR_PAD_LEFT) }}
                                </div>

                                <div class="patient-name"
                                    data-pregid="{{ $registration->patient->medical_record_number }}">
                                    {{ Illuminate\Support\Str::limit($registration->patient->name, 23) }}
                                </div>
                                <div class="birth">
                                    {{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d M Y') }}
                                    ({{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->age }}
                                    Thn)
                                    <i
                                        class="mdi mdi-gender-{{ $registration->gender == 'M' ? 'male' : 'female' }}"></i>
                                    <i class="fa fa-check green-text d-none"
                                        style="float: right; margin-right: 10px;"></i>
                                    <i class="fa fa-check blue-text d-none"
                                        style="float: right; margin-right: 20px;"></i>
                                </div>
                                <div class="rm">No. RM :
                                    {{ $registration->patient->medical_record_number }}</div>
                            </li>
                        </a>
                    @elseif ($path === 'rawat-inap')
                        <a href="{{ url('simrs/rawat-inap/catatan-medis') . '?' . $query }}">
                            <li style="background: #f5f5f5; border-radius: 11px; padding: 18px;">
                                <div class="number mr-4 p-2 text-center bg-primary text-white"
                                    style="border-radius: 11px; width: 65px; height: 65px; line-height: 50px;">
                                    {{ str_pad($registration->no_urut, 3, '0', STR_PAD_LEFT) }}
                                </div>

                                <div class="patient-name"
                                    data-pregid="{{ $registration->patient->medical_record_number }}">
                                    {{ Illuminate\Support\Str::limit($registration->patient->name, 23) }}
                                </div>
                                <div class="birth">
                                    {{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d M Y') }}
                                    ({{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->age }}
                                    Thn)
                                    <i
                                        class="mdi mdi-gender-{{ $registration->gender == 'M' ? 'male' : 'female' }}"></i>
                                    <i class="fa fa-check green-text d-none"
                                        style="float: right; margin-right: 10px;"></i>
                                    <i class="fa fa-check blue-text d-none"
                                        style="float: right; margin-right: 20px;"></i>
                                </div>
                                <div class="rm">No. RM :
                                    {{ $registration->patient->medical_record_number }}</div>
                            </li>
                        </a>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>
