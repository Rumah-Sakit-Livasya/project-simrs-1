@extends('pages.simrs.erm.index')
@section('erm')
    {{-- Header Pasien --}}
    <div class="p-3 tab-content">
        @include('pages.simrs.erm.partials.detail-pasien')
    </div>
    <hr>

    <div class="card">
        <form id="form-discharge-planning" action="javascript:void(0)">
            @csrf
            <input type="hidden" name="registration_id" value="{{ $registration->id }}">

            <div class="card-body">
                <h3 class="text-center text-success font-weight-bold">RENCANA PULANG (DISCHARGE PLANNING)</h3>
                <hr>

                {{-- SKRINING FAKTOR RESIKO --}}
                <h4 class="text-primary mt-4">Skrining Faktor Resiko Pasien Pulang</h4>
                @include('pages.simrs.erm.form.perawat.component.discharge-planning-skrining', [
                    'data' => $pengkajian->skrining_faktor_resiko ?? [],
                ])

                {{-- RENCANA PERAWATAN --}}
                <h4 class="text-primary mt-4">Rencana Perawatan Pasien Untuk Di Rumah</h4>
                @include('pages.simrs.erm.form.perawat.component.discharge-planning-rencana', [
                    'data' => $pengkajian->rencana_perawatan_rumah ?? [],
                ])

                {{-- HAL-HAL YANG PERLU DIPERHATIKAN --}}
                <h4 class="text-primary mt-4">Hal-Hal Yang Perlu Diperhatikan Saat Pasien Akan Pulang/Dirumah</h4>
                @include('pages.simrs.erm.form.perawat.component.discharge-planning-perhatian', [
                    'data' => $pengkajian->hal_diperhatikan ?? [],
                ])

                {{-- YANG MEMBERI PENJELASAN --}}
                <h4 class="text-primary mt-4">Yang Memberi Penjelasan/Informasi</h4>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Petugas</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Tanggal Penjelasan</label>
                        <input type="date" name="tgl_penjelasan" class="form-control"
                            value="{{ optional($pengkajian->waktu_penjelasan)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Jam Penjelasan</label>
                        <input type="time" name="jam_penjelasan" class="form-control"
                            value="{{ optional($pengkajian->waktu_penjelasan)->format('H:i') }}">
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" id="btn-save-discharge">Simpan Data</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // AJAX untuk menyimpan form
            $('#form-discharge-planning').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const saveButton = $('#btn-save-discharge');

                saveButton.prop('disabled', true).html('Menyimpan...');

                $.ajax({
                    url: "{{ route('erm.discharge-planning.store') }}",
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire('Sukses!', response.success, 'success');
                    },
                    error: function(jqXHR) {
                        let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                        if (jqXHR.status === 422) {
                            errorMsg = Object.values(jqXHR.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMsg
                        });
                    },
                    complete: function() {
                        saveButton.prop('disabled', false).html('Simpan Data');
                    }
                });
            });
        });
    </script>
@endpush
