@extends('inc.layout')
@section('title', 'Organisasi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Tambah Survei</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content p-2">
                            <form action="{{ route('update.survei.kebersihan-kamar', $survei->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="p-1 mb-3">
                                    <div class="mb-2">
                                        <label for="room_maintenance_id" class="form-label">Pilih Kamar</label>
                                        <select name="room_maintenance_id" id="room_maintenance_id"
                                            class="form-control select2">
                                            @foreach ($kamar as $row)
                                                <option value="{{ $row->id }}"
                                                    {{ $row->id == $survei->room_maintenance_id ? 'selected' : '' }}>
                                                    {{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="petugas" class="form-label">Petugas</label>
                                        <input type="text" class="form-control" value="{{ $survei->employee->name }}"
                                            readonly>
                                        <input type="hidden" name="user_id" value="{{ $survei->user_id }}">
                                    </div>
                                    <h2 class="text-white bg-primary p-2 rounded">Kondisi Kamar</h2>
                                    <hr>
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(136, 106, 181, 0.8); color: white;">Lantai</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="lantai_kamar" name="lantai_kamar" rows="4"
                                                placeholder="Deskripsi kondisi lantai kamar">{{ $survei->lantai_kamar }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Sudut -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(136, 106, 181, 0.8); color: white;">Sudut</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="sudut_kamar" name="sudut_kamar" rows="4"
                                                placeholder="Deskripsi kondisi sudut kamar">{{ $survei->sudut_kamar }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Plafon -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(136, 106, 181, 0.8); color: white;">Plafon</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="plafon_kamar" name="plafon_kamar" rows="4"
                                                placeholder="Deskripsi kondisi plafon kamar">{{ $survei->plafon_kamar }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Dinding -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(136, 106, 181, 0.8); color: white;">Dinding</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="dinding_kamar" name="dinding_kamar" rows="4"
                                                placeholder="Deskripsi kondisi dinding kamar">{{ $survei->dinding_kamar }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Bed Head -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(136, 106, 181, 0.8); color: white;">Bed Head</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="bed_head" name="bed_head" rows="4"
                                                placeholder="Deskripsi kondisi bed head">{{ $survei->bed_head }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kondisi Toilet Section -->
                                <div class="p-1 mb-3">
                                    <h2 class="text-white bg-danger p-2 rounded">Kondisi Toilet</h2>
                                    <hr>

                                    <!-- Lantai -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(253, 57, 149, 0.8); color: white;">Lantai</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="lantai_toilet" name="lantai_toilet" rows="4"
                                                placeholder="Deskripsi kondisi lantai toilet">{{ $survei->lantai_toilet }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Wastafel -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(253, 57, 149, 0.8); color: white;">Wastafel</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="wastafel_toilet" name="wastafel_toilet" rows="4"
                                                placeholder="Deskripsi kondisi wastafel toilet">{{ $survei->wastafel_toilet }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Kloset -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(253, 57, 149, 0.8); color: white;">Kloset</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="closet_toilet" name="closet_toilet" rows="4"
                                                placeholder="Deskripsi kondisi kloset toilet">{{ $survei->closet_toilet }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Kaca -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(253, 57, 149, 0.8); color: white;">Kaca</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="kaca_toilet" name="kaca_toilet" rows="4"
                                                placeholder="Deskripsi kondisi kaca toilet">{{ $survei->kaca_toilet }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Dinding -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(253, 57, 149, 0.8); color: white;">Dinding</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="dinding_toilet" name="dinding_toilet" rows="4"
                                                placeholder="Deskripsi kondisi dinding toilet">{{ $survei->dinding_toilet }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Shower -->
                                    <div class="card mt-2">
                                        <div class="card-header p-2"
                                            style="background-color: rgba(253, 57, 149, 0.8); color: white;">Shower</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 rounded-0" id="shower_toilet" name="shower_toilet" rows="4"
                                                placeholder="Deskripsi kondisi shower toilet">{{ $survei->shower_toilet }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dokumentasi Section -->
                                <div class="form-group p-1 mb-4">
                                    {{-- <label class="form-label" for="dokumentasi">Dokumentasi</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="dokumentasi"
                                            name="dokumentasi">
                                        <label class="custom-file-label" for="dokumentasi">Choose file</label>
                                    </div> --}}
                                    <button type="submit" class="btn btn-primary mt-3 btn-block">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
