 <div class="row">
     <div class="col-xl-12">
         <div id="panel-1" class="panel">
             <div class="panel-hdr">
                 <h2>
                     Daftar <span class="fw-300"><i>Order Radiologi</i></span>
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
                                 <th>No. RM</th>
                                 <th>No. Registrasi</th>
                                 <th>No. Order</th>
                                 <th>Nama Lengkap</th>
                                 <th>Poly / Ruang</th>
                                 <th>Penjamin</th>
                                 <th>Dokter</th>
                                 <th>Status Isi Hasil</th>
                                 <th>Status Billed</th>
                                 <th>Action</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach ($orders as $order)
                                 <tr>
                                     <td>{{ $loop->iteration }}</td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->order_date }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->order_date }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->order_date }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->registration->patient->medical_record_number }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->registration->patient->medical_record_number }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->registration->patient->medical_record_number }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->registration->registration_number }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->registration->registration_number }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->registration->registration_number }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->no_order }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->no_order }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->no_order }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->registration->patient->name }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->registration->patient->name }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->registration->patient->name }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->registration->poliklinik }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->registration->poliklinik }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->registration->poliklinik }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->registration->patient->penjamin->name ?? '-' }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->registration->patient->penjamin->name ?? '-' }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->registration->patient->penjamin->name ?? '-' }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->doctor->employee->fullname }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->doctor->employee->fullname }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->doctor->employee->fullname }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->status_isi_hasil == 1 ? 'Finished' : 'Ongoing' }}
                                             </a>
                                         @endif
                                     </td>
                                     <td>
                                         @if ($order->registration->patient->orderBy('created_at', 'desc')->first() !== null)
                                             @if ($order->registration->patient->orderBy('created_at', 'desc')->first()->status === 'aktif')
                                                 <a
                                                     href="{{ route('detail.registrasi.pasien', $order->registration->patient->orderBy('created_at', 'desc')->first()->id) }}">
                                                     {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                                 </a>
                                             @else
                                                 <a
                                                     href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                     {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                                 </a>
                                             @endif
                                         @else
                                             <a href="{{ route('detail.pendaftaran.pasien', $order->registration->patient->id) }}">
                                                 {{ $order->status_billed == 1 ? 'Billed' : 'Not Billed' }}
                                             </a>
                                         @endif
                                     </td>
                                     <td> - </td>
                                 </tr>
                             @endforeach
                         </tbody>
                         <tfoot>
                             <tr>
                                 <th>#</th>
                                 <th>Tanggal</th>
                                 <th>No. RM</th>
                                 <th>No. Registrasi</th>
                                 <th>No. Order</th>
                                 <th>Nama Lengkap</th>
                                 <th>Poly / Ruang</th>
                                 <th>Penjamin</th>
                                 <th>Dokter</th>
                                 <th>Status Isi Hasil</th>
                                 <th>Status Billed</th>
                                 <th>Action</th>
                             </tr>
                         </tfoot>
                     </table>
                     <!-- datatable end -->
                 </div>
             </div>
         </div>
     </div>
 </div>
