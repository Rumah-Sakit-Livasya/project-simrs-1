@props([
    'parameterOrder' => null,
    'allParametersOrdered' => null,
    'nilaiNormals' => null,
    'level' => 0, // Represents the nesting level for indentation
    'totalCount' => null, // Pass by reference to keep track of numbering
    'order' => null,
])

@if ($parameterOrder)
    @php
        // --- Logic to get Nilai Normal (same as before) ---
        $dob = $order->registration->patient->date_of_birth ?? $order->registration_otc->date_of_birth;
        $jenis_kelamin = $order->registration->patient->gender ?? $order->registration_otc->jenis_kelamin;
        $nilai_normal_parameter = $nilaiNormals->first(function ($nilai_normal) use (
            $parameterOrder,
            $dob,
            $jenis_kelamin,
        ) {
            return $nilai_normal->parameter_laboratorium_id == $parameterOrder->parameter_laboratorium_id &&
                isWithinAgeRange($dob, $nilai_normal->dari_umur, $nilai_normal->sampai_umur) &&
                ($nilai_normal->jenis_kelamin == $jenis_kelamin || $nilai_normal->jenis_kelamin == 'Semuanya');
        });
        $tipe_hasil = $parameterOrder->parameter_laboratorium->tipe_hasil;
        $paddingLeft = 1 + $level * 2; // Calculate padding in rem for indentation
    @endphp

    {{-- Main Row for the current parameter --}}
    <tr class="{{ $level > 0 ? 'bg-gray-100' : '' }}">
        <td class="text-center">
            {{-- Only show number for top-level parameters --}}
            @if ($level === 0)
                {{ $totalCount->value++ }}
            @endif
        </td>
        <td style="padding-left: {{ $paddingLeft }}rem;">
            @if ($level > 0)
                <i class="fal fa-level-up fa-rotate-90 text-muted mr-2"></i>
            @endif

            {{-- Use <strong> for top-level parameters --}}
            @if ($level === 0)
                <strong>{{ $parameterOrder->parameter_laboratorium->parameter }}</strong>
                @if ($order->tipe_order == 'cito')
                    <span class="badge badge-danger ml-2">CITO</span>
                @endif
            @else
                {{ $parameterOrder->parameter_laboratorium->parameter }}
            @endif
        </td>

        {{-- Hasil (Result) Column --}}
        <td class="p-2 td-hasil" data-tipe-hasil="{{ $tipe_hasil }}"
            data-nilai-normal="{{ $nilai_normal_parameter->nilai_normal ?? '' }}"
            data-min="{{ $nilai_normal_parameter->min ?? '' }}" data-max="{{ $nilai_normal_parameter->max ?? '' }}">
            @if ($tipe_hasil == 'Angka')
                <input type="number" step="any" class="form-control form-control-sm hasil-input"
                    name="hasil_{{ $parameterOrder->id }}"
                    value="{{ old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) }}" autocomplete="off">
            @elseif ($tipe_hasil == 'Negatif/Positif')
                <select class="form-control form-control-sm hasil-input" name="hasil_{{ $parameterOrder->id }}">
                    <option value="">Pilih</option>
                    <option value="Negatif" @if (old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) == 'Negatif') selected @endif>Negatif</option>
                    <option value="Positif" @if (old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) == 'Positif') selected @endif>Positif</option>
                </select>
            @elseif ($tipe_hasil == 'Reaktif/NonReaktif')
                <select class="form-control form-control-sm hasil-input" name="hasil_{{ $parameterOrder->id }}">
                    <option value="">Pilih</option>
                    <option value="Reaktif" @if (old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) == 'Reaktif') selected @endif>Reaktif</option>
                    <option value="NonReaktif" @if (old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) == 'NonReaktif') selected @endif>NonReaktif</option>
                </select>
            @elseif ($tipe_hasil == 'Text')
                <input type="text" class="form-control form-control-sm hasil-input"
                    name="hasil_{{ $parameterOrder->id }}"
                    value="{{ old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) }}" autocomplete="off">
            @elseif ($tipe_hasil == 'Pilihan')
                <select class="form-control form-control-sm hasil-input" name="hasil_{{ $parameterOrder->id }}">
                    <option value="">Pilih</option>
                    @if ($parameterOrder->parameter_laboratorium->pilihan_hasil)
                        @foreach (explode(',', $parameterOrder->parameter_laboratorium->pilihan_hasil) as $pilihan)
                            <option value="{{ trim($pilihan) }}" @if (old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) == trim($pilihan)) selected @endif>
                                {{ trim($pilihan) }}
                            </option>
                        @endforeach
                    @endif
                </select>
            @else
                <input type="text" class="form-control form-control-sm hasil-input"
                    name="hasil_{{ $parameterOrder->id }}"
                    value="{{ old('hasil_' . $parameterOrder->id, $parameterOrder->hasil) }}" autocomplete="off">
            @endif
        </td>
        <td>{{ $parameterOrder->parameter_laboratorium->satuan }}</td>
        <td>
            @if ($nilai_normal_parameter)
                {{ $tipe_hasil == 'Angka' ? $nilai_normal_parameter->min . ' - ' . $nilai_normal_parameter->max : str_replace('/', ' / ', $nilai_normal_parameter->nilai_normal) }}
            @else
                -
            @endif
        </td>
        <td>
            <textarea class="form-control form-control-sm" name="catatan_{{ $parameterOrder->id }}" rows="1">{{ old('catatan_' . $parameterOrder->id, $parameterOrder->catatan) }}</textarea>
        </td>
        <td class="text-center">
            {{-- Your verifikasi logic here --}}
        </td>
        <td class="text-center">
            {{-- Show delete button only for top-level parameters --}}
            @if ($level === 0 && $parameterOrder->parameter_laboratorium->is_order && $allParametersOrdered->count() > 1)
                <a href="javascript:void(0);" class="btn btn-xs btn-icon btn-danger delete-btn"
                    title="Hapus Pemeriksaan" data-id="{{ $parameterOrder->id }}">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </td>
    </tr>

    {{-- =================================== --}}
    {{-- ========= THE RECURSION =========== --}}
    {{-- =================================== --}}
    @if ($parameterOrder->parameter_laboratorium->subParameters->isNotEmpty())
        @foreach ($parameterOrder->parameter_laboratorium->subParameters as $subParamModel)
            @php
                // Find the corresponding order item for this sub-parameter
                $subParamOrder = $allParametersOrdered->firstWhere('parameter_laboratorium_id', $subParamModel->id);
            @endphp

            {{-- If the sub-parameter exists in the order, call this component again --}}
            @if ($subParamOrder)
                <x-parameter-row :parameterOrder="$subParamOrder" :allParametersOrdered="$allParametersOrdered" :nilaiNormals="$nilaiNormals" :level="$level + 1"
                    :totalCount="$totalCount" :order="$order" />
            @endif
        @endforeach
    @endif
@endif
