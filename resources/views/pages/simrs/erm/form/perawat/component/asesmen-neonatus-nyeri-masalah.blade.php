@php
    $nyeri = $data['asesmen_nyeri_neonatus'] ?? [];
    $masalah = $data['masalah_keperawatan'] ?? [];
@endphp
<hr>
<h4 class="text-primary mt-4 font-weight-bold">ASESMEN NYERI & MASALAH KEPERAWATAN</h4>

{{-- Asesmen Nyeri FLACC --}}
<div class="card">
    <div class="card-header bg-light"><b>Asesmen Nyeri (Skala FLACC)</b></div>
    <div class="card-body">
        {{-- ... tabel FLACC direfaktor sama seperti di asesmen-anak-nyeri-jatuh.blade.php ... --}}
        {{-- Gunakan name="asesmen_nyeri_neonatus[flacc][...]" --}}
    </div>
</div>

{{-- Masalah Keperawatan --}}
<div class="card mt-3">
    <div class="card-header bg-light"><b>Masalah Keperawatan yang Ditemukan</b></div>
    <div class="card-body">
        <div class="row">
            @foreach ([
        'resiko_aspirasi' => 'Resiko aspirasi',
        'gangguan_thermoregulasi' => 'Gangguan thermoregulasi',
        // ... dan seterusnya
    ] as $key => $label)
                <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="masalah_neo_{{ $key }}"
                            name="masalah_keperawatan[{{ $key }}]" value="1" class="custom-control-input"
                            @checked(isset($masalah[$key]))>
                        <label class="custom-control-label"
                            for="masalah_neo_{{ $key }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
