<div class="row">
    <div class="col-xl-12">
        {{-- Panel Form Pencarian --}}
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form action="{{ route('laboratorium.list-order') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="registration_date">Tgl. Order</label>
                                <input type="text" class="form-control" id="datepicker-1" name="registration_date"
                                    value="{{ request('registration_date', date('Y-m-d') . ' - ' . date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="medical_record_number">No. RM</label>
                                <input type="text" value="{{ request('medical_record_number') }}"
                                    class="form-control" id="medical_record_number" name="medical_record_number"
                                    placeholder="Contoh: 12-34-56" onkeyup="formatAngka(this)">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="name">Nama Pasien</label>
                                <input type="text" value="{{ request('name') }}" class="form-control" id="name"
                                    name="name" placeholder="Cari nama pasien...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="registration_number">No. Registrasi</label>
                                <input type="text" value="{{ request('registration_number') }}" class="form-control"
                                    id="registration_number" name="registration_number"
                                    placeholder="Cari nomor registrasi...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="no_order">No. Order</label>
                                <input type="text" value="{{ request('no_order') }}" class="form-control"
                                    id="no_order" name="no_order" placeholder="Cari nomor order...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                                <a href="{{ route('laboratorium.order') }}" class="btn btn-success">
                                    <span class="fal fa-plus mr-1"></span>
                                    Order Baru
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
