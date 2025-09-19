  {{-- CSS untuk child row agar terlihat lebih rapi --}}
  <style>
      /* Memberi padding dan background pada child row */
      tr.details-shown>td {
          padding: 0 !important;
          border-bottom: 2px solid #3c6eb4 !important;
      }

      .child-table {
          width: 95%;
          margin: 10px auto;
      }

      .child-table thead {
          background-color: #eef3f9;
      }
  </style>

  {{-- BAGIAN 1: DAFTAR ORDER LABORATORIUM YANG SUDAH ADA --}}
  <div class="panel" id="panel-laboratorium-list">
      <div class="panel-hdr">
          <h2>
              <i class="fal fa-notes-medical mr-2"></i> Daftar Order Laboratorium
          </h2>
          <div class="panel-toolbar">
              <button class="btn btn-primary btn-sm" id="btn-show-lab-form">
                  <i class="fal fa-plus mr-1"></i> Buat Order Baru
              </button>
          </div>
      </div>
      <div class="panel-container show">
          <div class="panel-content">
              <table id="dt-lab-orders" class="table table-bordered table-hover table-striped w-100">
                  <thead class="bg-primary-600">
                      <tr>
                          <th class="text-center" style="width: 30px;">#</th> {{-- Kolom untuk Ikon, dibuat tidak bisa di-sort --}}
                          <th>Tgl Order</th>
                          <th>No. Order</th>
                          <th>Dokter Lab</th>
                          <th>Tipe</th>
                          <th>Status Hasil</th>
                          <th>Status Billing</th>
                          <th style="width: 50px;">Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($laboratoriumOrders as $order)
                          {{-- Atribut data-details tetap sama --}}
                          <tr data-details="{{ json_encode($order->order_parameter_laboratorium) }}">
                              {{-- Kolom ini sekarang secara eksplisit berisi ikon --}}
                              <td class="text-center details-control">
                                  {{-- Ikon FontAwesome yang bisa diklik. Sesuaikan dengan style Anda jika perlu --}}
                                  <i class="fal fa-plus-circle text-success" style="cursor: pointer;"></i>
                              </td>
                              <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i') }}</td>
                              <td>{{ $order->no_order }}</td>
                              <td>{{ $order->doctor->employee->fullname ?? 'N/A' }}</td>
                              <td>
                                  @if ($order->is_cito)
                                      <span class="badge badge-danger">CITO</span>
                                  @else
                                      <span class="badge badge-primary">Normal</span>
                                  @endif
                              </td>
                              <td>
                                  @if ($order->status_isi_hasil == 1)
                                      <span class="badge badge-success">Selesai</span>
                                  @else
                                      <span class="badge badge-warning">Proses</span>
                                  @endif
                              </td>
                              <td>
                                  @if ($order->status_billed == 1)
                                      <span class="badge badge-success">Sudah Ditagih</span>
                                  @else
                                      <span class="badge badge-secondary">Belum Ditagih</span>
                                  @endif
                              </td>
                              <td class="text-center">
                                  <a href="#" class="btn btn-xs btn-outline-primary" data-toggle="tooltip"
                                      title="Cetak Hasil">
                                      <i class="fal fa-print"></i>
                                  </a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>

  {{-- BAGIAN 2: FORM UNTUK MEMBUAT ORDER BARU --}}
  <div class="panel" id="panel-laboratorium-form" style="display: none;">
      @include('pages.simrs.pendaftaran.partials.order-laboratorium')
  </div>
