{{-- resources/views/components/doctor-select.blade.php --}}
@props(['id', 'name', 'doctors', 'selected' => ''])

<select class="select2 form-control w-100" id="{{ $id }}" name="{{ $name }}">
    <option value=""></option>
    @foreach ($doctors as $department => $doctorList)
        <optgroup label="{{ $department }}">
            @foreach ($doctorList as $doctor)
                <option value="{{ $doctor->id }}" data-departement="{{ $department }}"
                    {{ $selected == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->employee->fullname }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
