@extends('inc.layout-no-side')
@section('title', 'Pilih Diet Pasien')

@section('extended-css')
    {{-- Tidak perlu CSS tambahan, kita akan gunakan kelas dari template --}}
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr"">
                        <h2>Pilih Diet untuk Auto Order</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Menggunakan route() helper untuk URL yang lebih aman --}}
                            <form id="form-pilih-diet" action="{{ route('gizi.diet.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                                {{-- Informasi Pasien --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Nama Pasien</label>
                                    <div class="col-md-10">
                                        <input type="text" value="{{ $registration->patient->name }}"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">RM / No. Reg</label>
                                    <div class="col-md-10">
                                        <input type="text"
                                            value="{{ $registration->patient->medical_record_number }} / {{ $registration->registration_number }}"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right">Diagnosa Awal</label>
                                    <div class="col-md-10">
                                        <input type="text" value="{{ $registration->diagnosa_awal }}"
                                            class="form-control" readonly>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Tampilan CPPT --}}
                                <div class="row mb-3">
                                    <div class="col-md-2 text-right">
                                        <h5 class="fw-500">CPPT Terbaru:</h5>
                                    </div>
                                    <div class="col-md-10">
                                        @if ($registration->cppt && $registration->cppt->isNotEmpty())
                                            <div id="cpptCarousel" class="carousel slide border rounded"
                                                data-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach ($registration->cppt->sortByDesc('created_at') as $cppt)
                                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                            <div class="p-4">
                                                                <h5 class="mb-3">
                                                                    <span class="fw-500">{{ $cppt->tipe_cppt }}</span>
                                                                    <small
                                                                        class="text-muted float-right">{{ $cppt->created_at->addHours(7)->format('d M Y, H:i') }}</small>
                                                                </h5>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <strong>Subjective:</strong>
                                                                        <p class="bg-faded p-2 rounded">
                                                                            {{ $cppt->subjective ?? '-' }}</p>
                                                                        <strong>Objective:</strong>
                                                                        <p class="bg-faded p-2 rounded">
                                                                            {{ $cppt->objective ?? '-' }}</p>
                                                                        <strong>Assessment:</strong>
                                                                        <p class="bg-faded p-2 rounded">
                                                                            {{ $cppt->assesment ?? '-' }}</p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <strong>Planning:</strong>
                                                                        <p class="bg-faded p-2 rounded">
                                                                            {{ $cppt->planning ?? '-' }}</p>
                                                                        <strong>Instruksi:</strong>
                                                                        <p class="bg-faded p-2 rounded">
                                                                            {{ $cppt->instruksi ?? '-' }}</p>
                                                                        <strong>Evaluasi:</strong>
                                                                        <p class="bg-faded p-2 rounded">
                                                                            {{ $cppt->evaluasi ?? '-' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <a class="carousel-control-prev" href="#cpptCarousel" role="button"
                                                    data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#cpptCarousel" role="button"
                                                    data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="alert alert-info">Belum ada data CPPT untuk pasien ini.</div>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Pilihan Kategori Diet --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label text-right" for="search-menu">Pilih Kategori
                                        Diet</label>
                                    <div class="col-md-10">
                                        <select class="select2 form-control w-100" id="search-menu" required
                                            name="kategori_id">
                                            {{-- Nilai default dari data yang sudah ada --}}
                                            @php
                                                $selectedDiet = $registration->diet_gizi->kategori_id ?? null;
                                            @endphp
                                            <option value="" disabled {{ !$selectedDiet ? 'selected' : '' }}>Pilih
                                                salah satu...</option>
                                            {{-- Opsi untuk menghapus diet --}}
                                            <option value="-1" class="text-danger">Hapus Diet (Unset)</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $selectedDiet == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="help-block">Pilih "Hapus Diet" untuk membatalkan auto-order diet untuk
                                            pasien ini.</div>
                                    </div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                    <button type="button" class="btn btn-secondary" onclick="window.close()">Tutup</button>
                                    <button type="submit" id="order-submit" class="btn btn-primary ml-auto">Simpan</button>
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
    {{-- Hapus dependensi bootstrap.bundle.js dan jquery.js yang terpisah --}}
    {{-- Semua sudah ada di vendors.bundle.js dan app.bundle.js yang dimuat oleh layout utama --}}
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih salah satu...",
                // Dropdown akan muncul di dalam body, bukan di dalam popup, untuk menghindari masalah z-index
                dropdownParent: $('body')
            });

            // Anda bisa menambahkan validasi form di sini jika diperlukan
            // $('#form-pilih-diet').on('submit', function(e) { ... });
        });
    </script>
@endsection
