@if ($registrations->isEmpty())
    <p class="mt-3">Tidak ada pasien yang terdaftar hari ini.</p>
@else
    <ul>
        @foreach ($registrations as $registration)
            <a
                href="{{ route('poliklinik.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_perawat']) }}">
                <li style="background: #f5f5f5; border-radius: 11px; padding: 18px;">
                    @if ($registration->registration_type === 'igd')
                        @php
                            $kesimpulanRaw = optional($registration->triage)->kesimpulan;
                            $kesimpulanArray = json_decode($kesimpulanRaw, true); // konversi JSON ke array
                            $kesimpulan = is_array($kesimpulanArray) ? $kesimpulanArray[0] ?? null : null;

                            $bgColor = match ($kesimpulan) {
                                'Hijau' => 'bg-success',
                                'Kuning' => 'bg-warning',
                                'Merah' => 'bg-danger',
                                'Hitam' => 'bg-dark',
                                default => 'bg-secondary',
                            };
                        @endphp

                        <div class="number mr-4 p-2 text-white text-center {{ $bgColor }}"
                            style="border-radius: 11px; width: 65px; height: 65px; line-height: 50px;">
                            {{ str_pad($registration->no_urut, 3, '0', STR_PAD_LEFT) }}
                        </div>
                    @else
                        <div class="number mr-4 p-2 text-center"
                            style="border-radius: 11px; width: 65px; height: 65px; line-height: 50px;">
                            {{ str_pad($registration->no_urut, 3, '0', STR_PAD_LEFT) }}
                        </div>
                    @endif
                    <div class="patient-name" data-pregid="{{ $registration->patient->medical_record_number }}">
                        {{ Illuminate\Support\Str::limit($registration->patient->name, 23) }}
                    </div>
                    <div class="birth">
                        {{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d M Y') }}
                        ({{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->age }} Thn)
                        <i class="mdi mdi-gender-{{ $registration->gender == 'M' ? 'male' : 'female' }}"></i>
                        <i class="fa fa-check green-text d-none" style="float: right; margin-right: 10px;"></i>
                        <i class="fa fa-check blue-text d-none" style="float: right; margin-right: 20px;"></i>
                    </div>
                    <div class="rm">No. RM : {{ $registration->patient->medical_record_number }}</div>
                </li>
            </a>
        @endforeach
    </ul>
@endif
