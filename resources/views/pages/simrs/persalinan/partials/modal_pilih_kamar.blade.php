   <div class="modal fade" id="modal-pilih-kamar-bayi" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog modal-lg" style="max-width: 80vw" role="document">
           <div class="modal-content">
               <div class="modal-header bg-primary-600">
                   <h5 class="modal-title">Pilih Kelas / Kamar Rawat untuk Bayi</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true"><i class="fal fa-times"></i></span>
                   </button>
               </div>
               <div class="modal-body">
                   <div class="row justify-content-center">
                       <div class="col-lg-6">
                           <div class="card m-auto border">
                               <div class="card-header py-2 bg-primary-600">
                                   <div class="card-title">Form Pencarian</div>
                               </div>
                               <div class="card-body">
                                   <div class="form-group">
                                       <label class="form-label" for="kelas_rawat_id_bayi">Kelas Rawat</label>
                                       <select class="form-control w-100" id="kelas_rawat_id_bayi">
                                           <option value="">-- Semua Kelas --</option>
                                       </select>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="row mt-4">
                       <div class="col-12">
                           <table id="dt-kamar-bayi-table" style="width: 100%;"
                               class="table table-bordered table-striped">
                               <thead>
                                   <tr>
                                       <th>Ruangan</th>
                                       <th>Kelas</th>
                                       <th>T. Tidur</th>
                                       <th>Status</th>
                                       <th>Fungsi</th>
                                   </tr>
                               </thead>
                               <tbody></tbody>
                           </table>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
