 <div class="row">
     <div class="col-xl-12">
         <div id="panel-1" class="panel">
             <div class="panel-hdr">
                 <h2>
                     Daftar <span class="fw-300"><i>Rekam Medis</i></span>
                 </h2>
             </div>
             <div class="panel-container show">
                 <div class="panel-content">
                     <!-- datatable start -->
                     <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                         <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                         <thead class="bg-primary-600">
                             <tr>
                                 <th>#</th>
                                 <th>Tanggal</th>
                                 <th>No. Registrasi</th>
                                 <th>Nama Lengkap</th>
                                 <th>Dokter</th>
                                 <th>Penjamin</th>
                                 <th>Diagnosa Awal</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach ($registrations as $registration)
                                 <tr>
                                     <td>{{ $loop->iteration }}</td>
                                     <td>
                                         @if ($registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $registration->date }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                     {{ $registration->date }}
                                                 </a>
                                             @endif
                                         @else
                                             <a
                                                 href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                 {{ $registration->date }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $registration->registration_number }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                     {{ $registration->registration_number }}
                                                 </a>
                                             @endif
                                         @else
                                             <a
                                                 href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                 {{ $registration->registration_number }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $registration->patient->name }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                     {{ $registration->patient->name }}
                                                 </a>
                                             @endif
                                         @else
                                             <a
                                                 href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                 {{ $registration->patient->name }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $registration->doctor->employee->fullname }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                     {{ $registration->doctor->employee->fullname }}
                                                 </a>
                                             @endif
                                         @else
                                             <a
                                                 href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                 {{ $registration->doctor->employee->fullname }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $registration->patient->penjamin->name ?? '-' }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                     {{ $registration->patient->penjamin->name ?? '-' }}
                                                 </a>
                                             @endif
                                         @else
                                             <a
                                                 href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                 {{ $registration->patient->penjamin->name ?? '-' }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $registration->diagnosa_awal }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                     {{ $registration->diagnosa_awal }}
                                                 </a>
                                             @endif
                                         @else
                                             <a
                                                 href="{{ route('detail.pendaftaran.pasien', $registration->patient->id) }}">
                                                 {{ $registration->diagnosa_awal }}
                                             </a>
                                         @endif
                                     </td>
                                 </tr>
                             @endforeach
                         </tbody>
                         <tfoot>
                             <tr>
                                 <th>#</th>
                                 <th>Tanggal</th>
                                 <th>No. Registrasi</th>
                                 <th>Nama Lengkap</th>
                                 <th>Dokter</th>
                                 <th>Penjamin</th>
                                 <th>Diagnosa Awal</th>
                             </tr>
                         </tfoot>
                     </table>
                     <!-- datatable end -->
                 </div>
             </div>
         </div>
     </div>
 </div>
