@if ($registrations->isEmpty())
    <p class="mt-3">Tidak ada pasien yang terdaftar hari ini.</p>
@else
<ul>
    @foreach($registrations as $patient)
        <li style="background: #fdf7fb">
            <div class="number mr-2">{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</div>
            <div class="patient-name" data-pregid="{{ $patient->patient->medical_record_number }}">
                <a href="{{route('poliklinik.daftar-pasien', ['registration' => $patient->registration_number, 'menu' => 'pengkajian_perawat'])}}">
                {{ $patient->patient->name }}
            </a>
            </div>
            <div class="birth">
                {{ \Carbon\Carbon::parse($patient->patient->date_of_birth)->format('d M Y') }} ({{ \Carbon\Carbon::parse($patient->patient->date_of_birth)->age }} Thn) 
                <i class="mdi mdi-gender-{{ $patient->gender == 'M' ? 'male' : 'female' }}"></i> 
                <i class="fa fa-check green-text d-none" style="float: right; margin-right: 10px;"></i> 
                <i class="fa fa-check blue-text d-none" style="float: right; margin-right: 20px;"></i>
            </div>
            <div class="rm">No. RM : {{ $patient->patient->medical_record_number }}</div>
        </li>
    @endforeach
</ul>
@endif