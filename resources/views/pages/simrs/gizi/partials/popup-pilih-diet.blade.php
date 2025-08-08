@extends('inc.layout-no-side')
@section('title', 'Pilih diet untuk auto order')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        .modal-dialog {
            max-width: 70%;
        }

        .borderless-input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0
        }

        .qty {
            width: 60px;
            margin-left: 10px;
        }

        textarea {
            height: fit-content;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Pilih diet untuk auto order
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-jam-makan-gizi"
                                name="form-jam-makan-gizi"action="/api/simrs/gizi/auto-diet/store/" method="GET">
                                @csrf
                                @method('GET')
                                <input type="hidden" name="_method" value="GET">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="untuk" value="pasien">
                                <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                                <div class="container">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="patient-name">Nama Pasien</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="patient-name"
                                                value="{{ $registration->patient->name }}" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="rm-reg">RM / No. Reg</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="rm-reg"
                                                value="{{ $registration->patient->medical_record_number }} / {{ $registration->registration_number }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="diagnosa-awal">Diagnosa Awal</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="diagnosa-awal"
                                                value="{{ $registration->diagnosa_awal }}" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="cppt">CPPT</label>
                                        </div>
                                        <div class="col-md-9">
                                            @if ($registration->cppt)
                                                <div id="cpptCarousel" class="carousel slide carousel-dark">
                                                    <div class="carousel-inner">
                                                        @foreach ($registration->cppt->sortByDesc('created_at') as $cppt)
                                                            <div
                                                                class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h1 class="card-title">
                                                                            [{{ $cppt->created_at->addHours(7)->format('d M Y, h.iA') }}]
                                                                            {{ $cppt->tipe_cppt }}</h1>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="card mb-3">
                                                                                    <div class="card-body">
                                                                                        <h4 class="card-title">Subjective
                                                                                        </h4>
                                                                                        <textarea readonly class="form-control">{{ $cppt->subjective }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card mb-3">
                                                                                    <div class="card-body">
                                                                                        <h4 class="card-title">Objective
                                                                                        </h4>
                                                                                        <textarea readonly class="form-control">{{ $cppt->objective }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card mb-3">
                                                                                    <div class="card-body">
                                                                                        <h4 class="card-title">Assesment
                                                                                        </h4>
                                                                                        <textarea readonly class="form-control">{{ $cppt->assesment }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="card mb-3">
                                                                                    <div class="card-body">
                                                                                        <h4 class="card-title">Planning</h4>
                                                                                        <textarea readonly class="form-control">{{ $cppt->planning }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card mb-3">
                                                                                    <div class="card-body">
                                                                                        <h4 class="card-title">Instruksi
                                                                                        </h4>
                                                                                        <textarea readonly class="form-control">{{ $cppt->instruksi }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card mb-3">
                                                                                    <div class="card-body">
                                                                                        <h4 class="card-title">Evaluasi</h4>
                                                                                        <textarea readonly class="form-control">{{ $cppt->evaluasi }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button class="carousel-control-prev" style="font: black" type="button"
                                                        data-bs-target="#cpptCarousel" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" style="font: black"
                                                        type="button" data-bs-target="#cpptCarousel"
                                                        data-bs-slide="next">
                                                        <span class="carousel-control-next-icon"
                                                            aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                </div>
                                            @else
                                                <h1>Belum ada CPPT!</h1>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="search-menu">Pilih Kategori Diet</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="select2 form-control w-100" id="search-menu" required
                                                name="kategori_id">
                                                <option value=""></option>
                                                <option value="-1">Unset</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12 mt-5">
                                    <div class="row">
                                        <div class="col-xl">
                                            <a onclick="window.close()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-xl text-right">
                                            <button type="submit" id="order-submit"
                                                class="btn btn-lg btn-primary waves-effect waves-themed">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/jquery.js') }}"></script>
@endsection
