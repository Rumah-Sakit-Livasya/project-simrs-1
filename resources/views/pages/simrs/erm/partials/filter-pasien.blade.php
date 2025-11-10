<div id="js-slide-left"
    class="h-100 flex-wrap flex-shrink-0 position-relative slide-on-mobile slide-on-mobile-left bg-primary-200 pattern-0 pt-3">
    <div class="position-fixed h-100" style="width: 20rem !important">
        <row class="justify-content-center">
            <div class="col">
                <form action="javascript:void(0)" method="POST" id="filter_pasien">
                    @csrf
                    <div class="form-group mb-2">
                        <select class="select2 form-control @error('departement_id') is-invalid @enderror filter-pasien"
                            name="departement_id" id="departement_id">
                            @if ($path === 'igd')
                                @foreach ($departements as $departement)
                                    <option value=""></option>
                                    <option value="{{ $departement->id }}"
                                        {{ $departement->id == $departement->id ? 'selected' : '' }}>
                                        {{ $departement->name }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($departements as $departement)
                                    <option value=""></option>
                                    <option value="{{ $departement->id }}"
                                        {{ ($registration->departement_id ?? '') == $departement->id ? 'selected' : '' }}>
                                        {{ $departement->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('departement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <select class="select2 form-control @error('doctor_id') is-invalid @enderror filter-pasien"
                            name="doctor_id" id="doctor_id">
                            <option value=""></option>
                        </select>

                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" id="nama_pasien" name="nama_pasien" class="form-control filter-pasien"
                            placeholder="Nama Pasien">
                    </div>
                    <div class="form-group">
                        {{-- <button type="submit" class="btn btn-primary w-100">Submit</button> --}}
                    </div>
                </form>

                {{-- DAFTAR PASIEN --}}
                @include('pages.simrs.erm.partials.list-pasien')
            </div>
        </row>
    </div>
</div>
