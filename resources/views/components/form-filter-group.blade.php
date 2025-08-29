{{--
    Komponen ini membungkus sebuah grup form filter (label + input)
    agar memiliki layout yang konsisten.

    Props:
    - $label: Teks yang akan ditampilkan di label.
    - $for (opsional): Menghubungkan label ke input, baik untuk aksesibilitas.
--}}
@props(['label', 'for' => ''])

<div class="col-lg-6 mb-2">
    <div class="form-group row">
        <label for="{{ $for }}" class="col-sm-4 col-form-label text-sm-right">{{ $label }}</label>
        <div class="col-sm-8">
            {{-- $slot adalah tempat di mana input/select/button Anda akan dimasukkan --}}
            {{ $slot }}
        </div>
    </div>
</div>
