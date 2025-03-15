   <div class="row justify-content-center">
       <div class="col-xl-8">
           <div id="panel-1" class="panel">
               <div class="panel-hdr">
                   <h2>
                       Form <span class="fw-300"><i>Pencarian</i></span>
                   </h2>
               </div>
               <div class="panel-container show">
                   <div class="panel-content">

                       <form action="{{ route('igd.daftar-pasien') }}" method="get">
                           @csrf
                           <div class="row justify-content-center">
                               <div class="col-xl-4">
                                   <div class="form-group">
                                       <div class="row">
                                           <div class="col-xl-4" style="text-align: right">
                                               <label for="registration_date">Tgl. Registrasi</label>
                                           </div>
                                           <div class="col-xl">
                                               <div class="form-group row">
                                                   <div class="col-xl ">
                                                       <input type="text" class="form-control" id="datepicker-1"
                                                           style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                           placeholder="Select date" name="registration_date"
                                                           value="01/01/2018 - 01/15/2018">
                                                   </div>
                                               </div>
                                               @error('registration_date')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                               @enderror
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-xl-4">
                                   <div class="form-group">
                                       <div class="row">
                                           <div class="col-xl-5" style="text-align: right">
                                               <label class="form-label text-end" for="medical_record_number">
                                                   No. RM
                                               </label>
                                           </div>
                                           <div class="col-xl">
                                               <input type="text" value="{{ request('medical_record_number') }}"
                                                   style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                   class="form-control" id="medical_record_number"
                                                   name="medical_record_number" onkeyup="formatAngka(this)">
                                               @error('medical_record_number')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                               @enderror
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-xl-4">
                                   <div class="form-group">
                                       <div class="row">
                                           <div class="col-xl-5" style="text-align: right">
                                               <label class="form-label text-end" for="registration_name">
                                                   Nama Pasien
                                               </label>
                                           </div>
                                           <div class="col-xl">
                                               <input type="text" value="{{ request('name') }}"
                                                   style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                   class="form-control" id="name" name="name">
                                               @error('name')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                               @enderror
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="row justify-content-center mt-4">
                               <div class="col-xl-4">
                                   <div class="form-group">
                                       <div class="row">
                                           <div class="col-xl-5" style="text-align: right">
                                               <label class="form-label text-end" for="registration_number">
                                                   No. Registrasi
                                               </label>
                                           </div>
                                           <div class="col-xl">
                                               <input type="text" value="{{ request('registration_number') }}"
                                                   style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                   class="form-control" id="registration_number"
                                                   name="registration_number" onkeyup="formatAngka(this)">
                                               @error('registration_number')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                               @enderror
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-xl-4">
                                   <div class="form-group">
                                       <div class="row">
                                           <div class="col-xl-4" style="text-align: right">
                                               <label for="status">Status</label>
                                           </div>
                                           <div class="col-xl">
                                               <select class="form-control w-100 select2" id="status"
                                                   style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                   name="status">
                                                   <option value=""></option>
                                                   <option value="aktif">Registrasi Aktif</option>
                                                   <option value="tutup">Tutup Kunjungan</option>
                                                   <option value="all" selected>All</option>
                                               </select>
                                               @error('status')
                                                   <div class="invalid-feedback">{{ $message }}</div>
                                               @enderror
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="row justify-content-end mt-3">
                               <div class="col-xl-3">
                                   <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                       <span class="fal fa-search mr-1"></span>
                                       Cari
                                   </button>
                               </div>
                           </div>
                       </form>

                   </div>
               </div>
           </div>
       </div>
   </div>
