@php
    $masalah = $data['masalah_keperawatan'] ?? [];
@endphp
<h4 class="text-primary mt-4 font-weight-bold">MASALAH KEPERAWATAN YANG DITEMUKAN</h4>
<div class="row">
    @foreach ([
        'gangguan_termogulasi' => 'Gangguan termogulasi',
        'nyeri' => 'Nyeri',
        'defisit_nutrisi' => 'Defisit nutrisi',
        'kekurangan_cairan' => 'Kekurangan cairan',
        'resiko_infeksi' => 'Resiko infeksi',
        'resiko_aspirasi' => 'Resiko aspirasi',
        'penurunan_curah_jantung' => 'Penurunan curah jantung',
        'intoleransi_aktivitas' => 'Intoleransi aktivitas',
        'pola_nafas_tidak_efektif' => 'Pola nafas tidak efektif',
        'ansietas' => 'Ansietas',
        'resiko_syok' => 'Resiko syok',
        'gangguan_mobilitas_fisik' => 'Gangguan mobilitas fisik',
        'gangguan_pertukaran_gas' => 'Gangguan pertukaran gas',
        'bersihan_jalan_nafas' => 'Bersihan jalan nafas tidak efektif',
        'perfusi_jaringan_perifer' => 'Ketidakefektifan perfusi jaringan perifer',
    ] as $key => $label)
        <div class="col-md-4">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" id="masalah_{{ $key }}" name="masalah_keperawatan[{{ $key }}]"
                    value="1" class="custom-control-input" @checked(isset($masalah[$key]))>
                <label class="custom-control-label" for="masalah_{{ $key }}">{{ $label }}</label>
            </div>
        </div>
    @endforeach
</div>
