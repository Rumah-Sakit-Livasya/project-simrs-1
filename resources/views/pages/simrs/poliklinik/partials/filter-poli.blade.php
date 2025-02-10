<div id="js-slide-left"
    class="flex-wrap flex-shrink-0 position-relative slide-on-mobile slide-on-mobile-left bg-primary-200 pattern-0 p-3">
    <form action="#" method="POST" id="                                                                                                                                                                                                                                                                                     ">
        @csrf
        <div class="form-group mb-2">
            <select class="select2 form-control @error('departement_id') is-invalid @enderror filter-poli" name="departement_id"
                id="departement_id">
                <option value=""></option>
                @foreach ($departements as $departement)
                    <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                @endforeach
            </select>
            @error('departement_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-2">
            <select class="select2 form-control @error('doctor_id') is-invalid @enderror filter-poli" name="doctor_id"
                id="doctor_id">
                <option value=""></option>
                @foreach ($jadwal_dokter as $jadwal)
                    <option value="{{ $jadwal->doctor_id }}">{{ $jadwal->doctor->employee->fullname }}</option>
                @endforeach
            </select>
            @error('doctor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-2">
            <input type="text" id="nama_pasien" name="nama_pasien" class="form-control filter-poli" placeholder="Nama Pasien">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </div>
    </form>
</div>
